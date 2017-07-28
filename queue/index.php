<!DOCTYPE html>
<html class="no-js" lang="">
    <head>
		<title>Queue Test</title>
		<?php require_once '../server/header.php' ?>
		<link rel='stylesheet' href='../assets/css/queue.css'>
		<meta http-equiv='refresh' content='600'/>
    </head>
    <body>
        <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
		<![endif]-->
		<?php require_once '../components/nav.php' ?>
		<div class='section theme-light dashboard'>
			<div class='sub-section-full'>
				<div class='wrap-left flex volunteers-wrap'>
					<div class='volunteers-lbl'>Volunteers:</div>
					<div class='volunteers-count'>5</div>
				</div>
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
			</div>
		</div>

		<div class='section queue'>
			<div class='sub-section flex queue-header'>
				<div class='wrap-left queue-position-wrap'>Pos.</div>
				<div class='wrap-left queue-name-wrap'>Name</div>
				<div class='wrap-right queue-time-wrap'>Time</div>
			</div>
			<div class='sub-section flex queue-table'></div> 
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
				{{#isLate}}
					<div class='flex queue-tag late-tag'>LATE</div>
				{{/isLate}}
				{{time}}
			</div>
		</div>
	</script>
</html>
