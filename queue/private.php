<?php $root = realpath($_SERVER["DOCUMENT_ROOT"]) ?>

<link rel="stylesheet" href="/dist/queue/queue_private.css">

<!-- Header section -->
<?php
	require_once "$root/queue/queue_header.php";
?>

<!-- Default Section -->
<div class="wdn-inner-wrapper wdn-inner-padding-sm wdn-inner-padding-no-top wdn-center" ng-if="appointments == null">
	Select a site and date.
</div>
<!-- End of Default Section -->


<!-- Queue Section -->
<div ng-if="appointments != null && client == null" ng-cloak>
	<div ng-if="appointments.length > 0">
		<!-- Search box -->
		<form class="queue-search wdn-inner-wrapper wdn-inner-padding-sm wdn-inner-padding-no-top wdn-center">
			<label for="queue-search">Search for a client by name or appointment ID</label>
			<input id="queue-search" type="text" ng-model="clientSearch" />
		</form>

		<!-- Message if there are no appointments that match the search -->
		<p class="wdn-inner-wrapper wdn-inner-padding-sm wdn-inner-padding-no-top wdn-center" ng-show="(appointments | searchFor: clientSearch).length == 0">
			No results for "{{clientSearch}}".
		</p>

		<!-- List of clients -->
		<table class="wdn_responsive_table queue" ng-show="(appointments | searchFor: clientSearch).length > 0">
			<thead>
				<tr>
					<th class="queue-name">Name</th>
					<th class="queue-progress">Progress</th>
					<th class="queue-time">Scheduled Time</th>
					<th class="queue-id">Appointment ID</th>
				</tr>
			</thead>
			<tbody>
				<tr class="pointer"
					ng-repeat="appointment in appointments | orderBy:['timeIn == null', 'timeReturnedPapers == null', 'timeAppointmentStarted == null', 'scheduledTime'] | searchFor: clientSearch"
					ng-if="appointment.completed == null"
					ng-click="selectClient(appointment)">
					<th class="queue-name" data-header="Name">{{appointment.firstName}} {{appointment.lastName}}</th>
					<td class="queue-status" data-header="Progress">
						<span class="pill" ng-class="appointment.checkedIn ? 'pill-complete': 'pill-incomplete'">Checked In</span>
						<span class="pill" ng-class="appointment.paperworkComplete ? 'pill-complete': 'pill-incomplete'">Completed Paperwork</span>
						<span class="pill" ng-class="appointment.preparing ? 'pill-complete': 'pill-incomplete'">Preparing</span>
						<span class="pill" ng-class="appointment.ended ? 'pill-complete': 'pill-incomplete'">Appointment Complete</span>
					</td>
					<td class="queue-time" data-header="Scheduled Time">{{appointment.scheduledTime | date: "h:mm a"}}</td>
					<td class="queue-id" data-header="Appointment ID">#{{appointment.appointmentId}}</td>
				</tr>
			</tbody>
		</table>
	</div>

	<!-- Default message if there are no appointments on the selected date -->
	<div class="wdn-inner-wrapper wdn-center" ng-if="appointments.length == 0">
		There are no appointments on this day.
	</div>
</div>
<!-- End of Queue Section -->


<!-- Client/Appointment Info Section -->
<div class="client-info-section container-fluid d-flex py-3" ng-if="client != null" ng-cloak>
	<!-- Currently selected client -->
	<div class="client align-items-start w-100">
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
</div>
<!-- End of Client/Appointment Info Section -->

