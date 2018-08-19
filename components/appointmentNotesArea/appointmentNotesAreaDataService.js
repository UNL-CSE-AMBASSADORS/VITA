define('appointmentNotesAreaDataService', [], function($http) {

	function appointmentNotesAreaDataService($http) {
		return {
			getNotesForAppointment: function(appointmentId) {
				return $http.get(`/server/api/appointments/notes/notes.php?action=getForAppointment&appointmentId=${appointmentId}`).then(function(response) {
					return response.data;
				}, function(error) {
					return null;
				});
			},
			addNote: function(appointmentId, noteText) {
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