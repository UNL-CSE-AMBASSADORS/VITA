<?php $root = realpath($_SERVER["DOCUMENT_ROOT"]) ?>

<!-- Header section -->
<?php
	require_once "$root/queue/queue_header.php";
?>

<!-- Body Section with list of clients -->
<div class="container-fluid queue" ng-if="appointments.length > 0" ng-cloak>
	<div class="row queue-header py-2 bg-secondary text-light font-weight-bold">
		<div class="col col-4 queue-name">Name</div>
		<div class="col col-5 queue-progress">Progress</div>
		<div class="col col-3 queue-time">Scheduled Time</div>
	</div>
	<div class="row queue-row" 
			ng-repeat="appointment in appointments | orderBy:['timeIn == null', 'timeReturnedPapers == null', 'timeAppointmentStarted == null', 'scheduledTime']" 
			ng-if="appointment.completed == null" 
			ng-class-even="'bg-light'">
		<div class="col col-4 queue-name" style="size:8em">{{appointment.firstName}} {{appointment.lastName}}</div>
		<div class="col col-5 queue-progress">
			<span class="badge badge-pill" ng-class="appointment.checkedIn ? 'badge-primary': 'badge-secondary'">Checked In</span>
			<span class="badge badge-pill" ng-class="appointment.paperworkComplete ? 'badge-primary': 'badge-secondary'">Completed Paperwork</span>
			<span class="badge badge-pill" ng-class="appointment.preparing ? 'badge-primary': 'badge-secondary'">Appointment in Progress</span>
			<span class="badge badge-pill" ng-class="appointment.ended ? 'badge-primary': 'badge-secondary'">Appointment Complete</span>
		</div>
		<div class="col col-3 queue-time">{{appointment.scheduledTime | date: "h:mm a"}}</div>
	</div>
</div>
<div class="container-fluid queue" ng-if="appointments.length == 0" ng-cloak>
	<div class="row d-flex justify-content-center">
		<div class="my-5">
			There are no appointments on this day.
		</div>
	</div>
</div>
