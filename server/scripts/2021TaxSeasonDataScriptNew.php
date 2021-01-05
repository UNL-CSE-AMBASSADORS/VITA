<?php
// This script will only work for Matthew Meacham

$root = realpath($_SERVER['DOCUMENT_ROOT']);
require_once "$root/server/user.class.php";
$USER = new User();
if (!$USER->isLoggedIn() || $USER->getUserId() !== '1') {
	header("Location: /unauthorized");
	die();
}

// TODO: Need to update the `$dataAlreadyInserted` values before this query will run successfully

insert2020Data();

function insert2020Data() {	
	$dataAlreadyInserted = true;
	if ($dataAlreadyInserted) {
		die('The data has already been inserted');
	}
	// insertAndersonLibraryData();
	// insertCenterForPeopleInNeedData();
	// insertLorenEiseleyLibraryData();
	// insertBennettMartinLibraryData();
	// insertFStreetCommunityCenterData();
	// insertSoutheastCommunityCollegeData();
	// insertInternationalStudentScholarSiteData();
	// insertNebraskaUnionData();

	die('SUCCESS');
}

// Need to create the new sites
// Create Shifts for each site
// Create appointment times within those shifts
// Create role limits for that site/shift/role

function insertAndersonLibraryData() {
	GLOBAL $DB_CONN;

	$siteCoordinatorRoleId = 1;
	$greeterRoleId = 2;
	$preparerRoleId = 3;
	$reviewerRoleId = 4;
	
	$dataAlreadyInserted = true;
	if ($dataAlreadyInserted) {
		die('The AL data has already been inserted');
	}

	$siteId = 2; // Manually obtained from PROD DB

	try {
		$DB_CONN->beginTransaction();

		// Wednesdays
		$wednesdayDates = getWeeklyDatesFromRange('2021-02-03', '2021-04-07');
		foreach ($wednesdayDates as $date) {
			$shiftId = insertShift("$date 17:00:00", "$date 20:00:00", $siteId);

			// Are from Katie's parameter sheet
			$firstAppointmentTimeId = insertAppointmentTime("$date 17:00:00", 7.5, 7.5, 100, 60, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 18:00:00", 7.5, 7.5, 100, 60, $siteId);
			$thirdAppointmentTimeId = insertAppointmentTime("$date 19:00:00", 7.5, 7.5, 100, 60, $siteId);
		}
		
		// Thursdays
		$thursdayDates = getWeeklyDatesFromRange('2021-02-04', '2021-02-08');
		foreach ($thursdayDates as $date) {
			$shiftId = insertShift("$date 17:00:00", "$date 20:00:00", $siteId);

			$firstAppointmentTimeId = insertAppointmentTime("$date 17:00:00", 7.5, 7.5, 100, 60, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 18:00:00", 7.5, 7.5, 100, 60, $siteId);
			$thirdAppointmentTimeId = insertAppointmentTime("$date 19:00:00", 7.5, 7.5, 100, 60, $siteId);
		}

		// Default Site Role Limits
		insertSiteRoleLimit(1, $siteCoordinatorRoleId, $siteId);
		insertSiteRoleLimit(1, $greeterRoleId, $siteId);

		$DB_CONN->commit();
	} catch (Exception $e) {
		$DB_CONN->rollback();
		throw new Exception('Failed inserting Anderson Library data', MY_EXCEPTION);
		die();
	}
}

//center for people in need exists Feb 2 - Mar 23
function insertCenterForPeopleInNeedData() {
	GLOBAL $DB_CONN;

	$siteCoordinatorRoleId = 1;
	$greeterRoleId = 2;
	$preparerRoleId = 3;
	$reviewerRoleId = 4;
	
	$dataAlreadyInserted = true;
	if ($dataAlreadyInserted) {
		die('The Center for People in Need data has already been inserted');
	}

	try {
		$DB_CONN->beginTransaction();

		$siteId = 5; // Manually obtained from PROD DB

		// Tuesdays
		$tuesdayDates = getWeeklyDatesFromRange('2021-02-02', '2021-03-23'); //TODO check this end date!
		foreach ($tuesdayDates as $date) {
			$shiftId = insertShift("$date 11:00:00", "$date 14:00:00", $siteId);

			$firstAppointmentTimeId = insertAppointmentTime("$date 11:00:00", 6, 6, 100, 60, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 12:00:00", 6, 6, 100, 60, $siteId);
			$thirdAppointmentTimeId = insertAppointmentTime("$date 13:00:00", 6, 6, 100, 60, $siteId);
		}

		// Default Site Role Limits
		insertSiteRoleLimit(1, $siteCoordinatorRoleId, $siteId);
		insertSiteRoleLimit(1, $greeterRoleId, $siteId);

		$DB_CONN->commit();
	} catch (Exception $e) {
		$DB_CONN->rollback();
		throw new Exception('Failed inserting Center for People in Need data', MY_EXCEPTION);
		die();
	}
}

function insertLorenEiseleyLibraryData() {
	GLOBAL $DB_CONN;

	$siteCoordinatorRoleId = 1;
	$greeterRoleId = 2;
	$preparerRoleId = 3;
	$reviewerRoleId = 4;
	
	$dataAlreadyInserted = true;
	if ($dataAlreadyInserted) {
		die('The Eiseley data has already been inserted');
	}

	try {
		$DB_CONN->beginTransaction();

		$siteId = 6; // Manually obtained from PROD DB

		// Thursdays
		$thursdayDates = getWeeklyDatesFromRange('2021-02-04', '2021-04-08');
		foreach ($thursdayDates as $date) {
			$shiftId = insertShift("$date 16:00:00", "$date 19:00:00", $siteId);

			$firstAppointmentTimeId = insertAppointmentTime("$date 16:00:00", 6, 6, 100, 60, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 17:00:00", 6, 6, 100, 60, $siteId);
			$thirdAppointmentTimeId = insertAppointmentTime("$date 18:00:00", 6, 6, 100, 60, $siteId);
		}

		// Saturdays
		$saturdayDates = getWeeklyDatesFromRange('2021-02-06', '2021-04-10');
		foreach ($saturdayDates as $date) {
			$shiftId = insertShift("$date 13:00:00", "$date 16:00:00", $siteId);

			$firstAppointmentTimeId = insertAppointmentTime("$date 13:00:00", 6, 6, 100, 60, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 14:00:00", 6, 6, 100, 60, $siteId);
			$thirdAppointmentTimeId = insertAppointmentTime("$date 15:00:00", 6, 6, 100, 60, $siteId);
		}

		// Default Site Role Limits
		insertSiteRoleLimit(1, $siteCoordinatorRoleId, $siteId);
		insertSiteRoleLimit(1, $greeterRoleId, $siteId);

		$DB_CONN->commit();
	} catch (Exception $e) {
		$DB_CONN->rollback();
		throw new Exception('Failed inserting Center for People in Need data', MY_EXCEPTION);
		die();
	}
}

function insertBennettMartinLibraryData() {
	GLOBAL $DB_CONN;

	$siteCoordinatorRoleId = 1;
	$greeterRoleId = 2;
	$preparerRoleId = 3;
	$reviewerRoleId = 4;
	
	$dataAlreadyInserted = true;
	if ($dataAlreadyInserted) {
		die('The Bennett Martin data has already been inserted');
	}

	try {
		$DB_CONN->beginTransaction();

		$siteId = 7; // Manually obtained from PROD DB

		// Sundays
		$sundayDates = getWeeklyDatesFromRange('2021-02-07', '2021-03-14');
		foreach ($sundayDates as $date) {
			$shiftId = insertShift("$date 13:00:00", "$date 16:00:00", $siteId);

			$firstAppointmentTimeId = insertAppointmentTime("$date 13:00:00", 6, 6, 100, 60, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 14:00:00", 6, 6, 100, 60, $siteId);
			$thirdAppointmentTimeId = insertAppointmentTime("$date 15:00:00", 6, 6, 100, 60, $siteId);
		}

		// Default Site Role Limits
		insertSiteRoleLimit(1, $siteCoordinatorRoleId, $siteId);
		insertSiteRoleLimit(1, $greeterRoleId, $siteId);

		$DB_CONN->commit();
	} catch (Exception $e) {
		$DB_CONN->rollback();
		throw new Exception('Failed inserting Center for People in Need data', MY_EXCEPTION);
		die();
	}
}

function insertFStreetCommunityCenterData() {
	GLOBAL $DB_CONN;

	$siteCoordinatorRoleId = 1;
	$greeterRoleId = 2;
	$preparerRoleId = 3;
	$reviewerRoleId = 4;
	
	$dataAlreadyInserted = true;
	if ($dataAlreadyInserted) {
		die('The F Street Community Center data has already been inserted');
	}
	
	try {
		$DB_CONN->beginTransaction();

		$siteId = 8; // Manually obtained from PROD DB

		// Tuesdays Feb 2 - Apr 6
		$tuesdayDates = getWeeklyDatesFromRange('2021-02-02', '2021-04-06');
		foreach ($tuesdayDates as $date) {
			$shiftId = insertShift("$date 17:00:00", "$date 19:00:00", $siteId);

			$firstAppointmentTimeId = insertAppointmentTime("$date 17:00:00", 6, 6, 100, 60, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 18:00:00", 6, 6, 100, 60, $siteId);
		}

		// Default Site Role Limits
		insertSiteRoleLimit(1, $siteCoordinatorRoleId, $siteId);
		insertSiteRoleLimit(1, $greeterRoleId, $siteId);

		$DB_CONN->commit();
	} catch (Exception $e) {
		$DB_CONN->rollback();
		throw new Exception('Failed inserting F Street Community Center data', MY_EXCEPTION);
		die();
	}
}

//Southeast community exists
function insertSoutheastCommunityCollegeData() {	
	GLOBAL $DB_CONN;

	$siteCoordinatorRoleId = 1;
	$greeterRoleId = 2;
	$preparerRoleId = 3;
	$reviewerRoleId = 4;

	$dataAlreadyInserted = true;
	if ($dataAlreadyInserted) {
		die('The Southeast Community College Data has already been inserted');
	}
 	
	try {
		$DB_CONN->beginTransaction();

		$siteId = 10; // Manually obtained from PROD DB

		// Mondays, Feb 1 - Mar 1
		$mondayDates = getWeeklyDatesFromRange('2021-02-01', '2021-03-01');//TODO idk if this is monday or satur//array('2021-02-01', '2021-02-08', '2021-02-15', '2021-02-22', '2021-03-01');
		foreach ($mondayDates as $date) {
			$shiftId = insertShift("$date 16:30:00", "$date 18:30:00", $siteId);

			$firstAppointmentTimeId = insertAppointmentTime("$date 16:30:00", 4, 4, 100, 60, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 17:30:00", 4, 4, 100, 60, $siteId);
		}

		// Thursdays, Feb 4 - mar 25 or apr 8
		$thursdayDates = getWeeklyDatesFromRange('2021-02-04', '2021-03-25');//TODO CHECK end date //array('2021-02-18', '2021-02-25', '2021-03-04', '2021-03-11', '2021-03-18', '2021-03-25', '2021-04-01', '2021-04-08');
		foreach ($thursdayDates as $date) {
			$shiftId = insertShift("$date 16:30:00", "$date 18:30:00", $siteId);

			$firstAppointmentTimeId = insertAppointmentTime("$date 16:30:00", 4.5, 4.5, 100, 60, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 17:30:00", 4.5, 4.5, 100, 60, $siteId);
		}

		// Default Site Role Limits
		insertSiteRoleLimit(1, $siteCoordinatorRoleId, $siteId);
		insertSiteRoleLimit(1, $greeterRoleId, $siteId);

		$DB_CONN->commit();
	} catch (Exception $e) {
		$DB_CONN->rollback();
		throw new Exception('Failed inserting Southeast Community College data', MY_EXCEPTION);
		die();
	}
}

function insertNebraskaUnionData() {
	GLOBAL $DB_CONN;

	$siteCoordinatorRoleId = 1;
	$greeterRoleId = 2;
	$preparerRoleId = 3;
	$reviewerRoleId = 4;
	
	$dataAlreadyInserted = true;
	if ($dataAlreadyInserted) {
		die('The Nebraska Union data has already been inserted');
	}

	$siteId = 11; // Manually obtained from PROD DB

	try {
		$DB_CONN->beginTransaction();

		// Saturdays
		$saturdayDates = getWeeklyDatesFromRange('2021-01-30', '2021-03-13');
		foreach ($saturdayDates as $date) {
			$shiftId = insertShift("$date 10:00:00", "$date 16:00:00", $siteId);

			$firstAppointmentTimeId = insertAppointmentTime("$date 10:00:00", 7.5, 7.5, 100, 60, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 11:00:00", 7.5, 7.5, 100, 60, $siteId);
			$thirdAppointmentTimeId = insertAppointmentTime("$date 12:00:00", 7.5, 7.5, 100, 60, $siteId);
			$fourthAppointmentTimeId = insertAppointmentTime("$date 13:00:00", 7.5, 7.5, 100, 60, $siteId);
			$fifthAppointmentTimeId = insertAppointmentTime("$date 14:00:00", 7.5, 7.5, 100, 60, $siteId);
			$sixthAppointmentTimeId = insertAppointmentTime("$date 15:00:00", 7.5, 7.5, 100, 60, $siteId);
		}

		// Default Site Role Limits
		insertSiteRoleLimit(1, $siteCoordinatorRoleId, $siteId);
		insertSiteRoleLimit(1, $greeterRoleId, $siteId);

		$DB_CONN->commit();
	} catch (Exception $e) {
		$DB_CONN->rollback();
		throw new Exception('Failed inserting Nebraska Union data', MY_EXCEPTION);
		die();
	}
}

function insertInternationalStudentScholarSiteData() {
	GLOBAL $DB_CONN;

	$siteCoordinatorRoleId = 1;
	$greeterRoleId = 2;
	$preparerRoleId = 3;
	$reviewerRoleId = 4;
	
	$dataAlreadyInserted = true;
	if ($dataAlreadyInserted) {
		die('The ISS data has already been inserted');
	}

	$siteId = 4; // Manually obtained from PROD DB

	try {
		$DB_CONN->beginTransaction();

		// Tuesdays
		$tuesdayDates = getWeeklyDatesFromRange('2021-03-02', '2021-04-06');
		foreach ($tuesdayDates as $date) {
			$shiftId = insertShift("$date 12:00:00", "$date 17:00:00", $siteId);

			$fifthAppointmentTimeId = insertAppointmentTime("$date 12:00:00", 30, 30, 100, 60, $siteId);
			$firstAppointmentTimeId = insertAppointmentTime("$date 13:00:00", 30, 30, 100, 60, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 14:00:00", 30, 30, 100, 60, $siteId);
			$thirdAppointmentTimeId = insertAppointmentTime("$date 15:00:00", 30, 30, 100, 60, $siteId);
			$fourthAppointmentTimeId = insertAppointmentTime("$date 16:00:00", 30, 30, 100, 60, $siteId);

		}

		// Default Site Role Limits
		insertSiteRoleLimit(1, $siteCoordinatorRoleId, $siteId);
		insertSiteRoleLimit(1, $greeterRoleId, $siteId);

		$DB_CONN->commit();
	} catch (Exception $e) {
		$DB_CONN->rollback();
		throw new Exception('Failed inserting ISS data', MY_EXCEPTION);
		die();
	}

}
//f street community center exists

// Asian Community and Cultural Center doesn't exist
//Lincoln City Libraries? victor e anderson, loren eiseley, bennet martin library
//Nebraska Unions? east and normal





/*
 * Helper Methods
 */ 
function insertSite($title, $address, $phoneNumber, $doesMultilingual, $doesInternational) {
	GLOBAL $DB_CONN;

	$query = 'INSERT INTO Site (title, address, phoneNumber, doesMultilingual, doesInternational, createdBy, lastModifiedBy)
		VALUES (?, ?, ?, ?, ?, 1, 1);';
	$params = array($title, $address, $phoneNumber, $doesMultilingual, $doesInternational);

	$stmt = $DB_CONN->prepare($query);
	if (!$stmt->execute($params)) {
		throw new Exception("Unable to insert site", MY_EXCEPTION);
	}

	return $DB_CONN->lastInsertId();
}

function insertShift($startTime, $endTime, $siteId) {
	GLOBAL $DB_CONN;

	$query = 'INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
		VALUES (?, ?, ?, 1, 1)';
	$params = array($startTime, $endTime, $siteId);

	$stmt = $DB_CONN->prepare($query);
	if (!$stmt->execute($params)) {
		throw new Exception("Unable to insert shift", MY_EXCEPTION);
	}

	return $DB_CONN->lastInsertId();
}

function insertAppointmentTime($scheduledTime, $minimumNumberOfAppointments, $maximumNumberOfAppointments, $percentageAppointments, $approximateLengthInMinutes, $siteId) {
	GLOBAL $DB_CONN;

	$query = 'INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, percentageAppointments, approximateLengthInMinutes, siteId)
		VALUES (?, ?, ?, ?, ?, ?)';
	$params = array($scheduledTime, $minimumNumberOfAppointments, $maximumNumberOfAppointments, $percentageAppointments, $approximateLengthInMinutes, $siteId);

	$stmt = $DB_CONN->prepare($query);
	if (!$stmt->execute($params)) {
		throw new Exception("Unable to insert appointment time", MY_EXCEPTION);
	}

	return $DB_CONN->lastInsertId();
}

function insertSiteRoleLimit($maximumNumber, $roleId, $siteId) {
	GLOBAL $DB_CONN;

	$query = 'INSERT INTO RoleLimit (maximumNumber, roleId, siteId)
		VALUES (?, ?, ?)';
	$params = array($maximumNumber, $roleId, $siteId);

	$stmt = $DB_CONN->prepare($query);
	if (!$stmt->execute($params)) {
		throw new Exception("Unable to insert role limit", MY_EXCEPTION);
	}

	return $DB_CONN->lastInsertId();
}

function insertShiftRoleLimit($maximumNumber, $roleId, $shiftId, $siteId) {
	GLOBAL $DB_CONN;

	$query = 'INSERT INTO RoleLimit (maximumNumber, roleId, shiftId, siteId)
		VALUES (?, ?, ?, ?)';
	$params = array($maximumNumber, $roleId, $shiftId, $siteId);

	$stmt = $DB_CONN->prepare($query);
	if (!$stmt->execute($params)) {
		throw new Exception("Unable to insert role limit", MY_EXCEPTION);
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
