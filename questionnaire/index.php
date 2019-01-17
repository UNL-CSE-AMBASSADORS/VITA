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
		<?php wdnInclude("/wdn/templates_5.0/includes/global/head-1.html"); ?>
	<!--
		Membership and regular participation in the UNL Web Developer Network is required to use the UNLedu Web Framework. Visit the WDN site at http://wdn.unl.edu/. Register for our mailing list and add your site or server to UNLwebaudit.
		All framework code is the property of the UNL Web Developer Network. The code seen in a source code view is not, and may not be used as, a template. You may not use this code, a reverse-engineered version of this code, or its associated visual presentation in whole or in part to create a derivative work.
		This message may not be removed from any pages based on the UNLedu Web Framework.

		$Id: php.fixed.dwt.php | cf0a670a0fd8db9e20a169941c55c838d7c2ba10 | Wed Dec 12 16:54:41 2018 -0600 | Eric Rasmussen	$
	-->
	<!-- TemplateBeginEditable name="doctitle" -->
	<title>Questionnaire | VITA Lincoln | University of Nebraska&ndash;Lincoln</title>
	<!-- TemplateEndEditable -->
		<?php wdnInclude("/wdn/templates_5.0/includes/global/head-2.html"); ?>
	<!-- TemplateBeginEditable name="head" -->
	<link rel="stylesheet" href="/dist/assets/css/form.css">
	<link rel="stylesheet" href="/dist/questionnaire/questionnaire.css">
	<!-- TemplateEndEditable -->
	<!-- TemplateParam name="class" type="text" value="" -->
