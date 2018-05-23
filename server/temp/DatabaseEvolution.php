<?php

$root = realpath($_SERVER['DOCUMENT_ROOT']);
require_once "$root/server/user.class.php";
$USER = new User();
if (!$USER->getUserId() == 1) {
	header("Location: /unauthorized");
	die();
}

require_once "$root/server/config.php";
require_once "$root/server/accessors/noteAccessor.class.php";

function runEvolution() {
	GLOBAL $DB_CONN;

	# Grab all the serviced appointments
	$query = 'SELECT notCompletedDescription, appointmentId
		FROM ServicedAppointment
		WHERE notCompletedDescription IS NOT NULL';
	$stmt = $DB_CONN->prepare($query);
	$stmt->execute();
	$servicedAppointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

	# Insert the notes
	$noteAccessor = new NoteAccessor();
	$userId = 1; # The id of Matthew Meacham
	foreach ($servicedAppointments as $servicedAppointment) {
		$noteAccessor->addNote($servicedAppointment['appointmentId'], $servicedAppointment['notCompletedDescription'], $userId);
	}
}

runEvolution();




