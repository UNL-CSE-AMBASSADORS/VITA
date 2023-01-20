<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once "$root/server/user.class.php";
$USER = new User();
if (!$USER->isLoggedIn()) {
	header("Location: /unauthorized");
	die();
}
function wdnInclude($path)
{
$documentRoot = 'https://unlcms.unl.edu';
return readfile($documentRoot . $path);
}
?>
<!DOCTYPE html>
<html class="no-js" lang="en">
<head>
		<?php wdnInclude("/wdn/templates_5.1/includes/global/head-1.html"); ?>
	<!--
		Membership and regular participation in the UNL Web Developer Network is required to use the UNLedu Web Framework. Visit the WDN site at http://wdn.unl.edu/. Register for our mailing list and add your site or server to UNLwebaudit.
		All framework code is the property of the UNL Web Developer Network. The code seen in a source code view is not, and may not be used as, a template. You may not use this code, a reverse-engineered version of this code, or its associated visual presentation in whole or in part to create a derivative work.
		This message may not be removed from any pages based on the UNLedu Web Framework.

		$Id: php.fixed.dwt.php | cf0a670a0fd8db9e20a169941c55c838d7c2ba10 | Wed Dec 12 16:54:41 2018 -0600 | Eric Rasmussen	$
	-->
	<!-- TemplateBeginEditable name="doctitle" -->
	<title>Profile | TCAN / VITA Lincoln | University of Nebraska&ndash;Lincoln</title>
	<!-- TemplateEndEditable -->
		<?php wdnInclude("/wdn/templates_5.1/includes/global/head-2.html"); ?>
	<!-- TemplateBeginEditable name="head" -->
	<link rel="stylesheet" href="/dist/profile/profile.css">
	<!-- TemplateEndEditable -->
	<!-- TemplateParam name="class" type="text" value="" -->
</head>
<body class="@@(_document['class'])@@ unl" data-version="5.1">
<?php wdnInclude("/wdn/templates_5.1/includes/global/skip-nav.html"); ?>
<header class="dcf-header" id="dcf-header" role="banner">
		<?php wdnInclude("/wdn/templates_5.1/includes/global/header-global-1.html"); ?>
		<?php wdnInclude("/wdn/templates_5.1/includes/global/nav-global-1.html"); ?>
				<?php wdnInclude("/wdn/templates_5.1/includes/global/visit-global-1.html"); ?>
				<!-- TemplateBeginEditable name="visitlocal" -->
				<?php wdnInclude("/wdn/templates_5.1/includes/local/visit-local.html"); ?>
				<!-- TemplateEndEditable -->
				<?php wdnInclude("/wdn/templates_5.1/includes/global/visit-global-2.html"); ?>
				<?php wdnInclude("/wdn/templates_5.1/includes/global/apply-global-1.html"); ?>
				<!-- TemplateBeginEditable name="applylocal" -->
				<?php wdnInclude("/wdn/templates_5.1/includes/local/apply-local.html"); ?>
				<!-- TemplateEndEditable -->
				<?php wdnInclude("/wdn/templates_5.1/includes/global/apply-global-2.html"); ?>
				<?php wdnInclude("/wdn/templates_5.1/includes/global/give-global-1.html"); ?>
				<!-- TemplateBeginEditable name="givelocal" -->
				<?php wdnInclude("/wdn/templates_5.1/includes/local/give-local.html"); ?>
				<!-- TemplateEndEditable -->
				<?php wdnInclude("/wdn/templates_5.1/includes/global/give-global-2.html"); ?>
		<?php wdnInclude("/wdn/templates_5.1/includes/global/nav-global-2.html"); ?>
		<?php wdnInclude("/wdn/templates_5.1/includes/global/search.html"); ?>
		<?php wdnInclude("/wdn/templates_5.1/includes/global/header-global-2.html"); ?>
		<?php wdnInclude("/wdn/templates_5.1/includes/global/logo-lockup-1.html"); ?>
		<?php wdnInclude("/wdn/templates_5.1/includes/global/site-affiliation-1.html"); ?>
	<!-- TemplateBeginEditable name="affiliation" -->
	<!-- TemplateEndEditable -->
		<?php wdnInclude("/wdn/templates_5.1/includes/global/site-affiliation-2.html"); ?>
		<?php wdnInclude("/wdn/templates_5.1/includes/global/site-title-1.html"); ?>
	<!-- TemplateBeginEditable name="titlegraphic" -->
	<a class="unl-site-title-medium" href="/">TCAN / VITA Lincoln</a>
	<!-- TemplateEndEditable -->
		<?php wdnInclude("/wdn/templates_5.1/includes/global/site-title-2.html"); ?>
		<?php wdnInclude("/wdn/templates_5.1/includes/global/logo-lockup-2.html"); ?>
		<?php wdnInclude("/wdn/templates_5.1/includes/global/nav-toggle-group.html"); ?>
		<?php wdnInclude("/wdn/templates_5.1/includes/global/nav-menu-1.html"); ?>
		<?php wdnInclude("/wdn/templates_5.1/includes/global/nav-toggle-btn.html"); ?>
		<?php wdnInclude("/wdn/templates_5.1/includes/global/nav-menu-child-1.html"); ?>
	<!-- TemplateBeginEditable name="navlinks" -->
		<?php include "$root/sharedcode/navigation.php"; ?>
	<!-- TemplateEndEditable -->
		<?php wdnInclude("/wdn/templates_5.1/includes/global/nav-menu-child-2.html"); ?>
		<?php wdnInclude("/wdn/templates_5.1/includes/global/nav-menu-2.html"); ?>
