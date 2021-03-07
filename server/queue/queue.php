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

date_default_timezone_set('America/Chicago');

if (isset($_REQUEST['action'])) {
	switch($_REQUEST['action']) {
		case 'getAppointments': getAppointments($_GET['date'], $_GET['siteId']); break;
		case 'awaiting': markAppointmentAsAwaiting($_POST['appointmentId']); break;
		case 'checkIn': markAppointmentAsCheckedIn($_POST['appointmentId']); break;
		case 'paperworkCompleted': markAppointmentAsPaperworkCompleted($_POST['appointmentId']); break;
		case 'startAppointment': markAppointmentAsBeingPrepared($_POST['appointmentId']); break;
		case 'completeAppointment': markAppointmentAsCompleted($_POST['appointmentId']); break;
		case 'incompleteAppointment': markAppointmentAsIncomplete($_POST['appointmentId']); break;
		case 'cancelAppointment': markAppointmentAsCancelled($_POST['appointmentId']); break;
		default:
			die('Invalid action function. This instance has been reported.');
			break;
	}
}

function getAppointments($date, $siteId) {
	GLOBAL $DB_CONN, $USER;

	$response = array();
	$response['success'] = true;

	try {
		$canViewClientInformation = $USER->hasPermission('view_client_information');

		$query = 'SELECT t1.appointmentId, TIME_FORMAT(AppointmentTime.scheduledTime, "%l:%i %p") AS scheduledTime, 
					firstName, lastName, timeIn, timeReturnedPapers, timeAppointmentStarted, timeAppointmentEnded, 
					completed, cancelled, language, Client.clientId, 
					(DATE_ADD(AppointmentTime.scheduledTime, INTERVAL 30 MINUTE) < NOW() AND timeIn IS NULL) AS noShow,
					(AppointmentTime.scheduledTime < t1.createdAt) AS walkIn , appointmentType.name as appointmentType,
					t2.visa';
		if ($canViewClientInformation) {
			$query .= ', phoneNumber, emailAddress ';
		}
		$query .= 'FROM Appointment t1
					LEFT JOIN ServicedAppointment ON t1.appointmentId = ServicedAppointment.appointmentId
					JOIN Client ON t1.clientId = Client.clientId
					JOIN AppointmentTime ON t1.appointmentTimeId = AppointmentTime.appointmentTimeId
					JOIN AppointmentType ON AppointmentTime.appointmentTypeId = AppointmentType.appointmentTypeId
					LEFT JOIN
					(SELECT Answer.appointmentId, Answer.questionId, PossibleAnswer.text as visa
					FROM Answer
					JOIN PossibleAnswer ON Answer.possibleAnswerId = PossibleAnswer.possibleAnswerId
					WHERE questionId = 3
					) t2
					ON t1.appointmentId = t2.appointmentId
					WHERE DATE(scheduledTime) = ?
						AND siteId = ?
						AND (cancelled IS NULL OR cancelled = FALSE)
						AND (completed IS NULL OR completed != FALSE)
						AND t1.archived = FALSE';
		$stmt = $DB_CONN->prepare($query);
		$stmt->execute(array($date, $siteId));
		$appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

		foreach ($appointments as &$appointment) {
			$appointment['language'] = expandLanguageCode($appointment['language']);
			// Convert the following from tinyints to booleans
			$appointment['noShow'] = (boolean)$appointment['noShow'];
			$appointment['walkIn'] = (boolean)$appointment['walkIn'];
			$appointment['cancelled'] = (boolean)$appointment['cancelled'];

			// Shorten last name to only the initial if user doesn't have permission to view entire last name
			if (!$canViewClientInformation) {
				$appointment['lastName'] = substr($appointment['lastName'], 0, 1).'.';
			}
		}

		$response['appointments'] = $appointments;
	} catch (Exception $e) {
		$response['success'] = false;
		$response['error'] = 'There was an error on the server getting the appointments. Please refresh the page and try again';
	}

	echo json_encode($response);
}

