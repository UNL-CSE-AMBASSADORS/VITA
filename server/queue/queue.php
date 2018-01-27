<?php

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once "$root/server/config.php";
require_once "$root/server/user.class.php";

$USER = new User();
if ($USER->isLoggedIn()) { 
	loadPrivateQueue($_GET);
} else {
	loadPublicQueue($_GET);
}

function loadPublicQueue($data) {
	GLOBAL $DB_CONN;
	$query = "SELECT Appointment.appointmentId, TIME_FORMAT(scheduledTime, '%l:%i %p') AS scheduledTime, firstName, lastName, 
				timeIn, timeReturnedPapers, timeAppointmentStarted, timeAppointmentEnded, completed,
				(DATE_ADD(AppointmentTime.scheduledTime, INTERVAL 15 MINUTE) < NOW() AND timeIn IS NULL) AS noshow 
			FROM Appointment
			LEFT JOIN ServicedAppointment ON Appointment.appointmentId = ServicedAppointment.appointmentId
			JOIN Client ON Appointment.clientId = Client.clientId
			JOIN AppointmentTime ON Appointment.appointmentTimeId = AppointmentTime.appointmentTimeId
			WHERE DATE(AppointmentTime.scheduledTime) = ?
				AND AppointmentTime.siteId = ?
				AND Appointment.archived = FALSE
			ORDER BY AppointmentTime.scheduledTime ASC";
	$stmt = $DB_CONN->prepare($query);

	$stmt->execute(array($data['displayDate'], $data['siteId']));
	$appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

	foreach ($appointments as &$appointment) {
		$appointment['lastName'] = substr($appointment['lastName'], 0, 1).'.';
	}

	echo json_encode($appointments);
	$stmt = null;
}

function loadPrivateQueue($data) {
	GLOBAL $DB_CONN, $USER;
	$canViewClientInformation = $USER->hasPermission('view_client_information');

	$query = "SELECT Appointment.appointmentId, TIME_FORMAT(AppointmentTime.scheduledTime, '%l:%i %p') AS scheduledTime, 
				firstName, lastName, timeIn, timeReturnedPapers, timeAppointmentStarted, timeAppointmentEnded, 
				completed, language, Client.clientId, 
				(DATE_ADD(AppointmentTime.scheduledTime, INTERVAL 15 MINUTE) < NOW() AND timeIn IS NULL) AS noshow ";
	if ($canViewClientInformation) {
		$query .= ", phoneNumber, emailAddress ";
	}
	$query .= "FROM Appointment
			LEFT JOIN ServicedAppointment ON Appointment.appointmentId = ServicedAppointment.appointmentId
			JOIN Client ON Appointment.clientId = Client.clientId
			JOIN AppointmentTime ON Appointment.appointmentTimeId = AppointmentTime.appointmentTimeId
			WHERE DATE(AppointmentTime.scheduledTime) = ?
				AND AppointmentTime.siteId = ?
				AND Appointment.archived = FALSE
			ORDER BY AppointmentTime.scheduledTime ASC";
	$stmt = $DB_CONN->prepare($query);
	$stmt->execute(array($data['displayDate'], $data['siteId']));
	$appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

	foreach ($appointments as &$appointment) {
		$appointment['language'] = expandLanguageCode($appointment['language']);

		// Shorten last name to only the initial if user doesn't have permission to view entire last name
		if (!$canViewClientInformation) {
			$appointment['lastName'] = substr($appointment['lastName'], 0, 1).'.';
		}
	}

	echo json_encode($appointments);
	$stmt = null;
}

function expandLanguageCode($languageCode) {
	if ($languageCode === 'eng') return 'English';
	if ($languageCode === 'vie') return 'Vietnamese';
	if ($languageCode === 'spa') return 'Spanish';
	if ($languageCode === 'ara') return 'Arabic';
	return 'Unknown';
}