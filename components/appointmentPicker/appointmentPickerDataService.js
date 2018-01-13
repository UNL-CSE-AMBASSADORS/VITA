define('appointmentPickerDataService', [], function($http) {

	function appointmentPickerDataService($http) {
		return {
			loadAllAppointments: function(year, studentScholar = false) {
				return $http.get(`/server/api/appointments/getTimes.php?action=getAppointments&year=${year}&studentScholar=${studentScholar}`).then(function(response){
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