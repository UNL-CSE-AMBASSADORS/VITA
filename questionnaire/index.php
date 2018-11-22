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
	Membership and regular participation in the UNL Web Developer Network is required to use the UNLedu Web Framework. Visit the WDN site at https://wdn.unl.edu/. Register for our mailing list and add your site or server to UNLwebaudit.
	All framework code is the property of the UNL Web Developer Network. The code seen in a source code view is not, and may not be used as, a template. You may not use this code, a reverse-engineered version of this code, or its associated visual presentation in whole or in part to create a derivative work.
	This message may not be removed from any pages based on the UNLedu Web Framework.

	$Id: php.fixed.dwt.php | 6edb0e1ee94038935f3821c6ce15dfd5c217b2e2 | Tue Dec 1 17:08:56 2015 -0600 | Kevin Abel  $
-->
<?php wdnInclude("/wdn/templates_4.1/includes/scriptsandstyles.html"); ?>
<!-- TemplateBeginEditable name="doctitle" -->
<title>Questionnaire | VITA Lincoln | University of Nebraska&ndash;Lincoln</title>
<!-- TemplateEndEditable -->
<!-- TemplateBeginEditable name="head" -->
<link rel="stylesheet" href="/dist/assets/css/bootstrap.btn-group.min.css">
<link rel="stylesheet" href="/dist/assets/css/form.css">
<link rel="stylesheet" href="/dist/questionnaire/questionnaire.css">
<!-- TemplateEndEditable -->
<!-- TemplateParam name="class" type="text" value="" -->
</head>
<body class="hide-wdn_identity_management" data-version="4.1">
	<?php wdnInclude("/wdn/templates_4.1/includes/skipnav.html"); ?>
	<div id="wdn_wrapper">
		<input type="checkbox" id="wdn_menu_toggle" value="Show navigation menu" class="wdn-content-slide wdn-input-driver" />
		<?php wdnInclude("/wdn/templates_4.1/includes/noscript-padding.html"); ?>
		<header id="header" role="banner" class="wdn-content-slide wdn-band">
			<div id="wdn_header_top">
				<span id="wdn_institution_title"><a href="https://www.unl.edu/">University of Nebraska&ndash;Lincoln</a></span>
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
					<li><a href="https://www.unl.edu/" title="University of Nebraska&ndash;Lincoln" class="wdn-icon-home">UNL</a></li>
					<li><a href="/" title="VITA Lincoln">VITA Lincoln</a></li>
					<li>Need Assistance</li>
					<li>Questionnaire</li>

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
					<h1>Questionnaire</h1>
					<!-- TemplateEndEditable -->
				</div>
				<!-- TemplateBeginEditable name="maincontentarea" -->

				<!-- Questions -->
				<div class="wdn-band">
					<div class="wdn-inner-wrapper wdn-inner-padding-no-top">
						<h2>Can VITA Help You?</h2>
						<p><b>Please note:</b> The scope of work that can be done within a VITA site is defined by the IRS. If your return is considered “out of scope” for a site, our VITA Volunteers will not be able to prepare your return.</p>

						<form class="cmxform" id="vitaQuestionnaireForm" autocomplete="off">
							<div class="form-radio" id="depreciationSchedule">
								<p class="form-required question-text">Will you require a Depreciation Schedule?</p>
								<div class="error-placement">
									<div class="btn-group" data-toggle="buttons">
										<label class="wdn-button btn" for="1yes">
											<input type="radio" id="1yes" value="1" name="1" required>Yes
										</label>
										<label class="wdn-button btn" for="1no">
											<input type="radio" id="1no" value="2" name="1" required>No
										</label>
									</div>
								</div>
								<p class="cant-help-text" style="display:none;">Sorry, VITA can't prepare taxes requiring a depreciation schedule.</p>
							</div>

							<div class="form-radio" id="scheduleF">
								<p class="form-required question-text">Will you require a Schedule F (Farm)?</p>
								<div class="error-placement">
									<div class="btn-group" data-toggle="buttons">
										<label class="wdn-button btn" for="2yes">
											<input type="radio" id="2yes" value="1" name="2" required>Yes
										</label>
										<label class="wdn-button btn" for="2no">
											<input type="radio" id="2no" value="2" name="2" required>No
										</label>
									</div>
								</div>
								<p class="cant-help-text" style="display:none;">Sorry, VITA can't prepare taxes requiring a schedule F.</p>
							</div>

							<div class="form-radio" id="homeBased">
								<p class="form-required question-text">Are you self-employed or own a home-based business?</p>
								<div class="error-placement">
									<div class="btn-group" data-toggle="buttons">
										<label class="wdn-button btn" for="3yes">
											<input type="radio" id="3yes" value="1" name="3" required>Yes
										</label>
										<label class="wdn-button btn" for="3no">
											<input type="radio" id="3no" value="2" name="3" required>No
										</label>
									</div>
								</div>
							</div>

							<div class="form-radio" id="homeBasedNetLoss" style="display: none;">
								<p class="form-required question-text">Does your home-based business or self-employment have a net loss?</p>
								<div class="error-placement">
									<div class="btn-group" data-toggle="buttons">
										<label class="wdn-button btn" for="4yes">
											<input type="radio" id="4yes" value="1" name="4" required>Yes
										</label>
										<label class="wdn-button btn" for="4no">
											<input type="radio" id="4no" value="2" name="4" required>No
										</label>
									</div>
								</div>
								<p class="cant-help-text" style="display:none;">Sorry, VITA can't prepare taxes when the home-based business has a net loss.</p>
							</div>

							<div class="form-radio" id="homeBased10000" style="display: none;" >
								<p class="form-required question-text">Does your home-based business or self-employment have more than $10,000 in expenses?</p>
								<div class="error-placement">
									<div class="btn-group" data-toggle="buttons">
										<label class="wdn-button btn" for="5yes">
											<input type="radio" id="5yes" value="1" name="5" required>Yes
										</label>
										<label class="wdn-button btn" for="5no">
											<input type="radio" id="5no" value="2" name="5" required>No
										</label>
									</div>
								</div>
								<p class="cant-help-text" style="display:none;">Sorry, VITA can't prepare taxes when the home-based business has more than $10,000 in expenses.</p>
							</div>

							<div class="form-radio" id="homeBasedSEP" style="display: none;">
								<p class="form-required question-text">Does your home-based business or self-employment have self-employed, SEP, SIMPLE, or qualified retirement plans?</p>
								<div class="error-placement">
									<div class="btn-group" data-toggle="buttons">
										<label class="wdn-button btn" for="6yes">
											<input type="radio" id="6yes" value="1" name="6" required>Yes
										</label>
										<label class="wdn-button btn" for="6no">
											<input type="radio" id="6no" value="2" name="6" required>No
										</label>
									</div>
								</div>
								<p class="cant-help-text" style="display:none;">Sorry, VITA can't prepare taxes when the home-based business has retirement plans.</p>
							</div>

							<div class="form-radio" id="homeBasedEmployees" style="display: none;">
								<p class="form-required question-text">Does your home-based business or self-employment have employees?</p>
								<div class="error-placement">
									<div class="btn-group" data-toggle="buttons">
										<label class="wdn-button btn" for="7yes">
											<input type="radio" id="7yes" value="1" name="7" required>Yes
										</label>
										<label class="wdn-button btn" for="7no">
											<input type="radio" id="7no" value="2" name="7" required>No
										</label>
									</div>
								</div>
								<p class="cant-help-text" style="display:none;">Sorry, VITA can't prepare taxes when the home-based business has employees.</p>
							</div>

							<div class="form-radio" id="casualtyLosses">
								<p class="form-required question-text">Will your return have casualty losses?</p>
								<div class="error-placement">
									<div class="btn-group" data-toggle="buttons">
										<label class="wdn-button btn" for="8yes">
											<input type="radio" id="8yes" value="1" name="8" required>Yes
										</label>
										<label class="wdn-button btn" for="8no">
											<input type="radio" id="8no" value="2" name="8" required>No
										</label>
									</div>
								</div>
								<p class="cant-help-text" style="display:none;">Sorry, VITA can't prepare taxes which will have casualty losses.</p>
							</div>

							<div class="form-radio" id="theftLosses">
								<p class="form-required question-text">Will your return have theft losses?</p>
								<div class="error-placement">
									<div class="btn-group" data-toggle="buttons">
										<label class="wdn-button btn" for="9yes">
											<input type="radio" id="9yes" value="1" name="9" required>Yes
										</label>
										<label class="wdn-button btn" for="9no">
											<input type="radio" id="9no" value="2" name="9" required>No
										</label>
									</div>
								</div>
								<p class="cant-help-text" style="display:none;">Sorry, VITA can't prepare taxes which will have theft losses.</p>
							</div>

							<div class="form-radio" id="scheduleE">
								<p class="form-required question-text">Will you require a Schedule E (rental income)?</p>
								<div class="error-placement">
									<div class="btn-group" data-toggle="buttons">
										<label class="wdn-button btn" for="10yes">
											<input type="radio" id="10yes" value="1" name="10" required>Yes
										</label>
										<label class="wdn-button btn" for="10no">
											<input type="radio" id="10no" value="2" name="10" required>No
										</label>
									</div>
								</div>
							</div>

							<div class="form-radio" id="scheduleK1">
								<p class="form-required question-text">Will you require a Schedule K-1 (partnership or trust income)?</p>
								<div class="error-placement">
									<div class="btn-group" data-toggle="buttons">
										<label class="wdn-button btn" for="11yes">
											<input type="radio" id="11yes" value="1" name="11" required>Yes
										</label>
										<label class="wdn-button btn" for="11no">
											<input type="radio" id="11no" value="2" name="11" required>No
										</label>
									</div>
								</div>
							</div>

							<div class="form-radio" id="dividendsIncome">
								<p class="form-required question-text">Do you have income from dividends, capital gains, or minimal brokerage transactions?</p>
								<div class="error-placement">
									<div class="btn-group" data-toggle="buttons">
										<label class="wdn-button btn" for="12yes">
											<input type="radio" id="12yes" value="1" name="12" required>Yes
										</label>
										<label class="wdn-button btn" for="12no">
											<input type="radio" id="12no" value="2" name="12" required>No
										</label>
									</div>
								</div>
							</div>

							<div class="form-radio" id="currentBankruptcy">
								<p class="form-required question-text">Will your return involve a current bankruptcy?</p>
								<div class="error-placement">
									<div class="btn-group" data-toggle="buttons">
										<label class="wdn-button btn" for="13yes">
											<input type="radio" id="13yes" value="1" name="13" required>Yes
										</label>
										<label class="wdn-button btn" for="13no">
											<input type="radio" id="13no" value="2" name="13" required>No
										</label>
									</div>
								</div>
							</div>

							<div class="form-radio" id="multipleStates">
								<p class="form-required question-text">Will your return involve income from more than one state?</p>
								<div class="error-placement">
									<div class="btn-group" data-toggle="buttons">
										<label class="wdn-button btn" for="14yes">
											<input type="radio" id="14yes" value="1" name="14" required>Yes
										</label>
										<label class="wdn-button btn" for="14no">
											<input type="radio" id="14no" value="2" name="14" required>No
										</label>
									</div>
								</div>
							</div>
							<br>
							<input id="vitaQuestionnaireSubmit" type="submit" value="Schedule An Appointment"/>
						</form>
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
	<script src="/dist/questionnaire/questionnaire.js"></script>
</body>
</html>
