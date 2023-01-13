<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once "$root/server/user.class.php";
$USER = new User();
if (!$USER->hasPermission('edit_user_permissions')) {
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
	<title>User Management | TCAN / VITA Lincoln | University of Nebraska&ndash;Lincoln</title>
	<!-- TemplateEndEditable -->
		<?php wdnInclude("/wdn/templates_5.1/includes/global/head-2.html"); ?>
	<!-- TemplateBeginEditable name="head" -->
	<link rel="stylesheet" href="/dist/management/users/users.css" />
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
						<li><a href="/management">Management</a></li>
						<li><span aria-current="page">User Management</span></li>
					</ol>
					<!-- TemplateEndEditable -->
				</nav>
			</div>
			<header class="dcf-page-title" id="dcf-page-title">
				<!-- TemplateBeginEditable name="pagetitle" -->
				<h1>User Management</h1>
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
		<div class="dcf-bleed">
			<div class="dcf-wrapper">
				<a class="dcf-btn dcf-btn-primary dcf-float-right dcf-mb-5" id="add-user" href="#add-user-modal">Add User</a>
			</div>
			<div class="dcf-wrapper dcf-mb-8" id="user-management-table-row">
				<table class="table table-condensed table-hover dcf-table dcf-table-striped" id="user-management-table">
					<!-- table data injected through JS -->
				</table>
			</div>
		</div>

		<!-- Add User Modal -->
		<div class="hidden">
			<div class="modal" id="add-user-modal" tabindex="-1" role="dialog" aria-hidden="true">
				<div class="dcf-wrapper dcf-pt-8 dcf-pb-5">
					<h4>Add a new user</h4>
					<form id="add-user-form">
						<fieldset>
							<section class="form-group required-input">
								<label class="dcf-label" for="firstName">First Name:</label>
								<input type="text" class="dcf-input-text form-control" id="firstName" required>
							</section>
							<section class="form-group required-input">
								<label class="dcf-label" for="lastName">Last Name:</label>
								<input type="text" class="dcf-input-text form-control" id="lastName" required>
							</section>
							<section class="form-group required-input">
								<label class="dcf-label" for="email">Email Address:</label>
								<input type="text" class="dcf-input-text form-control" id="email" placeholder="student@huskers.unl.edu" required>
							</section>
							<section class="form-group">
								<label class="dcf-label" for="phone">Phone Number:</label>
								<input type="text" class="dcf-input-text form-control" id="phone">
							</section>
						</fieldset>
						<div class="dcf-pt-5">
							<button type="submit" class="dcf-btn dcf-btn-primary">Add User</button>
							<button type="button" class="dcf-btn dcf-btn-secondary close-modal-button">Close</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<!-- End Add User Modal -->


		<!-- Edit User  Modal -->
		<div class="hidden">
			<div class="modal" id="edit-user-modal" tabindex="-1" role="dialog" aria-hidden="true">
				<div class="dcf-wrapper dcf-pt-8 dcf-pb-5">
					<h4>Edit User Information</h4>
					<form id="edit-user-form">
						<fieldset>
							<section class="form-group required-input">
								<label class="dcf-label" for="editFirstName">First Name:</label>
								<input type="text" class="dcf-input-text form-control" id="editFirstName" required>
							</section>
							<section class="form-group required-input">
								<label class="dcf-label" for="editLastName">Last Name:</label>
								<input type="text" class="dcf-input-text form-control" id="editLastName" required>
							</section>
							<section class="form-group required-input">
								<label class="dcf-label" for="editEmail">Email Address:</label>
								<input type="text" class="dcf-input-text form-control" id="editEmail" placeholder="student@huskers.unl.edu" required>
								<p>Please note, changing the email address will affect the email a user logs in with. Do not do this unless you are absolutely certain of the change.</p>
							</section>
							<section class="form-group">
								<label class="dcf-label" for="editPhoneNumber">Phone Number:</label>
								<input type="text" class="dcf-input-text form-control" id="editPhoneNumber">
							</section>
						</fieldset>
						<div class="dcf-pt-5">
							<button type="submit" class="dcf-btn dcf-btn-primary">Save</button>
							<button type="button" class="dcf-btn dcf-btn-secondary close-modal-button">Close</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<!-- End Edit User Modal -->


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
<script src="/dist/management/users/users.js"></script>
<!-- TemplateEndEditable -->
</body>
</html>
