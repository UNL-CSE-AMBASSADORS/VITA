define('queueDataService', [], function() {

	function queueDataService($http) {
		return {
			getAppointments: function(date, siteId){
				return $http.get(`/server/queue/queue.php?displayDate=${date}&siteId=${siteId}`).then(function(response){
					return response.data;
				},function(error){
					return null;
				});
			},
			getSites: function() {
				return $http.get('/server/api/sites/getAll.php?siteId=true&title=true').then(function(response){
					return response.data;
				}, function(error){
					return null;
				});
			},
			getFilingStatuses: function() {
				return $http.get('/server/api/filingStatuses/getAll.php?filingStatusId=true&text=true').then(function(response) {
					return response.data;
				},function(error) {
					return null;
				});
			},
			checkInNow: function(time, id) {
				return $http({
					url: "/server/queue/queue_priv.php",
					method: 'POST',
					data: `action=checkIn&time=${time}&id=${id}`,
					headers: {
						'Content-Type': "application/x-www-form-urlencoded"
					}
				}).then(function(response){
					return response.data;
				},function(error){
					return null;
				});
			},
			turnInPapers: function(time, id) {
				return $http({
					url: "/server/queue/queue_priv.php",
					method: 'POST',
					data: `action=completePaperwork&time=${time}&id=${id}`,
					headers: {
						'Content-Type': "application/x-www-form-urlencoded"
					}
				}).then(function(response){
					return response.data;
				},function(error){
					return null;
				});
			},
			beginAppointment: function(time, id) {
				return $http({
					url: "/server/queue/queue_priv.php",
					method: 'POST',
					data: `action=appointmentStart&time=${time}&id=${id}`,
					headers: {
						'Content-Type': "application/x-www-form-urlencoded"
					}
				}).then(function(response){
					return response.data;
				},function(error){
					return null;
				});
			},
			finishAppointment: function(time, id, stationNumber, filingStatusIds) {
				return $http({
					url: "/server/queue/queue_priv.php",
					method: 'POST',
					params: {
						"action": "appointmentComplete",
						"time": time,
						"id": id,
						"stationNumber": stationNumber,
						"filingStatusIds[]": filingStatusIds
					}
				}).then(function(response){
					return response.data;
				},function(error){
					return null;
				});
			},
			incompleteAppointment: function(explanation, id) {
				return $http({
					url: "/server/queue/queue_priv.php",
					method: 'POST',
					data: `action=appointmentIncomplete&explanation=${explanation}&id=${id}`,
					headers: {
						'Content-Type': "application/x-www-form-urlencoded"
					}
				}).then(function(response){
					return response.data;
				},function(error){
					return null;
				});
			},
			cancelledAppointment: function(id) {
				return $http({
					url: "/server/queue/queue_priv.php",
					method: 'POST',
					data: `action=cancelledAppointment&id=${id}`,
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

	queueDataService.$inject = ['$http'];

	return queueDataService;

});
