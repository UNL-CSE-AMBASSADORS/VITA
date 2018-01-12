define('signupDataService', [], function() {

	function signupDataService($http) {
		return {
			storeAppointments: function(data){
				return $http({
					url: "/server/storeAppointment.php",
					method: 'POST',
					data: data,
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

	signupDataService.$inject = ['$http'];

	return signupDataService;

});
