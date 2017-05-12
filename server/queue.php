<?php
	require 'config.php';
	$conn = $DB_CONN;

	// TODO make this handle multiple locations, if necessary
	$stmt = $conn->prepare('SELECT DISTINCT appointmentId, scheduledTime
		FROM Appointment
		WHERE (Appointment.scheduledTime >= NOW() AND Appointment.scheduledTime < DATE_ADD(CURDATE(), INTERVAL 1 DAY))
			AND Appointment.archived = FALSE
		ORDER BY Appointment.scheduledTime ASC');

	$stmt->execute();
	$results = $stmt->fetchAll();

	$firstNameStmt = $conn->prepare('SELECT Answer.string AS firstName
		FROM Answer
		JOIN Question ON Answer.questionId = Question.questionId
		WHERE Question.tag = "first_name" AND Answer.appointmentId = ?');

	$lastNameStmt = $conn->prepare('SELECT Answer.string AS lastName
		FROM Answer
		JOIN Question ON Answer.questionId = Question.questionId
		WHERE Question.tag = "last_name" AND Answer.appointmentId = ?');

	// We must only display the first letter of the last name
	// We do this server-side since we can't disclose the data client-side
	$appointments = [];
	foreach ($results as $result) {
		$firstNameStmt->execute(array($result['appointmentId']));
		$lastNameStmt->execute(array($result['appointmentId']));

		$firstNameResult = $firstNameStmt->fetch();
		$lastNameResult = $lastNameStmt->fetch();

		$result['firstName'] = $firstNameResult['firstName'];
		$result['lastName'] = substr($lastNameResult['lastName'], 0, 1);

		$appointments[] = $result;
	}

	echo json_encode($appointments);

	$firstNameStmt = null;
	$lastNameStmt = null;
	$stmt = null;
