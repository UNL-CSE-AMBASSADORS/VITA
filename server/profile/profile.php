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
		case 'getUserInformation': getUserInformation(); break;
		case 'getAbilities': getAbilities(); break;
		case 'getShifts': getShifts(); break;
		case 'updateFirstName': updateFirstName($_REQUEST['firstName']); break;
		case 'updateLastName': updateLastName($_REQUEST['lastName']); break;
		case 'updateEmail': updateEmail($_REQUEST['email']); break;
		case 'updatePhoneNumber': updatePhoneNumber($_REQUEST['phoneNumber']); break;
		case 'updateAbilities': updateAbilities($_REQUEST); break;
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
		$ability['verificationRequired'] = $ability['verificationRequired'] ? true : false;
		
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

function updateLastName($lastName) {
	GLOBAL $USER, $DB_CONN;
	$userId = $USER->getUserId();

	$response = array();
	$response['success'] = false;

	try {
		if (isset($lastName)) {
			$query = "UPDATE User
				SET lastName = ?
				WHERE userId = ?";
			
			$stmt = $DB_CONN->prepare($query);
			$response['success'] = $stmt->execute(array($lastName, $userId));
		} 
	} catch (Exception $e) {
		$response['success'] = false;
		$response['error'] = 'There was an error communicating with the server. Please try again later.';
	}

	echo json_encode($response);
}

function updateEmail($email) {
	GLOBAL $USER, $DB_CONN;
	$userId = $USER->getUserId();

	$response = array();
	$response['success'] = false;

	try {
		if (isset($email)) {
			$query = "UPDATE User
				SET email = ?
				WHERE userId = ?";
			
			$stmt = $DB_CONN->prepare($query);
			$response['success'] = $stmt->execute(array($email, $userId));
		}
	} catch (Exception $e) {
		$response['success'] = false;
		$response['error'] = 'There was an error communicating with the server. Please try again later.';
	}

	echo json_encode($response);
}

function updatePhoneNumber($phoneNumber) {
	GLOBAL $USER, $DB_CONN;
	$userId = $USER->getUserId();

	$response = array();
	$response['success'] = false;

	try {
		if (isset($phoneNumber)) {
			$query = "UPDATE User
				SET phoneNumber = ?
				WHERE userId = ?";
			
			$stmt = $DB_CONN->prepare($query);
			$response['success'] = $stmt->execute(array($phoneNumber, $userId));
		}
	} catch (Exception $e) {
		$response['success'] = false;
		$response['error'] = 'There was an error communicating with the server. Please try again later.';
	}

	echo json_encode($response);
}

function updateAbilities($data) {
	GLOBAL $DB_CONN, $USER;

	$userId = $USER->getUserId();
	$response = array();
	$response['success'] = true;

	$DB_CONN->beginTransaction();

	if(isset($data['removeAbilityArray'])){
		$stmt = $DB_CONN->prepare("DELETE FROM UserAbility WHERE userAbilityId = ?");

		foreach ($data['removeAbilityArray'] as $userAbilityId) {
			$stmt->execute(array($userAbilityId));
		}
	}

	if(isset($data['addAbilityArray'])){
		$stmt = $DB_CONN->prepare("INSERT INTO UserAbility 
				(userId, abilityId, createdBy)
			VALUES 
				(?, ?, ?)");

		foreach ($data['addAbilityArray'] as $abilityId) {
			$stmt->execute(array(
				$userId,
				$abilityId, 
				$userId
			));
		}
	}

	$DB_CONN->commit();

	echo json_encode($response);
}
