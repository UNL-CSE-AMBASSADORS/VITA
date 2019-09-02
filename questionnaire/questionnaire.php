<!-- Questions -->
<h2>Can VITA Help You?</h2>
<p><b>Please note:</b> The scope of work that can be done within a VITA site is defined by the IRS. If your return is considered “out of scope” for a site, our VITA Volunteers will not be able to prepare your return.</p>


<form class="cmxform"
	autocomplete="off"
	name="form"
	id="vitaQuestionnaireForm" 
	ng-submit="submitQuestionnaireForm($event)">

	<div class="form-radio">
		<p class="form-required dcf-mb-0">Will you require a Depreciation Schedule?</p>
		<div>
			<div class="dcf-btn-group" data-toggle="buttons">
				<label class="dcf-btn dcf-btn-secondary"
					name="1"
					ng-model="responses[1]" 
					uib-btn-radio="'1'"
					type="radio"
					required>Yes
				</label>
				<label class="dcf-btn dcf-btn-secondary"
					name="1"
					ng-model="responses[1]" 
					uib-btn-radio="'2'"
					type="radio"
					required>No
				</label>
			</div>
		</div>
		<p class="cant-help-text" ng-show="responses[1] == 1">Sorry, VITA can't prepare taxes requiring a depreciation schedule.</p>
	</div>

	<div class="form-radio">
		<p class="form-required dcf-mb-0">Will you require a Schedule F (Farm)?</p>
		<div>
			<div class="dcf-btn-group" data-toggle="buttons">
				<label class="dcf-btn dcf-btn-secondary"
					name="2"
					ng-model="responses[2]"
					uib-btn-radio="'1'"
					type="radio"
					required>Yes
				</label>
				<label class="dcf-btn dcf-btn-secondary"
					name="2"
					ng-model="responses[2]"
					uib-btn-radio="'2'"
					type="radio"
					required>No
				</label>
			</div>
		</div>
		<p class="cant-help-text" ng-show="responses[2] == 1">Sorry, VITA can't prepare taxes requiring a schedule F.</p>
	</div>

	<div class="form-radio">
		<p class="form-required dcf-mb-0">Are you self-employed or own a home-based business?</p>
		<div>
			<div class="dcf-btn-group" data-toggle="buttons">
				<label class="dcf-btn dcf-btn-secondary"
					name="3"
					ng-model="responses[3]"
					uib-btn-radio="'1'"
					type="radio"
					required>Yes
				</label>
				<label class="dcf-btn dcf-btn-secondary"
					name="3"
					ng-model="responses[3]"
					uib-btn-radio="'2'"
					type="radio"
					required>No
				</label>
			</div>
		</div>
	</div>

	<div class="form-radio" ng-show="responses[3] == 1">
		<p class="form-required dcf-mb-0">Does your home-based business or self-employment have a net loss?</p>
		<div>
			<div class="dcf-btn-group" data-toggle="buttons">
				<label class="dcf-btn dcf-btn-secondary"
					name="4"
					ng-model="responses[4]"
					uib-btn-radio="'1'"
					type="radio"
					required>Yes
				</label>
				<label class="dcf-btn dcf-btn-secondary"
					name="4"
					ng-model="responses[4]"
					uib-btn-radio="'2'"
					type="radio"
					required>No
				</label>
			</div>
		</div>
		<p class="cant-help-text" ng-show="responses[4] == 1">Sorry, VITA can't prepare taxes when the home-based business has a net loss.</p>
	</div>

	<div class="form-radio" ng-show="responses[3] == 1">
		<p class="form-required dcf-mb-0">Does your home-based business or self-employment have more than $10,000 in expenses?</p>
		<div>
			<div class="dcf-btn-group" data-toggle="buttons">
				<label class="dcf-btn dcf-btn-secondary"
					name="5"
					ng-model="responses[5]"
					uib-btn-radio="'1'"
					type="radio"
					required>Yes
				</label>
				<label class="dcf-btn dcf-btn-secondary"
					name="5"
					ng-model="responses[5]"
					uib-btn-radio="'2'"
					type="radio"
					required>No
				</label>
			</div>
		</div>
		<p class="cant-help-text" ng-show="responses[5] == 1">Sorry, VITA can't prepare taxes when the home-based business has more than $10,000 in expenses.</p>
	</div>

	<div class="form-radio" ng-show="responses[3] == 1">
		<p class="form-required dcf-mb-0">Does your home-based business or self-employment have self-employed, SEP, SIMPLE, or qualified retirement plans?</p>
		<div>
			<div class="dcf-btn-group" data-toggle="buttons">
				<label class="dcf-btn dcf-btn-secondary"
					name="6"
					ng-model="responses[6]"
					uib-btn-radio="'1'"
					type="radio"
					required>Yes
				</label>
				<label class="dcf-btn dcf-btn-secondary"
					name="6"
					ng-model="responses[6]"
					uib-btn-radio="'2'"
					type="radio"
					required>No
				</label>
			</div>
		</div>
		<p class="cant-help-text" ng-show="responses[6] == 1">Sorry, VITA can't prepare taxes when the home-based business has retirement plans.</p>
	</div>

	<div class="form-radio" ng-show="responses[3] == 1">
		<p class="form-required dcf-mb-0">Does your home-based business or self-employment have employees?</p>
		<div>
			<div class="dcf-btn-group" data-toggle="buttons">
				<label class="dcf-btn dcf-btn-secondary"
					name="7"
					ng-model="responses[7]"
					uib-btn-radio="'1'"
					type="radio"
					required>Yes
				</label>
				<label class="dcf-btn dcf-btn-secondary"
					name="7"
					ng-model="responses[7]"
					uib-btn-radio="'2'"
					type="radio"
					required>No
				</label>
			</div>
		</div>
		<p class="cant-help-text" ng-show="responses[7] == 1">Sorry, VITA can't prepare taxes when the home-based business has employees.</p>
	</div>

	<div class="form-radio">
		<p class="form-required dcf-mb-0">Will your return have casualty losses?</p>
		<div>
			<div class="dcf-btn-group" data-toggle="buttons">
				<label class="dcf-btn dcf-btn-secondary"
					name="8"
					ng-model="responses[8]"
					uib-btn-radio="'1'"
					type="radio"
					required>Yes
				</label>
				<label class="dcf-btn dcf-btn-secondary"
					name="8"
					ng-model="responses[8]"
					uib-btn-radio="'2'"
					type="radio"
					required>No
				</label>
			</div>
		</div>
		<p class="cant-help-text" ng-show="responses[8] == 1">Sorry, VITA can't prepare taxes which will have casualty losses.</p>
	</div>

	<div class="form-radio">
		<p class="form-required dcf-mb-0">Will your return have theft losses?</p>
		<div>
			<div class="dcf-btn-group" data-toggle="buttons">
				<label class="dcf-btn dcf-btn-secondary"
					name="9"
					ng-model="responses[9]"
					uib-btn-radio="'1'"
					type="radio"
					required>Yes
				</label>
				<label class="dcf-btn dcf-btn-secondary"
					name="9"
					ng-model="responses[9]"
					uib-btn-radio="'2'"
					type="radio"
					required>No
				</label>
			</div>
		</div>
		<p class="cant-help-text" ng-show="responses[9] == 1">Sorry, VITA can't prepare taxes which will have theft losses.</p>
	</div>

	<div class="form-radio">
		<p class="form-required dcf-mb-0">Will you require a Schedule E (rental income)?</p>
		<div>
			<div class="dcf-btn-group" data-toggle="buttons">
				<label class="dcf-btn dcf-btn-secondary"
					name="10"
					ng-model="responses[10]"
					uib-btn-radio="'1'"
					type="radio"
					required>Yes
				</label>
				<label class="dcf-btn dcf-btn-secondary"
					name="10"
					ng-model="responses[10]"
					uib-btn-radio="'2'"
					type="radio"
					required>No
				</label>
			</div>
		</div>
	</div>

	<div class="form-radio">
		<p class="form-required dcf-mb-0">Will you require a Schedule K-1 (partnership or trust income)?</p>
		<div>
			<div class="dcf-btn-group" data-toggle="buttons">
				<label class="dcf-btn dcf-btn-secondary"
					name="11"
					ng-model="responses[11]"
					uib-btn-radio="'1'"
					type="radio"
					required>Yes
				</label>
				<label class="dcf-btn dcf-btn-secondary"
					name="11"
					ng-model="responses[11]"
					uib-btn-radio="'2'"
					type="radio"
					required>No
				</label>
			</div>
		</div>
	</div>

	<div class="form-radio">
		<p class="form-required dcf-mb-0">Do you have income from dividends, capital gains, or minimal brokerage transactions?</p>
		<div>
			<div class="dcf-btn-group" data-toggle="buttons">
				<label class="dcf-btn dcf-btn-secondary"
					name="12"
					ng-model="responses[12]"
					uib-btn-radio="'1'"
					type="radio"
					required>Yes
				</label>
				<label class="dcf-btn dcf-btn-secondary"
					name="12"
					ng-model="responses[12]"
					uib-btn-radio="'2'"
					type="radio"
					required>No
				</label>
			</div>
		</div>
	</div>

	<div class="form-radio">
		<p class="form-required dcf-mb-0">Will your return involve a current bankruptcy?</p>
		<div>
			<div class="dcf-btn-group" data-toggle="buttons">
				<label class="dcf-btn dcf-btn-secondary"
					name="13"
					ng-model="responses[13]"
					uib-btn-radio="'1'"
					type="radio"
					required>Yes
				</label>
				<label class="dcf-btn dcf-btn-secondary"
					name="13"
					ng-model="responses[13]"
					uib-btn-radio="'2'"
					type="radio"
					required>No
				</label>
			</div>
		</div>
	</div>

	<div class="form-radio">
		<p class="form-required dcf-mb-0">Will your return involve income from more than one state?</p>
		<div>
			<div class="dcf-btn-group" data-toggle="buttons">
				<label class="dcf-btn dcf-btn-secondary"
					name="14"
					ng-model="responses[14]"
					uib-btn-radio="'1'"
					type="radio"
					required>Yes
				</label>
				<label class="dcf-btn dcf-btn-secondary"
					name="14"
					ng-model="responses[14]"
					uib-btn-radio="'2'"
					type="radio"
					required>No
				</label>
			</div>
		</div>
	</div>
	<br/>
	<input type="submit" value="Schedule An Appointment"/>
</form>
