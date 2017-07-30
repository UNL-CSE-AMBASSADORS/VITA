<!DOCTYPE html>
<html class='no-js theme-light' lang="">
<head>
	<title>Queue Test</title>
	<?php require_once '../server/header.php' ?>
	<link rel='stylesheet' href='/assets/css/queue.css'>
	<meta http-equiv='refresh' content='600'/>
</head>
<body>
	<!--[if lt IE 8]>
		<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
	<![endif]-->
	<?php
		$page_title = 'Queue';
		require_once '../components/nav.php';
	?>

	<div class='section dashboard'>
		<div class='sub-section-full'>
			<!-- <div class='wrap-left flex volunteers-wrap'>
				<div class='volunteers-lbl'>Volunteers:</div>
				<div class='volunteers-count'></div>
			</div> -->
			<div class='wrap-left queue-size-wrap'>
				<div class='queue-size-lbl'>Queue:</div>
				<div class='queue-size-count'></div>
			</div>
			<div class='wrap-right clock-wrap'>
				<div class='clock-time'></div>
				<div class='clock-period'>
					<div class='clock-am'>AM</div>
					<div class='clock-pm'>PM</div>
				</div>
			</div>
			<div class='wrap-right flex date-wrap'>
				<i class='material-icons no-select date-back'>keyboard_arrow_up</i>
				<div class='date'></div>
				<i class='material-icons no-select date-forward'>keyboard_arrow_down</i>
			</div>
		</div>
	</div>

	<div class='section theme-light queue'>
		<div class='sub-section flex queue-header'>
			<div class='wrap-left queue-position-wrap'>Pos.</div>
			<div class='wrap-left queue-name-wrap'>Name</div>
			<div class='wrap-right queue-time-wrap'>Time</div>
		</div>
		<div class='sub-section theme-white flex queue-table'></div>
	</div>
	<?php require_once '../server/footer.php' ?>
</body>
<script src='https://cdnjs.cloudflare.com/ajax/libs/mustache.js/2.3.0/mustache.min.js'></script>
<script src='/assets/js/queue.js'></script>
<script class='queue-record-template' type='text/template'>
	<div class='queue-record'>
		<div class='wrap-left queue-position-wrap'>{{position}}</div>
		<div class='wrap-left queue-name-wrap'>{{name}}</div>
		<div class='wrap-right queue-time-wrap'>
			{{#isOnTime}}<div class='flex queue-tag on-time-tag'>ON TIME</div>{{/isOnTime}}
			{{^isOnTime}}
				{{#isPresent}}<div class='flex queue-tag late-tag'>LATE</div>{{/isPresent}}
				{{^isPresent}}<div class='flex queue-tag no-show-tag'>NO SHOW</div>{{/isPresent}}
			{{/isOnTime}}
			{{time}}
		</div>
	</div>
</script>
</html>
