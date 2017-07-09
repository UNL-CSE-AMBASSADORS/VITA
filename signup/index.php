<!DOCTYPE html>
<html class="no-js" lang="">
<head>
  <title>VITA Appointment Signup</title>
  <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/form.css">
  <link rel="stylesheet" href="../assets/css/signup.css">
  <?php
    require_once "../server/header.php";
  ?>
</head>
<body>

  <div class="vita-form-container vita-signup-form">
    <form class="cmxform" id="vitaSignupForm" method="post" action="" autocomplete="off">
      <h1 class="vita-form-title">Sign Up for a VITA Appointment</h1>

      <div class="vita-form-textfield">
        <input type="text" name="firstName" id="firstName">
        <span class="vita-form-bar"></span>
        <label class="vita-form-label vita-form-required" for="firstName">First Name</label>
      </div>

      <div class="vita-form-textfield">
        <input type="text" name="lastName" id="lastName" required>
        <span class="vita-form-bar"></span>
        <label class="vita-form-label vita-form-required" for="lastName">Last Name</label>
      </div>

      <div class="vita-form-textfield">
        <input type="email" name="email" id="email" required>
        <span class="vita-form-bar"></span>
        <label class="vita-form-label vita-form-required" for="email">Email</label>
      </div>

      <div class="vita-form-textfield">
        <input type="text" name="phone" id="phone" required>
        <span class="vita-form-bar"></span>
        <label class="vita-form-label vita-form-required" for="phone">Phone Number</label>
      </div>

      <h2 class="vita-form-subheading">Appointment Information</h2>

      <?php
        require_once "../server/signup.php";
        getLitmusQuestions();
      ?>

      <input type="submit" value="Submit" class="submit vita-form-button vita-background-primary">
    </form>
  </div>
  <?php
    require_once "../server/footer.php";
    require_once "../components/nav.php";
  ?>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/jquery.validate.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/additional-methods.min.js"></script>
  <script src='../assets/js/boilerplate.js'></script>
  <script src="../assets/js/signup.js"></script>
</body>
</html>
