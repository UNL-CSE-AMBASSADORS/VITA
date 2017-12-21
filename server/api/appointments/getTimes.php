<?php

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once "$root/server/config.php";

getAppointmentTimes($_GET);

/*
 * The fields that will be returned are: appointmentTimeId, siteId, scheduledDate,  scheduledTime, percentageAppointments, numberOfAppointmentsAlreadyMade, numberOfPreparers
 */
function getAppointmentTimes($data) {
	GLOBAL $DB_CONN;

	date_default_timezone_set('America/Chicago');

	$year = date("Y");
	if (isset($data['year'])) {
		$year = $data['year'];
	}

	$stmt = $DB_CONN->prepare('SELECT apt.appointmentTimeId, apt.siteId, s.title, DATE(scheduledTime) AS scheduledDate, TIME(scheduledTime) AS scheduledTime, percentageAppointments, COUNT(DISTINCT a.appointmentId) AS numberOfAppointmentsAlreadyMade, COUNT(DISTINCT us.userShiftId) AS numberOfPreparers, apt.minimumNumberOfAppointments, apt.maximumNumberOfAppointments
		FROM AppointmentTime apt
		LEFT JOIN Appointment a ON a.appointmentTimeId = apt.appointmentTimeId
		LEFT JOIN UserShift us ON us.shiftId IN (SELECT s.shiftId FROM Shift s WHERE s.siteId = apt.siteId AND s.startTime <= apt.scheduledTime AND s.endTime >= apt.scheduledTime) AND us.roleId = (SELECT roleId FROM Role WHERE lookupName = "preparer")
		LEFT JOIN Site s ON s.siteId = apt.siteId
		WHERE YEAR(apt.scheduledTime) = ?
		GROUP BY apt.appointmentTimeId
		ORDER BY apt.scheduledTime');
	$stmt->execute(array($year));
	$appointmentTimes = $stmt->fetchAll(PDO::FETCH_ASSOC);

	$dstMap = new DateSiteTimeMap();

	foreach ($appointmentTimes as $appointmentTime) {
		$dstMap->addDateSiteTimeObject($appointmentTime);
	}

	$dstMap->updateAvailability();

	echo json_encode($dstMap);
}

function calculateRemainingAppointmentsAvailable($appointmentCount, $percentAppointments, $preparerCount, $minimum, $maximum) {
	if (isset($maximum)) {
		$availableAppointmentSpots = $maximum;
	} else {
		$availableAppointmentSpots = max($minimum, $preparerCount);
	}
	$availableAppointmentSpots *= $percentAppointments / 100;
	if ($appointmentCount < $availableAppointmentSpots) {
		return $availableAppointmentSpots - $appointmentCount;
	}
	return 0;
}

class DateSiteTimeMap {
	public $dates = [];

	public function addDateSiteTimeObject($dstObject) {
		// Get the number of appointments still available
		$appointmentsAvailable = calculateRemainingAppointmentsAvailable($dstObject['numberOfAppointmentsAlreadyMade'], $dstObject['percentageAppointments'], $dstObject['numberOfPreparers'], $dstObject['minimumNumberOfAppointments'], $dstObject['maximumNumberOfAppointments']);

		// Reformat the time to be h:i MM
		$time = date_format(date_create($dstObject['scheduledTime']), 'g:i A');

		// Add the appointmentTime to the map
		$this->dates[$dstObject['scheduledDate']]["sites"][$dstObject['siteId']]["site_title"] = $dstObject['title'];
		$this->dates[$dstObject['scheduledDate']]["sites"][$dstObject['siteId']]["times"][$time] = $appointmentsAvailable;
	}

	public function updateAvailability() {
		foreach (array_keys($this->dates) as $date) {
			$dateHasTimes = false;
			foreach (array_keys($this->dates[$date]["sites"]) as $site) {
				$siteHasTimes = false;
				foreach ($this->dates[$date]["sites"][$site]["times"] as $time) {
					if($time > 0) {
						$dateHasTimes = true;
						$siteHasTimes = true;
					}
				}
				$this->dates[$date]["sites"][$site]["hasAvailability"] = $siteHasTimes;
			}
			$this->dates[$date]["hasAvailability"] = $dateHasTimes;
		}
	}
}


