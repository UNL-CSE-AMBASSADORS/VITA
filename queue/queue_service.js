queueApp.factory("QueueService", function($http){
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
		checkInNow: function(time, id) {
			return $http({
				url: "/server/queue/queue_priv.php",
				method: 'POST',
				params: {
					"action": "checkIn",
					"time": time,
					"id": id
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
				params: {
					"action": "completePaperwork",
					"time": time,
					"id": id
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
				params: {
					"action": "appointmentStart",
					"time": time,
					"id": id
				}
			}).then(function(response){
				return response.data;
			},function(error){
				return null;
			});
		},
		finishAppointment: function(time, id, stationNumber) {
			return $http({
				url: "/server/queue/queue_priv.php",
				method: 'POST',
				params: {
					"action": "appointmentComplete",
					"time": time,
					"id": id,
					"stationNumber": stationNumber
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
				params: {
					"action": "appointmentIncomplete",
					"explanation": explanation,
					"id": id
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
				params: {
					"action": "cancelledAppointment",
					"id": id
				}
			}).then(function(response){
				return response.data;
			},function(error){
				return null;
			});
		}
	}
})
