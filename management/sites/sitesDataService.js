define('sitesDataService', [], function($http) {

	function sitesDataService($http) {
		return {
			getSites: function() {
				return $http.get('/server/api/sites/getAll.php?siteId=true&title=true').then(function(response){
					return response.data;
				},function(error){
					return null;
				});
			},
			getSiteInformation: function(siteId) {
				return $http.get(`/server/management/sites/sites.php?action=getSiteInformation&siteId=${siteId}`).then(function(response){
					return response.data;
				},function(error){
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
				}).then(function(response){
					return response.data;
				},function(error){
					return null;
				});
			}
		}
	}

	sitesDataService.$inject = ['$http'];

	return sitesDataService;
	
});