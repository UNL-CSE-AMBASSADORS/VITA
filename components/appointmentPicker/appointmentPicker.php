<style>
#appointmentPicker {
	padding-bottom: 30px
}

div#ui-datepicker-div {
	z-index: 50 !important;
}

td.ui-state-disabled.full, 
td.ui-state-disabled.full .ui-state-default {
	background: transparent;
}

td.ui-state-disabled.full .ui-state-default {
	box-shadow: 0 0 0px 5px #fff inset;
}

td.available .ui-state-default {
	box-shadow: 0 0 0px 5px #d5d5d2 inset;
}

.px-1rem {
	padding-top: 1rem;
	padding-bottom: 1rem;
}
</style>

<div id="appointmentPicker" ng-cloak>
	<div ng-show="sharedProperties.studentScholar == true">International Student Scholar</div>
	<div ng-show="sharedProperties.hasAvailability == false">Sorry! There are currently no remaining appointments available.</div>
	<div id="datePicker" 
		class="form-textfield" 
		ng-show="sharedProperties.hasAvailability == true">
		<label class="form-label form-required">Date</label>
		<input type="text" 
			id="dateInput" 
			name="dateInput" 
			placeholder=" -- Select a Date -- " 
			ng-model="sharedProperties.selectedDate"
			required>
		<div ng-show="form.$submitted || form.dateInput.$touched">
			<label class="error" ng-show="form.dateInput.$error.required">This field is required.</label>
		</div>
	</div>
	<div id="sitePicker" 
		class="form-select" 
		ng-show="sharedProperties.selectedDate != null">
		<label class="form-label form-required" for="sitePickerSelect">Site</label>
		<select id="sitePickerSelect" 
			name="sitePickerSelect" 
			ng-model="sharedProperties.selectedSite" 
			ng-change="siteChanged(sharedProperties.selectedSite)" 
			ng-options="key as site.site_title for (key, site) in sites track by key" 
			required>
			<option value="" style="display:none;">-- Select a Site --</option>
		</select>
		<div ng-show="form.$submitted || form.sitePickerSelect.$touched">
			<label class="error" ng-show="form.sitePickerSelect.$error.required">This field is required.</label>
		</div>
	</div>
	<div id="timePicker" 
		class="form-select" 
		ng-show="sharedProperties.selectedDate != null && sharedProperties.selectedSite != null">
		<label class="form-label form-required" for="timePickerSelect">Time</label>
		<select id="timePickerSelect" 
			name="timePickerSelect" 
			ng-options="time as (remaining <= 0) ? (time + ' - FULL') : time disable when (remaining <= 0) for (time, remaining) in times track by time" 
			ng-model="sharedProperties.selectedTime"
			required>
			<option value="" style="display:none;">-- Select a Time --</option>
		</select>
		<div ng-show="form.$submitted || form.timePickerSelect.$touched">
			<label class="error" ng-show="form.timePickerSelect.$error.required">This field is required.</label>
		</div>
	</div>
</div>
