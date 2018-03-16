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


	$DB_CONN->beginTransaction();
	try {
		$email = '';
		if (isset($data['email'])) {
			$email = $data['email'];
		}
		
		$clientInsert = "INSERT INTO Client
			(
				firstName,
				lastName,
				emailAddress,
				phoneNumber
			)
			VALUES
			(
				?,
				?,
				?,
				?
			);";
		$clientParams = array(
			$data['firstName'],
			$data['lastName'],
			$email,
			$data['phone']
		);
		$stmt = $DB_CONN->prepare($clientInsert);
		$stmt->execute($clientParams);

		$clientId = $DB_CONN->lastInsertId();


		$appointmentInsert = "INSERT INTO Appointment
			(
				clientId,
				appointmentTimeId,
				language,
				ipAddress
			)
			VALUES
			(
				?,
				?,
				?,
				?
			);";

		$appointmentParams = array(
			$clientId,
			$data['appointmentTimeId'],
			$data['language'],
			$_SERVER['REMOTE_ADDR']
		);
		$stmt = $DB_CONN->prepare($appointmentInsert);
		if(!$stmt->execute($appointmentParams)){
			throw new Exception("There was an issue on the server. Please refresh the page and try again.", MY_EXCEPTION);
		}

		$appointmentId = $DB_CONN->lastInsertId();


		$answerInsert = "INSERT INTO Answer
			(
				appointmentId,
				questionId,
				possibleAnswerId
			)
			VALUES
			(
				?,
				?,
				?
			)";
		$stmt = $DB_CONN->prepare($answerInsert);

		foreach ($data['questions'] as $answer) {
			$answerParams = array(
				$appointmentId,
				$answer['id'],
				$answer['value']
			);

			$stmt->execute($answerParams);
		}

		$DB_CONN->commit();
		$response['success'] = true;
		$response['appointmentId'] = $appointmentId;
		$response['message'] = generateAppointmentConfirmation($appointmentId);
	} catch (Exception $e) {
		$DB_CONN->rollback();
		
		// TODO
		// mail('vita@cse.unl.edu', 'Please help, everything is on fire?', print_r($e, true).print_r($data, true));
	}

	## Return
	print json_encode($response);
}

function emailConfirmation($data) {
	$response = array();
	$response['success'] = false;

	try {
		if (!isset($data['email']) || !preg_match('/.+@.+/', $data['email'])) throw new Exception('Invalid email address given. Unable to send email.', MY_EXCEPTION);
		if (!isset($data['appointmentId'])) throw new Exception('Invalid information received. Unable to send email.', MY_EXCEPTION); 

		$confirmationMessage = AppointmentConfirmationUtilities::generateAppointmentConfirmation($data['appointmentId']);
		
		if (PROD) {
			EmailUtilities::sendHtmlFormattedEmail($data['email'], 'VITA Appointment Confirmation', $confirmationMessage, 'noreply@vita.unl.edu');
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
