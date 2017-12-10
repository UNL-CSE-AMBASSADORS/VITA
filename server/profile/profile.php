<?php

$root = realpath($_SERVER['DOCUMENT_ROOT']);
require_once "$root/server/user.class.php";
$USER = new User();
if (!$USER->isLoggedIn()) {
	header("Location: /unauthorized");
	die();
}

require_once "$root/server/config.php";

if (isset($_REQUEST['callback'])) {
	switch ($_REQUEST['callback']) {
		case 'getUserInformation':
			getUserInformation();
			break;
		case 'getAbilities':
			getAbilities();
			break;
		case 'getShifts':
			getShifts();
			break;
		case 'updateFirstName':
			updateFirstName($_REQUEST['firstName']);
			break;
		default:
			die('Invalid callback function. This instance has been reported.');
			break;
	}
}

function getUserInformation() {
	GLOBAL $USER;
	$userInformation = $USER->getUserDetails();
	echo json_encode($userInformation);
}

function updateFirstName($firstName) {
	GLOBAL $USER, $DB_CONN;
	$userId = $USER->getUserId();

	$response = array();
	$response['success'] = false;

	try {
		if (isset($firstName)) {
			$query = "UPDATE User
				SET firstName = ?
				WHERE userId = ?";
			
			$stmt = $DB_CONN->prepare($query);
			$response['success'] = $stmt->execute(array($firstName, $userId));
		}
	} catch (Exception $e) {
		$response['success'] = false;
		$response['error'] = 'There was an error communicating with the server. Please try again later.';
	}

	echo json_encode($response);
}

function getAbilities() {
	GLOBAL $USER, $DB_CONN;
	$userId = $USER->getUserId();
	
	$query = "SELECT Ability.abilityId, Ability.name, Ability.description, Ability.verificationRequired, UserAbility.userAbilityId
		FROM Ability
		LEFT JOIN UserAbility ON Ability.abilityId = UserAbility.abilityId AND UserAbility.userId = ?";
	
	$stmt = $DB_CONN->prepare($query);
	$stmt->execute(array($userId));
	$abilities = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
	$abilitiesNotRequiringVerificaiton = array();
	$abilitiesRequiringVerification = array();
	foreach ($abilities as &$ability) {
		$ability['has'] = isset($ability['userAbilityId']);
		
		if ($ability['verificationRequired']) {
			$abilitiesRequiringVerification[] = $ability;
		} else {
			$abilitiesNotRequiringVerificaiton[] = $ability;
		}
	}

	$result = array(
		'abilities' => $abilitiesNotRequiringVerificaiton,
		'abilitiesRequiringVerification' => $abilitiesRequiringVerification
	);

	echo json_encode($result);
}

function getShifts() {
	GLOBAL $USER, $DB_CONN;
	$userId = $USER->getUserId();

	$query = "SELECT Shift.shiftId, startTime, endTime, title, UserShift.userShiftId
		FROM Shift
		LEFT JOIN UserShift ON Shift.shiftId = UserShift.shiftId AND UserShift.userId = ?
		JOIN Site ON Shift.siteId = Site.siteId
		ORDER BY title, startTime";

	$stmt = $DB_CONN->prepare($query);
	$stmt->execute(array($userId));
	$shifts = $stmt->fetchAll(PDO::FETCH_ASSOC);

	foreach ($shifts as &$shift) {
		$shift['signedUp'] = isset($shift['userShiftId']);
	}

	$result = array(
		'shifts' => $shifts
	);

	echo json_encode($result);
}
