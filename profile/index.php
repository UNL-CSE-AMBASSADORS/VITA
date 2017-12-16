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
		<h2>Personal Information</h2>
		<div>
			<label for="firstName">First Name:</label>
			<input type="text" id="firstName" class="col-md-6" />
			<button class="btn btn-primary col-md-2" id="firstNameSaveButton">Save</button>
		</div>

		<div>
			<label for="lastName">Last Name:</label>
			<input type="text" id="lastName" class="col-md-6" />
			<button class="btn btn-primary col-md-2" id="lastNameSaveButton">Save</button>
		</div>

		<div>
			<label for="email">Email:</label>
			<input type="text" id="email" class="col-md-6" />
			<button class="btn btn-primary col-md-2" id="emailSaveButton">Save</button>
		</div>

		<div>
			<label for="phoneNumber">Phone Number:</label>
			<input type="text" id="phoneNumber" class="col-md-6" />
			<button class="btn btn-primary col-md-2" id="phoneNumberSaveButton">Save</button>
		</div>

		<h2>Abilities</h2>
		<select id="abilitiesSelect" class='userAbilitiesSelectPicker' multiple=true>
			
		</select>

		<h2>Abilities That Require Verification</h2>
		<div id ="abilitiesRequiringVerificationDiv">

		</div>

		<h2>Shifts</h2>
		<select id="shiftsSelect" class='userShiftsSelectPicker' multiple=true>
			
		</select>

		<h2 pb-5>TEST</h2>
		<h2 pb-5>TEST</h2>
		<h2 pb-5>TEST</h2>
		<h2 pb-5>TEST</h2>
		<h2 pb-5>TEST</h2>
		<h2 pb-5>TEST</h2>
	</div>

	<?php require_once "$root/server/footer.php" ?>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>
	<!-- TODO NEED TO CHANGE THIS TO DIST -->
	<script src="/profile/profile.js"></script>
</body>
</html>