function markAppointmentAsAwaiting($appointmentId) {
	GLOBAL $DB_CONN;

	$response = array();
	$response['success'] = true;

	try {
		$query = 'UPDATE ServicedAppointment
			SET timeIn = NULL
			WHERE appointmentId = ?;';

		$stmt = $DB_CONN->prepare($query);
		$stmt->execute(array($appointmentId));
	} catch (Exception $e) {
		$response['success'] = false;
		$response['error'] = 'There was an error on the server marking the appointment as paperwork completed. Please refresh the page and try again';
	}

	echo json_encode($response);
}

function markAppointmentAsCheckedIn($appointmentId) {
	GLOBAL $DB_CONN;

	$response = array();
	$response['success'] = true;

	try {
		$time = date("Y-m-d H:i:s");

		$query = 'INSERT INTO ServicedAppointment (servicedAppointmentId, appointmentId, timeIn)
			VALUES (
				(SELECT sa2.servicedAppointmentId FROM ServicedAppointment sa2 WHERE appointmentId = ?),
				?, ?
			) ON DUPLICATE KEY UPDATE 
				timeIn = ?, 
				timeReturnedPapers = NULL;';

		$stmt = $DB_CONN->prepare($query);
		$stmt->execute(array($appointmentId, $appointmentId, $time, $time));
	} catch (Exception $e) {
		$response['success'] = false;
		$response['error'] = 'There was an error on the server marking the appointment as checked in. Please refresh the page and try again';
	}

	echo json_encode($response);
}

function markAppointmentAsPaperworkCompleted($appointmentId) {
	GLOBAL $DB_CONN;

	$response = array();
	$response['success'] = true;

	try {
		$time = date("Y-m-d H:i:s");

		$query = 'UPDATE ServicedAppointment
			SET timeReturnedPapers = ?,
				timeAppointmentStarted = NULL
			WHERE appointmentId = ?;';

		$stmt = $DB_CONN->prepare($query);
		$stmt->execute(array($time, $appointmentId));
	} catch (Exception $e) {
		$response['success'] = false;
		$response['error'] = 'There was an error on the server marking the appointment as paperwork completed. Please refresh the page and try again';
	}

	echo json_encode($response);
}

function markAppointmentAsBeingPrepared($appointmentId) {
	GLOBAL $DB_CONN;

	$response = array();
	$response['success'] = true;

	try {
		$time = date("Y-m-d H:i:s");

		$query = 'UPDATE ServicedAppointment
			SET timeAppointmentStarted = ?,
				timeAppointmentEnded = NULL,
				completed = NULL
			WHERE appointmentId = ?;';

		$stmt = $DB_CONN->prepare($query);
		$stmt->execute(array($time, $appointmentId));
	} catch (Exception $e) {
		$response['success'] = false;
		$response['error'] = 'There was an error on the server marking the appointment as being prepared. Please refresh the page and try again';
	}

	echo json_encode($response);
}

function markAppointmentAsCompleted($appointmentId) {
	GLOBAL $DB_CONN;

	$response = array();
	$response['success'] = true;

	try {
		$time = date("Y-m-d H:i:s");

		$query = 'UPDATE ServicedAppointment
			SET timeAppointmentEnded = ?,
				completed = TRUE
			WHERE appointmentId = ?;';

		$stmt = $DB_CONN->prepare($query);
		$stmt->execute(array($time, $appointmentId));
	} catch (Exception $e) {
		$response['success'] = false;
		$response['error'] = 'There was an error on the server marking the appointment as being prepared. Please refresh the page and try again';
	}

	echo json_encode($response);
}

function markAppointmentAsIncomplete($appointmentId) {
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
}

function markAppointmentAsCancelled($appointmentId) {
	$response = array();
	$response['success'] = true;

	try {
		$appointmentAccessor = new AppointmentAccessor();
		$appointmentAccessor->cancelAppointment($appointmentId);
	} catch (Exception $e) {
		$response['success'] = false;
		$response['error'] = 'Unable to cancel the appointment. Please refresh the page and try again.';
	}

	echo json_encode($response);
}

###################
# Private Methods #
###################

function expandLanguageCode($languageCode) {
	if ($languageCode === 'eng') return 'English';
	if ($languageCode === 'vie') return 'Vietnamese';
	if ($languageCode === 'spa') return 'Spanish';
	if ($languageCode === 'ara') return 'Arabic';
	return 'Unknown';
}