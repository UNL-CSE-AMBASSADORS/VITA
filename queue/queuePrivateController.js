define('queuePrivateController', [], function() {

	function queuePrivateController($scope, $controller, QueueDataService) {
		angular.extend(this, $controller('queueController', {$scope: $scope}));
	
		function fixedEncodeURIComponent (str) {
			return encodeURIComponent(str).replace(/[!'()]/g, escape).replace(/\*/g, "%2A");
		}
	
		$scope.selectClient = function(client) {
			$scope.client = client;
		};

		$scope.unselectClient = function() {
			$scope.client = null;
		}
	
		$scope.checkIn = function() {
			$scope.client.checkedIn = true;
			QueueDataService.checkInNow(new Date().toISOString(), $scope.client.appointmentId);
		};
	
		$scope.pwFilledOut = function() {
			$scope.client.paperworkComplete = true;
			QueueDataService.turnInPapers(new Date().toISOString(), $scope.client.appointmentId);
		};
	
		$scope.nowPreparing = function() {
			$scope.client.preparing = true;
			QueueDataService.beginAppointment(new Date().toISOString(), $scope.client.appointmentId);
		};
	
		$scope.completeAppointment = function() {
			if ($scope.client.selectedStationNumber == null) {
				alert('You must select which station the taxes were prepared at');
				return;
			}
	
			$scope.client.ended = true;
			QueueDataService.finishAppointment(new Date().toISOString(), $scope.client.appointmentId, $scope.client.selectedStationNumber);
		};
	
		$scope.incompleteAppointment = function(explanation) {
			$scope.client.ended = true;
			let urlSafeExplanation = fixedEncodeURIComponent(explanation);
			QueueDataService.incompleteAppointment(urlSafeExplanation, $scope.client.appointmentId);
			$scope.client.explanation = "";
		};
	
		$scope.cancelledAppointment = function() {
			$scope.client.ended = true;
			QueueDataService.cancelledAppointment($scope.client.appointmentId);
		};
	
		$scope.stationNumbers = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25];
	}

	queuePrivateController.$inject = ['$scope', '$controller', 'queueDataService'];

	return queuePrivateController;

});
