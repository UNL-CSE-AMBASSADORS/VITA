<?php
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);

	require_once "$root/server/user.class.php";
	$USER = new User();
	if (!$USER->isLoggedIn()) {
		header("Location: /unauthorized");
		die();
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Profile Edit</title>
	<?php
		$root = realpath($_SERVER["DOCUMENT_ROOT"]);
		require_once "$root/server/header.php";
	?>
	<link href="/assets/css/main.css" rel="stylesheet">
	<link href="/assets/css/form.css" rel="stylesheet">
</head>
<body>
	<?php
		$page_subtitle = 'Volunteer Profile';
		require_once "$root/components/nav.php";
	?>
	<!-- TODO, fix the weird text overlay issue, however, not a huge priority so not finished -->
	<div>
		<div class="container">
			<div class="row justify-content-center">
				<div class="col col-12 col-sm-8">
					<div id="responsePlaceholder" style="display: none;"></div>
					<form action="" autocomplete="off" class="cmxform" id="vitaProfileEdit" method="post" name="vitaProfileEdit">
						<h2 class="form-title">Edit Volunteer Profile</h2>

						<div class="form-textfield">
							<input class='' id="firstNameProfile" name="firstNameProfile" required="" type="text">
							<span class="form-bar"></span>
							<label class="form-label form-required" for="firstNameProfile">First Name</label>
						</div>

						<div class="form-textfield">
							<input class='' id="lastNameProfile" name="lastNameProfile" required="" type="text">
							<span class="form-bar"></span>
							<label class="form-label vita-form-required" for="lastNameProfile">Last Name</label>
						</div>

						<div class="form-textfield">
							<input class="" id="phoneProfile" name="phoneProfile" required="" type="tel">
							<span class="form-bar"></span>
							<label class="form-label form-required" for="phoneProfile">Phone Number</label>
						</div>

						<div class="form-textfield">
							<input class="" id="emailProfileStatic" name="emailProfile" required="" type="text">
							<span class="form-bar"></span>
							<label class="form-label form-required" for="emailProfile">Email</label>
						</div>
						<div class="form-group" style="margin-bottom: 10px;" required="">
							<select id="languageSkills" class="form-control" multiple="multiple">
								<option value='' disabled selected>Foreign Language</option>
								<option value="None">None</option>
								<option value="Spanish">Spanish</option>
								<option value="Arabic">Arabic</option>
								<option value="Vietnamese">Vietnamese</option>
								<option value="Other">Other</option>
							</select>
						</div>
						<div class="apptSelect form-group">
							<h5>Choose Your Shift Times</h2>
				    <select id="shiftLocation" class="form-control" onchange="allowShiftSelect()">
							<option></option>
						</select>
						<select id="shiftTime" class="form-control" disabled>
							<option></option>
						</select> <button>+</button>

					 </div>

						<input type="submit" value="Submit" class="submit button vita-background-primary" onclick="editSubmit()">
					</form>
				</div>
				<?php require_once "$root/server/footer.php" ?>
			</div>
		</div>
	</div>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"> </script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/jquery.validate.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/additional-methods.min.js"></script>
	<script src="/profile/profile.js"></script>
	<script src="/profile/multipleSelect.js"></script>
	<script src="/assets/js/form.js"></script>
</body>
</html>
