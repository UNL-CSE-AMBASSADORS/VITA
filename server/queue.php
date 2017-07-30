<?php
	require 'config.php';
	$conn = $DB_CONN;
	$displayDate = $_GET['displayDate'];

	// TODO make this handle multiple locations, if necessary
	$stmt = $conn->prepare("SELECT DISTINCT appointmentId, scheduledTime, firstName, lastName
		FROM Appointment
		JOIN Client ON Appointment.clientId = Client.clientId
		WHERE DATE(Appointment.scheduledTime) = '$displayDate'
			AND Appointment.archived = FALSE
		ORDER BY Appointment.scheduledTime ASC");

	$stmt->execute();
	$appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

	// We must only display the first letter of the last name
	// We do this server-side since we can't disclose the data client-side
	for ($i = 0; $i < count($appointments); $i++) {
		$appointments[$i]['lastName'] = substr($appointments[$i]['lastName'], 0, 1);
	}

	echo json_encode($appointments);
	$stmt = null;
