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
	$query = 'SELECT servicedAppointmentId, notCompletedDescription, appointmentId
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

		# If the appointment was cancelled as shown in the notCompletedDescription field (the old method), then
		# we need to set the cancelled field to true
		$cancelled = $servicedAppointment['notCompletedDescription'] === 'Cancelled Appointment';
		if ($cancelled) {
			setCancelledToTrue($servicedAppointment['servicedAppointmentId']);
		}
	}
}

function setCancelledToTrue($servicedAppointmentId) {
	GLOBAL $DB_CONN;

	$query = 'UPDATE ServicedAppointment
		SET cancelled = TRUE
		WHERE servicedAppointmentId = ?';
	$stmt = $DB_CONN->prepare($query);
	$stmt->execute(array($servicedAppointmentId));	
}

runEvolution();




