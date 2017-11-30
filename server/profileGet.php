<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);

require_once "$root/server/user.class.php";
$USER = new User();
if (!$USER->isLoggedIn()) {
	header("Location: /unauthorized");
	die();
}

require_once "$root/server/config.php";

getProfile($_GET);
function getProfile($data) {
	GLOBAL $DB_CONN, $USER;

	$userId = $USER->getUserId();

	$userInformationQuery = "SELECT firstName, lastName, phoneNumber, email, preparesTaxes
		FROM User
		WHERE userId = ?";
	$stmt = $DB_CONN->prepare($userInformationQuery);
	$stmt->execute(array($userId));
	$userInformation = $stmt->fetchAll(PDO::FETCH_ASSOC);


	$userAbilityQuery = "SELECT Ability.abilityId, Ability.lookupName, Ability.verificationRequired, Ability.name
		FROM UserAbility
		JOIN Ability ON UserAbility.abilityId = Ability.abilityId
		WHERE UserAbility.userId = ?";
	$stmt = $DB_CONN->prepare($userAbilityQuery);
	$stmt->execute(array($userId));
	$userAbilityInformation = $stmt->fetchAll(PDO::FETCH_ASSOC);



	$userShiftsQuery = "SELECT Shift.shiftId, Shift.startTime, Shift.endTime, Site.siteId, Site.title, 
		FROM UserShift
		JOIN Shift ON UserShift.shiftId = Shift.shiftId
		JOIN Site ON Shift.siteId = Site.siteId
		WHERE userId = ?";
	$stmt = $DB_CONN->prepare($userShiftsQuery);
	$stmt->execute(array($userId));
	$userShiftsInformation = $stmt->fetchAll(PDO::FETCH_ASSOC);


	/*
	$shiftSelectGet = "SELECT startTime, endTime, shiftId
		FROM Shift
		WHERE siteId = ?";
	$stmt = $DB_CONN->prepare($shiftSelectGet);
	$stmt->execute(array($data));
	$response = $stmt->fetchAll(PDO::FETCH_ASSOC);


	$siteIdGet = "SELECT siteId
		FROM Shift;"
	$stmt = $DB_CONN->prepare($shiftSelectGet);
	$stmt->execute(array($data));
	$response = $stmt->fetchAll(PDO::FETCH_ASSOC);
	*/

	$response = array(
		'success' => true,
		'userInformation' => $userInformation,
		'userAbilityInformation' => $userAbilityInformation,
		'userShiftsInformation' => $userShiftsInformation
	);

	echo json_encode($response);
}
