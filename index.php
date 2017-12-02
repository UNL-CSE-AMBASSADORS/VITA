<?php $root = realpath($_SERVER["DOCUMENT_ROOT"]) ?>
<!DOCTYPE html>
<html class="no-js" lang="">
<head>
	<title>VITA Lincoln</title>
	<?php require_once "$root/server/header.php" ?>
	<link rel="stylesheet" href="/assets/css/main.css">
</head>
<body>
	<?php require_once "$root/components/nav.php" ?>

	<div class="jumbotron jumbotron-fluid mb-0">
		<div class="container-fluid">
			<img id="main-banner" class="img-fluid mx-auto d-block" src="assets/res/VITA-Coalition-Web-Banner.png" alt="Lincoln VITA Coalition">
		</div>
	</div>

	<div class="container-fluid bg-secondary">
		<div class="container">
			<div class="row">
				<p class="col vita-block-highlight my-4">
					Qualified students and community members can have their tax return prepared AT NO CHARGE 
					by trained student and community volunteers.
				</p>
			</div>
		</div>
	</div>

	<div class="container my-4">
		<div class="row">
			<h2 class="col col-12 col-sm-4">What is the UNL Tax Credit Campaign?</h2>
			<p class="col col-12 col-sm-8">
				The UNL Tax Credit Campaign prepares tax returns for low-income, working families and students for free.
				It is sponsored by the University of Nebraska-Lincoln's Center for Civic Engagement in conjunction
				with the Lincoln Volunteer Income Tax Assistance (VITA) Coalition.
			</p>
		</div>
	</div>
	<hr>

	<div class="container my-4">
		<div class="row">
			<h2 class="col col-12 col-sm-4">Sign up for an Appointment</h2>
			<p class="col col-12 col-sm-8">
				Residential appointments begin January 21st and run through April 8th. Appointments for international
				returns begin March 6th and run through April 10th. You can sign up for appointments at the Nebraska East
				Union, Anderson Library, and Jackie Gaughan Multicultural Center sites through this site. Download the schedule
				to see all times for all sites and how to contact the sites. 
			</p>
		</div>
		<div class="row justify-content-end mx-0">
			<div class='col col-12 col-sm-4 px-3'>
				<a class="btn-block btn btn-primary" href="/signup">Make an Appointment</a>
			</div>
			<div class='col col-12 col-sm-4 px-3'>
				<a class="btn-block btn btn-primary" href="/server/download/downloadFile.php?file=2018_Schedule.pdf">Download Site Schedule</a>
			</div>
		</div>
	</div>
	<hr>

	<div class="container my-4">
		<div class="row">
			<h2 class="col col-12 col-sm-4">Documents to Bring</h2>
			<ul class="col col-12 col-sm-8">
				<li>Social Security Card and photo identification</li>
				<li>Social Security numbers and birth dates for all dependents</li>
				<li>Forms W-2 and all other income forms and tax documents for 2016</li>
				<li>Bank routing and account number for deposit/debit of any tax balance</li>
				<li>Tax identification numbers for any and all daycare providers</li>
				<li>Spouse, if filing jointly (both must be present to sign the return)</li>
				<li>If a post secondary student, you must bring your 1098T and student account information showing payment of tuition and qualified fees</li>
			</ul>
		</div>
	</div>
	<hr>

	<?php
		require_once "$root/server/footer.php";
	?>
</body>
</html>
