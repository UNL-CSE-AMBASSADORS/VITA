queueApp.factory("QueueService", function($http){
	//$httpProvider.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded"; // Sends data in PHP-friendly format
	return {
		getAppointments: function(date){
			return $http.get("/server/queue.php?displayDate=" + date).then(function(response){
				return response.data;
			},function(error){
				return null;
			});
		},
		// TODO: Add functions
		checkInNow: function(time, id) {
			// Puts time into DB on click
			return $http({
				url: "/server/queue_priv.php",
				method: 'POST',
				data: 'action=checkIn&time=' + time + '&id=' + id,
				headers: {
					'Content-Type': "application/x-www-form-urlencoded"
				}
			}).then(function(response){
				return response.data;
			},function(error){
				return null;
			});
		},
		completePaperwork: function(time) {

		}
	}
})
