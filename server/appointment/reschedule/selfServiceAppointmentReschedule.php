<?php

$root = realpath($_SERVER['DOCUMENT_ROOT']);

require_once "$root/server/config.php";
require_once "$root/server/accessors/appointmentAccessor.class.php";
require_once "$root/server/utilities/appointmentConfirmationUtilities.class.php";
require_once "$root/server/utilities/emailUtilities.class.php";
require_once "$root/server/accessors/noteAccessor.class.php";

if (isset($_REQUEST['action'])) {
	switch ($_REQUEST['action']) {
		case 'doesTokenExist': doesTokenExist($_GET['token']); break;
		case 'validateClientInformation': isClientInformationValid($_POST['token'], $_POST['firstName'], $_POST['lastName'], $_POST['emailAddress'], $_POST['phoneNumber']); break;
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
			FROM SelfServiceAppointmentRescheduleToken
			JOIN Appointment ON SelfServiceAppointmentRescheduleToken.appointmentId = Appointment.appointmentId
			JOIN AppointmentTime ON Appointment.appointmentTimeId = AppointmentTime.appointmentTimeId
			JOIN Site ON AppointmentTime.siteId = Site.siteId
			LEFT JOIN ServicedAppointment ON Appointment.appointmentId = ServicedAppointment.appointmentId
			WHERE token = ?';

		$stmt = $DB_CONN->prepare($query);
		$stmt->execute(array($token));
		$appointmentInformation = $stmt->fetch();

		$tokenExists = (bool)$appointmentInformation !== false;
		$response['exists'] = $tokenExists;
		
		if ($tokenExists) {
			$response['phoneNumber'] = $appointmentInformation['phoneNumber'];

			$appointmentValidForRescheduleArray = isAppointmentValidForReschedule($appointmentInformation);
			$response = array_merge($response, $appointmentValidForRescheduleArray);
		}
	} catch (Exception $e) {
		$response['success'] = false;
		$response['error'] = 'There was an error on the server validating the token. Please refresh the page and try again';
	}

	echo json_encode($response);
}

