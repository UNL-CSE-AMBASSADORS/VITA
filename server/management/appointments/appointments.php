<?php

$root = realpath($_SERVER['DOCUMENT_ROOT']);
require_once "$root/server/user.class.php";
$USER = new User();
if (!$USER->isLoggedIn()) {
	header("Location: /unauthorized");
	die();
}

require_once "$root/server/config.php";
require_once "$root/server/accessors/appointmentAccessor.class.php";
require_once "$root/server/utilities/emailUtilities.class.php";
require_once "$root/server/utilities/appointmentConfirmationUtilities.class.php";
require_once "$root/server/accessors/noteAccessor.class.php";

if (isset($_REQUEST['action'])) {
	switch ($_REQUEST['action']) {
		case 'getAppointments': getAppointments($_GET['year']); break;
		case 'reschedule': rescheduleAppointment($_POST['id'], $_POST['appointmentTimeId']); break;
		case 'cancel': cancelAppointment($_POST['id']); break;
		default:
			die('Invalid action function. This instance has been reported.');
			break;
	}
}

function getAppointments($year) {
	GLOBAL $DB_CONN, $USER;

	$response = array();
	$response['success'] = true;

	$canViewClientInformation = $USER->isLoggedIn() && $USER->hasPermission('view_client_information');

	try {
		$query = 'SELECT Appointment.appointmentId, language, DATE_FORMAT(scheduledTime, "%b %D %l:%i %p") AS scheduledTime, 
			Site.title, firstName, lastName,
			TIME_FORMAT(timeIn, "%l:%i %p") AS timeIn, 
			TIME_FORMAT(timeReturnedPapers, "%l:%i %p") AS timeReturnedPapers, 
			TIME_FORMAT(timeAppointmentStarted, "%l:%i %p") AS timeAppointmentStarted, 
			TIME_FORMAT(timeAppointmentEnded, "%l:%i %p") AS timeAppointmentEnded,
			AppointmentType.lookupName AS appointmentType,
			completed, cancelled, servicedAppointmentId, token ';
		if ($canViewClientInformation) {
			$query .= ', Client.phoneNumber, emailAddress, bestTimeToCall ';
		}
		$query .= 'FROM Appointment
				LEFT JOIN ServicedAppointment ON Appointment.appointmentId = ServicedAppointment.appointmentId
				JOIN AppointmentTime ON Appointment.appointmentTimeId = AppointmentTime.appointmentTimeId
				JOIN AppointmentType ON AppointmentTime.appointmentTypeId = AppointmentType.appointmentTypeId
				JOIN Site ON AppointmentTime.siteId = Site.siteId
				JOIN Client ON Appointment.clientId = Client.clientId
				LEFT JOIN SelfServiceAppointmentRescheduleToken ON Appointment.appointmentId = SelfServiceAppointmentRescheduleToken.appointmentId
			WHERE Appointment.archived = FALSE AND
				YEAR(AppointmentTime.scheduledTime) = ?
			ORDER BY AppointmentTime.scheduledTime';
	
		$stmt = $DB_CONN->prepare($query);
		$stmt->execute(array($year));
		$appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);


		foreach ($appointments as &$appointment) {
			// Shorten last name to only the initial if user doesn't have permission to view entire last name
			if (!$canViewClientInformation) {
				$appointment['lastName'] = getInitial($appointment['lastName']);
			}

			if (isset($appointment['token'])) {
				$token = $appointment['token'];
				$serverName = $_SERVER['SERVER_NAME'];
				$appointment['uniqueAppointmentRescheduleUrl'] = "https://$serverName/appointment/reschedule/?token=$token";
				$appointment['uniqueUploadDocumentsUrl'] = "https://$serverName/appointment/upload-documents/?token=$token";
			}

			$appointment['language'] = expandLanguageCode($appointment['language']);
			$appointment['notes'] = getNotesForAppointment($appointment['appointmentId']);
		}

		$response['appointments'] = $appointments;
	} catch(Exception $e) {
		$response['success'] = false;
		$response['error'] = 'There was an error loading the appointments on the server. Please refresh the page and try again.';
	}

	echo json_encode($response);
}

// NOTE: Upon a successful reschedule, an appointment rescheduled confirmation email is automatically sent to the client
// whose appointment was rescheduled
function rescheduleAppointment($appointmentId, $appointmentTimeId) {
	GLOBAL $DB_CONN;
	
	$response = array();
	$response['success'] = true;

	try {
		$appointmentAccessor = new AppointmentAccessor();
		$appointmentAccessor->rescheduleAppointment($appointmentId, $appointmentTimeId);

		if (PROD) {
			sendEmailConfirmation($appointmentId);
		}

		addNote($appointmentId, 'Rescheduled [Automatic Note]');
	} catch (Exception $e) {
		$response['success'] = false;
		$response['error'] = 'There was an error rescheduling the appointment on the server. Please refresh the page and try again.';
	}

	echo json_encode($response);
}

function cancelAppointment($appointmentId) {
	$response = array();
	$response['success'] = true;

	try {
		$appointmentAccessor = new AppointmentAccessor();
		$appointmentAccessor->cancelAppointment($appointmentId);

		addNote($appointmentId, 'Cancelled [Automatic Note]');
	} catch (Exception $e) {
		$response['success'] = false;
		$response['error'] = 'There was an error cancelling the appointment on the server. Please refresh the page and try again.';
	}

	echo json_encode($response);
}

/*
 * Private Methods
 */
function getInitial($name) {
	return substr($name, 0, 1) . '.'; // concat period since this is an initial
}

function getNotesForAppointment($appointmentId) {
	$noteAccessor = new NoteAccessor();
	return $noteAccessor->getNotesForAppointment($appointmentId);
}

function sendEmailConfirmation($appointmentId) {
	GLOBAL $DB_CONN;
	
	try {
		$query = "SELECT emailAddress FROM Appointment
			JOIN Client ON Appointment.clientId = Client.clientId
			WHERE appointmentId = ?";
		$stmt = $DB_CONN->prepare($query);
		$success = $stmt->execute(array($appointmentId));

		if ($success == false) return;

		$data = $stmt->fetch();
		if (!isset($data) || !isset($data['emailAddress'])) return;

		$confirmationMessage = AppointmentConfirmationUtilities::generateAppointmentConfirmation($appointmentId);
		EmailUtilities::sendHtmlFormattedEmail($data['emailAddress'], 'VITA Appointment Rescheduled', $confirmationMessage);
	} catch (Exception $e) {
		// do nothing, we just won't send an email
	}
}

function expandLanguageCode($languageCode) {
	if ($languageCode === 'eng') return 'English';
	if ($languageCode === 'spa') return 'Spanish';
	if ($languageCode === 'vie') return 'Vietnamese';
	if ($languageCode === 'ara') return 'Arabic';
	return 'Unknown';
}

function addNote($appointmentId, $noteText) {
	GLOBAL $USER;

	$noteAccessor = new NoteAccessor();
	$userId = $USER->getUserId();
	$notes = $noteAccessor->addNote($appointmentId, $noteText, $userId);
}
