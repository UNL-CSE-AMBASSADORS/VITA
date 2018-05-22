<?php

$root = realpath($_SERVER['DOCUMENT_ROOT']);
require_once "$root/server/config.php";

class NoteAccessor {

	public function addNote($appointmentId, $noteText, $userId) {
		GLOBAL $DB_CONN;

		$query = "INSERT INTO Note (appointmentId, note, createdBy)
			VALUES (?, ?, ?)";
		$stmt = $DB_CONN->prepare($query);

		$success = $stmt->execute(array($appointmentId, $noteText, $userId));
		if ($success == false) {
			throw new Exception('Unable to add the note.', MY_EXCEPTION);
		}
	}

	public function getNotesForAppointment($appointmentId) {
		GLOBAL $DB_CONN;

		$query = "SELECT noteId, note, firstName AS createdByFirstName, lastName AS createdByLastName,
			DATE_FORMAT(createdAt, '%c/%d/%Y %l:%i %p') AS createdAt
			FROM Note
			JOIN User ON Note.createdBy = User.userId
			WHERE appointmentId = ?
			ORDER BY noteId ASC";
		$stmt = $DB_CONN->prepare($query);

		$success = $stmt->execute(array($appointmentId));
		if ($success == false) {
			throw new Exception("Unable to get notes for appointment ID $appointmentId", MY_EXCEPTION);
		}

		$notes = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		return $notes;
	}

}