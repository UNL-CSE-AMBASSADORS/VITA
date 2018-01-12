define('appointmentPickerDataService', [], function($http) {

	function appointmentPickerDataService($http) {
		return {
			loadAllAppointments: function(year) {
				return $http.get(`/server/api/appointments/getTimes.php?action=getAppointments&year=${year}`).then(function(response){
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