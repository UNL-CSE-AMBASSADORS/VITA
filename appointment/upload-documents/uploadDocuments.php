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
			<p class="clear-top">Thank you for verifying your information. You may now upload your documents below. See the bottom of this page for a list of relevant documents you should upload as applicable. After your documents have been uploaded, a tax preparer will begin preparing your taxes and you will be contacted by a quality reviewer prior to your taxes being submitted.</p>

			<!-- File upload area-->
			<table class="dcf-table-striped dcf-w-100% dcf-mt-1">
				<tbody>
					<tr>
						<th>Select File</th>
						<th>Upload</th>
						<th>Remove</th>
						<th>Status</th>
					</tr>
					
					<tr ng-repeat="fileRepresentative in fileRepresentatives" class="dcf-p-2">
						<td>
							<input type="file" 
								id="{{fileRepresentative.id}}" 
								accept=".pdf, .png, .jpeg, .jpg"
								ng-disabled="fileRepresentative.uploading || fileRepresentative.uploadSucceeded" />
						</td>
						<td class="dcf-pt-1 dcf-pb-1">
							<button type="button" 
								class="dcf-btn dcf-btn-primary" 
								ng-click="uploadDocument(fileRepresentative)" 
								ng-disabled="fileRepresentative.uploading || fileRepresentative.uploadSucceeded">Upload</button>
						</td>
						<td class="dcf-pt-1 dcf-pb-1">
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
			<p class="dcf-mt-2 dcf-txt-xs">Documents are transmitted and stored securely. These documents will not be shared with anyone except the Lincoln VITA volunteers helping to prepare your tax return and will be deleted after 14 days.</p>

			<!-- Relevant documents descriptions -->
			<div class="dcf-mt-6">
				<h4>Relevant Documents to Upload:</h4>
				<h6>Intake Form</h6>
				<ul>
					<li>Please <b><a href ng-click="downloadIntakeForm()">download this Intake Form</a></b>, fill it out, and submit it</li>
				</ul>
				<h6>Identification:</h6>
				<ul>
					<li><b>Social Security Cards</b> or <b>ITIN Letters</b> for <span class="dcf-uppercase">everyone</span> who will be included on the return</li>
					<li><b>Photo IDs</b> for <b class="dcf-uppercase">all</b> tax return signers (<span class="dcf-uppercase">both</span> spouses must sign if filing jointly)</li>
					<li><b>Passports</b> for <b class="dcf-uppercase">all</b> tax return signers (if international tax return)</li>
					<li><b>Birthdates</b> and <b>number of months in the home</b> for <span class="dcf-uppercase">everyone</span> who will be included on the return</li>
				</ul>
				<h6>Income:</h6>
				<ul>
					<li><b>W-2s</b> for wages, <b>W-2Gs</b> for gambling income</li>
					<li><b>1099s</b> for interest, dividends, unemployment, state tax refunds, pension or 401-K distributions, and other income</li>
					<li><b>Records</b> of revenue from self-employment or home-based businesses</li>
				</ul>
				<h6>Expenses:</h6>
				<ul>
					<li><b>1098s</b> for mortgage interest, student loan interest (1098-E), or tuition (1098-T), statement of property tax paid</li>
					<li><b>Statement of college student account</b> showing all charges and payments for each student on the return</li>
					<li><b>Childcare receipts</b>, including tax ID and address for childcare provider</li>
					<li><b>1095s</b> showing creditable health insurance coverage</li>
					<li><b>Records</b> of expenses for self-employment or home-based businesses</li>
					<li><b>1042-S</b> for international tax returns (If you received one, not everyone receives one)</li>
				</ul>
				<h6>Immigration Documents (if an international tax return):</h6>
				<ul>
					<li><b>I-94</b></li>
					<li><b>I-20</b></li>
					<li><b>DS-2019</b> for those in J1 status</li>
				</ul>
				<h6>Miscellaneous:</h6>
				<ul>
					<li>Checking or savings account information for direct deposit/direct debit</li>
				</ul>
			</div>

		</div>
	</div>
</div>
