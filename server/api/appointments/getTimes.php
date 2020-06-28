<?php

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once "$root/server/config.php";
require_once "$root/server/user.class.php";
$USER = new User();

$isLoggedIn = $USER->isLoggedIn();


getAppointmentTimes($_GET, $isLoggedIn);

/*
 * The fields that will be returned are: appointmentTimeId, siteId, isVirtual, scheduledDate, scheduledTime, percentageAppointments, numberOfAppointmentsAlreadyMade, numberOfPreparers
 */
function getAppointmentTimes($data, $isLoggedIn) {
	date_default_timezone_set('America/Chicago');

	$year = date('Y');
	if (isset($data['year'])) {
		$year = $data['year'];
	}

	$after = date('Y-m-d H:i:s', time() - ($isLoggedIn ? 3600 : 0));
	if (isset($data['after'])) {
		$after = $data['after'];
	}

	$isResidential = true;
	if (isset($data['appointmentType'])) {
		$isResidential = $data['appointmentType'] === 'residential';
	}

	$tenantName = 'unl';
	if (isset($data['tenantName'])) {
		$tenantName = $data['tenantName'];
	}

	if ($isResidential) {
		$appointmentTimes = getResidentialAppointmentTimes($year, $after);
	} else {
		$appointmentTimes = getInternationalAppointmentTimes($year, $after, $data['appointmentType']);
	}

	$dstMap = new DateSiteTimeMap($data['appointmentType']);

	$uiowaSiteIds = [ 8 ]; // TODO: Set to actual IDs for UIowa sites
	foreach ($appointmentTimes as $appointmentTime) {
		// TODO: If this 'tenant' system remains (which it probably shouldn't), this filterin should be done somehow in the SQL query
		$shouldSkip = ($tenantName === 'unl' && in_array($appointmentTime['siteId'], $uiowaSiteIds))
				||	  ($tenantName === 'uiowa' && !in_array($appointmentTime['siteId'], $uiowaSiteIds));
		if ($shouldSkip) continue;

		$dstMap->addDateSiteTimeObject($appointmentTime);
	}

	$dstMap->isLoggedIn = $isLoggedIn;

	$dstMap->updateAvailability();

	echo json_encode($dstMap);
}

function getResidentialAppointmentTimes($year, $after) {
	GLOBAL $DB_CONN;

	$query = 'SELECT apt.appointmentTimeId, apt.siteId, s.title, s.isVirtual, DATE(scheduledTime) AS scheduledDate, 
		TIME(scheduledTime) AS scheduledTime, percentageAppointments, 
		COUNT(DISTINCT a.appointmentId) AS numberOfAppointmentsAlreadyMade, 
		COUNT(DISTINCT us.userShiftId) AS numberOfPreparers, 
		apt.minimumNumberOfAppointments, apt.maximumNumberOfAppointments
	FROM AppointmentTime apt
		LEFT JOIN Appointment a ON a.appointmentTimeId = apt.appointmentTimeId
		LEFT JOIN ServicedAppointment sa ON sa.appointmentId = a.appointmentId
		LEFT JOIN UserShift us ON us.shiftId IN 
			(SELECT s.shiftId FROM Shift s WHERE s.siteId = apt.siteId 
				AND s.startTime <= apt.scheduledTime 
				AND s.endTime >= apt.scheduledTime 
				AND TIMESTAMPDIFF(MINUTE, apt.scheduledTime, s.endTime) >= apt.approximateLengthInMinutes) 
			AND us.roleId = (SELECT roleId FROM Role WHERE lookupName = "preparer")
		LEFT JOIN Site s ON s.siteId = apt.siteId
	WHERE YEAR(apt.scheduledTime) = ? 
		AND apt.scheduledTime > ? 
		AND s.doesInternational = FALSE
		AND (sa.cancelled IS NULL OR sa.cancelled = FALSE)
	GROUP BY apt.appointmentTimeId
	ORDER BY apt.scheduledTime';

	$stmt = $DB_CONN->prepare($query);
	$stmt->execute(array($year, $after));
	$appointmentTimes = $stmt->fetchAll(PDO::FETCH_ASSOC);

	return $appointmentTimes;
}

function getInternationalAppointmentTimes($year, $after, $treatyType) {
	GLOBAL $DB_CONN;

	$query = 'SELECT apt.appointmentTimeId, apt.siteId, s.title, s.isVirtual, DATE(scheduledTime) AS scheduledDate, 
		TIME(scheduledTime) AS scheduledTime, percentageAppointments, 
		COUNT(DISTINCT ans.answerId) AS numberOfAppointmentsAlreadyMade, 
		apt.minimumNumberOfAppointments, apt.maximumNumberOfAppointments
	FROM AppointmentTime apt
		LEFT JOIN Appointment a ON a.appointmentTimeId = apt.appointmentTimeId
		LEFT JOIN ServicedAppointment sa ON sa.appointmentId = a.appointmentId
		LEFT JOIN Answer ans ON ans.appointmentId = a.appointmentId 
			AND ans.questionId = (SELECT questionId FROM Question WHERE lookupName = "treaty_type")
			AND ans.possibleAnswerId = (SELECT possibleAnswerId FROM PossibleAnswer WHERE text = ?)
		LEFT JOIN Site s ON s.siteId = apt.siteId
	WHERE YEAR(apt.scheduledTime) = ? 
		AND apt.scheduledTime > ? 
		AND s.doesInternational = TRUE
		AND (sa.cancelled IS NULL OR sa.cancelled = FALSE)
	GROUP BY apt.appointmentTimeId
	ORDER BY apt.scheduledTime;';

	$stmt = $DB_CONN->prepare($query);
	$stmt->execute(array($treatyType, $year, $after));
	$appointmentTimes = $stmt->fetchAll(PDO::FETCH_ASSOC);

	return $appointmentTimes;
}

