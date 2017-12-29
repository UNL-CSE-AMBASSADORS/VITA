<?php

$root = realpath($_SERVER['DOCUMENT_ROOT']);
require_once "$root/server/user.class.php";
$USER = new User();
if (!$USER->isLoggedIn()) {
	header("Location: /unauthorized");
	die();
}

require_once "$root/server/config.php";

if (isset($_REQUEST['action'])) {
	switch ($_REQUEST['action']) {
		case 'getAppointments': getAppointments($_GET['year']); break;
		case 'reschedule': rescheduleAppointment($_POST['id'], $_POST['scheduledTime'], $_POST['siteId']); break;
		default:
			die('Invalid action function. This instance has been reported.');
			break;
	}
}

function getAppointments($year) {
	GLOBAL $DB_CONN;

	$response = array();
	$response['success'] = true;

	try {
		$query = "SELECT appointmentId, language, scheduledTime, title, firstName, lastName, Client.phoneNumber, emailAddress
			FROM Appointment
				JOIN AppointmentTime ON Appointment.appointmentTimeId = AppointmentTime.appointmentTimeId
				JOIN Site ON AppointmentTime.siteId = Site.siteId
				JOIN Client ON Appointment.clientId = Client.clientId
			WHERE Appointment.archived = FALSE AND
				YEAR(scheduledTime) = ?";
	
		$stmt = $DB_CONN->prepare($query);
		$stmt->execute(array($year));
		$appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

		//TODO: NEED TO REMOVE LAST NAME AND STUFF HERE

		$response['appointments'] = $appointments;
	} catch(Exception $e) {
		$response['success'] = false;
		$response['error'] = 'There was an error loading the appointments on the server. Please refresh the page and try again.';
	}

	echo json_encode($response);
}

function rescheduleAppointment($appointmentId, $scheduledTime, $siteId) {
	GLOBAL $DB_CONN;
	
	$response = array();
	$response['success'] = true;

	try {
		$query = "UPDATE Appointment
			SET appointmentTimeId = (SELECT appointmentTimeId FROM AppointmentTime
					WHERE DATE(scheduledTime) = ? 
					AND TIME_FORMAT(TIME(scheduledTime), '%l:%i %p') = ?
					AND siteId = ?)
			WHERE appointmentId = ?";
		$stmt = $DB_CONN->prepare($query);

		$dateTime = new DateTime($scheduledTime);
		$success = $stmt->execute(array(
			$dateTime->format('Y-m-d'),
			$dateTime->format('g:i A'),
			$siteId,
			$appointmentId
		));
		if ($success == false) {
			throw new Exception();
		}
	} catch (Exception $e) {
		$response['success'] = false;
		$response['error'] = 'There was an error rescheduling the appointment on the server. Please refresh the page and try again.';
	}

	echo json_encode($response);
}