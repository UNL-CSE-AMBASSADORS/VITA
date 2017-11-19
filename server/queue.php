<?php
	require_once 'config.php';
	$conn = $DB_CONN;

	// TODO make this handle multiple locations, if necessary
	$stmt = $conn->prepare("SELECT DISTINCT appointmentId, scheduledTime, firstName, lastName, timeIn, timeReturnedPapers, actualAppointmentTime, timeFinished, completed, notCompletedDescription
		FROM Appointment
		JOIN Client ON Appointment.clientId = Client.clientId
		WHERE DATE(Appointment.scheduledTime) = ?
			AND Appointment.archived = FALSE
		ORDER BY Appointment.scheduledTime ASC");

	$stmt->execute(array($_GET['displayDate']));
	$appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

	// We must only display the first letter of the last name
	// We do this server-side since we can't disclose the data client-side
	for ($i = 0; $i < count($appointments); $i++) {
		$appointments[$i]['lastName'] = substr($appointments[$i]['lastName'], 0, 1);
	}

	echo json_encode($appointments);
	$stmt = null;
