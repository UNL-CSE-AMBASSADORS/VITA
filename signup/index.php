<?php $root = realpath($_SERVER["DOCUMENT_ROOT"]) ?>
<!DOCTYPE html>
<html class="no-js" lang="">
<head>
	<title>VITA Appointment Signup</title>
	<?php require_once "$root/server/header.php" ?>
	<link rel="stylesheet" href="/assets/css/form.css">
	<link rel="stylesheet" href="/signup/signup.css">
</head>
<body>
	<?php
		$page_title = 'Signup for an appointment';
		require_once "$root/components/nav.php";
	?>

	<div class="container">
		<div class="row justify-content-center">
			<div class="col col-12 col-sm-8">
				<div id="responsePlaceholder" style="display: none;"></div>
				<form class="cmxform" id="vitaSignupForm" method="post" action="" autocomplete="off">
					<h2 class="form-title">Sign Up for a VITA Appointment</h2>

					<div class="form-textfield">
						<input type="text" name="firstName" id="firstName">
						<span class="form-bar"></span>
						<label class="form-label form-required" for="firstName">First Name</label>
					</div>

					<div class="form-textfield">
						<input type="text" name="lastName" id="lastName" required>
						<span class="form-bar"></span>
						<label class="form-label form-required" for="lastName">Last Name</label>
					</div>

					<div class="form-textfield">
						<input type="email" name="email" id="email" required>
						<span class="form-bar"></span>
						<label class="form-label form-required" for="email">Email</label>
					</div>

					<div class="form-textfield">
						<input type="text" name="phone" id="phone" required>
						<span class="form-bar"></span>
						<label class="form-label form-required" for="phone">Phone Number</label>
					</div>

					<h3 class="form-subheading">Appointment Information</h3>

					<?php
						require_once "$root/server/signup.php";
						getLitmusQuestions();
					?>

					<input type="submit" value="Submit" class="submit button vita-background-primary">
				</form>
			</div>
		</div>
	</div>
	<?php require_once "$root/server/footer.php" ?>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/jquery.validate.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/additional-methods.min.js"></script>
	<script src="/signup/signup.js"></script>
	<script src="/assets/js/form.js"></script>
</body>
</html>
