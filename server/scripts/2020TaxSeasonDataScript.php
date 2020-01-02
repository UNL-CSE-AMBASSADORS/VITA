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
		$wednesdayDates = getWeeklyDatesFromRange('2020-01-29', '2020-04-08');
		foreach ($wednesdayDates as $date) {
			$shiftId = insertShift("$date 17:00:00", "$date 20:00:00", $siteId);

			// Are from Katie's parameter sheet
			$firstAppointmentTimeId = insertAppointmentTime("$date 17:00:00", 9, 9, 100, 60, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 18:00:00", 9, 9, 100, 60, $siteId);
			$thirdAppointmentTimeId = insertAppointmentTime("$date 19:00:00", 5, 5, 100, 60, $siteId);
		}
		
		// Thursdays
		$thursdayDates = getWeeklyDatesFromRange('2020-01-30', '2020-04-09');
		foreach ($thursdayDates as $date) {
			$shiftId = insertShift("$date 17:00:00", "$date 20:00:00", $siteId);

			$firstAppointmentTimeId = insertAppointmentTime("$date 17:00:00", 6, 6, 100, 60, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 18:00:00", 6, 6, 100, 60, $siteId);
			$thirdAppointmentTimeId = insertAppointmentTime("$date 19:00:00", 5, 5, 100, 60, $siteId);
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
		$tuesdayDates = getWeeklyDatesFromRange('2020-02-04', '2020-03-24');
		foreach ($tuesdayDates as $date) {
			$shiftId = insertShift("$date 11:00:00", "$date 14:00:00", $siteId);

			$firstAppointmentTimeId = insertAppointmentTime("$date 11:00:00", 8, 8, 100, 60, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 12:00:00", 8, 8, 100, 60, $siteId);
			$thirdAppointmentTimeId = insertAppointmentTime("$date 13:00:00", 4, 4, 100, 60, $siteId);
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
		$thursdayDates = getWeeklyDatesFromRange('2020-02-06', '2020-04-09');
		foreach ($thursdayDates as $date) {
			$shiftId = insertShift("$date 16:00:00", "$date 19:00:00", $siteId);

			$firstAppointmentTimeId = insertAppointmentTime("$date 16:00:00", 8, 8, 100, 60, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 17:00:00", 8, 8, 100, 60, $siteId);
			$thirdAppointmentTimeId = insertAppointmentTime("$date 18:00:00", 4, 4, 100, 60, $siteId);
		}

		// Saturdays
		$saturdayDates = getWeeklyDatesFromRange('2020-02-01', '2020-04-04');
		foreach ($saturdayDates as $date) {
			$shiftId = insertShift("$date 13:00:00", "$date 16:00:00", $siteId);

			$firstAppointmentTimeId = insertAppointmentTime("$date 13:00:00", 8, 8, 100, 60, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 14:00:00", 8, 8, 100, 60, $siteId);
			$thirdAppointmentTimeId = insertAppointmentTime("$date 15:00:00", 4, 4, 100, 60, $siteId);
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
		$sundayDates = getWeeklyDatesFromRange('2020-02-02', '2020-03-15');
		foreach ($sundayDates as $date) {
			$shiftId = insertShift("$date 13:00:00", "$date 16:00:00", $siteId);

			$firstAppointmentTimeId = insertAppointmentTime("$date 13:00:00", 8, 8, 100, 60, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 14:00:00", 8, 8, 100, 60, $siteId);
			$thirdAppointmentTimeId = insertAppointmentTime("$date 15:00:00", 4, 4, 100, 60, $siteId);
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

		// Tuesdays
		$tuesdayDates = getWeeklyDatesFromRange('2020-02-04', '2020-04-07');
		foreach ($tuesdayDates as $date) {
			$shiftId = insertShift("$date 17:00:00", "$date 19:00:00", $siteId);

			$firstAppointmentTimeId = insertAppointmentTime("$date 17:00:00", 8, 8, 100, 60, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 18:00:00", 4, 4, 100, 60, $siteId);
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

		$siteId = insertSite('Southeast Community College', '8800 O Street, #T-102', '402-472-9638', FALSE, FALSE);

		// Thursdays, closed March 26th
		$thursdayDates = array('2020-02-06', '2020-02-13', '2020-02-20', '2020-02-27', '2020-03-05', '2020-03-12', '2020-03-19', '2020-04-02', '2020-04-09');
		foreach ($thursdayDates as $date) {
			$shiftId = insertShift("$date 16:30:00", "$date 18:30:00", $siteId);

			$firstAppointmentTimeId = insertAppointmentTime("$date 16:30:00", 4, 4, 100, 60, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 17:30:00", 4, 4, 100, 60, $siteId);
		}

		// Saturdays, closed March 21st and 28th
		$saturdayDates = array('2020-02-01', '2020-02-08', '2020-02-15', '2020-02-22', '2020-02-29', '2020-03-07', '2020-03-14', '2020-04-04');
		foreach ($saturdayDates as $date) {
			$shiftId = insertShift("$date 09:00:00", "$date 13:00:00", $siteId);

			$firstAppointmentTimeId = insertAppointmentTime("$date 09:00:00", 4, 4, 100, 60, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 10:00:00", 4, 4, 100, 60, $siteId);
			$thirdAppointmentTimeId = insertAppointmentTime("$date 11:00:00", 4, 4, 100, 60, $siteId);
			$fourthAppointmentTimeId = insertAppointmentTime("$date 12:00:00", 4, 4, 100, 60, $siteId);
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
