<?php

$root = realpath($_SERVER['DOCUMENT_ROOT']);

require_once "$root/server/config.php";
require_once "$root/vendor/autoload.php";
require_once "$root/server/utilities/emailUtilities.class.php";

use MicrosoftAzure\Storage\Blob\BlobRestProxy;



if (isset($_REQUEST['action'])) {
	switch ($_REQUEST['action']) {
		case 'doesTokenExist': doesTokenExist($_GET['token']); break;
		case 'validateClientInformation': isClientInformationValid($_POST['token'], $_POST['firstName'], $_POST['lastName'], $_POST['emailAddress'], $_POST['phoneNumber']); break;
		case 'upload': uploadDocument($_POST['token'], $_POST['firstName'], $_POST['lastName'], $_POST['emailAddress'], $_POST['phoneNumber']); break;
		case 'markAppointmentAsReady': markAppointmentAsReady($_POST['token'], $_POST['firstName'], $_POST['lastName'], $_POST['emailAddress'], $_POST['phoneNumber']); break;
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
		$query = 'SELECT 1
			FROM SelfServiceAppointmentRescheduleToken
			WHERE token = ?';

		$stmt = $DB_CONN->prepare($query);
		$stmt->execute(array($token));
		$row = $stmt->fetch();

		$tokenExists = (bool)$row !== false;
		$response['exists'] = $tokenExists;
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
			$response['residentialAppointment'] = $clientInformation['appointmentType'] === 'residential';
			$response['isIowaAppointment'] = false; // TODO: Set this based on appointment information
		}
	} catch (Exception $e) {
		$response['success'] = false;
		$response['error'] = $e->getCode() === MY_EXCEPTION ? $e->getMessage() : 'There was an error on the server validating information. Please refresh the page and try again.';
	}

	echo json_encode($response);
}


function uploadDocument($token, $firstName, $lastName, $emailAddress, $phoneNumber) {
	$response = array();
	$response['success'] = true;

	try {
		// Validate file information
		if (!isset($_FILES['file'])) {
			throw new Exception('No file provided', MY_EXCEPTION);
		}

		$uploadedFile = $_FILES['file'];
		if (!isset($uploadedFile['name'], $uploadedFile['type'], $uploadedFile['size'], $uploadedFile['tmp_name'], $uploadedFile['error'])) {
			throw new Exception('Invalid file information', MY_EXCEPTION);
		}

		$uploadedFileName = trim($uploadedFile['name']);
		$uploadedFileType = $uploadedFile['type'];
		$uploadedFileSize = $uploadedFile['size'];
		$uploadedFileTempName = $uploadedFile['tmp_name'];
		$uploadedFileErrorCode = $uploadedFile['error'];
		if ($uploadedFileName === '') {
			throw new Exception('Error: File name is empty', MY_EXCEPTION);
		}
		if (strlen($uploadedFileName) > 200) {
			throw new Exception('Error: File name is too long, it must be 200 characters or less', MY_EXCEPTION);
		}
		if ($uploadedFileErrorCode == 1 || $uploadedFileSize > 10000000) {
			throw new Exception('Error: File is too big, max size is 10MB', MY_EXCEPTION);
		}
		if (!in_array($uploadedFileType, ['application/pdf', 'image/jpeg', 'image/png'])) {
			throw new Exception('Error: Unsupported file type. Must be of type PDF, JPEG, JPG, or PNG', MY_EXCEPTION);
		}
		if (!endsWith($uploadedFileName, '.pdf') && !endsWith($uploadedFileName, '.jpeg') && !endsWith($uploadedFileName, '.jpg') && !endsWith($uploadedFileName, '.png')) {
			throw new Exception('Error: Unsupported file extension. Must be .pdf, .jpeg, .jpg, or .png', MY_EXCEPTION);
		}
		if ($uploadedFileSize <= 0) {
			throw new Exception('Error: File is empty', MY_EXCEPTION);
		}
		if ($uploadedFileErrorCode !== 0) {
			throw new Exception('Error uploading file', MY_EXCEPTION);
		}

		// Check if file is Fillable Form 14446 and, if so, that it has changed
		if (preg_match('/(.*)f14446VirtualLincolnVita(.*)\.pdf(.*)/i', $uploadedFileName)) {
			validateForm14446HasChanged($uploadedFileTempName);
		}

		// Check if file is Fillable Intake Form 13614C and, if so, that it has changed
		if (preg_match('/(.*)IntakeForm_13614C(.*)\.pdf(.*)/i', $uploadedFileName)) {
			validateForm13614CHasChanged($uploadedFileTempName);
		}

		// Check if file is Fillable Intake Form 13614NR and, if so, that it has changed
		if (preg_match('/(.*)NonResidentIntakeForm_F13614NR(.*)\.pdf(.*)/i', $uploadedFileName)) {
			validateForm13614NRHasChanged($uploadedFileTempName);
		}
		
		// Validate the client information
		$clientInformation = validateClientInformation($token, $firstName, $lastName, $emailAddress, $phoneNumber);
		$appointmentId = $clientInformation['appointmentId'];
		$appointmentType = $clientInformation['appointmentType'];
		$isIowaAppointment = false; // TODO: Set this based on appointment information

		// Upload the user's file to Azure BLOB Storage
		$containerName = 'ty2019';
		$fileContent = fopen($uploadedFileTempName, 'r');
		$fileNameToSave = $firstName.'_'.$lastName."/$appointmentId/".uniqid()."-$uploadedFileName";
		if ($appointmentType === 'residential') {
			$fileNameToSave = 'residential/'.$fileNameToSave;
		} else {
			$fileNameToSave = 'non-residential/'.$fileNameToSave;
		}

		$azureBlobStorageConnectionString = $isIowaAppointment
			? UIOWA_AZURE_BLOB_STORAGE_CONNECTION_STRING
			: AZURE_BLOB_STORAGE_CONNECTION_STRING;

		$blobClient = BlobRestProxy::createBlobService($azureBlobStorageConnectionString);
		$blobClient->createBlockBlob($containerName, $fileNameToSave, $fileContent);
	} catch (Exception $e) {
		$response['success'] = false;
		$response['error'] = $e->getCode() === MY_EXCEPTION ? $e->getMessage() : 'There was an error on the server uploading your document. Please refresh the page and try again.';
	}

	echo json_encode($response);
}


