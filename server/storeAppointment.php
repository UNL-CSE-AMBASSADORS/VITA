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
		$response['message'] = generateConfirmation($data['firstName'], $data['siteId'], $data['appointmentTimeId']);
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
		if (!isset($data['firstName']) || !isset($data['siteId']) || !isset($data['appointmentTimeId'])) throw new Exception('Invalid information received. Unable to send email.', MY_EXCEPTION); 

		$confirmationMessage = generateConfirmation($data['firstName'], $data['siteId'], $data['appointmentTimeId']);
		$headers = "From: noreply@vita.unl.edu\r\n";
		$headers .= 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1';

		if (PROD) {
			mail($data['email'], 'VITA Appointment Confirmation', $confirmationMessage, $headers);
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

function generateConfirmation($firstName, $siteId, $appointmentTimeId) {
	$siteInformation = getSiteInformation($siteId);
	$appointmentTimeInformation = getAppointmentTimeInformation($appointmentTimeId);

	$siteTitle = $siteInformation['title'];
	$siteAddress = $siteInformation['address'];
	$sitePhoneNumber = $siteInformation['phoneNumber'];
	$dateStr = $appointmentTimeInformation['dateStr'];
	$timeStr = $appointmentTimeInformation['timeStr'];

	$message = "<h2>Appointment Confirmation</h2>".
			$firstName.", thank you for signing up! Your appointment will be located at the $siteTitle site ($siteAddress). 
			Please arrive no later than $timeStr on $dateStr with all necessary materials (listed below). 
			Please call $sitePhoneNumber if you have any questions or would like to reschedule. 
			Thank you from Lincoln VITA.
			<h2 class='mt-3'>What to Bring for your Appointment</h2>
			<h5>Identification:</h5>
			<ul>
				<li><b>Social Security cards</b> or <b>ITIN Letters</b> for <b>EVERYONE</b> who will be included on the return</li>
				<li><b>Photo ID</b> for <b>ALL</b> tax return signers (BOTH spouses must sign if filing jointly)</li>
			</ul>
			<h5>Health Care Coverage:</h5>
			<ul>
				<li><b>Verification</b> of health insurance (1095 A, B or C)</li>
			</ul>
			<h5>Income:</h5>
			<ul>
				<li><b>W-2s</b> for wages, <b>W-2Gs</b> for gambling income</li>
				<li><b>1099s</b> for interest, dividends, unemployment, state tax refunds, pension or 401-K distributions, and other income</li>
				<li><b>Records</b> of revenue from self-employment or home-based businesses</li>
			</ul>
			<h5>Expenses:</h5>
			<ul>
				<li><b>1098s</b> for mortgage interest, student loan interest (1098-E), or tuition (1098-T), statement of property tax paid</li>
				<li><b>Statement of college student account</b> showing all charges and payments for each student on the return</li>
				<li><b>Childcare receipts</b>, including tax ID and address for childcare provider</li>
				<li><b>Records</b> of expenses for self-employment or home-based businesses</li>
			</ul>
			<h5>Miscellaneous:</h5>
			<ul>
				<li>Checking or savings account information for direct deposit/direct debit</li>
				<li>It is <b>STRONGLY RECOMMENDED</b> that you bring last year's tax return</li>
			</ul>";

	return $message;
}

function getSiteInformation($siteId) {
	GLOBAL $DB_CONN;

	$query = "SELECT address, phoneNumber, title 
		FROM Site 
		WHERE siteId = ?";
	$stmt = $DB_CONN->prepare($query);
	$stmt->execute(array($siteId));

	return $stmt->fetch();
}

function getAppointmentTimeInformation($appointmentTimeId) {
	GLOBAL $DB_CONN;

	$query = "SELECT DATE_FORMAT(scheduledTime, '%W, %M %D, %Y') AS dateStr, 
			TIME_FORMAT(scheduledTime, '%l:%i %p') as timeStr
		FROM AppointmentTime
		WHERE appointmentTimeId = ?";
	$stmt = $DB_CONN->prepare($query);
	$stmt->execute(array($appointmentTimeId));

	return $stmt->fetch();
}