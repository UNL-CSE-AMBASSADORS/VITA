define('appointmentPickerDataService', [], function($http) {

	function appointmentPickerDataService($http) {
		return {
			loadAllAppointments: function(year, appointmentType = "residential") {
				return $http.get(`/server/api/appointments/getTimes.php?year=${year}&appointmentType=${appointmentType}`).then(function(response){
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