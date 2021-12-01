<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once "$root/server/config.php";
require_once "$root/server/utilities/emailUtilities.class.php";
require_once "$root/server/utilities/appointmentConfirmationUtilities.class.php";

if (isset($_REQUEST['action'])) {
	switch ($_REQUEST['action']) {
		case 'storeAppointment': storeAppointment($_POST); break;
		case 'emailConfirmation': emailConfirmation($_REQUEST); break;
		case 'getExistingClientAppointments': getExistingClientAppointments($_POST['firstName'], $_POST['lastName']); break;
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
			$email = trim($data['email']);
		}

		$clientId = insertClient($data['firstName'], $data['lastName'], $email, $data['phone'], $data['bestTimeToCall']);
		$appointmentId = insertAppointment($clientId, $data['appointmentTimeId'], $data['language'], $_SERVER['REMOTE_ADDR']);
		$selfServiceAppointmentRescheduleTokenId = insertSelfServiceAppointmentRescheduleToken($appointmentId);
		insertAnswers($appointmentId, $data['questions']);
		$DB_CONN->commit();

		$response['success'] = true;
		$response['appointmentId'] = $appointmentId;
		$response['message'] = AppointmentConfirmationUtilities::generateAppointmentConfirmation($appointmentId);
	} catch (Exception $e) {
		$DB_CONN->rollback();
		$response['message'] = 'An error occurred while trying to confirm the appointment. Please try again in a few minutes.';
	}

	print json_encode($response);
}

function insertClient($firstName, $lastName, $email, $phoneNumber, $bestTimeToCall) {
	GLOBAL $DB_CONN;

	$clientInsert = 'INSERT INTO Client (firstName, lastName, emailAddress, phoneNumber, bestTimeToCall)
		VALUES (?, ?, ?, ?, ?);';
	$clientParams = array($firstName, $lastName, $email, $phoneNumber, $bestTimeToCall);

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
		if (!isset($data['email']) || !preg_match('/.+@.+/', $data['email'])) throw new Exception('Unable to send confirmation email--An invalid email address was given.', MY_EXCEPTION);
		if (!isset($data['appointmentId'])) throw new Exception('Unable to send confirmation email--Invalid information was received.', MY_EXCEPTION); 

		// Ensure the email address given matches that of the appointment before sending email
		$appointmentId = $data['appointmentId'];
		$emailAddressToSendTo = $data['email'];
		$clientInformation = getClientInformationForAppointmentId($appointmentId);
		$emailAddressMatches = isset($clientInformation['emailAddress']) && strtolower($clientInformation['emailAddress']) === strtolower($emailAddressToSendTo);
		if ($emailAddressMatches === false) {
			http_response_code(401);
			throw new Exception('The email provided does not match what was given during appointment sign up.', MY_EXCEPTION);
		}

		$confirmationMessage = AppointmentConfirmationUtilities::generateAppointmentConfirmation($appointmentId);
		if (PROD) {
			EmailUtilities::sendHtmlFormattedEmail($emailAddressToSendTo, 'VITA Appointment Confirmation', $confirmationMessage);
		} else {
			$response['message'] = $confirmationMessage;
		}
		$response['success'] = true;
	} catch (Exception $e) {
		if ($e->getCode() === MY_EXCEPTION) {
			$response['error'] = $e->getMessage();
		} else {
			$response['error'] = 'There was an error on the server sending the confirmation email, please try again. If the problem persists, please print this page instead.';
		}
	}

	echo json_encode($response);
}


function getClientInformationForAppointmentId($appointmentId) {
	GLOBAL $DB_CONN;

	$query = 'SELECT emailAddress
		FROM Client
			JOIN Appointment ON Client.clientId = Appointment.clientId
		WHERE Appointment.appointmentId = ?';

	$stmt = $DB_CONN->prepare($query);
	if (!$stmt->execute(array($appointmentId))) {
		throw new Exception('There was an error on the server sending the appointment confirmation email. Please print the confirmation page instead.', MY_EXCEPTION);
	}

	$clientInformation = $stmt->fetch();
	if (!$clientInformation) {
		throw new Exception('There was an error on the server sending the appointment confirmation email. Please print the confirmation page instead..', MY_EXCEPTION);
	}

	return $clientInformation;
}


function getExistingClientAppointments($firstName, $lastName) {
	GLOBAL $DB_CONN;
	
	$response = array();
	$response['success'] = true;
	try {
		// uses same cancelled logic from getTimes.php : getAppointmentTimesFromDatabase()
		$query = 'SELECT
			SUM(IF(apt.appointmentId IS NOT NULL AND (sa.cancelled IS NULL OR sa.cancelled = FALSE), 1, 0)) AS numberExistingAppointments
			FROM vita.appointment apt
			LEFT JOIN client ON apt.clientId = client.clientId
			LEFT JOIN servicedappointment sa ON apt.appointmentId = sa.appointmentId
			WHERE firstName = ? AND lastName = ?
			GROUP BY firstName, lastName';

		$stmt = $DB_CONN->prepare($query);
		$stmt->execute(array($firstName, $lastName));
		
		$numberExistingAppointments = $stmt->fetch(PDO::FETCH_ASSOC);
		$response['numberExistingAppointments'] = $numberExistingAppointments;
	} catch (Exception $e) {
		$response['success'] = false;
		$response['error'] = 'There was an error on the server retrieving appointment information. Please try again later.';
	}

	echo json_encode($response);
}