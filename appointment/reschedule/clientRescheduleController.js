define('clientRescheduleController', [], function() {

	function clientRescheduleController($scope, ClientRescheduleDataService) {
		$scope.token = '';
	}

	clientRescheduleController.$inject = ['$scope', 'clientRescheduleDataService'];

	return clientRescheduleController;

});
