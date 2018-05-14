define('appointmentNotesAreaSharedPropertiesService', [], function() {

	function appointmentNotesAreaSharedPropertiesService() {

		// https://stackoverflow.com/questions/12008908/angularjs-how-can-i-pass-variables-between-controllers

		var sharedProperties = {
			note: null
		};

		return {
			getSharedProperties: function () {
				return sharedProperties;
			}
		};

	}

	return appointmentNotesAreaSharedPropertiesService;

});
