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

		
		<!-- Shown when the client information is not yet validated -->
		<div ng-if="clientInformationValidated === false">
			<p>For security reasons, you must verify the information associated with your appointment</p>
			<p ng-if="invalidClientInformation === true" class="error">
				The information you provided did not match our records. Please try again.
			</p>

			<!-- Form to validate client information -->
			<form class="cmxform dcf-form" 
					id="validateClientInformationForm"
					name="form" 
					ng-submit="form.$valid && validateClientInformation()" 
					autocomplete="off" 
					novalidate>

				<ul class="dcf-list-bare">
					<li class="dcf-form-group form-textfield">
						<label class="dcf-label form-label form-required" for="firstName">First Name</label>
						<input class="dcf-input-text" type="text" name="firstName" id="firstName" ng-model="clientData.firstName" required>
						<div ng-show="form.$submitted || form.firstName.$touched">
							<label class="error" ng-show="form.firstName.$error.required">This field is required.</label>
						</div>
					</li>

					<li class="dcf-form-group form-textfield">
						<label class="dcf-label form-label form-required" for="lastName">Last Name</label>
						<input class="dcf-input-text" type="text" name="lastName" id="lastName" ng-model="clientData.lastName" required>
						<div ng-show="form.$submitted || form.lastName.$touched">
							<label class="error" ng-show="form.lastName.$error.required">This field is required.</label>
						</div>
					</li>

					<li class="dcf-form-group form-textfield">
						<label class="dcf-label form-label" for="email">Email</label>
						<input class="dcf-input-text" type="email" name="email" id="email" ng-model="clientData.email">
						<p class="dcf-txt-xs">If you provided an email while signing up, this field is required to verify your information.</p>
					</li>

					<li class="dcf-form-group form-textfield">
						<label class="dcf-label form-label form-required" for="phone">Phone Number</label>
						<input class="dcf-input-text" type="text" name="phone" id="phone" ng-model="clientData.phone" required>
						<div ng-show="form.$submitted || form.phone.$touched">
							<label class="error" ng-show="form.phone.$error.required">This field is required.</label>
						</div>
					</li>
				</ul>

				<input type="submit" 
					value="Submit" 
					class="submit dcf-btn dcf-btn-primary"
					ng-model="validatingClientInformation" 
					ng-disabled="!form.$valid || validatingClientInformation">
			</form>
		</div>


		<!-- Shown once the client information has been validated -->
		<div ng-if="clientInformationValidated === true">
			<p class="clear-top">Thank you for verifying your information. You may now upload your documents below. See the bottom of this page for all the documents required.</p>

			<!-- File upload area-->
			<table class="dcf-table-striped dcf-w-100%">
				<tbody>
					<tr>
						<th>Select File</th>
						<th>Upload</th>
						<th>Remove</th>
						<th>Status</th>
					</tr>
					<tr ng-repeat="fileRepresentative in fileRepresentatives">
						<td>
							<input type="file" 
								id="{{fileRepresentative.id}}" 
								accept=".pdf, .png, .jpeg, .jpg"
								ng-disabled="fileRepresentative.uploading || fileRepresentative.uploadSucceeded" />
						</td>
						<td>
							<button type="button" 
								class="dcf-btn dcf-btn-primary" 
								ng-click="uploadDocument(fileRepresentative)" 
								ng-disabled="fileRepresentative.uploading || fileRepresentative.uploadSucceeded">Upload</button>
						</td>
						<td>
							<button type="button" 
								class="dcf-btn dcf-btn-secondary" 
								ng-click="removeDocument(fileRepresentative)" 
								ng-disabled="fileRepresentative.uploading || fileRepresentative.uploadSucceeded">Remove</button>
						</td>
						<td class="dcf-relative" ng-class="{ 'error-text': fileRepresentative.error, 'success-text': fileRepresentative.uploadSucceeded }">
							<div ng-if="fileRepresentative.uploading" class="loading-spinner" role="status">
								<span class="dcf-sr-only">Loading...</span>
							</div>
							<div ng-class="{ 'dcf-ml-8': fileRepresentative.uploading }">
								{{fileRepresentative.statusMessage}}
							</div>
						</td>
					</tr>
				</tbody>
			</table>
			<button type="button" class="dcf-btn dcf-btn-secondary dcf-mt-2" ng-click="addAnotherDocument()">Add Another Document</button>

			<!-- Required documents descriptions -->
			<div class="dcf-mt-5">
				<h4>Required Documents to Upload:</h4>
				TODO: THESE DOCUMENTS NEED TO BE UPLOADED TO PREPARE YOUR RETURN
			</div>

		</div>
	</div>
</div>
