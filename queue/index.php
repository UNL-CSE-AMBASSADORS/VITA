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

		<div class='vita-header'>
			<div class='vita-volunteer-count'>Volunteers on duty: 0</div>
			<img class='vita-header-curtain' src='../assets/res/queue_curtain.svg'>
			<img class='vita-exit-fullscreen' src='../assets/res/generic_exit.svg'>
		</div>

		<div class='vita-queue-wrapper'>
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
