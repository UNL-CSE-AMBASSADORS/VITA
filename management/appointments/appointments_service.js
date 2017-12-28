appointmentsApp.factory("AppointmentsService", function($http){
	return {
		getAppointments: function(year) {
			return $http.get(`/server/management/appointments/appointments.php?action=getAppointments&year=${year}`).then(function(response){
				return response.data;
			},function(error){
				return null;
			});	
		}
	}
});