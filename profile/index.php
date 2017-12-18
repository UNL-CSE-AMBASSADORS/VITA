<?php
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	
	require_once "$root/server/user.class.php";
	$USER = new User();
	if (!$USER->isLoggedIn()) {
		header("Location: /unauthorized");
		die();
	}
?>

<!DOCTYPE html>
<html class="no-js" lang="">
<head>
	<title>VITA Profile</title>
	<?php require_once "$root/server/header.php" ?>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css" />
	<link rel="stylesheet" href="/profile/profile.css">
</head>
<body>
	<?php
		$page_subtitle = "Profile";
		require_once "$root/components/nav.php";
	?>

	<div class="container pt-5">
		<div class="personal-info">
			<div class="heading-group">
				<h2>Personal Information</h2>
				<button class="btn btn-secondary" id="personalInformationEditButton">Edit</button>
			</div>
			<div>
				<label for="firstName" id="firstNameLabel">First Name:</label>
				<p id="firstNameText"></p>
				<input type="text" id="firstNameInput" style="display:none;" />
			</div>

			<div>
				<label for="lastName" id="lastNameLabel">Last Name:</label>
				<p id="lastNameText"></p>
				<input type="text" id="lastNameInput" style="display:none;" />
			</div>

			<div>
				<label for="email" id="emailLabel">Email:</label>
				<p id="emailText"></p>
				<input type="text" id="emailInput" style="display:none;" />
			</div>

			<div>
				<label for="phoneNumber" id="phoneNumberLabel">Phone Number:</label>
				<p id="phoneNumberText"></p>
				<input type="text" id="phoneNumberInput" style="display:none;" />
			</div>

			<button class="btn btn-primary" id="personalInformationSaveButton" style="display:none;">Save</button>
			<button class="btn btn-secondary" id="personalInformationCancelButton" style="display:none;">Cancel</button>
		</div>

		<div>
			<h2 class="mt-5">Abilities</h2>
			<select id="abilitiesSelect" class='userAbilitiesSelectPicker' multiple=true></select>
		</div>	
			
		<div>
			<h2 class="mt-5">Certifications</h2>
			<p><b>NOTE</b>: These must be verified by your site administrator.</p>
			<div id ="certificationsDiv"></div>
		</div>

		<div>
			<h2 class="mt-5">Shifts You're Signed Up For</h2>
			<div id="shifts">
				<div id="shiftsSignedUpFor"></div>
			</div>
			<button class="btn btn-primary mb-5 mt-3" id="addShiftButton">+ Sign Up for a Shift</button>
		</div>
	</div>

	<?php require_once "$root/server/footer.php" ?>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>
	<script src="/dist/profile/profile.js"></script>
</body>
</html>