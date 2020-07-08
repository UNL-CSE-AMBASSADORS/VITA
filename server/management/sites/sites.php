<?php

$root = realpath($_SERVER['DOCUMENT_ROOT']);
require_once "$root/server/user.class.php";
$USER = new User();
if (!$USER->isLoggedIn()) {
	header('Location: /unauthorized');
	die();
}

require_once "$root/server/config.php";
require_once "$root/server/utilities/dateTimeUtilities.class.php";

if (isset($_REQUEST['action'])) {
	switch ($_REQUEST['action']) {
		case 'getSiteInformation': getSiteInformation($_GET['siteId']); break;
		case 'getAppointmentTimes': getAppointmentTimes($_GET['siteId']); break;
		case 'addAppointmentTime': addAppointmentTime($_POST['siteId'], $_POST['date'], $_POST['scheduledTime'], $_POST['appointmentTypeId'], $_POST['numberOfAppointments']); break;
		case 'updateAppointmentTime': updateAppointmentTime($_POST['appointmentTimeId'], $_POST['numberOfAppointments']); break;
		default:
			die('Invalid action function. This instance has been reported.');
			break;
	}
}



function getSiteInformation($siteId) {
	GLOBAL $DB_CONN;

	$response = array();
	$response['success'] = true;

	try {
		$query = 'SELECT siteId, title, address, phoneNumber
			FROM Site
			WHERE siteId = ? AND archived = FALSE';
		$stmt = $DB_CONN->prepare($query);
		$stmt->execute(array($siteId));

		$site = $stmt->fetch(PDO::FETCH_ASSOC);
		$response['site'] = $site;
		$response['site']['appointmentTimes'] = getAppointmentTimesForSite($siteId);
	} catch (Exception $e) {
		$response['success'] = false;
		$response['error'] = 'There was an error on the server retrieving site information. Please try again later.';
	}

	echo json_encode($response);
}

function getAppointmentTimes($siteId) {
	GLOBAL $DB_CONN;

	$response = array();
	$response['success'] = true;

	try {
		$response['appointmentTimes'] = getAppointmentTimesForSite($siteId);
	} catch (Exception $e) {
		$response['success'] = false;
		$response['error'] = 'There was an error on the server retrieving appointment times. Please try again later.';
	}

	echo json_encode($response);
}

function getAppointmentTimesForSite($siteId, $year = null) {
	GLOBAL $DB_CONN;

	if (!isset($year)) {
		date_default_timezone_set('America/Chicago');
		$year = date('Y');
	}

	$query = 'SELECT AppointmentTime.appointmentTimeId, scheduledTime, 
			DATE_FORMAT(scheduledTime, "%b %e, %Y (%W)") AS scheduledDateString,
			TIME_FORMAT(scheduledTime, "%l:%i %p") AS scheduledTimeString,
			numberOfAppointments, AppointmentType.lookupName AS appointmentType,
			AppointmentType.name AS appointmentTypeName,
			COUNT(DISTINCT Appointment.appointmentId) AS numberOfAppointmentsAlreadyScheduled
		FROM AppointmentTime
		JOIN AppointmentType ON AppointmentTime.appointmentTypeId = AppointmentType.appointmentTypeId
		LEFT JOIN Appointment ON AppointmentTime.appointmentTimeId = Appointment.appointmentTimeId
		WHERE siteId = ?
			AND YEAR(scheduledTime) = ?
		GROUP BY AppointmentTime.appointmentTimeId
		ORDER BY AppointmentTime.scheduledTime;';
	$stmt = $DB_CONN->prepare($query);
	$stmt->execute(array($siteId, $year));

	$appointmentTimes = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $appointmentTimes;
}

