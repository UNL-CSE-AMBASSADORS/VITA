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
	<link rel="stylesheet" href="/profile/profile.css">
</head>
<body>
	<?php
		$page_subtitle = "Profile";
		require_once "$root/components/nav.php";
	?>

	<div class="container pt-5">
		<h2>First Name:</h2><p id="firstName"></p>
		<h2>Last Name:</h2><p id="lastName"></p>
		<h2>Email:</h2><p id="email"></p>
		<h2>Phone number:</h2><p id="phoneNumber"></p>

		<h2>Abilities</h2>
		<div id="abilitiesDiv">

		</div>

		<h2>Abilities That Require Verification</h2>
		<div id ="abilitiesRequiringVerificationDiv">

		</div>

		<h2>Shifts</h2>
		<div id="shiftsDiv">

		</div>
	</div>

	<?php require_once "$root/server/footer.php" ?>
	<!-- TODO NEED TO CHANGE THIS TO DIST -->
	<script src="/profile/profile.js"></script>
</body>
</html>