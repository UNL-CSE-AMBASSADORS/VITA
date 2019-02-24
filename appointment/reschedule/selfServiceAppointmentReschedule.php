<div class="dcf-wrapper dcf-mb-8">

	<!-- Shown when data is loading upon initial page load -->
	<div ng-if="tokenExists === null">
		<p>We are checking our records, please wait.</p>
	</div>

	<!-- Shown when the token is invalid or does not exist --> 
	<div ng-if="tokenExists === false" ng-cloak>
		<a href="/">You appear to have reached this page in error. Please click here to go to the main page.</a>
	</div>
	
	<!-- Shown when the token is valid and exists -->
	<div ng-if="tokenExists === true" ng-cloak>

		<!-- Shown when the appointment is not allowed to be rescheduled/cancelled for any reason -->
		<div ng-if="!validForReschedule">
			<p>Your appointment cannot be cancelled or rescheduled.</p>
			<div>
				<p class="wdn-intro-p"><b>Reason:</b> {{invalidForRescheduleReason}}</p>
			</div>
			<p>If you believe this is a mistake, please call {{phoneNumberToCall}} to speak with a VITA volunteer.</p>
		</div>

		<!-- Shown when the client information is not yet validated -->
		<div ng-if="validForReschedule && clientInformationValidated === false">
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

				<ul class="dcf-list-bare">
					<li class="form-textfield">
						<label class="dcf-label form-label form-required" for="firstName">First Name</label>
						<input class="dcf-input-text" type="text" name="firstName" id="firstName" ng-model="clientData.firstName" required>
						<div ng-show="form.$submitted || form.firstName.$touched">
							<label class="error" ng-show="form.firstName.$error.required">This field is required.</label>
						</div>
					</li>

					<li class="form-textfield">
						<label class="dcf-label form-label form-required" for="lastName">Last Name</label>
						<input class="dcf-input-text" type="text" name="lastName" id="lastName" ng-model="clientData.lastName" required>
						<div ng-show="form.$submitted || form.lastName.$touched">
							<label class="error" ng-show="form.lastName.$error.required">This field is required.</label>
						</div>
					</li>

					<li class="form-textfield">
						<label class="dcf-label form-label" for="email">Email</label>
						<input class="dcf-input-text" type="email" name="email" id="email" ng-model="clientData.email">
					</li>

					<li class="form-textfield">
						<label class="dcf-label form-label form-required" for="phone">Phone Number</label>
						<input class="dcf-input-text" type="text" name="phone" id="phone" ng-model="clientData.phone" required>
						<div ng-show="form.$submitted || form.phone.$touched">
							<label class="error" ng-show="form.phone.$error.required">This field is required.</label>
						</div>
					</li>
				</ul>

				<input type="submit" 
					value="Submit" 
					class="submit dcf-btn"
					ng-model="validatingClientInformation" 
					ng-disabled="!form.$valid || validatingClientInformation">
			</form>
		</div>


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

					<!-- Confirm Appointment Cancel Modal -->
					<div class="hidden">
						<div class="modal" id="confirm-cancel-modal" tabindex="-1" role="dialog" aria-hidden="true">
							<div class="dcf-wrapper dcf-pt-8 dcf-pb-5">
								<h4>Are you sure you want to cancel your appointment?</h4>
								<div>
									<button type="button" class="dcf-btn dcf-btn close-modal-button">No, do not Cancel</button>
									<button type="button" class="dcf-btn dcf-btn-secondary" ng-click="cancelAppointment()">Yes, cancel</button>
								</div>
							</div>
						</div>
					</div>
					<!-- End Confirm Appointment Cancel Modal -->

					<!-- Cancel Button -->
					<a id="confirm-cancel-modal-opener"
					   class="submit dcf-btn dcf-btn-secondary"
					   href="#confirm-cancel-modal">Cancel Appointment</a>
				</div>

				
				<!-- Horizontal separator -->
				<div class="horizontal-line"></div>


				<!-- Reschedule Section -->				
				<h4 class="clear-top">Reschedule Appointment</h4>

				<form class="cmxform" id="rescheduleForm">
					<div appointment-picker class="dcf-mb-5"></div>

					<!-- Reschedule button -->
					<input type="submit" 
						value="Reschedule" 
						id="rescheduleButton" 
						class="submit dcf-btn" 
						ng-disabled="appointmentPickerSharedProperties.selectedDate == null || appointmentPickerSharedProperties.selectedSite == null || appointmentPickerSharedProperties.selectedTime == null || submittingReschedule" 
						ng-model="submittingReschedule" 
						ng-click="rescheduleAppointment()">
				</form>
			</div>


			<!-- Successful Reschedule Screen -->
			<div ng-if="rescheduleSuccessMessage != null">
				<ng-bind-html ng-bind-html="rescheduleSuccessMessage"></ng-bind-html>

				<div>
					<button type="button" class="mb-3 dcf-btn btn" onclick="window.print();">Print</button>
					<button type="button" 
						class="mb-3 dcf-btn btn email-confirmation-button" 
						ng-if="clientData.email != null && clientData.email.length > 0"
						ng-disabled="emailButton.disabled"
						ng-click="emailConfirmation()">{{emailButton.text}}</button>
				</div>
			</div>


			<!-- Successful Cancelling Screen -->
			<div ng-if="appointmentCancelled === true">
				<p>Your appointment has been cancelled.</p>
			</div>
		</div>
	</div>
</div>
