define('queueDataService', [], ($http) => {

	function queueDataService($http) {
		return {
			getSites: () => {
				return $http.get('/server/api/sites/getAll.php?siteId=true&title=true').then((response) => {
					return response.data;
				}, function(error){
					return null;
				});
			},
			getAppointments: (date, siteId) => {
				return $http.get(`/server/queue/new/queue.php?action=getAppointments&date=${date}&siteId=${siteId}`).then((response) => {
					return response.data;
				}, (error) => {
					return null;
				});
			},
			markAppointmentAsAwaiting: (appointmentId) => {
				return $http({
					url: '/server/queue/new/queue.php',
					method: 'POST',
					data: `action=awaiting&appointmentId=${appointmentId}`,
					headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
				}).then((response) => response.data, (error) => null);
			},
			markAppointmentAsCheckedIn: (appointmentId) => {
				return $http({
					url: '/server/queue/new/queue.php',
					method: 'POST',
					data: `action=checkIn&appointmentId=${appointmentId}`,
					headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
				}).then((response) => response.data, (error) => null);
			},
			markAppointmentAsPaperworkCompleted: (appointmentId) => {
				return $http({
					url: '/server/queue/new/queue.php',
					method: 'POST',
					data: `action=paperworkCompleted&appointmentId=${appointmentId}`,
					headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
				}).then((response) => response.data, (error) => null);
			},
			markAppointmentAsBeingPrepared: (appointmentId) => {
				return $http({
					url: '/server/queue/new/queue.php',
					method: 'POST',
					data: `action=startAppointment&appointmentId=${appointmentId}`,
					headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
				}).then((response) => response.data, (error) => null);
			},
			markAppointmentAsCompleted: (appointmentId) => {
				return $http({
					url: '/server/queue/new/queue.php',
					method: 'POST',
					data: `action=completeAppointment&appointmentId=${appointmentId}`,
					headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
				}).then((response) => response.data, (error) => null);
			}
		};
	}

	queueDataService.$inject = ['$http'];

	return queueDataService;
});