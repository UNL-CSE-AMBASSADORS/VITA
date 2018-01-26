<?php

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once "$root/server/user.class.php";
$USER = new User();
if (!$USER->hasPermission('use_admin_tools')) {
	header("Location: /unauthorized");
	die();
}
$HEADER_COLUMN_NAMES = array('Scheduled Time', 'First Name', 'Last Name', 'Phone Number', 'Email Address', 'Appointment ID');
$ALL_SITES_ID = -1;

require_once "$root/server/config.php";
require_once "$root/server/libs/wrappers/PHPExcelWrapper.class.php";

getAppointmentsScheduleExcelFile($_GET);

function getAppointmentsScheduleExcelFile($data) {
	$appointments = executeAppointmentQuery($data);
	$phpExcelWrapper = createAppointmentExcelFile($appointments);

	ob_clean();
	ob_end_clean();

	$fileName = $data['date'] . '_AppointmentSchedule' . '.xlsx';
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="'. $fileName .'"');
	header('Cache-Control: max-age=0');
	header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
	header ('Cache-Control: cache, must-revalidate');
	header ('Pragma: public');

	$objWriter = $phpExcelWrapper->createExcelWriter();
	$objWriter->save('php://output');

	exit;
}

function executeAppointmentQuery($data) {
	GLOBAL $DB_CONN, $ALL_SITES_ID;

	$query = "SELECT TIME_FORMAT(scheduledTime, '%l:%i %p') AS scheduledTime, Client.firstName, Client.lastName, 
			Client.phoneNumber, emailAddress, appointmentId, AppointmentTime.siteId, Site.title
		FROM Appointment
		JOIN Client ON Appointment.clientId = Client.clientId
		JOIN AppointmentTime ON AppointmentTime.appointmentTimeId = Appointment.appointmentTimeId
		JOIN Site ON AppointmentTime.siteId = Site.siteId
		WHERE DATE(AppointmentTime.scheduledTime) = ?
			AND Appointment.archived = FALSE";
	if ($data['siteId'] != $ALL_SITES_ID) {
		$query .= ' AND AppointmentTime.siteId = ?';
	}
	$query .= ' ORDER BY AppointmentTime.siteId ASC, AppointmentTime.scheduledTime ASC';
	$stmt = $DB_CONN->prepare($query);

	$filterParams = array($data['date']);
	if ($data['siteId'] != $ALL_SITES_ID) {
		$filterParams[] = $data['siteId'];
	}
	$stmt->execute($filterParams);
	$appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

	return $appointments;
}

function createAppointmentExcelFile($appointments) {
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
				if ($key === 'siteId' || $key === 'title') continue;
				if (!$value) $row[$key] = ''; # Change any null data to just be an empty string

				$phpExcelWrapper->insertData($row[$key]);
				$phpExcelWrapper->nextColumn();
			}
			$phpExcelWrapper->nextRow();
		}
	}

	# Set the default sheet to the first one
	$phpExcelWrapper->setActiveSheetIndex(0);

	return $phpExcelWrapper;
}
