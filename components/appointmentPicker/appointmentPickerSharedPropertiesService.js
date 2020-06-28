define('appointmentPickerSharedPropertiesService', [], function() {

	function appointmentPickerSharedPropertiesService() {

		// https://stackoverflow.com/questions/12008908/angularjs-how-can-i-pass-variables-between-controllers

		let sharedProperties = {
			selectedDate: null,
			selectedSite: null,
			selectedSiteTitle: null,
			isSelectedSiteVirtual: false,
			selectedTime: null,
			selectedAppointmentTimeId: null,
			hasAvailability: null,
			isLoggedIn: false,
			appointmentType: 'residential',
			tenantName: 'unl'
		};

		return {
			getSharedProperties: function () {
				return sharedProperties;
			}
		};

	}

	return appointmentPickerSharedPropertiesService;

});
