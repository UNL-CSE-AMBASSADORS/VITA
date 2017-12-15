<?php

error_reporting(E_ALL);
ini_set('display_errors', 'On');

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once "$root/server/config.php";

class DateSiteTimeMap {
	public $dates = [];

	public function addDateSiteTimeObject($dstObject) {
		// If this is the first instance of the date
		if(!array_key_exists($dstObject['scheduledDate'], $this->dates)) {
			$this->dates[$dstObject['scheduledDate']] = array($dstObject['siteId'] => array($dstObject['scheduledTime']));
		} else {
			// If the date exists, but this is the first instance of the site
			if(!array_key_exists($dstObject['siteId'], $this->dates[$dstObject['scheduledDate']])) {
				$this->dates[$dstObject['scheduledDate']][$dstObject['siteId']] = array($dstObject['scheduledTime']);
			} else {
				// Otherwise just add the time
				$this->dates[$dstObject['scheduledDate']][$dstObject['siteId']][] = $dstObject['scheduledTime'];
			}
		}
	}
}

getAppointmentTimes($_GET);

/*
 * The fields that will be returned are: appointmentTimeId, siteId, scheduledDate,  scheduledTime, percentageAppointments
 */
function getAppointmentTimes($data) {
	GLOBAL $DB_CONN;

	date_default_timezone_set('America/Chicago');

	$year = date("Y");
	if (isset($data['year'])) {
		$year = $data['year'];
	}

	$stmt = $DB_CONN->prepare('SELECT appointmentTimeId, siteId, DATE(scheduledTime) as scheduledDate, TIME(scheduledTime) as scheduledTime, percentageAppointments FROM AppointmentTime WHERE YEAR(scheduledTime) = ? ORDER BY AppointmentTime.scheduledTime');
	$stmt->execute(array($year));
	$appointmentTimes = $stmt->fetchAll(PDO::FETCH_ASSOC);

	$dstMap = new DateSiteTimeMap();

	foreach ($appointmentTimes as $appointmentTime) {
		$dstMap->addDateSiteTimeObject($appointmentTime);
	}

	echo json_encode($dstMap);
}
