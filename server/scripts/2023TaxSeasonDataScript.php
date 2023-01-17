<?php
// This script will only work for BEN
// To run it locally, make sure userId and siteIds exist here and in helper functions

$root = realpath($_SERVER['DOCUMENT_ROOT']);
require_once "$root/server/user.class.php";
$USER = new User();
if (!$USER->isLoggedIn() || $USER->getUserId() !== '439') {
	header("Location: /unauthorized");
	die();
}

insert2023Data();

function insert2023Data() {	
	insertAndersonLibraryData();
	insertNativeAmericanVITAData();
	insertMuslimCommunityVITAData();
	insertCenterForPeopleInNeedData();
	insertAmericanJobCenterData();
	insertAsianCulturalCommunityCenterData();
	insertLorenEiseleyLibraryData();
	insertBennettMartinLibraryData();
	insertFStreetCommunityCenterData();
	insertSoutheastCommunityCollegeData();
	insertNebraskaUnionData();
	insertGoodNeighborCenterData();
	insertVeteranAdministrationData();
	insertInternationalStudentScholarSiteData();
	insertVirtualVITAData();

	die('SUCCESS');
}

function insertAndersonLibraryData() {
	GLOBAL $DB_CONN;

	$siteId = 20;

	try {
		$DB_CONN->beginTransaction();

		// Wednesdays (Feb 1-Apr 12)
		$wednesdayDates = getWeeklyDatesFromRange('2023-02-01', '2023-04-12');
		foreach ($wednesdayDates as $date) {

			//4:30pm-5:30pm
			//5:30pm-6:30pm
			//6:30pm-7:30pm
			// 1 is Residential
			$firstAppointmentTimeId = insertAppointmentTime("$date 16:30:00", 4, 1, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 17:30:00", 4, 1, $siteId);
			$thirdAppointmentTimeId = insertAppointmentTime("$date 18:30:00", 4, 1, $siteId);
		}

		$DB_CONN->commit();
	} catch (Exception $e) {
		$DB_CONN->rollback();
		throw new Exception('Failed inserting Anderson Library data', MY_EXCEPTION);
		die();
	}
}

function insertNativeAmericanVITAData() {
	GLOBAL $DB_CONN;

	$siteId = 28;

	try {
		$DB_CONN->beginTransaction();

		// Sundays  (Jan 29-Apr 9 except Mar 5 & 12)

		$wednesdayDates = ['2023-01-29', '2023-02-05', '2023-02-12', '2023-02-19', '2023-02-26', '2023-03-19', '2023-03-26', '2023-04-02', '2023-04-09'];
		foreach ($wednesdayDates as $date) {

			// 1pm-2pm
			// 2pm-3pm
			// 3pm-4pm
			// 4pm-5pm
			// 1 is Residential
			$firstAppointmentTimeId = insertAppointmentTime("$date 13:00:00", 4, 1, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 14:00:00", 4, 1, $siteId);
			$thirdAppointmentTimeId = insertAppointmentTime("$date 15:00:00", 4, 1, $siteId);
			$fourthAppointmentTimeId = insertAppointmentTime("$date 16:00:00", 4, 1, $siteId);
		}

		$DB_CONN->commit();
	} catch (Exception $e) {
		$DB_CONN->rollback();
		throw new Exception('Failed inserting Native American VITA data', MY_EXCEPTION);
		die();
	}
}

function insertMuslimCommunityVITAData() {
	GLOBAL $DB_CONN;

	$siteId = 32;

	try {
		$DB_CONN->beginTransaction();

		// Saturdays (Jan 28-Apr 8 except Feb 25, Mar 1, 18 and 25)
		$wednesdayDates = ['2023-01-28', '2023-02-04', '2023-02-11', '2023-02-18', '2023-03-04', '2023-04-01', '2023-04-08']; // doing march 11 (not the 1st) per the poster
		foreach ($wednesdayDates as $date) {

			/*
			9am-10am
			10am-11am
			11am-12pm
			12pm-1pm
			1pm-2pm
			2pm-3pm
			3pm-4pm
			4pm-5pm
			5pm-6pm
			6pm-7pm
			7pm-8pm
			*/
			// 1 is Residential
			$firstAppointmentTimeId = insertAppointmentTime("$date 09:00:00", 2, 1, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 10:00:00", 2, 1, $siteId);
			$thirdAppointmentTimeId = insertAppointmentTime("$date 11:00:00", 2, 1, $siteId);
			$fourthAppointmentTimeId = insertAppointmentTime("$date 12:00:00", 2, 1, $siteId);
			$fourthAppointmentTimeId = insertAppointmentTime("$date 13:00:00", 2, 1, $siteId);
			$fourthAppointmentTimeId = insertAppointmentTime("$date 14:00:00", 2, 1, $siteId);
			$fourthAppointmentTimeId = insertAppointmentTime("$date 15:00:00", 2, 1, $siteId);
			$fourthAppointmentTimeId = insertAppointmentTime("$date 16:00:00", 2, 1, $siteId);
			$fourthAppointmentTimeId = insertAppointmentTime("$date 17:00:00", 2, 1, $siteId);
			$fourthAppointmentTimeId = insertAppointmentTime("$date 18:00:00", 2, 1, $siteId);
			$fourthAppointmentTimeId = insertAppointmentTime("$date 19:00:00", 2, 1, $siteId);
		}

		$DB_CONN->commit();
	} catch (Exception $e) {
		$DB_CONN->rollback();
		throw new Exception('Failed inserting Muslim Community data', MY_EXCEPTION);
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

		// Wednesdays (Feb 1-Mar 29)
		$wednesdayDates = getWeeklyDatesFromRange('2023-02-01', '2023-03-29');

		foreach ($wednesdayDates as $date) {

			// 11am-12pm
			// 12pm-1pm
			// 1pm-2pm
			$firstAppointmentTimeId = insertAppointmentTime("$date 11:00:00", 4, 1, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 12:00:00", 4, 1, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 13:00:00", 4, 1, $siteId);
		}
		$DB_CONN->commit();
	} catch (Exception $e) {
		$DB_CONN->rollback();
		throw new Exception('Failed inserting Center for People in Need data', MY_EXCEPTION);
		die();
	}
}

function insertAmericanJobCenterData() {
	GLOBAL $DB_CONN;
	
	$dataAlreadyInserted = true;
	if ($dataAlreadyInserted) {
		die('The Center for People in Need data has already been inserted');
	}

	try {
		$DB_CONN->beginTransaction();

		$siteId = 24; // Manually obtained from PROD DB

		// Mondays (Jan 30-Apr 10 except Feb 20)
		$wednesdayDates = ['2023-01-30', '2023-02-06', '2023-02-13', '2023-02-27', '2023-03-06', '2023-03-13', '2023-03-20', '2023-03-27', '2023-04-03', '2023-04-10'];

		foreach ($wednesdayDates as $date) {

			// 10am-11pm
			// 11pm-12pm
			// 12pm-1pm
			// 1pm-2pm
			$firstAppointmentTimeId = insertAppointmentTime("$date 10:00:00", 3, 1, $siteId);
			$firstAppointmentTimeId = insertAppointmentTime("$date 11:00:00", 3, 1, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 12:00:00", 3, 1, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 13:00:00", 3, 1, $siteId);
		}
		$DB_CONN->commit();
	} catch (Exception $e) {
		$DB_CONN->rollback();
		throw new Exception('Failed inserting Center for People in Need data', MY_EXCEPTION);
		die();
	}
}

function insertAsianCulturalCommunityCenterData() {
	GLOBAL $DB_CONN;
	
	$dataAlreadyInserted = true;
	if ($dataAlreadyInserted) {
		die('The Asian CCC data has already been inserted');
	}

	try {
		$DB_CONN->beginTransaction();

		$siteId = 14; // Manually obtained from PROD DB. TODO there are multiple of these in the DB now

		// Wednesdays (Feb 1-Mar 29)
		$wednesdayDates = getWeeklyDatesFromRange('2023-02-01', '2023-03-29');

		foreach ($wednesdayDates as $date) {
			// 1pm-2pm
			// 2pm-3pm
			// 3pm-4pm
			$secondAppointmentTimeId = insertAppointmentTime("$date 13:00:00", 2, 1, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 14:00:00", 2, 1, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 15:00:00", 2, 1, $siteId);
		}
		$DB_CONN->commit();
	} catch (Exception $e) {
		$DB_CONN->rollback();
		throw new Exception('Failed inserting Asian CCC data', MY_EXCEPTION);
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

		// Thursdays (Feb 2-Apr 13)
		$thursdayDates = getWeeklyDatesFromRange('2023-02-02', '2023-04-13');
		foreach ($thursdayDates as $date) {

			// 4:30pm-5:30pm
			// 5:30pm-6:30pm
			// 6:30pm-7:30pm
			$firstAppointmentTimeId = insertAppointmentTime("$date 16:30:00", 4, 1, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 17:30:00", 4, 1, $siteId);
			$thirdAppointmentTimeId = insertAppointmentTime("$date 18:30:00", 4, 1, $siteId);
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

		// Feb 5-Mar 26, Sundays 1-4 starting time
		$sundayDates = getWeeklyDatesFromRange('2023-02-05', '2023-03-26');
		foreach ($sundayDates as $date) {

			$firstAppointmentTimeId = insertAppointmentTime("$date 13:00:00", 5, 1, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 14:00:00", 5, 1, $siteId);
			$thirdAppointmentTimeId = insertAppointmentTime("$date 15:00:00", 5, 1, $siteId);
			$fourthAppointmentTimeId = insertAppointmentTime("$date 16:00:00", 5, 1, $siteId);
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

		// Tuesdays (Jan 31-Mar 28)
		$tuesdayDates = getWeeklyDatesFromRange('2023-01-31', '2023-03-28');
		foreach ($tuesdayDates as $date) {

			// 5-6pm
			// 6-7pm
			$firstAppointmentTimeId = insertAppointmentTime("$date 17:00:00", 2, 1, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 18:00:00", 2, 1, $siteId);
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

		// Thurdays (Feb 2-Apr 13 except Mar 16)
		$wednesdayDates = array('2023-02-02', '2023-02-09', '2023-02-16', '2023-02-23', '2023-03-02', '2023-03-09', '2023-03-23', '2023-03-30', '2023-04-06', '2023-04-13');
		foreach ($wednesdayDates as $date) {

			// 4-5 pm
			// 5-6 pm
			// 6-7 pm
			$firstAppointmentTimeId = insertAppointmentTime("$date 16:00:00", 4, 1, $siteId);
			$firstAppointmentTimeId = insertAppointmentTime("$date 17:00:00", 4, 1, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 18:00:00", 4, 1, $siteId);
		}

		// Mondays (Jan 30-Apr 10 except Feb 6, Mar 6 & Apr 3)
		$mondayDates = array('2023-01-30', '2023-02-13', '2023-02-20', '2023-02-27', '2023-03-13', '2023-03-20', '2023-03-27', '2023-04-10');

		foreach ($mondayDates as $date) {
			// 5-6 pm
			// 6-7 pm
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

function insertNebraskaUnionData() {
	GLOBAL $DB_CONN;
	
	$dataAlreadyInserted = true;
	if ($dataAlreadyInserted) {
		die('The Nebraska Union data has already been inserted');
	}

	$siteId = 11; // Manually obtained from PROD DB

	try {
		$DB_CONN->beginTransaction();

		// Saturdays (Jan 28-Mar 4)
		$saturdayDates = getWeeklyDatesFromRange('2023-01-28', '2023-03-04');
		foreach ($saturdayDates as $date) {

			//10am-11am first
			//3pm-4pm last
			$firstAppointmentTimeId = insertAppointmentTime("$date 10:00:00", 15, 1, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 11:00:00", 15, 1, $siteId);
			$thirdAppointmentTimeId = insertAppointmentTime("$date 12:00:00", 15, 1, $siteId);
			$fourthAppointmentTimeId = insertAppointmentTime("$date 13:00:00", 10, 1, $siteId);
			$fourthAppointmentTimeId = insertAppointmentTime("$date 14:00:00", 10, 1, $siteId);
			$fifthAppointmentTimeId = insertAppointmentTime("$date 15:00:00", 5, 1, $siteId);
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

	$siteId = 16; // Good Neighbor site id from db

	try {
		$DB_CONN->beginTransaction();

		// Sundays (Jan 29-Feb 19)
		$sundayDates = getWeeklyDatesFromRange('2023-01-29', '2023-02-19');
		foreach ($sundayDates as $date) {

			//1-2pm
			//2-3pm
			//3-4pm
			$firstAppointmentTimeId = insertAppointmentTime("$date 13:00:00", 3, 1, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 14:00:00", 3, 1, $siteId);
			$thirdAppointmentTimeId = insertAppointmentTime("$date 15:00:00", 3, 1, $siteId);
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

	$siteId = 17; // VA id from DB

	try {
		$DB_CONN->beginTransaction();

		// Fridays (Feb 3; Feb 10; Feb 24; Mar 4; Mar 10; Mar 24) //TODO should this be march 3rd?
		$fridayDates = array('2023-02-03', '2023-02-10', '2023-02-24', '2023-03-03', '2023-03-10', '2023-03-24');
		foreach ($fridayDates as $date) {

            // 0 because these are "hidden" appointments
			//10-11am first
			//2-3pm last
			$firstAppointmentTimeId = insertAppointmentTime("$date 10:00:00", 0, 1, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 11:00:00", 0, 1, $siteId);
			$thirdAppointmentTimeId = insertAppointmentTime("$date 12:00:00", 0, 1, $siteId);
			$fourthAppointmentTimeId = insertAppointmentTime("$date 13:00:00", 0, 1, $siteId);
			$fourthAppointmentTimeId = insertAppointmentTime("$date 14:00:00", 0, 1, $siteId);
		}

		$DB_CONN->commit();
	} catch (Exception $e) {
		$DB_CONN->rollback();
		throw new Exception('Failed inserting first VA data', MY_EXCEPTION);
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

		// Tuesdays (Mar 7, Mar 14, Mar 21, Mar 28, Apr 4, Apr 11) ALL APPOINTMENT ONLY
		$tuesdayDates = getWeeklyDatesFromRange('2023-03-07', '2023-04-11');
		foreach ($tuesdayDates as $date) {
			//times for all appt types:
			//1-2pm
			//2-3pm
			//3-4pm

			// China
			$firstAppointmentTimeId = insertAppointmentTime("$date 13:00:00", 15, 2, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 14:00:00", 15, 2, $siteId);
			$thirdAppointmentTimeId = insertAppointmentTime("$date 15:00:00", 15, 2, $siteId);

			// India
			$firstAppointmentTimeId = insertAppointmentTime("$date 13:00:00", 10, 3, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 14:00:00", 10, 3, $siteId);
			$thirdAppointmentTimeId = insertAppointmentTime("$date 15:00:00", 10, 3, $siteId);
		
			// Treaty
			$firstAppointmentTimeId = insertAppointmentTime("$date 13:00:00", 10, 4, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 14:00:00", 10, 4, $siteId);
			$thirdAppointmentTimeId = insertAppointmentTime("$date 15:00:00", 10, 4, $siteId);
		
			// Non-Treaty
			$firstAppointmentTimeId = insertAppointmentTime("$date 13:00:00", 15, 5, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 14:00:00", 15, 5, $siteId);
			$thirdAppointmentTimeId = insertAppointmentTime("$date 15:00:00", 15, 5, $siteId);
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

	$siteId = 12; //TODO make sure she wants us to upload these.

	try {
		$DB_CONN->beginTransaction();

        // JAN 30th to APR 10th
		$fridayDates = getWeeklyDatesFromRange('2023-01-31', '2023-04-04');
		foreach ($fridayDates as $date) {

			//15/day
			$firstAppointmentTimeId = insertAppointmentTime("$date 12:00:00", 15, 6, $siteId);
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