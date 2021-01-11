<?php
// This script will only work for Joey Carrigan

$root = realpath($_SERVER['DOCUMENT_ROOT']);
require_once "$root/server/user.class.php";
$USER = new User();
if (!$USER->isLoggedIn() || $USER->getUserId() !== '358') {
	header("Location: /unauthorized");
	die();
}

require_once "$root/server/config.php";

evolveDatabase();

function evolveDatabase() {
	GLOBAL $DB_CONN;

	$alreadyEvolved = true; // TODO: Update when actually running
	if ($alreadyEvolved) {
		die('The evolution has already occurred');
	}

	$query = 'SELECT AppointmentTime.appointmentTimeId, Site.doesInternational, Site.isVirtual, AppointmentTime.appointmentTypeId
		FROM AppointmentTime
			JOIN Site ON Site.siteId = AppointmentTime.siteId';
	
	$stmt = $DB_CONN->prepare($query);
	$stmt->execute();
	$appointmentTimes = $stmt->fetchAll(PDO::FETCH_ASSOC);

	// Iterate through all the AppointmentTimes as assign them an appointmentTypeId
	$query = 'UPDATE AppointmentTime
		SET appointmentTypeId = ?
		WHERE appointmentTime.appointmentTimeId = ?';
	$stmt = $DB_CONN->prepare($query);
	foreach ($appointmentTimes as &$appointmentTime) {
		$appointmentTypeId = 1; // Residential
		if ($appointmentTime['doesInternational'] == true && $appointmentTime['isVirtual'] == true) {
			$appointmentTypeId = 7; // Virtual-China, this is the easiest thing we can do--just assign all international appointments as china
		} else if ($appointmentTime['doesInternational'] == false && $appointmentTime['isVirtual'] == true) {
			$appointmentTypeId = 6; // Virtual-Residential
		} else if ($appointmentTime['doesInternational'] == true && $appointmentTime['isVirtual'] == false) {
			$appointmentTypeId = 2; // China, this is the easiest thing we can do--just assign all international appointments as china
		}
		$stmt->execute(array($appointmentTypeId, $appointmentTime['appointmentTimeId']));
	}

}
