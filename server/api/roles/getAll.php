<?php

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once "$root/server/config.php";

getAllRoles($_GET);

/*
 * The fields that CAN be returned are: roleId, name, and lookupName
 *
 * The data can be used to narrow down what is selected from the database:
 * {
 *   "roleId": true,
 *   "name": true
 * }
 *
 * If the database field is missing it is assumed that you DON'T want it.
 */
function getAllRoles($data) {
	GLOBAL $DB_CONN;
	$defaultSelectColumns = array('roleId', 'name', 'lookupName');

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

	$stmt = $DB_CONN->prepare('SELECT ' . $selectColumnsString . ' FROM Role');
	$stmt->execute();
	$roles = $stmt->fetchAll(PDO::FETCH_ASSOC);

	echo json_encode($roles);
}
