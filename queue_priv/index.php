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
		<div class='section box theme-light queue-details'>
			<div class='theme-white flex box queue'>
				<div class='sub-section flex queue-header'>
					<div class='wrap-left queue-position-wrap'>Pos.</div>
					<div class='wrap-left queue-name-wrap'>Name</div>
					<div class='wrap-right queue-time-wrap'>Time</div>
				</div>
				<div class='sub-section theme-white flex queue-table'></div> 
			</div>
			<div class='theme-white flex details'>

			</div>
		</div>
		<!-- <div class='section theme-light queue'>
			 <div class='sub-section flex queue-header'>
				<div class='wrap-left queue-position-wrap'>Pos.</div>
				<div class='wrap-left queue-name-wrap'>Name</div>
				<div class='wrap-right queue-time-wrap'>Time</div>
			</div> 
			<div class='sub-section theme-white flex queue-table'></div> 
		</div>  -->
		<?php require_once '../server/footer.php' ?>
	</body>
	<script src='https://cdnjs.cloudflare.com/ajax/libs/mustache.js/2.3.0/mustache.min.js'></script>
	<script src='/assets/js/queue.js'></script>
	<script class='queue-record-template' type='text/template'>
		<div class='queue-record'>
			<div class='wrap-left queue-position-wrap'>{{position}}</div>
			<div class='wrap-left queue-name-wrap'>{{name}}</div>
			<div class='wrap-right queue-time-wrap'>
				{{time}}
				{{#isOnTime}}<div class='flex queue-tag on-time-tag'>ON TIME</div>{{/isOnTime}}
				{{^isOnTime}}
					{{#isPresent}}<div class='flex queue-tag late-tag'>LATE</div>{{/isPresent}}
					{{^isPresent}}<div class='flex queue-tag no-show-tag'>NO SHOW</div>{{/isPresent}}
				{{/isOnTime}}
			</div>
		</div>
	</script>
</html>
