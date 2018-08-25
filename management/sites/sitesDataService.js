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
			}
		}
	}

	sitesDataService.$inject = ['$http'];

	return sitesDataService;
	
});