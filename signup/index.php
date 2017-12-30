<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
function wdnInclude($path)
{
	$documentRoot = 'https://unlcms.unl.edu';

	return readfile($documentRoot . $path);
}
?>
<!DOCTYPE html>
<html class="no-js" lang="en">
<head>
<?php wdnInclude("/wdn/templates_4.1/includes/metanfavico.html"); ?>
<!--
	Membership and regular participation in the UNL Web Developer Network is required to use the UNLedu Web Framework. Visit the WDN site at http://wdn.unl.edu/. Register for our mailing list and add your site or server to UNLwebaudit.
	All framework code is the property of the UNL Web Developer Network. The code seen in a source code view is not, and may not be used as, a template. You may not use this code, a reverse-engineered version of this code, or its associated visual presentation in whole or in part to create a derivative work.
	This message may not be removed from any pages based on the UNLedu Web Framework.

	$Id: php.fixed.dwt.php | 6edb0e1ee94038935f3821c6ce15dfd5c217b2e2 | Tue Dec 1 17:08:56 2015 -0600 | Kevin Abel  $
-->
<?php wdnInclude("/wdn/templates_4.1/includes/scriptsandstyles.html"); ?>
<!-- TemplateBeginEditable name="doctitle" -->
<title>Sign Up for a VITA Appointment | VITA Lincoln | University of Nebraska&ndash;Lincoln</title>
<!-- TemplateEndEditable -->
<!-- TemplateBeginEditable name="head" -->
<link rel="stylesheet" href="/assets/css/bootstrap.btn-group.min.css">
<link rel="stylesheet" href="/dist/assets/css/form.css">
<link rel="stylesheet" href="/dist/signup/signup.css">
<!-- TemplateEndEditable -->
<!-- TemplateParam name="class" type="text" value="" -->
</head>
<body class="@@(_document['class'])@@" data-version="4.1">
	<?php wdnInclude("/wdn/templates_4.1/includes/skipnav.html"); ?>
	<div id="wdn_wrapper">
		<input type="checkbox" id="wdn_menu_toggle" value="Show navigation menu" class="wdn-content-slide wdn-input-driver" />
		<?php wdnInclude("/wdn/templates_4.1/includes/noscript-padding.html"); ?>
		<header id="header" role="banner" class="wdn-content-slide wdn-band">
			<div id="wdn_header_top">
				<span id="wdn_institution_title"><a href="http://www.unl.edu/">University of Nebraska&ndash;Lincoln</a></span>
				<div id="wdn_resources">
					<?php wdnInclude("/wdn/templates_4.1/includes/wdnResources.html"); ?>
					<?php wdnInclude("/wdn/templates_4.1/includes/idm.html"); ?>
					<?php wdnInclude("/wdn/templates_4.1/includes/search.html"); ?>
				</div>
			</div>
			<div id="wdn_logo_lockup">
				<div class="wdn-inner-wrapper">
					<?php wdnInclude("/wdn/templates_4.1/includes/logo.html"); ?>
						<span id="wdn_site_affiliation"><!-- TemplateBeginEditable name="affiliation" --><!-- TemplateEndEditable --></span>
						<span id="wdn_site_title"><!-- TemplateBeginEditable name="titlegraphic" -->VITA Lincoln<!-- TemplateEndEditable --></span>
				</div>
			</div>
		</header>
		<div id="wdn_navigation_bar" class="wdn-band">
			<nav id="breadcrumbs" class="wdn-inner-wrapper" role="navigation" aria-label="breadcrumbs">
				<!-- TemplateBeginEditable name="breadcrumbs" -->
				<ul>
					<li><a href="http://www.unl.edu/" title="University of Nebraska&ndash;Lincoln" class="wdn-icon-home">UNL</a></li>
					<li><a href="/" title="VITA Lincoln">VITA Lincoln</a></li>
					<li>Need Assistance</li>
					<li>Sign Up for a VITA Appointment</li>
				</ul>
				<!-- TemplateEndEditable -->
			</nav>
			<div id="wdn_navigation_wrapper">
				<nav id="navigation" role="navigation" aria-label="main navigation">
					<!-- TemplateBeginEditable name="navlinks" -->
					<?php include "$root/sharedcode/navigation.php"; ?>
					<!-- TemplateEndEditable -->
					<?php wdnInclude("/wdn/templates_4.1/includes/navigation-addons.html"); ?>
				</nav>
			</div>
		</div>
		<div class="wdn-menu-trigger wdn-content-slide">
			<label for="wdn_menu_toggle" class="wdn-icon-menu">Menu</label>
			<?php wdnInclude("/wdn/templates_4.1/includes/share.html"); ?>
		</div>
		<main id="wdn_content_wrapper" role="main" class="wdn-content-slide" tabindex="-1">
			<div id="maincontent" class="wdn-main">
				<div id="pagetitle">
					<!-- TemplateBeginEditable name="pagetitle" -->
					<h1>Sign Up for a VITA Appointment</h1>
					<!-- TemplateEndEditable -->
				</div>
				<!-- TemplateBeginEditable name="maincontentarea" -->
				<?php 
					// TODO, THIS CODE WILL NEED TO BE REMOVED ONCE APPOINTMENT SIGN UP ACTUALLY STARTS
					date_default_timezone_set('America/Chicago'); // Use CST
					$now = date('Y-m-d H:i:s');
					$signupBeginsDate = '2018-01-15 00:00:00';
					require_once "$root/server/user.class.php";
					$USER = new User();
					if ($now < $signupBeginsDate && !$USER->isLoggedIn()) {
				?>
					<!-- BEFORE SIGN UP BEGINS -->
					<div class="wdn-band">
						<div class="wdn-inner-wrapper wdn-inner-padding-no-top">
							<h3>Appointment signup does not begin until January 15th, 2018. Please check back then.</h3>
						</div>
					</div>
				<?php } else { ?>
					<!-- AFTER SIGN UP BEGINS -->
					<div class="wdn-band">
						<div class="wdn-inner-wrapper wdn-inner-padding-no-top">
							<div id="responsePlaceholder" class="mt-5" style="display: none;"></div>
							<form class="cmxform mb-5" id="vitaSignupForm" method="post" action="" autocomplete="off">
								<p mt-2 mb-3>Unsure if VITA can help you? <a href="/questionnaire" target="_blank">Click here to find out.</a></p>

								<ul>
									<li class="form-textfield">
										<label class="form-label form-required" for="firstName">First Name</label>
										<input type="text" name="firstName" id="firstName" required>
									</li>

									<li class="form-textfield">
										<label class="form-label form-required" for="lastName">Last Name</label>
										<input type="text" name="lastName" id="lastName" required>
									</li>

									<li class="form-textfield">
										<label class="form-label" for="email">Email</label>
										<input type="email" name="email" id="email">
									</li>

									<li class="form-textfield">
										<label class="form-label form-required" for="phone">Phone Number</label>
										<input type="text" name="phone" id="phone" required>
									</li>
								</ul>


								<h3 class="form-subheading">Add Filing Dependents</h3>
								<p>Are any of your dependents filing a return during this appointment? If so, add them here.</p>
								<ul id="dependents"></ul>
								<button type="button" class="btn mb-3" id="addDependentButton">Add Dependent</button>


								<h3 class="form-subheading">Background Information</h3>

								<ul>
									<li class="form-radio" id="language">
										<label for="language" class="form-required">Which language will you require?</label>
										<div class="error-group">
											<div class="btn-group" data-toggle="buttons">
												<!-- NOTE: the values here are the ISO 639-2/T specfication for language codes (https://en.wikipedia.org/wiki/List_of_ISO_639-1_codes) -->
												<label class="wdn-button btn" for="languageEnglish">
													<input type="radio" id="languageEnglish" value="eng" name="languageRadio" required>English
												</label>
												<label class="wdn-button btn" for="languageSpanish">
													<input type="radio" id="languageSpanish" value="spa" name="languageRadio" required>Spanish
												</label>
												<label class="wdn-button btn" for="languageArabic">
													<input type="radio" id="languageArabic" value="ara" name="languageRadio" required>Arabic
												</label>
												<label class="wdn-button btn" for="languageVietnamese">
													<input type="radio" id="languageVietnamese" value="vie" name="languageRadio" required>Vietnamese
												</label>
											</div>
										</div>
									</li>

									<li class="form-radio" id="studentUNL">
										<label for="1" class="form-required">Are you a University of Nebraska-Lincoln student?</label>
										<div class="error-group">
											<div class="btn-group" data-toggle="buttons">
												<label class="wdn-button btn" for="studentyes">
													<input type="radio" id="studentyes" value="1" name="1" required>Yes
												</label>
												<label class="wdn-button btn" for="studentno">
													<input type="radio" id="studentno" value="2" name="1" required>No
												</label>
											</div>
										</div>
									</li>

									<li class="form-radio" id="studentInt" style="display: none;">
										<label for="2" class="form-required">Are you an International Student Scholar?</label>
										<div class="error-group">
											<div class="btn-group" data-toggle="buttons">
												<label class="wdn-button btn" for="studentIntyes">
													<input type="radio" id="studentIntyes" value="1" name="2" required>Yes
												</label>
												<label class="wdn-button btn" for="studentIntno">
													<input type="radio" id="studentIntno" value="2" name="2" required>No
												</label>
											</div>
										</div>
									</>

									<li class="form-radio" id="studentIntVisa" style="display: none;">
										<label for="3" class="form-required">What sort of visa are you on?</label>
										<div class="error-group">
											<div class="btn-group" data-toggle="buttons">
												<label class="wdn-button btn" for="f1">
													<input type="radio" id="f1" value="4" name="3" required>F-1
												</label>
												<label class="wdn-button btn" for="j1">
													<input type="radio" id="j1" value="5" name="3" required>J-1
												</label>
												<label class="wdn-button btn" for="h1b">
													<input type="radio" id="h1b" value="6" name="3" required>H1B
												</label>
											</div>
										</div>
									</li>

									<li class="form-radio" id="studentf1" style="display: none;">
										<label for="4" class="form-required">How long have you been in the United States?</label>
										<div class="error-group">
											<div class="btn-group" data-toggle="buttons">
												<label class="wdn-button btn" for="2012">
													<input type="radio" id="2012" value="7" name="4" required>2012 or earlier
												</label>
												<label class="wdn-button btn" for="2013">
													<input type="radio" id="2013" value="8" name="4" required>2013 or later
												</label>
											</div>
										</div>
									</li>

									<li class="form-radio" id="studentj1" style="display: none;">
										<label for="4" class="form-required">How long have you been in the United States?</label>
										<div class="error-group">
											<div class="btn-group" data-toggle="buttons">
												<label class="wdn-button btn" for="2015">
													<input type="radio" id="2015" value="9" name="4" required>2015 or earlier
												</label>
												<label class="wdn-button btn" for="2016">
													<input type="radio" id="2016" value="10" name="4" required>2016 or later
												</label>
											</div>
										</div>
									</li>

									<li class="form-radio" id="studenth1b" style="display: none;">
										<label for="5" class="">Have you been on this visa for less than 183 days and in the United States for less than 5 years?</label>
										<div class="error-group">
											<div class="btn-group form-required" data-toggle="buttons">
												<label class="wdn-button btn" for="studenth1byes">
													<input type="radio" id="studenth1byes" value="1" name="5" required>Yes
												</label>
												<label class="wdn-button btn" for="studenth1bno">
													<input type="radio" id="studenth1bno" value="2" name="5" required>No
												</label>
											</div>
										</div>
									</li>
								</ul>


								<h3 class="form-subheading">Appointment Information</h3>

								<div id="appointmentPicker">
									<div id="studentScholarAppointmentPicker" style="display:none">
										Student Scholar
									</div>
									<div id="datePicker" class="form-textfield">
										<input type="text" id="dateInput" name="dateInput" placeholder="Select a Date" required>
										<label class="form-label form-required form-label__always-floating">Date</label>
									</div>
									<div id="sitePicker" class="form-select" style="display: none;">
										<label class="form-label form-required" for="sitePickerSelect">Site</label>
										<select id="sitePickerSelect" name="sitePickerSelect" required></select>
										<div class="form-select__arrow"></div>
									</div>
									<div id="timePicker" class="form-select" style="display: none;">
										<label class="form-label form-required" for="timePickerSelect">Time</label>
										<select id="timePickerSelect" name="timePickerSelect" required></select>
										<div class="form-select__arrow"></div>
									</div>
								</div>

								<input type="submit" value="Submit" class="submit btn btn-primary mb-5 vita-background-primary">
							</form>
						</div>
					</div>
				<?php } ?> 
				<!-- TemplateEndEditable -->
			</div>
		</main>
		<footer id="footer" role="contentinfo" class="wdn-content-slide">
			<div id="wdn_optional_footer" class="wdn-band wdn-footer-optional">
				<div class="wdn-inner-wrapper">
					<!-- TemplateBeginEditable name="optionalfooter" -->
					<!-- TemplateEndEditable -->
				</div>
			</div>
			<div id="wdn_local_footer" class="wdn-band wdn-footer-local">
				<div class="wdn-inner-wrapper">
					<!-- TemplateBeginEditable name="contactinfo" -->
					<?php include "$root/sharedcode/localFooter.html"; ?>
					<!-- TemplateEndEditable -->
					<!-- TemplateBeginEditable name="leftcollinks" -->
					<!-- TemplateEndEditable -->
				</div>
			</div>
			<div id="wdn_global_footer" class="wdn-band wdn-footer-global">
				<div class="wdn-inner-wrapper">
					<?php wdnInclude("/wdn/templates_4.1/includes/globalfooter.html"); ?>
				</div>
			</div>
		</footer>
		<?php wdnInclude("/wdn/templates_4.1/includes/noscript.html"); ?>
	</div>
	<?php wdnInclude("/wdn/templates_4.1/includes/body_scripts.html"); ?>
	<?php require_once "$root/server/global_includes.php"; ?>
	<script src="/dist/signup/signup.js"></script>
</body>
</html>
