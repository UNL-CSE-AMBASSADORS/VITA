define('appointmentPickerDataService', [], function($http) {

	function appointmentPickerDataService($http) {
		return {
			loadAllAppointments: function(year, appointmentType = "residential", tenantName = "unl") {
				return $http.get(`/server/api/appointments/getTimes.php?action=getAppointments&year=${year}&appointmentType=${appointmentType}&tenantName=${tenantName}`).then(function(response){
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