<?php $root = realpath($_SERVER["DOCUMENT_ROOT"]) ?>
<!DOCTYPE html>
<html class="no-js" lang="">
<head>
	<title>VITA Appointment Management</title>
	<?php require_once "$root/server/header.php" ?>
	<link rel="stylesheet" href="/management/appointments/appointments.css">
</head>
<body>
	<?php
		$page_subtitle = "Appointment Management";
		require_once "$root/components/nav.php";
	?>

	<div class="container">
		<h2> Download Appointment Schedule for Site </h2>
		<input type="date" id="dateInput" />
		<select id="siteSelect">
			<?php
				require_once "$root/server/config.php";
				GLOBAL $DB_CONN;
				
				$query = "SELECT siteId, title FROM Site";
				$stmt = $DB_CONN->prepare($query);

				$stmt->execute();
				$sites = $stmt->fetchAll(PDO::FETCH_ASSOC);

				for ($i = 0; $i < count($sites); $i++) {
					print('<option value="' . $sites[$i]['siteId'] . '">' . $sites[$i]['title'] . '</option>');
				}
			?>
		</select>
		<a href="#" onclick="downloadCsv();">Download CSV</a>
	</div>

	<?php require_once "$root/server/footer.php" ?>
	<script src="/management/appointments/appointments.js"></script>
</body>
</html>