<div class="wdn-inner-wrapper wdn-inner-padding-no-top">
	
	<!-- Shown when data is loading upon initial page load -->
	<div ng-if="tokenExists === null">
		<p>We are checking our records, please wait.</p>
	</div>

	<!-- Shown when the token is invalid or does not exist --> 
	<div ng-if="tokenExists === false" ng-cloak>
		<a href="/">You appear to have reached this page in error. Please click here to the main page.</a>
	</div>
	
	<!-- Shown when the token is valid and exists -->
	<div ng-if="tokenExists === true" ng-cloak>

		<!-- Shown when the client information is not yet validated -->
		<div ng-if="clientInformationValidated === false">
			<p>For security reasons, you must verify the information associated with your appointment</p>
			<p ng-if="invalidClientInformation === true" class="error">
				The information you provided did not match our records. Please try again.
			</p>

			<!-- Form to validate client information -->
			<form class="cmxform" 
					id="validateClientInformationForm"
					name="form" 
					ng-submit="form.$valid && validateClientInformation()" 
					autocomplete="off" 
					novalidate>

				<ul>
					<li class="form-textfield">
						<label class="form-label form-required" for="firstName">First Name</label>
						<input type="text" name="firstName" id="firstName" ng-model="clientData.firstName" required>
						<div ng-show="form.$submitted || form.firstName.$touched">
							<label class="error" ng-show="form.firstName.$error.required">This field is required.</label>
						</div>
					</li>

					<li class="form-textfield">
						<label class="form-label form-required" for="lastName">Last Name</label>
						<input type="text" name="lastName" id="lastName" ng-model="clientData.lastName" required>
						<div ng-show="form.$submitted || form.lastName.$touched">
							<label class="error" ng-show="form.lastName.$error.required">This field is required.</label>
						</div>
					</li>

					<li class="form-textfield">
						<label class="form-label" for="email">Email</label>
						<input type="email" name="email" id="email" ng-model="clientData.email">
					</li>

					<li class="form-textfield">
						<label class="form-label form-required" for="phone">Phone Number</label>
						<input type="text" name="phone" id="phone" ng-model="clientData.phone" required>
						<div ng-show="form.$submitted || form.phone.$touched">
							<label class="error" ng-show="form.phone.$error.required">This field is required.</label>
						</div>
					</li>
				</ul>

				<input type="submit" 
					value="Submit" 
					class="submit wdn-button wdn-button-triad"
					ng-model="validatingClientInformation" 
					ng-disabled="!form.$valid || validatingClientInformation">
			</form>
		</div>

		<!-- TODO: NEED TO CONTROL WHEN THESE SHOW UP. I.E. NOT AFTER THE APPT HAS STARTED... Or if it's been cancelled -->

		<!-- Shown once the client information has been validated -->
		<div ng-if="clientInformationValidated === true">

			<div ng-if="!rescheduleSuccessMessage && appointmentCancelled === false">
				<p>Thank you for verifying your information. You may now cancel or reschedule your appointment below.</p>

				<!-- Current Appointment Information -->
				<h4 class="clear-top">Current Appointment Information</h4>
				<div>
					<div>
						<p class="clear-vertical"><b>Name:</b> {{clientData.firstName}} {{clientData.lastName}}</p>
					</div>
					<div>
						<p class="clear-vertical"><b>Site:</b> {{currentAppointment.site.title}}</p>
					</div>
					<div>
						<p class="clear-vertical"><b>Site Address:</b> {{currentAppointment.site.address}}</p>
					</div>
					<div>
						<p class="clear-top"><b>Time:</b> {{currentAppointment.scheduledTime}}</p>
					</div>

					<!-- Cancel Button -->
					<button type="button" 
							value="Cancel"
							id="cancelButton"
							class="submit wdn-button wdn-button-brand"
							ng-click="cancelAppointment()">Cancel Appointment</button>
				</div>

				
				<!-- Horizontal separator -->
				<div class="horizontal-line"></div>


				<!-- Reschedule Section -->				
				<h4 class="clear-top">Reschedule Appointment</h4>

				<form class="cmxform" id="rescheduleForm">
					<div appointment-picker></div>

					<!-- Reschedule button -->
					<input type="submit" 
						value="Reschedule" 
						id="rescheduleButton" 
						class="submit wdn-button wdn-button-triad" 
						ng-disabled="appointmentPickerSharedProperties.selectedDate == null || appointmentPickerSharedProperties.selectedSite == null || appointmentPickerSharedProperties.selectedTime == null || submittingReschedule" 
						ng-model="submittingReschedule" 
						ng-click="rescheduleAppointment()">

					<!-- TODO: NEED TO ACTUALLY SEND THIS EMAIL THEN... -->
					<p>If you have an email on record, an email will automatically be sent to you confirming the change.</p>
				</form>
			</div>


			<!-- Successful Reschedule Screen -->
			<div ng-if="rescheduleSuccessMessage != null">
				<ng-bind-html ng-bind-html="rescheduleSuccessMessage"></ng-bind-html>

				<div>
					<button type="button" class="mb-3 wdn-button btn wdn-button-triad" onclick="window.print();">Print</button>
					<button type="button" 
						class="mb-3 wdn-button btn wdn-button-triad email-confirmation-button" 
						ng-if="clientData.email != null && clientData.email.length > 0"
						ng-disabled="emailButton.disabled"
						ng-click="emailConfirmation()">{{emailButton.text}}</button>
				</div>
			</div>


			<!-- Successful Cancelling Screen -->
			<!-- TODO MAKE THIS BETTER -->
			<div ng-if="appointmentCancelled === true">
				<p>Your appointment has been cancelled.</p>
			</div>
		</div>
	</div>
</div>
