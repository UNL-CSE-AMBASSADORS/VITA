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
				// TODO: NEED TO URL ENCODE THE NOTE TEXT

				return $http({
					url: "/server/api/appointments/notes/notes.php",
					method: 'POST',
					data: `action=add&appointmentId=${appointmentId}&noteText=${noteText}`,
					headers: {
						'Content-Type': "application/x-www-form-urlencoded"
					}
				}).then(function(response){
					return response.data;
				},function(error){
					return null;
				});
			}
		}
	}

	appointmentNotesAreaDataService.$inject = ['$http'];

	return appointmentNotesAreaDataService;
	
});