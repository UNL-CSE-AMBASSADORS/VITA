<?php

$root = realpath($_SERVER['DOCUMENT_ROOT']);

require_once "$root/server/config.php";
require_once "$root/server/accessors/appointmentAccessor.class.php";
require_once "$root/server/utilities/appointmentConfirmationUtilities.class.php";

if (isset($_REQUEST['action'])) {
	switch ($_REQUEST['action']) {
		case 'doesTokenExist': doesTokenExist($_GET['token']); break;
		case 'validateClientInformation': validateClientInformation($_POST['token'], $_POST['firstName'], $_POST['lastName'], $_POST['emailAddress'], $_POST['phoneNumber']); break;
		case 'reschedule': rescheduleAppointmentWithToken($_POST['token'], $_POST['firstName'], $_POST['lastName'], $_POST['emailAddress'], $_POST['phoneNumber'], $_POST['appointmentTimeId']); break;
		case 'emailConfirmation': emailConfirmation($_POST['token'], $_POST['firstName'], $_POST['lastName'], $_POST['emailAddress'], $_POST['phoneNumber']); break;
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
		$query = 'SELECT 1
			FROM AppointmentClientReschedule
			WHERE token = ?';

		$stmt = $DB_CONN->prepare($query);
		$stmt->execute(array($token));
		$tokenExists = $stmt->fetchColumn() !== false;

		$response['exists'] = $tokenExists;
	} catch (Exception $e) {
		$response['success'] = false;
		$response['error'] = 'There was an error on the server validating the token. Please refresh the page and try again';
	}

	echo json_encode($response);
}

function validateClientInformation($token, $firstName, $lastName, $emailAddress, $phoneNumber) {	
	$response = array();
	$response['success'] = true;

	try {
		$clientInformation = getClientInformationFromToken($token);
		$clientInformationMatches = clientInformationMatches($clientInformation, $firstName, $lastName, $emailAddress, $phoneNumber);
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

function rescheduleAppointmentWithToken($token, $firstName, $lastName, $emailAddress, $phoneNumber, $appointmentTimeId) {
	$response = array();
	$response['success'] = true;

	/*
		TODO: 
		1. Validate information with token
		2. Get appointmentId (along with validation?)
		3. actually reschedule the appointment
		4. Give back the appointment confirmation information
		5. Automatically send an email??
	*/

	try {
		$clientInformation = getClientInformationFromToken($token);
		$clientInformationMatches = clientInformationMatches($clientInformation, $firstName, $lastName, $emailAddress, $phoneNumber);

		if ($clientInformationMatches === false) {
			http_response_code(401);
			throw new Exception('Provided information does not match our records.', MY_EXCEPTION);
		}

		$appointmentId = $clientInformation['appointmentId'];

		$appointmentAccessor = new AppointmentAccessor();
		$appointmentAccessor->rescheduleAppointment($appointmentId, $appointmentTimeId);

		$response['message'] = AppointmentConfirmationUtilities::generateAppointmentConfirmation($appointmentId);
	} catch (Exception $e) {
		$response['success'] = false;
		$response['error'] = $e->getCode() === MY_EXCEPTION ? $e->getMessage() : 'There was an error on the server rescheduling your appointment. Please refresh the page and try again.';
	}

	echo json_encode($response);
}

function emailConfirmation($token, $firstName, $lastName, $emailAddress, $phoneNumber) {
	$response = array();
	$response['success'] = true;

	try {
		$clientInformation = getClientInformationFromToken($token);
		$clientInformationMatches = clientInformationMatches($clientInformation, $firstName, $lastName, $emailAddress, $phoneNumber);

		if ($clientInformationMatches === false) {
			http_response_code(401);
			throw new Exception('Provided information does not match our records.', MY_EXCEPTION);
		}

		$appointmentId = $clientInformation['appointmentId'];
		$confirmationMessage = AppointmentConfirmationUtilities::generateAppointmentConfirmation($appointmentId);
		
		if (PROD && isset($clientInformation['email'])) {
			EmailUtilities::sendHtmlFormattedEmail($clientInformation['email'], 'VITA Appointment Rescheduled Confirmation', $confirmationMessage, 'noreply@vita.unl.edu');
		} else {
			$response['message'] = $confirmationMessage;
		}
	} catch (Exception $e) {
		$response['success'] = false;
		$response['error'] = $e->getCode() === MY_EXCEPTION ? $e->getMessage() : 'There was an error on the server sending the email. Please try again.';
	}

	echo json_encode($response);
}

/* 
 * Private functions
 */

function getClientInformationFromToken($token) {
	GLOBAL $DB_CONN;

	$query = 'SELECT firstName, lastName, emailAddress, phoneNumber, Appointment.appointmentId
		FROM AppointmentClientReschedule
		JOIN Appointment ON AppointmentClientReschedule.appointmentId = Appointment.appointmentId
		JOIN Client ON Appointment.clientId = Client.clientId
		WHERE token = ?';

	$stmt = $DB_CONN->prepare($query);
	if (!$stmt->execute(array($token))) {
		throw new Exception('There was an error on the server fetching information. Please refresh the page and try again.', MY_EXCEPTION);
	}

	return $stmt->fetch();
}

function clientInformationMatches($clientInformation, $firstName, $lastName, $emailAddress, $phoneNumber) {
	if ($clientInformation === false) { // PDOStatement::fetch returns false on failure http://php.net/manual/en/pdostatement.fetch.php
		return false;
	}

	$firstNameMatches = isset($clientInformation['firstName']) && $clientInformation['firstName'] === $firstName;
	$lastNameMatches = isset($clientInformation['lastName']) && $clientInformation['lastName'] === $lastName;
	$emailAddressMatches = isset($clientInformation['emailAddress']) && $clientInformation['emailAddress'] === $emailAddress;
	$phoneNumberMatches = isset($clientInformation['phoneNumber']) && $clientInformation['phoneNumber'] === $phoneNumber;

	return $firstNameMatches && $lastNameMatches && $emailAddressMatches && $phoneNumberMatches;
}