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

		// get site information
		$siteQuery = "SELECT address,phoneNumber FROM vita.site WHERE siteId = ?";
		$stmt = $conn->prepare($siteQuery);
		$stmt->execute(array($data['siteId']));

		$siteInfo = $stmt->fetch();
		$siteAddress = $siteInfo['address'];
		$sitePhoneNumber = $siteInfo['phoneNumber'];

		// TODO: Make sound better
		$response['message'] = $data['firstName'].", thank you for signing up! Your appointment will be located at $siteAddress. Please arrive by ".$data['scheduledTime']." with all necessary materials. Please call $sitePhoneNumber for additional details or to reschedule. Thank you from Lincoln VITA.";

		// if email is set and passes simple validation (x@x)
		if($data['email'] && preg_match('/.+@.+/', $data['email'])){
			mail($data['email'], 'Lincoln VITA - Appointment', $response['message']);
		}
	} catch (Exception $e) {
		$conn->rollback();

		// TODO
		mail('someoneimportant@important.com', 'Please help, everything is on fire?', print_r($e, true).print_r($data, true));
	}

	## Return
	print json_encode($response);
}