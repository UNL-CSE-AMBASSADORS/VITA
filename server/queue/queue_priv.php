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
		case 'appointmentComplete': appointmentComplete($_REQUEST['time'], $_REQUEST['id'], $_REQUEST['stationNumber'], $_REQUEST['filingStatusIds']); break;
		case 'appointmentIncomplete': appointmentIncomplete($_REQUEST['explanation'], $_REQUEST['id']); break;
		case 'cancelledAppointment': cancelledAppointment($_REQUEST['id']); break;
		default: break;
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

	function appointmentComplete($time, $id, $stationNumber, $filingStatusIds) {
		GLOBAL $DB_CONN;
		
		$DB_CONN->beginTransaction();

		try {
			$stmt = $DB_CONN->prepare("UPDATE ServicedAppointment
				SET timeAppointmentEnded = ?, completed = TRUE, servicedByStation = ?
				WHERE appointmentId = ?");
			$stmt->execute(array($time, $stationNumber, $id));

			$stmt = $DB_CONN->prepare("INSERT INTO AppointmentFilingStatus (servicedAppointmentId, filingStatusId)
				VALUES ((SELECT servicedAppointmentId FROM ServicedAppointment WHERE appointmentId = ?), ?)");
			foreach ($filingStatusIds as $filingStatusId) {
				$stmt->execute(array($id, $filingStatusId));
			}

			$DB_CONN->commit();
		} catch (Exception $e) {
			$DB_CONN->rollBack();
		}

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
