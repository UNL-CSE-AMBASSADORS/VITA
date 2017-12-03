<?php $root = realpath($_SERVER["DOCUMENT_ROOT"]) ?>
<!DOCTYPE html>
<html class="no-js" lang="">
<head>
	<title>VITA Appointment Signup</title>
	<?php require_once "$root/server/header.php" ?>
	<link rel="stylesheet" href="/assets/css/form.css">
	<link rel="stylesheet" href="/signup/signup.css">
	<link rel="stylesheet" href="/assets/css/jquery-ui-datepicker.css">
</head>
<body>
	<?php
		$page_subtitle = 'Signup for an appointment';
		require_once "$root/components/nav.php";
	?>

	<?php 
		date_default_timezone_set('America/Chicago'); // Use CST
		$now = date('Y-m-d H:i:s');
		$signupBeginsDate = '2018-01-15 00:00:00';
		if ($now < $signupBeginsDate) {
	?>
		<!-- BEFORE SIGN UP BEGINS -->
		<div class="container">
			<h2 class="pt-5">Appointment signup does not begin until January 15th, 2018. Please check back then.</h2>
		</div>
	<?php } else { ?>
		<!-- AFTER SIGN UP BEGINS -->
		<div class="container">
			<div class="row justify-content-center">
				<div class="col col-12 col-sm-8">
					<div id="responsePlaceholder" class="mt-5" style="display: none;"></div>
					<form class="cmxform mb-5" id="vitaSignupForm" method="post" action="" autocomplete="off">
						<h2 class="pt-5">Sign Up for a VITA Appointment</h2>

						<div class="form-textfield">
							<input type="text" name="firstName" id="firstName" required>
							<span class="form-bar"></span>
							<label class="form-label form-required" for="firstName">First Name</label>
						</div>

						<div class="form-textfield">
							<input type="text" name="lastName" id="lastName" required>
							<span class="form-bar"></span>
							<label class="form-label form-required" for="lastName">Last Name</label>
						</div>

						<div class="form-textfield">
							<input type="email" name="email" id="email" required>
							<span class="form-bar"></span>
							<label class="form-label form-required" for="email">Email</label>
						</div>

						<div class="form-textfield">
							<input type="text" name="phone" id="phone" required>
							<span class="form-bar"></span>
							<label class="form-label form-required" for="phone">Phone Number</label>
						</div>

						<h3 class="form-subheading">Background Information</h3>

						<div class="form-radio row">
							<label for="depreciation_schedule" class="col form-required">Will you require a Depreciation Schedule?</label>
							<div class="col btn-group" data-toggle="buttons">
								<label class="btn btn-outline-secondary" for="1yes">
									<input type="radio" id="1yes" value="1" name="1" required>Yes
								</label>
								<label class="btn btn-outline-secondary" for="1no">
									<input type="radio" id="1no" value="2" name="1" required>No
								</label>
								<label class="btn btn-outline-secondary" for="1unsure">
									<input type="radio" id="1unsure" value="3" name="1" required>Unsure
								</label>
							</div>
						</div>
						<div class="form-radio row">
							<label for="schedule_f" class="col form-required">Will you require a Schedule F (Farm)?</label>
							<div class="col btn-group" data-toggle="buttons">
								<label class="btn btn-outline-secondary" for="2yes">
									<input type="radio" id="2yes" value="1" name="2" required>Yes
								</label>
								<label class="btn btn-outline-secondary" for="2no">
									<input type="radio" id="2no" value="2" name="2" required>No
								</label>
								<label class="btn btn-outline-secondary" for="2unsure">
									<input type="radio" id="2unsure" value="3" name="2" required>Unsure
								</label>
							</div>
						</div>
						<div class="form-radio row" id="homeBased">
							<label for="self_employed" class="col form-required">Are you self-employed or own a home-based business?</label>
							<div class="col btn-group" data-toggle="buttons">
								<label class="btn btn-outline-secondary" for="3yes">
									<input type="radio" id="3yes" value="1" name="3" required>Yes
								</label>
								<label class="btn btn-outline-secondary" for="3no">
									<input type="radio" id="3no" value="2" name="3" required>No
								</label>
							</div>
						</div>
						<div class="form-radio row" id="homeBasedNetLoss" style="display: none;">
							<label for="net_loss" class="col form-required">Does your home-based business or self-employment have a net loss?</label>
							<div class="col btn-group" data-toggle="buttons">
								<label class="btn btn-outline-secondary" for="4yes">
									<input type="radio" id="4yes" value="1" name="4" required>Yes
								</label>
								<label class="btn btn-outline-secondary" for="4no">
									<input type="radio" id="4no" value="2" name="4" required>No
								</label>
								<label class="btn btn-outline-secondary" for="4unsure">
									<input type="radio" id="4unsure" value="3" name="4" required>Unsure
								</label>
							</div>
						</div>
						<div class="form-radio row" id="homeBased10000" style="display: none;">
							<label for="more_than_10000_expenses" class="col form-required">Does your home-based business or self-employment have more than $10,000 in expenses?</label>
							<div class="col btn-group" data-toggle="buttons">
								<label class="btn btn-outline-secondary" for="5yes">
									<input type="radio" id="5yes" value="1" name="5" required>Yes
								</label>
								<label class="btn btn-outline-secondary" for="5no">
									<input type="radio" id="5no" value="2" name="5" required>No
								</label>
								<label class="btn btn-outline-secondary" for="5unsure">
									<input type="radio" id="5unsure" value="3" name="5" required>Unsure
								</label>
							</div>
						</div>
						<div class="form-radio row" id="homeBasedSEP" style="display: none;">
							<label for="retirement_plans" class="col form-required">Does your home-based business or self-employment have self-employed, SEP, SIMPLE, or qualified retirement plans?</label>
							<div class="col btn-group" data-toggle="buttons">
								<label class="btn btn-outline-secondary" for="6yes">
									<input type="radio" id="6yes" value="1" name="6" required>Yes
								</label>
								<label class="btn btn-outline-secondary" for="6no">
									<input type="radio" id="6no" value="2" name="6" required>No
								</label>
								<label class="btn btn-outline-secondary" for="6unsure">
									<input type="radio" id="6unsure" value="3" name="6" required>Unsure
								</label>
							</div>
						</div>
						<div class="form-radio row" id="homeBasedEmployees" style="display: none;">
							<label for="any_employees" class="col form-required">Does your home-based business or self-employment have employees?</label>
							<div class="col btn-group" data-toggle="buttons">
								<label class="btn btn-outline-secondary" for="7yes">
									<input type="radio" id="7yes" value="1" name="7" required>Yes
								</label>
								<label class="btn btn-outline-secondary" for="7no">
									<input type="radio" id="7no" value="2" name="7" required>No
								</label>
								<label class="btn btn-outline-secondary" for="7unsure">
									<input type="radio" id="7unsure" value="3" name="7" required>Unsure
								</label>
							</div>
						</div>
						<div class="form-radio row">
							<label for="casualty_losses" class="col form-required">Will your return have casualty losses?</label>
							<div class="col btn-group" data-toggle="buttons">
								<label class="btn btn-outline-secondary" for="8yes">
									<input type="radio" id="8yes" value="1" name="8" required>Yes
								</label>
								<label class="btn btn-outline-secondary" for="8no">
									<input type="radio" id="8no" value="2" name="8" required>No
								</label>
								<label class="btn btn-outline-secondary" for="8unsure">
									<input type="radio" id="8unsure" value="3" name="8" required>Unsure
								</label>
							</div>
						</div>
						<div class="form-radio row">
							<label for="theft_losses" class="col form-required">Will your return have theft losses?</label>
							<div class="col btn-group" data-toggle="buttons">
								<label class="btn btn-outline-secondary" for="9yes">
									<input type="radio" id="9yes" value="1" name="9" required>Yes
								</label>
								<label class="btn btn-outline-secondary" for="9no">
									<input type="radio" id="9no" value="2" name="9" required>No
								</label>
								<label class="btn btn-outline-secondary" for="9unsure">
									<input type="radio" id="9unsure" value="3" name="9" required>Unsure
								</label>
							</div>
						</div>
						<div class="form-radio row">
							<label for="schedule_e" class="col form-required">Will you require a Schedule E (rental income)?</label>
							<div class="col btn-group" data-toggle="buttons">
								<label class="btn btn-outline-secondary" for="10yes">
									<input type="radio" id="10yes" value="1" name="10" required>Yes
								</label>
								<label class="btn btn-outline-secondary" for="10no">
									<input type="radio" id="10no" value="2" name="10" required>No
								</label>
								<label class="btn btn-outline-secondary" for="10unsure">
									<input type="radio" id="10unsure" value="3" name="10" required>Unsure
								</label>
							</div>
						</div>
						<div class="form-radio row">
							<label for="schedule_k-1" class="col form-required">Will you require a Schedule K-1 (partnership or trust income)?</label>
							<div class="col btn-group" data-toggle="buttons">
								<label class="btn btn-outline-secondary" for="11yes">
									<input type="radio" id="11yes" value="1" name="11" required>Yes
								</label>
								<label class="btn btn-outline-secondary" for="11no">
									<input type="radio" id="11no" value="2" name="11" required>No
								</label>
								<label class="btn btn-outline-secondary" for="11unsure">
									<input type="radio" id="11unsure" value="3" name="11" required>Unsure
								</label>
							</div>
						</div>
						<div class="form-radio row">
							<label for="dividends_income" class="col form-required">Do you have income from dividends, capital gains, or minimal brokerage transactions?</label>
							<div class="col btn-group" data-toggle="buttons">
								<label class="btn btn-outline-secondary" for="12yes">
									<input type="radio" id="12yes" value="1" name="12" required>Yes
								</label>
								<label class="btn btn-outline-secondary" for="12no">
									<input type="radio" id="12no" value="2" name="12" required>No
								</label>
								<label class="btn btn-outline-secondary" for="12unsure">
									<input type="radio" id="12unsure" value="3" name="12" required>Unsure
								</label>
							</div>
						</div>
						<div class="form-radio row">
							<label for="current_bankruptcy" class="col form-required">Will your return involve a current bankruptcy?</label>
							<div class="col btn-group" data-toggle="buttons">
								<label class="btn btn-outline-secondary" for="13yes">
									<input type="radio" id="13yes" value="1" name="13" required>Yes
								</label>
								<label class="btn btn-outline-secondary" for="13no">
									<input type="radio" id="13no" value="2" name="13" required>No
								</label>
								<label class="btn btn-outline-secondary" for="13unsure">
									<input type="radio" id="13unsure" value="3" name="13" required>Unsure
								</label>
							</div>
						</div>
						<div class="form-radio row">
							<label for="multiple_states" class="col form-required">Will your return involve income from more than one state?</label>
							<div class="col btn-group" data-toggle="buttons">
								<label class="btn btn-outline-secondary" for="14yes">
									<input type="radio" id="14yes" value="1" name="14" required>Yes
								</label>
								<label class="btn btn-outline-secondary" for="14no">
									<input type="radio" id="14no" value="2" name="14" required>No
								</label>
								<label class="btn btn-outline-secondary" for="14unsure">
									<input type="radio" id="14unsure" value="3" name="14" required>Unsure
								</label>
							</div>
						</div>

						<div class="form-radio row" id="studentUNL">
							<label for="15" class="col form-required">Are you a University of Nebraska-Lincoln student?</label>
							<div class="col btn-group" data-toggle="buttons">
								<label class="btn btn-outline-secondary" for="studentyes">
									<input type="radio" id="studentyes" value="1" name="15" required>Yes
								</label>
								<label class="btn btn-outline-secondary" for="studentno">
									<input type="radio" id="studentno" value="2" name="15" required>No
								</label>
							</div>
						</div>

						<div class="form-radio row" id="studentInt" style="display: none;">
							<label for="16" class="col">Are you an International Student Scholar?</label>
							<div class="col btn-group" data-toggle="buttons">
								<label class="btn btn-outline-secondary" for="studentIntyes">
									<input type="radio" id="studentIntyes" value="1" name="16" required>Yes
								</label>
								<label class="btn btn-outline-secondary" for="studentIntno">
									<input type="radio" id="studentIntno" value="2" name="16" required>No
								</label>
							</div>
						</div>

						<div class="form-radio row" id="studentIntVisa" style="display: none;">
							<label for="17" class="col">What sort of visa are you on?</label>
							<div class="col btn-group" data-toggle="buttons">
								<label class="btn btn-outline-secondary" for="f1">
									<input type="radio" id="f1" value="4" name="17" required>F-1
								</label>
								<label class="btn btn-outline-secondary" for="j1">
									<input type="radio" id="j1" value="5" name="17" required>J-1
								</label>
								<label class="btn btn-outline-secondary" for="h1b">
									<input type="radio" id="h1b" value="6" name="17" required>H1B
								</label>
							</div>
						</div>

						<div class="form-radio row" id="studentf1" style="display: none;">
							<label for="18" class="col">How long have you been in the United States?</label>
							<div class="col btn-group" data-toggle="buttons">
								<label class="btn btn-outline-secondary" for="2011">
									<input type="radio" id="2011" value="7" name="18" required>2011 or earlier
								</label>
								<label class="btn btn-outline-secondary" for="2012">
									<input type="radio" id="2012" value="8" name="18" required>2012 or later
								</label>
							</div>
						</div>

						<div class="form-radio row" id="studentj1" style="display: none;">
							<label for="18" class="col">How long have you been in the United States?</label>
							<div class="col btn-group" data-toggle="buttons">
								<label class="btn btn-outline-secondary" for="2014">
									<input type="radio" id="2014" value="9" name="18" required>2014 or earlier
								</label>
								<label class="btn btn-outline-secondary" for="2015">
									<input type="radio" id="2015" value="10" name="18" required>2015 or later
								</label>
							</div>
						</div>

						<div class="form-radio row" id="studenth1b" style="display: none;">
							<label for="19" class="col">Have you been on this visa for less than 183 days and in the United States for less than 5 years?</label>
							<div class="col btn-group" data-toggle="buttons">
								<label class="btn btn-outline-secondary" for="studenth1byes">
									<input type="radio" id="studenth1byes" value="1" name="19" required>Yes
								</label>
								<label class="btn btn-outline-secondary" for="studenth1bno">
									<input type="radio" id="studenth1bno" value="2" name="19" required>No
								</label>
							</div>
						</div>

						<h3 class="form-subheading">Appointment Information</h3>

						<div id="appointmentPicker">
							<div id="studentScholarAppointmentPicker" style="display:none">
								Student Scholar
							</div>
							<div id="datePicker" class="form-textfield">
								<input type="text" id="dateInput" name="dateInput" placeholder="Select a Date" required>
								<label class="form-label form-required form-label__always-floating">Date</label>
							</div>
							<div id="sitePicker" class="form-select" style="display: none;">
								<label class="form-label form-required" for="sitePickerSelect">Site</label>
								<select id="sitePickerSelect" name="sitePickerSelect" required></select>
								<div class="form-select__arrow"></div>
							</div>
							<div id="timePicker" class="form-select" style="display: none;">
								<label class="form-label form-required" for="timePickerSelect">Time</label>
								<select id="timePickerSelect" name="timePickerSelect" required></select>
								<div class="form-select__arrow"></div>
							</div>
						</div>

						<input type="submit" value="Submit" class="submit btn btn-primary mb-5 vita-background-primary">
					</form>
				</div>
			</div>
		</div>
	<?php } ?> 

	<?php require_once "$root/server/footer.php" ?>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/jquery.validate.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/additional-methods.min.js"></script>
	<script src="/signup/signup.js"></script>
	<script src="/assets/js/form.js"></script>
</body>
</html>
