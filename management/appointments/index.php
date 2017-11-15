<?php
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	
	require_once "$root/server/user.class.php";
	$USER = new User();
	if (!$USER->hasPermission('can_use_admin_tools')) {
		header("Location: /unauthorized");
		die();
	}
?>

<!DOCTYPE html>
<html class="no-js" lang="">
<head>
	<title>VITA Appointment Management</title>
	<?php require_once "$root/server/header.php" ?>
	<link rel="stylesheet" href="./appointments.css">
</head>
<body>
	<?php
		$page_subtitle = "Appointment Management";
		require_once "$root/components/nav.php";
	?>

	<div class="container pt-5">
		<h2> Download Appointment Schedule for Site </h2>
		<input type="date" id="dateInput" value="<?php echo date('Y-m-d'); ?>"/>
		<select id="siteSelect">
			<!-- Sites injected through JS -->
		</select>
		<button onclick="downloadSchedule()">Download Schedule</button>
	</div>

	<?php require_once "$root/server/footer.php" ?>
	<script src="/dist/appointments_bundle.js"></script>
</body>
</html>