<?php
	require 'config.php';
	$conn = $DB_CONN;

	// TODO make this handle multiple locations, if necessary
	$stmt = $conn->prepare('SELECT id, time, firstName, lastName FROM Appointment
		WHERE (date = getdate() && archived = 0)');
	$stmt->execute();
	$results = $stmt->fetchAll();

	// We must only display the first letter of the last name
	// We do this server-side since we can't disclose the data client-side
	$appointments = [];
	foreach ($results as $result) {
		$result['lastName'] = substr($result['lastName'], 0, 1);
		$appointments[] = $result;
	}

	echo json_encode($appointments);

	$stmt->close();
	$conn->close();
