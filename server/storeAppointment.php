<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once "$root/server/config.php";

storeAppointment($_POST);

function storeAppointment($data){
	GLOBAL $DB_CONN;

	$response = array();
	$response['success'] = false;


	$DB_CONN->beginTransaction();
	try {

		$clientInsert = "INSERT INTO vita.client
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
			$data['email'],
			$data['phone']
		);
		$stmt = $DB_CONN->prepare($clientInsert);
		$stmt->execute($clientParams);

		$clientId = $DB_CONN->lastInsertId();

		$appointmentInsert = "INSERT INTO vita.appointment
			(
				clientId,
				scheduledTime,
				siteId
			)
			VALUES
			(
				?,
				?,
				?
			);";
		$appointmentParams = array(
			$clientId,
			$data['scheduledTime'],
			$data['siteId']
		);
		$stmt = $DB_CONN->prepare($appointmentInsert);
		if(!$stmt->execute($appointmentParams)){
			throw new Exception("There was an issue on the server. Please refresh the page and try again.", 999);
		}

		$appointmentId = $DB_CONN->lastInsertId();


		$answerInsert = "INSERT INTO vita.answer
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

		// get site information
		$siteQuery = "SELECT address,phoneNumber FROM vita.site WHERE siteId = ?";
		$stmt = $DB_CONN->prepare($siteQuery);
		$stmt->execute(array($data['siteId']));

		$siteInfo = $stmt->fetch();
		$siteAddress = $siteInfo['address'];
		$sitePhoneNumber = $siteInfo['phoneNumber'];

		// TODO: Make sound better
		$response['message'] = $data['firstName'].", thank you for signing up! Your appointment will be located at $siteAddress. Please arrive by ".$data['scheduledTime']." with all necessary materials. Please call $sitePhoneNumber for additional details or to reschedule. Thank you from Lincoln VITA.";

		// if email is set and passes simple validation (x@x)
		if($data['email'] && preg_match('/.+@.+/', $data['email'])){
			// mail($data['email'], 'Lincoln VITA - Appointment', $response['message']);
		}
	} catch (Exception $e) {
		$DB_CONN->rollback();

		// TODO
		// mail('vita@cse.unl.edu', 'Please help, everything is on fire?', print_r($e, true).print_r($data, true));
	}

	## Return
	print json_encode($response);
}
