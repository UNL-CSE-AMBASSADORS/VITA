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
			getProgressionSteps: (date, siteId) => {
				return $http.get(`/server/queue/queue.php?action=getProgressionSteps&date=${date}&siteId=${siteId}`).then((response) => {
					return response.data;
				}, (error) => {
					return null;
				});
			},
			deleteTimestamp: (appointmentId, stepId) => {
				return $http({
					url: '/server/queue/queue.php',
					method: 'POST',
					data: `action=deleteTimestamp&appointmentId=${appointmentId}&stepId=${stepId}`,
					headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
				}).then((response) => response.data, (error) => null);
			},
			insertStepTimestamp: (appointmentId, stepId, setTimeStampToNull) => {
				return $http({
					url: '/server/queue/queue.php',
					method: 'POST',
					data: `action=insertStepTimestamp&appointmentId=${appointmentId}&stepId=${stepId}&setTimeStampToNull=${setTimeStampToNull}`,
					headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
				}).then((response) => response.data, (error) => null);
			},
			insertSubstepTimestamp: (appointmentId, substepId) => {
				return $http({
					url: '/server/queue/queue.php',
					method: 'POST',
					data: `action=insertSubstepTimestamp&appointmentId=${appointmentId}&substepId=${substepId}`,
					headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
				}).then((response) => response.data, (error) => null);
			},
			markAppointmentAsCompleted: (appointmentId) => {
				return $http({
					url: '/server/queue/queue.php',
					method: 'POST',
					data: `action=completeAppointment&appointmentId=${appointmentId}`,
					headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
				}).then((response) => response.data, (error) => null);
			},
			markAppointmentAsIncomplete: (appointmentId) => {
				return $http({
					url: '/server/queue/queue.php',
					method: 'POST',
					data: `action=incompleteAppointment&appointmentId=${appointmentId}`,
					headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
				}).then((response) => response.data, (error) => null);
			},
			markAppointmentAsCancelled: (appointmentId) => {
				return $http({
					url: '/server/queue/queue.php',
					method: 'POST',
					data: `action=cancelAppointment&appointmentId=${appointmentId}`,
					headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
				}).then((response) => response.data, (error) => null);
			},
			getPossibleSwimlanes: () => {
				return $http({
					url: '/server/queue/queue.php',
					method: 'POST',
					data: `action=getPossibleSwimlanes`,
					headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
				}).then((response) => response.data, (error) => null);
			},
			getPossibleSubsteps: () => {
				return $http({
					url: '/server/queue/queue.php',
					method: 'POST',
					data: `action=getPossibleSubsteps`,
					headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
				}).then((response) => response.data, (error) => null);
			}
		};
	}

	queueDataService.$inject = ['$http'];

	return queueDataService;
});