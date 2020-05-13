<?php
$root = realpath($_SERVER['DOCUMENT_ROOT']);
require_once "$root/server/config.php";

class AppointmentConfirmationUtilities {

	public static function generateAppointmentConfirmation($appointmentId) {
		$data = self::getAppointmentInformation($appointmentId);
	
		$firstName = $data['firstName'];
		$siteTitle = $data['title'];
		$siteAddress = $data['address'];
		$sitePhoneNumber = $data['phoneNumber'];
		$dateStr = $data['dateStr'];
		$timeStr = $data['timeStr'];
		$doesInternational = $data['doesInternational'];
		$isVirtualAppointment = $data['isVirtual'];
		$selfServiceAppointmentRescheduleToken = $data['token'];

		if ($isVirtualAppointment) {
			$message = self::virtualAppointmentIntroductionInformation($firstName, $dateStr);
			$message .= self::virtualAppointmentUploadDocumentsInformation($selfServiceAppointmentRescheduleToken);
		} else {
			$message = self::introductionInformation($firstName, $siteTitle, $siteAddress, $timeStr, $dateStr, $sitePhoneNumber);
			if ($doesInternational) {
				// If it is an international appointment, there is a different list of what to brings than for residential appointments
				$message .= self::internationalInformation(); 
			} else {
				$message .= self::residentialInformation();
			}
			$message .= self::miscellaneousInformation();
			$message .= self::selfServiceAppointmentRescheduleInformation($selfServiceAppointmentRescheduleToken);
		}
	
		return $message;
	}

	private static function virtualAppointmentIntroductionInformation($firstName, $dateStr) {
		return "<h2>Appointment Confirmation</h2>
				$firstName, thank you for signing up for a virtual VITA appointment!
				<b>You now need to upload your documents to have your taxes prepared </b> (see the instructions below for uploading your documents).
				After your documents have been received, a tax preparer will start preparing your taxes the week of $dateStr. 
				Please email vita@unl.edu if you have any questions.
				Thank you from Lincoln VITA.";
	}

	private static function virtualAppointmentUploadDocumentsInformation($selfServiceAppointmentRescheduleToken) {
		$serverName = $_SERVER['SERVER_NAME'];
		$uploadDocumentsLink = "https://$serverName/appointment/upload-documents/?token=$selfServiceAppointmentRescheduleToken";
		return "<h2 class='dcf-mt-2'>Uploading Your Documents</h2>
				Please visit <a href='$uploadDocumentsLink' target='_blank'>the upload documents page</a> to upload the necessary documents to have your taxes prepared. 
				If the link is not working, you can copy and paste this link into your browser: $uploadDocumentsLink";
	}
	
	private static function getAppointmentInformation($appointmentId) {
		GLOBAL $DB_CONN;
	
		$query = "SELECT Site.address, Site.phoneNumber, Site.title, 
				Site.isVirtual, Site.doesInternational, Client.firstName, 
				SelfServiceAppointmentRescheduleToken.token, 
				DATE_FORMAT(scheduledTime, '%W, %M %D, %Y') AS dateStr, 
				TIME_FORMAT(scheduledTime, '%l:%i %p') as timeStr
			FROM Appointment
			JOIN Client ON Appointment.clientId = Client.clientId
			JOIN SelfServiceAppointmentRescheduleToken ON Appointment.appointmentId = SelfServiceAppointmentRescheduleToken.appointmentId
			JOIN AppointmentTime ON Appointment.appointmentTimeId = AppointmentTime.appointmentTimeId
			JOIN Site ON AppointmentTime.siteId = Site.siteId
			WHERE Appointment.appointmentId = ?";
	
		$stmt = $DB_CONN->prepare($query);
		$stmt->execute(array($appointmentId));
	
		return $stmt->fetch();
	}
	
	private static function introductionInformation($firstName, $siteTitle, $siteAddress, $timeStr, $dateStr, $sitePhoneNumber) {
		return "<h2>Appointment Confirmation</h2>
				$firstName, thank you for signing up! Your appointment will be located at the $siteTitle site ($siteAddress). 
				Please arrive no later than $timeStr on $dateStr with all necessary materials (listed below). 
				Please call $sitePhoneNumber if you have any questions. 
				Thank you from Lincoln VITA.
				<h2 class='dcf-mt-3'>To Have Your Taxes Prepared, Bring:</h2>";
	}
	
	private static function residentialInformation() {
		return "<h5>Identification:</h5>
				<ul>
					<li><b>Social Security Cards</b> or <b>ITIN Letters</b> for EVERYONE who will be included on the return</li>
					<li>Photo ID for ALL tax return signers (BOTH spouses must sign if filing jointly)</li>
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
					<li><b>1095s</b> showing creditable health insurance coverage</li>
					<li><b>Records</b> of expenses for self-employment or home-based businesses</li>
				</ul>";
	}
	
	private static function internationalInformation() {
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
	
	private static function miscellaneousInformation() {
		return "<h5>Miscellaneous:</h5>
				<ul>
					<li>Checking or savings account information for direct deposit/direct debit</li>
					<li>It is <b>REQUIRED</b> that you bring last year's tax return for MyFreeTaxes self-preparation</li>
				</ul>";
	}

	private static function selfServiceAppointmentRescheduleInformation($selfServiceAppointmentRescheduleToken) {
		$serverName = $_SERVER['SERVER_NAME'];
		$selfServiceAppointmentRescheduleLink = "https://$serverName/appointment/reschedule?token=$selfServiceAppointmentRescheduleToken";
		return "<h2>Rescheduling or Cancelling Your Appointment</h2>
				You can reschedule or cancel your appointment by visiting 
				<a href='$selfServiceAppointmentRescheduleLink' target='_blank'>the appointment reschedule page</a>";
	}
}
