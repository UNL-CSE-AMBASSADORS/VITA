<?php $root = realpath($_SERVER["DOCUMENT_ROOT"]) ?>
<!DOCTYPE html>
<html class="no-js theme-light" lang="" ng-app="queueApp">
<head>
	<title>Queue Test</title>
	<?php require_once "$root/server/header.php" ?>
	<link rel="stylesheet" href="/queue/queue_public.css">
	<!-- Angular Material style sheet -->
	<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/angular_material/1.1.4/angular-material.min.css">
	<!-- AngularJS -->
	<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular-animate.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular-aria.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular-messages.min.js"></script>
	<!-- Angular Material Library -->
	<script src="https://ajax.googleapis.com/ajax/libs/angular_material/1.1.4/angular-material.min.js"></script>
	<script src="/queue/queue_public.js"></script>
	<script src="/queue/queue_service.js"></script>
</head>
<body ng-controller="QueueController">
	<!--[if lt IE 8]>
		<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
	<![endif]-->
	<?php
		require_once "$root/components/nav.php";
	?>

	<!-- Header section -->
	<div class="container-fluid dashboard bg-light py-3" ng-cloak>
		<div class="d-flex flex-column flex-sm-row justify-content-sm-between justify-content-center align-items-center">
			<div class="d-flex flex-row pb-3 pb-sm-0">
				<div class="queue-size-lbl">Queue:</div>
				<div class="queue-size-count">{{appointments.length}}</div>
			</div>
			<md-datepicker
				ng-model="currentDate"
				ng-change="updateAppointmentInformation()"
				md-placeholder="Enter date"
				md-min-date="today"
				md-hide-icons="calendar">
			</md-datepicker>
			<div class="d-none d-sm-flex flex-row align-items-center">
				<div class="clock-time">{{updateTime | date: "h:mm"}}</div>
				<div class="clock-period d-flex flex-column align-items-center ml-1">
					<div class="clock-am" ng-class="isAm ? '':'inactive-period'">AM</div>
					<div class="clock-pm" ng-class="isAm ? 'inactive-period':''">PM</div>
				</div>
			</div>
		</div>
	</div>

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
</body>
</html>
