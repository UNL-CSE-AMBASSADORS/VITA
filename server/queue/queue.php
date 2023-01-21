<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once "$root/server/config.php";

require_once "$root/server/user.class.php";
$USER = new User();
if (!$USER->isLoggedIn()) {
	header("Location: /unauthorized");
	die();
}

require_once "$root/server/accessors/appointmentAccessor.class.php";
require_once "$root/server/accessors/noteAccessor.class.php";

date_default_timezone_set('America/Chicago');

if (isset($_REQUEST['action'])) {
	switch($_REQUEST['action']) {
		case 'getProgressionSteps': getProgressionSteps($_GET['date'], $_GET['siteId']); break;
		case 'completeAppointment': markAppointmentAsCompleted($_POST['appointmentId']); break; //todo check which ones to delete/keep
		case 'incompleteAppointment': markAppointmentAsIncomplete($_POST['appointmentId']); break;
		case 'cancelAppointment': markAppointmentAsCancelled($_POST['appointmentId']); break;
		case 'getPossibleSwimlanes': getPossibleSwimlanes(); break;
		case 'getPossibleSubsteps': getPossibleSubsteps(); break;
		case 'deleteTimestamp': deleteTimestamp($_POST['appointmentId'], $_POST['stepId']); break;
		case 'insertStepTimestamp': insertStepTimestamp($_POST['appointmentId'], $_POST['stepId'], $_POST['setTimeStampToNull']); break;
		case 'insertSubstepTimestamp': insertSubstepTimestamp($_POST['appointmentId'], $_POST['substepId']); break;
		default:
			die('Invalid action function. This instance has been reported.');
			break;
	}
}


// get all the steps to render the swimlanes before we fill them in
function getPossibleSwimlanes() {
	GLOBAL $DB_CONN;

	$response = array();
	$response['success'] = true;

	try {
		$query = 'select *
		from ProgressionType progType
		left join ProgressionStep progStep
			on progType.progressionTypeId = progStep.progressionTypeId
		order by progType.progressionTypeId, progStep.progressionStepOrdinal;';
		$stmt = $DB_CONN->prepare($query);
		$stmt->execute();
	
		$potentialProgressionSteps = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$response['potentialProgressionSteps'] = $potentialProgressionSteps;
	} catch (Exception $e) {
		$response['success'] = false;
		$response['error'] = 'There was an error on the server retrieving appointment times. Please try again later.';
	}

	echo json_encode($response);
}

// get all the steps to render the swimlanes before we fill them in
function getPossibleSubsteps() {
	GLOBAL $DB_CONN;

	$response = array();
	$response['success'] = true;

	try {
		$query = 'select a.progressionSubstepId, a.progressionSubstepName,
		b.progressionStepOrdinal, b.progressionTypeId
        from ProgressionSubstep a
        left join ProgressionStep b
        on a.progressionStepId = b.progressionStepId
		order by a.progressionSubstepName;';
		$stmt = $DB_CONN->prepare($query);
		$stmt->execute();
	
		$possibleSubsteps = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$response['possibleSubsteps'] = $possibleSubsteps;
	} catch (Exception $e) {
		$response['success'] = false;
		$response['error'] = 'There was an error on the server retrieving appointment times. Please try again later.';
	}

	echo json_encode($response);
}

