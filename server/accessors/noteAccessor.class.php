<?php

$root = realpath($_SERVER['DOCUMENT_ROOT']);
require_once "$root/server/config.php";

class NoteAccessor {

	/*
	 * NOTE: If null is used as the userId, it is assumed to be created by "SYSTEM" user
	 */
	public function addNote($appointmentId, $noteText, $userId = null) {
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

		$query = "SELECT noteId, note, COALESCE(firstName, 'SYSTEM') AS createdByFirstName, 
				COALESCE(lastName, '') AS createdByLastName,
				DATE_FORMAT(createdAt, '%c/%d/%Y %l:%i %p') AS createdAt
			FROM Note
				LEFT JOIN User ON Note.createdBy = User.userId
			WHERE appointmentId = ?
			ORDER BY noteId ASC";
		$stmt = $DB_CONN->prepare($query);

		$success = $stmt->execute(array($appointmentId));
		if ($success == false) {
			throw new Exception("Unable to get notes for appointment ID $appointmentId", MY_EXCEPTION);
		}

		$notes = $stmt->fetchAll(PDO::FETCH_ASSOC);
		foreach ($notes as &$note) {
			
		}
		
		return $notes;
	}

}