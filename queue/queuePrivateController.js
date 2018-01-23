define('queuePrivateController', [], function() {

	function queuePrivateController($scope, $controller, QueueDataService) {
		angular.extend(this, $controller('queueController', {$scope: $scope}));
	
		function fixedEncodeURIComponent (str) {
			return encodeURIComponent(str).replace(/[!'()]/g, escape).replace(/\*/g, "%2A");
		}
	
		$scope.selectClient = function(client) {
			$scope.client = client;
			for (let filingStatus of $scope.filingStatuses) {
				filingStatus.checked = false;
			}
		};

		$scope.unselectClient = function() {
			$scope.client = null;
		}
	
		$scope.checkIn = function() {
			const noshow = $scope.client.noshow;
			$scope.client.checkedIn = true;
			$scope.client.noshow = false;
			QueueDataService.checkInNow(new Date().toISOString(), $scope.client.appointmentId).then(function(result) {
				if(!result.success) {
					$scope.client.checkedIn = false;
					$scope.client.noshow = noshow;
					alert(result.error);
				}
			});
		};
	
		$scope.pwFilledOut = function() {
			$scope.client.paperworkComplete = true;
			QueueDataService.turnInPapers(new Date().toISOString(), $scope.client.appointmentId).then(function(result) {
				if(!result.success) {
					$scope.client.paperworkComplete = false;
					alert(result.error);
				}
			});
		};
	
		$scope.nowPreparing = function() {
			$scope.client.preparing = true;
			QueueDataService.beginAppointment(new Date().toISOString(), $scope.client.appointmentId).then(function(result) {
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
	
			let selectedFilingStatuses = [];
			for (let filingStatus of $scope.filingStatuses) {
				if (filingStatus.checked) {
					selectedFilingStatuses.push(filingStatus.filingStatusId);
				}
			}

			$scope.client.ended = true;
			QueueDataService.finishAppointment(new Date().toISOString(), $scope.client.appointmentId, $scope.client.selectedStationNumber, selectedFilingStatuses).then(function(result) {
				if(!result.success) {
					$scope.client.ended = false;
					alert(result.error);
				}
				$scope.updateAppointmentInformation();
			});
		};
	
		$scope.incompleteAppointment = function(explanation) {
			$scope.client.ended = true;
			let urlSafeExplanation = fixedEncodeURIComponent(explanation);
			QueueDataService.incompleteAppointment(urlSafeExplanation, $scope.client.appointmentId).then(function(result) {
				if(!result.success) {
					$scope.client.ended = false;
					alert(result.error);
				} else {
					$scope.client.explanation = "";
				}
				$scope.updateAppointmentInformation();
			});
		};
	
		$scope.cancelledAppointment = function() {
			$scope.client.ended = true;
			QueueDataService.cancelledAppointment($scope.client.appointmentId).then(function(result) {
				if(!result.success) {
					$scope.client.ended = false;
					alert(result.error);
				}
				$scope.updateAppointmentInformation();
			});
		};

		$scope.getFilingStatuses = function() {
			QueueDataService.getFilingStatuses().then(function(data) {
				if(data == null) {
					alert('There was an error loading data from the server. Please refresh the page and try again in a few minutes.');
				} else if(data.length > 0) {
					$scope.filingStatuses = data;
				} else {
					$scope.filingStatuses = [];
				}
			});
		}
	
		$scope.stationNumbers = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25];

		// Invoke initially
 		$scope.getFilingStatuses();
	}

	queuePrivateController.$inject = ['$scope', '$controller', 'queueDataService'];

	return queuePrivateController;

});
