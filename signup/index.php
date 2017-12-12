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
		// TODO, THIS CODE WILL NEED TO BE REMOVED ONCE APPOINTMENT SIGN UP ACTUALLY STARTS
		date_default_timezone_set('America/Chicago'); // Use CST
		$now = date('Y-m-d H:i:s');
		$signupBeginsDate = '2018-01-15 00:00:00';
		require_once "$root/server/user.class.php";
		$USER = new User();
		if ($now < $signupBeginsDate && !$USER->isLoggedIn()) {
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
	<script src="/dist/signup/signup.js"></script>
	<script src="/dist/assets/js/form.js"></script>
</body>
</html>
