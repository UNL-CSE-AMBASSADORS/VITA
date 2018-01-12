
<div class="wdn-inner-wrapper wdn-inner-padding-no-top" ng-if="successMessage == null">
	<form class="cmxform" 
		id="vitaSignupForm" 
		name="form" 
		ng-submit="form.$valid && storeAppointments()" 
		autocomplete="off" 
		novalidate>
		<p mt-2 mb-3>Unsure if VITA can help you? <a href="/questionnaire" target="_blank">Click here to find out.</a></p>

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


		<h3 class="form-subheading">Add Filing Dependents</h3>
		<p>Are any of your dependents filing a return during this appointment? If so, add them here.</p>
		<ul id="dependents"></ul>
		<br>
		<button type="button" class="wdn-button wdn-button-triad" id="addDependentButton">Add Dependent</button>


		<h3 class="form-subheading">Background Information</h3>

		<ul>
			<li class="form-radio" id="language">
				<label for="language" class="form-required">Which language will you require?</label>
				<div>
					<div class="btn-group" data-toggle="buttons">
						<!-- NOTE: the values here are the ISO 639-2/T specfication for language codes (https://en.wikipedia.org/wiki/List_of_ISO_639-1_codes) -->
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

			<li class="form-radio" id="studentUNL">
				<label for="1" class="form-required">Are you a University of Nebraska-Lincoln student?</label>
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
				<label for="2" class="form-required">Are you an International Student Scholar?</label>
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
				<label for="4" class="form-required">How long have you been in the United States?</label>
				<div>
					<div class="btn-group" data-toggle="buttons">
						<label class="wdn-button btn" 
							name="f1date" 
							ng-model="questions[4]" 
							uib-btn-radio="'7'" 
							ng-required="questions[3] == 4" 
							ng-click="standardAppointment()"
							required>2012 or earlier
						</label>
						<label class="wdn-button btn" 
							name="f1date" 
							ng-model="questions[4]" 
							uib-btn-radio="'8'" 
							ng-required="questions[3] == 4" 
							ng-click="studentScholarAppointment()"
							required>2013 or later
						</label>
					</div>
				</div>
				<div ng-show="form.$submitted || form.f1date.$touched">
					<label class="error" ng-show="form.f1date.$error.required">This field is required.</label>
				</div>
			</li>

			<li class="form-radio" id="studentj1" ng-show="questions[2] == 1 && questions[3] == 5">
				<label for="4" class="form-required">How long have you been in the United States?</label>
				<div>
					<div class="btn-group" data-toggle="buttons">
						<label class="wdn-button btn" 
							name="j1date" 
							ng-model="questions[4]" 
							uib-btn-radio="'9'" 
							ng-required="questions[3] == 5" 
							ng-click="standardAppointment()"
							required>2015 or earlier
						</label>
						<label class="wdn-button btn" 
							name="j1date" 
							ng-model="questions[4]" 
							uib-btn-radio="'10'" 
							ng-required="questions[3] == 5" 
							ng-click="studentScholarAppointment()"
							required>2016 or later
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

		<div appointment-picker></div>

		<div>Form valid? {{form.$valid}}</div>

		<input type="submit" 
			value="Submit" 
			class="submit wdn-button wdn-button-triad" >
			<!-- ng-click="storeAppointments()"> -->
	</form>
</div>

<div class="wdn-inner-wrapper wdn-inner-padding-no-top" ng-if="successMessage != null">{{successMessage}}</div>
