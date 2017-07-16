<!DOCTYPE html>
<html class="no-js" lang="">
    <head>
        <title>Queue Test</title>
		<link rel='stylesheet' href='../assets/css/queue.css'>
    </head>
    <body>
        <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

		<div class='section nav-bar'>
			<div class='wrap-left menu-icon-wrap'>
				<img class='menu-icon' src='../assets/res/menu_white.svg'>
			</div>
			<div class='wrap-left page-lbl-wrap'>
				<div class='page-lbl'>Queue</div>
			</div>
			<div class='wrap-right site-lbl-wrap'>
				<div class='site-lbl'>Center for People in Need</div>
			</div>
		</div>

		<div class='section dashboard'>
			<div class='wrap-left volunteers-wrap'>
				<div class='flex volunteers'>
					<div class='volunteers-lbl'>Volunteers:</div>
					<div class='volunteers-count'>5</div>
				</div>
			</div>
			<div class='wrap-left queue-size-wrap'>
				<div class='flex queue-size'>
					<div class='queue-size-lbl'>Queue:</div>
					<div class='queue-size-count'>23</div>
				</div>
			</div>
			<div class='wrap-right clock-wrap'>
				<div class='flex clock'>
					<div class='clock-time'>08:45</div>
					<div class='clock-period'>
						<div class='clock-am inactive-period'>AM</div>
						<div class='clock-pm'>PM</div>
					</div>
				</div>
			</div>
		</div>

		<div class='section queue'>
			<div class='flex queue-container'>
				<div class='flex queue-header-container'>
					<div class='queue-header'>
						<div class='wrap-left queue-position-wrap'>
							<div class='queue-lbl'>Position</div>
						</div>
						<div class='wrap-left queue-name-wrap'>
							<div class='queue-lbl'>Name</div>
						</div>
						<div class='wrap-right queue-time-wrap'>
							<div class='queue-lbl'>Scheduled Time</div>
						</div>
					</div>
				</div>

				<div class='queue-table'>
					<div class='queue-table-record'>
						<div class='wrap-left queue-position-wrap'>
							<div class='queue-position-field'>1</div>
						</div>
						<div class='wrap-left queue-name-wrap'>
							<div class='queue-name-field'>Natasha-Savatovic DeBaron-Rasmussen-Kapichev</div>
						</div>
						<div class='wrap-right queue-time-wrap'>
							<div class='queue-time-field'>8:30 AM</div>
						</div>
					</div>
					<div class='queue-table-record'>
						<div class='wrap-left queue-position-wrap'>
							<div class='queue-position-field'>2</div>
						</div>
						<div class='wrap-left queue-name-wrap'>
							<div class='queue-name-field'>Matt M.</div>
						</div>
						<div class='wrap-right queue-time-wrap'>
							<div class='queue-time-field'>8:30 AM</div>
						</div>
					</div>
					<div class='queue-table-record'>
						<div class='wrap-left queue-position-wrap'>
							<div class='queue-position-field'>3</div>
						</div>
						<div class='wrap-left queue-name-wrap'>
							<div class='queue-name-field'>Matt M.</div>
						</div>
						<div class='wrap-right queue-time-wrap'>
							<div class='queue-time-field'>8:30 AM</div>
						</div>
					</div>
					<div class='queue-table-record'>
						<div class='wrap-left queue-position-wrap'>
							<div class='queue-position-field'>4</div>
						</div>
						<div class='wrap-left queue-name-wrap'>
							<div class='queue-name-field'>Matt M.</div>
						</div>
						<div class='wrap-right queue-time-wrap'>
							<div class='queue-time-field'>8:30 AM</div>
						</div>
					</div>
					<div class='queue-table-record'>
						<div class='wrap-left queue-position-wrap'>
							<div class='queue-position-field'>5</div>
						</div>
						<div class='wrap-left queue-name-wrap'>
							<div class='queue-name-field'>Matt M.</div>
						</div>
						<div class='wrap-right queue-time-wrap'>
							<div class='queue-time-field'>8:30 AM</div>
						</div>
					</div>
					<div class='queue-table-record'>
						<div class='wrap-left queue-position-wrap'>
							<div class='queue-position-field'>6</div>
						</div>
						<div class='wrap-left queue-name-wrap'>
							<div class='queue-name-field'>Matt M.</div>
						</div>
						<div class='wrap-right queue-time-wrap'>
							<div class='queue-time-field'>8:30 AM</div>
						</div>
					</div>
					<div class='queue-table-record'>
						<div class='wrap-left queue-position-wrap'>
							<div class='queue-position-field'>7</div>
						</div>
						<div class='wrap-left queue-name-wrap'>
							<div class='queue-name-field'>Matt M.</div>
						</div>
						<div class='wrap-right queue-time-wrap'>
							<div class='queue-time-field'>8:30 AM</div>
						</div>
					</div>
					<div class='queue-table-record'>
						<div class='wrap-left queue-position-wrap'>
							<div class='queue-position-field'>8</div>
						</div>
						<div class='wrap-left queue-name-wrap'>
							<div class='queue-name-field'>Matt M.</div>
						</div>
						<div class='wrap-right queue-time-wrap'>
							<div class='queue-time-field'>8:30 AM</div>
						</div>
					</div>
					<div class='queue-table-record'>
						<div class='wrap-left queue-position-wrap'>
							<div class='queue-position-field'>9</div>
						</div>
						<div class='wrap-left queue-name-wrap'>
							<div class='queue-name-field'>Matt M.</div>
						</div>
						<div class='wrap-right queue-time-wrap'>
							<div class='queue-time-field'>8:30 AM</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!--<div class='vita-queue-wrap'>
			<div class='vita-queue-header'>
				<div class='vita-queue-header-position'>#</div>
				<div class='vita-queue-header-name'>Name</div>
				<div class='vita-queue-header-wait'>Wait Time</div>
			</div>
			<div class='vita-queue'>
                Appointment data dynamically injected through js
			</div>
		</div>-->

		<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
		<script src='../assets/js/boilerplate.js'></script>
		<script src='../assets/js/main.js'></script>
		<script src='../assets/js/queue.js'></script>

		<!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
		<script>
			(function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
			function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
			e=o.createElement(i);r=o.getElementsByTagName(i)[0];
			e.src='https://www.google-analytics.com/analytics.js';
			r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
			ga('create','UA-XXXXX-X','auto');ga('send','pageview');
		</script>

		<?php /*require '../server/queue.php'*/ ?>
    </body>
</html>
