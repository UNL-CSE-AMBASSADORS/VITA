<?php $root = realpath($_SERVER["DOCUMENT_ROOT"]) ?>
<!DOCTYPE html>
<html class="no-js theme-light" lang="" ng-app="queueApp">
<head>
	<title>Queue Test</title>
	<?php require_once "$root/server/header.php" ?>
	<link rel="stylesheet" href="/queue/queue.css">
</head>
<body ng-controller="QueueController">
	<!--[if lt IE 8]>
		<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
	<![endif]-->
	<?php
		require_once "$root/components/nav.php";
	?>

	<!-- Header section -->
	<?php
		require_once "$root/queue/queue_header.php";
	?>

	<!-- Body Section with list of clients -->
	<div class="container-fluid queue" ng-if="appointments.length > 0" ng-cloak>
		<div class="row queue-header py-2 bg-secondary text-light font-weight-bold">
			<div class="col col-1 queue-id">Id.</div>
			<div class="col col-8 queue-name">Name</div>
			<div class="col col-3 queue-time">Time</div>
		</div>
		<div class="row queue-row" ng-repeat="appointment in appointments | orderBy:'scheduledTime'" ng-class-even="'bg-light'">
			<div class="col col-1 queue-id">{{appointment.appointmentId}}</div>
			<div class="col col-8 queue-name">{{appointment.firstName}} {{appointment.lastName}}.</div>
			<div class="col col-3 queue-time">{{appointment.scheduledTime | date: "h:mm a"}}</div>
		</div>
	</div>
	<div class="container-fluid queue" ng-if="appointments.length == 0" ng-cloak>
		<div class="row d-flex justify-content-center">
			<div class="my-5">
				There are no appointments on this day.
			</div>
		</div>
	</div>

	<?php require_once "$root/server/footer.php" ?>
	<?php require_once "$root/server/angularjs_dependencies.php" ?>
	<script src="/dist/queue/queue.js"></script>
	<script src="/dist/queue/queue_service.js"></script>
</body>
</html>
