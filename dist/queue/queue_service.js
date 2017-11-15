"use strict";

queueApp.factory("QueueService", function ($http) {
	return {
		getAppointments: function getAppointments(date) {
			return $http.get("/server/queue.php?displayDate=" + date).then(function (response) {
				return response.data;
			}, function (error) {
				return null;
			});
		}
	};
});