function calculateRemainingAppointmentsAvailableForResidentialAppointment($appointmentCount, $percentAppointments, $preparerCount, $minimum, $maximum) {
	$availableAppointmentSpots = $preparerCount;
	if (isset($minimum)) {
		$availableAppointmentSpots = max($minimum, $availableAppointmentSpots);
	}

	if (isset($maximum)) {
		$availableAppointmentSpots = min($maximum, $availableAppointmentSpots);
	}

	$availableAppointmentSpots *= $percentAppointments / 100;
	return ceil($availableAppointmentSpots) - $appointmentCount;
}

function calculateRemainingAppointmentsAvailableForInternationalAppointment($treatyType, $numberAppointmentsAlreadyScheduled, $appointmentTimeId) {
	// TODO: This is a real band-aid that we are using to enable less appointments for certain time slots
	$appointmentTimeIdsForLessAppointments = array(968, 969, 970, 971, 972);
	if (in_array($appointmentTimeId, $appointmentTimeIdsForLessAppointments)) {
		if ($treatyType === 'china') {
			return 7 - $numberAppointmentsAlreadyScheduled;
		} else if ($treatyType === 'india') {
			return 3 - $numberAppointmentsAlreadyScheduled;
		} else if ($treatyType === 'treaty') {
			return 2 - $numberAppointmentsAlreadyScheduled;
		} else if ($treatyType === 'non-treaty') {
			return 7 - $numberAppointmentsAlreadyScheduled;
		}
	} else {
		if ($treatyType === 'china') {
			return 15 - $numberAppointmentsAlreadyScheduled;
		} else if ($treatyType === 'india') {
			return 6 - $numberAppointmentsAlreadyScheduled;
		} else if ($treatyType === 'treaty') {
			return 5 - $numberAppointmentsAlreadyScheduled;
		} else if ($treatyType === 'non-treaty') {
			return 15 - $numberAppointmentsAlreadyScheduled;
		} 
	}
}

class DateSiteTimeMap {
	public $dates = [];
	public $hasAvailability = false;
	public $isLoggedIn = false;
	
	private $appointmentType;

	public function __construct($appointmentType) {
		$this->appointmentType = $appointmentType;
	}

	public function addDateSiteTimeObject($dstObject) {
		// Get the number of appointments still available
		$appointmentsAvailable = 0;
		if ($this->appointmentType === 'residential') {
			$appointmentsAvailable = calculateRemainingAppointmentsAvailableForResidentialAppointment($dstObject['numberOfAppointmentsAlreadyMade'], $dstObject['percentageAppointments'], $dstObject['numberOfPreparers'], $dstObject['minimumNumberOfAppointments'], $dstObject['maximumNumberOfAppointments']);
		} else {
			$appointmentsAvailable = calculateRemainingAppointmentsAvailableForInternationalAppointment($this->appointmentType, $dstObject['numberOfAppointmentsAlreadyMade'], $dstObject['appointmentTimeId']);
		}
		

		// Reformat the time to be h:i MM
		$time = date_format(date_create($dstObject['scheduledTime']), 'g:i A');

		// Add the appointmentTime to the map
		$this->dates[$dstObject['scheduledDate']]["sites"][$dstObject['siteId']]["site_title"] = $dstObject['title'];
		$this->dates[$dstObject['scheduledDate']]["sites"][$dstObject['siteId']]["is_virtual"] = (boolean)$dstObject['isVirtual'];
		$this->dates[$dstObject['scheduledDate']]["sites"][$dstObject['siteId']]["times"][$time]['appointmentsAvailable'] = $appointmentsAvailable;
		$this->dates[$dstObject['scheduledDate']]["sites"][$dstObject['siteId']]["times"][$time]['appointmentTimeId'] = $dstObject['appointmentTimeId'];
	}

	public function updateAvailability() {
		$this->hasAvailability = false;
		foreach (array_keys($this->dates) as $date) {
			$dateHasTimes = false;
			foreach (array_keys($this->dates[$date]["sites"]) as $site) {
				$siteHasTimes = false;
				foreach ($this->dates[$date]["sites"][$site]["times"] as $time) {
					if($this->isLoggedIn || $time['appointmentsAvailable'] > 0) {
						$this->hasAvailability = true;
						$dateHasTimes = true;
						$siteHasTimes = true;
					}
				}
				$this->dates[$date]["sites"][$site]["hasAvailability"] = $this->isLoggedIn || $siteHasTimes;
			}
			$this->dates[$date]["hasAvailability"] = $this->isLoggedIn || $dateHasTimes;
		}
	}
}


