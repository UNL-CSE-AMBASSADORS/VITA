<?php

$root = realpath($_SERVER['DOCUMENT_ROOT']);

require_once "$root/server/config.php";

if (isset($_REQUEST['action'])) {
	switch ($_REQUEST['action']) {
		case 'doesTokenExist': doesTokenExist($_GET['token']); break;
		case 'validateClientInformation': validateClientInformation($_POST['token'], $_POST['firstName'], $_POST['lastName'], $_POST['emailAddress'], $_POST['phoneNumber']); break;
		default:
			die('Invalid action function. This instance has been reported.');
			break;
	}
}

function doesTokenExist($token) {
	GLOBAL $DB_CONN;

	$response = array();
	$response['success'] = true;

	try {
		$query = 'SELECT COUNT(*) AS count
			FROM AppointmentClientReschedule
			WHERE token = ?';

		$stmt = $DB_CONN->prepare($query);
		if (!$stmt->execute(array($token))) {
			throw new Exception();
		}

		$result = $stmt->fetch();
		$response['exists'] = $result['count'] > 0;
	} catch (Exception $e) {
		$response['success'] = false;
		$response['error'] = 'There was an error on the server validating the token. Please refresh the page and try again';
	}

	echo json_encode($response);
}

function validateClientInformation($token, $firstName, $lastName, $emailAddress, $phoneNumber) {	
	GLOBAL $DB_CONN;

	$response = array();
	$response['success'] = true;

	try {
		$query = 'SELECT firstName, lastName, emailAddress, phoneNumber
			FROM AppointmentClientReschedule
			JOIN Appointment ON AppointmentClientReschedule.appointmentId = Appointment.appointmentId
			JOIN Client ON Appointment.clientId = Client.clientId
			WHERE token = ?';
		
		$stmt = $DB_CONN->prepare($query);
		if (!$stmt->execute(array($token))) {
			throw new Exception('There was an error on the server fetching information. Please refresh the page and try again.', MY_EXCEPTION);
		}

		$result = $stmt->fetch();

		$clientInformationMatches = clientInformationMatches($result, $firstName, $lastName, $emailAddress, $phoneNumber);
		$response['validated'] = $clientInformationMatches;

		if (!$clientInformationMatches) {
			// TODO: increment failed count
		}
	} catch (Exception $e) {
		$response['success'] = false;
		$response['error'] = $e->getCode() === MY_EXCEPTION ? $e->getMessage() : 'There was an error on the server validating information. Please refresh the page and try again.';
	}

	echo json_encode($response);
}

function clientInformationMatches($clientQueryResult, $firstName, $lastName, $emailAddress, $phoneNumber) {
	if ($clientQueryResult === false) { // PDOStatement::fetch returns false on failure http://php.net/manual/en/pdostatement.fetch.php
		return false;
	}

	$firstNameMatches = isset($clientQueryResult['firstName']) && $clientQueryResult['firstName'] === $firstName;
	$lastNameMatches = isset($clientQueryResult['lastName']) && $clientQueryResult['lastName'] === $lastName;
	$emailAddressMatches = isset($clientQueryResult['emailAddress']) && $clientQueryResult['emailAddress'] === $emailAddress;
	$phoneNumberMatches = isset($clientQueryResult['phoneNumber']) && $clientQueryResult['phoneNumber'] === $phoneNumber;

	return $firstNameMatches && $lastNameMatches && $emailAddressMatches && $phoneNumberMatches;
}