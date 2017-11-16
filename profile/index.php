<!DOCTYPE html>
<html>
<head>
	<title>VITA lincoln</title>
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
  <div>
    <div class="container">
      <div class="row justify-content-center">
        <div class="col col-12 col-sm-8">
              <h2 class="form-title" id="nameHeaderProfile"></h2>
              <h4>Name</h4>
              <p id="nameProfile"></p>
              <h4>Phone Number</h4>
              <p id="phoneProfile"></p>
              <h4>Email</h4>
              <a class="emailProfileStatic" href="mailto:joe@example.com?subject=feedback" id="emailProfile"></a>
              <h4>Foreign Languages</h4>
              <ul id="#languageSkills">
              </ul>
              <h4>Tax Abilities</h4>
              <ul id="#taxSkills">
              </ul>
              <h4>Signed Up Shifts</h4>
                <ul id="#sbTwo">
                </ul>
                <a href="/profile/profileedit/index.php"><button>Edit Page</button></a>
            </div>
          </div>
        </div>

	<?php require_once "$root/server/footer.php" ?>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"> </script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/jquery.validate.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/additional-methods.min.js"></script>
	<script src="/profile/profilepage.js"></script>
	<script src="/profile/multipleSelect.js"></script>
	<script src="/assets/js/form.js"></script>
</body>
</html>
