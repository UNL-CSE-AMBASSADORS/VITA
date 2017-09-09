<?php $root = realpath($_SERVER["DOCUMENT_ROOT"]) ?>
<!DOCTYPE html>
<html class="no-js theme-light" lang="" ng-app="queueApp">
<head>
	<title>Queue Test</title>
	<?php require_once "$root/server/header.php" ?>
	<link rel="stylesheet" href="/queue/queue.css">
	<link rel="stylesheet" href="/queue/queue_private.css">
	<?php require_once "$root/server/angularjs_dependencies.php" ?>
	<script src="/queue/queue.js"></script>
	<script src="/queue/queue_service.js"></script>
	<script src="/queue/queue_private.js"></script>
</head>
<body ng-controller="QueuePrivateController">
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

	<!-- Body Section -->
	<div class="relative-wrapper clearfix">
		<div class="fill-remaining d-flex flex-column flex-md-row">

			<!-- Queue Section -->
			<div class="queue-scroll-section container-fluid" ng-cloak>
				<!-- Search box -->
				<div class="queue-search py-3">
					<input class="w-100" type="text" ng-model="clientSearch" placeholder="Search for a client by name or appointment ID" />
				</div>
				<!-- List of clients -->
				<div class="queue" ng-if="appointments.length > 0" ng-cloak>
					<div class="row queue-row py-1 pointer"
							 ng-repeat="appointment in appointments | orderBy:'scheduledTime' | searchFor: clientSearch"
							 ng-class-odd="'bg-light'"
							 ng-click="selectClient(appointment)">
						<div class="col">
							<div class="d-flex flex-column">
								<div class="d-flex flex-nowrap justify-content-between">
									<div class="queue-name font-weight-bold">{{appointment.firstName}} {{appointment.lastName}}.</div>
									<div class="queue-time">{{appointment.scheduledTime | date: "h:mm a"}}</div>
								</div>
								<div class="d-flex flex-nowrap justify-content-between">
									<div class="queue-id">#{{appointment.appointmentId}}</div>
									<div class="queue-status">
										<span class="badge badge-pill badge-primary">Checked-in</span>
										<span class="badge badge-pill badge-primary">Task 2</span>
									</div>
								</div>
							</div>
						</div>
					</div>
					<p ng-show="(appointments | searchFor: clientSearch).length == 0">No results for "{{clientSearch}}"</p>
					<!-- <p ng-hide="filteredBars.length">Nothing here!</p> -->
				</div>
				<!-- Default message if there are no appointments on the selected date -->
				<div class="queue" ng-if="appointments.length == 0" ng-cloak>
					<div class="row d-flex justify-content-center">
						<div class="my-5">
							There are no appointments on this day.
						</div>
					</div>
				</div>
			</div>

			<!-- Client/Appointment Info Section -->
			<div class="client-info-section container-fluid d-flex py-3">
				<!-- Currently selected client -->
				<div class="client d-flex align-items-start flex-column w-100" ng-if="client != null" ng-cloak>
						<div class="client-name">{{client.firstName}} {{client.lastName}}.</div>
						<div class="client-time">Scheduled Appointment Time: {{client.scheduledTime | date: "h:mm a"}}</div>

						<!-- The following are a couple of options for client progress/"workflow" -->
						<div class="progress w-100 my-2">
							<div class="progress-bar" role="progressbar" style="width: 20%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
							<div class="progress-bar bg-success" role="progressbar" style="width: 20%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
							<div class="progress-bar bg-info" role="progressbar" style="width: 20%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
						</div>

						<div class="client-progress d-flex flex-column">
							<span class="my-1 badge badge-pill badge-primary">Check-in</span>
							<span class="my-1 badge badge-pill badge-primary">Task 2</span>
							<span class="my-1 badge badge-pill badge-secondary">Complete Paperwork</span>
							<span class="my-1 badge badge-pill badge-secondary">Check-out</span>
						</div>

						<div class="client-appointmentId mt-auto">Appointment ID: {{client.appointmentId}}</div>
				</div>
				<!-- Default message if no appointment is selected -->
				<div class="client d-flex justify-content-center w-100" ng-if="client == null" ng-cloak>
					<div class="my-5">
						Select a client
					</div>
				</div>
			</div>
		</div>
	</div>

	<?php require_once "../server/footer.php" ?>
</body>
</html>
