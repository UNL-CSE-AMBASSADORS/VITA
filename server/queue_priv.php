<?php
	require 'config.php';
	$conn = $DB_CONN;

	switch($_GET['action']) {
		case 'display': displayAppointment($_GET['id']); break;
		default: break;
	}





	function displayAppointment($id) {
		$stmt = $GLOBALS['DB_CONN']->prepare(
			"SELECT Appointment.scheduledTime, Client.firstName, Client.lastName, Client.emailAddress, Site.title
			FROM Appointment
			JOIN Client ON Appointment.clientId = Client.clientId
			JOIN Site ON Appointment.siteId = Site.siteId
			WHERE Appointment.AppointmentId = ?"
		);
	
		$stmt->execute(array($id));
		$appointment = $stmt->fetchAll(PDO::FETCH_ASSOC);
		echo json_encode($appointment);
		$stmt = null;
	}
