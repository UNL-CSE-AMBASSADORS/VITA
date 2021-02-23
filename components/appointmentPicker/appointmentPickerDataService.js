define('appointmentPickerDataService', [], function($http) {

	function appointmentPickerDataService($http) {
		return {
			loadAllAppointments: function(year, appointmentType = "residential") {
				// Is this able to call anything? I only see getAppointmentTimes in getTimes.php
				return $http.get(`/server/api/appointments/getTimes.php?action=getAppointments&year=${year}&appointmentType=${appointmentType}`).then(function(response){
					return response.data; 
				},function(error){
					return null;
				});
			}
		}
	}

	appointmentPickerDataService.$inject = ['$http'];

	return appointmentPickerDataService;
	
});