<!DOCTYPE html>
<html class='no-js theme-light' lang="">
    <head>
		<title>Queue Test</title>
		<?php require_once '../server/header.php' ?>
		<link rel='stylesheet' href='../assets/css/queue_priv.css'>
		<meta http-equiv='refresh' content='600'/>
    </head>
    <body>
        <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
		<![endif]-->
		<?php require_once '../components/nav.php' ?>
		<div class='section flex box theme-light queue-details'>
			<div class='flex box queue'>
				<div class='theme-dark flex box date-wrap'>
					<i class='material-icons no-select date-back'>keyboard_arrow_left</i>
					<div class='date'></div>
					<i class='material-icons no-select date-forward'>keyboard_arrow_right</i>
				</div>
				<div class='theme-dark flex box queue-header'>
					<div class='wrap-left queue-position-wrap'>Pos.</div>
					<div class='wrap-left queue-name-wrap'>Name</div>
					<div class='wrap-right queue-time-wrap'>Time</div>
				</div>
				<div class='theme-white flex queue-table'></div>
				<div class='flex queue-legend'>
					Legend:
					<div class='flex queue-tag on-time-tag'>On Time</div>
					<div class='flex queue-tag late-tag'>Late</div>
					<div class='flex queue-tag no-show-tag'>No Show</div>
				</div>
			</div>
			<div class='flex details'>
				<div class='theme-white flex details-id'>
					<div class='theme-dark box details-id-header'>Details</div>
					<div class='details-id-body'>
						<div class='box details-id-attribute'>
							<div class='details-id-attribute-label'>Name</div>
							<div class='details-id-attribute-value details-name'></div>
						</div>
						<div class='box details-id-attribute'>
							<div class='details-id-attribute-label'>Email</div>
							<div class='details-id-attribute-value details-email'></div>
						</div>
						<div class='box details-id-attribute'>
							<div class='details-id-attribute-label'>Phone</div>
							<div class='details-id-attribute-value details-phone'></div>
						</div>
						<div class='box details-id-attribute'>
							<div class='details-id-attribute-label'>Site</div>
							<div class='details-id-attribute-value details-site-name'></div>
						</div>
						<div class='box details-id-attribute'>
							<div class='details-id-attribute-label'>Time</div>
							<div class='details-id-attribute-value details-time'></div>
						</div>
					</div>
				</div>
				<div class='flex details-controls'>
					<div class='details-control details-close'>Close</div>
					<div class='button theme-dark details-control details-reschedule'>Reschedule</div>
					<div class='button theme-dark details-control details-cancel'>Cancel Appointment</div>
					<div class='button theme-dark details-control details-accept'>Accept</div>
				</div>
			</div>
		</div>
		<?php require_once '../server/footer.php' ?>
	</body>
	<script src='https://cdnjs.cloudflare.com/ajax/libs/mustache.js/2.3.0/mustache.min.js'></script>
	<script src='/assets/js/queue_common.js'></script>
	<script src='/assets/js/queue_priv.js'></script>
	<script class='queue-record-template' type='text/template'>
		<div class='queue-record'>
			<div class='queue-record-id'>{{id}}</div>
			<div class='wrap-left queue-position-wrap'>{{position}}</div>
			<div class='wrap-left queue-name-wrap'>{{name}}</div>
			<div class='wrap-right queue-time-wrap'>
				{{#isOnTime}}<div class='flex queue-tag on-time-tag'>OT</div>{{/isOnTime}}
				{{^isOnTime}}
					{{#isPresent}}<div class='flex queue-tag late-tag'>LT</div>{{/isPresent}}
					{{^isPresent}}<div class='flex queue-tag no-show-tag'>NS</div>{{/isPresent}}
				{{/isOnTime}}
				{{time}}
			</div>
		</div>
	</script>
</html>
