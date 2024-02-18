<?php
$root = realpath($_SERVER['DOCUMENT_ROOT']);
require_once "$root/server/config.php";
require_once "$root/server/utilities/appointmentTypeUtilities.class.php";

class AppointmentConfirmationUtilities {

	public static function generateAppointmentConfirmation($appointmentId) {
		$data = self::getAppointmentInformation($appointmentId);
	
		$firstName = $data['firstName'];
		$siteTitle = $data['title'];
		$siteAddress = $data['address'];
		$sitePhoneNumber = $data['phoneNumber'];
		$dateStr = $data['dateStr'];
		$timeStr = $data['timeStr'];
		$selfServiceAppointmentRescheduleToken = $data['token'];
		$isInternationalAppointment = AppointmentTypeUtilities::isInternationalAppointmentType($data['appointmentType']);
		$isVirtualAppointment = AppointmentTypeUtilities::isVirtualAppointmentType($data['appointmentType']);
		$isFsaAppointment = AppointmentTypeUtilities::isFsaAppointment($data['title']);
		$isInternationalFSA = AppointmentTypeUtilities::isInternationalFsaAppointment($data['title'], $data['appointmentType']);
		$isDomesticFSA = AppointmentTypeUtilities::isDomesticFsaAppointment($data['title']);
		$isDropoff = AppointmentTypeUtilities::isDropoffAppointment($data['title']);

		if ($isInternationalFSA) {
			$message =  self::internationalFSATemplate($firstName, $siteTitle, $siteAddress, $timeStr, $dateStr, $sitePhoneNumber, $selfServiceAppointmentRescheduleToken);
		  $message .= self::selfServiceAppointmentRescheduleInformation($selfServiceAppointmentRescheduleToken);
			$message .= self::fsaInformation();
			return $message;
		}
		if ($isDomesticFSA) {
			$message =  self::domesticFSATemplate($firstName, $siteTitle, $siteAddress, $timeStr, $dateStr, $sitePhoneNumber, $selfServiceAppointmentRescheduleToken);
		  $message .= self::selfServiceAppointmentRescheduleInformation($selfServiceAppointmentRescheduleToken);
			$message .= self::fsaInformation();
			return $message;
		}
		if ($isDropoff) {
			$message =  self::dropoffTemplate($firstName, $siteTitle, $siteAddress, $timeStr, $dateStr, $sitePhoneNumber, $selfServiceAppointmentRescheduleToken);
		  $message .= self::selfServiceAppointmentRescheduleInformation($selfServiceAppointmentRescheduleToken);
			return $message;
		}
		if ($isVirtualAppointment) {
			$message = self::virtualAppointmentIntroductionInformation($firstName, $dateStr);
			$message .= self::virtualAppointmentUploadDocumentsInformation($selfServiceAppointmentRescheduleToken);
			if ($isFsaAppointment) {
				$message .= self::fsaInformation();
			}
		} else {
			$message = self::introductionInformation($firstName, $siteTitle, $siteAddress, $timeStr, $dateStr, $sitePhoneNumber);
			if ($isInternationalAppointment) {
				// If it is an international appointment, there is a different list of what to brings than for residential appointments
				$message .= self::internationalInformation(); 
			} else {
				$message .= self::residentialInformation();
			}
			$message .= self::miscellaneousInformation();
		}
		$message .= self::selfServiceAppointmentRescheduleInformation($selfServiceAppointmentRescheduleToken);
		return $message;
	}

