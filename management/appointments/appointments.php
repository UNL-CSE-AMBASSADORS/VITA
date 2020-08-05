<?php $root = realpath($_SERVER["DOCUMENT_ROOT"]) ?>

<!-- Show when an appointment is selected -->
<div class="dcf-wrapper" ng-show="appointment != null" ng-cloak>

	<!-- Provide a way back to list of appointments -->
	<button type="button" class="dcf-btn dcf-btn-secondary dcf-mb-8" ng-click="deselectAppointment()">Back to List of Appointments</button>

	<!-- Information Section -->
	<h2 class="client-name">{{appointment.firstName}} {{appointment.lastName}}</h2>
	<div class="dcf-grid-halves@md">
		<div>
			<div><b>Scheduled Appointment Time: </b>{{appointment.scheduledTime}}</div>
			<div><b>Site: </b>{{appointment.title}}</div>
			<div><b>Requested Language: </b>{{appointment.language}}</div>
			<div ng-if="appointment.emailAddress != null">
				<span><b>Email: </b>{{appointment.emailAddress}}</span>
			</div>
			<div ng-if="appointment.phoneNumber != null">
				<span><b>Phone Number: </b>{{appointment.phoneNumber}}</span>
			</div>
			<div ng-if="appointment.bestTimeToCall != null">
				<span><b>Best Time to Call: </b>{{appointment.bestTimeToCall}}</span>
			</div>
			<div><b>Tax Type: </b>{{appointment.appointmentType}}</div>
		</div>

		<div>
			<div><b>Status: </b><span 
				ng-class="{'status-in-progress': appointment.inProgress || appointment.notStarted, 'status-incomplete': appointment.incomplete || appointment.cancelled, 'status-complete': appointment.completed}">
				{{appointment.statusText}}
			</span></div>
			<div><b>Time Arrived: </b>{{appointment.timeIn != null ? appointment.timeIn : "N/A"}}</div>
			<div><b>Time Paperwork Completed: </b>{{appointment.timeReturnedPapers != null ? appointment.timeReturnedPapers : "N/A"}}</div>
			<div><b>Time Appointment Started: </b>{{appointment.timeAppointmentStarted != null ? appointment.timeAppointmentStarted : "N/A"}}</div>
			<div><b>Time Appointment Ended: </b>{{appointment.timeAppointmentEnded != null ? appointment.timeAppointmentEnded : "N/A"}}</div>
			<div><b>Appointment ID: </b>{{appointment.appointmentId}}</div>
		</div>
	</div>
	<div>
		<div ng-if="appointment.uniqueAppointmentRescheduleUrl != null"><b>Unique Appointment Reschedule URL: </b><a ng-href="{{appointment.uniqueAppointmentRescheduleUrl}}" target="_blank">{{appointment.uniqueAppointmentRescheduleUrl}}</a></div>
		<div ng-if="appointment.uniqueUploadDocumentsUrl != null"><b>Unique Upload Documents URL: </b><a ng-href="{{appointment.uniqueUploadDocumentsUrl}}" target="_blank">{{appointment.uniqueUploadDocumentsUrl}}</a></div>
	</div>



	<!-- Notes Area -->
	<div appointment-notes-area class="dcf-mt-5"></div>
	<!-- End Notes Area -->



	<!-- Reschedule Section -->
	<h3 class="dcf-mt-5">Reschedule Appointment</h3>
	<form class="cmxform" id="rescheduleForm">

		<label>Tax Type (currently: {{appointmentPickerSharedProperties.appointmentType}})</label>
		<div class="dcf-btn-group">
			<button class="dcf-btn dcf-ml-2 dcf-mr-2" ng-class="appointmentPickerSharedProperties.appointmentType === 'residential' ? 'dcf-btn-primary' : 'dcf-btn-secondary'" 
				type="button" ng-click="appointmentPickerSharedProperties.appointmentType = 'residential'">Residential</button>
			<button class="dcf-btn dcf-mr-2" ng-class="appointmentPickerSharedProperties.appointmentType === 'china' ? 'dcf-btn-primary' : 'dcf-btn-secondary'"
				type="button" ng-click="appointmentPickerSharedProperties.appointmentType = 'china'">China</button>
			<button class="dcf-btn dcf-mr-2" ng-class="appointmentPickerSharedProperties.appointmentType === 'india' ? 'dcf-btn-primary' : 'dcf-btn-secondary'"
				type="button" ng-click="appointmentPickerSharedProperties.appointmentType = 'india'">India</button>
			<button class="dcf-btn dcf-mr-2" ng-class="appointmentPickerSharedProperties.appointmentType === 'treaty' ? 'dcf-btn-primary' : 'dcf-btn-secondary'"
				type="button" ng-click="appointmentPickerSharedProperties.appointmentType = 'treaty'">Treaty</button>
			<button class="dcf-btn" ng-class="appointmentPickerSharedProperties.appointmentType === 'non-treaty' ? 'dcf-btn-primary' : 'dcf-btn-secondary'"
				type="button" ng-click="appointmentPickerSharedProperties.appointmentType = 'non-treaty'">Non-Treaty</button>
		</div>
		
		<div appointment-picker class="dcf-mb-5"></div>

		<div class="dcf-mb-5">
			<input type="submit" 
				value="Reschedule" 
				id="rescheduleButton" 
				class="submit dcf-btn dcf-btn-primary" 
				ng-disabled="appointmentPickerSharedProperties.selectedDate == null || appointmentPickerSharedProperties.selectedSite == null || appointmentPickerSharedProperties.selectedTime == null || submittingReschedule" 
				ng-model="submittingReschedule" 
				ng-click="rescheduleAppointment()">

			<!-- Cancel Button -->
			<a id="confirm-cancel-modal-opener"
				class="submit dcf-btn dcf-btn-secondary"
				ng-show="appointment.notStarted && !appointment.cancelled"
				href="#confirm-cancel-modal">Cancel Appointment</a>
		</div>
		
		<p>An email will automatically be sent to the client with the rescheduled information if the client has an email on record.</p>

		<!-- Confirm Appointment Cancel Modal -->
		<div class="hidden">
			<div class="modal" id="confirm-cancel-modal" tabindex="-1" role="dialog" aria-hidden="true">
				<div class="dcf-wrapper dcf-pt-8 dcf-pb-5">
					<h4>Are you sure you want to cancel this appointment?</h4>
					<div class="dcf-pt-5">
						<button type="button" class="dcf-btn dcf-btn-secondary close-modal-button">No, do not Cancel</button>
						<button type="button" id="cancel-button" class="dcf-btn dcf-btn-primary" ng-click="cancelAppointment()">Yes, cancel</button>
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
		<div class="appointment-search dcf-wrapper dcf-txt-center dcf-pb-8">
			<label class="dcf-label" for="queue-search">Search for an appointment by client name or appointment ID</label>
			<input class="dcf-input-text dcf-d-inline" id="queue-search" type="text" ng-model="appointmentSearch" />
		</div>

		<!-- Show when there's no search results -->
		<p class="dcf-wrapper dcf-txt-center unl-font-sans" 
			ng-show="(appointments | searchFor: appointmentSearch).length == 0">
			No results for "{{appointmentSearch}}"
		</p>

		<!-- List of appointments  -->
		<table class="dcf-table queue dcf-ml-5 dcf-mr-5" ng-show="(appointments | searchFor: appointmentSearch).length > 0">
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
	<div class="dcf-wrapper dcf-txt-center unl-font-sans" ng-if="appointments.length == 0">
		There are no appointments.
	</div>

</div>
