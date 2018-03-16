<?php

$root = realpath($_SERVER['DOCUMENT_ROOT']);
require_once "$root/server/user.class.php";
$USER = new User();
if (!$USER->isLoggedIn()) {
	header("Location: /unauthorized");
	die();
}

require_once "$root/server/config.php";
require_once "$root/server/utilities/emailUtilities.php";
require_once "$root/server/utilities/appointmentConfirmationUtilities.php";

if (isset($_REQUEST['action'])) {
	switch ($_REQUEST['action']) {
		case 'getAppointments': getAppointments($_GET['year']); break;
		case 'reschedule': rescheduleAppointment($_POST['id'], $_POST['appointmentTimeId']); break;
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
		$query = 'SELECT appointmentId, language, DATE_FORMAT(scheduledTime, "%b %D %l:%i %p") AS scheduledTime, title, 
			firstName, lastName ';
		if ($canViewClientInformation) {
			$query .= ', Client.phoneNumber, emailAddress ';
		}
		$query .= 'FROM Appointment
				JOIN AppointmentTime ON Appointment.appointmentTimeId = AppointmentTime.appointmentTimeId
				JOIN Site ON AppointmentTime.siteId = Site.siteId
				JOIN Client ON Appointment.clientId = Client.clientId
			WHERE Appointment.archived = FALSE AND
				YEAR(AppointmentTime.scheduledTime) = ?
			ORDER BY AppointmentTime.scheduledTime';
	
		$stmt = $DB_CONN->prepare($query);
		$stmt->execute(array($year));
		$appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

		foreach ($appointments as &$appointment) {
			// Shorten last name to only the initial if user doesn't have permission to view entire last name
			if (!$canViewClientInformation) {
				$appointment['lastName'] = substr($appointment['lastName'], 0, 1).'.'; // concat period since this is a last initial
			}
			$appointment['language'] = expandLanguageCode($appointment['language']);
		}

		$response['appointments'] = $appointments;
	} catch(Exception $e) {
		$response['success'] = false;
		$response['error'] = 'There was an error loading the appointments on the server. Please refresh the page and try again.';
	}

	echo json_encode($response);
}

function rescheduleAppointment($appointmentId, $appointmentTimeId) {
	GLOBAL $DB_CONN;
	
	$response = array();
	$response['success'] = true;

	try {
		$query = "UPDATE Appointment
			SET appointmentTimeId = ?
			WHERE appointmentId = ?";
		$stmt = $DB_CONN->prepare($query);

		$success = $stmt->execute(array(
			$appointmentTimeId,
			$appointmentId
		));

		if ($success == false) {
			throw new Exception();
		} else {
			if (PROD) {
				sendEmailConfirmation($appointmentId);
			}
		}
	} catch (Exception $e) {
		$response['success'] = false;
		$response['error'] = 'There was an error rescheduling the appointment on the server. Please refresh the page and try again.';
	}

	echo json_encode($response);
}

function sendEmailConfirmation($appointmentId) {
	GLOBAL $DB_CONN;
	
	try {
		$query = "SELECT email FROM Appointment
			JOIN Client ON Appointment.clientId = Client.clientId
			WHERE appointmentId = ?";
		$stmt = $DB_CONN->prepare($query);
		$success = $stmt->execute(array($appointmentId));

		if ($success == false) return;

		$data = $stmt->fetch();
		if (!isset($data) || !isset($data['email'])) return;

		$confirmationMessage = generateAppointmentConfirmation($appointmentId);
		sendHtmlFormattedEmail($data['email'], 'VITA Appointment Rescheduled', $confirmationMessage, 'noreply@vita.unl.edu');
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