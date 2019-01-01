<?php

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once "$root/server/config.php";

getAllSites($_GET);

/*
 * The fields that CAN be returned are: siteId, title, address, and, phoneNumber
 *
 * The data can be used to narrow down what is selected from the database:
 * {
 *   "siteId": true,
 *   "title": true,
 *   "address": false
 * }
 *
 * If the database field is missing it is assumed that you DON'T want it.
 */
function getAllSites($data) {
	GLOBAL $DB_CONN;
	$defaultSelectColumns = array('siteId', 'title', 'address', 'phoneNumber');

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

	$stmt = $DB_CONN->prepare('SELECT ' . $selectColumnsString . ' FROM Site WHERE archived = FALSE');
	$stmt->execute();
	$sites = $stmt->fetchAll(PDO::FETCH_ASSOC);

	echo json_encode($sites);
}
