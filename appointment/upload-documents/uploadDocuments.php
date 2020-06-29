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
			<div ng-if="!agreeToVirtualPreparationCheckbox.checked">
				<p>
					Thank you for verifying your information. Before you can upload documents, we need your consent
					to use virtual tax preparation. See <a href ng-click="downloadForm14446()">Form 14446 (Virtual VITA/TCE Taxpayer Consent)</a>
				</p>

				<div class="dcf-input-checkbox">
					<input id="agree-to-virtual-preparation-checkbox" type="checkbox" ng-model="agreeToVirtualPreparationCheckbox.checked" value="false">
					<label for="agree-to-virtual-preparation-checkbox">I agree to have my tax return prepared virtually</label>
				</div>
			</div>

			<div ng-if="agreeToVirtualPreparationCheckbox.checked">
				<p>
					You may now upload your documents below. 
					See the bottom of this page for a list of relevant documents you should upload as applicable. 
					After your documents have been uploaded, a tax preparer will begin preparing your taxes and 
					you will be contacted by a quality reviewer prior to your taxes being submitted.
				</p>

				<!-- File upload area-->
				<fieldset>
					<legend>File Upload</legend>
					<table class="dcf-table dcf-table-striped dcf-table-responsive dcf-w-100% dcf-mt-1">
						<thead>
							<tr>
								<th scope="col">Select File</th>
								<th scope="col">Upload</th>
								<th scope="col">Remove</th>
								<th scope="col">Status</th>
							</tr>
						</thead>
						<tbody>
							<tr ng-repeat="fileRepresentative in fileRepresentatives" class="dcf-p-2">
								<td data-label="Select File">
									<input type="file" 
										id="{{fileRepresentative.id}}" 
										accept=".pdf, .png, .jpeg, .jpg"
										ng-disabled="fileRepresentative.uploading || fileRepresentative.uploadSucceeded" />
								</td>
								<td data-label="Upload" class="dcf-pt-1 dcf-pb-1">
									<button type="button" 
										class="dcf-btn dcf-btn-primary" 
										ng-click="uploadDocument(fileRepresentative)" 
										ng-disabled="fileRepresentative.uploading || fileRepresentative.uploadSucceeded">Upload</button>
								</td>
								<td data-label="Remove" class="dcf-pt-1 dcf-pb-1">
									<button type="button" 
										class="dcf-btn dcf-btn-secondary" 
										ng-click="removeDocument(fileRepresentative)" 
										ng-disabled="fileRepresentative.uploading || fileRepresentative.uploadSucceeded">Remove</button>
								</td>
								<td data-label="Status" class="dcf-relative" ng-class="{ 'error-text': fileRepresentative.error, 'success-text': fileRepresentative.uploadSucceeded }">
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
					<p class="dcf-mt-2 dcf-txt-xs">Documents are transmitted and stored securely. These documents will not be shared with anyone except the VITA volunteers helping to prepare your tax return and will be deleted after 14 days.</p>
				</fieldset>

				<!-- Ready button -->
				<fieldset class="dcf-mt-3">
					<legend>Appointment Ready</legend>
					<p>
						After you mark your appointment as 'ready', a tax preparer will begin preparing your return within 48 hours. 
						If the tax preparer does not have all the necessary documents, they will not be able to prepare your return. 
						You may return to this page to upload additional documents if necessary.
					</p>
					<div class="dcf-input-checkbox">
						<input id="ready-checkbox" type="checkbox" ng-model="readyCheckbox.checked" value="false">
						<label for="ready-checkbox">I have uploaded all the necessary documents and my appointment is ready to be prepared</label>
					</div>
					<button type="button"
						class="dcf-btn dcf-btn-primary dcf-mt-1"
						ng-disabled="!readyCheckbox.checked || submittingAppointmentReady || appointmentMarkedAsReadySuccessfully"
						ng-click="markAppointmentAsReady()">Mark My Appointment as Ready</button>
				</fieldset>
				

				<!-- Relevant documents descriptions -->
				<div class="dcf-mt-6">
					<h4>Relevant Documents to Upload:</h4>
					<!-- Residential appointments -->
					<div ng-if="isResidentialAppointment">
						<h6>Required Forms:</h6>
						<ul>
							<b class="dcf-uppercase">Important Note</b>: If you are typing your information into the fillable form, you must "save as" to your device before uploading it or your information will not save.
							<li><b>Completed <a href ng-click="downloadForm14446()">Form 14446</a></b>. The <b>bottom portion of page two</b> (Part III: Taxpayer Consents) needs to be completed.</li>
							<li><b>Completed <a href ng-click="downloadResidentIntakeForm()">Form 13614-C</a></b> (Intake Form). Without this form, VITA cannot prepare your return.</li>
						</ul>
						<h6>Identification:</h6>
						<ul>
							<li><b>Social Security Cards</b> or <b>ITIN Letters</b> for <span class="dcf-uppercase">everyone</span> who will be included on the return</li>
							<li><b>Photo IDs</b> for <b class="dcf-uppercase">all</b> tax return signers (<span class="dcf-uppercase">both</span> spouses must sign if filing jointly)</li>
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
						</ul>
						<h6>Direct Deposit Information:</h6>
						<ul>
							<li>Picture of a <b>blank check</b> showing account number, routing number, and account owner's name</li>
							<li>A previous <b>bank statement</b> showing bank name, account number, routing number, and account owner's name</li>
						</ul>
					</div>



					<!-- Non-Residential appointments -->
					<div ng-if="!isResidentialAppointment">
						<h6>Required Forms:</h6>
						<ul>
							<b class="dcf-uppercase">Important Note</b>: If you are typing your information into the fillable form, you must "save as" to your device before uploading it or your information will not save.
							<li><b>Completed <a href ng-click="downloadForm14446()">Form 14446</a></b>. The <b>bottom portion of page two</b> (Part III: Taxpayer Consents) needs to be completed.</li>
							<li><b>Completed <a href ng-click="downloadNonResidentIntakeForm()">Form 13614-NR</a></b> (Intake Form). Without this form, VITA cannot prepare your return.</li>
						</ul>
						<h6>Identification:</h6>
						<ul>
							<li><b>Social Security Cards</b> or <b>ITIN Letters</b> for <span class="dcf-uppercase">everyone</span> who will be included on the return</li>
							<li><b>Passports</b> for <b class="dcf-uppercase">all</b> tax return signers</li>
						</ul>
						<h6>Immigration Documents:</h6>
						<ul>
							<li><b>I-94</b></li>
							<li><b>I-20</b></li>
							<li><b>DS-2019</b> for those in J1 status</li>
						</ul>
						<h6>Income:</h6>
						<ul>
							<li><b>W-2s</b> for wages</li>
							<li><b>1042-S</b> (If you received one, not everyone receives one)</li>
							<li><b>1099s</b> for interest, dividends, unemployment, state tax refunds, pension or 401-K distributions, and other income</li>
						</ul>
						<h6>Expenses:</h6>
						<ul>
							<li><b>1098s</b> for mortgage interest, student loan interest (1098-E), or tuition (1098-T), statement of property tax paid</li>
							<li><b>Statement of college student account</b> showing all charges and payments for each student on the return</li>
							<li><b>Childcare receipts</b>, including tax ID and address for childcare provider</li>
							<li><b>1095s</b> showing creditable health insurance coverage</li>
							<li><b>Records</b> of expenses for self-employment or home-based businesses</li>
						</ul>
						<h6>Direct Deposit Information:</h6>
						<ul>
							<li>Picture of a <b>blank check</b> showing account number, routing number, and account owner's name</li>
							<li>A previous <b>bank statement</b> showing bank name, account number, routing number, and account owner's name</li>
						</ul>
					</div>

				</div>
			</div>

		</div>
	</div>
</div>
