<!DOCTYPE html>
<html class="no-js" lang="">
  <head>
  <title>Signup Test</title>
  <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/form.css">
  <link rel="stylesheet" href="../assets/css/signup.css">
  <?php
    require_once "../server/header.php";
  ?>
  </head>
  <body>
    <div  class="vita-form-container">
      <form class="cmxform" id="vitaSignupForm" method="get" action="" autocomplete="off">
        <h1 class="vita-form-title">Sign Up for a VITA Appointment</h1>

        <div class="vita-form-textfield">
          <input class="" type="text" name="firstName" id="firstName" required>
          <span class="vita-form-bar"></span>
          <label class="vita-form-label vita-form-required" for="firstName">First Name</label>
        </div>

        <div class="vita-form-textfield">
          <input class="" type="text" name="lastName" id="lastName" required>
          <span class="vita-form-bar"></span>
          <label class="vita-form-label vita-form-required" for="lastName">Last Name</label>
          <label id="lastName-error" class="error" for="lastName"></label>
        </div>

        <div class="vita-form-textfield">
          <input class="" type="email" name="email" id="email" required>
          <span class="vita-form-bar"></span>
          <label class="vita-form-label vita-form-required" for="email">Email</label>
        </div>

        <div class="vita-form-textfield">
          <input class="" type="text" name="phone" id="phone" required>
          <span class="vita-form-bar"></span>
          <label class="vita-form-label vita-form-required" for="phone">Phone Number</label>
        </div>

        <div class="vita-form-textfield">
          <input class="" type="text" name="address1" id="address1" required>
          <span class="vita-form-bar"></span>
          <label class="vita-form-label vita-form-required" for="address1">Address Line 1</label>
          <span class="vita-form-hint">(Street address, P.O. box, company name, c/o)</span>
        </div>

        <div class="vita-form-textfield">
          <input class="" type="text" name="address2" id="address2">
          <span class="vita-form-bar"></span>
          <label class="vita-form-label" for="address2">Address Line 2</label>
          <span class="vita-form-hint">(Apartment, suite , unit, building, floor, etc.)</span>
        </div>

        <div class="vita-form-textfield">
          <input class="" type="text" name="city" id="city" required>
          <span class="vita-form-bar"></span>
          <label class="vita-form-label vita-form-required" for="city">City / Town</label>
        </div>

        <div class="vita-form-textfield">
          <input class="" type="text" name="state" id="state" required>
          <span class="vita-form-bar"></span>
          <label class="vita-form-label vita-form-required" for="state">State</label>
          <span class="vita-form-hint">(2 character state code; e.g. NE for Nebraska)</span>
        </div>

        <div class="vita-form-textfield">
          <input class="" type="text" name="zip" id="zip" required>
          <span class="vita-form-bar"></span>
          <label class="vita-form-label vita-form-required" for="zip">Zip / Postal Code</label>
        </div>

        <h2 class="vita-form-subheading">Background Information</h2>

        <div class="vita-form-select">
          <label for="pharmacist" class="vita-form-label vita-form-required">Are you a pharmacist?</label>
          <select id="pharmacist" class="required">
      			<option>No</option>
      			<option>Yes</option>
      		</select>
      		<div class="vita-form-select__arrow"></div>
          <span class="vita-form-hint">(Street address, P.O. box, company name, c/o)</span>
      	</div>

        <div class="vita-form-select">
          <label for="gamble" class="vita-form-label vita-form-required">How often do you gamble?</label>
          <select id="gamble" class="required">
      			<option>Never</option>
      			<option>Rarely</option>
            <option>Occasionally</option>
            <option>Frequenty</option>
      		</select>
      		<div class="vita-form-select__arrow"></div>
      	</div>

        <div class="vita-form-select">
          <label for="military" class="vita-form-label">Indicate your military status</label>
          <select id="military">
      			<option>None</option>
      			<option>Active Duty</option>
            <option>Active Reserve</option>
            <option>Inactive Reserve</option>
            <option>Disabled Veteran</option>
      		</select>
      		<div class="vita-form-select__arrow"></div>
      	</div>


        <h2 class="vita-form-subheading">Language Requirements</h2>

        <label for="english" class="vita-form-control vita-form-checkbox">
          Can you speak fluent English?
          <input type="checkbox" id="english">
          <div class="vita-form-control__indicator"></div>
        </label>

        <div class="vita-form-textfield">
          If not, what is your strongest language?
          <input class="" type="text" name="language" id="language">
          <span class="vita-form-bar"></span>
          <label class="vita-form-label" for="zip"></label>
        </div>


        <h2 class="vita-form-subheading">Appointment Information</h2>

        <div class="vita-form-select">
          <label for="location" class="vita-form-label vita-form-required">Location</label>
          <select id="location" class="required">
      			<option>5696 Lotheville Court</option>
      			<option>9 Utah Court</option>
      			<option>71499 Buhler Trail</option>
            <option>02122 Prairieview Place</option>
            <option>8 Scofield Road</option>
            <option>591 Oak Avenue</option>
      		</select>
      		<div class="vita-form-select__arrow"></div>
      	</div>

        <div class="vita-form-textfield">
          <input class="" type="date" name="date" id="date" required>
          <span class="vita-form-bar"></span>
          <label class="vita-form-label vita-form-label__always-floating vita-form-required" for="date">Date</label>
        </div>

        <input type="submit" value="Submit" class="submit vita-form-button background-primary">
      </form>
    </div>
    <?php
      require_once "../server/footer.php";
    ?>
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/jquery.validate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/additional-methods.min.js"></script>
    <script src='../assets/js/boilerplate.js'></script>
    <script src="../assets/js/signup.js"></script>
  </body>
</html>
