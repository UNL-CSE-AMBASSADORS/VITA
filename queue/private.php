<?php $root = realpath($_SERVER["DOCUMENT_ROOT"]) ?>

<!-- Header section -->
<?php
	require_once "$root/queue/queue_header.php";
?>

<!-- Default Section -->
<div class="dcf-wrapper dcf-txt-center dcf-p-10" ng-if="appointments == null">
	Select a site and date.
</div>
<!-- End of Default Section -->


<!-- Queue Section -->
<div ng-if="appointments != null && client == null" class="dcf-mb-6" ng-cloak>
	<div ng-if="appointments.length > 0">
		<!-- Search box -->
		<form class="queue-search dcf-wrapper dcf-p-8 dcf-txt-center">
			<label for="queue-search">Search for a client by name or appointment ID</label>
			<input id="queue-search" type="text" ng-model="clientSearch" />
		</form>

		<!-- Message if there are no appointments that match the search -->
		<p class="dcf-wrapper dcf-p-10 dcf-txt-center" 
			ng-show="(appointments | searchFor: clientSearch).length == 0">
			No results for "{{clientSearch}}".
		</p>

		<!-- List of clients -->
		<table class="dcf-table queue dcf-ml-2 dcf-mr-2" ng-show="(appointments | searchFor: clientSearch).length > 0">
			<thead>
				<tr>
					<th class="queue-name">Name</th>
					<th class="queue-progress">Progress</th>
					<th class="queue-time">Scheduled Time</th>
				</tr>
			</thead>
			<tbody>
				<tr class="pointer"
					ng-repeat="appointment in appointments | orderBy:['noShow', 'cancelled', 'completed == false', 'ended', 'timeIn == null', 'timeReturnedPapers == null', 'timeAppointmentStarted == null', 'scheduledTime', 'walkIn'] | searchFor: clientSearch"
					ng-click="selectClient(appointment)">
					<th class="queue-name" data-header="Name">{{appointment.firstName}} {{appointment.lastName}}</th>
					<td class="queue-status" data-header="Progress">
						<span class="pill pill-red" ng-if="appointment.cancelled">Cancelled</span>
						<span class="pill pill-red" ng-if="appointment.noShow && !appointment.cancelled">No-show</span>
						<span class="pill pill-red" ng-if="appointment.completed == false && !appointment.cancelled">Incomplete</span>
						<span ng-if="!appointment.noShow && !appointment.cancelled && appointment.completed != false">
							<span class="pill pill-green" ng-if="appointment.ended">Completed</span>
							<span ng-if="!appointment.ended">
								<span class="pill pill-walk-in" ng-if="appointment.walkIn">Walk-In</span>
								<span class="pill" ng-class="appointment.checkedIn ? 'pill-complete': 'pill-incomplete'">Checked In</span>
								<span class="pill" ng-class="appointment.paperworkComplete ? 'pill-complete': 'pill-incomplete'">Completed Paperwork</span>
								<span class="pill" ng-class="appointment.preparing ? 'pill-complete': 'pill-incomplete'">Preparing</span>
								<span class="pill" ng-class="appointment.ended ? 'pill-complete': 'pill-incomplete'">Appointment Complete</span>
							</span>
						</span>
					</td>
					<td class="queue-time" data-header="Scheduled Time">{{appointment.scheduledTime}}</td>
				</tr>
			</tbody>
		</table>
	</div>

	<!-- Default message if there are no appointments on the selected date -->
	<div class="dcf-wrapper dcf-txt-center dcf-p-10" ng-if="appointments.length == 0">
		There are no appointments on this day.
	</div>
</div>
<!-- End of Queue Section -->


