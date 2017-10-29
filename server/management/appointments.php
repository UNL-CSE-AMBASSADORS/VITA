<?php

const headerColumnNames = array('Scheduled Time', 'First Name', 'Last Name', 'Phone Number', 'Email Address', 'Appointment ID');
const allSitesId = -1;

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once "$root/server/config.php";
require_once "$root/server/libs/PHPExcel/Classes/PHPExcel.php";

getAppointmentsScheduleExcelFile($_GET);

function getAppointmentsScheduleExcelFile($data) {
	$stmt = executeAppointmentQuery($data);
	$objPHPExcel = createAppointmentExcelFile($stmt);
	
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

	return $stmt;
}

function createAppointmentExcelFile($stmt) {
	# Initiate PHPExcel
	$objPHPExcel = new PHPExcel();
	# Remove the default sheet
	$objPHPExcel->setActiveSheetIndexByName('Worksheet'); 
	$objPHPExcel->removeSheetByIndex($objPHPExcel->getActiveSheetIndex());

	# Iterate through all the results and append them to the proper sheet
	$sheetRowNumber = array(); # This will keep track of the sheet index and the next row number for it
	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		# Add in the sheet for the site if it doesn't already exist
		if (!isset($sheetRowNumber[$row['siteId']])) {
			# Create worksheet
			$objPHPExcel->createSheet();
			$objPHPExcel->setActiveSheetIndex($row['siteId'] - 1);
			$activeSheet = $objPHPExcel->getActiveSheet();
			$activeSheet->setTitle($row['title']);

			# Insert header row
			$columnCharacter = 'A'; # Excel columns start at A
			$rowNumber = 1;
			foreach (headerColumnNames as $headerName) {
				$activeSheet->setCellValue("$columnCharacter$rowNumber", $headerName);
				$activeSheet->getColumnDimension("$columnCharacter")->setAutoSize(true);
				$columnCharacter++;
			}
			$sheetRowNumber[$row['siteId']] = 2;
		}
		
		$objPHPExcel->setActiveSheetIndex($row['siteId'] - 1);
		$activeSheet = $objPHPExcel->getActiveSheet();

		# Insert Appointment Data
		$columnCharacter = 'A';
		foreach ($row as $key => $value) {
			if ($key === 'siteId' || $key === 'title') continue; 
			if (!$value) $row[$key] = ''; # Change any null data to just be an empty string
			
			$rowNumber = $sheetRowNumber[$row['siteId']];
			$activeSheet->setCellValue("$columnCharacter$rowNumber", $row[$key]);
			$columnCharacter++;
		}
		$sheetRowNumber[$row['siteId']]++;
	}

	# Set the default sheet to the first one
	$objPHPExcel->setActiveSheetIndex(0);
	
	return $objPHPExcel;
}