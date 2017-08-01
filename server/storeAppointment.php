<?
require_once 'config.php';

storeAppointment($_POST);

function storeAppointment($data){
	GLOBAL $DB_CONN;
	$conn = $DB_CONN;

	$response = array();
	$response['success'] = false;

	

	$conn->beginTransaction();
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
		$stmt = $conn->prepare($clientInsert);
		$stmt->execute($clientParams);

		$clientId = $conn->lastInsertId();

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
		$stmt = $conn->prepare($appointmentInsert);
		print_r($appointmentParams);
		if(!$stmt->execute($appointmentParams)){
			throw new Exception("There was an issue on the server. Please refresh the page and try again.", 999);
		}

		$appointmentId = $conn->lastInsertId();
		

		$answerInsert = "INSERT INTO vita.answer
			(
				appointmentId,
				litmusQuestionId,
				possibleAnswerId
			)
			VALUES
			(
				?,
				?,
				?
			)";
		$stmt = $conn->prepare($answerInsert);

		foreach ($data['questions'] as $answer) {
			$answerParams = array(
				$appointmentId,
				$answer['id'],
				$answer['value']
			);

			$stmt->execute($answerParams);
		}

		$conn->commit();
		$response['success'] = true;
		$response['appointmentId'] = $appointmentId;
	} catch (Exception $e) {
		$conn->rollback();

		// TODO
		mail('someoneimportant@important.com', 'Please help, everything is on fire?', print_r($e, true).print_r($data, true));
	}

	## Return
	print json_encode($response);
}