<!DOCTYPE html>
<html class="no-js" lang="">
<head>
	<title>VITA Questionnaire</title>
	<?php 
		$root = realpath($_SERVER['DOCUMENT_ROOT']);
		require_once "$root/server/header.php" 
	?>
	<link rel="stylesheet" href="/dist/assets/css/form.css">
	<link rel="stylesheet" href="/dist/questionnaire/questionnaire.css">
</head>
<body>
	<?php
		$page_subtitle = "Questionnaire";
		require_once "$root/components/nav.php";
	?>

	<!-- Questions -->
	<div class="container">
		<div class="row justify-content-center">
			<div class="col col-12 col-sm-8">
				<h2 class="mt-4">Can VITA Help You?</h2>
				<p class="my-4"><b>Please note:</b> The scope of work that can be done within a VITA site is defined by the IRS. If your return is considered “out of scope” for a site, our VITA Volunteers will not be able to prepare your return.</p>

				<form class="cmxform mb-5" id="vitaQuestionnaireForm" action="" autocomplete="off">
					<div class="form-radio row" id="depreciationSchedule">
						<label for="depreciation_schedule" class="col form-required">Will you require a Depreciation Schedule?</label>
						<div class="col btn-group" data-toggle="buttons">
							<label class="btn btn-outline-secondary" for="1yes">
								<input type="radio" id="1yes" value="1" name="1" required>Yes
							</label>
							<label class="btn btn-outline-secondary" for="1no">
								<input type="radio" id="1no" value="2" name="1" required>No
							</label>
						</div>
						<p class="col mt-2 cant-help-text" style="display:none;" name="cantHelp">Sorry, VITA can't prepare taxes requiring a depreciation schedule.</p>
					</div>
					<div class="form-radio row" id="scheduleF">
						<label for="schedule_f" class="col form-required">Will you require a Schedule F (Farm)?</label>
						<div class="col btn-group" data-toggle="buttons">
							<label class="btn btn-outline-secondary" for="2yes">
								<input type="radio" id="2yes" value="1" name="2" required>Yes
							</label>
							<label class="btn btn-outline-secondary" for="2no">
								<input type="radio" id="2no" value="2" name="2" required>No
							</label>
						</div>
						<p class="col mt-2 cant-help-text" style="display:none;" name="cantHelp">Sorry, VITA can't prepare taxes requiring a schedule F.</p>
					</div>
					<div class="form-radio row" id="homeBased">
						<label for="self_employed" class="col form-required">Are you self-employed or own a home-based business?</label>
						<div class="col btn-group" data-toggle="buttons">
							<label class="btn btn-outline-secondary" for="3yes">
								<input type="radio" id="3yes" value="1" name="3" required>Yes
							</label>
							<label class="btn btn-outline-secondary" for="3no">
								<input type="radio" id="3no" value="2" name="3" required>No
							</label>
						</div>
					</div>
					<div class="form-radio row" id="homeBasedNetLoss" style="display: none;">
						<label for="net_loss" class="col form-required">Does your home-based business or self-employment have a net loss?</label>
						<div class="col btn-group" data-toggle="buttons">
							<label class="btn btn-outline-secondary" for="4yes">
								<input type="radio" id="4yes" value="1" name="4" required>Yes
							</label>
							<label class="btn btn-outline-secondary" for="4no">
								<input type="radio" id="4no" value="2" name="4" required>No
							</label>
						</div>
						<p class="col mt-2 cant-help-text" style="display:none;" name="cantHelp">Sorry, VITA can't prepare taxes when the home-based business has a net loss.</p>
					</div>
					<div class="form-radio row" id="homeBased10000" style="display: none;" >
						<label for="more_than_10000_expenses" class="col form-required">Does your home-based business or self-employment have more than $10,000 in expenses?</label>
						<div class="col btn-group" data-toggle="buttons">
							<label class="btn btn-outline-secondary" for="5yes">
								<input type="radio" id="5yes" value="1" name="5" required>Yes
							</label>
							<label class="btn btn-outline-secondary" for="5no">
								<input type="radio" id="5no" value="2" name="5" required>No
							</label>
						</div>
						<p class="col mt-2 cant-help-text" style="display:none;" name="cantHelp">Sorry, VITA can't prepare taxes when the home-based business has more than $10,000 in expenses.</p>
					</div>
					<div class="form-radio row" id="homeBasedSEP" style="display: none;">
						<label for="retirement_plans" class="col form-required">Does your home-based business or self-employment have self-employed, SEP, SIMPLE, or qualified retirement plans?</label>
						<div class="col btn-group" data-toggle="buttons">
							<label class="btn btn-outline-secondary" for="6yes">
								<input type="radio" id="6yes" value="1" name="6" required>Yes
							</label>
							<label class="btn btn-outline-secondary" for="6no">
								<input type="radio" id="6no" value="2" name="6" required>No
							</label>
						</div>
						<p class="col mt-2 cant-help-text" style="display:none;" name="cantHelp">Sorry, VITA can't prepare taxes when the home-based business has retirement plans.</p>
					</div>
					<div class="form-radio row" id="homeBasedEmployees" style="display: none;">
						<label for="any_employees" class="col form-required">Does your home-based business or self-employment have employees?</label>
						<div class="col btn-group" data-toggle="buttons">
							<label class="btn btn-outline-secondary" for="7yes">
								<input type="radio" id="7yes" value="1" name="7" required>Yes
							</label>
							<label class="btn btn-outline-secondary" for="7no">
								<input type="radio" id="7no" value="2" name="7" required>No
							</label>
						</div>
						<p class="col mt-2 cant-help-text" style="display:none;" name="cantHelp">Sorry, VITA can't prepare taxes when the home-based business has employees.</p>
					</div>
					<div class="form-radio row" id="casualtyLosses">
						<label for="casualty_losses" class="col form-required">Will your return have casualty losses?</label>
						<div class="col btn-group" data-toggle="buttons">
							<label class="btn btn-outline-secondary" for="8yes">
								<input type="radio" id="8yes" value="1" name="8" required>Yes
							</label>
							<label class="btn btn-outline-secondary" for="8no">
								<input type="radio" id="8no" value="2" name="8" required>No
							</label>
						</div>
						<p class="col mt-2 cant-help-text" style="display:none;" name="cantHelp">Sorry, VITA can't prepare taxes which will have casualty losses.</p>
					</div>
					<div class="form-radio row" id="theftLosses">
						<label for="theft_losses" class="col form-required">Will your return have theft losses?</label>
						<div class="col btn-group" data-toggle="buttons">
							<label class="btn btn-outline-secondary" for="9yes">
								<input type="radio" id="9yes" value="1" name="9" required>Yes
							</label>
							<label class="btn btn-outline-secondary" for="9no">
								<input type="radio" id="9no" value="2" name="9" required>No
							</label>
						</div>
						<p class="col mt-2 cant-help-text" style="display:none;" name="cantHelp">Sorry, VITA can't prepare taxes which will have theft losses.</p>
					</div>
					<div class="form-radio row" id="scheduleE">
						<label for="schedule_e" class="col form-required">Will you require a Schedule E (rental income)?</label>
						<div class="col btn-group" data-toggle="buttons">
							<label class="btn btn-outline-secondary" for="10yes">
								<input type="radio" id="10yes" value="1" name="10" required>Yes
							</label>
							<label class="btn btn-outline-secondary" for="10no">
								<input type="radio" id="10no" value="2" name="10" required>No
							</label>
						</div>
					</div>
					<div class="form-radio row" id="scheduleK1">
						<label for="schedule_k-1" class="col form-required">Will you require a Schedule K-1 (partnership or trust income)?</label>
						<div class="col btn-group" data-toggle="buttons">
							<label class="btn btn-outline-secondary" for="11yes">
								<input type="radio" id="11yes" value="1" name="11" required>Yes
							</label>
							<label class="btn btn-outline-secondary" for="11no">
								<input type="radio" id="11no" value="2" name="11" required>No
							</label>
						</div>
					</div>
					<div class="form-radio row" id="dividendsIncome">
						<label for="dividends_income" class="col form-required">Do you have income from dividends, capital gains, or minimal brokerage transactions?</label>
						<div class="col btn-group" data-toggle="buttons">
							<label class="btn btn-outline-secondary" for="12yes">
								<input type="radio" id="12yes" value="1" name="12" required>Yes
							</label>
							<label class="btn btn-outline-secondary" for="12no">
								<input type="radio" id="12no" value="2" name="12" required>No
							</label>
						</div>
					</div>
					<div class="form-radio row" id="currentBankruptcy">
						<label for="current_bankruptcy" class="col form-required">Will your return involve a current bankruptcy?</label>
						<div class="col btn-group" data-toggle="buttons">
							<label class="btn btn-outline-secondary" for="13yes">
								<input type="radio" id="13yes" value="1" name="13" required>Yes
							</label>
							<label class="btn btn-outline-secondary" for="13no">
								<input type="radio" id="13no" value="2" name="13" required>No
							</label>
						</div>
					</div>
					<div class="form-radio row" id="multipleStates">
						<label for="multiple_states" class="col form-required">Will your return involve income from more than one state?</label>
						<div class="col btn-group" data-toggle="buttons">
							<label class="btn btn-outline-secondary" for="14yes">
								<input type="radio" id="14yes" value="1" name="14" required>Yes
							</label>
							<label class="btn btn-outline-secondary" for="14no">
								<input type="radio" id="14no" value="2" name="14" required>No
							</label>
						</div>
					</div>

					<input class="submit btn btn-primary mb-5 vita-background-primary" id="vitaQuestionnaireSubmit" type="submit" value="Schedule An Appointment"/>
				</form>
			</div>
		</div>
	</div>

	<?php require_once "$root/server/footer.php" ?>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/jquery.validate.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/additional-methods.min.js"></script>
	<script src="/dist/questionnaire/questionnaire.js"></script>
	<script src="/dist/assets/js/form.js"></script>
</body>
</html>