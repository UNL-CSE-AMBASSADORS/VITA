<?php
	date_default_timezone_set('America/Chicago');
	$today = date('Y-m-d');
	$dateAppointmentSignUpsStart = date('Y-01-13');
	$taxDay = date('Y-04-15');
	$overrideForCovid19 = true;
	
	$taxYear = ($today > $taxDay) ? date('Y', strtotime('+1 year')) : date('Y');
?>

<!-- Appointment Signup with no success -->
<div class="dcf-pb-7" ng-if="successMessage == null">
	<?php if ($overrideForCovid19) { ?>
		<h4>
			In response to COVID-19, all VITA tax sites are cancelled through the remainder of the scheduled appointment dates. 
			However, the federal income tax filing deadline has been extended to July 15, 2020, and 
			we are currently testing "virtual" appointments. 
			Please check back here frequently to see if we are accepting more clients for "virtual" appointments.
		</h4>
	<?php } else if ($today > $taxDay) { ?>
		<h4>VITA appointments have ended for the <?php echo date('Y') ?> tax season. Check back during the <?php echo $taxYear ?> tax season to sign up for an appointment.</h4>
	<?php } else if ($today < $dateAppointmentSignUpsStart) { ?>
		<h4>Tax appointments cannot yet be scheduled. Please check back on <?php echo date('F jS, Y', strtotime($dateAppointmentSignUpsStart)) ?>.</h4>
	<?php } else { ?>
		<form class="cmxform dcf-form" 
			id="vitaSignupForm" 
			name="form" 
			ng-submit="form.$valid && storeAppointments()" 
			autocomplete="off" 
			novalidate>
			<p class="dcf-mt-2 dcf-mb-3">Unsure if VITA can help you? <a href="/questionnaire" target="_blank">Click here to find out.</a></p>
			<p><b>NOTE: Please create a separate appointment for every tax return that needs to be done.</b></p>

			<ul class="dcf-pl-0">
				<li class="dcf-form-group form-textfield">
					<label class="dcf-label form-label form-required" for="firstName">First Name</label>
					<input type="text" class="dcf-input-text form-control" name="firstName" id="firstName" ng-model="data.firstName" required>
					<div ng-show="form.$submitted || form.firstName.$touched">
						<label class="error" ng-show="form.firstName.$error.required">This field is required.</label>
					</div>
				</li>

				<li class="form-textfield">
					<label class="dcf-label form-label form-required" for="lastName">Last Name</label>
					<input type="text" class="dcf-input-text form-control" name="lastName" id="lastName" ng-model="data.lastName" required>
					<div ng-show="form.$submitted || form.lastName.$touched">
						<label class="error" ng-show="form.lastName.$error.required">This field is required.</label>
					</div>
				</li>

				<li class="form-textfield">
					<label class="dcf-label form-label" for="email">Email</label>
					<input type="email" class="dcf-input-text form-control" name="email" id="email" ng-model="data.email">
					<p class="dcf-txt-xs">A confirmation email will be sent to this email address.</p>
				</li>

				<li class="form-textfield">
					<label class="dcf-label form-label form-required" for="phone">Phone Number</label>
					<input type="text" class="dcf-input-text form-control" name="phone" id="phone" ng-model="data.phone" required>
					<div ng-show="form.$submitted || form.phone.$touched">
						<label class="error" ng-show="form.phone.$error.required">This field is required.</label>
					</div>
				</li>
			</ul>


			<h3 class="form-subheading">Background Information</h3>

			<ul class="dcf-pl-0">
				<!-- TODO: NOTE THAT THIS WAS COMMENTED OUT, WE CURRENTLY DONT LET THEM SAY THE LANGUAGE THEY REQUIRED, WE LET IT DEFAULT TO ENGLISH
				<li class="form-radio" id="language">
					<label for="language" class="form-required">Which language will you require?</label>
					<div>
						<div class="dcf-btn-group" data-toggle="buttons">
							NOTE: the values here are the ISO 639-2/T specfication for language codes (https://en.wikipedia.org/wiki/List_of_ISO_639-1_codes)
							<label class="dcf-btn dcf-btn-secondary" 
								name="language" 
								ng-model="data.language" 
								uib-btn-radio="'eng'" 
								required>English
							</label>
							<label class="dcf-btn dcf-btn-secondary" 
								name="language" 
								ng-model="data.language" 
								uib-btn-radio="'spa'" 
								required>Spanish
							</label>
							<label class="dcf-btn dcf-btn-secondary"
								name="language" 
								ng-model="data.language" 
								uib-btn-radio="'ara'" 
								required>Arabic
							</label>
							<label class="dcf-btn dcf-btn-secondary" 
								name="language" 
								ng-model="data.language" 
								uib-btn-radio="'vie'" 
								required>Vietnamese
							</label>
						</div>
						<div ng-show="form.$submitted || form.language.$touched">
							<label class="error" ng-show="form.language.$error.required">This field is required.</label>
						</div>
					</li>
					-->

				<li class="form-radio" id="studentUNL">
					<label for="1" class="form-required">Are you a University of Nebraska-Lincoln or Nebraska Wesleyan student?</label>
					<div>
						<div class="dcf-btn-group" data-toggle="buttons">
							<label class="dcf-btn dcf-btn-secondary" 
								name="unlStudent" 
								ng-model="questions[1]" 
								uib-btn-radio="'1'" 
								type="radio" 
								required>Yes
							</label>
							<label class="dcf-btn dcf-btn-secondary" 
								name="unlStudent" 
								ng-model="questions[1]" 
								uib-btn-radio="'2'" 
								type="radio" 
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
						<div class="dcf-btn-group" data-toggle="buttons">
							<label class="dcf-btn dcf-btn-secondary" 
								name="intStudent" 
								ng-model="questions[2]" 
								uib-btn-radio="'1'" 
								ng-change="intStudentChanged()" 
								required>Yes
							</label>
							<label class="dcf-btn dcf-btn-secondary" 
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
						<div class="dcf-btn-group" data-toggle="buttons">
							<label class="dcf-btn dcf-btn-secondary" 
								name="visa" 
								ng-model="questions[3]" 
								uib-btn-radio="'4'" 
								ng-required="questions[2] == 1" 
								ng-change="visaChanged()" 
								required>F-1
							</label>
							<label class="dcf-btn dcf-btn-secondary" 
								name="visa" 
								ng-model="questions[3]" 
								uib-btn-radio="'5'" 
								ng-required="questions[2] == 1" 
								ng-change="visaChanged()" 
								required>J-1
							</label>
							<label class="dcf-btn dcf-btn-secondary" 
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
						<div class="dcf-btn-group" data-toggle="buttons">
							<label class="dcf-btn dcf-btn-secondary" 
								name="f1date" 
								ng-model="questions[4]" 
								uib-btn-radio="'7'" 
								ng-required="questions[3] == 4" 
								ng-click="residentialAppointment()"
								required><?php echo $taxYear - 6 ?> or earlier
							</label>
							<label class="dcf-btn dcf-btn-secondary" 
								name="f1date" 
								ng-model="questions[4]" 
								uib-btn-radio="'8'" 
								ng-required="questions[3] == 4" 
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
						<div class="dcf-btn-group" data-toggle="buttons">
							<label class="dcf-btn dcf-btn-secondary" 
								name="j1date" 
								ng-model="questions[4]" 
								uib-btn-radio="'9'" 
								ng-required="questions[3] == 5" 
								ng-click="residentialAppointment()"
								required><?php echo $taxYear - 3 ?> or earlier
							</label>
							<label class="dcf-btn dcf-btn-secondary" 
								name="j1date" 
								ng-model="questions[4]" 
								uib-btn-radio="'10'" 
								ng-required="questions[3] == 5" 
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
						<div class="dcf-btn-group form-required" data-toggle="buttons">
							<label class="dcf-btn dcf-btn-secondary" 
								name="h1bdate" 
								ng-model="questions[5]" 
								uib-btn-radio="'1'" 
								ng-required="questions[3] == 6" 
								required>Yes
							</label>
							<label class="dcf-btn dcf-btn-secondary" 
								name="h1bdate" 
								ng-model="questions[5]" 
								uib-btn-radio="'2'" 
								ng-required="questions[3] == 6" 
								ng-click="residentialAppointment()"
								required>No
							</label>
						</div>
					</div>
					<div ng-show="form.$submitted || form.h1bdate.$touched">
						<label class="error" ng-show="form.h1bdate.$error.required">This field is required.</label>
					</div>
				</li>

				<li class="form-select" id="studentCountry" ng-show="questions[4] == 8 || questions[4] == 10 || questions[5] == 1">
					<label for="6" class="form-required">What country are you a citizen or resident of?</label>
					<div>
						<select id="studentCountrySelect" 
								class="dcf-input-select"
								name="studentCountrySelect" 
								ng-model="questions[6]"
								ng-required="questions[4] == 8 || questions[4] == 10 || questions[5] == 1"
								ng-change="studentCountryChanged(questions[6])" 
								ng-options="country.name for country in countries"
								required>
								<option value="" style="display:none;">-- Select a Country --</option>
						</select>
					</div>
					<p class="dcf-txt-xs">This information is for the purpose of having a specific tax preparer available to assist with your China, India, Treaty, or Non-Treaty tax return and is stored as one of those four values.</p>
					<div ng-show="form.$submitted || form.studentCountry.$touched">
						<label class="error" ng-show="form.studentCountry.$error.required">This field is required.</label>
					</div>
				</li>
			</ul>


			<h3 class="form-subheading">Appointment Information</h3>

			<div appointment-picker class="dcf-mb-5"></div>

			<input type="submit" 
				value="Submit" 
				class="submit dcf-btn dcf-btn-primary" >
		</form>
	<?php } ?>
</div>

<!-- Successful Signup Screen -->
<div id="successfulSignupContent" class="dcf-mb-6" ng-if="successMessage != null">
	<ng-bind-html ng-bind-html="successMessage"></ng-bind-html>

	<div id="successfulSignupButtons" class="dcf-mt-4">
		<button type="button" class="dcf-btn dcf-btn-primary" onclick="window.print();">Print this Confirmation</button>
	</div>
</div>
