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
		case 'cancel': cancelAppointmentWithToken($_POST['token'], $_POST['firstName'], $_POST['lastName'], $_POST['emailAddress'], $_POST['phoneNumber']); break;
		case 'emailConfirmation': emailConfirmationWithToken($_POST['token'], $_POST['firstName'], $_POST['lastName'], $_POST['emailAddress'], $_POST['phoneNumber']); break;
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
		$query = 'SELECT DATE_FORMAT(scheduledTime, "%Y-%m-%d %H:%i:%S") AS scheduledTime, cancelled, timeIn,
				Site.phoneNumber
			FROM AppointmentClientReschedule
			JOIN Appointment ON AppointmentClientReschedule.appointmentId = Appointment.appointmentId
			JOIN AppointmentTime ON Appointment.appointmentTimeId = AppointmentTime.appointmentTimeId
			JOIN Site ON AppointmentTime.siteId = Site.siteId
			LEFT JOIN ServicedAppointment ON Appointment.appointmentId = ServicedAppointment.appointmentId
			WHERE token = ?';

		$stmt = $DB_CONN->prepare($query);
		$stmt->execute(array($token));
		$appointmentInformation = $stmt->fetch();

		$tokenExists = $appointmentInformation != false;
		$response['exists'] = $tokenExists;

		if ($tokenExists) {
			$appointmentValidForRescheduleArray = isAppointmentValidForReschedule($appointmentInformation);
			$response = array_merge($response, $appointmentValidForRescheduleArray);
		}
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

		if ($clientInformationMatches) {
			// Grab appointment information so we can display the site/date/time
			$appointmentInformation = getAppointmentInformationFromToken($token);

			$response['site'] = array(
				'title' => $appointmentInformation['title'],
				'address' => $appointmentInformation['address']
			);
			$response['scheduledTime'] = $appointmentInformation['scheduledTimeStr'];
		} else {
			handleClientInformationValidationFailure($token);
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

function cancelAppointmentWithToken($token, $firstName, $lastName, $emailAddress, $phoneNumber) {
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
		$appointmentAccessor = new AppointmentAccessor();
		$appointmentAccessor->cancelAppointment($appointmentId);
	} catch (Exception $e) {
		$response['success'] = false;
		$response['error'] = $e->getCode() === MY_EXCEPTION ? $e->getMessage() : 'There was an error on the server cancelling the appointment. Please try again.';
	}

	echo json_encode($response);
}

function emailConfirmationWithToken($token, $firstName, $lastName, $emailAddress, $phoneNumber) {
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

/*
TODO: THIS LAST SCENARIO IS NOT ACCOUNT FOR YET
- Being locked out as well after so many invalid attempts
*/

function isAppointmentValidForReschedule($appointmentInformation) {
	// See if it has been cancelled
	$isAppointmentCancelled = isset($appointmentInformation['cancelled']) && $appointmentInformation['cancelled'] == true;
	if ($isAppointmentCancelled) {
		return [
			'valid' => false,
			'reason' => 'Your appointment has already been cancelled.'
		];
	}
	
	// See if the appointment has already been started
	$appointmentHasBeenStarted = isset($appointmentInformation['timeIn']);
	if ($appointmentHasBeenStarted) {
		return [
			'valid' => false,
			'reason' => 'Your appointment has been marked as started.'
		];
	}

	// See if the current time is past the scheduled time
	date_default_timezone_set('America/Chicago');
	$now = date('Y-m-d H:i:s');
	$scheduledTime = $appointmentInformation['scheduledTime'];
	$pastScheduledTime = $now > $scheduledTime;
	if ($pastScheduledTime) {
		return [
			'valid' => false,
			'reason' => "Your appointment's scheduled time is in the past."
		];
	}

	return [ 'valid' => true ];
}

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

	$clientInformation = $stmt->fetch();
	if (!$clientInformation) {
		throw new Exception('There was an error on the server fetching information. Please refresh the page and try again.', MY_EXCEPTION);
	}

	return $clientInformation;
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

function getAppointmentInformationFromToken($token) {
	GLOBAL $DB_CONN;

	$query = 'SELECT DATE_FORMAT(scheduledTime, "%W, %M %D, %Y at %l:%i %p") AS scheduledTimeStr, 
		Site.title, Site.address
		FROM AppointmentClientReschedule
		JOIN Appointment ON AppointmentClientReschedule.appointmentId = Appointment.appointmentId
		JOIN AppointmentTime ON Appointment.appointmentTimeId = AppointmentTime.appointmentTimeId
		JOIN Site ON AppointmentTime.siteId = Site.siteId
		WHERE token = ?';
	
	$stmt = $DB_CONN->prepare($query);
	if (!$stmt->execute(array($token))) {
		throw new Exception('There was an error on the server fetching information. Please refresh the page and try again.', MY_EXCEPTION);
	}

	$appointmentInformation = $stmt->fetch();
	if (!$appointmentInformation) {
		throw new Exception('There was an error on the server fetching information. Please refresh the page and try again.', MY_EXCEPTION);
	}

	return $appointmentInformation;
}

function handleClientInformationValidationFailure($token) {
	// Not actually doing anything right now, but we could in the future.
}
