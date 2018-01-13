define('signupDataService', [], function() {

	function signupDataService($http, $httpParamSerializerJQLike) {
		return {
			storeAppointments: function(data){

				console.log("post");
				console.log(data);

				// The code for the transformRequest came from this post:
				// https://stackoverflow.com/a/24964658
				return $http({
					url: "/server/storeAppointment.php",
					method: 'POST',
					data: data,
					transformRequest: $httpParamSerializerJQLike,
					headers: {
						'Content-Type': "application/x-www-form-urlencoded"
					},
				}).then(function(response){
					return response.data;
				},function(error){
					return null;
				});
			}
		}
	}

	signupDataService.$inject = ['$http', '$httpParamSerializerJQLike'];

	return signupDataService;

});
