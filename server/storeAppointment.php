<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once "$root/server/config.php";
require_once "$root/server/utilities/emailUtilities.class.php";
require_once "$root/server/utilities/appointmentConfirmationUtilities.class.php";

if (isset($_REQUEST['action'])) {
	switch ($_REQUEST['action']) {
		case 'storeAppointment': storeAppointment($_POST); break;
		case 'emailConfirmation': emailConfirmation($_REQUEST); break;
		default:
			die('Invalid action function. This instance has been reported.');
			break;
	}
}

function storeAppointment($data){
	GLOBAL $DB_CONN;

	$response = array();
	$response['success'] = false;


	try {
		$DB_CONN->beginTransaction();

		$email = null;
		if (isset($data['email'])) {
			$email = $data['email'];
		}

		$clientId = insertClient($data['firstName'], $data['lastName'], $email, $data['phone']);
		$appointmentId = insertAppointment($clientId, $data['appointmentTimeId'], $data['language'], $_SERVER['REMOTE_ADDR']);
		$selfServiceAppointmentRescheduleTokenId = insertSelfServiceAppointmentRescheduleToken($appointmentId);
		insertAnswers($appointmentId, $data['questions']);
		$DB_CONN->commit();

		$response['success'] = true;
		$response['appointmentId'] = $appointmentId;
		$response['message'] = AppointmentConfirmationUtilities::generateAppointmentConfirmation($appointmentId);
	} catch (Exception $e) {
		$DB_CONN->rollback();
		
		// TODO
		// mail('vita@cse.unl.edu', 'Please help, everything is on fire?', print_r($e, true).print_r($data, true));
	}

	print json_encode($response);
}

function insertClient($firstName, $lastName, $email, $phoneNumber) {
	GLOBAL $DB_CONN;

	$clientInsert = 'INSERT INTO Client (firstName, lastName, emailAddress, phoneNumber)
		VALUES (?, ?, ?, ?);';
	$clientParams = array($firstName, $lastName, $email, $phoneNumber);

	$stmt = $DB_CONN->prepare($clientInsert);
	if (!$stmt->execute($clientParams)) {
		throw new Exception("There was an issue on the server. Please refresh the page and try again.", MY_EXCEPTION);
	}

	return $DB_CONN->lastInsertId();
}

function insertAppointment($clientId, $appointmentTimeId, $language, $ipAddress) {
	GLOBAL $DB_CONN;

	$appointmentInsert = 'INSERT INTO Appointment (clientId, appointmentTimeId, language, ipAddress)
		VALUES (?, ?, ?, ?)';
	$appointmentParams = array($clientId, $appointmentTimeId, $language, $ipAddress);

	$stmt = $DB_CONN->prepare($appointmentInsert);
	if(!$stmt->execute($appointmentParams)){
		throw new Exception("There was an issue on the server. Please refresh the page and try again.", MY_EXCEPTION);
	}

	return $DB_CONN->lastInsertId();
}

function insertSelfServiceAppointmentRescheduleToken($appointmentId) {
	GLOBAL $DB_CONN;

	$insertStatement = 'INSERT INTO SelfServiceAppointmentRescheduleToken (appointmentId, token)
		VALUES (?, ?);';
	$token = md5(strval($appointmentId));
	$params = array($appointmentId, $token);

	$stmt = $DB_CONN->prepare($insertStatement);
	if (!$stmt->execute($params)) {
		throw new Exception("There was an issue on the server. Please refresh the page and try again.", MY_EXCEPTION);
	}

	return $DB_CONN->lastInsertId();
}

function insertAnswers($appointmentId, $answers) {
	GLOBAL $DB_CONN;

	$answerInsert = 'INSERT INTO Answer (appointmentId, questionId, possibleAnswerId)
		VALUES (?, ?, ?);';
	$stmt = $DB_CONN->prepare($answerInsert);

	foreach ($answers as $answer) {
		$answerParams = array($appointmentId, $answer['id'], $answer['value']);

		if (!$stmt->execute($answerParams)) {
			throw new Exception("There was an issue on the server. Please refresh the page and try again", MY_EXCEPTION);
		}
	}
}


function emailConfirmation($data) {
	$response = array();
	$response['success'] = false;

	try {
		if (!isset($data['email']) || !preg_match('/.+@.+/', $data['email'])) throw new Exception('Invalid email address given. Unable to send email.', MY_EXCEPTION);
		if (!isset($data['appointmentId'])) throw new Exception('Invalid information received. Unable to send email.', MY_EXCEPTION); 

		$confirmationMessage = AppointmentConfirmationUtilities::generateAppointmentConfirmation($data['appointmentId']);
		
		if (PROD) {
			EmailUtilities::sendHtmlFormattedEmail($data['email'], 'VITA Appointment Confirmation', $confirmationMessage);
		} else {
			$response['message'] = $confirmationMessage;
		}
		$response['success'] = true;
	} catch (Exception $e) {
		if ($e->getCode() === MY_EXCEPTION) {
			$response['error'] = $e->getMessage();
		} else {
			$response['error'] = 'There was an error on the server, please try again. If the problem persists, please print this page instead.';
		}
	}

	echo json_encode($response);
}
