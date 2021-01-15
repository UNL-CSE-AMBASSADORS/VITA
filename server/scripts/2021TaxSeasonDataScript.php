<?php
// This script will only work for Joey Carrigan.
// To run it locally, make sure userId and siteIds exist here and in helper functions

$root = realpath($_SERVER['DOCUMENT_ROOT']);
require_once "$root/server/user.class.php";
$USER = new User();
if (!$USER->isLoggedIn() || $USER->getUserId() !== '358') {
	header("Location: /unauthorized");
	die();
}

// TODO: Need to update the `$dataAlreadyInserted` values before this query will run successfully

insert2021Data();

function insert2021Data() {	
	$dataAlreadyInserted = true;
	if ($dataAlreadyInserted) {
		die('The data has already been inserted');
	}
//	insertAndersonLibraryData();
//	insertCenterForPeopleInNeedData();
//	insertLorenEiseleyLibraryData();
//	insertBennettMartinLibraryData();
//	insertFStreetCommunityCenterData();
//	insertSoutheastCommunityCollegeData();
//	insertAsianCommunityAndCulturalCenterData();
//	insertNebraskaUnionData();
//	insertInternationalStudentScholarSiteData();
	
	die('SUCCESS');
}

function insertAndersonLibraryData() {
	GLOBAL $DB_CONN;
	
	$dataAlreadyInserted = true;
	if ($dataAlreadyInserted) {
		die('The Anderson Library data has already been inserted');
	}

	$siteId = 2; // Manually obtained from PROD DB

	try {
		$DB_CONN->beginTransaction();

		// Wednesdays 5-8
		$wednesdayDates = getWeeklyDatesFromRange('2021-02-03', '2021-04-07');
		foreach ($wednesdayDates as $date) {

			// Are from Katie's parameter sheet
			// 6 is Virtual Residential
			$firstAppointmentTimeId = insertAppointmentTime("$date 17:00:00", 7.5, 6, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 18:00:00", 7.5, 6, $siteId);
			$thirdAppointmentTimeId = insertAppointmentTime("$date 19:00:00", 7.5, 6, $siteId);
		}
		
		// Thursdays 5-8
		$thursdayDates = getWeeklyDatesFromRange('2021-02-04', '2021-04-08');
		foreach ($thursdayDates as $date) {

			$firstAppointmentTimeId = insertAppointmentTime("$date 17:00:00", 7.5, 6, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 18:00:00", 7.5, 6, $siteId);
			$thirdAppointmentTimeId = insertAppointmentTime("$date 19:00:00", 7.5, 6, $siteId);
		}

		$DB_CONN->commit();
	} catch (Exception $e) {
		$DB_CONN->rollback();
		throw new Exception('Failed inserting Anderson Library data', MY_EXCEPTION);
		die();
	}
}

function insertCenterForPeopleInNeedData() {
	GLOBAL $DB_CONN;
	
	$dataAlreadyInserted = true;
	if ($dataAlreadyInserted) {
		die('The Center for People in Need data has already been inserted');
	}

	try {
		$DB_CONN->beginTransaction();

		$siteId = 5; // Manually obtained from PROD DB

		// Tuesdays 11-2
		$tuesdayDates = getWeeklyDatesFromRange('2021-02-02', '2021-03-23');

		foreach ($tuesdayDates as $date) {

			$firstAppointmentTimeId = insertAppointmentTime("$date 11:00:00", 6, 6, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 12:00:00", 6, 6, $siteId);
			$thirdAppointmentTimeId = insertAppointmentTime("$date 13:00:00", 6, 6, $siteId);
		}
		$DB_CONN->commit();
	} catch (Exception $e) {
		$DB_CONN->rollback();
		throw new Exception('Failed inserting Center for People in Need data', MY_EXCEPTION);
		die();
	}
}

function insertLorenEiseleyLibraryData() {
	GLOBAL $DB_CONN;

	$dataAlreadyInserted = true;
	if ($dataAlreadyInserted) {
		die('The Loren Eiseley data has already been inserted');
	}

	try {
		$DB_CONN->beginTransaction();

		$siteId = 6; // Manually obtained from PROD DB

		// Thursdays 4-7
		$thursdayDates = getWeeklyDatesFromRange('2021-02-18', '2021-04-08');
		foreach ($thursdayDates as $date) {

			$firstAppointmentTimeId = insertAppointmentTime("$date 16:00:00", 6, 6, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 17:00:00", 6, 6, $siteId);
			$thirdAppointmentTimeId = insertAppointmentTime("$date 18:00:00", 6, 6, $siteId);
		}

		// Saturdays 1-4
		$saturdayDates = getWeeklyDatesFromRange('2021-02-20', '2021-04-03');
		foreach ($saturdayDates as $date) {
		
			$firstAppointmentTimeId = insertAppointmentTime("$date 13:00:00", 6, 6, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 14:00:00", 6, 6, $siteId);
			$thirdAppointmentTimeId = insertAppointmentTime("$date 15:00:00", 6, 6, $siteId);
		}

		$DB_CONN->commit();
	} catch (Exception $e) {
		$DB_CONN->rollback();
		throw new Exception('Failed inserting Loren Eisely data', MY_EXCEPTION);
		die();
	}
}

function insertBennettMartinLibraryData() {
	GLOBAL $DB_CONN;

	$dataAlreadyInserted = true;
	if ($dataAlreadyInserted) {
		die('The Bennett Martin data has already been inserted');
	}

	try {
		$DB_CONN->beginTransaction();

		$siteId = 7; // Manually obtained from PROD DB

		// Sundays 1-4
		$sundayDates = getWeeklyDatesFromRange('2021-02-07', '2021-03-14');
		foreach ($sundayDates as $date) {

			$firstAppointmentTimeId = insertAppointmentTime("$date 13:00:00", 6, 6, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 14:00:00", 6, 6, $siteId);
			$thirdAppointmentTimeId = insertAppointmentTime("$date 15:00:00", 6, 6, $siteId);
		}

		$DB_CONN->commit();
	} catch (Exception $e) {
		$DB_CONN->rollback();
		throw new Exception('Failed inserting Bennett Martin Library data', MY_EXCEPTION);
		die();
	}
}

function insertFStreetCommunityCenterData() {
	GLOBAL $DB_CONN;
	
	$dataAlreadyInserted = true;
	if ($dataAlreadyInserted) {
		die('The F Street Community Center data has already been inserted');
	}
	
	try {
		$DB_CONN->beginTransaction();

		$siteId = 8; // Manually obtained from PROD DB

		// Tuesdays Feb 2 - Apr 6, 5-7
		$tuesdayDates = getWeeklyDatesFromRange('2021-02-02', '2021-04-06');
		foreach ($tuesdayDates as $date) {

			$firstAppointmentTimeId = insertAppointmentTime("$date 17:00:00", 6, 6, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 18:00:00", 6, 6, $siteId);
		}

		$DB_CONN->commit();
	} catch (Exception $e) {
		$DB_CONN->rollback();
		throw new Exception('Failed inserting F Street Community Center data', MY_EXCEPTION);
		die();
	}
}

function insertSoutheastCommunityCollegeData() {	
	GLOBAL $DB_CONN;

	$dataAlreadyInserted = true;
	if ($dataAlreadyInserted) {
		die('The Southeast Community College data has already been inserted');
	}
 	
	try {
		$DB_CONN->beginTransaction();

		$siteId = 10; // Manually obtained from PROD DB

		// Mondays, Feb 1 - Mar 1. Skipping President's Day on February 15th, for now
		$mondayDates = array('2021-02-01', '2021-02-08',' 2021-02-22', '2021-03-01');
		foreach ($mondayDates as $date) {

			$firstAppointmentTimeId = insertAppointmentTime("$date 12:00:00", 4.5, 6, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 13:00:00", 4.5, 6, $siteId);
		}

		// Thursdays, Feb 4 - mar 25 or apr 8
		$thursdayDates = getWeeklyDatesFromRange('2021-02-04', '2021-04-08');
		foreach ($thursdayDates as $date) {

			$firstAppointmentTimeId = insertAppointmentTime("$date 15:00:00", 4.5, 6, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 16:00:00", 4.5, 6, $siteId);
			$thirdAppointmentTimeId = insertAppointmentTime("$date 17:00:00", 4.5, 6, $siteId);
		}

		$DB_CONN->commit();
	} catch (Exception $e) {
		$DB_CONN->rollback();
		throw new Exception('Failed inserting Southeast Community College data', MY_EXCEPTION);
		die();
	}
}

function insertNebraskaUnionData() {
	GLOBAL $DB_CONN;
	
	$dataAlreadyInserted = true;
	if ($dataAlreadyInserted) {
		die('The Nebraska Union data has already been inserted');
	}

	$siteId = 11; // Manually obtained from PROD DB

	try {
		$DB_CONN->beginTransaction();

		// Saturdays
		$saturdayDates = getWeeklyDatesFromRange('2021-01-30', '2021-03-06');
		foreach ($saturdayDates as $date) {

			$firstAppointmentTimeId = insertAppointmentTime("$date 10:00:00", 7.5, 6, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 11:00:00", 7.5, 6, $siteId);
			$thirdAppointmentTimeId = insertAppointmentTime("$date 12:00:00", 7.5, 6, $siteId);
			$fourthAppointmentTimeId = insertAppointmentTime("$date 13:00:00", 7.5, 6, $siteId);
			$fifthAppointmentTimeId = insertAppointmentTime("$date 14:00:00", 7.5, 6, $siteId);
			$sixthAppointmentTimeId = insertAppointmentTime("$date 15:00:00", 7.5, 6, $siteId);
		}

		$DB_CONN->commit();
	} catch (Exception $e) {
		$DB_CONN->rollback();
		throw new Exception('Failed inserting Nebraska Union data', MY_EXCEPTION);
		die();
	}
}

function insertAsianCommunityAndCulturalCenterData() {
	GLOBAL $DB_CONN;
	
	$dataAlreadyInserted = true;
	if ($dataAlreadyInserted) {
		die('The Asian Community and Cultural Center data has already been inserted');
	}

	$siteId = insertSite("Asian Community And Cultural Center", "", "");

	try {
		$DB_CONN->beginTransaction();

		// Wednesdays 11-2
		$wednesdayDates = getWeeklyDatesFromRange('2021-02-03', '2021-04-07');
		foreach ($wednesdayDates as $date) {

			$firstAppointmentTimeId = insertAppointmentTime("$date 11:00:00", 6, 6, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 12:00:00", 6, 6, $siteId);
			$thirdAppointmentTimeId = insertAppointmentTime("$date 13:00:00", 6, 6, $siteId);
		}
		
		// Thursdays 11-2
		$thursdayDates = getWeeklyDatesFromRange('2021-02-04', '2021-04-08');
		foreach ($thursdayDates as $date) {

			$firstAppointmentTimeId = insertAppointmentTime("$date 11:00:00", 6, 6, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 12:00:00", 6, 6, $siteId);
			$thirdAppointmentTimeId = insertAppointmentTime("$date 13:00:00", 6, 6, $siteId);
		}

		$DB_CONN->commit();
	} catch (Exception $e) {
		$DB_CONN->rollback();
		throw new Exception('Failed inserting Asian Community and Cultural Center data', MY_EXCEPTION);
		die();
	}
}

function insertInternationalStudentScholarSiteData() {
	GLOBAL $DB_CONN;

	$dataAlreadyInserted = true;
	if ($dataAlreadyInserted) {
		die('The International Student Scholar data has already been inserted');
	}

	$siteId = 4; // Manually obtained from PROD DB

	try {
		$DB_CONN->beginTransaction();

		// Tuesdays 12-5
		$tuesdayDates = getWeeklyDatesFromRange('2021-03-02', '2021-04-06');
		foreach ($tuesdayDates as $date) {

			// Virtual China
			$firstAppointmentTimeId = insertAppointmentTime("$date 13:00:00", 10, 7, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 14:00:00", 10, 7, $siteId);
			$thirdAppointmentTimeId = insertAppointmentTime("$date 15:00:00", 10, 7, $siteId);
			$fourthAppointmentTimeId = insertAppointmentTime("$date 16:00:00", 10, 7, $siteId);

			// Virtual India
			$firstAppointmentTimeId = insertAppointmentTime("$date 13:00:00", 5, 8, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 14:00:00", 5, 8, $siteId);
			$thirdAppointmentTimeId = insertAppointmentTime("$date 15:00:00", 5, 8, $siteId);
			$fourthAppointmentTimeId = insertAppointmentTime("$date 16:00:00", 5, 8, $siteId);			
		
			// Virtual Treaty
			$firstAppointmentTimeId = insertAppointmentTime("$date 13:00:00", 5, 9, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 14:00:00", 5, 9, $siteId);
			$thirdAppointmentTimeId = insertAppointmentTime("$date 15:00:00", 5, 9, $siteId);
			$fourthAppointmentTimeId = insertAppointmentTime("$date 16:00:00", 5, 9, $siteId);			
		
			// Virtual Non-Treaty
			$firstAppointmentTimeId = insertAppointmentTime("$date 13:00:00", 10, 10, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 14:00:00", 10, 10, $siteId);
			$thirdAppointmentTimeId = insertAppointmentTime("$date 15:00:00", 10, 10, $siteId);
			$fourthAppointmentTimeId = insertAppointmentTime("$date 16:00:00", 10, 10, $siteId);
		}

		$DB_CONN->commit();
	} catch (Exception $e) {
		$DB_CONN->rollback();
		throw new Exception('Failed inserting International Student Scholar data', MY_EXCEPTION);
		die();
	}
}

/*
 * Helper Methods
 */ 
function insertSite($title, $address, $phoneNumber) {
	GLOBAL $DB_CONN;

	$query = 'INSERT INTO Site (title, address, phoneNumber, createdBy, lastModifiedBy)
		VALUES (?, ?, ?, 358, 358);';
	$params = array($title, $address, $phoneNumber);

	$stmt = $DB_CONN->prepare($query);
	if (!$stmt->execute($params)) {
		throw new Exception("Unable to insert site", MY_EXCEPTION);
	}

	return $DB_CONN->lastInsertId();
}

function insertAppointmentTime($scheduledTime, $numberOfAppointments, $appointmentTypeId, $siteId) {
	GLOBAL $DB_CONN;

	$query = 'INSERT INTO AppointmentTime (scheduledTime, numberOfAppointments, appointmentTypeId, siteId)
		VALUES (?, ?, ?, ?)';
	$params = array($scheduledTime, $numberOfAppointments, $appointmentTypeId, $siteId);

	$stmt = $DB_CONN->prepare($query);
	if (!$stmt->execute($params)) {
		throw new Exception("Unable to insert appointment time", MY_EXCEPTION);
	}

	return $DB_CONN->lastInsertId();
}

// Gets dates of the same days of the week as $startDateInclusive, ends on
// the first occurrence of that day of the week on or after $endDateInclusive
// taken from https://www.geeksforgeeks.org/return-all-dates-between-two-dates-in-an-array-in-php/
function getWeeklyDatesFromRange($startDateInclusive, $endDateInclusive, $dateFormat = 'Y-m-d') { 
	
	$array = array(); 
	
	// Variable that store the date interval of period 1 week 
	$interval = new DateInterval('P1W'); 

	// ensures $endDateInclusive is actually included
	$realEnd = new DateTime($endDateInclusive); 
	$realEnd->add($interval); 

	$period = new DatePeriod(new DateTime($startDateInclusive), $interval, $realEnd); 

	// Use loop to store date into array 
	foreach($period as $date) {				 
		$array[] = $date->format($dateFormat); 
	} 

	return $array; 
}