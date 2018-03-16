<?php

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once "$root/server/config.php";

class AppointmentManager {

	public function cancelAppointment($appointmentId) {
		GLOBAL $DB_CONN;

		$query = "INSERT INTO ServicedAppointment (appointmentId, notCompletedDescription, completed)
			VALUES (?, 'Cancelled Appointment', FALSE)";
		$stmt = $DB_CONN->prepare($query);

		if ($stmt->execute(array($appointmentId)) == false) {
			throw new Exception('Unable to cancel the appointment.', MY_EXCEPTION);
		}
	}

}