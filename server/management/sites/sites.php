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
		case 'addShift': addShift($_POST['siteId'], $_POST['date'], $_POST['startTime'], $_POST['endTime']); break;
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
		$query = 'SELECT siteId, title, address, phoneNumber, doesMultilingual, doesInternational
			FROM Site
			WHERE siteId = ? AND archived = FALSE';
		$stmt = $DB_CONN->prepare($query);
		$stmt->execute(array($siteId));

		$site = $stmt->fetch(PDO::FETCH_ASSOC);
		$response['site'] = $site;
		$response['site']['shifts'] = getShiftsForSite($siteId);
		$response['site']['appointmentTimes'] = getAppointmentTimesForSite($siteId);
	} catch (Exception $e) {
		$response['success'] = false;
		$response['error'] = 'There was an error on the server retrieving site information. Please try again later.';
	}

	echo json_encode($response);
}

function getShiftsForSite($siteId, $year = null) {
	GLOBAL $DB_CONN;

	if (!isset($year)) {
		date_default_timezone_set('America/Chicago');
		$year = date('Y');
	}

	$query = 'SELECT shiftId, DATE_FORMAT(startTime, "%b %e, %Y (%W)") AS date, 
			TIME_FORMAT(startTime, "%l:%i %p") AS startTime, TIME_FORMAT(endTime, "%l:%i %p") AS endTime
		FROM Shift
		WHERE siteId = ?
			AND YEAR(startTime) = ?
			AND archived = FALSE
		ORDER BY Shift.startTime';
	$stmt = $DB_CONN->prepare($query);
	$stmt->execute(array($siteId, $year));

	$shifts = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $shifts;
}

function getAppointmentTimesForSite($siteId, $year = null) {
	GLOBAL $DB_CONN;

	if (!isset($year)) {
		date_default_timezone_set('America/Chicago');
		$year = date('Y');
	}

	$query = 'SELECT appointmentTimeId, DATE_FORMAT(scheduledTime, "%b %e, %Y, %l:%i %p (%W)") AS scheduledTime, 
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

function addShift($siteId, $dateString, $startTimeString, $endTimeString) {
	GLOBAL $DB_CONN, $USER;

	$response = array();
	$response['success'] = true;

	try {
		if (!isset($dateString) || !DateTimeUtilities::isValidDateString($dateString, DateFormats::MM_DD_YYYY)) throw new Exception('Invalid date given', MY_EXCEPTION);
		if (!isset($startTimeString) || !DateTimeUtilities::isValidTimeString($startTimeString, TimeFormats::HH_MM_PERIOD)) throw new Exception('Invalid start time given', MY_EXCEPTION);
		if (!isset($endTimeString) || !DateTimeUtilities::isValidTimeString($endTimeString, TimeFormats::HH_MM_PERIOD)) throw new Exception('Invalid end time given', MY_EXCEPTION);

		$startTime = DateTime::createFromFormat('m-d-Y g:i A', $dateString . ' ' . $startTimeString);
		$endTime = DateTime::createFromFormat('m-d-Y g:i A', $dateString . ' ' . $endTimeString);

		if ($startTime >= $endTime) {
			throw new Exception('End time cannot be before or same as start time', MY_EXCEPTION);
		}

		//Get into format for db, i.e. 2018-01-21 13:00:00
		$startTimeForDb = $startTime->format('Y-m-d H:i:s');
		$endTimeForDb = $endTime->format('Y-m-d H:i:s');

		$userId = $USER->getUserId();

		$query = 'INSERT INTO Shift (siteId, startTime, endTime, createdBy, lastModifiedBy)
			VALUES (?, ?, ?, ?, ?)';
		$stmt = $DB_CONN->prepare($query);
		$stmt->execute(array($siteId, $startTimeForDb, $endTimeForDb, $userId, $userId));
	} catch (Exception $e) {
		$response['success'] = false;
		$response['error'] = $e->getCode() === MY_EXCEPTION ? $e->getMessage() : 'There was an error on the server adding the shift. Please refresh the page and try again.';
	}

	echo json_encode($response);
}
