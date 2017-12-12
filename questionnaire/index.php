<!DOCTYPE html>
<html class="no-js" lang="">
<head>
	<title>VITA Questionnaire</title>
	<?php 
		$root = realpath($_SERVER['DOCUMENT_ROOT']);
		require_once "$root/server/header.php" 
	?>
	<link rel="stylesheet" href="/assets/css/form.css">
	<link rel="stylesheet" href="/questionnaire/questionnaire.css">
</head>
<body>
	<?php
		$page_subtitle = "Questionnaire";
		require_once "$root/components/nav.php";
	?>

	<!-- TODO HERE -->

	<div class="container">
		<div class="row justify-content-center">
			<div class="col col-12 col-sm-8">
				<h2 class="my-5">Can VITA Help You?</h2>

				<form class="cmxform mb-5" id="vitaSignupForm" method="post" action="" autocomplete="off">
					<div class="form-radio row">
						<label for="depreciation_schedule" class="col form-required">Will you require a Depreciation Schedule?</label>
						<div class="col btn-group" data-toggle="buttons">
							<label class="btn btn-outline-secondary" for="1yes">
								<input type="radio" id="1yes" value="1" name="1" required>Yes
							</label>
							<label class="btn btn-outline-secondary" for="1no">
								<input type="radio" id="1no" value="2" name="1" required>No
							</label>
							<label class="btn btn-outline-secondary" for="1unsure">
								<input type="radio" id="1unsure" value="3" name="1" required>Unsure
							</label>
						</div>
					</div>
					<div class="form-radio row">
						<label for="schedule_f" class="col form-required">Will you require a Schedule F (Farm)?</label>
						<div class="col btn-group" data-toggle="buttons">
							<label class="btn btn-outline-secondary" for="2yes">
								<input type="radio" id="2yes" value="1" name="2" required>Yes
							</label>
							<label class="btn btn-outline-secondary" for="2no">
								<input type="radio" id="2no" value="2" name="2" required>No
							</label>
							<label class="btn btn-outline-secondary" for="2unsure">
								<input type="radio" id="2unsure" value="3" name="2" required>Unsure
							</label>
						</div>
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
							<label class="btn btn-outline-secondary" for="4unsure">
								<input type="radio" id="4unsure" value="3" name="4" required>Unsure
							</label>
						</div>
					</div>
					<div class="form-radio row" id="homeBased10000" style="display: none;">
						<label for="more_than_10000_expenses" class="col form-required">Does your home-based business or self-employment have more than $10,000 in expenses?</label>
						<div class="col btn-group" data-toggle="buttons">
							<label class="btn btn-outline-secondary" for="5yes">
								<input type="radio" id="5yes" value="1" name="5" required>Yes
							</label>
							<label class="btn btn-outline-secondary" for="5no">
								<input type="radio" id="5no" value="2" name="5" required>No
							</label>
							<label class="btn btn-outline-secondary" for="5unsure">
								<input type="radio" id="5unsure" value="3" name="5" required>Unsure
							</label>
						</div>
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
							<label class="btn btn-outline-secondary" for="6unsure">
								<input type="radio" id="6unsure" value="3" name="6" required>Unsure
							</label>
						</div>
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
							<label class="btn btn-outline-secondary" for="7unsure">
								<input type="radio" id="7unsure" value="3" name="7" required>Unsure
							</label>
						</div>
					</div>
					<div class="form-radio row">
						<label for="casualty_losses" class="col form-required">Will your return have casualty losses?</label>
						<div class="col btn-group" data-toggle="buttons">
							<label class="btn btn-outline-secondary" for="8yes">
								<input type="radio" id="8yes" value="1" name="8" required>Yes
							</label>
							<label class="btn btn-outline-secondary" for="8no">
								<input type="radio" id="8no" value="2" name="8" required>No
							</label>
							<label class="btn btn-outline-secondary" for="8unsure">
								<input type="radio" id="8unsure" value="3" name="8" required>Unsure
							</label>
						</div>
					</div>
					<div class="form-radio row">
						<label for="theft_losses" class="col form-required">Will your return have theft losses?</label>
						<div class="col btn-group" data-toggle="buttons">
							<label class="btn btn-outline-secondary" for="9yes">
								<input type="radio" id="9yes" value="1" name="9" required>Yes
							</label>
							<label class="btn btn-outline-secondary" for="9no">
								<input type="radio" id="9no" value="2" name="9" required>No
							</label>
							<label class="btn btn-outline-secondary" for="9unsure">
								<input type="radio" id="9unsure" value="3" name="9" required>Unsure
							</label>
						</div>
					</div>
					<div class="form-radio row">
						<label for="schedule_e" class="col form-required">Will you require a Schedule E (rental income)?</label>
						<div class="col btn-group" data-toggle="buttons">
							<label class="btn btn-outline-secondary" for="10yes">
								<input type="radio" id="10yes" value="1" name="10" required>Yes
							</label>
							<label class="btn btn-outline-secondary" for="10no">
								<input type="radio" id="10no" value="2" name="10" required>No
							</label>
							<label class="btn btn-outline-secondary" for="10unsure">
								<input type="radio" id="10unsure" value="3" name="10" required>Unsure
							</label>
						</div>
					</div>
					<div class="form-radio row">
						<label for="schedule_k-1" class="col form-required">Will you require a Schedule K-1 (partnership or trust income)?</label>
						<div class="col btn-group" data-toggle="buttons">
							<label class="btn btn-outline-secondary" for="11yes">
								<input type="radio" id="11yes" value="1" name="11" required>Yes
							</label>
							<label class="btn btn-outline-secondary" for="11no">
								<input type="radio" id="11no" value="2" name="11" required>No
							</label>
							<label class="btn btn-outline-secondary" for="11unsure">
								<input type="radio" id="11unsure" value="3" name="11" required>Unsure
							</label>
						</div>
					</div>
					<div class="form-radio row">
						<label for="dividends_income" class="col form-required">Do you have income from dividends, capital gains, or minimal brokerage transactions?</label>
						<div class="col btn-group" data-toggle="buttons">
							<label class="btn btn-outline-secondary" for="12yes">
								<input type="radio" id="12yes" value="1" name="12" required>Yes
							</label>
							<label class="btn btn-outline-secondary" for="12no">
								<input type="radio" id="12no" value="2" name="12" required>No
							</label>
							<label class="btn btn-outline-secondary" for="12unsure">
								<input type="radio" id="12unsure" value="3" name="12" required>Unsure
							</label>
						</div>
					</div>
					<div class="form-radio row">
						<label for="current_bankruptcy" class="col form-required">Will your return involve a current bankruptcy?</label>
						<div class="col btn-group" data-toggle="buttons">
							<label class="btn btn-outline-secondary" for="13yes">
								<input type="radio" id="13yes" value="1" name="13" required>Yes
							</label>
							<label class="btn btn-outline-secondary" for="13no">
								<input type="radio" id="13no" value="2" name="13" required>No
							</label>
							<label class="btn btn-outline-secondary" for="13unsure">
								<input type="radio" id="13unsure" value="3" name="13" required>Unsure
							</label>
						</div>
					</div>
					<div class="form-radio row">
						<label for="multiple_states" class="col form-required">Will your return involve income from more than one state?</label>
						<div class="col btn-group" data-toggle="buttons">
							<label class="btn btn-outline-secondary" for="14yes">
								<input type="radio" id="14yes" value="1" name="14" required>Yes
							</label>
							<label class="btn btn-outline-secondary" for="14no">
								<input type="radio" id="14no" value="2" name="14" required>No
							</label>
							<label class="btn btn-outline-secondary" for="14unsure">
								<input type="radio" id="14unsure" value="3" name="14" required>Unsure
							</label>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

	<?php require_once "$root/server/footer.php" ?>
	<!-- TOOD SWITCH THESE TO THE DIST -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/jquery.validate.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/additional-methods.min.js"></script>
	<script src="/questionnaire/questionnaire.js"></script>
	<script src="/assets/js/form.js"></script>
</body>
</html>