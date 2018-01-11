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
<title>Register | VITA Lincoln | University of Nebraska&ndash;Lincoln</title>
<!-- TemplateEndEditable -->
<!-- TemplateBeginEditable name="head" -->
<link rel="stylesheet" href="/dist/assets/css/form.css">
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
					<li><a href="/volunteer" title="Volunteer">Volunteer</a></li>
					<li>Register</li>
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
					<h1>Register</h1>
					<!-- TemplateEndEditable -->
				</div>
				<!-- TemplateBeginEditable name="maincontentarea" -->
				<?php
					require_once "$root/server/config.php";

					if(isset($_REQUEST['token']) && strlen($_REQUEST['token']) == 32){
						## Define Passed Variables
						$token = $_REQUEST['token'];

						## Make Sure There Is A Row With That Token
						$stmt = $DB_CONN->prepare("SELECT * FROM PasswordReset WHERE token = ?");
						$stmt->execute(array($token));
						if(count($stmt->fetchAll()) === 1){
				?>
					<div class="wdn-band">
						<div class="wdn-inner-wrapper wdn-inner-padding-no-top">
							<h3 class="panel-title">Password Reset</h3>

							<section id="reset_password_success" style="display:none;">
								<p>Your password has been reset successfully. You may now <a href="/login">login to your account.</a></p>
							</section>

							<section id="reset_password_info">
								<p>Please provide your current password, and your new password below. Your password must meet the following requirements:</p>
								<ul>
									<li class="text-primary"><b>At least 8 characters</b></li>
									<li><b>At least 1 uppercase</b></li>
									<li><b>At least 1 lowercase</b></li>
									<li><b>At least 1 number or special character</b></li>
								</ul>
							</section>

							<form id="reset_password_form">
								<input id="reset_password_token" type="hidden" value="<?php echo $_REQUEST["token"]; ?>" />
								<section class="form-group">
									<label class="form-required">Email Address</label>
									<input id="reset_password_email" type="text" placeholder="john.doe@email.com" autocomplete="off" required />
								</section>
								<section class="form-group">
									<label class="form-required">New Password</label>
									<input id="reset_password_npassword" type="password" required />
								</section>
								<section class="form-group">
									<label class="form-required">Confirm Password</label>
									<input id="reset_password_vpassword" type="password" required />
								</section>
								<br>
								<button type="submit" class="wdn-button wdn-button-triad">Submit</button>
							</form>
						</div>
					</div>
				<?php
						}else{
				?>
							<div class="wdn-band">
								<div class="wdn-inner-wrapper">
									<a href="/login">Your password reset link has expired. Please request a new one here.</a>
								</div>
							</div>
				<?php
						}
					}else{
				?>
						<div class="wdn-band">
							<div class="wdn-inner-wrapper">
								<a href="/">You appear to have reached this page in error. Please click this link to return home.</a>
							</div>
						</div>
				<?php
					}
				?>
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
	<script src="/dist/register/register.js"></script>
</body>
</html>
