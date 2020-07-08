<?php

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once "$root/server/config.php";
require_once "$root/server/user.class.php";
require_once "$root/server/utilities/appointmentTypeUtilities.class.php";
$USER = new User();

$isLoggedIn = $USER->isLoggedIn();

getAppointmentTimes($_GET, $isLoggedIn);


function getAppointmentTimes($data, $isLoggedIn) {
	date_default_timezone_set('America/Chicago');

	$year = date("Y");
	if (isset($data['year'])) {
		$year = $data['year'];
	}

	$after = date("Y-m-d H:i:s", time() - ($isLoggedIn ? 3600 : 0));
	if (isset($data['after'])) {
		$after = $data['after'];
	}

	$appointmentType = 'residential';
	if (isset($data['appointmentType'])) {
		$appointmentType = $data['appointmentType'];
	}

	$appointmentTimes = getAppointmentTimesFromDatabase($year, $after, $appointmentType);

	$dstMap = new DateSiteTimeMap($isLoggedIn);
	foreach ($appointmentTimes as $appointmentTime) {
		$dstMap->addDateSiteTimeObject($appointmentTime);
	}
	$dstMap->updateAvailability();

	echo json_encode($dstMap);
}

function getAppointmentTimesFromDatabase($year, $after, $appointmentType) {
	GLOBAL $DB_CONN;

	$query = 'SELECT apt.appointmentTimeId, apt.siteId, s.title, DATE(scheduledTime) AS scheduledDate,
		TIME(scheduledTime) AS scheduledTime, COUNT(DISTINCT a.appointmentId) AS numberOfAppointmentsAlreadyScheduled,
		apt.numberOfAppointments, AppointmentType.name, AppointmentType.lookupName AS appointmentType
	FROM AppointmentTime apt
		LEFT JOIN Site s ON s.siteId = apt.siteId
		LEFT JOIN AppointmentType ON apt.appointmentTypeId = AppointmentType.appointmentTypeId
		LEFT JOIN Appointment a ON a.appointmentTimeId = apt.appointmentTimeId
		LEFT JOIN ServicedAppointment sa ON sa.appointmentId = a.appointmentId
	WHERE YEAR(apt.scheduledTime) = ?
		AND apt.scheduledTime > ?
		AND AppointmentType.lookupName = ?
		AND (sa.cancelled IS NULL OR sa.cancelled = FALSE)
	GROUP BY apt.appointmentTimeId
	ORDER BY apt.scheduledTime;';

	$stmt = $DB_CONN->prepare($query);
	$stmt->execute(array($year, $after, $appointmentType));
	$appointmentTimes = $stmt->fetchAll(PDO::FETCH_ASSOC);

	return $appointmentTimes;
}

class DateSiteTimeMap {
	public $dates = [];
	public $hasAvailability = false;
	public $isLoggedIn = false;

	function __construct($isLoggedIn) {
		$this->isLoggedIn = $isLoggedIn;
	}
	
	public function addDateSiteTimeObject($dstObject) {
		// Get the number of appointments still available
		$appointmentsAvailable = $this->calculateRemainingAppointmentsAvailable($dstObject['numberOfAppointmentsAlreadyScheduled'], $dstObject['numberOfAppointments']);

		// Reformat the time to be h:i MM
		$time = date_format(date_create($dstObject['scheduledTime']), 'g:i A');

		// Add the appointmentTime to the map
		$this->dates[$dstObject['scheduledDate']]['sites'][$dstObject['siteId']]['times'][$time]['isVirtual'] = AppointmentTypeUtilities::isVirtualAppointmentType($dstObject['appointmentType']);
		$this->dates[$dstObject['scheduledDate']]['sites'][$dstObject['siteId']]['siteTitle'] = $dstObject['title'];
		$this->dates[$dstObject['scheduledDate']]['sites'][$dstObject['siteId']]['times'][$time]['appointmentsAvailable'] = $appointmentsAvailable;
		$this->dates[$dstObject['scheduledDate']]['sites'][$dstObject['siteId']]['times'][$time]['appointmentTimeId'] = $dstObject['appointmentTimeId'];
	}

	private function calculateRemainingAppointmentsAvailable($numberOfAppointmentsAlreadyScheduled, $numberOfAppointmentsAllowed) {
		return $numberOfAppointmentsAllowed - $numberOfAppointmentsAlreadyScheduled;
	}

	public function updateAvailability() {
		$this->hasAvailability = false;
		foreach (array_keys($this->dates) as $date) {
			$dateHasTimes = false;
			foreach (array_keys($this->dates[$date]['sites']) as $site) {
				$siteHasTimes = false;
				foreach ($this->dates[$date]['sites'][$site]['times'] as $time) {
					if($this->isLoggedIn || $time['appointmentsAvailable'] > 0) {
						$this->hasAvailability = true;
						$dateHasTimes = true;
						$siteHasTimes = true;
					}
				}
				$this->dates[$date]['sites'][$site]['hasAvailability'] = $this->isLoggedIn || $siteHasTimes;
			}
			$this->dates[$date]['hasAvailability'] = $this->isLoggedIn || $dateHasTimes;
		}
	}
}


