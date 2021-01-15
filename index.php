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
		<?php wdnInclude("/wdn/templates_5.1/includes/global/head-1.html"); ?>
	<!--
		Membership and regular participation in the UNL Web Developer Network is required to use the UNLedu Web Framework. Visit the WDN site at http://wdn.unl.edu/. Register for our mailing list and add your site or server to UNLwebaudit.
		All framework code is the property of the UNL Web Developer Network. The code seen in a source code view is not, and may not be used as, a template. You may not use this code, a reverse-engineered version of this code, or its associated visual presentation in whole or in part to create a derivative work.
		This message may not be removed from any pages based on the UNLedu Web Framework.

		$Id: php.fixed.dwt.php | cf0a670a0fd8db9e20a169941c55c838d7c2ba10 | Wed Dec 12 16:54:41 2018 -0600 | Eric Rasmussen	$
	-->
	<!-- TemplateBeginEditable name="doctitle" -->
	<title>VITA Lincoln | University of Nebraska&ndash;Lincoln</title>
	<!-- TemplateEndEditable -->
		<?php wdnInclude("/wdn/templates_5.1/includes/global/head-2.html"); ?>
	<!-- TemplateBeginEditable name="head" -->
	<link rel="stylesheet" href="/dist/index.css">
	<!-- TemplateEndEditable -->
	<!-- TemplateParam name="class" type="text" value="" -->
</head>
<body class="@@(_document['class'])@@ unl" data-version="5.1">
<?php wdnInclude("/wdn/templates_5.1/includes/global/skip-nav.html"); ?>

<div class="dcf-d-flex dcf-flex-wrap dcf-ai-center dcf-wrapper dcf-pt-3 dcf-pb-3 dcf-bold dcf-txt-decor-none unl-font-sans unl-cream" style="background-color: #007197;">
	<span class="dcf-txt-xs">
		<span class="dcf-d-inline-block dcf-mt-2 dcf-mb-2 dcf-mr-4">
			In response to COVID-19, Lincoln VITA tax appointments are now available virtually. 
			You may schedule a virtual appointment under the 'Need Assistance' tab. The virtual site will be taking appointments through Monday, August 24th.
		</span>
	</span>
</div>

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
	<a class="unl-site-title-medium" href="/">VITA Lincoln</a>
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
	<div class="dcf-hero dcf-hero-default dcf-sr-only">
		<!-- TemplateEndEditable -->
		<div class="dcf-hero-group-1">
			<div class="dcf-breadcrumbs-wrapper">
				<nav class="dcf-breadcrumbs" id="dcf-breadcrumbs" role="navigation" aria-label="breadcrumbs">
					<!-- TemplateBeginEditable name="breadcrumbs" -->
					<ol>
						<li><a href="https://www.unl.edu/">Nebraska</a></li>
						<li><a href="/">VITA Lincoln</a></li>
						<li><span aria-current="page">Home</span></li>
					</ol>
					<!-- TemplateEndEditable -->
				</nav>
			</div>
			<header class="dcf-page-title" id="dcf-page-title">
				<!-- TemplateBeginEditable name="pagetitle" -->
				<h1>VITA Lincoln</h1>
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
		<div class="dcf-bleed unl-bg-light-gray">
			<div class="hero-picture-container">
				<img src="assets/res/VITA-Coalition-Web-Banner.png" alt="Lincoln VITA Coalition">
			</div>
		</div>

		<div class="dcf-bleed">
			<div class="dcf-wrapper dcf-pt-8 dcf-pb-8">
				<p>
					Qualified students and community members can have their tax return prepared AT NO CHARGE 
					by trained student and community volunteers.
				</p>
			</div>
		</div>

		<div class="dcf-bleed unl-bg-lighter-gray">
			<div class="dcf-wrapper">
				<div class="dcf-grid-halves@md dcf-pt-8 dcf-pb-8">
					<div>
						<h2>What is the UNL Tax Credit Campaign?</h2>
					</div>
					<div>
						The UNL Tax Credit Campaign prepares tax returns for low-income, working families and students for free.
						It is sponsored by the University of Nebraska&ndash;Lincoln's Center for Civic Engagement in conjunction
						with the Lincoln EITC Coalition.
					</div>
				</div>
			</div>
		</div>

		<div class="dcf-bleed unl-bg-lightest-gray">
			<div class="dcf-wrapper">
				<div class="dcf-grid-halves@md dcf-pt-8 dcf-pb-8">
					<div>
						<h2>Sign up for an Appointment</h2>
					</div>
					<div>
						<div>
							Lincoln VITA offers tax appointments for United States residents and for international visitors. 
							Tax appointments begin late January and run through April 9th and are available at multiple 
							sites in the Lincoln area. To see the days and times for all the locations, please download 
							the site schedule. 
						</div>
						<div class="dcf-grid-halves@md dcf-mt-3">
							<div class="visual-island">
								<a class="dcf-btn dcf-btn-primary" href="/signup">Make an Appointment</a>
							</div>
							<div class="visual-island">
								<button class="dcf-btn dcf-btn-primary" onClick="javascript:downloadSiteSchedule()">Download Site Schedule</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="dcf-bleed">
			<div class="dcf-wrapper">
				<div class="dcf-grid-halves@md dcf-pt-8 dcf-pb-8">
					<div class="dcf-col-50%">
						<h2>Documents to Upload</h2>
					</div>
					<ul class="dcf-col-50%">
						<li>Social Security Card and photo identification</li>
						<li>Social Security numbers and birth dates for all dependents</li>
						<li>Forms W-2 and all other income forms (ex: 1099-MISC, 1099-R, etc.)</li>
						<li>Banking information for deposit/debit, routing number and account number</li>
						<li>Tax identification numbers for any and all daycare providers</li>
						<li>If filing jointly, both you and your spouse must be present to sign the return</li>
						<li>If a college student, you must bring your 1098T and student account information showing payment of tuition and qualified fees</li>
					</ul>
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
<script src="/dist/index.js"></script>
<!-- TemplateEndEditable -->
</body>
</html>
