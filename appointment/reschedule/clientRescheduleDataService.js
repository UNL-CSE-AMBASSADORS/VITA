define('clientRescheduleDataService', [], function($http) {

	function clientRescheduleDataService($http) {
		return {
			doesTokenExist: function(token) {
				return $http.get(`/server/appointment/reschedule/clientReschedule.php?action=doesTokenExist&token=${token}`).then(function(response){
					return response.data;
				},function(error){
					return null;
				});
			}
		};
	}

	clientRescheduleDataService.$inject = ['$http'];

	return clientRescheduleDataService;
});