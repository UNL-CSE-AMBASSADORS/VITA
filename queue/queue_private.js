queueApp.controller("QueuePrivateController", function($scope, $controller, QueueService) {
	angular.extend(this, $controller('QueueController', {$scope: $scope}));

	function fixedEncodeURIComponent (str) {
  		return encodeURIComponent(str).replace(/[!'()]/g, escape).replace(/\*/g, "%2A");
	}

	$scope.selectClient = function(client) {
		$scope.client = client;
	};

	$scope.checkIn = function() {
		$scope.client.checkedIn = true;
		QueueService.checkInNow(new Date().toISOString(), $scope.client.appointmentId).then(function(result) {
			if(!result.success) {
				$scope.client.checkedIn = false;
				alert(result.error);
			}
		});
	};

	$scope.pwFilledOut = function() {
		$scope.client.paperworkComplete = true;
		QueueService.turnInPapers(new Date().toISOString(), $scope.client.appointmentId).then(function(result) {
			if(!result.success) {
				$scope.client.paperworkComplete = false;
				alert(result.error);
			}
		});
	};

	$scope.nowPreparing = function() {
		$scope.client.preparing = true;
		QueueService.beginAppointment(new Date().toISOString(), $scope.client.appointmentId).then(function(result) {
			if(!result.success) {
				$scope.client.preparing = false;
				alert(result.error);
			}
		});
	};

	$scope.completeAppointment = function() {
		if ($scope.client.selectedStationNumber == null) {
			alert('You must select which station the taxes were prepared at');
			return;
		}

		$scope.client.ended = true;
		QueueService.finishAppointment(new Date().toISOString(), $scope.client.appointmentId, $scope.client.selectedStationNumber).then(function(result) {
			if(!result.success) {
				$scope.client.ended = false;
				alert(result.error);
			}
		});
	};

	$scope.incompleteAppointment = function(explanation) {
		$scope.client.ended = true;
		let urlSafeExplanation = fixedEncodeURIComponent(explanation);
		QueueService.incompleteAppointment(urlSafeExplanation, $scope.client.appointmentId).then(function(result) {
			if(!result.success) {
				$scope.client.ended = false;
				alert(result.error);
			} else {
				$('textarea').val('');
			}
		});
	};

	$scope.cancelledAppointment = function() {
		$scope.client.ended = true;
		QueueService.cancelledAppointment($scope.client.appointmentId).then(function(result) {
			if(!result.success) {
				$scope.client.ended = false;
				alert(result.error);
			}
		});
	};

	$scope.stationNumbers = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25];
});

queueApp.filter('searchFor', function(){

	// All filters must return a function. The first parameter
	// is the data that is to be filtered, and the second is an
	// argument that may be passed with a colon (searchFor:searchString)

	return function(arr, searchString){

		if(!searchString){
			return arr;
		}

		var result = [];

		searchString = searchString.toLowerCase();

		// Using the forEach helper method to loop through the array
		angular.forEach(arr, function(item){

			if(item.name.toLowerCase().indexOf(searchString) !== -1 ||
				 item.appointmentId.toString().indexOf(searchString) !== -1 ||
				 ("#" + item.appointmentId.toString()).indexOf(searchString) !== -1){
				result.push(item);
			}

		});

		return result;
	};

});
