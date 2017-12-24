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
		case 'appointmentComplete': appointmentComplete($_REQUEST['time'], $_REQUEST['id'], $_REQUEST['stationNumber']); break;
		case 'appointmentIncomplete': appointmentIncomplete($_REQUEST['explanation'], $_REQUEST['id']); break;
		case 'cancelledAppointment': cancelledAppointment($_REQUEST['id']); break;
		default: break;
	}

	function checkIn($time, $appointmentId) {
		$response = array();
		$response['success'] = true;

		try {
			$stmt = $GLOBALS['conn']->prepare(
				"INSERT INTO ServicedAppointment (timeIn, appointmentId)
						VALUES (?, ?)"
			);

			if ($stmt->execute(array($time, $appointmentId)) == false) {
				throw new Exception();
			}
		} catch (Exception $e) {
			$response['success'] = false;
			$response['error'] = 'Unable to check in the client. Please refresh the page and try again.';
		}

		echo json_encode($response);
		$stmt = null;
	}

	function completePaperwork($time, $appointmentId) {
		$response = array();
		$response['success'] = true;

		try {
			$stmt = $GLOBALS['conn']->prepare(
				"UPDATE ServicedAppointment
				SET timeReturnedPapers = ?
				WHERE appointmentId = ?"
			);

			if ($stmt->execute(array($time, $appointmentId)) == false) {
				throw new Exception();
			}
		} catch (Exception $e) {
			$response['success'] = false;
			$response['error'] = 'Unable to update the appointment. Please refresh the page and try again.';
		}

		echo json_encode($response);
		$stmt = null;
	}

	function appointmentStart($time, $appointmentId) {
		$response = array();
		$response['success'] = true;

		try {
			$stmt = $GLOBALS['conn']->prepare(
				"UPDATE ServicedAppointment
				SET timeAppointmentStarted = ?
				WHERE appointmentId = ?"
			);

			if ($stmt->execute(array($time, $appointmentId)) == false) {
				throw new Exception();
			}
		} catch (Exception $e) {
			$response['success'] = false;
			$response['error'] = 'Unable to update the appointment. Please refresh the page and try again.';
		}

		echo json_encode($response);
		$stmt = null;
	}

	function appointmentComplete($time, $appointmentId, $stationNumber) {
		$response = array();
		$response['success'] = true;

		try {
			$stmt = $GLOBALS['conn']->prepare(
				"UPDATE ServicedAppointment
				SET timeAppointmentEnded = ?, servicedByStation = ?, completed = TRUE
				WHERE appointmentId = ?"
			);

			if ($stmt->execute(array($time, $stationNumber, $appointmentId)) == false) {
				throw new Exception();
			}
		} catch (Exception $e) {
			$response['success'] = false;
			$response['error'] = 'Unable to update the appointment. Please refresh the page and try again.';
		}

		echo json_encode($response);
		$stmt = null;
	}

	function appointmentIncomplete($explanation, $appointmentId) {
		$response = array();
		$response['success'] = true;

		try {
			$stmt = $GLOBALS['conn']->prepare(
				"UPDATE ServicedAppointment
				SET notCompletedDescription = ?, completed = FALSE
				WHERE appointmentId = ?"
			);

			if ($stmt->execute(array($explanation, $appointmentId)) == false) {
				throw new Exception();
			}
		} catch (Exception $e) {
			$response['success'] = false;
			$response['error'] = 'Unable to update the appointment. Please refresh the page and try again.';
		}

		echo json_encode($response);
		$stmt = null;
	}

	function cancelledAppointment($appointmentId) {
		$response = array();
		$response['success'] = true;

		try {
			$stmt = $GLOBALS['conn']->prepare(
				"INSERT INTO ServicedAppointment (appointmentId, notCompletedDescription, completed)
						VALUES (?, 'Cancelled Appointment', FALSE)"
			);

			if ($stmt->execute(array($appointmentId)) == false) {
				throw new Exception();
			}
		} catch (Exception $e) {
			$response['success'] = false;
			$response['error'] = 'Unable to update the appointment. Please refresh the page and try again.';
		}

		echo json_encode($response);
		$stmt = null;
	}
