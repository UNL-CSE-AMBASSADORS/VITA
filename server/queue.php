<?php
	require 'config.php';
	$conn = $DB_CONN;

	// TODO make this handle multiple locations, if necessary
	$stmt = $conn->prepare('SELECT DISTINCT appointmentId, scheduledTime, firstName, lastName
		FROM Appointment
		JOIN Client ON Appointment.clientId = Client.clientId
		WHERE (Appointment.scheduledTime >= NOW() AND Appointment.scheduledTime < DATE_ADD(CURDATE(), INTERVAL 1 DAY))
			AND Appointment.archived = FALSE
		ORDER BY Appointment.scheduledTime ASC');

	$stmt->execute();
	$results = $stmt->fetchAll();

	// We must only display the first letter of the last name
	// We do this server-side since we can't disclose the data client-side
	$appointments = [];
	foreach ($results as $result) {
		$appointment = [];
		
		$appointment['appointmentId'] = $result['appointmentId'];
		$appointment['scheduledTime'] = $result['scheduledTime'];
		$appointment['firstName'] = $result['firstName'];
		$appointment['lastName'] = substr($result['lastName'], 0, 1);
		
		$appointments[] = $appointment;
	}

	echo json_encode($appointments);

	$stmt = null;
