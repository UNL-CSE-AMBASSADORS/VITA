<!DOCTYPE html>
<html>
	<head>
		<title>Queue Test</title>
		<link rel='stylesheet' href='../css/queue.css'>
	</head>
	<body>
		<div class='header'>
			<div class='vita-volunteer-count'>Volunteers on duty: 0</div>
			<img class='vita-header-curtain' src='../res/QueueCurtain.svg'>
			<img class='vita-exit-fullscreen' src='../res/GenericExit.svg' onclick='alert("This ought to close the window")'>
		</div>


		<div class='vita-queue-wrapper'>
			<div class='vita-queue-header'>
				<div class='vita-queue-header-position'>#</div>
				<div class='vita-queue-header-name'>Name</div>
				<div class='vita-queue-header-wait'>Wait Time</div>
			</div>
			<div class='vita-queue'>
				<?php
					$server_name = 'localhost';
					$username = 'username';
					$password = 'password';
					$db_name = 'myDB';

					$conn = new mysqli($server_name, $username, $password, $db_name);
					if ($conn->connect_error) {
						die('Unable to connect to queue');
					}

		 			//TODO make this handle multiple locations.
					$stmt = $conn->prepare('SELECT id, time, firstName, lastName FROM appointment
						where (date = getdate() && archived = 0)');
					$stmt->execute();
					$results = $stmt->fetchAll();

					for ($i = 0; $i < $results.length; $i++) {
						$appointment = $results[i];
						$current_date_time = new DateTime();
						//TODO the below might get stored differently in database, might need to rework
						$appointment_date_time = DateTime.createFromFormat('Y-m-d H:i:s', $appointment.date . ' ' . $appointment.time);
						$time_difference = $current_date_time.diff($appointment_date_time);
						$wait_time = $time_difference.i + ($time_difference.h * 60);

						echo "<div class='vita-queue-entry'>
								<div class='vita-queue-entry-position'>" . ($i + 1) . "</div>
								<div class='vita-queue-entry-name'>" . $appointment.firstName . " " . substr($appointment.lastName, 0, 1) . ".</div>
								<div class='vita-queue-entry-wait'>" . $wait_time . " Minutes</div>
							</div>";
					}
				?>
			</div>
		</div>


		<div class='vita-queue-wrapper'>
			<div class='vita-queue-header'>
				<div class='vita-queue-header-position'>#</div>
				<div class='vita-queue-header-name'>Name</div>
				<div class='vita-queue-header-wait'>Wait Time</div>
			</div>
			<div class='vita-queue'>
				<div class='vita-queue-entry'>
					<div class='vita-queue-entry-position'>0</div>
					<div class='vita-queue-entry-name'>Robert L.</div>
					<div class='vita-queue-entry-wait'>0 Minutes</div>
				</div>
				<div class='vita-queue-entry'>
					<div class='vita-queue-entry-position'>0</div>
					<div class='vita-queue-entry-name'>Robert L.</div>
					<div class='vita-queue-entry-wait'>0 Minutes</div>
				</div>
				<div class='vita-queue-entry'>
					<div class='vita-queue-entry-position'>0</div>
					<div class='vita-queue-entry-name'>Robert L.</div>
					<div class='vita-queue-entry-wait'>0 Minutes</div>
				</div>
				<div class='vita-queue-entry'>
					<div class='vita-queue-entry-position'>0</div>
					<div class='vita-queue-entry-name'>Robert L.</div>
					<div class='vita-queue-entry-wait'>0 Minutes</div>
				</div>
				<div class='vita-queue-entry'>
					<div class='vita-queue-entry-position'>0</div>
					<div class='vita-queue-entry-name'>Robert L.</div>
					<div class='vita-queue-entry-wait'>0 Minutes</div>
				</div>
				<div class='vita-queue-entry'>
					<div class='vita-queue-entry-position'>0</div>
					<div class='vita-queue-entry-name'>Robert L.</div>
					<div class='vita-queue-entry-wait'>0 Minutes</div>
				</div>
				<div class='vita-queue-entry'>
					<div class='vita-queue-entry-position'>0</div>
					<div class='vita-queue-entry-name'>Robert L.</div>
					<div class='vita-queue-entry-wait'>0 Minutes</div>
				</div>
				<div class='vita-queue-entry'>
					<div class='vita-queue-entry-position'>0</div>
					<div class='vita-queue-entry-name'>Robert L.</div>
					<div class='vita-queue-entry-wait'>0 Minutes</div>
				</div>
				<div class='vita-queue-entry'>
					<div class='vita-queue-entry-position'>0</div>
					<div class='vita-queue-entry-name'>Robert L.</div>
					<div class='vita-queue-entry-wait'>0 Minutes</div>
				</div>
			</div>
		</div>
	</body>
	<script src='../js/jquery-3.1.1.min.js'></script>
	<script src='../js/queue.js'></script>
	<?php require '../php/queue.php' ?>
</html>
