define('sitesDataService', [], function($http) {

	function sitesDataService($http) {
		return {
			getSites: function() {
				return $http.get('/server/api/sites/getAll.php?siteId=true&title=true').then(function(response){
					return response.data;
				},function(error) {
					return null;
				});
			},
			getSiteInformation: function(siteId) {
				return $http.get(`/server/management/sites/sites.php?action=getSiteInformation&siteId=${siteId}`).then(function(response){
					return response.data;
				},function(error) {
					return null;
				});
			},
			addShift: function(siteId, date, startTime, endTime) {
				return $http({
					url: "/server/management/sites/sites.php",
					method: 'POST',
					data: `action=addShift&siteId=${siteId}&date=${date}&startTime=${startTime}&endTime=${endTime}`,
					headers: {
						'Content-Type': "application/x-www-form-urlencoded"
					}
				}).then(function(response) {
					return response.data;
				},function(error) {
					return null;
				});
			},
			addAppointmentTime: function(siteId, date, scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, percentageAppointments, approximateLengthInMinutes) {
				return $http({
					url: '/server/management/sites/sites.php',
					method: 'POST',
					data: `action=addAppointmentTime&siteId=${siteId}&date=${date}&scheduledTime=${scheduledTime}&minimumNumberOfAppointments=${minimumNumberOfAppointments}&maximumNumberOfAppointments=${maximumNumberOfAppointments == null ? '' : maximumNumberOfAppointments}&percentageAppointments=${percentageAppointments}&approximateLengthInMinutes=${approximateLengthInMinutes}`,
					headers: {
						'Content-Type': "application/x-www-form-urlencoded"
					}
				}).then(function(response) {
					return response.data;
				},function(error) {
					return null;
				});
			}
		};
	};

	sitesDataService.$inject = ['$http'];

	return sitesDataService;
	
});