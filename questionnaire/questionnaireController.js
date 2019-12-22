define('questionnaireController', [], function() {

	function questionnaireController($scope) {
		$scope.responses = [];

		$scope.submitQuestionnaireForm = function(e) {
			e.preventDefault();

			window.location.href = "/signup";

			return false;
		};
	};

	questionnaireController.$inject = ['$scope'];

	return questionnaireController;
});
