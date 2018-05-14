define('appointmentNotesAreaDataService', [], function($http) {

	function appointmentNotesAreaDataService($http) {
		return {
			getNotes: function(appointmentId) {
				// TODO: NEED TO DO THIS PROPERLY
				return {
					notes: []
				};

				/*
				return $http.get(`/server/api/appointments/getTimes.php?action=getAppointments&year=${year}&studentScholar=${studentScholar}`).then(function(response){
					return response.data;
				},function(error){
					return null;
				});
				*/
			},
			addNote: function(appointmentId, noteText) {
				/*
				return $http.get(`/server/api/appointments/getTimes.php?action=getAppointments&year=${year}&studentScholar=${studentScholar}`).then(function(response){
					return response.data;
				},function(error){
					return null;
				});
				*/
			}
		}
	}

	appointmentNotesAreaDataService.$inject = ['$http'];

	return appointmentNotesAreaDataService;
	
});