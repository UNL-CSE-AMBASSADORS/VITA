define('appointmentsDataService', [], function($http) {

	function appointmentsDataService($http) {
		return {
			getAppointments: function(year) {
				return $http.get(`/server/management/appointments/appointments.php?action=getAppointments&year=${year}`).then(function(response){
					return response.data;
				},function(error){
					return null;
				});
			},
			rescheduleAppointment: function(appointmentId, scheduledTime, siteId) {
				return $http({
					url: "/server/management/appointments/appointments.php",
					method: 'POST',
					data: `action=reschedule&id=${appointmentId}&scheduledTime=${scheduledTime}&siteId=${siteId}`,
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

	appointmentsDataService.$inject = ['$http'];

	return appointmentsDataService;
	
});