<?php

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once "$root/server/user.class.php";
$USER = new User();
if (!$USER->hasPermission('can_use_admin_tools')) {
	header("Location: /unauthorized");
	die();
}

require_once "$root/server/config.php";
require_once "$root/server/libs/wrappers/PHPExcelWrapper.class.php";

$HEADER_COLUMN_NAMES = array('First Name', 'Last Name', 'Start Time', 'End Time', 'Preparing Taxes', 'Phone Number', 'Email Address');
$ALL_SITES_ID = -1;

getVolunteerScheduleExcelFile($_GET);

function getVolunteerScheduleExcelFile($data) {
	$volunteerShifts = executeVolunteerShiftsQuery($data);
	$phpExcelWrapper = createVolunteerScheduleExcelFile($volunteerShifts);
	
	@ob_clean();
	@ob_end_clean();
	
	$fileName = $data['date'] . '_VolunteerSchedule' . '.xlsx';
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

function executeVolunteerShiftsQuery($data) {
	GLOBAL $DB_CONN, $ALL_SITES_ID;

	$query = "SELECT User.firstName, User.lastName, TIME(Shift.startTime), TIME(Shift.endTime), User.preparesTaxes, User.phoneNumber, User.email, Shift.siteId, Site.title
		FROM User
		JOIN UserShift ON User.userId = UserShift.userId
		JOIN Shift ON UserShift.shiftId = Shift.shiftId
		JOIN Site ON Shift.siteId = Site.siteId
		WHERE DATE(Shift.startTime) = ?
			AND User.archived = FALSE
			AND Shift.archived = FALSE";
	if ($data['siteId'] != $ALL_SITES_ID) {
		$query .= ' AND Shift.siteId = ?';
	}
	$query .= ' ORDER BY Shift.siteId ASC, Shift.startTime ASC';
	$stmt = $DB_CONN->prepare($query);
	
	$filterParams = array($data['date']);
	if ($data['siteId'] != $ALL_SITES_ID) {
		$filterParams[] = $data['siteId'];
	}
	$stmt->execute($filterParams);
	$volunteerShifts = $stmt->fetchAll(PDO::FETCH_ASSOC);

	return $volunteerShifts;
}

function createVolunteerScheduleExcelFile($volunteerShifts) {
	GLOBAL $HEADER_COLUMN_NAMES;
	$phpExcelWrapper = new PHPExcelWrapper();

	if (empty($volunteerShifts)) {
		$sheetIndex = $phpExcelWrapper->createSheet('None');
		$phpExcelWrapper->setActiveSheetIndex($sheetIndex);
		$phpExcelWrapper->insertHeaderRow($HEADER_COLUMN_NAMES);
		$phpExcelWrapper->nextRow();
	} else {
		# Iterate through all the results and append them to the proper sheet
		$sheetIndexForSiteId = array(); 
		foreach ($volunteerShifts as $row) {
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

			# Insert Volunteer Shift Data
			foreach ($row as $key => $value) {
				if ($key === 'siteId' || $key === 'title') continue; 
				if (!$value) $row[$key] = ''; # Change any null data to just be an empty string
				if ($key === 'preparesTaxes') {
					$row[$key] = $value == 1 ? 'Yes' : 'No';
				}
				
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