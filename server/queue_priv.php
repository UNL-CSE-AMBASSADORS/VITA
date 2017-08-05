<?php
	require 'config.php';
	$conn = $DB_CONN;

	$stmt = $conn->prepare("SELECT
		Appointment.scheduledTime, Client.firstName, Client.lastName, Client.emailAddress, Site.title
		FROM Appointment
		JOIN Client ON Appointment.clientId = Client.clientId
		JOIN Site ON Appointment.siteId = Site.siteId
		WHERE Appointment.AppointmentId = ?
	");

	$stmt->execute(array($_GET['id']));
	$appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
	echo json_encode($appointments);
	$stmt = null;
