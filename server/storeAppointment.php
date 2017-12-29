<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once "$root/server/config.php";

// TODO, WILL BE REMOVED AFTER APPOINTMENTS BEGIN THIS SEASON
date_default_timezone_set('America/Chicago'); // Use CST
$now = date('Y-m-d H:i:s');
$signupBeginsDate = '2018-01-15 00:00:00';
if ($now < $signupBeginsDate) {
	require_once "$root/server/user.class.php";
	$USER = new User();
	if (!$USER->isLoggedIn()) {
		die('Appointment signup does not begin until January 15th, 2018. Please check back then.');
	}
}
// END TODO

storeAppointment($_POST);

function storeAppointment($data){
	GLOBAL $DB_CONN;

	$response = array();
	$response['success'] = false;


	$DB_CONN->beginTransaction();
	try {

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
			$data['email'],
			$data['phone']
		);
		$stmt = $DB_CONN->prepare($clientInsert);
		$stmt->execute($clientParams);

		$clientId = $DB_CONN->lastInsertId();


		if (isset($data['dependents'])) {
			$dependentClientInsert = "INSERT INTO DependentClient
				(
					clientId,
					firstName,
					lastName
				)
				VALUES
				(
					?,
					?,
					?
				)";	
			$stmt = $DB_CONN->prepare($dependentClientInsert);						
			foreach ($data['dependents'] as $dependentClient) {
				$dependentClientParams = array( 
					$clientId,
					$dependentClient['firstName'], 
					$dependentClient['lastName']
				);
				$stmt->execute($dependentClientParams);
			}
		}


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
				(SELECT appointmentTimeId FROM AppointmentTime
					WHERE DATE(scheduledTime) = ? 
					AND TIME_FORMAT(TIME(scheduledTime), '%l:%i %p') = ?
					AND siteId = ?),
				?,
				?
			);";

		$dateTime = new DateTime($data['scheduledTime']);
		$appointmentParams = array(
			$clientId,
			$dateTime->format('Y-m-d'),
			$dateTime->format('g:i A'),
			$data['siteId'],
			$data['language'],
			$_SERVER['REMOTE_ADDR']
		);
		$stmt = $DB_CONN->prepare($appointmentInsert);
		if(!$stmt->execute($appointmentParams)){
			throw new Exception("There was an issue on the server. Please refresh the page and try again.", 999);
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

		// get site information
		$siteQuery = "SELECT address,phoneNumber FROM Site WHERE siteId = ?";
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
