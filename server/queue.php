<?php
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	require_once "$root/server/config.php";
	require_once "$root/server/user.class.php";
	require_once "$root/server/utilities/dateTimezoneUtilities.php";
	$conn = $DB_CONN;

	// TODO make this handle multiple locations, if necessary
	$stmt = $conn->prepare("SELECT Appointment.appointmentId, scheduledTime,
		firstName, lastName, timeIn, timeReturnedPapers,
		timeAppointmentStarted, timeAppointmentEnded, completed, notCompletedDescription
		FROM Appointment
		LEFT JOIN ServicedAppointment ON Appointment.appointmentId = ServicedAppointment.appointmentId
		JOIN Client ON Appointment.clientId = Client.clientId
		WHERE Appointment.scheduledTime >= ? AND Appointment.scheduledTime < ?
			AND Appointment.archived = FALSE
		ORDER BY Appointment.scheduledTime ASC");

	$timezoneOffset = $_GET['timezoneOffset'];
	$dates = getUtcDateAdjustedForTimezoneOffset($_GET['displayDate'], $timezoneOffset);
	$stmt->execute(array($dates['date']->format('Y-m-d H:i:s'), $dates['datePlusOneDay']->format('Y-m-d H:i:s')));
	$appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

	// We must only display the first letter of the last name
	// We do this server-side since we can't disclose the data client-side
	foreach ($appointments as &$appointment) {
		$appointment['lastName'] = substr($appointment['lastName'], 0, 1);
		$appointment['scheduledTime'] = getTimezoneDateFromUtc($appointment['scheduledTime'], $timezoneOffset)->format('Y-m-d H:i:s');
		// TODO FINISH THE OTHERS

	}

	echo json_encode($appointments);
	$stmt = null;