function getProgressionSteps($date, $siteId) {
	GLOBAL $DB_CONN, $USER;

	$response = array();
	$response['success'] = true;

	try {
		$canViewClientInformation = $USER->hasPermission('view_client_information');
		$DB_CONN->query('drop temporary table if exists stepTimestamps');
		$DB_CONN->query('create temporary table stepTimestamps
			select
				a.appointmentId,
				case when a.progressionStepId is not null then typeFromStep.progressionTypeId else typeFromSubStep.progressionTypeId end as progressionTypeId,
				# each row will only have either a step or a substep--if it has a substep, then we can derive the step name from the schema.
				case when a.progressionStepId is not null then typeFromStep.progressionTypeName else typeFromSubStep.progressionTypeName end as progressionTypeName,
				case when a.progressionStepId is not null then stepWithoutSubStep.progressionStepId else stepFromSubStep.progressionStepId end as progressionStepId,
				case when a.progressionStepId is not null then stepWithoutSubStep.progressionStepName else stepFromSubStep.progressionStepName end as progressionStepName,
				case when a.progressionStepId is not null then stepWithoutSubStep.progressionStepOrdinal else stepFromSubStep.progressionStepOrdinal end as progressionStepOrdinal,	
				substep.progressionSubStepId, substep.progressionSubStepName,
				timestamp
			from ProgressionTimestamp a
			# a given row in ProgressionTimestamp will have either a step ID or a substep ID. The other ID will be null.
			left join ProgressionStep stepWithoutSubStep # join steps directly (this is for steps like legacy without substeps)
				on a.progressionstepid = stepWithoutSubStep.progressionstepid
			left join ProgressionType typeFromStep
				on stepWithoutSubStep.progressionTypeId = typeFromStep.progressionTypeId
			left join ProgressionSubstep substep # join substeps directly
				on a.progressionSubStepId = substep.progressionSubStepId
			left join ProgressionStep stepFromSubStep # the substep from the timestamp table is derived from a step, we want to show that step for clarity here. we just leave stepId null in ProgressionTimestamp because it would be redundant and could potentially result in erroneous insertions (what if substep "FSA" only exists for stepId 13, but we accientally inserted 12 into ProgressionTimestamps stepID field? Could probably impose some restraint to avoid this, but it would still be redundant data.
				on stepFromSubStep.progressionStepId = substep.progressionStepId
			left join ProgressionType typeFromSubStep
				on stepWithoutSubStep.progressionTypeId = typeFromSubStep.progressionTypeId
			order by appointmentId
		');
		$DB_CONN->query('drop temporary table if exists stepTimestamps2');
		$DB_CONN->query('create temporary table stepTimestamps2	select * from stepTimestamps');
		$DB_CONN->query('drop temporary table if exists rankedGroups');
		$DB_CONN->query('create temporary table rankedGroups
			select a.appointmentId, a.progressionTypeId, a.progressionStepOrdinal, count(b.progressionStepOrdinal)+1 as advancement_rank
			from stepTimestamps a
			left join stepTimestamps2 b
			on a.progressionStepOrdinal < b.progressionStepOrdinal
			and a.appointmentId = b.appointmentId
			and a.progressionTypeId = b.progressionTypeId
			group by a.appointmentId, a.progressionTypeId, a.progressionStepOrdinal
			order by appointmentId, advancement_rank desc
			');
		$DB_CONN->query('drop temporary table if exists appointment_step_timestamp');
		$DB_CONN->query('create temporary table appointment_step_timestamp
			select a.*, b.advancement_rank
			from stepTimestamps a
			join rankedGroups b
			on a.appointmentId = b.appointmentId
			and a.progressionTypeId = b.progressionTypeId
			and a.progressionStepOrdinal = b.progressionStepOrdinal
		');
		
		$query = 'select
			a.appointmentId, a.progressionTypeId, a.progressionTypeName, a.progressionStepName, a.progressionSubstepId, a.progressionSubstepName,
			a.timestamp, a.progressionStepOrdinal, a.advancement_rank,
			TIME_FORMAT(atime.scheduledTime, "%l:%i %p") AS scheduledTime,
			Client.firstName, Client.lastName, 
			sa.cancelled, app.language, app.clientId,
			# (DATE_ADD(at.scheduledTime, INTERVAL 30 MINUTE) < NOW() AND timeIn IS NULL) AS noShow, #this isnt able to be done on one line anymore.
			atime.scheduledTime as scheduledDatetime,
			(atime.scheduledTime < app.createdAt) AS walkIn, atype.name as appointmentType,
			VisaAnswer.visa';
		if ($canViewClientInformation) {
			$query .= ', Client.phoneNumber, Client.emailAddress';
		}
		// appointment_step_timestamp is a view that makes it easier to query ProgressionTimestamps
		$query .= ' from Appointment app
			left join appointment_step_timestamp a
				on a.appointmentId = app.appointmentId
			left join AppointmentTime atime
				on app.appointmentTimeId = atime.appointmentTimeId
			left join Client
				on app.clientId = Client.clientId
			left join ServicedAppointment sa
				on a.appointmentId = sa.appointmentId
			left join AppointmentType atype
				on atime.appointmentTypeId = atype.appointmentTypeId
			LEFT JOIN
				(SELECT Answer.appointmentId, PossibleAnswer.text as visa
				FROM Answer
				JOIN Question ON Answer.questionId = Question.questionId
				JOIN PossibleAnswer ON Answer.possibleAnswerId = PossibleAnswer.possibleAnswerId
				WHERE Question.lookupName = \'visa\'
				) VisaAnswer
			ON app.appointmentId = VisaAnswer.appointmentId
			WHERE DATE(scheduledTime) = ?
				AND siteId = ?
				AND (cancelled IS NULL OR cancelled = FALSE)
				AND (completed IS NULL OR completed != FALSE)
				AND app.archived = FALSE
			;';
		// If advancement rank = 1, we want to send it through so that we can add it to that step's swimlane
		// If an appointment is new, currently we have it set up so that they will have an ordinalRank = 0 and timestamp = null
		// so we want to pass those through, too, so the front end can know which pool to put the appt in.
		$stmt = $DB_CONN->prepare($query);
		$stmt->execute(array($date, $siteId));
		$progressionSteps = $stmt->fetchAll(PDO::FETCH_ASSOC);

		// TODO not sure if all this is necessary anymore. going to leave it for now.
		foreach ($progressionSteps as &$step) {
			$step['language'] = expandLanguageCode($step['language']);
			// Convert the following from tinyints to booleans
			# $appointment['noShow'] = (boolean)$appointment['noShow'];
			$step['walkIn'] = (boolean)$step['walkIn'];
			$step['cancelled'] = (boolean)$step['cancelled'];

			// Shorten last name to only the initial if user doesn't have permission to view entire last name
			if (!$canViewClientInformation) {
				$step['lastName'] = substr($step['lastName'], 0, 1).'.';
			}
		}

		$response['progressionSteps'] = $progressionSteps;
	} catch (Exception $e) {
		$response['success'] = false;
		$response['error'] = 'There was an error on the server getting the appointments. Please refresh the page and try again';
	}

	echo json_encode($response);
}

function deleteTimestamp($appointmentId, $progressionStepId) {
	GLOBAL $DB_CONN;

	$response = array();
	$response['success'] = true;

	try {
		$query = 'DELETE a FROM ProgressionTimestamp a
			left join ProgressionStep stepWithoutSubstep
				on a.progressionStepId = stepWithoutSubstep.progressionstepId
			left join ProgressionSubstep substep
				on a.progressionSubstepId = substep.progressionSubstepId
			left join ProgressionStep stepFromSubstep
				on stepFromSubstep.progressionStepId = substep.progressionStepId
			WHERE a.appointmentId = ?
			and case when a.progressionStepId is not null then stepWithoutSubstep.progressionStepId else stepFromSubstep.progressionStepId end = ?;
		';

		$stmt = $DB_CONN->prepare($query);
		$stmt->execute(array($appointmentId, $progressionStepId));
	} catch (Exception $e) {
		$response['success'] = false;
		$response['error'] = "There was an error on the server changing this appointment's records. Please refresh the page and try again";
	}

	echo json_encode($response);
}

function insertStepTimestamp($appointmentId, $stepId, $setTimeStampToNull) {
	GLOBAL $DB_CONN;

	$response = array();
	$response['success'] = true;
	
	try {
		// $stepId = null when step was moved to "Awaiting", step 0. there is no stepId for this, we just make the
		// first ordinal step have a null timestamp
		$time = ($setTimeStampToNull == 'true') ? NULL : date("Y-m-d H:i:s");

		// don't have to insert substepId because it is null by default.
		// syntax for https://stackoverflow.com/questions/15383852/sql-if-exists-update-else-insert-into
		$query = 'INSERT INTO ProgressionTimestamp
		(appointmentId, progressionStepId, timestamp) 
	  VALUES
		(?, ?, ?)
	  ON DUPLICATE KEY UPDATE
	  timestamp = VALUES(timestamp);';

		$stmt = $DB_CONN->prepare($query);
		$stmt->execute(array($appointmentId, $stepId, $time));
	} catch (Exception $e) {
		$response['success'] = false;
		$response['error'] = "There was an error on the server updating the appointment's queue status. Please refresh the page and try again";
	}

	echo json_encode($response);
}

function insertSubstepTimestamp($appointmentId, $substepId) {
	GLOBAL $DB_CONN;

	$response = array();
	$response['success'] = true;

	try {
		$time = date("Y-m-d H:i:s");
		// don't have to insert subStepId because it is null by default.
		// syntax for https://stackoverflow.com/questions/15383852/sql-if-exists-update-else-insert-into
		// looks like this syntax might throw a warning? can update TODO https://dev.mysql.com/doc/refman/8.0/en/insert-on-duplicate.html

		$query = 'INSERT INTO ProgressionTimestamp (appointmentId, progressionStepId, progressionSubstepId, timestamp)
			SELECT ?, ss.progressionStepId, ?, ?
			FROM ProgressionSubstep ss
			WHERE progressionSubstepId = ?
	 		ON DUPLICATE KEY UPDATE
			progressionSubstepId = VALUES(progressionSubstepId),
			timestamp = VALUES(timestamp);'; // this is for if the (appointmentId, progressionStepId) unique constraint is triggered.

		$stmt = $DB_CONN->prepare($query);
		$stmt->execute(array($appointmentId, $substepId, $time, $substepId));
	} catch (Exception $e) {
		$response['success'] = false;
		$response['error'] = "There was an error on the server saving the appointment's queue subcategory. Please refresh the page and try again";
	}

	echo json_encode($response);
}

function markAppointmentAsCompleted($appointmentId) {
	GLOBAL $DB_CONN;

	$response = array();
	$response['success'] = true;

	try {
		$time = date("Y-m-d H:i:s");

		// just leaving the timeAppointmentEnded for now, doesn't hurt.
		$query = 'UPDATE ServicedAppointment
			SET timeAppointmentEnded = ?,
				completed = TRUE
			WHERE appointmentId = ?;';

		$stmt = $DB_CONN->prepare($query);
		$stmt->execute(array($time, $appointmentId));
	} catch (Exception $e) {
		$response['success'] = false;
		$response['error'] = 'There was an error on the server marking the appointment as being prepared. Please refresh the page and try again';
	}

	echo json_encode($response);
}

function markAppointmentAsIncomplete($appointmentId) {
	GLOBAL $DB_CONN, $USER;

	$response = array();
	$response['success'] = true;

	try {
		$query = 'UPDATE ServicedAppointment
			SET completed = FALSE
			WHERE appointmentId = ?';
		$stmt = $DB_CONN->prepare($query);

		$success = $stmt->execute(array($appointmentId));
		if ($success == false) throw new Exception();

		# Insert an automatic note saying it was marked as incomplete
		$noteAccessor = new NoteAccessor();
		$noteText = 'Marked as Incomplete [Automatic Note]';
		$userId = $USER->getUserId();
		$noteAccessor->addNote($appointmentId, $noteText, $userId);
	} catch (Exception $e) {
		$response['success'] = false;
		$response['error'] = 'Unable to update the appointment. Please refresh the page and try again.';
	}

	echo json_encode($response);
}

function markAppointmentAsCancelled($appointmentId) {
	$response = array();
	$response['success'] = true;

	try {
		$appointmentAccessor = new AppointmentAccessor();
		$appointmentAccessor->cancelAppointment($appointmentId);
	} catch (Exception $e) {
		$response['success'] = false;
		$response['error'] = 'Unable to cancel the appointment. Please refresh the page and try again.';
	}

	echo json_encode($response);
}

###################
# Private Methods #
###################

function expandLanguageCode($languageCode) {
	if ($languageCode === 'eng') return 'English';
	if ($languageCode === 'vie') return 'Vietnamese';
	if ($languageCode === 'spa') return 'Spanish';
	if ($languageCode === 'ara') return 'Arabic';
	return 'Unknown';
}