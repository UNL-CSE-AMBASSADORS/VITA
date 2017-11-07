<?php $root = realpath($_SERVER["DOCUMENT_ROOT"]) ?>
<!DOCTYPE html>
<html>
<head>
	<title>VITA Lincoln</title>
	<?php $root = realpath($_SERVER["DOCUMENT_ROOT"]) ?> <?php require_once "$root/server/header.php" ?>
	<!-- <link href="/assets/css/main.css" rel="stylesheet"> -->
	<link href="/assets/css/form.css" rel="stylesheet"><?php
			require_once "../server/header.php";
		?>
</head>
<body>
	<?php
		$page_subtitle = 'Create Volunteer Profile';
		require_once "$root/components/nav.php";
	?>
	<div>
		<div class="container">
			<div class="row justify-content-center">
				<div class="col col-12 col-sm-8">
					<div id="responsePlaceholder" style="display: none;"></div>
					<form action="" autocomplete="off" class="cmxform" id="vitaProfileEdit" method="post" name="vitaProfileEdit">
						<h2 class="form-title">Create Volunteer Profile</h2>

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
							<input class="" id="emailProfile" name="emailProfile" required="" type="text">
							<span class="form-bar"></span>
							<label class="form-label form-required" for="emailProfile">Email</label>
						</div>
						<div class="form-group" style="margin-bottom: 10px;" required="">
							<select id="languageSkills" class="form-control">
								<option value='' disabled selected>Foreign Language</option>
								<option value="None">None</option>
								<option value="Spanish">Spanish</option>
								<option value="Arabic">Arabic</option>
								<option value="Vietnamese">Vietnamese</option>
								<option value="Other">Other</option>
							</select>
						</div>
						<div class="form-group" style="margin-bottom: 10px;" required="">
					<label class="form-label form-required" for="taxSkills">Can you file taxes?</label>
						<select id="taxSkills" class="form-control" onchange="taxFunction();" name="taxSkills">
							<option value="No">No</option>
							<option value="Yes">Yes</option>
						</select>
					</div>
					<div class="form-group" style="margin-bottom: 10px;" id="skillType">
					</div>
						<div class="apptSelect form-group">
						<h5>Choose Your Shift Times</h2>
				     <select id="sbOne" multiple="multiple" class="form-control">
				         <option value="1">Alpha</option>
				         <option value="2">Beta</option>
				         <option value="3">Gamma</option>
				         <option value="4">Delta</option>
				         <option value="5">Epsilon</option>
				     </select>

				     <select id="sbTwo" multiple="multiple" class="form-control">
				         <option value="6">Zeta</option>
				         <option value="7">Eta</option>
				     </select>

				     <br />

				     <input type="button" id="left" value="<" />
				     <input type="button" id="right" value=">" />
				     <input type="button" id="leftall" value="<<" />
				     <input type="button" id="rightall" value=">>" />
					 </div>

						<input type="submit" value="Submit" class="submit button vita-background-primary">
					</form>
				</div><?php require_once "$root/server/footer.php" ?>
			</div>
		</div>
	</div>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"> </script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/jquery.validate.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/additional-methods.min.js"></script>
	<script src="/profilepage/profilepage.js"></script>
	<script src="/profilepage/multipleSelect.js"></script>
	<script src="/assets/js/form.js"></script>
</body>
</html>
