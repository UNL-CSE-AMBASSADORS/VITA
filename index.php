<?php $root = realpath($_SERVER["DOCUMENT_ROOT"]) ?>
<!DOCTYPE html>
<html class='no-js' lang=''>
<head>
	<title>VITA Lincoln</title>
	<?php require_once "$root/server/header.php" ?>
	<link rel='stylesheet' href='/assets/css/main.css'>
</head>
<body>
	<?php require_once 'components/nav.php' ?>

	<div class='section'>
		<div class='sub-section'>
			<img id='main-banner' src='assets/res/VITA-Coalition-Web-Banner.png' alt='Lincoln VITA Coalition'>
		</div>
	</div>

	<div class="section theme-medium">
		<div class="sub-section">
			<p class="vita-block-highlight">Qualified students and community members can have their tax return prepared AT NO CHARGE by qualified student and community volunteers.</p>
		</div>
	</div>

	<div class='section'>
		<div class='sub-section'>
			<h2>Who qualifies to receive assistance?</h2>
			<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi aliquet vitae lectus non auctor. Suspendisse quis lacus neque. Integer nibh sem, vulputate et rhoncus suscipit, consequat id est. Vivamus efficitur, nibh vitae finibus sodales, elit sem viverra quam, vel fringilla ligula sapien a tellus. Suspendisse ac velit facilisis, vestibulum neque vitae, convallis metus. Maecenas dignissim iaculis accumsan. Nunc vulputate felis quis ullamcorper fermentum. Mauris mauris massa, molestie at congue vitae, accumsan sed lorem. Nam at rutrum ante.</p>

			<a class="button-xl vita-background-primary" href="/signup">Make an Appointment</a>
		</div>
	</div>
	<hr>
	<div class='section'>
		<div class='sub-section'>
			<h2>Volunteers</h2>
			<h3>How to Get Started</h3>
			<p>Getting started is easy! Register below for any of our volunteer positions. Sign up ends December 4th.  If you are uncomfortable with actually handling tax returns, there are still opportunities for you.  The Lincoln Tax Coalition is always in need of greeters and interpreters.  Apply for the volunteer position that works best for you.</p>

			<a class="button vita-background-primary" href="/login">Volunteer login</a>
		</div>
	</div>
	<?php
		require_once "$root/server/footer.php";
	?>
</body>
</html>
