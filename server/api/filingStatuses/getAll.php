<?php

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once "$root/server/config.php";

getAllFilingStatuses($_GET);

/*
 * The fields that CAN be returned are: filingStatusId, text, lookupName
 *
 * The data can be used to narrow down what is selected from the database:
 * {
 *   "filingStatusId": true,
 *   "text": true,
 *   "lookupName": false
 * }
 *
 * If the database field is missing it is assumed that you DON'T want it.
 */
function getAllFilingStatuses($data) {
	GLOBAL $DB_CONN;
	$defaultSelectColumns = array('filingStatusId', 'text', 'lookupName');

	// construct select columns list
	$selectColumns = [];
	if (!is_null($data) && !empty($data)) {
		foreach ($defaultSelectColumns as $key) {
			if (isset($data[$key])) {
				if ($data[$key] == true) {
					$selectColumns[] = $key; // append the select columns
				}
			}
		}
	}
	$selectColumnsString = join(',', $selectColumns);

	$stmt = $DB_CONN->prepare('SELECT ' . $selectColumnsString . ' FROM FilingStatus');
	$stmt->execute();
	$filingStatuses = $stmt->fetchAll(PDO::FETCH_ASSOC);

	echo json_encode($filingStatuses);
}