</head>
<body class="@@(_document['class'])@@ unl" data-version="5.0">
<?php wdnInclude("/wdn/templates_5.0/includes/global/skip-nav.html"); ?>
<header class="dcf-header" id="dcf-header" role="banner">
		<?php wdnInclude("/wdn/templates_5.0/includes/global/header-global-1.html"); ?>
		<?php wdnInclude("/wdn/templates_5.0/includes/global/nav-global-1.html"); ?>
				<?php wdnInclude("/wdn/templates_5.0/includes/global/visit-global-1.html"); ?>
				<!-- TemplateBeginEditable name="visitlocal" -->
				<?php wdnInclude("/wdn/templates_5.0/includes/local/visit-local.html"); ?>
				<!-- TemplateEndEditable -->
				<?php wdnInclude("/wdn/templates_5.0/includes/global/visit-global-2.html"); ?>
				<?php wdnInclude("/wdn/templates_5.0/includes/global/apply-global-1.html"); ?>
				<!-- TemplateBeginEditable name="applylocal" -->
				<?php wdnInclude("/wdn/templates_5.0/includes/local/apply-local.html"); ?>
				<!-- TemplateEndEditable -->
				<?php wdnInclude("/wdn/templates_5.0/includes/global/apply-global-2.html"); ?>
				<?php wdnInclude("/wdn/templates_5.0/includes/global/give-global-1.html"); ?>
				<!-- TemplateBeginEditable name="givelocal" -->
				<?php wdnInclude("/wdn/templates_5.0/includes/local/give-local.html"); ?>
				<!-- TemplateEndEditable -->
				<?php wdnInclude("/wdn/templates_5.0/includes/global/give-global-2.html"); ?>
		<?php wdnInclude("/wdn/templates_5.0/includes/global/nav-global-2.html"); ?>
		<?php wdnInclude("/wdn/templates_5.0/includes/global/idm.html"); ?>
		<?php wdnInclude("/wdn/templates_5.0/includes/global/search.html"); ?>
		<?php wdnInclude("/wdn/templates_5.0/includes/global/header-global-2.html"); ?>
		<?php wdnInclude("/wdn/templates_5.0/includes/global/logo-lockup-1.html"); ?>
		<?php wdnInclude("/wdn/templates_5.0/includes/global/site-affiliation-1.html"); ?>
	<!-- TemplateBeginEditable name="affiliation" -->
	<!-- TemplateEndEditable -->
		<?php wdnInclude("/wdn/templates_5.0/includes/global/site-affiliation-2.html"); ?>
		<?php wdnInclude("/wdn/templates_5.0/includes/global/site-title-1.html"); ?>
	<!-- TemplateBeginEditable name="titlegraphic" -->
	<a class="unl-site-title-medium" href="/">VITA Lincoln</a>
	<!-- TemplateEndEditable -->
		<?php wdnInclude("/wdn/templates_5.0/includes/global/site-title-2.html"); ?>
		<?php wdnInclude("/wdn/templates_5.0/includes/global/logo-lockup-2.html"); ?>
		<?php wdnInclude("/wdn/templates_5.0/includes/global/nav-toggle-group.html"); ?>
		<?php wdnInclude("/wdn/templates_5.0/includes/global/nav-menu-1.html"); ?>
		<?php wdnInclude("/wdn/templates_5.0/includes/global/nav-toggle-btn.html"); ?>
		<?php wdnInclude("/wdn/templates_5.0/includes/global/nav-menu-child-1.html"); ?>
	<!-- TemplateBeginEditable name="navlinks" -->
		<?php include "$root/sharedcode/navigation.php"; ?>
	<!-- TemplateEndEditable -->
		<?php wdnInclude("/wdn/templates_5.0/includes/global/nav-menu-child-2.html"); ?>
		<?php wdnInclude("/wdn/templates_5.0/includes/global/nav-menu-2.html"); ?>
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
						<li><a href="/">VITA Lincoln</a></li>
						<li>Need Assistance</li>
						<li><span aria-current="page">Questionnaire</span></li>
					</ol>
					<!-- TemplateEndEditable -->
				</nav>
			</div>
			<header class="dcf-page-title" id="dcf-page-title">
				<!-- TemplateBeginEditable name="pagetitle" -->
				<h1>Questionnaire</h1>
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
		
		<!-- Questions -->
		<h2>Can VITA Help You?</h2>
		<p><b>Please note:</b> The scope of work that can be done within a VITA site is defined by the IRS. If your return is considered “out of scope” for a site, our VITA Volunteers will not be able to prepare your return.</p>

		<form class="cmxform" id="vitaQuestionnaireForm" autocomplete="off">
			<div class="form-radio" id="depreciationSchedule">
				<p class="form-required question-text">Will you require a Depreciation Schedule?</p>
				<div class="error-placement">
					<div class="dcf-btn-group" data-toggle="buttons">
						<label class="dcf-btn dcf-btn-secondary" for="1yes">
							<input type="radio" id="1yes" value="1" name="1" required>Yes
						</label>
						<label class="dcf-btn dcf-btn-secondary" for="1no">
							<input type="radio" id="1no" value="2" name="1" required>No
						</label>
					</div>
				</div>
				<p class="cant-help-text" style="display:none;">Sorry, VITA can't prepare taxes requiring a depreciation schedule.</p>
			</div>

			<div class="form-radio" id="scheduleF">
				<p class="form-required question-text">Will you require a Schedule F (Farm)?</p>
				<div class="error-placement">
					<div class="dcf-btn-group" data-toggle="buttons">
						<label class="dcf-btn dcf-btn-secondary" for="2yes">
							<input type="radio" id="2yes" value="1" name="2" required>Yes
						</label>
						<label class="dcf-btn dcf-btn-secondary" for="2no">
							<input type="radio" id="2no" value="2" name="2" required>No
						</label>
					</div>
				</div>
				<p class="cant-help-text" style="display:none;">Sorry, VITA can't prepare taxes requiring a schedule F.</p>
			</div>

			<div class="form-radio" id="homeBased">
				<p class="form-required question-text">Are you self-employed or own a home-based business?</p>
				<div class="error-placement">
					<div class="dcf-btn-group" data-toggle="buttons">
						<label class="dcf-btn dcf-btn-secondary" for="3yes">
							<input type="radio" id="3yes" value="1" name="3" required>Yes
						</label>
						<label class="dcf-btn dcf-btn-secondary" for="3no">
							<input type="radio" id="3no" value="2" name="3" required>No
						</label>
					</div>
				</div>
			</div>

			<div class="form-radio" id="homeBasedNetLoss" style="display: none;">
				<p class="form-required question-text">Does your home-based business or self-employment have a net loss?</p>
				<div class="error-placement">
					<div class="dcf-btn-group" data-toggle="buttons">
						<label class="dcf-btn dcf-btn-secondary" for="4yes">
							<input type="radio" id="4yes" value="1" name="4" required>Yes
						</label>
						<label class="dcf-btn dcf-btn-secondary" for="4no">
							<input type="radio" id="4no" value="2" name="4" required>No
						</label>
					</div>
				</div>
				<p class="cant-help-text" style="display:none;">Sorry, VITA can't prepare taxes when the home-based business has a net loss.</p>
			</div>

			<div class="form-radio" id="homeBased10000" style="display: none;" >
				<p class="form-required question-text">Does your home-based business or self-employment have more than $10,000 in expenses?</p>
				<div class="error-placement">
					<div class="dcf-btn-group" data-toggle="buttons">
						<label class="dcf-btn dcf-btn-secondary" for="5yes">
							<input type="radio" id="5yes" value="1" name="5" required>Yes
						</label>
						<label class="dcf-btn dcf-btn-secondary" for="5no">
							<input type="radio" id="5no" value="2" name="5" required>No
						</label>
					</div>
				</div>
				<p class="cant-help-text" style="display:none;">Sorry, VITA can't prepare taxes when the home-based business has more than $10,000 in expenses.</p>
			</div>

			<div class="form-radio" id="homeBasedSEP" style="display: none;">
				<p class="form-required question-text">Does your home-based business or self-employment have self-employed, SEP, SIMPLE, or qualified retirement plans?</p>
				<div class="error-placement">
					<div class="dcf-btn-group" data-toggle="buttons">
						<label class="dcf-btn dcf-btn-secondary" for="6yes">
							<input type="radio" id="6yes" value="1" name="6" required>Yes
						</label>
						<label class="dcf-btn dcf-btn-secondary" for="6no">
							<input type="radio" id="6no" value="2" name="6" required>No
						</label>
					</div>
				</div>
				<p class="cant-help-text" style="display:none;">Sorry, VITA can't prepare taxes when the home-based business has retirement plans.</p>
			</div>

			<div class="form-radio" id="homeBasedEmployees" style="display: none;">
				<p class="form-required question-text">Does your home-based business or self-employment have employees?</p>
				<div class="error-placement">
					<div class="dcf-btn-group" data-toggle="buttons">
						<label class="dcf-btn dcf-btn-secondary" for="7yes">
							<input type="radio" id="7yes" value="1" name="7" required>Yes
						</label>
						<label class="dcf-btn dcf-btn-secondary" for="7no">
							<input type="radio" id="7no" value="2" name="7" required>No
						</label>
					</div>
				</div>
				<p class="cant-help-text" style="display:none;">Sorry, VITA can't prepare taxes when the home-based business has employees.</p>
			</div>

			<div class="form-radio" id="casualtyLosses">
				<p class="form-required question-text">Will your return have casualty losses?</p>
				<div class="error-placement">
					<div class="dcf-btn-group" data-toggle="buttons">
						<label class="dcf-btn dcf-btn-secondary" for="8yes">
							<input type="radio" id="8yes" value="1" name="8" required>Yes
						</label>
						<label class="dcf-btn dcf-btn-secondary" for="8no">
							<input type="radio" id="8no" value="2" name="8" required>No
						</label>
					</div>
				</div>
				<p class="cant-help-text" style="display:none;">Sorry, VITA can't prepare taxes which will have casualty losses.</p>
			</div>

			<div class="form-radio" id="theftLosses">
				<p class="form-required question-text">Will your return have theft losses?</p>
				<div class="error-placement">
					<div class="dcf-btn-group" data-toggle="buttons">
						<label class="dcf-btn dcf-btn-secondary" for="9yes">
							<input type="radio" id="9yes" value="1" name="9" required>Yes
						</label>
						<label class="dcf-btn dcf-btn-secondary" for="9no">
							<input type="radio" id="9no" value="2" name="9" required>No
						</label>
					</div>
				</div>
				<p class="cant-help-text" style="display:none;">Sorry, VITA can't prepare taxes which will have theft losses.</p>
			</div>

			<div class="form-radio" id="scheduleE">
				<p class="form-required question-text">Will you require a Schedule E (rental income)?</p>
				<div class="error-placement">
					<div class="dcf-btn-group" data-toggle="buttons">
						<label class="dcf-btn dcf-btn-secondary" for="10yes">
							<input type="radio" id="10yes" value="1" name="10" required>Yes
						</label>
						<label class="dcf-btn dcf-btn-secondary" for="10no">
							<input type="radio" id="10no" value="2" name="10" required>No
						</label>
					</div>
				</div>
			</div>

			<div class="form-radio" id="scheduleK1">
				<p class="form-required question-text">Will you require a Schedule K-1 (partnership or trust income)?</p>
				<div class="error-placement">
					<div class="dcf-btn-group" data-toggle="buttons">
						<label class="dcf-btn dcf-btn-secondary" for="11yes">
							<input type="radio" id="11yes" value="1" name="11" required>Yes
						</label>
						<label class="dcf-btn dcf-btn-secondary" for="11no">
							<input type="radio" id="11no" value="2" name="11" required>No
						</label>
					</div>
				</div>
			</div>

			<div class="form-radio" id="dividendsIncome">
				<p class="form-required question-text">Do you have income from dividends, capital gains, or minimal brokerage transactions?</p>
				<div class="error-placement">
					<div class="dcf-btn-group" data-toggle="buttons">
						<label class="dcf-btn dcf-btn-secondary" for="12yes">
							<input type="radio" id="12yes" value="1" name="12" required>Yes
						</label>
						<label class="dcf-btn dcf-btn-secondary" for="12no">
							<input type="radio" id="12no" value="2" name="12" required>No
						</label>
					</div>
				</div>
			</div>

			<div class="form-radio" id="currentBankruptcy">
				<p class="form-required question-text">Will your return involve a current bankruptcy?</p>
				<div class="error-placement">
					<div class="dcf-btn-group" data-toggle="buttons">
						<label class="dcf-btn dcf-btn-secondary" for="13yes">
							<input type="radio" id="13yes" value="1" name="13" required>Yes
						</label>
						<label class="dcf-btn dcf-btn-secondary" for="13no">
							<input type="radio" id="13no" value="2" name="13" required>No
						</label>
					</div>
				</div>
			</div>

			<div class="form-radio" id="multipleStates">
				<p class="form-required question-text">Will your return involve income from more than one state?</p>
				<div class="error-placement">
					<div class="dcf-btn-group" data-toggle="buttons">
						<label class="dcf-btn dcf-btn-secondary" for="14yes">
							<input type="radio" id="14yes" value="1" name="14" required>Yes
						</label>
						<label class="dcf-btn dcf-btn-secondary" for="14no">
							<input type="radio" id="14no" value="2" name="14" required>No
						</label>
					</div>
				</div>
			</div>
			<br>
			<input id="vitaQuestionnaireSubmit" type="submit" value="Schedule An Appointment"/>
		</form>

		<!-- TemplateEndEditable -->
	</div>
</main>
<footer class="dcf-footer" id="dcf-footer" role="contentinfo">
	<!-- TemplateBeginEditable name="optionalfooter" -->
	<!-- TemplateEndEditable -->
		<?php wdnInclude("/wdn/templates_5.0/includes/global/footer-global-1.html"); ?>
	<!-- TemplateBeginEditable name="contactinfo" -->
		<?php include "$root/sharedcode/localFooter.html"; ?>
	<!-- TemplateEndEditable -->
		<?php wdnInclude("/wdn/templates_5.0/includes/global/footer-global-2.html"); ?>
</footer>
<?php wdnInclude("/wdn/templates_5.0/includes/global/noscript.html"); ?>
<?php wdnInclude("/wdn/templates_5.0/includes/global/js-body.html"); ?>
<!-- TemplateBeginEditable name="jsbody" -->
<?php require_once "$root/server/global_includes.php"; ?>
<script src="/dist/questionnaire/questionnaire.js"></script>
<!-- TemplateEndEditable -->
</body>
</html>
