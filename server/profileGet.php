<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);

require_once "$root/server/user.class.php";
$USER = new User();
if (!$USER->isLoggedIn()) {
	header("Location: /unauthorized");
	die();
}
require_once 'config.php';
getProfile($userId);
function getProfile($data) {
	GLOBAL $DB_CON;
	$userGet = "SELECT firstName, lastName, phoneNumber, email, preparesTaxes
	FROM User
	WHERE userId = ? ";
	$stmt = $DB_CONN->prepare($userGet);
	$stmt->execute(array($data));
	$response = $stmt->fetchAll(PDO::FETCH_ASSOC);
	echo json_encode($response);

	$userAbilityGet = "SELECT abilityId, abilityId
	FROM UserAbility
	WHERE abilityId = ?";
	$stmt = $DB_CONN->prepare($userAbilityGet);
	$stmt->execute(array($data));
	$response = $stmt->fetchAll(PDO::FETCH_ASSOC);
	echo json_encode($response);


	$userShiftGet = "SELECT shiftId
	FROM UserShift
	WHERE userId = ?";
	$stmt = $DB_CONN->prepare($userShiftGet);
	$stmt->execute(array($data));
	$response = $stmt->fetchAll(PDO::FETCH_ASSOC);
	echo json_encode($response);

	$shiftSelectGet = "SELECT startTime, endTime, shiftId
	FROM Shift
	WHERE siteId = ?";
	$stmt = $DB_CONN->prepare($shiftSelectGet);
	$stmt->execute(array($data));
	$response = $stmt->fetchAll(PDO::FETCH_ASSOC);
	echo json_encode($response);

	$siteIdGet = "SELECT siteId
	FROM Shift;"
	$stmt = $DB_CONN->prepare($shiftSelectGet);
	$stmt->execute(array($data));
	$response = $stmt->fetchAll(PDO::FETCH_ASSOC);
	echo json_encode($response);
	}
