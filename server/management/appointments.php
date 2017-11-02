<?php

const headerColumnNames = array('Scheduled Time', 'First Name', 'Last Name', 'Phone Number', 'Email Address', 'Appointment ID');
const allSitesId = -1;

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once "$root/server/config.php";
require_once "$root/server/libs/PHPExcel/Classes/PHPExcel.php";

getAppointmentsScheduleExcelFile($_GET);

function getAppointmentsScheduleExcelFile($data) {
	$appointments = executeAppointmentQuery($data);
	$objPHPExcel = createAppointmentExcelFile($appointments);
	
	@ob_clean();
	@ob_end_clean();
	
	$fileName = $data['date'] . '_AppointmentSchedule' . '.xlsx';
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="'. $fileName .'"');
	header('Cache-Control: max-age=0');
	header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); 
	header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); 
	header ('Cache-Control: cache, must-revalidate'); 
	header ('Pragma: public');

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save('php://output');

	exit;
}

function executeAppointmentQuery($data) {
	GLOBAL $DB_CONN;
	
	$query = "SELECT TIME(scheduledTime), firstName, lastName, Client.phoneNumber, emailAddress, appointmentId, Appointment.siteId, Site.title
		FROM Appointment
		JOIN Client ON Appointment.clientId = Client.clientId
		JOIN Site ON Appointment.siteId = Site.siteId
		WHERE DATE(Appointment.scheduledTime) = ?
			AND Appointment.archived = FALSE";
	if ($data['siteId'] != allSitesId) {
		$query .= ' AND Appointment.siteId = ?';
	} 
	$query .= ' ORDER BY Appointment.siteId ASC, Appointment.scheduledTime ASC';
	$stmt = $DB_CONN->prepare($query);
	
	$filterParams = array($data['date']);
	if ($data['siteId'] != allSitesId) {
		$filterParams[] = $data['siteId'];
	}
	$stmt->execute($filterParams);
	$appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

	return $appointments;
}

function createAppointmentExcelFile($appointments) {
	# Initiate PHPExcel
	$objPHPExcel = new PHPExcel();
	# Remove the default sheet
	$objPHPExcel->setActiveSheetIndexByName('Worksheet'); 
	$objPHPExcel->removeSheetByIndex($objPHPExcel->getActiveSheetIndex());

	if (empty($appointments)) {
		$activeSheet = createSheet($objPHPExcel, 0, 'None');
		insertHeaderRow($activeSheet);
	} else {
		# Iterate through all the results and append them to the proper sheet
		$sheetNumber = 0;
		$sheetIndexForSiteId = array(); 
		$rowNumberForSiteId = array(); 
		foreach ($appointments as $row) {
			$siteId = $row['siteId'];

			# Add in the sheet for the site if it doesn't already exist
			if (!isset($sheetIndexForSiteId[$siteId])) {
				$sheetIndexForSiteId[$siteId] = $sheetNumber;
				$sheetNumber++;

				$activeSheet = createSheet($objPHPExcel, $sheetIndexForSiteId[$siteId], $row['title']);
				insertHeaderRow($activeSheet);

				$rowNumberForSiteId[$siteId] = 2;
			}
			
			$objPHPExcel->setActiveSheetIndex($sheetIndexForSiteId[$siteId]);
			$activeSheet = $objPHPExcel->getActiveSheet();

			# Insert Appointment Data
			$columnCharacter = 'A';
			foreach ($row as $key => $value) {
				if ($key === 'siteId' || $key === 'title') continue; 
				if (!$value) $row[$key] = ''; # Change any null data to just be an empty string
				
				$rowNumber = $rowNumberForSiteId[$siteId];
				$activeSheet->setCellValue("$columnCharacter$rowNumber", $row[$key]);
				$columnCharacter++;
			}
			$rowNumberForSiteId[$siteId]++;
		}
	}

	# Set the default sheet to the first one
	$objPHPExcel->setActiveSheetIndex(0);
	
	return $objPHPExcel;
}

function insertHeaderRow($activeSheet) {
	$columnCharacter = 'A'; # Excel columns start at A
	$rowNumber = 1;
	foreach (headerColumnNames as $headerName) {
		$activeSheet->setCellValue("$columnCharacter$rowNumber", $headerName);
		$activeSheet->getColumnDimension("$columnCharacter")->setAutoSize(true);
		$columnCharacter++;
	}
}

function createSheet($objPHPExcel, $sheetIndex, $title) {
	$objPHPExcel->createSheet();
	$objPHPExcel->setActiveSheetIndex($sheetIndex);
	$activeSheet = $objPHPExcel->getActiveSheet();
	$activeSheet->setTitle($title);
	return $activeSheet;
}