define('appointmentPickerSharedPropertiesService', [], function() {

	function appointmentPickerSharedPropertiesService() {

		// https://stackoverflow.com/questions/12008908/angularjs-how-can-i-pass-variables-between-controllers

		var sharedProperties = {
			selectedDate: null,
			selectedSite: null,
			selectedTime: null
		};

		return {
			getSharedProperties: function () {
				return sharedProperties;
			}
		};

	}

	return appointmentPickerSharedPropertiesService;

});
