<?php

error_reporting(E_ALL);
ini_set('display_errors', 'On');

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once "$root/server/config.php";

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

	$stmt = $DB_CONN->prepare('SELECT apt.appointmentTimeId, apt.siteId, DATE(scheduledTime) AS scheduledDate, TIME(scheduledTime) AS scheduledTime, percentageAppointments, COUNT(DISTINCT a.appointmentId) AS numberOfAppointmentsAlreadyMade, COUNT(DISTINCT us.userShiftId) AS numberOfVolunteers, apt.maximumNumberOfAppointments
		FROM AppointmentTime apt
		LEFT JOIN Appointment a ON a.appointmentTimeId = apt.appointmentTimeId
		LEFT JOIN UserShift us ON us.shiftId IN (SELECT s.shiftId FROM Shift s WHERE s.siteId = apt.siteId AND s.startTime <= apt.scheduledTime AND s.endTime >= apt.scheduledTime ORDER BY s.startTime DESC)
		WHERE YEAR(apt.scheduledTime) = ?
		GROUP BY apt.appointmentTimeId
		ORDER BY apt.scheduledTime');
	$stmt->execute(array($year));
	$appointmentTimes = $stmt->fetchAll(PDO::FETCH_ASSOC);

	$dstMap = new DateSiteTimeMap();

	foreach ($appointmentTimes as $appointmentTime) {
		$dstMap->addDateSiteTimeObject($appointmentTime);
	}

	echo json_encode($dstMap);
}

function calculateRemainingAppointmentsAvailable($appointmentCount, $percentAppointments, $volunteerCount, $maximum) {
	if (isset($maximum)) {
		$availableAppointmentSpots = $maximum;
	} else {
		$availableAppointmentSpots = $volunteerCount * $percentAppointments / 100;
	}
	if ($appointmentCount < $availableAppointmentSpots) {
		return $availableAppointmentSpots - $appointmentCount;
	}
	return 0;
}

class DateSiteTimeMap {
	public $dates = [];

	public function addDateSiteTimeObject($dstObject) {
		// Get the number of appointments still available
		$appointmentsAvailable = calculateRemainingAppointmentsAvailable($dstObject['numberOfAppointmentsAlreadyMade'], $dstObject['percentageAppointments'], $dstObject['numberOfVolunteers'], $dstObject['maximumNumberOfAppointments']);

		// Add the appointmentTime to the map
		$this->dates[$dstObject['scheduledDate']][$dstObject['siteId']][$dstObject['scheduledTime']] = $appointmentsAvailable;
	}
}
