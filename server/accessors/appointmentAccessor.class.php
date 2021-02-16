<?php

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once "$root/server/config.php";

class AppointmentAccessor {

	public function cancelAppointment($appointmentId) {
		GLOBAL $DB_CONN;

		$query = 'INSERT INTO ServicedAppointment (servicedAppointmentId, appointmentId, cancelled, completed)
			VALUES (
				(SELECT sa2.servicedAppointmentId FROM ServicedAppointment sa2 WHERE appointmentId = ?),
				?, TRUE, FALSE
			) ON DUPLICATE KEY UPDATE cancelled = TRUE, completed = FALSE;';

		$stmt = $DB_CONN->prepare($query);
		$success = $stmt->execute(array($appointmentId, $appointmentId));
		if ($success == false) {
			throw new Exception('Unable to cancel the appointment.', MY_EXCEPTION);
		}
	}

	public function rescheduleAppointment($appointmentId, $appointmentTimeId) {
		GLOBAL $DB_CONN;

		try {
			$DB_CONN->beginTransaction();

			self::reschedule($appointmentId, $appointmentTimeId);
			self::resetFieldsInServicedAppointmentByAppointmentId($appointmentId);

			$DB_CONN->commit();
		} catch (Exception $e) {
			$DB_CONN->rollback();
			throw $e;
		}
	}

	private function reschedule($appointmentId, $appointmentTimeId) {
		GLOBAL $DB_CONN;

		$query = "UPDATE Appointment
			SET appointmentTimeId = ?
			WHERE appointmentId = ?";

		$stmt = $DB_CONN->prepare($query);
		$success = $stmt->execute(array($appointmentTimeId, $appointmentId));
		if ($success == false) {
			throw new Exception('Unable to reschedule the appointment.', MY_EXCEPTION);
		}
	}

	// Resets the necessary fields in the ServicedAppointment entry associated with the given appointmentId
	private function resetFieldsInServicedAppointmentByAppointmentId($appointmentId) {
		GLOBAL $DB_CONN;

		$query = "UPDATE ServicedAppointment
			SET timeIn = NULL, timeReturnedPapers = NULL, timeAppointmentStarted = NULL, timeAppointmentEnded = NULL,
				completed = NULL, cancelled = FALSE
			WHERE appointmentId = ?";

		$stmt = $DB_CONN->prepare($query);
		$stmt->execute(array($appointmentId));
	}
  
}