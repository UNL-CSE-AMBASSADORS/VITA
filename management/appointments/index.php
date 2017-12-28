<!DOCTYPE html>
<html class="no-js" lang="">
<head>
	<title>VITA Appointment Management</title>
	<?php 
		$root = realpath($_SERVER['DOCUMENT_ROOT']);
		require_once "$root/server/header.php" 
	?>
</head>
<body>
	<?php
		$page_subtitle = "Appointment Management";
		require_once "$root/components/nav.php";
	?>

	<div class="container">
		<div class="row justify-content-center">
			
		</div>
	</div>

	<?php require_once "$root/server/footer.php" ?>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
	<script src="/management/appointments/appointments.js"></script>
</body>
</html>