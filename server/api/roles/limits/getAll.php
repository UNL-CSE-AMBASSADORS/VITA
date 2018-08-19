<?php

$root = realpath($_SERVER['DOCUMENT_ROOT']);
require_once "$root/server/config.php";
require_once "$root/server/user.class.php";

$USER = new User();
if (!$USER->isLoggedIn()) {
	header("Location: /unauthorized");
	die();
}


getAllRoleLimits();

function getAllRoleLimits() {
	GLOBAL $DB_CONN;

	$query = 'SELECT maximumNumber, siteId, shiftId, roleId
		FROM RoleLimit';
	$stmt = $DB_CONN->prepare($query);
	$stmt->execute();
	$roleLimits = $stmt->fetchAll(PDO::FETCH_ASSOC);

	echo json_encode($roleLimits);
}
