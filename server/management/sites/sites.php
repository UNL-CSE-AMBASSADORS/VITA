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

function addShift($siteId, $dateString, $startTimeString, $endTimeString) {
	$response = array();
	$response['success'] = true;

	try {
		$dateParts = DateTimeUtilities::extractDateParts($dateString, DateFormats::MM_DD_YYYY);
		$startTimeParts = DateTimeUtilities::extractTimeParts($startTimeString, TimeFormats::HH_MM_PERIOD);
		$endTimeParts = DateTimeUtilities::extractTimeParts($endTimeString, TimeFormats::HH_MM_PERIOD);

		$endTimeParts = array(
			'hours' => '08',
			'minutes' => '00',
			'seconds' => '00'
		);

		$timeComparison = DateTimeUtilities::compareTimeParts($startTimeParts, $endTimeParts);
		if ($timeComparison & ComparerResults::EQUAL == true || $timeComparison & ComparerResults::GREATER == true) {
			throw new Exception('End time cannot be before or same as start time', MY_EXCEPTION);
		}

		echo json_encode($startTimeParts);
		die();
	} catch (Exception $e) {
		$response['success'] = false;
		$response['error'] = $e->getCode() === MY_EXCEPTION ? $e->getMessage() : 'There was an error on the server adding the shift. Please refresh the page and try again.';
	}

	echo json_encode($response);
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

function getAppointmentTimesForShift($siteId, $year = null) {
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