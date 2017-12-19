<?php
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	require_once "$root/server/config.php";
	$conn = $DB_CONN;

	require_once "$root/server/user.class.php";
	$USER = new User();
	if (!$USER->isLoggedIn()) {
		header("Location: /unauthorized");
		die();
	}

	switch($_REQUEST['action']) {
		case 'checkIn': checkIn($_REQUEST['time'], $_REQUEST['id']); break;
		case 'completePaperwork': completePaperwork($_REQUEST['time'], $_REQUEST['id']); break;
		case 'appointmentStart': appointmentStart($_REQUEST['time'], $_REQUEST['id']); break;
		case 'appointmentComplete': appointmentComplete($_REQUEST['time'], $_REQUEST['id'], $_REQUEST['servicedById']); break;
		case 'appointmentIncomplete': appointmentIncomplete($_REQUEST['explanation'], $_REQUEST['id']); break;
		case 'cancelledAppointment': cancelledAppointment($_REQUEST['id']); break;
		case 'getVolunteers': getVolunteers($_REQUEST['date'], $_REQUEST['siteId']); break;
		default: break;
	}

	function getVolunteers($date, $siteId) {
		GLOBAL $DB_CONN;
		$stmt = $DB_CONN->prepare("SELECT User.firstName, User.lastName, User.userId FROM User
			JOIN UserShift ON User.userId = UserShift.userId
			JOIN Shift ON UserShift.shiftId = Shift.shiftId
			WHERE DATE(Shift.startTime) = ?
				AND Shift.siteId = ?
				AND Shift.archived = FALSE 
				AND User.archived = FALSE
			ORDER BY preparesTaxes DESC"
		);

		$stmt->execute(array($date, $siteId));
		$volunteers = $stmt->fetchAll(PDO::FETCH_ASSOC);
		echo json_encode($volunteers);
		$stmt = null;
	}

	function checkIn($time, $id) {
		$stmt = $GLOBALS['conn']->prepare(
			"INSERT INTO ServicedAppointment (timeIn, appointmentId)
					VALUES (?, ?)"
		);

		$stmt->execute(array($time, $id));
		$appointment = $stmt->fetchAll(PDO::FETCH_ASSOC);
		echo json_encode($appointment);
		$stmt = null;
	}

	function completePaperwork($time, $id) {
		$stmt = $GLOBALS['conn']->prepare(
			"UPDATE ServicedAppointment
			SET timeReturnedPapers = ?
			WHERE appointmentId = ?"
		);

		$stmt->execute(array($time, $id));
		$appointment = $stmt->fetchAll(PDO::FETCH_ASSOC);
		echo json_encode($appointment);
		$stmt = null;
	}

	function appointmentStart($time, $id) {
		$stmt = $GLOBALS['conn']->prepare(
			"UPDATE ServicedAppointment
			SET timeAppointmentStarted = ?
			WHERE appointmentId = ?"
		);

		$stmt->execute(array($time, $id));
		$appointment = $stmt->fetchAll(PDO::FETCH_ASSOC);
		echo json_encode($appointment);
		$stmt = null;
	}

	function appointmentComplete($time, $id, $servicedById) {
		$stmt = $GLOBALS['conn']->prepare(
			"UPDATE ServicedAppointment
			SET timeAppointmentEnded = ?, completed = TRUE, servicedBy = ?
			WHERE appointmentId = ?"
		);

		$stmt->execute(array($time, $servicedById, $id));
		$appointment = $stmt->fetchAll(PDO::FETCH_ASSOC);
		echo json_encode($appointment);
		$stmt = null;
	}

	function appointmentIncomplete($explanation, $id) {
		$stmt = $GLOBALS['conn']->prepare(
			"UPDATE ServicedAppointment
			SET notCompletedDescription = ?, completed = FALSE
			WHERE appointmentId = ?"
		);

		$stmt->execute(array($explanation, $id));
		$appointment = $stmt->fetchAll(PDO::FETCH_ASSOC);
		echo json_encode($appointment);
		$stmt = null;
	}

	function cancelledAppointment($id) {
		$stmt = $GLOBALS['conn']->prepare(
			"INSERT INTO ServicedAppointment (appointmentId, notCompletedDescription, completed)
					VALUES (?, 'Cancelled Appointment', FALSE)"
		);

		$stmt->execute(array($id));
		$appointment = $stmt->fetchAll(PDO::FETCH_ASSOC);
		echo json_encode($appointment);
		$stmt = null;
	}
