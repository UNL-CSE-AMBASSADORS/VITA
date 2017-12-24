<?php $root = realpath($_SERVER["DOCUMENT_ROOT"]) ?>
<!DOCTYPE html>
<html class="no-js theme-light" lang="" ng-app="queueApp">
<head>
	<title>Queue</title>
	<?php require_once "$root/server/header.php" ?>
	<link rel="stylesheet" href="/dist/queue/queue.css">
	<link rel="stylesheet" href="/dist/queue/queue_private.css">
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
							 ng-repeat="appointment in appointments | orderBy:['timeIn == null', 'timeReturnedPapers == null', 'timeAppointmentStarted == null', 'scheduledTime'] | searchFor: clientSearch"
							 ng-if="appointment.completed == null"
							 ng-class-odd="'bg-light'"
							 ng-click="selectClient(appointment)">
						<div class="col">
							<div class="d-flex flex-column">
								<div class="d-flex flex-nowrap justify-content-between">
									<div class="queue-name font-weight-bold">{{appointment.firstName}} {{appointment.lastName}}</div>
									<div class="queue-time">{{appointment.scheduledTime | date: "h:mm a"}}</div>
								</div>
								<div class="d-flex flex-nowrap justify-content-between">
									<div class="queue-id">#{{appointment.appointmentId}}</div>
									<div class="queue-status">
										<span class="badge badge-pill" ng-class="appointment.checkedIn ? 'badge-primary': 'badge-secondary'">Checked In</span>
										<span class="badge badge-pill" ng-class="appointment.paperworkComplete ? 'badge-primary': 'badge-secondary'">Completed Paperwork</span>
										<span class="badge badge-pill" ng-class="appointment.preparing ? 'badge-primary': 'badge-secondary'">Preparing</span>
										<span class="badge badge-pill" ng-class="appointment.ended ? 'badge-primary': 'badge-secondary'">Appointment Complete</span>
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
				<div class="client align-items-start w-100" ng-if="client != null" ng-cloak>
					<div class="mb-3">
						<div class="client-name">{{client.firstName}} {{client.lastName}}</div>
						<div class="client-time"><b>Scheduled Appointment Time: </b>{{client.scheduledTime | date: "h:mm a"}}</div>
						<div class="client-email" ng-if="client.emailAddress != null" ng-cloak>
							<span><b>Email:</b> {{client.emailAddress}}</span>
						</div>
						<div class="client-phoneNumber" ng-if="client.phoneNumber != null" ng-cloak>
							<span><b>Phone Number:</b> {{client.phoneNumber}}</span>
						</div>
					</div>

						<div class="client-progress d-flex flex-column">
							<button type="button" class="btn" class="checkin" ng-disabled="client.checkedIn" ng-class="client.checkedIn ? 'btn-primary': 'btn-secondary' " ng-click="checkIn()">Checked In</button>
						</br>
							<button type="button" class="btn" class="paperworkComplete" ng-disabled="!client.checkedIn" ng-class="client.paperworkComplete ? 'btn-primary': 'btn-secondary' " ng-click="pwFilledOut()">Completed Paperwork</button>
						</br>
							<button type="button" class="btn" class="preparing" ng-disabled="!client.paperworkComplete" ng-class="client.preparing ? 'btn-primary': 'btn-secondary' " ng-click="nowPreparing()">Preparing</button>
						</br>
							<select ng-disabled="!client.preparing" ng-model="client.selectedStationNumber" ng-options="stationNumber for stationNumber in stationNumbers">
								<option value="" style="display:none;">-- Select Station --</option>
							</select>
						</br>
							<button type="button" class="btn" class="ended" ng-disabled="!client.preparing" ng-class="client.ended ? 'btn-primary': 'btn-secondary' " ng-click="completeAppointment()">Finished</button>
						</div>

						<div class="greeter-directions">
							<br>
							<strong>INSTRUCTIONS:</strong>
							<br>
							Once a client has completed a step, click on the corresponding button. This will log the time at which each step is completed. PROGRESS CANNOT BE UNDONE, so
							be sure to verify that the step is fully completed before clicking to progress the client. If, for any reason, a client does not complete their appointment,
							fill out the form below and explain (in 255 characters or less) why the appointment was not completed. Appointments will disappear shortly after being marked
							complete or incomplete.
						</div>
						<br>
						<br>
						<div class="appointment-not-complete">
							<form>
								<div class="form-group">
									<label><strong>Appointment Not Completed:</strong></label>
									<textarea ng-model="explanation" placeholder="Explain why the appointment was not completed." class="form-control" cols="300" rows="3" ng-maxlength="255"></textarea>
									<br>
									<button class="btn btn-danger" ng-disabled="!client.checkedIn" ng-click="incompleteAppointment(explanation)">Submit Incomplete Appointment</button>
							 </div>
						 	</form>
							<button class="btn btn-danger" ng-disabled="client.checkedIn" ng-click="cancelledAppointment()">Submit Cancelled Appointment</button>
						</div>
						<div class="client-appointmentId my-2"><b>Appointment ID: </b>{{client.appointmentId}}</div>
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

	<?php require_once "$root/server/footer.php" ?>
	<?php require_once "$root/server/angularjs_dependencies.php" ?>
	<script src="/dist/queue/queue.js"></script>
	<script src="/dist/queue/queue_service.js"></script>
	<script src="/dist/queue/queue_private.js"></script>
</body>
</html>
