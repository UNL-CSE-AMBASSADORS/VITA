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
<title>VITA Lincoln | University of Nebraska&ndash;Lincoln</title>
<!-- TemplateEndEditable -->
<!-- TemplateBeginEditable name="head" -->
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
					<li><a href="#" title="VITA Lincoln">VITA Lincoln</a></li>
					<li>Home</li>
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
					<!-- TemplateEndEditable -->
				</div>
				<!-- TemplateBeginEditable name="maincontentarea" -->
				<div class="wdn-band wdn-hero">
					<div class="wdn-hero-text-container">
						<div class="wdn-hero-text">
							<h2 class="wdn-hero-heading">VITA Lincoln</h2>
						</div>
					</div>
					<div class="wdn-hero-picture">
						<img src="assets/res/VITA-Coalition-Web-Banner.png" alt="Lincoln VITA Coalition">
					</div>
				</div>

				<div class="wdn-band wdn-text-band">
					<div class="wdn-inner-wrapper">
						<p>
							Qualified students and community members can have their tax return prepared AT NO CHARGE 
							by trained student and community volunteers.
						</p>
					</div>
				</div>

				<div class="wdn-band wdn-light-triad-band">
					<div class="wdn-inner-wrapper">
						<div class="wdn-grid-set">
							<div class="bp768-wdn-col-one-half">
								<h2>What is the UNL Tax Credit Campaign?</h2>
							</div>
							<div class="bp768-wdn-col-one-half">
								The UNL Tax Credit Campaign prepares tax returns for low-income, working families and students for free.
								It is sponsored by the University of Nebraska-Lincoln's Center for Civic Engagement in conjunction
								with the Lincoln Volunteer Income Tax Assistance (VITA) Coalition.
							</div>
						</div>
					</div>
				</div>

				<div class="wdn-band wdn-light-neutral-band">
					<div class="wdn-inner-wrapper">
						<div class="wdn-grid-set">
							<div class="bp768-wdn-col-one-half">
								<h2>Sign up for an Appointment</h2>
							</div>
							<div class="bp768-wdn-col-one-half">
								<p class="col col-12 col-sm-8">
									Residential appointments begin January 21st and run through April 8th. Appointments for international
									returns begin March 6th and run through April 10th. You can sign up for appointments at the Nebraska East
									Union, Anderson Library, and Jackie Gaughan Multicultural Center sites through this site. Download the schedule
									to see all times for all sites and how to contact the sites. 
								</p>
								<div class="bp768-wdn-col-one-half visual-island">
									<a class="wdn-button wdn-button-brand" href="/signup">Make an Appointment</a>
								</div>
								<div class="bp768-wdn-col-one-half visual-island">
									<a class="wdn-button wdn-button-brand" href="/server/download/downloadFile.php?file=2018_Schedule.pdf">Download Site Schedule</a>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="wdn-band">
					<div class="wdn-inner-wrapper">
						<div class="wdn-grid-set">
							<div class="bp768-wdn-col-one-half">
								<h2>Documents to Bring</h2>
							</div>
							<ul class="bp768-wdn-col-one-half">
								<li>Social Security Card and photo identification</li>
								<li>Social Security numbers and birth dates for all dependents</li>
								<li>Forms W-2 and all other income forms and tax documents for 2016</li>
								<li>Bank routing and account number for deposit/debit of any tax balance</li>
								<li>Tax identification numbers for any and all daycare providers</li>
								<li>Spouse, if filing jointly (both must be present to sign the return)</li>
								<li>If a post secondary student, you must bring your 1098T and student account information showing payment of tuition and qualified fees</li>
							</ul>
						</div>
					</div>
				</div>

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
	<!-- <script src="/dist/*.js"></script> -->
</body>
</html>
