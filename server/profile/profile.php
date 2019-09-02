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
		case 'getShifts': getShifts(); break;
		case 'updatePersonalInformation': updatePersonalInformation($_REQUEST); break;
		case 'updateAbilities': updateAbilities($_REQUEST); break;
		case 'removeShift': removeShift($_POST['userShiftId'], $_POST['reason']); break;
		case 'signUpForShift': signUpForShift($_REQUEST['shiftId'], $_REQUEST['roleId']); break;
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

function getShifts() {
	GLOBAL $USER, $DB_CONN;
	$userId = $USER->getUserId();

	date_default_timezone_set('America/Chicago');
	$year = date('Y');

	$query = "SELECT Shift.shiftId, TIME_FORMAT(startTime, '%l:%i %p') AS startTimeString, 
			TIME_FORMAT(endTime, '%l:%i %p') AS endTimeString, DATE_FORMAT(startTime, '%W, %b %D, %Y') AS dateString, 
			title, Site.siteId, UserShift.userShiftId, Role.roleId, Role.name AS roleName
		FROM Shift
			JOIN Site ON Shift.siteId = Site.siteId
			LEFT JOIN UserShift ON Shift.shiftId = UserShift.shiftId AND UserShift.userId = ?
			LEFT JOIN Role ON UserShift.roleId = Role.roleId
		WHERE Site.archived = FALSE AND Shift.archived = FALSE AND YEAR(Shift.startTime) = ?
		ORDER BY startTime";

	$stmt = $DB_CONN->prepare($query);
	$stmt->execute(array($userId, $year));
	$shifts = $stmt->fetchAll(PDO::FETCH_ASSOC);

	foreach ($shifts as &$shift) {
		$shift['signedUp'] = isset($shift['userShiftId']);
	}

	$shiftRoleCounts = getEachRoleCountForAllShifts();

	$result = array(
		'shifts' => $shifts,
		'shiftRoleCounts' => $shiftRoleCounts
	);

	echo json_encode($result);
}

function getEachRoleCountForAllShifts() {
	GLOBAL $DB_CONN;

	$query = 'SELECT shiftId, roleId, COUNT(*) AS numberSignedUp
		FROM UserShift
		GROUP BY shiftId, roleId;';
	
	$stmt = $DB_CONN->prepare($query);
	$stmt->execute();
	$roleCounts = $stmt->fetchAll(PDO::FETCH_ASSOC);

	return $roleCounts;
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

function removeShift($userShiftId, $reason) {
	GLOBAL $USER, $DB_CONN;
	$userId = $USER->getUserId();

	$response = array();
	$response['success'] = true;

	try {
		$shiftDetails = getShiftDetails($userShiftId, $userId);

		$query = "DELETE FROM UserShift WHERE userShiftId = ? AND userId = ?";
		$stmt = $DB_CONN->prepare($query);
		$success = $stmt->execute(array($userShiftId, $userId));
		if ($success == false) {
			throw new Exception();
		}
	} catch (Exception $e) {
		$response['success'] = false;
		$response['error'] = 'Unable to remove shift. Refresh the page and try again later.';
	}

	

	if ($success) {
		notifyForCancelledShift(
			$shiftDetails['firstName'],
			$shiftDetails['lastName'],
			$shiftDetails['email'],
			$shiftDetails['title'],
			$shiftDetails['dateStr'],
			$shiftDetails['startTimeStr'],
			$shiftDetails['endTimeStr'],
			$shiftDetails['roleName'],
			$reason
		);
	}

	echo json_encode($response);
}

function getShiftDetails($userShiftId, $userId) {
	GLOBAL $DB_CONN;
	$query = 'SELECT firstName, lastName, User.email, Site.title, DATE_FORMAT(startTime, "%m/%d/%Y") AS dateStr,
			TIME_FORMAT(startTime, "%l:%i %p") AS startTimeStr, TIME_FORMAT(endTime, "%l:%i %p") AS endTimeStr,
			Role.name AS roleName
		FROM UserShift
			JOIN User ON UserShift.userId = User.userId
			JOIN Shift ON UserShift.shiftId = Shift.shiftId
			JOIN Role ON UserShift.roleId = Role.roleId
			JOIN Site ON Shift.siteID = Site.siteId
		WHERE userShiftId = ? AND User.userId = ?';
	$stmt = $DB_CONN->prepare($query);
	$stmt->execute(array($userShiftId, $userId));
	return $stmt->fetch();
}

function notifyForCancelledShift($firstName, $lastName, $email, $siteTitle, $dateStr, $startTimeStr, $endTimeStr, $role, $reason) {
	if (PROD) {
		$handle = @fopen('./notificationEmails.txt', 'r');
		if ($handle != false) {
			while(!feof($handle)) {
				$toEmail = fgets($handle);
				$cancellationMessage = "A volunteer has cancelled one of their shifts: <br/>
					<b>First Name:</b> $firstName <br/>
					<b>Last Name:</b> $lastName <br/>
					<b>Email:</b> $email <br/>
					<b>Site:</b> $siteTitle <br/>
					<b>Date:</b> $dateStr <br/>
					<b>Start Time:</b> $startTimeStr <br/>
					<b>End Time:</b> $endTimeStr <br/>
					<b>Role:</b> $role <br/>
					<b>Reason:</b> $reason";
				EmailUtilities::sendHtmlFormattedEmail($toEmail, 'VITA -- Shift Cancellation', $cancellationMessage);
			}
			fclose($handle);
		}
	}
}

function signUpForShift($shiftId, $roleId) {
	GLOBAL $USER, $DB_CONN;
	$userId = $USER->getUserId();

	$response = array();
	$response['success'] = true;

	try {
		$query = "INSERT INTO UserShift (userId, shiftId, roleId)
			VALUES (?, ?, ?)";
		$stmt = $DB_CONN->prepare($query);
		
		$DB_CONN->beginTransaction();
		$success = $stmt->execute(array($userId, $shiftId, $roleId));
		$userShiftId = $DB_CONN->lastInsertId();
		$DB_CONN->commit();

		if ($success == false) {
			throw new Exception();
		} else {
			$response['userShiftId'] = $userShiftId;
		}
	} catch (Exception $e) {
		$response['success'] = false;
		$response['error'] = 'There was an error on the server. Please refresh the page and try again.';
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