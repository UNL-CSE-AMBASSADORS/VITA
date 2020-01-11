define('analyticsDataService', [], function($http) {

	function analyticsDataService($http) {
		return {
			getSites: function() {
				return $http.get('/server/api/sites/getAll.php?siteId=true&title=true').then(function(response){
					return response.data;
				},function(error) {
					return null;
				});
			},
		};
	};

	analyticsDataService.$inject = ['$http'];

	return analyticsDataService;
	
});