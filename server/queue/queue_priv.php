<?php
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	require_once "$root/server/config.php";

	require_once "$root/server/user.class.php";
	$USER = new User();
	if (!$USER->isLoggedIn()) {
		header("Location: /unauthorized");
		die();
	}

	require_once "$root/server/accessors/appointmentAccessor.class.php";
	require_once "$root/server/accessors/noteAccessor.class.php";

	switch($_REQUEST['action']) {
		case 'checkIn': checkIn($_REQUEST['time'], $_REQUEST['id']); break;
		case 'completePaperwork': completePaperwork($_REQUEST['time'], $_REQUEST['id']); break;
		case 'appointmentStart': appointmentStart($_REQUEST['time'], $_REQUEST['id']); break;
		case 'appointmentComplete': appointmentComplete($_REQUEST['time'], $_REQUEST['id'], $_REQUEST['stationNumber'], array_key_exists('filingStatusIds', $_REQUEST) ? $_REQUEST['filingStatusIds'] : []); break;
		case 'appointmentIncomplete': appointmentIncomplete($_REQUEST['id']); break;
		case 'cancelledAppointment': cancelledAppointment($_REQUEST['id']); break;
		default: break;
	}

	function checkIn($time, $appointmentId) {
		GLOBAL $DB_CONN;

		$response = array();
		$response['success'] = true;

		try {
			$stmt = $DB_CONN->prepare(
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
		GLOBAL $DB_CONN;

		$response = array();
		$response['success'] = true;

		try {
			$stmt = $DB_CONN->prepare(
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
		GLOBAL $DB_CONN;

		$response = array();
		$response['success'] = true;

		try {
			$stmt = $DB_CONN->prepare(
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

	function appointmentComplete($time, $appointmentId, $stationNumber, $filingStatusIds) {
		GLOBAL $DB_CONN;
		
		$response = array();
		$response['success'] = true;

		try {
			$DB_CONN->beginTransaction();

			$stmt = $DB_CONN->prepare("UPDATE ServicedAppointment
				SET timeAppointmentEnded = ?, servicedByStation = ?, completed = TRUE
				WHERE appointmentId = ?");

			if ($stmt->execute(array($time, $stationNumber, $appointmentId)) == false) {
				throw new Exception();
			}

			$stmt = $DB_CONN->prepare("INSERT INTO AppointmentFilingStatus (servicedAppointmentId, filingStatusId)
				VALUES ((SELECT servicedAppointmentId FROM ServicedAppointment WHERE appointmentId = ?), ?)");
			foreach ($filingStatusIds as $filingStatusId) {
				if ($stmt->execute(array($appointmentId, $filingStatusId)) == false) {
					throw new Exception();
				}
			}

			$DB_CONN->commit();
		} catch (Exception $e) {
			$DB_CONN->rollBack();
			$response['success'] = false;
			$response['error'] = 'Unable to update the appointment. Please refresh the page and try again.';
		}

		echo json_encode($response);
		$stmt = null;
	}

	function appointmentIncomplete($appointmentId) {
		GLOBAL $DB_CONN, $USER;

		$response = array();
		$response['success'] = true;

		try {
			$query = 'UPDATE ServicedAppointment
				SET completed = FALSE
				WHERE appointmentId = ?';
			$stmt = $DB_CONN->prepare($query);

			$success = $stmt->execute(array($appointmentId));
			if ($success == false) throw new Exception();

			# Insert an automatic note saying it was marked as incomplete
			$noteAccessor = new NoteAccessor();
			$noteText = 'Marked as Incomplete [Automatic Note]';
			$userId = $USER->getUserId();
			$noteAccessor->addNote($appointmentId, $noteText, $userId);
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
			$appointmentAccessor = new AppointmentAccessor();
			$appointmentAccessor->cancelAppointment($appointmentId);
		} catch (Exception $e) {
			$response['success'] = false;
			$response['error'] = 'Unable to update the appointment. Please refresh the page and try again.';
		}

		echo json_encode($response);
		$stmt = null;
	}
