<?php $root = realpath($_SERVER["DOCUMENT_ROOT"]) ?>

<!-- Show when an appointment is selected -->
<div class="wdn-inner-wrapper wdn-inner-padding-no-top" ng-show="appointment != null" ng-cloak>

	<!-- Provide a way back to list of appointments -->
	<button type="button" class="wdn-button" ng-click="deselectAppointment()">Back to List of Appointments</button>

	<!-- Information Section -->
	<h2 class="client-name">{{appointment.firstName}} {{appointment.lastName}}</h2>
	<div><b>Scheduled Appointment Time: </b>{{appointment.scheduledTime | date: "MMM dd, yyyy h:mm a"}}</div>
	<div><b>Site: </b>{{appointment.title}}</div>
	<div><b>Requested Language: </b>{{appointment.language}}</div>
	<div ng-if="appointment.emailAddress != null">
		<span><b>Email: </b>{{appointment.emailAddress}}</span>
	</div>
	<div ng-if="appointment.phoneNumber != null">
		<span><b>Phone Number: </b>{{appointment.phoneNumber}}</span>
	</div>
	<div><b>Appointment ID: </b>{{appointment.appointmentId}}</div>

	<!-- Reschedule Section -->
	<h3>Reschedule Appointment</h3>
	<form class="cmxform" id="rescheduleForm">
		<div appointment-picker></div>
		<input type="submit" 
			value="Reschedule" 
			id="rescheduleButton" 
			class="submit wdn-button wdn-button-triad" 
			ng-disabled="sharedProperties.selectedDate == null || sharedProperties.selectedSite == null || sharedProperties.selectedTime == null" 
			ng-click="rescheduleAppointment()">
	</form>
</div>

<!-- Show if no selected appointment -->
<div ng-if="appointments != null && appointment == null" ng-cloak>
	<div ng-if="appointments.length > 0">
		<!-- Search box -->
		<div class="appointment-search wdn-inner-wrapper wdn-inner-padding-sm wdn-inner-padding-no-top wdn-center">
			<label for="queue-search">Search for an appointment by client name or appointment ID</label>
			<input id="queue-search" type="text" ng-model="appointmentSearch" />
		</div>

		<!-- Show when there's no search results -->
		<p class="wdn-inner-wrapper wdn-inner-padding-sm wdn-inner-padding-no-top wdn-center" 
			ng-show="(appointments | searchFor: appointmentSearch).length == 0">
			No results for "{{appointmentSearch}}"
		</p>

		<!-- List of appointments  -->
		<table class="wdn_responsive_table queue" ng-show="(appointments | searchFor: appointmentSearch).length > 0">
			<tbody>
				<tr class="pointer"
					ng-repeat="appointment in appointments | orderBy:'scheduledTime' | searchFor: appointmentSearch"
					ng-click="selectAppointment(appointment)">
					<td data-header="Name">{{appointment.firstName}} {{appointment.lastName}}</td>
					<td data-header="Scheduled Time">{{appointment.scheduledTime | date: "MMM d h:mm a"}} at {{appointment.title}}</td>
					<td data-header="Appointment ID">#{{appointment.appointmentId}}</td>
				</tr>
			</tbody>
		</table>
	</div>

	<!-- Show when there's no appointments -->
	<div class="wdn-inner-wrapper wdn-center" ng-if="appointments.length == 0">
		There are no appointments.
	</div>

</div>
