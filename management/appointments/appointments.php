<?php $root = realpath($_SERVER["DOCUMENT_ROOT"]) ?>

<!-- Show when an appointment is selected -->
<div class="wdn-inner-wrapper wdn-inner-padding-no-top" ng-show="appointment != null" ng-cloak>

	<!-- Provide a way back to list of appointments -->
	<button type="button" class="wdn-button" ng-click="deselectAppointment()">Back to List of Appointments</button>

	<!-- Information Section -->
	<h2 class="client-name">{{appointment.firstName}} {{appointment.lastName}}</h2>
	<div class="wdn-grid-set">

		<div class="wdn-col-one-half">
			<div><b>Scheduled Appointment Time: </b>{{appointment.scheduledTime}}</div>
			<div><b>Site: </b>{{appointment.title}}</div>
			<div><b>Requested Language: </b>{{appointment.language}}</div>
			<div ng-if="appointment.emailAddress != null">
				<span><b>Email: </b>{{appointment.emailAddress}}</span>
			</div>
			<div ng-if="appointment.phoneNumber != null">
				<span><b>Phone Number: </b>{{appointment.phoneNumber}}</span>
			</div>
			<div><b>Appointment ID: </b>{{appointment.appointmentId}}</div>
		</div>

		<div class="wdn-col-one-half">
			<div><b>Status: </b><span 
				ng-class="{'status-in-progress': appointment.inProgress || appointment.notStarted, 'status-incomplete': appointment.incomplete || appointment.cancelled, 'status-complete': appointment.completed}">
				{{appointment.statusText}}
			</span></div>
			<div><b>Time Arrived: </b>{{appointment.timeIn != null ? appointment.timeIn : "N/A"}}</div>
			<div><b>Time Paperwork Completed: </b>{{appointment.timeReturnedPapers != null ? appointment.timeReturnedPapers : "N/A"}}</div>
			<div><b>Time Appointment Started: </b>{{appointment.timeAppointmentStarted != null ? appointment.timeAppointmentStarted : "N/A"}}</div>
			<div><b>Time Appointment Ended: </b>{{appointment.timeAppointmentEnded != null ? appointment.timeAppointmentEnded : "N/A"}}</div>
			<div><b>Prepared at Station: </b>{{appointment.servicedByStation != null ? appointment.servicedByStation : "N/A"}}</div>
		</div>
	</div>
	<div ng-show="appointment.notCompletedDescription != null"><b>Not Completed Reason: </b>{{appointment.notCompletedDescription}}</div>
	<div ng-if="appointment.filingStatuses.length > 0">
		<div><b>Filed: </b></div>
		<ul>
			<li ng-repeat="filingStatus in appointment.filingStatuses">{{filingStatus.text}}</li>
		</ul>
	</div>

	<!-- Reschedule Section -->
	<h3>Reschedule Appointment</h3>
	<form class="cmxform" id="rescheduleForm">
		<label>
			<div>International Student Scholar</div>
			<span class="switch">
				<input type="checkbox" ng-model="sharedProperties.studentScholar">
				<span class="slider round"></span>
			</span>
		</label>
		<div appointment-picker></div>
		<input type="submit" 
			value="Reschedule" 
			id="rescheduleButton" 
			class="submit wdn-button wdn-button-triad" 
			ng-disabled="sharedProperties.selectedDate == null || sharedProperties.selectedSite == null || sharedProperties.selectedTime == null || submittingReschedule" 
			ng-model="submittingReschedule" 
			ng-click="rescheduleAppointment()">

		<!-- Cancel Button -->
		<a id="confirm-cancel-modal-opener"
			class="submit wdn-button wdn-button-brand"
			ng-show="appointment.notStarted && !appointment.cancelled"
			href="#confirm-cancel-modal">Cancel Appointment</a>
		
		<p>An email will automatically be sent to the client with the rescheduled information if the client has an email on record.</p>

		<!-- Confirm Appointment Cancel Modal -->
		<div class="hidden">
			<div class="modal" id="confirm-cancel-modal" tabindex="-1" role="dialog" aria-hidden="true">
				<div class="wdn-band">
					<div class="wdn-inner-wrapper">
						<h4>Are you sure you want to cancel this appointment?</h4>
						<div>
							<button type="button" class="wdn-button wdn-button-triad close-modal-button">No, do not Cancel</button>
							<button type="button" id="cancel-button" class="wdn-button wdn-button-brand" ng-click="cancelAppointment()">Yes, cancel</button>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- End Confirm Appointment Cancel Modal -->
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
					ng-repeat="appointment in appointments | searchFor: appointmentSearch"
					ng-click="selectAppointment(appointment)">
					<td data-header="Name">{{appointment.firstName}} {{appointment.lastName}}</td>
					<td data-header="Scheduled Time">{{appointment.scheduledTime}} at {{appointment.title}}</td>
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
