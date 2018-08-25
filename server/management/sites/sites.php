<?php

$root = realpath($_SERVER['DOCUMENT_ROOT']);
require_once "$root/server/user.class.php";
$USER = new User();
if (!$USER->isLoggedIn()) {
	header('Location: /unauthorized');
	die();
}

require_once "$root/server/config.php";

if (isset($_REQUEST['action'])) {
	switch ($_REQUEST['action']) {
		case 'getSiteInformation': getSiteInformation($_GET['siteId']); break;
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
		$response['site']['appointmentTimes'] = getAppointmentTimesForShift($siteId);
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

	$query = 'SELECT shiftId, DATE_FORMAT(startTime, "%b %e, %Y") AS date, 
			TIME_FORMAT(startTime, "%l:%i %p") AS startTime, TIME_FORMAT(endTime, "%l:%i %p") AS endTime
		FROM Shift
		WHERE siteId = ?
			AND YEAR(startTime) = ?
			AND archived = FALSE';
	$stmt = $DB_CONN->prepare($query);
	$stmt->execute(array($siteId, $year));

	$shifts = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $shifts;
}

function getAppointmentTimesForShift($siteId, $year = null) {
	GLOBAL $DB_CONN;

	if (!isset($year)) {
		date_default_timezone_set('America/Chicago');
		$year = date('Y');
	}

	$query = 'SELECT appointmentTimeId, scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments,
			percentageAppointments, approximateLengthInMinutes
		FROM AppointmentTime
		WHERE siteId = ?
			AND YEAR(scheduledTime) = ?';
	$stmt = $DB_CONN->prepare($query);
	$stmt->execute(array($siteId, $year));

	$appointmentTimes = $stmt->fetchAll(PDO::FETCH_ASSOC);
	return $appointmentTimes;
}