<!-- Client/Appointment Info Section -->
<div class="client-info-section dcf-wrapper dcf-pt-8 dcf-pt-8" ng-if="appointments != null && client != null" ng-cloak>
	<!-- Provide a way to get back to the queue -->
	<button type="button" class="dcf-btn dcf-btn-secondary dcf-mb-8" ng-click="unselectClient()">Back to Queue</button>

	<!-- Currently selected client -->
	<div class="client">
		<div class="client-information dcf-mb-8">
			<h2 class="client-name">{{client.firstName}} {{client.lastName}}</h2>
			<div>
				<span class="pill pill-no-show" ng-if="client.noShow">No-show</span>
				<span ng-if="!client.noShow">
					<span class="pill pill-walk-in" ng-if="client.walkIn">Walk-In</span>
					<span class="pill" ng-class="client.checkedIn ? 'pill-complete': 'pill-incomplete'">Checked In</span>
					<span class="pill" ng-class="client.paperworkComplete ? 'pill-complete': 'pill-incomplete'">Completed Paperwork</span>
					<span class="pill" ng-class="client.preparing ? 'pill-complete': 'pill-incomplete'">Preparing</span>
					<span class="pill" ng-class="client.ended ? 'pill-complete': 'pill-incomplete'">Appointment Complete</span>
				</span>
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
		</div>

		<div class="client-progress dcf-mb-8">
			<h4>Update Progress:</h4>
			<button type="button" 
				class="dcf-btn dcf-btn-primary checkin" 
				ng-show="!client.checkedIn && !client.ended" 
				ng-click="checkIn()">
				Checked In
			</button>
			<button type="button" 
				class="dcf-btn dcf-btn-primary paperworkComplete" 
				ng-show="client.checkedIn && !client.paperworkComplete && !client.ended" 
				ng-click="pwFilledOut()">
				Completed Paperwork
			</button>
			<button type="button" 
				class="dcf-btn dcf-btn-primary preparing" 
				ng-show="client.paperworkComplete && !client.preparing && !client.ended" 
				ng-click="nowPreparing()">
				Preparing
			</button>
			<div class="dcf-grid-halves@md dcf-mb-2" ng-show="client.preparing && !client.ended">
				<div ng-repeat="filingStatus in filingStatuses">
					<input class="dcf-input-control" type="checkbox" ng-model="filingStatus.checked"/> {{filingStatus.text}}
				</div>
			</div>
			<div class="dcf-mb-5" ng-show="client.preparing && !client.ended">
				<label class="dcf-label">Station</label>
				<select class="dcf-input-select dcf-mb-0" 
					ng-model="client.selectedStationNumber" 
					ng-options="stationNumber for stationNumber in stationNumbers">
					<option value="" style="display:none;">-- Select Station --</option>
				</select>
			</div>
			<button type="button" 
				class="dcf-btn dcf-btn-primary ended" 
				ng-show="client.preparing && !client.ended" 
				ng-disabled="client.selectedStationNumber == null" 
				ng-click="completeAppointment()">
				Finished
			</button>
			<p ng-show="client.ended">Finished!</p>
		</div>

		<div class="greeter-directions dcf-mb-8">
			<h4>Instructions:</h4>
			<p>
				Once a client has completed a step, click on the corresponding button. This will log the time at which each step is completed. PROGRESS CANNOT BE UNDONE, so
				be sure to verify that the step is fully completed before clicking to progress the client. If, for any reason, a client does not complete their appointment,
				click the incomplete appointment button below and leave a note explaining why. Appointments will disappear shortly after being marked
				complete or incomplete.
			</p>
		</div>

		<div appointment-notes-area class="dcf-mb-8"></div>
		
		<div class="appointment-not-complete dcf-mb-8">
			<form>
				<h4>Appointment Not Completed:</h4>
				<button class="dcf-btn dcf-btn-primary" 
					ng-show="!client.checkedIn" 
					ng-disabled="client.ended" 
					ng-click="cancelledAppointment()">
					Cancel Appointment
				</button>

				<button class="dcf-btn dcf-btn-primary" 
					ng-show="client.checkedIn" 
					ng-disabled="client.ended" 
					ng-click="incompleteAppointment()">
					Mark as Incomplete
				</button>
			</form>
		</div>

		<div class="client-appointmentId dcf-txt-xs dcf-txt-right dcf-mb-3"><b>Appointment ID: </b>{{client.appointmentId}}</div>
	</div>
</div>
<!-- End of Client/Appointment Info Section -->