function addAppointmentTime($siteId, $dateString, $scheduledTimeString, $appointmentTypeId, $numberOfAppointments) {
	GLOBAL $DB_CONN, $USER;

	$response = array();
	$response['success'] = true;

	try {
		// Validate inputs
		if (!isset($siteId)) throw new Exception('Invalid site ID given', MY_EXCEPTION);
		if (!isset($dateString) || !DateTimeUtilities::isValidDateString($dateString, DateFormats::MM_DD_YYYY)) throw new Exception('Invalid date given', MY_EXCEPTION);
		if (!isset($scheduledTimeString) || !DateTimeUtilities::isValidTimeString($scheduledTimeString, TimeFormats::HH_MM_PERIOD)) throw new Exception('Invalid start time given', MY_EXCEPTION);
		if (!isset($appointmentTypeId)) throw new Exception('Invalid appointment type ID given', MY_EXCEPTION);
		if (isset($numberOfAppointments) && (int)$numberOfAppointments < 0) throw new Exception('Invalid minimum number of appointments given', MY_EXCEPTION);

		// Get variables into proper format for db
		$scheduledTime = DateTime::createFromFormat('m-d-Y g:i A', $dateString . ' ' . $scheduledTimeString);
		$scheduledTimeForDb = $scheduledTime->format('Y-m-d H:i:s'); // i.e. 2018-01-21 13:00:00

		// Ensure it is a valid appointment time given the rules
		$isValidAppointmentTime = isValidAppointmentTime($siteId, $scheduledTimeForDb, $appointmentTypeId);
		if ($isValidAppointmentTime['valid'] === false) {
			throw new Exception($isValidAppointmentTime['reason'], MY_EXCEPTION);
		}

		// Insert into DB
		$query = 'INSERT INTO AppointmentTime (siteId, scheduledTime, appointmentTypeId, numberOfAppointments)
			VALUES (?, ?, ?, ?);';
		$stmt = $DB_CONN->prepare($query);
		$stmt->execute(array($siteId, $scheduledTimeForDb, $appointmentTypeId, $numberOfAppointments));

		$response['appointmentTimeId'] = $DB_CONN->lastInsertId();
	} catch (Exception $e) {
		$response['success'] = false;
		$response['error'] = $e->getCode() === MY_EXCEPTION ? $e->getMessage() : 'There was an error on the server adding the appointment time. Please refresh the page and try again.';
	}

	echo json_encode($response);
}

function updateAppointmentTime($appointmentTimeId, $numberOfAppointments) {
	GLOBAL $DB_CONN;

	$response = array();
	$response['success'] = true;

	try {
		// Validate inputs
		if (!isset($appointmentTimeId)) throw new Exception('Invalid appointment time ID given', MY_EXCEPTION);
		if (!isset($numberOfAppointments) || $numberOfAppointments < 0) throw new Exception('Invalid number of appointments given', MY_EXCEPTION);

		// Insert into DB
		$query = 'UPDATE AppointmentTime
			SET numberOfAppointments = ?
			WHERE appointmentTimeId = ?;';
		$stmt = $DB_CONN->prepare($query);
		$stmt->execute(array($numberOfAppointments, $appointmentTimeId));
	} catch (Exception $e) {
		$response['success'] = false;
		$response['error'] = $e->getCode() === MY_EXCEPTION ? $e->getMessage() : 'There was an error on the server adding the appointment time. Please refresh the page and try again.';
	}

	echo json_encode($response);
}

// Checks to ensure the given appointment time values satisfy the following conditions:
/*
	1. Two appointment times cannot have the same scheduled time
*/
function isValidAppointmentTime($siteId, $scheduledTime, $appointmentTypeId) {
	GLOBAL $DB_CONN;

	// Checks 1
	$appointmentTimeAlreadyExists = doesAppointmentTimeAlreadyExistWithScheduledTimeAndType($siteId, $scheduledTime, $appointmentTypeId);
	if ($appointmentTimeAlreadyExists) {
		return array('valid' => false, 'reason' => 'An appointment time with this scheduled time and appointment type already exists at this site');
	}

	return array('valid' => true);
}

function doesAppointmentTimeAlreadyExistWithScheduledTimeAndType($siteId, $scheduledTime, $appointmentTypeId) {
	GLOBAL $DB_CONN;

	$query = 'SELECT 1 FROM AppointmentTime
		WHERE siteId = ? AND scheduledTime = ? AND appointmentTypeId = ?';
	$stmt = $DB_CONN->prepare($query);
	$stmt->execute(array($siteId, $scheduledTime, $appointmentTypeId));

	$appointmentTimeAlreadyExists = (bool)$stmt->fetch() !== false;
	return $appointmentTimeAlreadyExists;
}
