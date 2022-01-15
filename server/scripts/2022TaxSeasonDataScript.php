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

insert2022Data();

function insert2022Data() {	
	$dataAlreadyInserted = true;
	if ($dataAlreadyInserted) {
		die('The data has already been inserted');
	}
	
//	insertAndersonLibraryData();
//  insertIndianCenterData();
//	insertCenterForPeopleInNeedData();
//	insertLorenEiseleyLibraryData();
//	insertBennettMartinLibraryData();
//	insertFStreetCommunityCenterData();
//	insertSoutheastCommunityCollegeData();
//	insertNebraskaUnionData();
//	insertGoodNeighborCenterData();
//	insertVeteranAdministrationData();
//  insertLincolnRegionalCenterData();
//  insertCretePublicLibraryData();
//	insertInternationalStudentScholarSiteData();
//  insertVirtualVITAData();

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

		// Feb 2-Apr 6, Wednesdays 5-8
		$wednesdayDates = getWeeklyDatesFromRange('2022-02-02', '2022-04-06');
		foreach ($wednesdayDates as $date) {

			// 1 is Residential
			$firstAppointmentTimeId = insertAppointmentTime("$date 17:00:00", 5, 1, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 18:00:00", 5, 1, $siteId);
			$thirdAppointmentTimeId = insertAppointmentTime("$date 19:00:00", 5, 1, $siteId);
		}

		$DB_CONN->commit();
	} catch (Exception $e) {
		$DB_CONN->rollback();
		throw new Exception('Failed inserting Anderson Library data', MY_EXCEPTION);
		die();
	}
}

