<?php
	require_once 'config.php';
	$conn = $DB_CONN;

	switch($_REQUEST['action']) {
		case 'display': displayAppointment($_REQUEST['id']); break;
		case 'cancel': cancelAppointment($_REQUEST['id']); break;
		case 'checkIn': checkIn($_REQUEST['time'], $_REQUEST['id']); break;
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
