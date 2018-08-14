<div class="wdn-inner-wrapper wdn-inner-padding-no-top">
	
	<!-- Shown when the token is invalid or does not exist --> 
	<div ng-if="tokenExists === false">
		<a href="/">You appear to have reached this page in error. Please click here to the main page.</a>
	</div>
	
	<!-- Shown when the token is valid and exists -->
	<div ng-if="tokenExists === true">
		Your token exists {{token}}

		<!-- Form to validate client information -->
		<form class="cmxform" 
		id="vitaSignupForm" 
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
				class="submit wdn-button wdn-button-triad" >
		</form>
		<!-- End form to validate client information -->

	</div>












	






















</div>
