<?php
	$root = realpath($_SERVER['DOCUMENT_ROOT']);
	
	require_once "$root/server/user.class.php";
	$USER = new User();
	if (!$USER->isLoggedIn()) {
		header("Location: /unauthorized");
		die();
	}
?>

<!DOCTYPE html>
<html class="no-js" lang="" ng-app="appointmentsApp">
<head>
	<title>VITA Appointment Management</title>
	<?php require_once "$root/server/header.php" ?>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
	<link rel="stylesheet" href="/dist/assets/css/form.css" />
	<link rel="stylesheet" href="/dist/management/appointments/appointments.css" />
	<link rel="stylesheet" href="/dist/components/appointmentPicker/appointmentPicker.css" />
	<link rel="stylesheet" href="/assets/css/jquery-ui-datepicker.css">
</head>
<body ng-controller="AppointmentsController">
	<?php
		$page_subtitle = "Appointment Management";
		require_once "$root/components/nav.php";
	?>

	<div class="container">
		<!-- Show when an appointment is selected -->
		<div ng-show="appointment != null" ng-cloak>
			<div class="row my-2">
				<i class="fa fa-arrow-left fa-pull-left fa-2x pointer" aria-hidden="true" title="Back" ng-click="deselectAppointment()"></i>
			</div>

			<div class="mb-3">
				<div class="mb-1 client-name">{{appointment.firstName}} {{appointment.lastName}}</div>
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
			</div>

			<div class="mt-5">
				<h3>Reschedule Appointment</h3>
				<form class="cmxform mb-5" id="rescheduleAppointmentForm">
					<?php require_once "$root/components/appointmentPicker/appointmentPicker.php" ?>
					<input type="submit" value="Reschedule" id="rescheduleButton" class="submit btn btn-primary mb-5 vita-background-primary" ng-click="rescheduleAppointment()">
				</form>
			</div>
		</div>

		<!-- Show if no selected appointment -->
		<div ng-if="appointment == null" ng-cloak>
			<!-- Search box -->
			<div class="row justify-content-center">
				<div class="appointment-search col-6 py-3">
					<input class="w-100" type="text" ng-model="appointmentSearch" placeholder="Search for an appointment by client name or appointment ID" />
				</div>
			</div>

			<!-- List of appointments  -->
			<div class="row justify-content-center">
				<div class="appointments" ng-if="appointments.length > 0" ng-cloak>
					<div class="row py-1 pointer"
								ng-repeat="appointment in appointments | orderBy:'scheduledTime' | searchFor: appointmentSearch"
								ng-class-odd="'bg-light'"
								ng-click="selectAppointment(appointment)">
						<div class="col">
							<div class="d-flex flex-column">
								<div class="d-flex flex-nowrap justify-content-between">
									<div class="font-weight-bold">{{appointment.firstName}} {{appointment.lastName}}</div>
									<div>{{appointment.scheduledTime | date: "MMM d h:mm a"}} at {{appointment.title}}</div>
								</div>
								<div class="d-flex flex-nowrap justify-content-between">
									<div>#{{appointment.appointmentId}}</div>
								</div>
							</div>
						</div>
					</div>

					<!-- Show when there's no search results -->
					<p ng-show="(appointments | searchFor: appointmentSearch).length == 0">No results for "{{appointmentSearch}}"</p>
				</div>

				<!-- Show when there's no appointments -->
				<div class="appointments" ng-if="appointments.length == 0" ng-cloak>
					<div class="row d-flex justify-content-center">
						<div class="my-5">
							There are no appointments.
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<?php require_once "$root/server/footer.php" ?>
	<?php require_once "$root/server/angularjs_dependencies.php" ?>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/jquery.validate.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/additional-methods.min.js"></script>

	<!-- TODO: CHANGE TO DIST -->
	<script src="/management/appointments/appointments.js"></script>
	<script src="/management/appointments/appointments_service.js"></script>
	<script src="/dist/components/appointmentPicker/appointmentPicker.js"></script>
	<script src="/dist/assets/js/form.js"></script>
</body>
</html>