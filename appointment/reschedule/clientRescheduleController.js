define('clientRescheduleController', [], function() {

	function clientRescheduleController($scope, ClientRescheduleDataService) {
		$scope.token = '';
		$scope.tokenExists = false;

		const EXPECTED_TOKEN_LENGTH = 32;

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
	}

	clientRescheduleController.$inject = ['$scope', 'clientRescheduleDataService'];

	return clientRescheduleController;

});
