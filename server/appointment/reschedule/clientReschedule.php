<?php

$root = realpath($_SERVER['DOCUMENT_ROOT']);

require_once "$root/server/config.php";

if (isset($_REQUEST['action'])) {
	switch ($_REQUEST['action']) {
		case 'doesTokenExist': doesTokenExist($_GET['token']); break;
		default:
			die('Invalid action function. This instance has been reported.');
			break;
	}
}

function doesTokenExist($token) {
	GLOBAL $DB_CONN;

	$response = array();
	$response['success'] = true;

	$query = 'SELECT COUNT(*) AS count
		FROM AppointmentClientReschedule
		WHERE token = ?';

	$stmt = $DB_CONN->prepare($query);
	if (!$stmt->execute(array($token))) {
		throw new Exception('There was an error on the server validating the token. Please refresh the page and try again', MY_EXCEPTION);
	}

	$result = $stmt->fetch();
	$response['exists'] = $result['count'] > 0;

	echo json_encode($response);
}
