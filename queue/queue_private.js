queueApp.controller("QueuePrivateController", function($scope, $controller, QueueService) {
	$scope.test = "world";
	angular.extend(this, $controller('QueueController', {$scope: $scope}));
});
