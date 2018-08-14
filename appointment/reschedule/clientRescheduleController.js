define('clientRescheduleController', [], function() {

	function clientRescheduleController($scope, ClientRescheduleDataService) {
		$scope.token = '';
		$scope.tokenExists = false;
		const EXPECTED_TOKEN_LENGTH = 32;

		$scope.clientData = {};
		$scope.validatingClientInformation = false;
		$scope.clientInformationValidated = false;


		$scope.doesTokenExist = function(token) {
			if (!token || 0 === token.length || EXPECTED_TOKEN_LENGTH !== token.length) {
				$scope.tokenExists = false;
				return;
			}

			ClientRescheduleDataService.doesTokenExist(token).then((result) => {
				if (result == null) {
					alert('There was an error loading appointment information. Please refresh and try again.');
					$scope.tokenExists = false;
				} else {
					$scope.tokenExists = result['exists'] || false;
				}
			});
		};

		// The token value isn't reflected in this controller until the DOM for the clientReschedule directive
		// is actually created, so we have to watch for the value change instead of simply invoking the method 
		$scope.$watch('token', (newValue, oldValue) => {
			$scope.doesTokenExist(newValue);
		});

		$scope.validateClientInformation = function() {
			if ($scope.validatingClientInformation || $scope.clientInformationValidated) {
				return;
			}
			$scope.validatingClientInformation = true;
			
			const token = $scope.token;
			const firstName = $scope.clientData.firstName;
			const lastName = $scope.clientData.lastName;
			const emailAddress = $scope.clientData.email;
			const phoneNumber = $scope.clientData.phone;

			ClientRescheduleDataService.validateClientInformation(token, firstName, lastName, emailAddress, phoneNumber).then((result) => {
				console.log(result);
			});

			$scope.validatingClientInformation = false;
		};
	}

	clientRescheduleController.$inject = ['$scope', 'clientRescheduleDataService'];

	return clientRescheduleController;

});
