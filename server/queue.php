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
