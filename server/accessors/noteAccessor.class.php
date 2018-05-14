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

}