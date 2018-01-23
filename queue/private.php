<?php $root = realpath($_SERVER["DOCUMENT_ROOT"]) ?>

<!-- Header section -->
<?php
	require_once "$root/queue/queue_header.php";
?>

<!-- Default Section -->
<div class="wdn-inner-wrapper wdn-center" ng-if="appointments == null">
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
		<p class="wdn-inner-wrapper wdn-inner-padding-sm wdn-inner-padding-no-top wdn-center" 
			ng-show="(appointments | searchFor: clientSearch).length == 0">
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
					ng-repeat="appointment in appointments | orderBy:['noshow', 'timeIn == null', 'timeReturnedPapers == null', 'timeAppointmentStarted == null', 'scheduledTime'] | searchFor: clientSearch"
					ng-if="appointment.completed == null"
					ng-click="selectClient(appointment)">
					<th class="queue-name" data-header="Name">{{appointment.firstName}} {{appointment.lastName}}</th>
					<td class="queue-status" data-header="Progress">
						<span class="pill pill-noshow" ng-if="appointment.noshow">No-show</span>
						<span class="pill" ng-class="appointment.checkedIn ? 'pill-complete': 'pill-incomplete'">Checked In</span>
						<span class="pill" ng-class="appointment.paperworkComplete ? 'pill-complete': 'pill-incomplete'">Completed Paperwork</span>
						<span class="pill" ng-class="appointment.preparing ? 'pill-complete': 'pill-incomplete'">Preparing</span>
						<span class="pill" ng-class="appointment.ended ? 'pill-complete': 'pill-incomplete'">Appointment Complete</span>
					</td>
					<td class="queue-time" data-header="Scheduled Time">{{appointment.scheduledTime}}</td>
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
<div class="client-info-section wdn-inner-wrapper" ng-if="appointments != null && client != null" ng-cloak>
	<!-- Provide a way to get back to the queue -->
	<button type="button" class="wdn-button" ng-click="unselectClient()">Back to Queue</button>

	<!-- Currently selected client -->
	<div class="client">
		<div class="client-information">
			<h2 class="client-name">{{client.firstName}} {{client.lastName}}</h2>
			<div>
				<span class="pill pill-noshow" ng-if="client.noshow">No-show</span>
				<span class="pill" ng-class="client.checkedIn ? 'pill-complete': 'pill-incomplete'">Checked In</span>
				<span class="pill" ng-class="client.paperworkComplete ? 'pill-complete': 'pill-incomplete'">Completed Paperwork</span>
				<span class="pill" ng-class="client.preparing ? 'pill-complete': 'pill-incomplete'">Preparing</span>
				<span class="pill" ng-class="client.ended ? 'pill-complete': 'pill-incomplete'">Appointment Complete</span>
			</div>
			<div class="client-time"><b>Scheduled Appointment Time: </b>{{client.scheduledTime}}</div>
			<div class="client-language">
				<span><b>Language:</b> {{client.language}}</span>
			</div>
			<div class="client-email" ng-if="client.emailAddress != null" ng-cloak>
				<span><b>Email:</b> {{client.emailAddress}}</span>
			</div>
			<div class="client-phoneNumber" ng-if="client.phoneNumber != null" ng-cloak>
				<span><b>Phone Number:</b> {{client.phoneNumber}}</span>
			</div>
			<div class="client-dependents" ng-if="client.dependents != null" ng-cloak>
				<b>Dependents:</b>
				<ul>
					<li ng-repeat="dependent in client.dependents">{{dependent.firstName}} {{dependent.lastName}}</li>
				</ul>
			</div>
		</div>

		<div class="client-progress">
			<h4>Update Progress:</h4>
			<button type="button" 
				class="wdn-button wdn-button-triad checkin" 
				ng-show="!client.checkedIn && !client.ended" 
				ng-click="checkIn()">
				Checked In
			</button>
			<button type="button" 
				class="wdn-button wdn-button-triad paperworkComplete" 
				ng-show="client.checkedIn && !client.paperworkComplete && !client.ended" 
				ng-click="pwFilledOut()">
				Completed Paperwork
			</button>
			<button type="button" 
				class="wdn-button wdn-button-triad preparing" 
				ng-show="client.paperworkComplete && !client.preparing && !client.ended" 
				ng-click="nowPreparing()">
				Preparing
			</button>
			<div ng-show="client.preparing && !client.ended">
				<div class="bp768-wdn-col-one-half" ng-repeat="filingStatus in filingStatuses">
					<input type="checkbox" ng-model="filingStatus.checked"/> {{filingStatus.text}}
				</div>
			</div>
			<select ng-show="client.preparing && !client.ended" 
				ng-model="client.selectedStationNumber" 
				ng-options="stationNumber for stationNumber in stationNumbers">
				<option value="" style="display:none;">-- Select Station --</option>
			</select>
			<button type="button" 
				class="wdn-button wdn-button-triad ended" 
				ng-show="client.preparing && !client.ended" 
				ng-disabled="client.selectedStationNumber == null" 
				ng-click="completeAppointment()">
				Finished
			</button>
			<p ng-show="client.ended">Finished!</p>
		</div>

		<div class="greeter-directions">
			<h4>Instructions:</h4>
			<p>
				Once a client has completed a step, click on the corresponding button. This will log the time at which each step is completed. PROGRESS CANNOT BE UNDONE, so
				be sure to verify that the step is fully completed before clicking to progress the client. If, for any reason, a client does not complete their appointment,
				fill out the form below and explain (in 255 characters or less) why the appointment was not completed. Appointments will disappear shortly after being marked
				complete or incomplete.
			</p>
		</div>
		
		<div class="appointment-not-complete">
			<form>
				<h4>Appointment Not Completed:</h4>
				<label ng-show="client.checkedIn">Explain why the appointment was not completed.</label>
				<textarea ng-model="client.explanation" 
					placeholder="-- Expanation --" 
					class="form-control" 
					cols="300" 
					rows="3" 
					maxlength="255" 
					ng-show="client.checkedIn" 
					ng-disabled="client.ended" 
					ng-maxlength="255">
				</textarea>
				<span class="wdn-pull-right" ng-show="client.checkedIn">{{ client.explanation ? client.explanation.length : 0 }}/255</span>
				<button class="wdn-button wdn-button-brand" 
					ng-show="client.checkedIn" 
					ng-disabled="client.ended" 
					ng-click="incompleteAppointment(client.explanation)">
					Submit Incomplete Appointment
				</button>
				<button class="wdn-button wdn-button-brand" 
					ng-show="!client.checkedIn" 
					ng-disabled="client.ended" 
					ng-click="cancelledAppointment()">
					Submit Cancelled Appointment
				</button>
			</form>
		</div>

		<div class="client-appointmentId"><b>Appointment ID: </b>{{client.appointmentId}}</div>
	</div>
</div>
<!-- End of Client/Appointment Info Section -->
