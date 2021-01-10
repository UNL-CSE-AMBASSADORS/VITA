<style>
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
	<!-- TODO: Is there a way to not have this date hard-coded? -->
	<div ng-show="!isResidentialAppointmentType()"><b>International tax appointments begin March 3rd.</b></div>

	<div ng-show="appointmentPickerSharedProperties.hasAvailability == false">Sorry! There are currently no remaining appointments available.</div>
	<div id="datePicker" 
		class="form-textfield" 
		ng-show="appointmentPickerSharedProperties.hasAvailability == true">
		<label class="form-label form-required">Date</label>
		<input type="text" 
			id="dateInput" 
			class="dcf-input-text"
			name="dateInput" 
			placeholder=" -- Select a Date -- " 
			ng-model="appointmentPickerSharedProperties.selectedDate"
			autocomplete="off"
			required>
		<div ng-show="form.$submitted || form.dateInput.$touched">
			<label class="error" ng-show="form.dateInput.$error.required">This field is required.</label>
		</div>
	</div>
	<div id="sitePicker" 
		class="form-select" 
		ng-show="appointmentPickerSharedProperties.selectedDate != null">
		<label class="form-label form-required" for="sitePickerSelect">Site</label>
		<select id="sitePickerSelect" 
			class="dcf-input-select dcf-mb-0"
			name="sitePickerSelect" 
			ng-model="appointmentPickerSharedProperties.selectedSite" 
			ng-change="siteChanged(appointmentPickerSharedProperties.selectedSite)" 
			ng-options="key as site.siteTitle for (key, site) in sites track by key" 
			required>
			<option value="" style="display:none;">-- Select a Site --</option>
		</select>
		<div ng-show="form.$submitted || form.sitePickerSelect.$touched">
			<label class="error" ng-show="form.sitePickerSelect.$error.required">This field is required.</label>
		</div>
	</div>
	<div id="timePicker" 
		class="form-select" 
		ng-show="appointmentPickerSharedProperties.selectedDate != null && appointmentPickerSharedProperties.selectedSite != null">
		<label class="form-label form-required" for="timePickerSelect">Time</label>
		<select id="timePickerSelect" 
			class="dcf-input-select dcf-mb-0"
			name="timePickerSelect" 
			ng-options="time as getTimeText(time, info) disable when (info.appointmentsAvailable <= 0 && appointmentPickerSharedProperties.isLoggedIn != true) for (time, info) in times track by time" 
			ng-model="appointmentPickerSharedProperties.selectedTime"
			ng-change="timeChanged(appointmentPickerSharedProperties.selectedTime)"
			required>
			<option value="" style="display:none;">-- Select a Time --</option>
		</select>
		<div ng-show="form.$submitted || form.timePickerSelect.$touched">
			<label class="error" ng-show="form.timePickerSelect.$error.required">This field is required.</label>
		</div>
	</div>
</div>
