<?php $root = realpath($_SERVER["DOCUMENT_ROOT"]) ?>
<!DOCTYPE html>
<html class="no-js theme-light" lang="" ng-app="queueApp">
<head>
	<title>Queue Test</title>
	<?php require_once "$root/server/header.php" ?>
	<link rel="stylesheet" href="/queue/queue.css">
	<link rel="stylesheet" href="/queue/queue_private.css">
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
										<span class="badge badge-pill badge-secondary" class="checkin">Checked In</span>
										<span class="badge badge-pill badge-secondary" class="paperwork-complete">Completed Paperwork</span>
										<span class="badge badge-pill badge-secondary" class="preparing">Preparing</span>
										<span class="badge badge-pill badge-secondary" class="finished">Finished</span>
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


						<div class="client-progress d-flex flex-column">
							<button type="button" class="btn btn-secondary" class="checkin">Checked In</button>
						</br>
							<button type="button" class="btn btn-secondary" class="paperwork-complete">Completed Paperwork</button>
						</br>
							<button type="button" class="btn btn-secondary" class="preparing">Preparing</button>
						</br>
							<button type="button" class="btn btn-secondary" class="finished">Finished</button>
						</div>

						<div class="greeter-directions">
							Click on the button for each step a client completes.
						</div>
						<!-- TODO: Add timestamps to SQL db on clicks, create field for incomplete -->
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
	<?php require_once "$root/server/angularjs_dependencies.php" ?>
	<script src="/queue/queue.js"></script>
	<script src="/queue/queue_service.js"></script>
	<script src="/queue/queue_private.js"></script>
</body>
</html>
