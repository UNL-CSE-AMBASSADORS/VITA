<?php $root = realpath($_SERVER["DOCUMENT_ROOT"]) ?>
<!DOCTYPE html>
<html class="no-js" lang="">
<head>
	<title>VITA Lincoln</title>
	<?php require_once "$root/server/header.php" ?>
	<link rel="stylesheet" href="/assets/css/main.css">
</head>
<body>
	<?php require_once "components/nav.php" ?>

	<div class="jumbotron jumbotron-fluid mb-0">
		<div class="container-fluid">
			<img id="main-banner" class="img-fluid mx-auto d-block" src="assets/res/VITA-Coalition-Web-Banner.png" alt="Lincoln VITA Coalition">
		</div>
	</div>

	<div class="container-fluid bg-secondary">
		<div class="container">
			<div class="row">
				<p class="col vita-block-highlight my-4">Qualified students and community members can have their tax return prepared AT NO CHARGE by qualified student and community volunteers.</p>
			</div>
		</div>
	</div>

	<div class="container my-4">
		<div class="row">
			<h2 class="col col-12 col-sm-4">Who qualifies to receive assistance?</h2>
			<p class="col col-12 col-sm-8">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi aliquet vitae lectus non auctor. Suspendisse quis lacus neque. Integer nibh sem, vulputate et rhoncus suscipit, consequat id est. Vivamus efficitur, nibh vitae finibus sodales, elit sem viverra quam, vel fringilla ligula sapien a tellus. Suspendisse ac velit facilisis, vestibulum neque vitae, convallis metus. Maecenas dignissim iaculis accumsan. Nunc vulputate felis quis ullamcorper fermentum. Mauris mauris massa, molestie at congue vitae, accumsan sed lorem. Nam at rutrum ante.</p>
		</div>
		<div class="row justify-content-center mx-0">
			<a class="col col-12 col-sm-4 btn btn-primary" href="/signup">Make an Appointment</a>
		</div>
	</div>
	<hr>
	<div class="container my-4">
		<div class="row">
			<h2 class="col col-12 col-sm-4">Volunteers</h2>
			<h3 class="col col-12 col-sm-8">How to Get Started</h3>
			<p class="col col-12 col-sm-8 ml-auto">Getting started is easy! Register below for any of our volunteer positions. Sign up ends December 4th.  If you are uncomfortable with actually handling tax returns, there are still opportunities for you.  The Lincoln Tax Coalition is always in need of greeters and interpreters.  Apply for the volunteer position that works best for you.</p>
		</div>
		<div class="row justify-content-center mx-0">
			<a class="col col-12 col-sm-4 btn btn-primary" href="/login">Volunteer login</a>
		</div>
	</div>
	<?php
		require_once "$root/server/footer.php";
	?>
</body>
</html>
