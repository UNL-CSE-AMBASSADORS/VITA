<?php

$root = realpath($_SERVER['DOCUMENT_ROOT']);
require_once "$root/server/config.php";
require_once "$root/server/user.class.php";

$USER = new User();
if (!$USER->isLoggedIn()) {
	header("Location: /unauthorized");
	die();
}

require_once "$root/server/accessors/noteAccessor.class.php";

if (isset($_REQUEST['action'])) {
	switch ($_REQUEST['action']) {
		case 'add': addNote($_POST['appointmentId'], $_POST['noteText']); break;
		case 'getForAppointment': getNotesForAppointment($_GET['appointmentId']); break;
		default:
			die('Invalid action function. This instance has been reported.');
			break;
	}
}

function addNote($appointmentId, $noteText) {
	GLOBAL $USER;

	$response = array();
	$response['success'] = true;

	try {
		$userId = $USER->getUserId();

		$noteAccessor = new NoteAccessor();
		$noteAccessor->addNote($appointmentId, $noteText, $userId);
	} catch (Exception $e) {
		$response['success'] = false;
		$response['error'] = 'There was an error creating the note on the server. Please refresh the page and try again.';
	}
	
	echo json_encode($response);
}

function getNotesForAppointment($appointmentId) {
	GLOBAL $DB_CONN;

	$response = array();
	$response['success'] = true;

	try {
		$noteAccessor = new NoteAccessor();
		$notes = $noteAccessor->getNotesForAppointment($appointmentId);
		$response['notes'] = $notes;
	} catch (Exception $e) {
		$response['success'] = false;
		$response['error'] = 'There was an error getting the notes for this appointment. Please refresh the page and try again.';
	}

	echo json_encode($response);
}





