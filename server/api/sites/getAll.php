<?php

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once "$root/server/config.php";

const defaultSelectParameters = array('siteId', 'title', 'address', 'phoneNumber', 'appointmentOnly');

readAllSites($_GET);

/*
 * The fields that CAN be returned are: siteId, title, address, phoneNumber, and appointmentOnly
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
function readAllSites($data) {
	GLOBAL $DB_CONN;

	// construct select parameter list
	$selectParameters = '';
	if ($data) {
		foreach (defaultSelectParameters as $key) {
			if (isset($data[$key])) {
				if ($data[$key] == true) {
					$selectParameters = $selectParameters . ' ' . $key; // append the select parameter
				}
			}
		}
	}

	$stmt = $DB_CONN->prepare("SELECT siteId, title FROM Site");
	$stmt->execute();
	$sites = $stmt->fetchAll(PDO::FETCH_ASSOC);

	echo json_encode($sites);
}

