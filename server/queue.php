<?php
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	require_once "$root/server/config.php";
	require_once "$root/server/user.class.php";
	$conn = $DB_CONN;
	$USER = new User();

	$canViewClientInformation = $USER->isLoggedIn() && $USER->hasPermission('view_client_information');

	$query = "SELECT Appointment.appointmentId, scheduledTime,
		firstName, lastName, timeIn, timeReturnedPapers,
		timeAppointmentStarted, timeAppointmentEnded, completed ";
	if ($canViewClientInformation) {
		$query .= ", phoneNumber, emailAddress ";
	}
	$query .= "FROM Appointment
		LEFT JOIN ServicedAppointment ON Appointment.appointmentId = ServicedAppointment.appointmentId
		JOIN Client ON Appointment.clientId = Client.clientId
		WHERE DATE(Appointment.scheduledTime) = ?
			AND Appointment.archived = FALSE
		ORDER BY Appointment.scheduledTime ASC";

	// TODO make this handle multiple locations, if necessary
	$stmt = $conn->prepare($query);

	$stmt->execute(array($_GET['displayDate']));
	$appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

	foreach ($appointments as &$appointment) {
		// Shorten last name to only the initial if user doesn't have permission to view entire last name
		if (!$canViewClientInformation) {
			$appointment['lastName'] = substr($appointment['lastName'], 0, 1).'.'; // concat period since this is a last initial
		}
	}

	echo json_encode($appointments);
	$stmt = null;
