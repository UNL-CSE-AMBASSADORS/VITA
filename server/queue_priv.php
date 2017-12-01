<?php
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	require_once "$root/server/config.php";
	$conn = $DB_CONN;

	// TODO: Make it so this is testable
	require_once "$root/server/user.class.php";
	$USER = new User();
	if (!$USER->isLoggedIn()) {
		header("Location: /unauthorized");
		die();
	}

	switch($_REQUEST['action']) {
		case 'display': displayAppointment($_REQUEST['id']); break;
		case 'cancel': cancelAppointment($_REQUEST['id']); break;
		case 'checkIn': checkIn($_REQUEST['time'], $_REQUEST['id']); break;
		case 'completePaperwork': completePaperwork($_REQUEST['time'], $_REQUEST['id']); break;
		case 'appointmentStart': appointmentStart($_REQUEST['time'], $_REQUEST['id']); break;
		case 'appointmentComplete': appointmentComplete($_REQUEST['time'], $_REQUEST['id']); break;
		case 'appointmentIncomplete': appointmentIncomplete($_REQUEST['explanation'], $_REQUEST['id']); break;
		default: break;
	}

	function checkIn($time, $id) {
		$stmt = $GLOBALS['conn']->prepare(
			"UPDATE Appointment
			SET Appointment.timeIn = ?
			WHERE Appointment.appointmentId = ?"
		);

		$stmt->execute(array($time, $id));
		$appointment = $stmt->fetchAll(PDO::FETCH_ASSOC);
		echo json_encode($appointment);
		$stmt = null;
	}

	function completePaperwork($time, $id) {
		$stmt = $GLOBALS['conn']->prepare(
			"UPDATE Appointment
			SET Appointment.timeReturnedPapers = ?
			WHERE Appointment.appointmentId = ?"
		);

		$stmt->execute(array($time, $id));
		$appointment = $stmt->fetchAll(PDO::FETCH_ASSOC);
		echo json_encode($appointment);
		$stmt = null;
	}

	function appointmentStart($time, $id) {
		$stmt = $GLOBALS['conn']->prepare(
			"UPDATE Appointment LEFT JOIN ServicedAppointment
			ON Appointment.appointmentId = ServicedAppointment.appointmentId
			SET Appointment.timeAppointmentStarted = ?, ServicedAppointment.startTime = Appointment.timeAppointmentStarted
			WHERE Appointment.appointmentId = ?"
		);

		$stmt->execute(array($time, $id));
		$appointment = $stmt->fetchAll(PDO::FETCH_ASSOC);
		echo json_encode($appointment);
		$stmt = null;
	}

	function appointmentComplete($time, $id) {
		$stmt = $GLOBALS['conn']->prepare(
			"UPDATE Appointment
			SET Appointment.timeAppointmentEnded = ?, Appointment.completed = TRUE
			WHERE Appointment.appointmentId = ?"
		);

		$stmt->execute(array($time, $id));
		$appointment = $stmt->fetchAll(PDO::FETCH_ASSOC);
		echo json_encode($appointment);
		$stmt = null;
	}

	function appointmentIncomplete($explanation, $id) {
		$stmt = $GLOBALS['conn']->prepare(
			"UPDATE Appointment
			SET Appointment.notCompletedDescription = ?, Appointment.completed = FALSE
			WHERE Appointment.appointmentId = ?"
		);

		$stmt->execute(array($explanation, $id));
		$appointment = $stmt->fetchAll(PDO::FETCH_ASSOC);
		echo json_encode($appointment);
		$stmt = null;
	}

	function displayAppointment($id) {
		$stmt = $GLOBALS['conn']->prepare(
			"SELECT Appointment.scheduledTime, Client.firstName, Client.lastName, Client.emailAddress, Site.title
			FROM Appointment
			JOIN Client ON Appointment.clientId = Client.clientId
			JOIN Site ON Appointment.siteId = Site.siteId
			WHERE Appointment.appointmentId = ?"
		);

		$stmt->execute(array($id));
		$appointment = $stmt->fetchAll(PDO::FETCH_ASSOC);
		echo json_encode($appointment);
		$stmt = null;
	}

	function cancelAppointment($id) {
		$stmt = $GLOBALS['conn']->prepare(
			"UPDATE Appointment
			SET Appointment.archived = TRUE
			WHERE Appointment.appointmentId = ?"
		);

		$stmt->execute(array($id));
		$stmt = null;
	}
