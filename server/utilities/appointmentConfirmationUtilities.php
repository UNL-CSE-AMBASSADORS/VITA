<?php
$root = realpath($_SERVER['DOCUMENT_ROOT']);
require_once "$root/server/config.php";


function generateAppointmentConfirmation($appointmentId) {
	$data = getAppointmentInformation($appointmentId);

	$firstName = $data['firstName'];
	$siteTitle = $data['title'];
	$siteAddress = $data['address'];
	$sitePhoneNumber = $data['phoneNumber'];
	$dateStr = $data['dateStr'];
	$timeStr = $data['timeStr'];
	$doesInternational = $data['doesInternational'];

	$message = introductionInformation($firstName, $siteTitle, $siteAddress, $timeStr, $dateStr, $sitePhoneNumber);
	if ($doesInternational) {
		$message .= internationalInformation(); // If it is an international appointment, there is a different list of what to brings than for residential appointments
	} else {
		$message .= residentialInformation();
	}
	$message .= miscellaneousInformation();

	return $message;
}


/*
function generateConfirmation($firstName, $siteId, $appointmentTimeId) {
	$siteInformation = getSiteInformation($siteId);
	$appointmentTimeInformation = getAppointmentTimeInformation($appointmentTimeId);

	$siteTitle = $siteInformation['title'];
	$siteAddress = $siteInformation['address'];
	$sitePhoneNumber = $siteInformation['phoneNumber'];
	$dateStr = $appointmentTimeInformation['dateStr'];
	$timeStr = $appointmentTimeInformation['timeStr'];

	$message = "<h2>Appointment Confirmation</h2>
				$firstName, thank you for signing up! Your appointment will be located at the $siteTitle site ($siteAddress). 
				Please arrive no later than $timeStr on $dateStr with all necessary materials (listed below). 
				Please call $sitePhoneNumber if you have any questions or would like to reschedule. 
				Thank you from Lincoln VITA.
				<h2 class='mt-3'>What to Bring for your Appointment</h2>";
	if ($siteInformation['doesInternational']) {
		$message .= internationalInformation(); // If it is an international appointment, there is a different list of what to brings than for residential appointments
	} else {
		$message .= residentialInformation();
	}
	$message .= "<h5>Miscellaneous:</h5>
				<ul>
					<li>Checking or savings account information for direct deposit/direct debit</li>
					<li>It is <b>STRONGLY RECOMMENDED</b> that you bring last year's tax return</li>
				</ul>";

	return $message;
}
*/

// For private use
function getAppointmentInformation($appointmentId) {
	GLOBAL $DB_CONN;

	$query = "SELECT Site.address, Site.phoneNumber, Site.title, Site.doesInternational, Client.firstName, 
		DATE_FORMAT(scheduledTime, '%W, %M %D, %Y') AS dateStr, TIME_FORMAT(scheduledTime, '%l:%i %p') as timeStr
		FROM Appointment
		JOIN Client ON Appointment.clientId = Client.clientId
		JOIN AppointmentTime ON Appointment.appointmentTimeId = AppointmentTime.appointmentTimeId
		JOIN Site ON AppointmentTime.siteId = Site.siteId
		WHERE Appointment.appointmentId = ?";

	$stmt = $DB_CONN->prepare($query);
	$stmt->execute(array($appointmentId));

	return $stmt->fetch();
}

function introductionInformation($firstName, $siteTitle, $siteAddress, $timeStr, $dateStr, $sitePhoneNumber) {
	return "<h2>Appointment Confirmation</h2>
			$firstName, thank you for signing up! Your appointment will be located at the $siteTitle site ($siteAddress). 
			Please arrive no later than $timeStr on $dateStr with all necessary materials (listed below). 
			Please call $sitePhoneNumber if you have any questions or would like to reschedule. 
			Thank you from Lincoln VITA.
			<h2 class='mt-3'>What to Bring for your Appointment</h2>";
}

function residentialInformation() {
	return "<h5>Identification:</h5>
			<ul>
				<li><b>Social Security cards</b> or <b>ITIN Letters</b> for <b>EVERYONE</b> who will be included on the return</li>
				<li><b>Photo ID</b> for <b>ALL</b> tax return signers (BOTH spouses must sign if filing jointly)</li>
			</ul>
			<h5>Health Care Coverage:</h5>
			<ul>
				<li><b>Verification</b> of health insurance (1095 A, B or C)</li>
			</ul>
			<h5>Income:</h5>
			<ul>
				<li><b>W-2s</b> for wages, <b>W-2Gs</b> for gambling income</li>
				<li><b>1099s</b> for interest, dividends, unemployment, state tax refunds, pension or 401-K distributions, and other income</li>
				<li><b>Records</b> of revenue from self-employment or home-based businesses</li>
			</ul>
			<h5>Expenses:</h5>
			<ul>
				<li><b>1098s</b> for mortgage interest, student loan interest (1098-E), or tuition (1098-T), statement of property tax paid</li>
				<li><b>Statement of college student account</b> showing all charges and payments for each student on the return</li>
				<li><b>Childcare receipts</b>, including tax ID and address for childcare provider</li>
				<li><b>Records</b> of expenses for self-employment or home-based businesses</li>
			</ul>";
}

function internationalInformation() {
	return "<h5>Identification:</h5>
			<ul>
				<li><b>Social Security card</b> or <b>ITIN Letters</b> for <b>EVERYONE</b> who will be included on the return</li>
				<li><b>Passport</b> for <b>ALL</b> tax return signers</li>
			</ul>
			<h5>Immigration Documents:</h5>
			<ul>
				<li><b>I-94</b></li>
				<li><b>I-20</b></li>
				<li><b>DS-2019</b> for those in J1 status</li>
			</ul>
			<h5>Income:</h5>
			<ul>
				<li><b>W-2s</b> for wages</li>
				<li><b>1042-S</b> (If you received one, not everyone receives one)</li>
			</ul>";
}

function miscellaneousInformation() {
	return "<h5>Miscellaneous:</h5>
			<ul>
				<li>Checking or savings account information for direct deposit/direct debit</li>
				<li>It is <b>STRONGLY RECOMMENDED</b> that you bring last year's tax return</li>
			</ul>";
}
