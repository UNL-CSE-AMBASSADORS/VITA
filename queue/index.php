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
			<div class='wrpr-left flex menu-icon-wrpr'>
				<img class='menu-icon' src='../assets/res/menu_white.svg'>
			</div>
			<div class='wrpr-left flex page-lbl-wrpr'>
				<div class='page-lbl'>Queue</div>
			</div>
			<div class='wrpr-right flex site-lbl-wrpr'>
				<div class='site-lbl'>Center for People in Need</div>
			</div>
		</div>

		<div class='section dashboard'>
			<div class='wrpr-left flex volunteers-wrpr'>
				<div class='flex volunteers'>
					<div class='volunteers-lbl'>Volunteers:</div>
					<div class='volunteers-count'>10</div>
				</div>
			</div>
			<div class='wrpr-left flex queue-size-wrpr'>
				<div class='flex queue-size'>
					<div class='queue-size-lbl'>In Queue:</div>
					<div class='queue-size-count'>23</div>
				</div>
			</div>
			<div class='wrpr-right flex clock-wrpr'>
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
			<div class='queue-container'>
				<div class='queue-header-container'>
					<div class='queue-header'>
						<div class='wrpr-left queue-position-wrpr'>
							<div class='queue-lbl'>Position</div>
						</div>
						<div class='wrpr-left queue-name-wrpr'>
							<div class='queue-lbl'>Name</div>
						</div>
						<div class='wrpr-right queue-time-wrpr'>
							<div class='queue-lbl'>Time</div>
						</div>
					</div>
				</div>

				<div class='queue-table-container'>
					<div class='queue-table'>
						<div class='queue-table-record'>
							<div class='wrpr-left queue-position-wrpr'>
								<div class='queue-field'>1</div>
							</div>
							<div class='wrpr-left queue-name-wrpr'>
								<div class='queue-field'>Matt M.</div>
							</div>
							<div class='wrpr-right queue-time-wrpr'>
								<div class='queue-field'>8:30 AM</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class='vita-queue-wrpr'>
			<div class='vita-queue-header'>
				<div class='vita-queue-header-position'>#</div>
				<div class='vita-queue-header-name'>Name</div>
				<div class='vita-queue-header-wait'>Wait Time</div>
			</div>
			<div class='vita-queue'>
                <!-- Appointment data dynamically injected through js -->
			</div>
		</div>

		<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
		<script src='../assets/js/boilerplate.js'></script>
		<!-- <script src='../assets/js/main.js'></script> -->
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
