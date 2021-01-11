define('appointmentsDataService', [], function($http) {

	function appointmentsDataService($http) {
		return {
			getAppointments: (year) => {
				return $http.get(`/server/management/appointments/appointments.php?action=getAppointments&year=${year}`).then(function(response){
					return response.data;
				},function(error){
					return null;
				});
			},
			getAppointmentTypes: () => {
				return $http.get('/server/api/appointments/types/getAll.php').then((response) => {
					return response.data;
				}, (error) => {
					return null;
				});
			},
			rescheduleAppointment: (appointmentId, appointmentTimeId) => {
				return $http({
					url: "/server/management/appointments/appointments.php",
					method: 'POST',
					data: `action=reschedule&id=${appointmentId}&appointmentTimeId=${appointmentTimeId}`,
					headers: {
						'Content-Type': "application/x-www-form-urlencoded"
					}
				}).then(function(response){
					return response.data;
				},function(error){
					return null;
				});
			},
			cancelAppointment: (appointmentId) => {
				return $http({
					url: "/server/management/appointments/appointments.php",
					method: 'POST',
					data: `action=cancel&id=${appointmentId}`,
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