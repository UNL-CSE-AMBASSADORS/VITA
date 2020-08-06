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
		case 'addAppointmentTime': addAppointmentTime($_POST['siteId'], $_POST['date'], $_POST['scheduledTime'], $_POST['minimumNumberOfAppointments'], $_POST['maximumNumberOfAppointments'], $_POST['percentageAppointments'], $_POST['approximateLengthInMinutes']); break;
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
		$query = 'SELECT siteId, title, address, phoneNumber, doesInternational
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

	$query = 'SELECT appointmentTimeId, scheduledTime, 
			DATE_FORMAT(scheduledTime, "%b %e, %Y, %l:%i %p (%W)") AS scheduledTimeString, 
			minimumNumberOfAppointments, maximumNumberOfAppointments, percentageAppointments, approximateLengthInMinutes
		FROM AppointmentTime
		WHERE siteId = ?
			AND YEAR(scheduledTime) = ?
		ORDER BY AppointmentTime.scheduledTime';
	$stmt = $DB_CONN->prepare($query);
	$stmt->execute(array($siteId, $year));

	$appointmentTimes = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $appointmentTimes;
}

function addAppointmentTime($siteId, $dateString, $scheduledTimeString, $minimumNumberOfAppointments, $maximumNumberOfAppointments, $percentageAppointments, $approximateLengthInMinutes) {
	GLOBAL $DB_CONN, $USER;

	$response = array();
	$response['success'] = true;

	try {
		// Validate inputs
		if (!isset($siteId)) throw new Exception('Invalid site Id given', MY_EXCEPTION);
		if (!isset($dateString) || !DateTimeUtilities::isValidDateString($dateString, DateFormats::MM_DD_YYYY)) throw new Exception('Invalid date given', MY_EXCEPTION);
		if (!isset($scheduledTimeString) || !DateTimeUtilities::isValidTimeString($scheduledTimeString, TimeFormats::HH_MM_PERIOD)) throw new Exception('Invalid start time given', MY_EXCEPTION);
		if (isset($minimumNumberOfAppointments) && (int)$minimumNumberOfAppointments < 0) throw new Exception('Invalid minimum number of appointments given', MY_EXCEPTION);
		if (isset($maximumNumberOfAppointments) && (int)$maximumNumberOfAppointments < 0) throw new Exception('Invalid maximum number of appointments given', MY_EXCEPTION);
		if (isset($minimumNumberOfAppointments) && isset($maximumNumberOfAppointments)) {
			if ((int)$minimumNumberOfAppointments > (int)$maximumNumberOfAppointments) throw new Exception('Minimum number of appointments cannot be greater than maximum number of appointments', MY_EXCEPTION);
		}

		if (!isset($percentageAppointments)) throw new Exception('Invalid percent appointments given', MY_EXCEPTION);
		if (isset($percentageAppointments) && ((int)$percentageAppointments < 0 || (int)$percentageAppointments > 300)) throw new Exception('Invalid percent appointments given', MY_EXCEPTION);

		if (!isset($approximateLengthInMinutes)) throw new Exception('Invalid approximate length in minutes given', MY_EXCEPTION);
		if (isset($approximateLengthInMinutes) && ((int)$approximateLengthInMinutes < 0)) throw new Exception('Invalid approximate length in minutes given', MY_EXCEPTION);

		// Get variables into proper format for db
		$scheduledTime = DateTime::createFromFormat('m-d-Y g:i A', $dateString . ' ' . $scheduledTimeString);
		$scheduledTimeForDb = $scheduledTime->format('Y-m-d H:i:s'); // i.e. 2018-01-21 13:00:00
		if (!isset($minimumNumberOfAppointments)) $minimumNumberOfAppointments = null;
		if (!isset($maximumNumberOfAppointments)) $maximumNumberOfAppointments = null;

		// Ensure it is a valid appointment time given the rules
		$isValidAppointmentTime = isValidAppointmentTime($siteId, $scheduledTimeForDb, $approximateLengthInMinutes);
		if ($isValidAppointmentTime['valid'] === false) {
			throw new Exception($isValidAppointmentTime['reason'], MY_EXCEPTION);
		}


		// Insert into DB
		$insertParams = array($siteId, $scheduledTimeForDb, $minimumNumberOfAppointments, $percentageAppointments, $approximateLengthInMinutes);
		$query = 'INSERT INTO AppointmentTime (siteId, scheduledTime, minimumNumberOfAppointments, 
				percentageAppointments, approximateLengthInMinutes';
		if ($maximumNumberOfAppointments != null) {
			$query .= ', maximumNumberOfAppointments) VALUES (?, ?, ?, ?, ?, ?)';
			array_push($insertParams, $maximumNumberOfAppointments);
		} else {
			$query .= ') VALUES (?, ?, ?, ?, ?)';
		}
		$stmt = $DB_CONN->prepare($query);
		$stmt->execute($insertParams);

		$response['appointmentTimeId'] = $DB_CONN->lastInsertId();
	} catch (Exception $e) {
		$response['success'] = false;
		$response['error'] = $e->getCode() === MY_EXCEPTION ? $e->getMessage() : 'There was an error on the server adding the appointment time. Please refresh the page and try again.';
	}

	echo json_encode($response);
}

// Checks to ensure the given appointment time values satisfy the following three conditions:
/*
	1. Two appointment times cannot have the same scheduled time
*/
function isValidAppointmentTime($siteId, $scheduledTime, $approximateLengthInMinutes) {
	GLOBAL $DB_CONN;

	// Checks 1
	$appointmentTimeAlreadyExists = doesAppointmentTimeAlreadyExistWithScheduledTime($siteId, $scheduledTime);
	if ($appointmentTimeAlreadyExists) {
		return array('valid' => false, 'reason' => 'An appointment time with this scheduled time already exists at this site');
	}

	return array('valid' => true);
}

function doesAppointmentTimeAlreadyExistWithScheduledTime($siteId, $scheduledTime) {
	GLOBAL $DB_CONN;

	$query = 'SELECT 1 FROM AppointmentTime
		WHERE siteId = ? AND scheduledTime = ?';
	$stmt = $DB_CONN->prepare($query);
	$stmt->execute(array($siteId, $scheduledTime));

	$appointmentTimeAlreadyExists = (bool)$stmt->fetch() !== false;
	return $appointmentTimeAlreadyExists;
}
