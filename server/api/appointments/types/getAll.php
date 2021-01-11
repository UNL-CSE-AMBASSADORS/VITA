<?php

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once "$root/server/config.php";

getAllAppointmentTypes();

function getAllAppointmentTypes() {
	GLOBAL $DB_CONN;

	$query = 'SELECT appointmentTypeId, name, lookupName FROM AppointmentType
		WHERE archived = FALSE;';
	$stmt = $DB_CONN->prepare($query);
	$stmt->execute();
	$appointmentTypes = $stmt->fetchAll(PDO::FETCH_ASSOC);

	echo json_encode($appointmentTypes);
}
