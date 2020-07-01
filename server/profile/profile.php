<?php

$root = realpath($_SERVER['DOCUMENT_ROOT']);
require_once "$root/server/user.class.php";
$USER = new User();
if (!$USER->isLoggedIn()) {
	header("Location: /unauthorized");
	die();
}

require_once "$root/server/config.php";
require_once "$root/server/utilities/emailUtilities.class.php";

if (isset($_REQUEST['action'])) {
	switch ($_REQUEST['action']) {
		case 'getUserInformation': getUserInformation(); break;
		case 'getAbilities': getAbilities(); break;
		case 'updatePersonalInformation': updatePersonalInformation($_REQUEST); break;
		case 'updateAbilities': updateAbilities($_REQUEST); break;
		default:
			die('Invalid action function. This instance has been reported.');
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

function updateAbilities($data) {
	GLOBAL $DB_CONN, $USER;

	$userId = $USER->getUserId();
	$response = array();
	$response['success'] = true;

	$DB_CONN->beginTransaction();

	if(isset($data['removeAbilityArray'])){
		$stmt = $DB_CONN->prepare("DELETE FROM UserAbility WHERE userAbilityId = ? AND userId = ?");

		foreach ($data['removeAbilityArray'] as $userAbilityId) {
			$stmt->execute(array($userAbilityId, $userId));
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

function updatePersonalInformation($data) {
	GLOBAL $USER, $DB_CONN;
	$userId = $USER->getUserId();

	$response = array();
	$response['success'] = true;

	try {
		// Validate input
		if (!isset($data['firstName']) || trim($data['firstName']) === '') throw new Exception('Please enter a valid first name.', MY_EXCEPTION);
		if (!isset($data['lastName']) || trim($data['lastName']) === '') throw new Exception('Please enter a valid last name.', MY_EXCEPTION);
		// if (!isset($data['email']) || trim($data['email']) === '' || !preg_match('/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/', trim(strtolower($data['email'])))) throw new Exception('Please enter a valid email.', MY_EXCEPTION);
		if (!isset($data['phoneNumber']) || trim($data['phoneNumber']) === '') throw new Exception('Please enter a valid phone number.', MY_EXCEPTION);

		$query = "UPDATE User
			SET firstName = ?, lastName = ?, phoneNumber = ?
			WHERE userId = ?";
		$stmt = $DB_CONN->prepare($query);
		$success = $stmt->execute(array(
			$data['firstName'],
			$data['lastName'],
			$data['phoneNumber'],
			$userId
		));

		if ($success == false) {
			throw new Exception('There was an error on the server. Please refresh the page and try again.', MY_EXCEPTION);
		}
	} catch (Exception $e) {
		processException($e, $response);
	}

	echo json_encode($response);
}

function processException($exception, &$response){
	if($exception->getCode() === MY_EXCEPTION){
		$response['error'] = $exception->getMessage();
	}else{
		$response['error'] = "Sorry, there was an error reaching the server. Please try again later.";
	}
	$response['success'] = false;
}