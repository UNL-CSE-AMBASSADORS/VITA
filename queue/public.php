<?php $root = realpath($_SERVER["DOCUMENT_ROOT"]) ?>

<!-- Header section -->
<?php
	require_once "$root/queue/queue_header.php";
?>

<!-- Default Section -->
<div class="wdn-inner-wrapper wdn-center" ng-if="appointments == null">
	Select a site and date.
</div>

<!-- Body Section with list of clients -->
<table class="wdn_responsive_table queue" ng-if="appointments.length > 0" ng-cloak>
	<thead>
		<tr>
			<th class="queue-name">Name</th>
			<th class="queue-progress">Progress</th>
			<th class="queue-time">Scheduled Time</th>
		</tr>
	</thead>
	<tbody>
		<tr ng-repeat="appointment in appointments | orderBy:['noShow', 'timeIn == null', 'timeReturnedPapers == null', 'timeAppointmentStarted == null', 'scheduledTime']" 
			ng-if="appointment.completed == null">
			<th class="queue-name" data-header="Name">{{appointment.firstName}} {{appointment.lastName}}</th>
			<td class="queue-progress" data-header="Progress">
				<span class="pill pill-noshow" ng-if="appointment.noShow">No-show</span>
				<span ng-if="!appointment.noShow">
					<span class="pill" ng-class="appointment.checkedIn ? 'pill-complete': 'pill-incomplete'">Checked In</span>
					<span class="pill" ng-class="appointment.paperworkComplete ? 'pill-complete': 'pill-incomplete'">Completed Paperwork</span>
					<span class="pill" ng-class="appointment.preparing ? 'pill-complete': 'pill-incomplete'">Appointment in Progress</span>
					<span class="pill" ng-class="appointment.ended ? 'pill-complete': 'pill-incomplete'">Appointment Complete</span>
				</span>
			</td>
			<td class="queue-time" data-header="Scheduled Time">{{appointment.scheduledTime}}</td>
		</tr>
	</tbody>
</table>

<!-- Default message if there are no appointments on the selected date -->
<div class="wdn-inner-wrapper wdn-center queue" ng-if="appointments.length == 0" ng-cloak>
	There are no appointments on this day.
</div>