function markAppointmentAsReady($token, $firstName, $lastName, $emailAddress, $phoneNumber) {
	$response = array();
	$response['success'] = true;

	try {
		// Validate the client information
		$clientInformation = validateClientInformation($token, $firstName, $lastName, $emailAddress, $phoneNumber);
		$appointmentId = $clientInformation['appointmentId'];
		$bestTimeToCall = $clientInformation['bestTimeToCall'];
		$appointmentType = $clientInformation['appointmentType'];
		$isIowaAppointment = false; // TODO: Set this based on appointment information

		// Email volunteers saying it's ready to go
		if (PROD) {
			$emailJsonFileName = $isIowaAppointment 
				? './uiowa_notificationEmails.json'
				: './notificationEmails.json';

			$emailJsonString = file_get_contents($emailJsonFileName);
			$emailsJson = json_decode($emailJsonString, true);
			$toEmailsString = $appointmentType === 'residential' ? $emailsJson['residential'] : $emailsJson['non-residential'];

			$readyMessage = "A client has marked their appointment as ready: <br/>
				<b>First Name:</b> $firstName <br/>
				<b>Last Name:</b> $lastName <br/>
				<b>Appointment ID:</b> $appointmentId <br/>
				<b>Phone Number:</b> $phoneNumber <br/>
				<b>Best Time to Call (if new appointment):</b> $bestTimeToCall <br/> 
				<b>Email (if provided):</b> $emailAddress <br/>
				<b>Type:</b> $appointmentType";
			EmailUtilities::sendHtmlFormattedEmail($toEmailsString, 'VITA -- Appointment Ready', $readyMessage);
		}
	} catch (Exception $e) {
		$response['success'] = false;
		$response['error'] = $e->getCode() === MY_EXCEPTION ? $e->getMessage() : 'There was an error on the server marking this appointment as ready. Please refresh the page and try again.';
	}

	echo json_encode($response);
}

/* 
 * Private functions
 */

function validateForm14446HasChanged($uploadedFileTempName) {
	GLOBAL $root;

	if (!hasFileChanged($uploadedFileTempName, "$root/server/download/documents/f14446VirtualLincolnVita.pdf")) {
		throw new Exception('Error: The uploaded Form 14446 does not appear to have been changed. Verify your changes and then save the file to your system and re-upload the file.', MY_EXCEPTION);
	}
}

function validateForm13614CHasChanged($uploadedFileTempName) {
	GLOBAL $root;

	if (!hasFileChanged($uploadedFileTempName, "$root/server/download/documents/IntakeForm_13614C.pdf")) {
		throw new Exception('Error: The uploaded Intake Form 13614-C does not appear to have been changed. Verify your changes and then save the file to your system and re-upload the file.', MY_EXCEPTION);
	}
}

function validateForm13614NRHasChanged($uploadedFileTempName) {
	GLOBAL $root;

	if (!hasFileChanged($uploadedFileTempName, "$root/server/download/documents/NonResidentIntakeForm_F13614NR.pdf")) {
		throw new Exception('Error: The uploaded Intake Form 13614-NR does not appear to have changed. Verify your changes and then save the file to your system and re-upload the file.', MY_EXCEPTION);
	}
}

function hasFileChanged($fileInQuestionName, $referenceFileName) {
	$fileInQuestionContent = file_get_contents($fileInQuestionName);
	$referenceFileContent = file_get_contents($referenceFileName);

	return md5($fileInQuestionContent) !== md5($referenceFileContent);
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

	$query = 'SELECT firstName, lastName, emailAddress, phoneNumber, bestTimeToCall, Appointment.appointmentId,
				PossibleAnswer.text AS countryText
		FROM SelfServiceAppointmentRescheduleToken
			JOIN Appointment ON SelfServiceAppointmentRescheduleToken.appointmentId = Appointment.appointmentId
			JOIN Client ON Appointment.clientId = Client.clientId
			LEFT JOIN Answer ON Answer.appointmentId = Appointment.appointmentId
				AND Answer.questionId = (SELECT questionId FROM Question WHERE lookupName = "treaty_type")
			LEFT JOIN PossibleAnswer ON PossibleAnswer.possibleAnswerId = Answer.possibleAnswerId
		WHERE token = ?';

	$stmt = $DB_CONN->prepare($query);
	if (!$stmt->execute(array($token))) {
		throw new Exception('There was an error on the server fetching information. Please refresh the page and try again.', MY_EXCEPTION);
	}

	$clientInformation = $stmt->fetch();
	if (!$clientInformation) {
		throw new Exception('There was an error on the server fetching information. Please refresh the page and try again.', MY_EXCEPTION);
	}

	$clientInformation['appointmentType'] = isset($clientInformation['countryText']) ? 'non-residential' : 'residential';

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

function endsWith($haystack, $needle, $caseInsensitive = true) {
    return substr_compare($haystack, $needle, -strlen($needle), strlen($needle), $caseInsensitive) === 0;
}