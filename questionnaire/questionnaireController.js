define('questionnaireController', [], function() {

	function questionnaireController($scope) {
		$scope.responses = [];

		$scope.submitQuestionnaireForm = function(e) {
			e.preventDefault();

			window.location.href = "/signup";

			return false;
		};

		$scope.appointmentIsInScope = function() {			
			const outOfScope = $scope.responses[1] == 1
							|| $scope.responses[2] == 1
							|| $scope.responses[4] == 1
							|| $scope.responses[5] == 1
							|| $scope.responses[6] == 1
							|| $scope.responses[7] == 1
							|| $scope.responses[8] == 1
							|| $scope.responses[9] == 1
			
			return !outOfScope;
		}
	};

	questionnaireController.$inject = ['$scope'];

	return questionnaireController;
});
