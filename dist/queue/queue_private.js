'use strict';

queueApp.controller("QueuePrivateController", function ($scope, $controller, QueueService) {
	angular.extend(this, $controller('QueueController', { $scope: $scope }));

	$scope.selectClient = function (client) {
		$scope.client = client;
	};
});

queueApp.filter('searchFor', function () {

	// All filters must return a function. The first parameter
	// is the data that is to be filtered, and the second is an
	// argument that may be passed with a colon (searchFor:searchString)

	return function (arr, searchString) {

		if (!searchString) {
			return arr;
		}

		var result = [];

		searchString = searchString.toLowerCase();

		// Using the forEach helper method to loop through the array
		angular.forEach(arr, function (item) {

			if (item.name.toLowerCase().indexOf(searchString) !== -1 || item.appointmentId.toString().indexOf(searchString) !== -1 || ("#" + item.appointmentId.toString()).indexOf(searchString) !== -1) {
				result.push(item);
			}
		});

		return result;
	};
});