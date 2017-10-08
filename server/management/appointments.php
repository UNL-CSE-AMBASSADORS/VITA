<?php

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once "$root/server/config.php";

getCsvFormattedAppointmentsFile($_POST);

function getCsvFormattedAppointmentsFile($data) {
	GLOBAL $DB_CONN;
	
	$stmt = $DB_CONN->prepare("SELECT scheduledTime, firstName, lastName, phoneNumber, emailAddress, appointmentId
		FROM Appointment
		JOIN Client ON Appointment.clientId = Client.clientId
		WHERE DATE(Appointment.scheduledTime) = ?
			AND Appointment.archived = FALSE
			AND Appointment.siteId = ?
		ORDER BY Appointment.scheduledTime ASC");

	$filterParams = array($data['date'], $data['siteId']);
	$stmt->execute($filterParams);
	$appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

	echo json_encode($appointments);
}