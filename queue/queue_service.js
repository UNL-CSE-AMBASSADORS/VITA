queueApp.factory("QueueService", function($http){
	return {
		getAppointments: function(date){
			return $http.get("/server/queue.php?displayDate=" + date).then(function(response){
				return response.data;
			},function(error){
				return null;
			});
		},
		checkInNow: function(time, id) {
			return $http({
				url: "/server/queue_priv.php",
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
				url: "/server/queue_priv.php",
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
				url: "/server/queue_priv.php",
				method: 'POST',
				data: `action=appointmentStart&time=${$time}&id=${id}`,
				headers: {
					'Content-Type': "application/x-www-form-urlencoded"
				}
			}).then(function(response){
				return response.data;
			},function(error){
				return null;
			});
		},
		finishAppointment: function(time, id) {
			return $http({
				url: "/server/queue_priv.php",
				method: 'POST',
				data: `action=appointmentComplete&time=${time}&id=${id}`,
				headers: {
					'Content-Type': "application/x-www-form-urlencoded"
				}
			}).then(function(response){
				return response.data;
			},function(error){
				return null;
			});
		},
		incompleteAppointment: function(explanation, id) {
			return $http({
				url: "/server/queue_priv.php",
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
		}
	}
})
