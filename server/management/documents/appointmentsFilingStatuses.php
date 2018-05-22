<?php

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once "$root/server/user.class.php";
$USER = new User();
if (!$USER->hasPermission('use_admin_tools')) {
	header("Location: /unauthorized");
	die();
}
$HEADER_COLUMN_NAMES = array('First Name', 'Last Name', 'Appointment ID', 'Appointment Completed', 'State E-File', 'Federal E-File', 'State Paper', 'Federal Paper', 'Notes');
$ALL_SITES_ID = -1;

require_once "$root/server/config.php";
require_once "$root/server/libs/wrappers/PHPExcelWrapper.class.php";
require_once "$root/server/accessors/noteAccessor.class.php";

getAppointmentsFilingStatusesExcelFile($_GET);

function getAppointmentsFilingStatusesExcelFile($data) {
	$appointments = executeAppointmentQuery($data);
	$phpExcelWrapper = createAppointmentsFilingStatusesExcelFile($appointments);

	ob_clean();
	ob_end_clean();

	$fileName = $data['date'] . '_AppointmentFilingStatuses' . '.xlsx';
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="'. $fileName .'"');
	header('Cache-Control: max-age=0');
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
	header('Cache-Control: cache, must-revalidate');
	header('Pragma: public');

	$objWriter = $phpExcelWrapper->createExcelWriter();
	$objWriter->save('php://output');

	exit;
}

function executeAppointmentQuery($data) {
	$appointments = getAppointments($data['siteId'], $data['date']);

	foreach ($appointments as &$appointment) {
		$appointment['notes'] = getNotesForAppointment($appointment['appointmentId']);
		setFilingStatuses($appointment);
	}

	return $appointments;
}

function createAppointmentsFilingStatusesExcelFile($appointments) {
	GLOBAL $HEADER_COLUMN_NAMES;
	$phpExcelWrapper = new PHPExcelWrapper();

	if (empty($appointments)) {
		$sheetIndex = $phpExcelWrapper->createSheet('None');
		$phpExcelWrapper->setActiveSheetIndex($sheetIndex);
		$phpExcelWrapper->insertHeaderRow($HEADER_COLUMN_NAMES);
		$phpExcelWrapper->nextRow();
	} else {
		# Iterate through all the results and append them to the proper sheet
		$sheetIndexForSiteId = array();
		foreach ($appointments as $row) {
			$siteId = $row['siteId'];

			# Add in the sheet for the site if it doesn't already exist
			if (!isset($sheetIndexForSiteId[$siteId])) {
				$sheetIndex = $phpExcelWrapper->createSheet($row['title']);
				$sheetIndexForSiteId[$siteId] = $sheetIndex;

				$phpExcelWrapper->setActiveSheetIndex($sheetIndex);
				$phpExcelWrapper->insertHeaderRow($HEADER_COLUMN_NAMES);
				$phpExcelWrapper->nextRow();
			}

			# Grab current sheet index
			$sheetIndex = $sheetIndexForSiteId[$siteId];
			$phpExcelWrapper->setActiveSheetIndex($sheetIndex);

			# Insert Appointment Data
			foreach ($row as $key => $value) {
				if ($key === 'siteId' || $key === 'title' || $key === 'servicedAppointmentId' || $key === 'notes') continue;
				if (!$value) $row[$key] = ''; # Change any null data to just be an empty string
				if ($key === 'stateEFile' || $key === 'federalEFile' || $key === 'statePaper' || $key === 'federalPaper') {
					$row[$key] = ($value == true ? 'Yes' : '');
				}
				if ($key === 'completed') {
					$row[$key] = ($value == true ? 'Yes' : 'No');
				}

				$phpExcelWrapper->insertData($row[$key]);
				$phpExcelWrapper->nextColumn();
			}
			# Insert the notes
			foreach ($row['notes'] as $note) {
				$noteText = $note['note'] . ' -- ' . $note['createdByFirstName'] . ' ' . $note['createdByLastName'] . ' (' . $note['createdAt'] . ')';
				$phpExcelWrapper->insertData($noteText);
				$phpExcelWrapper->nextColumn();
			}
			$phpExcelWrapper->nextRow();
		}
	}

	# Set the default sheet to the first one
	$phpExcelWrapper->setActiveSheetIndex(0);

	return $phpExcelWrapper;
}

######################
### HELPER METHODS ###
######################

function getAppointments($siteId, $date) {
	GLOBAL $DB_CONN, $ALL_SITES_ID;

	$query = 'SELECT Client.firstName, Client.lastName, Appointment.appointmentId,
		ServicedAppointment.completed, ServicedAppointment.servicedAppointmentId,
		Site.title, Site.siteId
		FROM Appointment
		LEFT JOIN ServicedAppointment ON Appointment.appointmentId = ServicedAppointment.appointmentId
		JOIN Client ON Appointment.clientId = Client.clientId
		JOIN AppointmentTime ON Appointment.appointmentTimeId = AppointmentTime.appointmentTimeId
		JOIN Site ON AppointmentTime.siteId = Site.siteId
		WHERE DATE(AppointmentTime.scheduledTime) = ?
			AND Appointment.archived = FALSE';
	if ($siteId != $ALL_SITES_ID) {
		$query .= ' AND AppointmentTime.siteId = ?';
	}
	$query .= ' ORDER BY AppointmentTime.siteId ASC, ServicedAppointment.completed DESC, AppointmentTime.scheduledTime ASC';
	$stmt = $DB_CONN->prepare($query);

	$filterParams = array($date);
	if ($siteId != $ALL_SITES_ID) {
		$filterParams[] = $siteId;
	}

	$stmt->execute($filterParams);
	$appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

	return $appointments;
}

function getNotesForAppointment($appointmentId) {
	GLOBAL $DB_CONN;

	$noteAccessor = new NoteAccessor();
	$notes = $noteAccessor->getNotesForAppointment($appointmentId);

	return $notes;
}

function setFilingStatuses(&$appointment) {
	$appointment['stateEFile'] = false;
	$appointment['federalEFile'] = false;
	$appointment['statePaper'] = false;
	$appointment['federalPaper'] = false;

	if (!isset($appointment['servicedAppointmentId'])) return;

	$filingStatuses = getFilingStatusesForAppointment($appointment['servicedAppointmentId']);
	foreach ($filingStatuses as $filingStatus) {
		if ($filingStatus['lookupName'] === 'state_efile') $appointment['stateEFile'] = true;
		else if ($filingStatus['lookupName'] === 'federal_efile') $appointment['federalEFile'] = true;
		else if ($filingStatus['lookupName'] === 'state_paper') $appointment['statePaper'] = true;
		else if ($filingStatus['lookupName'] === 'federal_paper') $appointment['federalPaper'] = true;
	}
}

function getFilingStatusesForAppointment($servicedAppointmentId) {
	GLOBAL $DB_CONN;

	$query = 'SELECT lookupName 
		FROM AppointmentFilingStatus
		JOIN FilingStatus ON AppointmentFilingStatus.filingStatusId = FilingStatus.filingStatusId
		WHERE AppointmentFilingStatus.servicedAppointmentId = ?';
	$stmt = $DB_CONN->prepare($query);

	$stmt->execute(array($servicedAppointmentId));
	$filingStatuses = $stmt->fetchAll(PDO::FETCH_ASSOC);

	return $filingStatuses;
}