	private static function getAppointmentInformation($appointmentId) {
		GLOBAL $DB_CONN;
	
		$query = "SELECT Site.address, Site.phoneNumber, Site.title, 
				Client.firstName, SelfServiceAppointmentRescheduleToken.token, 
				DATE_FORMAT(scheduledTime, '%W, %M %D, %Y') AS dateStr, 
				TIME_FORMAT(scheduledTime, '%l:%i %p') AS timeStr,
				AppointmentType.lookupName AS appointmentType
			FROM Appointment
			JOIN Client ON Appointment.clientId = Client.clientId
			JOIN SelfServiceAppointmentRescheduleToken ON Appointment.appointmentId = SelfServiceAppointmentRescheduleToken.appointmentId
			JOIN AppointmentTime ON Appointment.appointmentTimeId = AppointmentTime.appointmentTimeId
			JOIN AppointmentType ON AppointmentTime.appointmentTypeId = AppointmentType.appointmentTypeId
			JOIN Site ON AppointmentTime.siteId = Site.siteId
			WHERE Appointment.appointmentId = ?";
	
		$stmt = $DB_CONN->prepare($query);
		$stmt->execute(array($appointmentId));
	
		return $stmt->fetch();
	}

	private static function virtualAppointmentIntroductionInformation($firstName, $dateStr) {
		return "<h2>Appointment Confirmation</h2>
				$firstName, thank you for signing up for a virtual VITA appointment!
				<b>You now need to upload your documents to have your taxes prepared </b> (see the instructions below for uploading your documents).
				After your documents have been received, a tax preparer will start preparing your taxes the week of $dateStr. 
				Your site coordinator or return preparer will be in contact with you via email or phone, possibly from a restricted caller (*67) number.
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

	private static function fsaInformation() {
		$serverName = $_SERVER['SERVER_NAME'];
		$instructionsPdfLink = "https://$serverName/server/download/documents/2024_1040_IRS_Instructions.pdf";
		$howtoPdfLink = "https://$serverName/server/download/documents/2024_1040_How_to_Guide.pdf";
		return "<h2 class='dcf-mt-2'>Additional Documents for FSA</h2>
				Please see the <a href='$instructionsPdfLink'>Instruction PDF</a> and the <a href='$howtoPdfLink'>How to Guide.</a>";
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
					<li><b>1099s</b> for interest, dividends, unemployment, state tax refunds, pension or 401-K distributions, and other income</li>
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
				<a href='$selfServiceAppointmentRescheduleLink' target='_blank'>the appointment reschedule page.</a>";
	}

	private static function domesticFSATemplate($firstName, $siteTitle, $siteAddress, $timeStr, $dateStr, $sitePhoneNumber, $selfServiceAppointmentRescheduleToken) {
		$serverName = $_SERVER['SERVER_NAME'];
		$uploadDocumentsLink = "https://$serverName/appointment/upload-documents/?token=$selfServiceAppointmentRescheduleToken";
		return "$firstName, thank you for signing up! You have selected to do your return through Facilitated Self Assistance (FSA). 
				You will prepare your return yourself using the following link $uploadDocumentsLink. A do-it yourself instruction packet is attached. 
				If you would like one of our preparers to quality review your return or if you have questions, please contact us via email.  VITA Volunteers are looking forward to serving with you.
				<h4>To Prepare your taxes you will need the following:</h4>
				<h5>Identification:</h5>
				<ul>
					<li>Social Security Numbers or ITIN Numbers for EVERYONE who will be included on the return</li>
					<li>Drivers License or State ID if you have one for ALL tax return signers (BOTH spouses must sign if filing jointly)</li>
				</ul>
				<h5>Income:</h5>
				<ul>
					<li>W-2s for wages, W-2Gs for gambling income</li>
					<li>1099s for interest, dividends, unemployment, state tax refunds, pension or 401-K distributions, and other income</li>
					<li>Records of revenue from self-employment or home-based businesses</li>
				</ul>
				<h5>Expenses:</h5>
				<ul>
					<li>1098s for mortgage interest, student loan interest (1098-E), or tuition (1098-T), statement of property tax paid</li>
					<li>Statement of college student account showing all charges and payments for each student on the return</li>
					<li>Childcare receipts, including tax ID and address for childcare provider</li>
					<li>1095s showing creditable health insurance coverage</li>
					<li>Records of expenses for self-employment or home-based businesses</li>
				</ul>
				<h5>Miscellaneous:</h5>
				<ul>
					<li>Checking or savings account information for direct deposit/direct debit</li>
					<li>If you own property in Nebraska, bring you real estate tax statement</li>
					<li>Last year's tax return</li>
				</ul>";
	}

	private static function internationalFSATemplate($firstName, $siteTitle, $siteAddress, $timeStr, $dateStr, $sitePhoneNumber, $selfServiceAppointmentRescheduleToken) {
		$serverName = $_SERVER['SERVER_NAME'];
		$uploadDocumentsLink = "https://$serverName/appointment/upload-documents/?token=$selfServiceAppointmentRescheduleToken";
		return "$firstName, thank you for signing up! You have selected to do your return through Facilitated Self Assistance (FSA). 
				You will prepare your federal return yourself using the following link $uploadDocumentsLink. 
				A do-it yourself instruction packet is attached. Your state return will need to be completed at the Nebraska Department of Revenue. 
				If you plan to attend your appointment, you must have your return completed to have it reviewed or minimally started to ask questions. 
				Please attend your scheduled appointment at $siteAddress. VITA Volunteers are looking forward to serving with you.
				<h4>To Prepare your taxes you will need the following:</h4>
				<h5>Identification:</h5>
				<ul>
					<li>Social Security Number or ITIN Number</li>
					<li>Drivers License, State ID or Passport</li>
				</ul>
				<h5>Income:</h5>
				<ul>
					<li>W-2s for wages, W-2Gs for gambling income</li>
					<li>1099s for interest, dividends, unemployment, state tax refunds, pension or 401-K distributions, and other income</li>
					<li>Records of revenue from self-employment or home-based businesses</li>
					<li>1042-S for Scholarship/Income</li>
				</ul>
				<h5>Expenses:</h5>
				<ul>
					<li>1098s student loan interest (1098-E)</li>
					<li>Records of expenses for self-employment or home-based businesses</li>
				</ul>
				<h5>Miscellaneous:</h5>
				<ul>
					<li>Checking or savings account information for direct deposit/direct debit</li>
					<li>Last year's tax return/Pin Number</li>
				</ul>";
	}

	private static function dropoffTemplate($firstName, $siteTitle, $siteAddress, $timeStr, $dateStr, $sitePhoneNumber, $selfServiceAppointmentRescheduleToken) {
		return "$firstName, thank you for signing up! This is a drop-off appointment.  You will drop your documents off at $siteTitle ($siteAddress). 
				Please drop your documents off no later than $timeStr on $dateStr, with all necessary materials (listed below). 
				If all of your documents are complete, your return will be ready for review and pick the following Friday after 10am. Please call or email if you have any questions. 
				VITA Volunteers are looking forward to serving with you.
				<h4>To Have Your Taxes Prepared, Please Include:</h4>
				<h5>Identification:</h5>
				<ul>
					<li>Copies of Social Security Cards or ITIN Letters for EVERYONE who will be included on the return</li>
					<li>Copies of Photo ID for ALL tax return signers (BOTH spouses must sign if filing jointly)</li>
				</ul>
				<h5>Income:</h5>
				<ul>
					<li>W-2s for wages, W-2Gs for gambling income</li>
					<li>1099s for interest, dividends, unemployment, state tax refunds, pension or 401-K distributions, and other income</li>
					<li>Records of revenue from self-employment or home-based businesses</li>
				</ul>
				<h5>Expenses:</h5>
				<ul>
					<li>1098s for mortgage interest, student loan interest (1098-E), or tuition (1098-T), statement of property tax paid</li>
					<li>Statement of college student account showing all charges and payments for each student on the return</li>
					<li>Childcare receipts, including tax ID and address for childcare provider</li>
					<li>1095s showing creditable health insurance coverage</li>
					<li>Records of expenses for self-employment or home-based businesses</li>
				</ul>
				<h5>Miscellaneous:</h5>
				<ul>
					<li>Checking or savings account information for direct deposit/direct debit</li>
					<li>If you own property in Nebraska, bring you real estate tax statement</li>
					<li>Last year's tax return</li>
				</ul>";
	}

}
