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

	// We must only display the first letter of the last name
	// We do this server-side since we can't disclose the data client-side
	$appointments = [];
	foreach ($results as $result) {
		$firstNameStmt = $conn->prepare('SELECT UserAnswer.string AS firstName
			FROM AppointmentQuestionAnswer AS AQA
			JOIN UserAnswer ON AQA.userAnswerId = UserAnswer.userAnswerId
			JOIN Question ON UserAnswer.questionId = Question.questionId
			WHERE Question.tag = "first_name" AND AQA.appointmentId = ?');

		$lastNameStmt = $conn->prepare('SELECT UserAnswer.string AS lastName
			FROM AppointmentQuestionAnswer AS AQA
			JOIN UserAnswer on AQA.userAnswerId = UserAnswer.userAnswerId
			JOIN Question ON UserAnswer.questionId = Question.questionId
			WHERE Question.tag = "last_name" AND AQA.appointmentId = ?');

		$firstNameStmt->execute(array($result['appointmentId']));
		$lastNameStmt->execute(array($result['appointmentId']));

		$firstNameResult = $firstNameStmt->fetch();
		$lastNameResult = $lastNameStmt->fetch();

		$result['firstName'] = $firstNameResult['firstName'];
		$result['lastName'] = substr($lastNameResult['lastName'], 0, 1);

		$appointments[] = $result;

		$firstNameStmt = null;
		$lastNameStmt = null;
	}

	echo json_encode($appointments);

	$stmt = null;
