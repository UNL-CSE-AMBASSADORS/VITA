<?php
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	
	require_once "$root/server/user.class.php";
	$USER = new User();
	if (!$USER->hasPermission('use_admin_tools')) {
		header("Location: /unauthorized");
		die();
	}
?>

<!DOCTYPE html>
<html class="no-js" lang="">
<head>
	<title>VITA Document Management</title>
	<?php require_once "$root/server/header.php" ?>
	<link rel="stylesheet" href="/management/documents/documents.css">
</head>
<body>
	<?php
		$page_subtitle = "Document Management";
		require_once "$root/components/nav.php";
	?>

	<div class="container pt-5">
		<h2> Download Documents </h2>
		<input type="date" id="dateInput"/>
		<select id="siteSelect">
			<!-- Sites injected through JS -->
		</select>
		<button onclick="downloadAppointmentSchedule();">Download Appointment Schedule</button>
		<button onclick="downloadVolunteerSchedule();">Download Volunteer Schedule</button>
	</div>

	<?php require_once "$root/server/footer.php" ?>
	<script src="/dist/management/documents/documents.js"></script>
</body>
</html>