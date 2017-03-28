<!doctype html>
<html class="no-js" lang="">
    <head>
        <title>Queue Test</title>

        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="apple-touch-icon" href="apple-touch-icon.png">
        <!-- Place favicon.ico in the root directory -->

        <link rel="stylesheet" href="../assets/css/normalize.css">
        <link rel="stylesheet" href="../assets/css/main.css">
        <link rel='stylesheet' href='../assets/css/queue.css'>

        <script src="../assets/js/vendor/modernizr-2.8.3.min.js"></script>
    </head>
    <body>
        <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

		<div class='header'>
			<div class='vita-volunteer-count'>Volunteers on duty: 0</div>
			<img class='vita-header-curtain' src='../assets/res/QueueCurtain.svg'>
			<img class='vita-exit-fullscreen' src='../assets/res/GenericExit.svg' onclick='alert("This ought to close the window")'>
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

        <script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
        <script>window.jQuery || document.write('<script src="../assets/js/jquery-3.1.1.min.js"><\/script>')</script>
        <script src="../assets/js/plugins.js"></script>
        <script src="../assets/js/main.js"></script>

    	<script src='../assets/js/queue.js'></script>

    	<?php require '../assets/server/queue.php' ?>

        <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
        <script>
            (function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
            function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
            e=o.createElement(i);r=o.getElementsByTagName(i)[0];
            e.src='https://www.google-analytics.com/analytics.js';
            r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
            ga('create','UA-XXXXX-X','auto');ga('send','pageview');
        </script>
    </body>
</html>
