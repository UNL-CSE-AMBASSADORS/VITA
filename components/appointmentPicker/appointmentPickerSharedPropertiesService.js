define('appointmentPickerSharedPropertiesService', [], function() {

	function appointmentPickerSharedPropertiesService() {

		// https://stackoverflow.com/questions/12008908/angularjs-how-can-i-pass-variables-between-controllers

		let sharedProperties = {
			selectedDate: null,
			selectedSite: null,
			selectedSiteTitle: null,
			selectedTime: null,
			selectedAppointmentTimeId: null,
			hasAvailability: null,
			isLoggedIn: false,
			appointmentType: 'residential'
		};

		return {
			getSharedProperties: function () {
				return sharedProperties;
			}
		};

	}

	return appointmentPickerSharedPropertiesService;

});
