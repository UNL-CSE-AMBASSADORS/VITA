define('clientRescheduleDataService', [], function($http) {

	function clientRescheduleDataService($http) {
		return {
			doesTokenExist: function(token) {
				return $http.get(`/server/appointment/reschedule/clientReschedule.php?action=doesTokenExist&token=${token}`).then(function(response){
					return response.data;
				},function(error) {
					return null;
				});
			},
			validateClientInformation: function(token, firstName, lastName, emailAddress, phoneNumber) {
				return $http({
					url: "/server/appointment/reschedule/clientReschedule.php",
					method: 'POST',
					data: 'action=validateClientInformation' +
						`&token=${token}` +
						`&firstName=${firstName}` +
						`&lastName=${lastName}` +
						`&emailAddress=${emailAddress}` + 
						`&phoneNumber=${phoneNumber}`,
					headers: {
						'Content-Type': "application/x-www-form-urlencoded"
					}
				}).then(function(response){
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