define('analyticsDataService', [], function($http) {

	function analyticsDataService($http) {
		return {
			getAggregateAppointmentHistory: function() {
				return $http.get('/server/management/analytics/analytics.php?action=getAggregateAppointmentHistory').then((response) => {
					return response.data;
				}, (error) => {
					return null;
				});
			},
			getAppointmentCountsPerSiteHistory: function() {
				return $http.get('/server/management/analytics/analytics.php?action=getAppointmentCountsPerSiteHistory').then((response) => {
					return response.data;
				}, (error) => {
					return null;
				});
			}
		};
	};

	analyticsDataService.$inject = ['$http'];

	return analyticsDataService;
	
});