function insertIndianCenterData() {
	GLOBAL $DB_CONN;
	
	$dataAlreadyInserted = true;
	if ($dataAlreadyInserted) {
		die('The Indian Center data has already been inserted');
	}

	$siteId = insertSite("Indian Center", "1100 Military Rd, Lincoln, NE 68508", "");

	try {
		$DB_CONN->beginTransaction();

		// Jan 31-Apr 11, Mondays 4:30-7:30
		$wednesdayDates = getWeeklyDatesFromRange('2022-01-31', '2022-04-11');
		foreach ($wednesdayDates as $date) {

			$firstAppointmentTimeId = insertAppointmentTime("$date 16:30:00", 5, 1, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 17:30:00", 5, 1, $siteId);
			$thirdAppointmentTimeId = insertAppointmentTime("$date 18:30:00", 5, 1, $siteId);
		}

		$DB_CONN->commit();
	} catch (Exception $e) {
		$DB_CONN->rollback();
		throw new Exception('Failed inserting Indian Center data', MY_EXCEPTION);
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

		// Feb 2-Mar 30, Wednesdays 11-1
		$wednesdayDates = getWeeklyDatesFromRange('2022-02-02', '2022-03-30');

		foreach ($wednesdayDates as $date) {

			$firstAppointmentTimeId = insertAppointmentTime("$date 11:00:00", 2, 1, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 12:00:00", 2, 1, $siteId);
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

		// Feb 3-Apr 7, Thursdays 5:30-7
		$thursdayDates = getWeeklyDatesFromRange('2022-02-03', '2022-04-07');
		foreach ($thursdayDates as $date) {

			$firstAppointmentTimeId = insertAppointmentTime("$date 17:30:00", 4, 1, $siteId);
			$thirdAppointmentTimeId = insertAppointmentTime("$date 18:00:00", 4, 1, $siteId);
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

		// Feb 6-Mar 27, Sundays 1-4
		$sundayDates = getWeeklyDatesFromRange('2022-02-06', '2022-03-27');
		foreach ($sundayDates as $date) {

			$firstAppointmentTimeId = insertAppointmentTime("$date 13:00:00", 4, 1, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 14:00:00", 4, 1, $siteId);
			$thirdAppointmentTimeId = insertAppointmentTime("$date 15:00:00", 4, 1, $siteId);
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

		// Jan 25-Apr 11, Tuesdays 430-630
		$tuesdayDates = getWeeklyDatesFromRange('2022-01-25', '2022-04-12');
		foreach ($tuesdayDates as $date) {

			$firstAppointmentTimeId = insertAppointmentTime("$date 16:30:00", 4, 1, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 17:30:00", 4, 1, $siteId);
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

		// Feb 2-Apr 6, closed March 16th, Wednesdays 5-7
		$wednesdayDates = array('2022-02-02', '2022-02-09', '2022-02-16', '2022-02-23', '2022-03-02', '2022-03-09', '2022-03-23', '2022-03-30', '2022-04-06');
		foreach ($wednesdayDates as $date) {

			$firstAppointmentTimeId = insertAppointmentTime("$date 17:00:00", 4, 1, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 18:00:00", 4, 1, $siteId);
		}

		// Jan 31-Apr 11, closed feb7, mar7, apr4; Mondays 5-7
		$mondayDates = array('2022-01-31', '2022-02-14', '2022-02-21', '2022-02-28', '2022-03-14', '2022-03-21', '2022-03-28', '2022-04-11');

		foreach ($mondayDates as $date) {

			$firstAppointmentTimeId = insertAppointmentTime("$date 17:00:00", 4, 1, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 18:00:00", 4, 1, $siteId);
		}

		$DB_CONN->commit();
	} catch (Exception $e) {
		$DB_CONN->rollback();
		throw new Exception('Failed inserting Southeast Community College data', MY_EXCEPTION);
		die();
	}
}

function insertNebraskaEastUnionData() {
	GLOBAL $DB_CONN;
	
	$dataAlreadyInserted = true;
	if ($dataAlreadyInserted) {
		die('The Nebraska East Union data has already been inserted');
	}

	$siteId = 1; // Manually obtained from PROD DB

	try {
		$DB_CONN->beginTransaction();

		// Jan 22-Mar 5, Saturdays
		$saturdayDates = getWeeklyDatesFromRange('2022-01-22', '2022-03-05');
		foreach ($saturdayDates as $date) {

			$firstAppointmentTimeId = insertAppointmentTime("$date 10:00:00", 10, 1, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 11:00:00", 10, 1, $siteId);
			$thirdAppointmentTimeId = insertAppointmentTime("$date 12:00:00", 10, 1, $siteId);
			$fourthAppointmentTimeId = insertAppointmentTime("$date 13:00:00", 10, 1, $siteId);
			$fifthAppointmentTimeId = insertAppointmentTime("$date 14:00:00", 5, 1, $siteId);
		}

		$DB_CONN->commit();
	} catch (Exception $e) {
		$DB_CONN->rollback();
		throw new Exception('Failed inserting Nebraska Union data', MY_EXCEPTION);
		die();
	}
}

function insertGoodNeighborCenterData() {
	GLOBAL $DB_CONN;
	
	$dataAlreadyInserted = true;
	if ($dataAlreadyInserted) {
		die('The Good Neighbor Center data has already been inserted');
	}

	$siteId = insertSite("Good Neighbor Center", "617 Y St", "");

	try {
		$DB_CONN->beginTransaction();

		// Jan 30-Feb 20, Sundays 1-5
		$sundayDates = getWeeklyDatesFromRange('2022-01-30', '2022-02-20');
		foreach ($sundayDates as $date) {

			$firstAppointmentTimeId = insertAppointmentTime("$date 13:00:00", 5, 1, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 14:00:00", 5, 1, $siteId);
			$thirdAppointmentTimeId = insertAppointmentTime("$date 15:00:00", 5, 1, $siteId);
			$fourthAppointmentTimeId = insertAppointmentTime("$date 16:00:00", 5, 1, $siteId);
		}

		$DB_CONN->commit();
	} catch (Exception $e) {
		$DB_CONN->rollback();
		throw new Exception('Failed inserting Good Neighbor Center data', MY_EXCEPTION);
		die();
	}
}

function insertVeteranAdministrationData() {
	GLOBAL $DB_CONN;
	
	$dataAlreadyInserted = true;
	if ($dataAlreadyInserted) {
		die('The Veteran Administration data has already been inserted');
	}

	$siteId = insertSite("Veterans Affairs", "", "");

	try {
		$DB_CONN->beginTransaction();

		$fridayDates = array('2022-02-04', '2022-02-11', '2022-02-25', '2022-03-04', '2022-03-25', '2022-04-01');
		foreach ($fridayDates as $date) {

            // 0 because these are "hidden" appointments
			$firstAppointmentTimeId = insertAppointmentTime("$date 10:00:00", 0, 1, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 11:00:00", 0, 1, $siteId);
			$thirdAppointmentTimeId = insertAppointmentTime("$date 12:00:00", 0, 1, $siteId);
			$fourthAppointmentTimeId = insertAppointmentTime("$date 13:00:00", 0, 1, $siteId);
		}

		$DB_CONN->commit();
	} catch (Exception $e) {
		$DB_CONN->rollback();
		throw new Exception('Failed inserting first VA data', MY_EXCEPTION);
		die();
	}

    try {
		$DB_CONN->beginTransaction();

        // Apr 1, Friday 10-12
		$fridayDate = array('2022-04-01');
		foreach ($fridayDate as $date) {

            // 0 because these are "hidden" appointments
			$firstAppointmentTimeId = insertAppointmentTime("$date 10:00:00", 0, 1, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 11:00:00", 0, 1, $siteId);
		}

		$DB_CONN->commit();
	} catch (Exception $e) {
		$DB_CONN->rollback();
		throw new Exception('Failed inserting second VA data', MY_EXCEPTION);
		die();
	}
}


function insertLincolnRegionalCenterData() {
	GLOBAL $DB_CONN;
	
	$dataAlreadyInserted = true;
	if ($dataAlreadyInserted) {
		die('The Lincoln Regional Center data has already been inserted');
	}

	$siteId = insertSite("Lincoln Regional Center", "", "");

	try {
		$DB_CONN->beginTransaction();

        // 10/week for 5 weeks starting january 31 (Mondays)
		$mondayDates = getWeeklyDatesFromRange('2022-01-31', '2022-02-28');
		foreach ($mondayDates as $date) {

            // 0 because these are "hidden" appointments
			$firstAppointmentTimeId = insertAppointmentTime("$date 12:00:00", 0, 6, $siteId);
		}

		$DB_CONN->commit();
	} catch (Exception $e) {
		$DB_CONN->rollback();
		throw new Exception('Failed inserting Lincoln Regional Center data', MY_EXCEPTION);
		die();
	}
}

function insertCretePublicLibraryData() {
	GLOBAL $DB_CONN;
	
	$dataAlreadyInserted = true;
	if ($dataAlreadyInserted) {
		die('The Crete Public Library data has already been inserted');
	}

	$siteId = insertSite("Crete Public Library", "1515 Forest Ave, Crete, NE 68333", "");

	try {
		$DB_CONN->beginTransaction();

        // Feb 1-Mar 31, 10-5
        // Hidden and drop-off, so they are looking to take 10/week feb1-mar 29
        // These will likely have to be manually created upon reception of drop-offs (all hidden will be manually created)
		$fridayDates = getWeeklyDatesFromRange('2022-02-01', '2022-03-29'); // weekly on Tuesdays at noon
		foreach ($fridayDates as $date) {

            // 0 because these are "hidden" appointments
			$firstAppointmentTimeId = insertAppointmentTime("$date 12:00:00", 0, 1, $siteId);
		}

		$DB_CONN->commit();
	} catch (Exception $e) {
		$DB_CONN->rollback();
		throw new Exception('Failed inserting Crete Public Library data', MY_EXCEPTION);
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

		// March 1-Apr 5, 1-5
		$tuesdayDates = getWeeklyDatesFromRange('2022-03-01', '2022-04-05');
		foreach ($tuesdayDates as $date) {

			// Virtual China
			$firstAppointmentTimeId = insertAppointmentTime("$date 13:00:00", 10, 7, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 14:00:00", 10, 7, $siteId);
			$thirdAppointmentTimeId = insertAppointmentTime("$date 15:00:00", 10, 7, $siteId);
			$fourthAppointmentTimeId = insertAppointmentTime("$date 16:00:00", 5, 7, $siteId);

			// Virtual India
			$firstAppointmentTimeId = insertAppointmentTime("$date 13:00:00", 5, 8, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 14:00:00", 5, 8, $siteId);
			$thirdAppointmentTimeId = insertAppointmentTime("$date 15:00:00", 5, 8, $siteId);
			$fourthAppointmentTimeId = insertAppointmentTime("$date 16:00:00", 2, 8, $siteId);			
		
			// Virtual Treaty
			$firstAppointmentTimeId = insertAppointmentTime("$date 13:00:00", 5, 9, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 14:00:00", 5, 9, $siteId);
			$thirdAppointmentTimeId = insertAppointmentTime("$date 15:00:00", 5, 9, $siteId);
			$fourthAppointmentTimeId = insertAppointmentTime("$date 16:00:00", 2, 9, $siteId);			
		
			// Virtual Non-Treaty
			$firstAppointmentTimeId = insertAppointmentTime("$date 13:00:00", 10, 10, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 14:00:00", 10, 10, $siteId);
			$thirdAppointmentTimeId = insertAppointmentTime("$date 15:00:00", 10, 10, $siteId);
			$fourthAppointmentTimeId = insertAppointmentTime("$date 16:00:00", 5, 10, $siteId);
		}

		$DB_CONN->commit();
	} catch (Exception $e) {
		$DB_CONN->rollback();
		throw new Exception('Failed inserting International Student Scholar data', MY_EXCEPTION);
		die();
	}
}

function insertVirtualVITAData() {
	GLOBAL $DB_CONN;
	
	$dataAlreadyInserted = true;
	if ($dataAlreadyInserted) {
		die('The Virtual VITA data has already been inserted');
	}

	$siteId = 12;

	try {
		$DB_CONN->beginTransaction();

        // Jan 31-Apr 4, 10/week
		$fridayDates = getWeeklyDatesFromRange('2022-01-31', '2022-04-04');
		foreach ($fridayDates as $date) {

			$firstAppointmentTimeId = insertAppointmentTime("$date 12:00:00", 10, 6, $siteId);
		}

		$DB_CONN->commit();
	} catch (Exception $e) {
		$DB_CONN->rollback();
		throw new Exception('Failed inserting Virtual VITA data', MY_EXCEPTION);
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