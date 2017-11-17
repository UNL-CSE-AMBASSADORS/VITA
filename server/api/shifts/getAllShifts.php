<?php

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once "$root/server/config.php";

define ("defaultSelectColumns", serialize (array('shiftId', 'startTime', 'endTime', 'archived', 'createdAt', 'lastModifiedDate', 'siteId', 'createdBy', 'lastModifiedBy')));

getAllShifts($_GET);

/*
 * The fields that CAN be returned are: shiftId, startTime, endTime, archived, createdAt, lastModifiedDate, siteId, createdBy, lastModifiedBy
 *
 * The data can be used to narrow down what is selected from the database:
 * {
 *   "shiftId": false,
 *   "endTime": true,
 *   "siteId": true
 * }
 *
 * If the database field is missing it is assumed that you DON'T want it.
 */
function getAllShifts($data) {
	GLOBAL $DB_CONN;

	// construct select columns list
	$selectColumns = [];
	if (!is_null($data) && !empty($data)) {
		foreach (unserialize(defaultSelectColumns) as $key) {
			if (isset($data[$key])) {
				if ($data[$key] == true) {
					$selectColumns[] = $key; // append the select columns
				}
			}
		}
	}
	$selectColumnsString = join(',', $selectColumns);

	$year = 2018;
	if(isset($data['year'])) {
		$year = $data['year'];
	}

	$stmt = $DB_CONN->prepare('SELECT ' . $selectColumnsString . ' FROM Shift WHERE YEAR(startTime) = ?');
	$stmt->execute(array($year));
	$shifts = $stmt->fetchAll(PDO::FETCH_ASSOC);
	foreach ($shifts as &$shift) {
		$shift['startTime'] = $shift['startTime'] . " GMT";
		$shift['endTime'] = $shift['endTime'] . " GMT";
	}


	echo json_encode($shifts);
}
