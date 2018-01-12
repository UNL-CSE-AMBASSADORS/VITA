<style>
#appointmentPicker {
	min-height: 300px;
}

td.ui-state-disabled.full {
	opacity: 0.65;
}

.px-1rem {
	padding-top: 1rem;
	padding-bottom: 1rem;
}

.ui-datepicker tbody .ui-state-disabled {
	border: transparent;
}
</style>

<div id="appointmentPicker">
	<div id="studentScholarAppointmentPicker" style="display:none">
		Student Scholar
	</div>
	<div id="datePicker" class="form-textfield">
		<input type="text" id="dateInput" name="dateInput" placeholder="Select a Date" required>
		<label class="form-label form-required form-label__always-floating">Date</label>
	</div>
	<div id="sitePicker" class="form-select" style="display: none;">
		<label class="form-label form-required" for="sitePickerSelect">Site</label>
		<select id="sitePickerSelect" name="sitePickerSelect" required></select>
		<div class="form-select__arrow"></div>
	</div>
	<div id="timePicker" class="form-select" style="display: none;">
		<label class="form-label form-required" for="timePickerSelect">Time</label>
		<select id="timePickerSelect" name="timePickerSelect" required></select>
		<div class="form-select__arrow"></div>
	</div>
</div>
