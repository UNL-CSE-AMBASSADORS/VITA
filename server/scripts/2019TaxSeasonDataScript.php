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


insert2019Data();

function insert2019Data() {	
	$dataAlreadyInserted = true;
	if ($dataAlreadyInserted) {
		die('The data has already been inserted');
	}

	insertNebraskaEastUnionData();
	insertAndersonLibraryData();
	insertCenterForPeopleInNeedData();
	insertLorenEiseleyLibraryData();
	insertBennettMartinLibraryData();
	// insertInternationalStudentScholarSiteData();

	die('SUCCESS');
}

// Need to create the new sites
// Create Shifts for each site
// Create appointment times within those shifts
// Create role limits for that site/shift/role

// The first shifts for NEU start 30 mins prior to the first appointment and the second shift ends at last appt time end time
function insertNebraskaEastUnionData() {
	GLOBAL $DB_CONN;

	$siteCoordinatorRoleId = 1;
	$greeterRoleId = 2;
	$preparerRoleId = 3;
	$reviewerRoleId = 4;

	$dataAlreadyInserted = true;
	if ($dataAlreadyInserted) {
		die('The NEU data has already been inserted');
	}

	$siteId = 1; // Manually obtained from PROD DB

	try {
		$DB_CONN->beginTransaction();

		// Mondays, Tuesdays, and Wednesdays are all the same
		$mondayDates = array('2019-01-28', '2019-02-04', '2019-02-11', '2019-02-18', '2019-02-25');
		$tuesdayDates = array('2019-01-22', '2019-01-29', '2019-02-05', '2019-02-12', '2019-02-19', '2019-02-26');
		$wednesdayDates = array('2019-01-23', '2019-01-30', '2019-02-06', '2019-02-13', '2019-02-20', '2019-02-27');
		$dates = array_merge($mondayDates, $tuesdayDates, $wednesdayDates);
		foreach ($dates as $date) {
			$firstShiftId = insertShift("$date 16:30:00", "$date 18:30:00", $siteId);
			$secondShiftId = insertShift("$date 18:00:00", "$date 20:00:00", $siteId);

			$firstAppointmentTimeId = insertAppointmentTime("$date 17:00:00", 200, 60, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 18:00:00", 200, 60, $siteId);
			$thirdAppointmentTimeId = insertAppointmentTime("$date 19:00:00", 100, 60, $siteId);
		}

		// Saturdays
		$dates = array('2019-01-26', '2019-02-02', '2019-02-09', '2019-02-16', '2019-02-23', '2019-03-02');
		foreach ($dates as $date) {
			$firstShiftId = insertShift("$date 09:30:00", "$date 13:00:00", $siteId);
			$secondShiftId = insertShift("$date 12:30:00", "$date 16:00:00", $siteId);

			$firstAppointmentTimeId = insertAppointmentTime("$date 10:00:00", 200, 60, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 11:00:00", 200, 60, $siteId);
			$thirdAppointmentTimeId = insertAppointmentTime("$date 12:00:00", 200, 60, $siteId);
			$fourthAppointmentTimeId = insertAppointmentTime("$date 13:00:00", 100, 60, $siteId);
			$fifthAppointmentTimeId = insertAppointmentTime("$date 14:00:00", 100, 60, $siteId);
			$sixthAppointmentTimeId = insertAppointmentTime("$date 15:00:00", 100, 60, $siteId);

			insertShiftRoleLimit(25, $preparerRoleId, $firstShiftId, $siteId);
			insertShiftRoleLimit(25, $preparerRoleId, $secondShiftId, $siteId);
			insertShiftRoleLimit(5, $greeterRoleId, $firstShiftId, $siteId);
			insertShiftRoleLimit(5, $greeterRoleId, $secondShiftId, $siteId);
			insertShiftRoleLimit(5, $reviewerRoleId, $firstShiftId, $siteId);
			insertShiftRoleLimit(5, $reviewerRoleId, $secondShiftId, $siteId);
		}

		// Sundays
		$dates = array('2019-01-20', '2019-01-27', '2019-02-03', '2019-02-10', '2019-02-17', '2019-02-24', '2019-03-03');
		foreach ($dates as $date) {
			$firstShiftId = insertShift("$date 12:30:00", "$date 14:30:00", $siteId);
			$secondShiftId = insertShift("$date 14:00:00", "$date 16:00:00", $siteId);

			$firstAppointmentTimeId = insertAppointmentTime("$date 13:00:00", 200, 60, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 14:00:00", 200, 60, $siteId);
			$thirdAppointmentTimeId = insertAppointmentTime("$date 15:00:00", 100, 60, $siteId);

			insertShiftRoleLimit(25, $preparerRoleId, $firstShiftId, $siteId);
			insertShiftRoleLimit(25, $preparerRoleId, $secondShiftId, $siteId);
			insertShiftRoleLimit(5, $greeterRoleId, $firstShiftId, $siteId);
			insertShiftRoleLimit(5, $greeterRoleId, $secondShiftId, $siteId);
			insertShiftRoleLimit(5, $reviewerRoleId, $firstShiftId, $siteId);
			insertShiftRoleLimit(5, $reviewerRoleId, $secondShiftId, $siteId);
		}

		// Default Site Role Limits
		insertSiteRoleLimit(1, $siteCoordinatorRoleId, $siteId);
		insertSiteRoleLimit(1, $greeterRoleId, $siteId);
		insertSiteRoleLimit(6, $preparerRoleId, $siteId);

		$DB_CONN->commit();
	} catch (Exception $e) {
		$DB_CONN->rollback();
		throw new Exception('Failed inserting NEU data', MY_EXCEPTION);
		die();
	}
}

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

		// Tuesdays and Wednesdays are the same
		$tuesdayDates = array('2019-01-22', '2019-01-29', '2019-02-05', '2019-02-12', '2019-02-19', '2019-02-26', '2019-03-05', '2019-03-12', '2019-03-19', '2019-03-26', '2019-04-02', '2019-04-09');
		$wednesdayDates = array('2019-01-23', '2019-01-30', '2019-02-06', '2019-02-13', '2019-02-20', '2019-02-27', '2019-03-06', '2019-03-13', '2019-03-20', '2019-03-27', '2019-04-03', '2019-04-10');
		$dates = array_merge($tuesdayDates, $wednesdayDates);
		foreach ($dates as $date) {
			$shiftId = insertShift("$date 17:00:00", "$date 20:00:00", $siteId);

			$firstAppointmentTimeId = insertAppointmentTime("$date 17:00:00", 200, 60, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 18:00:00", 200, 60, $siteId);
			$thirdAppointmentTimeId = insertAppointmentTime("$date 19:00:00", 100, 60, $siteId);
		}

		// Default Site Role Limits
		insertSiteRoleLimit(1, $siteCoordinatorRoleId, $siteId);
		insertSiteRoleLimit(1, $greeterRoleId, $siteId);
		insertSiteRoleLimit(4, $preparerRoleId, $siteId);

		$DB_CONN->commit();
	} catch (Exception $e) {
		$DB_CONN->rollback();
		throw new Exception('Failed inserting AL data', MY_EXCEPTION);
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

		$siteId = insertSite('Center for People in Need', '3901 N 27th, Unit 1', '402-472-9638', FALSE, FALSE);

		// Tuesdays and Wednesdays are the same
		$tuesdayDates = array('2019-02-05', '2019-02-12', '2019-02-19', '2019-02-26');
		$wednesdayDates = array('2019-03-06', '2019-03-13', '2019-03-27');
		$dates = array_merge($tuesdayDates, $wednesdayDates);
		foreach ($dates as $date) {
			$shiftId = insertShift("$date 11:00:00", "$date 14:00:00", $siteId);

			$firstAppointmentTimeId = insertAppointmentTime("$date 11:00:00", 200, 60, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 12:00:00", 200, 60, $siteId);
			$thirdAppointmentTimeId = insertAppointmentTime("$date 13:00:00", 200, 60, $siteId);
		}

		// Default Site Role Limits
		insertSiteRoleLimit(1, $siteCoordinatorRoleId, $siteId);
		insertSiteRoleLimit(1, $greeterRoleId, $siteId);
		insertSiteRoleLimit(2, $preparerRoleId, $siteId);

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

		$siteId = insertSite('Loren Eiseley Library', '1530 Superior Street', '402-472-9638', FALSE, FALSE);

		// Thursdays
		$dates = array('2019-02-07', '2019-02-14', '2019-02-21', '2019-02-28', '2019-03-07', '2019-03-14', '2019-03-21', '2019-03-28', '2019-04-04', '2019-04-11');
		foreach ($dates as $date) {
			$shiftId = insertShift("$date 16:00:00", "$date 19:00:00", $siteId);

			$firstAppointmentTimeId = insertAppointmentTime("$date 16:00:00", 200, 60, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 17:00:00", 200, 60, $siteId);
			$thirdAppointmentTimeId = insertAppointmentTime("$date 18:00:00", 200, 60, $siteId);
		}

		// Sundays
		$dates = array('2019-02-03', '2019-02-10', '2019-02-17', '2019-02-24', '2019-03-03', '2019-03-10', '2019-03-17', '2019-03-24', '2019-03-31', '2019-04-07');
		foreach ($dates as $date) {
			$shiftId = insertShift("$date 13:00:00", "$date 16:00:00", $siteId);

			$firstAppointmentTimeId = insertAppointmentTime("$date 13:00:00", 200, 60, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 14:00:00", 200, 60, $siteId);
			$thirdAppointmentTimeId = insertAppointmentTime("$date 15:00:00", 200, 60, $siteId);
		}

		// Default Site Role Limits
		insertSiteRoleLimit(1, $siteCoordinatorRoleId, $siteId);
		insertSiteRoleLimit(1, $greeterRoleId, $siteId);
		insertSiteRoleLimit(6, $preparerRoleId, $siteId);

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

		$siteId = insertSite('Bennett Martin Library', '14th & N Street', '402-472-9638', FALSE, FALSE);

		// Sundays
		$dates = array('2019-02-03', '2019-02-10', '2019-02-17', '2019-02-24', '2019-03-03', '2019-03-10', '2019-03-17', '2019-03-24');
		foreach ($dates as $date) {
			$shiftId = insertShift("$date 13:00:00", "$date 16:00:00", $siteId);

			$firstAppointmentTimeId = insertAppointmentTime("$date 13:00:00", 200, 60, $siteId);
			$secondAppointmentTimeId = insertAppointmentTime("$date 14:00:00", 200, 60, $siteId);
			$thirdAppointmentTimeId = insertAppointmentTime("$date 15:00:00", 200, 60, $siteId);
		}

		// Default Site Role Limits
		insertSiteRoleLimit(1, $siteCoordinatorRoleId, $siteId);
		insertSiteRoleLimit(1, $greeterRoleId, $siteId);
		insertSiteRoleLimit(4, $preparerRoleId, $siteId);

		$DB_CONN->commit();
	} catch (Exception $e) {
		$DB_CONN->rollback();
		throw new Exception('Failed inserting Center for People in Need data', MY_EXCEPTION);
		die();
	}

}

function insertInternationalStudentScholarSiteData() {
	$dataAlreadyInserted = true;
	if ($dataAlreadyInserted) {
		die('The ISS data has already been inserted');
	}

	// TODO: NEED TO FINISH UPDATED STATION LIMITING CODE BEFORE I CAN ENTER THIS

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

function insertAppointmentTime($scheduledTime, $percentageAppointments, $approximateLengthInMinutes, $siteId) {
	GLOBAL $DB_CONN;

	$query = 'INSERT INTO AppointmentTime (scheduledTime, percentageAppointments, approximateLengthInMinutes, siteId)
		VALUES (?, ?, ?, ?)';
	$params = array($scheduledTime, $percentageAppointments, $approximateLengthInMinutes, $siteId);

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