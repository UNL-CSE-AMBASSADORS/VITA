<?php

error_reporting(E_ALL);
ini_set('display_errors', 'On');

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once "$root/server/config.php";

function calculateRemainingAppointmentsAvailable($volunteerCount, $appointmentCount, $percentAppointments) {
	$availableAppointmentSpots = $volunteerCount * $percentAppointments / 100;
	if ($appointmentCount < $availableAppointmentSpots) {
		return $availableAppointmentSpots - $appointmentCount;
	}
	return 0;
}

class DateSiteTimeMap {
	public $dates = [];

	public function addDateSiteTimeObject($dstObject) {
		// Get the number of appointments still available
		$appointmentsAvailable = calculateRemainingAppointmentsAvailable($dstObject['numberOfVolunteers'], $dstObject['numberOfAppointmentsAlreadyMade'], $dstObject['percentageAppointments']);

		// Add the appointmentTime to the map
		$this->dates[$dstObject['scheduledDate']][$dstObject['siteId']][$dstObject['scheduledTime']] = $appointmentsAvailable;
	}
}

getAppointmentTimes($_GET);

/*
 * The fields that will be returned are: appointmentTimeId, siteId, scheduledDate,  scheduledTime, percentageAppointments, numberOfAppointmentsAlreadyMade, numberOfVolunteers
 */
function getAppointmentTimes($data) {
	GLOBAL $DB_CONN;

	date_default_timezone_set('America/Chicago');

	$year = date("Y");
	if (isset($data['year'])) {
		$year = $data['year'];
	}

	$stmt = $DB_CONN->prepare('SELECT at.appointmentTimeId, at.siteId, DATE(scheduledTime) AS scheduledDate, TIME(scheduledTime) AS scheduledTime, percentageAppointments, COUNT(DISTINCT a.appointmentId) AS numberOfAppointmentsAlreadyMade, COUNT(DISTINCT us.userShiftId) AS numberOfVolunteers
		FROM AppointmentTime at
		LEFT JOIN Appointment a ON a.appointmentTimeId = at.appointmentTimeId
		LEFT JOIN UserShift us ON us.shiftId IN (SELECT s.shiftId FROM Shift s WHERE s.siteId = at.siteId AND s.startTime <= at.scheduledTime AND s.endTime >= at.scheduledTime ORDER BY s.startTime DESC)
		WHERE YEAR(at.scheduledTime) = ?
		GROUP BY at.appointmentTimeId
		ORDER BY at.scheduledTime');
	$stmt->execute(array($year));
	$appointmentTimes = $stmt->fetchAll(PDO::FETCH_ASSOC);

	$dstMap = new DateSiteTimeMap();

	foreach ($appointmentTimes as $appointmentTime) {
		$dstMap->addDateSiteTimeObject($appointmentTime);
	}

	echo json_encode($dstMap);
}
