<?php
	$today = date('Y-m-d');
	$taxDay = date('Y-04-15');
	$taxYear = ($today > $taxDay) ? date('Y', strtotime('+1 year')) : date('Y');
?>

<!-- Appointment Signup with no success -->
<div class="wdn-inner-wrapper wdn-inner-padding-no-top" ng-if="successMessage == null">
	<?php if ($today < $taxDay) { ?>
		<h4>VITA appointments have ended for the <?php echo date('Y') ?> tax season. Check back during the <?php echo $taxYear ?> tax season to sign up for an appointment.</h4>
	<?php } else { ?>
		<form class="cmxform" 
			id="vitaSignupForm" 
			name="form" 
			ng-submit="form.$valid && storeAppointments()" 
			autocomplete="off" 
			novalidate>
			<p mt-2 mb-3>Unsure if VITA can help you? <a href="/questionnaire" target="_blank">Click here to find out.</a></p>
			<p><b>NOTE: Please create a separate appointment for every tax return that needs to be done.</b></p>

			<ul>
				<li class="form-textfield">
					<label class="form-label form-required" for="firstName">First Name</label>
					<input type="text" name="firstName" id="firstName" ng-model="data.firstName" required>
					<div ng-show="form.$submitted || form.firstName.$touched">
						<label class="error" ng-show="form.firstName.$error.required">This field is required.</label>
					</div>
				</li>

				<li class="form-textfield">
					<label class="form-label form-required" for="lastName">Last Name</label>
					<input type="text" name="lastName" id="lastName" ng-model="data.lastName" required>
					<div ng-show="form.$submitted || form.lastName.$touched">
						<label class="error" ng-show="form.lastName.$error.required">This field is required.</label>
					</div>
				</li>

				<li class="form-textfield">
					<label class="form-label" for="email">Email</label>
					<input type="email" name="email" id="email" ng-model="data.email">
				</li>

				<li class="form-textfield">
					<label class="form-label form-required" for="phone">Phone Number</label>
					<input type="text" name="phone" id="phone" ng-model="data.phone" required>
					<div ng-show="form.$submitted || form.phone.$touched">
						<label class="error" ng-show="form.phone.$error.required">This field is required.</label>
					</div>
				</li>
			</ul>


			<h3 class="form-subheading">Background Information</h3>

			<ul>
				<!-- TODO: NOTE THAT THIS WAS COMMENTED OUT, WE CURRENTLY DONT LET THEM SAY THE LANGUAGE THEY REQUIRED, WE LET IT DEFAULT TO ENGLISH
				<li class="form-radio" id="language">
					<label for="language" class="form-required">Which language will you require?</label>
					<div>
						<div class="btn-group" data-toggle="buttons">
							NOTE: the values here are the ISO 639-2/T specfication for language codes (https://en.wikipedia.org/wiki/List_of_ISO_639-1_codes)
							<label class="wdn-button btn" 
								name="language" 
								ng-model="data.language" 
								uib-btn-radio="'eng'" 
								required>English
							</label>
							<label class="wdn-button btn" 
								name="language" 
								ng-model="data.language" 
								uib-btn-radio="'spa'" 
								required>Spanish
							</label>
							<label class="wdn-button btn"
								name="language" 
								ng-model="data.language" 
								uib-btn-radio="'ara'" 
								required>Arabic
							</label>
							<label class="wdn-button btn" 
								name="language" 
								ng-model="data.language" 
								uib-btn-radio="'vie'" 
								required>Vietnamese
							</label>
						</div>
					</div>
					<div ng-show="form.$submitted || form.language.$touched">
						<label class="error" ng-show="form.language.$error.required">This field is required.</label>
					</div>
				</li>
				-->

				<li class="form-radio" id="studentUNL">
					<label for="1" class="form-required">Are you a University of Nebraska-Lincoln or Nebraska Wesleyan student?</label>
					<div>
						<div class="btn-group" data-toggle="buttons">
							<label class="wdn-button btn" 
								name="unlStudent" 
								ng-model="questions[1]" 
								uib-btn-radio="'1'" 
								required>Yes
							</label>
							<label class="wdn-button btn" 
								name="unlStudent" 
								ng-model="questions[1]" 
								uib-btn-radio="'2'" 
								required>No
							</label>
						</div>
					</div>
					<div ng-show="form.$submitted || form.unlStudent.$touched">
						<label class="error" ng-show="form.unlStudent.$error.required">This field is required.</label>
					</div>
				</li>

				<li class="form-radio" id="studentInt">
					<label for="2" class="form-required">Are you on a visa?</label>
					<div>
						<div class="btn-group" data-toggle="buttons">
							<label class="wdn-button btn" 
								name="intStudent" 
								ng-model="questions[2]" 
								uib-btn-radio="'1'" 
								ng-change="intStudentChanged()" 
								required>Yes
							</label>
							<label class="wdn-button btn" 
								name="intStudent" 
								ng-model="questions[2]" 
								uib-btn-radio="'2'" 
								ng-change="intStudentChanged()" 
								required>No
							</label>
						</div>
					</div>
					<div ng-show="form.$submitted || form.intStudent.$touched">
						<label class="error" ng-show="form.intStudent.$error.required">This field is required.</label>
					</div>
				</li>

				<li class="form-radio" id="studentIntVisa" ng-show="questions[2] == 1">
					<label for="3" class="form-required">What sort of visa are you on?</label>
					<div>
						<div class="btn-group" data-toggle="buttons">
							<label class="wdn-button btn" 
								name="visa" 
								ng-model="questions[3]" 
								uib-btn-radio="'4'" 
								ng-required="questions[2] == 1" 
								ng-change="visaChanged()" 
								required>F-1
							</label>
							<label class="wdn-button btn" 
								name="visa" 
								ng-model="questions[3]" 
								uib-btn-radio="'5'" 
								ng-required="questions[2] == 1" 
								ng-change="visaChanged()" 
								required>J-1
							</label>
							<label class="wdn-button btn" 
								name="visa" 
								ng-model="questions[3]" 
								uib-btn-radio="'6'" 
								ng-required="questions[2] == 1" 
								ng-change="visaChanged()" 
								required>H1B
							</label>
						</div>
					</div>
					<div ng-show="form.$submitted || form.visa.$touched">
						<label class="error" ng-show="form.visa.$error.required">This field is required.</label>
					</div>
				</li>

				<li class="form-radio" id="studentf1" ng-show="questions[2] == 1 && questions[3] == 4">
					<label for="4" class="form-required">What year did you arrive in the United States?</label>
					<div>
						<div class="btn-group" data-toggle="buttons">
							<label class="wdn-button btn" 
								name="f1date" 
								ng-model="questions[4]" 
								uib-btn-radio="'7'" 
								ng-required="questions[3] == 4" 
								ng-click="standardAppointment()"
								required><?php echo $taxYear - 6 ?> or earlier
							</label>
							<label class="wdn-button btn" 
								name="f1date" 
								ng-model="questions[4]" 
								uib-btn-radio="'8'" 
								ng-required="questions[3] == 4" 
								ng-click="studentScholarAppointment()"
								required><?php echo $taxYear - 5 ?>, 
									<?php echo $taxYear - 4 ?>, 
									<?php echo $taxYear - 3 ?>, 
									<?php echo $taxYear - 2 ?>, or 
									<?php echo $taxYear - 1 ?>
							</label>
						</div>
					</div>
					<div ng-show="form.$submitted || form.f1date.$touched">
						<label class="error" ng-show="form.f1date.$error.required">This field is required.</label>
					</div>
				</li>

				<li class="form-radio" id="studentj1" ng-show="questions[2] == 1 && questions[3] == 5">
					<label for="4" class="form-required">What year did you arrive in the United States?</label>
					<div>
						<div class="btn-group" data-toggle="buttons">
							<label class="wdn-button btn" 
								name="j1date" 
								ng-model="questions[4]" 
								uib-btn-radio="'9'" 
								ng-required="questions[3] == 5" 
								ng-click="standardAppointment()"
								required><?php echo $taxYear - 3 ?> or earlier
							</label>
							<label class="wdn-button btn" 
								name="j1date" 
								ng-model="questions[4]" 
								uib-btn-radio="'10'" 
								ng-required="questions[3] == 5" 
								ng-click="studentScholarAppointment()"
								required><?php echo $taxYear - 2?> or <?php echo $taxYear - 1 ?>
							</label>
						</div>
					</div>
					<div ng-show="form.$submitted || form.j1date.$touched">
						<label class="error" ng-show="form.j1date.$error.required">This field is required.</label>
					</div>
				</li>

				<li class="form-radio" id="studenth1b" ng-show="questions[2] == 1 && questions[3] == 6">
					<label for="5" class="form-required">Have you been on this visa for less than 183 days and in the United States for less than 5 years?</label>
					<div>
						<div class="btn-group form-required" data-toggle="buttons">
							<label class="wdn-button btn" 
								name="h1bdate" 
								ng-model="questions[5]" 
								uib-btn-radio="'1'" 
								ng-required="questions[3] == 6" 
								ng-click="studentScholarAppointment()"
								required>Yes
							</label>
							<label class="wdn-button btn" 
								name="h1bdate" 
								ng-model="questions[5]" 
								uib-btn-radio="'2'" 
								ng-required="questions[3] == 6" 
								ng-click="standardAppointment()"
								required>No
							</label>
						</div>
					</div>
					<div ng-show="form.$submitted || form.h1bdate.$touched">
						<label class="error" ng-show="form.h1bdate.$error.required">This field is required.</label>
					</div>
				</li>
			</ul>


			<h3 class="form-subheading">Appointment Information</h3>

			<div ng-show="sharedProperties.studentScholar == true"><b>International Student Scholar appointments begin March 6th.</b></div>
			<div appointment-picker></div>

			<input type="submit" 
				value="Submit" 
				class="submit wdn-button wdn-button-triad" >
		</form>
	<?php } ?>
</div>

<!-- Successful Signup Screen -->
<div class="wdn-inner-wrapper wdn-inner-padding-no-top" ng-if="successMessage != null">
	<ng-bind-html ng-bind-html="successMessage"></ng-bind-html>

	<div>
		<button type="button" class="mb-3 wdn-button btn wdn-button-triad" onclick="window.print();">Print</button>
		<button type="button" 
			class="mb-3 wdn-button btn wdn-button-triad email-confirmation-button" 
			ng-if="data.email != null && data.email.length > 0"
			ng-disabled="emailButton.disabled"
			ng-click="emailConfirmation()">{{emailButton.text}}</button>
	</div>
</div>