function isClientInformationValid($token, $firstName, $lastName, $emailAddress, $phoneNumber) {	
	$response = array();
	$response['success'] = true;

	try {
		$clientInformation = getClientInformationFromToken($token);
		$clientInformationMatches = doesClientInformationMatch($clientInformation, $firstName, $lastName, $emailAddress, $phoneNumber);
		$response['validated'] = $clientInformationMatches;

		if ($clientInformationMatches) {
			// Grab appointment information so we can display the site/date/time and see what type of appointment it is
			$appointmentInformation = getAppointmentInformationFromToken($token);

			$response['appointmentType'] = $appointmentInformation['appointmentType'];
			$response['site'] = array(
				'title' => $appointmentInformation['title'],
				'address' => $appointmentInformation['address']
			);
			$response['scheduledTime'] = $appointmentInformation['scheduledTimeStr'];
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
		$clientInformation = validateClientInformation($token, $firstName, $lastName, $emailAddress, $phoneNumber);
		
		$appointmentId = $clientInformation['appointmentId'];
		$appointmentAccessor = new AppointmentAccessor();
		$appointmentAccessor->rescheduleAppointment($appointmentId, $appointmentTimeId);

		$noteAccessor = new NoteAccessor();
		$notes = $noteAccessor->addNote($appointmentId, 'Rescheduled by client [Automatic Note]');

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
		$clientInformation = validateClientInformation($token, $firstName, $lastName, $emailAddress, $phoneNumber);
		
		$appointmentId = $clientInformation['appointmentId'];
		$appointmentAccessor = new AppointmentAccessor();
		$appointmentAccessor->cancelAppointment($appointmentId);

		$noteAccessor = new NoteAccessor();
		$notes = $noteAccessor->addNote($appointmentId, 'Cancelled by client [Automatic Note]');
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
		$clientInformation = validateClientInformation($token, $firstName, $lastName, $emailAddress, $phoneNumber);

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

function isAppointmentValidForReschedule($appointmentInformation) {
	// See if it has been cancelled
	$isAppointmentCancelled = isset($appointmentInformation['cancelled']) && (bool)$appointmentInformation['cancelled'] === true;
	if ($isAppointmentCancelled) {
		return array(
			'valid' => false,
			'reason' => 'Your appointment has already been cancelled.'
		);
	}
	
	// See if the appointment has already been started
	$appointmentHasBeenStarted = isset($appointmentInformation['timeIn']);
	if ($appointmentHasBeenStarted) {
		return array(
			'valid' => false,
			'reason' => 'Your appointment has been marked as started.'
		);
	}

	// See if the current time is past the scheduled time
	date_default_timezone_set('America/Chicago');
	$now = date('Y-m-d H:i:s');
	$scheduledTime = $appointmentInformation['scheduledTime'];
	$pastScheduledTime = $now > $scheduledTime;
	if ($pastScheduledTime) {
		return array(
			'valid' => false,
			'reason' => "Your appointment's scheduled time is in the past."
		);
	}

	return array('valid' => true);
}

function validateClientInformation($token, $firstName, $lastName, $emailAddress, $phoneNumber) {
	$clientInformation = getClientInformationFromToken($token);
	if ($clientInformation === false) { // PDOStatement::fetch returns false on failure http://php.net/manual/en/pdostatement.fetch.php
		http_response_code(500);
		throw new Exception('There was an error validating information. Please refresh the page and try again.', MY_EXCEPTION);
	}

	$clientInformationMatches = doesClientInformationMatch($clientInformation, $firstName, $lastName, $emailAddress, $phoneNumber);
	if ($clientInformationMatches === false) {
		http_response_code(401);
		throw new Exception('Provided information does not match our records.', MY_EXCEPTION);
	}

	return $clientInformation;
}

function getClientInformationFromToken($token) {
	GLOBAL $DB_CONN;

	$query = 'SELECT firstName, lastName, emailAddress, phoneNumber, Appointment.appointmentId
		FROM SelfServiceAppointmentRescheduleToken
			JOIN Appointment ON SelfServiceAppointmentRescheduleToken.appointmentId = Appointment.appointmentId
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

function doesClientInformationMatch($clientInformation, $firstName, $lastName, $emailAddress, $phoneNumber) {
	$firstNameMatches = isset($clientInformation['firstName']) && strtolower($clientInformation['firstName']) === strtolower($firstName);
	$lastNameMatches = isset($clientInformation['lastName']) && strtolower($clientInformation['lastName']) === strtolower($lastName);
	$emailAddressMatches = ($clientInformation['emailAddress'] === null && $emailAddress === '') 
		|| (isset($clientInformation['emailAddress']) && strtolower($clientInformation['emailAddress']) === strtolower($emailAddress));
	$phoneNumberMatches = isset($clientInformation['phoneNumber']) && cleanPhoneNumber($clientInformation['phoneNumber']) === cleanPhoneNumber($phoneNumber);
	
	return $firstNameMatches && $lastNameMatches && $emailAddressMatches && $phoneNumberMatches;
}

function cleanPhoneNumber($phoneNumber) {
	$find = array('(', ')', '+', '-', ' ');
	$replace = '';
	return str_replace($find, $replace, $phoneNumber);
}

function getAppointmentInformationFromToken($token) {
	GLOBAL $DB_CONN;

	$query = 'SELECT DATE_FORMAT(scheduledTime, "%W, %M %D, %Y at %l:%i %p") AS scheduledTimeStr, 
		Site.title, Site.address, AppointmentType.lookupName AS appointmentType
		FROM SelfServiceAppointmentRescheduleToken
			JOIN Appointment ON SelfServiceAppointmentRescheduleToken.appointmentId = Appointment.appointmentId
			JOIN AppointmentTime ON Appointment.appointmentTimeId = AppointmentTime.appointmentTimeId
			JOIN AppointmentType ON AppointmentTime.appointmentTypeId = AppointmentType.appointmentTypeId
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
