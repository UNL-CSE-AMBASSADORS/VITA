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
						<p class="dcf-txt-xs">Please recall we asked you to provide a number accessible to restricted callers (*67), if possible</p>
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
			<div ng-if="consentData.completedConsent === false">
				<p>
					Thank you for verifying your information. Before you can upload documents, we need your consent
					to use virtual tax preparation. See Virtual VITA/TCE Taxpayer Consent information below.
				</p>
				<p class="dcf-txt-h3 dcf-txt-center dcf-uppercase"><b>virtual VITA/TCE Taxpayer Consent</b></p>
				<p>This form is required whenever the taxpayer’s tax return is completed and/or quality reviewed in a
					non-face-to-face environment. The site must explain to the taxpayer the process this site will use
					to prepare the taxpayer’s return. If applicable, taxpayers must also be advised of all procedures and
					the associated risk if their data will be transferred from one site location to another site location.
				</p>
				<p class="dcf-txt-h5 dcf-bold">PART I: Site Information</p>
				<p>SITE NAME: <b>Lincoln Virtual VITA</b></p>
				<p>SITE ADDRESS: <b>1400 R Street, 222 Nebraska Union, Lincoln, NE 68588-0444</b></p>
				<p>SITE IDENTIFICATION NUMBER (SIDN): <b>S53017050</b></p>
				<p>SITE COORDINATORS: <b>Linda, Kyla, or Tracy</b></p>
				<p>SITE CONTACT NAME: <b>UNL Tax Credit Campaign</b></p>
				<p>SITE CONTACT TELEPHONE NUMBER: <b>VITA@UNL.EDU</b></p>
				<p class="dcf-bold">This site is using the following Virtual VITA/TCE method(s) to prepare your tax return:</p>
				<p><b>100% Virtual VITA/TCE Process:</b> This method includes non face-to-face interactions with the taxpayer and
					any of the VITA/TCE volunteers during the intake, interview, return preparation, quality review, and
					signing the tax return. The taxpayer will be explained the full process and is required to consent to
					step-by-step process used by the site. This includes the virtual procedures to send required documents
					(social security numbers, Form W-2 and other documents) through a secured file sharing system to a
					designated volunteer for review.
				</p>
				<p class="dcf-txt-h5 dcf-bold">PART II: The Sites Process</p>
				<ol>
					<li><b>Scheduling the appointment:</b> Client schedules appointment at vita.unl.edu/signup and receives an email with a secure, unique link to verify information.</li>
					<li><b>Securing Taxpayer Consent Agreement:</b>	Client agrees by checking a box on the scheduling app, agreeing to participate in the virtual return preparation process. Clients read and electronically sign Form 14446 with a completed and signed Form 13614, Intake, Interview & Quality Review Sheet when uploading documents.</li>				
					<li><b>Performing the Intake Process (secure all documents):</b>  The site administrator will review the Client's uploaded documents ensuring receipt of signed Forms 14446 and 13614. When all documents are ready for review the Client will check the ready for tax preparation box on the secured link provided to them. The site coordinator will assign the Client to a certified return preparer who will then call the Client to review Form 13614.</li>
					<li><b>Validating taxpayer's authentication (Reviewing photo identification & Social Security Cards/ITINS:</b> The site administrator will review the Client's uploaded documents ensuring receipt of signed Forms 14446 and 13614. When all documents are ready for review the Client will check the ready for tax preparation box on the secured link provided to them. The site coordinator will assign the Client to a certified return preparer who will then call the Client to review Form 13614.</li>
					<li><b>Performing the interview with the taxpayer(s):</b> The Return Preparer will contact the Client by phone to discuss Form 13614 and review all photo identification, social security cards/ITIN letter and income documents that have been uploaded.</li>
					<li><b>Preparing the tax return:</b> An IRS tax law certified return preparer will be assigned to the Client by the Site Coordinator. The Site Coordinator will assign a Client to a Return Prepare based upon the certification level of the Return Preparer.</li>
					<li><b>Performing the quality review:</b> The Site Coordinator will review the Client's return.</li>
					<li><b>Sharing the completed return:</b> The quality reviewed tax return will be uploaded and sent via secured link to the Client. The Return Preparer will call the Client to review the finished return, making any needed edits to the return, and asking the Client to sign Form 8879.</li>
					<li><b>Signing the return:</b> The Client will upload on a secure link a signed and dated copy of their Form 8879.</li>
					<li><b>E-filing the tax return:</b> The Site Coordinator will review and e-file the tax return.</li>
				</ol>
				
				<p class="dcf-txt-h5 dcf-bold">Part III: Taxpayer Consents:</p>
				<form class="cmxform dcf-form" id="vitaConsentForm" name="consentForm" ng-submit="storeConsent()" autocomplete="off" novalidate>
					<p class="dcf-bold">Request to Review your Tax Return for Accuracy:</p>	
					<p>To ensure you are receiving quality services and an accurately prepared tax return at the volunteer site,
						IRS employees randomly select free tax preparation sites for review. If errors are identified, the site
						will make the necessary corrections. IRS does not keep any personal information from your reviewed tax
						return and this allows them to rate our VITA/TCE return preparation programs for accurately prepared tax
						returns. If you do not wish to have your return included as part of the review process, it will not affect
						the services provided to you at this site. If the site preparing this return is selected, do you consent
						to having your return reviewed for accuracy, by an IRS employee?
					</p>
					<li class="form-radio" id="consentReview">
						<label class="form-required"></label>
						<div>
							<div class="dcf-btn-group" data-toggle="buttons">
								<label class="dcf-btn dcf-btn-secondary" 
									name="consentToReview" 
									ng-model="consentData.reviewConsent" 
									uib-btn-radio="true" 
									type="radio"
									required>Yes
								</label>
								<label class="dcf-btn dcf-btn-secondary" 
									name="consentToReview" 
									ng-model="consentData.reviewConsent" 
									uib-btn-radio="false" 
									type="radio"
									required>No
								</label>
							</div>
						</div>
						<div ng-show="consentForm.$submitted">
							<label class="error" ng-show="consentForm.consentToReview.$error.required">This field is required.</label>
						</div>
					</li>

					<p class="dcf-bold">Virtual Consent Disclosure:</p>
					<p>If you agree to have your tax return prepared and your tax documents handled in the above manner,
						your signature and/or agreement is required on this document. Signing this document means that
						you are agreeing to the procedures stated above for preparing a tax return for you. (If this
						is a Married Filing Joint return both spouses must sign and date this document.) If you choose
						not to sign this form, we may not be able to prepare your tax return using this process. Since
						we are preparing your tax return virtually, we have to secure your consent agreeing to this 
						process. If you consent to use these non-IRS virtual systems to disclose or use your tax return
						information, Federal law may not protect your tax return information from further use or distribution
						in the event these systems are hacked or breached without our knowledge. If you agree to the disclosure
						of your tax return information, your consent is valid for the amount of time that you specify. If
						you do not specify the duration of your consent, your consent is valid for one year from the date
						of signature. If you believe your tax return information has been disclosed or used improperly in a
						manner unauthorized by law or without your permission, you may contact the Treasury Inspector General
						for Tax Administration (TIGTA) by telephone at 1-800-366-4484, or by e-mail at complaints@tigta.treas.gov .
						While the IRS is responsible for providing oversight requirements to Volunteer Income Tax Assistance (VITA) 
						and Tax Counseling for the Elderly (TCE) programs, these sites are operated by IRS sponsored partners who
						manage IRS site operations requirements and volunteer ethical standards. In addition, the locations of these 
						sites may not be in or on federal property. </p>
					<li class="form-radio" id="consentVirtual">
						<label class="form-required">I am agreeing to use this site's Virtual VITA/TCE Process</label>
						<div>
							<div class="dcf-btn-group" data-toggle="buttons">
								<label class="dcf-btn dcf-btn-secondary" 
									name="consentToVirtual" 
									ng-model="consentData.virtualConsent" 
									uib-btn-radio="true" 
									required>Yes
								</label>
								<label class="dcf-btn dcf-btn-secondary" 
									name="consentToVirtual" 
									ng-model="consentData.virtualConsent" 
									uib-btn-radio="false" 
									required>No
								</label>
							</div>
						</div>
						<div ng-show="consentForm.$submitted">
							<label class="error" ng-show="consentForm.consentToVirtual.$error.required">This field is required.</label>
						</div>
						<p class="cant-help-text" ng-show="consentData.virtualConsent == false">Sorry, we cannot prepare your tax return without your consent to the virtual process.</p>
					</li>

					<li class="dcf-form-group form-textfield">
						<label class="dcf-label form-label form-required" for="signature">TYPE YOUR NAME HERE</label>
						<input type="text" class="dcf-input-text form-control" name="signature" id="signature" ng-model="consentData.signature" required>
						<div ng-show="consentForm.$submitted || consentForm.signature.$touched">
							<label class="error" ng-show="consentForm.signature.$error.required">This field is required.</label>
						</div>
					</li>

					<p ng-if="consentForm.$invalid && consentForm.$submitted" class="error">Your input is invalid, please correct the errors above and re-submit this form</p>
					<input type="submit" 
						value="Submit" 
						class="submit dcf-btn dcf-btn-primary dcf-mt-4" 
						ng-disabled="!consentData.virtualConsent == true || consentData.reviewConsent == null || consentData.signature == null || consentData.signature == ''" />
				</form>
			</div>

			<div ng-if="consentData.completedConsent === true">
				<p>
					You may now upload your <b>Form 13614-C</b> and other documents below. 
					<b>Your documents need to be uploaded by {{uploadDeadlineStr}}.</b> 
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
					<p class="dcf-mt-2 dcf-txt-xs">Documents are transmitted and stored securely. These documents will not be shared with anyone except the Lincoln VITA volunteers helping to prepare your tax return and will be deleted at the end of tax season.</p>
				</fieldset>

				<!-- Ready button -->
				<fieldset class="dcf-mt-3">
					<legend>Finished Uploading Documents?</legend>
					<p>
						Your appointment is {{appointmentTimeStr}}.
						<b>Your documents need to be uploaded by {{uploadDeadlineStr}} (one week in advance).</b>
						You may return to this page to upload additional documents if necessary.
						Your site coordinator or return preparer will be in contact with you via email or phone.
					</p>
					<div class="dcf-input-checkbox">
						<input id="ready-checkbox" type="checkbox" ng-model="readyCheckbox.checked" value="false">
						<label for="ready-checkbox">I have uploaded <b>Form 13614-C</b> and all other necessary documents and my appointment is ready to be prepared</label>
					</div>
					<button type="button"
						class="dcf-btn dcf-btn-primary dcf-mt-1"
						ng-disabled="!readyCheckbox.checked || submittingAppointmentReady || appointmentMarkedAsReadySuccessfully"
						ng-click="markAppointmentAsReady()">Mark My Appointment as Ready</button>
				</fieldset>
				

				<!-- Relevant documents descriptions -->
				<div class="dcf-mt-6">
					<!-- Residential appointments -->
					<div ng-if="isResidentialAppointment">
						<h4>Required Form:</h4>
						<ul>
								<li><b><a href ng-click="downloadResidentIntakeForm()">Form 13614-C</a></b> (Intake Form for residents). Without this form, Lincoln VITA cannot prepare your return.</li>
								<ul>
									<li>The Spanish version of the Intake Form for residents can be found here: <b><a href ng-click="downloadResidentIntakeFormSpanish()">Form 13614-C (SP)</a></b></li>
								</ul>
								<b class="dcf-uppercase">Important Note</b>: If you are typing your information into the fillable form, you must "save as" to your device before uploading it or your information will not save.
						</ul>
						<h4>Relevant Documents to Upload:</h4>
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
						<h4>Relevant Documents to Upload:</h4>
						<h6>Required Forms:</h6>
						<ul>
							<b class="dcf-uppercase">Important Note</b>: If you are typing your information into the fillable form, you must "save as" to your device before uploading it or your information will not save.
							<li><b>Completed <a href ng-click="downloadForm14446()">Form 14446</a></b>. The <b>third page</b> (Part III: Taxpayer Consents) needs to be completed.</li>
							<li><b>Completed <a href ng-click="downloadNonResidentIntakeForm()">Form 13614-NR</a></b> (Intake Form for non-residents). Without this form, Lincoln VITA cannot prepare your return.</li>
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
