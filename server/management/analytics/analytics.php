<?php

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once "$root/server/config.php";
require_once "$root/server/user.class.php";

$USER = new User();
if (!$USER->isLoggedIn() || !$USER->hasPermission('use_admin_tools')) {
	header("Location: /unauthorized");
	die();
}

if (isset($_REQUEST['action'])) {
	switch ($_REQUEST['action']) {
		case 'getAggregateAppointmentHistory': getAggregateAppointmentHistory(); break;
		case 'getAppointmentCountsPerSiteHistory': getAppointmentCountsPerSiteHistory(); break;
		default:
			die('Invalid action function. This instance has been reported.');
			break;
	}
}

function getAggregateAppointmentHistory() {
	GLOBAL $DB_CONN;

	$response = array();
	$response['success'] = true;

	try {
		$query = 'SELECT ResidentialCounts.year, numberOfResidentialAppointments, 
					COALESCE(numberOfInternationalAppointments, 0) AS numberOfInternationalAppointments, 
					(numberOfResidentialAppointments + COALESCE(numberOfInternationalAppointments, 0)) AS totalNumberOfAppointments,
					numberOfUnlOrWesleyanAppointments
				FROM (
						-- Residential Counts
						((SELECT YEAR(Appointment.createdAt) AS year, 
							COUNT(*) AS numberOfResidentialAppointments
						FROM Appointment
						LEFT JOIN Answer ON Answer.appointmentId = Appointment.appointmentId
							AND Answer.questionId = (SELECT questionId FROM Question WHERE lookupName = "treaty_type")
						WHERE Appointment.archived = FALSE
							AND Answer.possibleAnswerId IS NULL
						GROUP BY YEAR(Appointment.createdAt)) AS ResidentialCounts)
					LEFT JOIN
						-- International Counts
						((SELECT YEAR(Appointment.createdAt) AS year, 
							COUNT(*) AS numberOfInternationalAppointments
						FROM Appointment
						LEFT JOIN Answer ON Answer.appointmentId = Appointment.appointmentId
							AND Answer.questionId = (SELECT questionId FROM Question WHERE lookupName = "treaty_type")
						WHERE Appointment.archived = FALSE
							AND Answer.possibleAnswerId IS NOT NULL
						GROUP BY YEAR(Appointment.createdAt)) AS InternationalCounts)
					ON ResidentialCounts.year = InternationalCounts.year
					LEFT JOIN 
						-- UNL/Wesleyan Counts
						((SELECT YEAR(Appointment.createdAt) AS year, 
							COUNT(*) AS numberOfUnlOrWesleyanAppointments
						FROM Appointment
						LEFT JOIN Answer ON Answer.appointmentId = Appointment.appointmentId
							AND Answer.questionId = (SELECT questionId FROM Question WHERE lookupName = "unl_student")
						LEFT JOIN PossibleAnswer ON PossibleAnswer.possibleAnswerId = Answer.possibleAnswerId
						WHERE Appointment.archived = FALSE
							AND PossibleAnswer.text = "Yes"
						GROUP BY YEAR(Appointment.createdAt)) AS UnlOrWesleyanCounts)
					ON ResidentialCounts.year = UnlOrWesleyanCounts.year
				)
				ORDER BY year;';
	
		$stmt = $DB_CONN->prepare($query);
		$stmt->execute();
		$aggregateAppointmentHistory = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$response['aggregateAppointmentHistory'] = $aggregateAppointmentHistory;
	} catch(Exception $e) {
		$response['success'] = false;
		$response['error'] = 'There was an error loading aggregate appointment history on the server. Please refresh the page and try again.';
	}

	echo json_encode($response);
}

function getAppointmentCountsPerSiteHistory() {
	GLOBAL $DB_CONN;

	$response = array();
	$response['success'] = true;

	try {
		$query = 'SELECT Site.title, Site.siteId, YEAR(AppointmentTime.scheduledTime) AS year, COUNT(*) AS numberOfAppointments
			FROM Appointment
			JOIN AppointmentTime ON AppointmentTime.appointmentTimeId = Appointment.appointmentTimeId
			JOIN Site ON Site.siteId = AppointmentTime.siteId
			WHERE Appointment.archived = FALSE
			GROUP BY Site.siteId, year
			ORDER BY Site.siteId, year;';
	
		$stmt = $DB_CONN->prepare($query);
		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

		// Group site data
		$appointmentCountsPerSiteHistory = array();
		foreach ($result as $entry) {
			$appointmentCountsPerSiteHistory[$entry['siteId']]['title'] = $entry['title'];
			$appointmentCountsPerSiteHistory[$entry['siteId']]['appointmentCounts'][$entry['year']] = $entry['numberOfAppointments'];
		}

		$response['appointmentCountsPerSiteHistory'] = $appointmentCountsPerSiteHistory;
	} catch(Exception $e) {
		$response['success'] = false;
		$response['error'] = 'There was an error loading appointment counts per site history. Please refresh the page and try again.';
	}

	echo json_encode($response);
}