</header>

<main class="dcf-main" id="dcf-main" role="main" tabindex="-1">

	<!-- TemplateBeginEditable name="hero" -->
	<div class="dcf-hero dcf-hero-default">
		<!-- TemplateEndEditable -->
		<div class="dcf-hero-group-1">
			<div class="dcf-breadcrumbs-wrapper">
				<nav class="dcf-breadcrumbs" id="dcf-breadcrumbs" role="navigation" aria-label="breadcrumbs">
					<!-- TemplateBeginEditable name="breadcrumbs" -->
					<ol>
						<li><a href="https://www.unl.edu/">Nebraska</a></li>
						<li><a href="/">TCAN / VITA Lincoln</a></li>
						<li><a href="/volunteer">Staff</a></li>
						<li><span aria-current="page">Profile</span></li>
					</ol>
					<!-- TemplateEndEditable -->
				</nav>
			</div>
			<header class="dcf-page-title" id="dcf-page-title">
				<!-- TemplateBeginEditable name="pagetitle" -->
				<h1>Profile</h1>
				<!-- TemplateEndEditable -->
			</header>
			<!-- TemplateBeginEditable name="herogroup1" -->
			<!-- TemplateEndEditable -->
		</div>
		<!-- TemplateBeginEditable name="herogroup2" -->
		<div class="dcf-hero-group-2">
		</div>
		<!-- TemplateEndEditable -->
	</div>
	<div class="dcf-main-content dcf-wrapper">
		<!-- TemplateBeginEditable name="maincontentarea" -->
		<!-- Personal Information -->
		<div class="dcf-bleed">
			<div class="dcf-wrapper dcf-pb-8">
				<div class="personal-info">
					<h2 class="inline">Personal Information</h2>
					<button class="dcf-btn dcf-btn-secondary dcf-float-right" id="personalInformationEditButton">Edit</button>

					<div>
						<label class="dcf-label" for="firstName" id="firstNameLabel">First Name:</label>
						<span id="firstNameText"></span>
						<input type="text" id="firstNameInput" style="display:none;" />
					</div>

					<div>
						<label class="dcf-label" for="lastName" id="lastNameLabel">Last Name:</label>
						<span id="lastNameText"></span>
						<input type="text" id="lastNameInput" style="display:none;" />
					</div>

					<div>
						<label class="dcf-label" for="email" id="emailLabel">Email:</label>
						<span id="emailText"></span>
						<input type="text" id="emailInput" style="display:none;" disabled />
					</div>

					<div>
						<label class="dcf-label" for="phoneNumber" id="phoneNumberLabel">Phone Number:</label>
						<span id="phoneNumberText"></span>
						<input type="text" id="phoneNumberInput" style="display:none;" />
					</div>

					<button class="dcf-btn dcf-btn-secondary" id="personalInformationSaveButton" style="display:none;">Save</button>
					<button class="dcf-btn dcf-btn-primary" id="personalInformationCancelButton" style="display:none;">Cancel</button>
				</div>
			</div>
		</div>
		<!-- TemplateEndEditable -->
	</div>
</main>
<footer class="dcf-footer" id="dcf-footer" role="contentinfo">
	<!-- TemplateBeginEditable name="optionalfooter" -->
	<!-- TemplateEndEditable -->
		<?php wdnInclude("/wdn/templates_5.1/includes/global/footer-global-1.html"); ?>
	<!-- TemplateBeginEditable name="contactinfo" -->
		<?php include "$root/sharedcode/localFooter.html"; ?>
	<!-- TemplateEndEditable -->
		<?php wdnInclude("/wdn/templates_5.1/includes/global/footer-global-2.html"); ?>
</footer>
<?php wdnInclude("/wdn/templates_5.1/includes/global/noscript.html"); ?>
<?php wdnInclude("/wdn/templates_5.1/includes/global/js-body.html"); ?>
<!-- TemplateBeginEditable name="jsbody" -->
<?php require_once "$root/server/global_includes.php"; ?>
<script src="/dist/profile/profile.js"></script>
<!-- TemplateEndEditable -->
</body>
</html>
