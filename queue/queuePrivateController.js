define('queuePrivateController', [], function() {

	function queuePrivateController($scope, $controller, QueueDataService, AppointmentNotesAreaSharedPropertiesService) {
		angular.extend(this, $controller('queueController', {$scope: $scope}));
	
		$scope.appointmentNotesAreaSharedProperties = AppointmentNotesAreaSharedPropertiesService.getSharedProperties();
	
		$scope.selectClient = function(client) {
			$scope.client = client;
			$scope.appointmentNotesAreaSharedProperties.appointmentId = $scope.client.appointmentId;
			document.body.scrollTop = document.documentElement.scrollTop = 0;
		};

		$scope.unselectClient = function() {
			$scope.client = null;
		}
	
		$scope.checkIn = function() {
			const noShow = $scope.client.noShow;
			$scope.client.checkedIn = true;
			$scope.client.noShow = false;
			QueueDataService.checkInNow(new Date().toISOString(), $scope.client.appointmentId).then(function(result) {
				if(!result.success) {
					$scope.client.checkedIn = false;
					$scope.client.noShow = noShow;
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
			$scope.client.ended = true;
			QueueDataService.finishAppointment(new Date().toISOString(), $scope.client.appointmentId).then(function(result) {
				if(!result.success) {
					$scope.client.ended = false;
					alert(result.error);
				}
				$scope.updateAppointmentInformation();
			});
		};
	
		$scope.incompleteAppointment = function() {
			$scope.client.ended = true;
			QueueDataService.incompleteAppointment($scope.client.appointmentId).then(function(result) {
				if(!result.success) {
					$scope.client.ended = false;
					alert(result.error);
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
	}

	queuePrivateController.$inject = ['$scope', '$controller', 'queueDataService', 'appointmentNotesAreaSharedPropertiesService'];

	return queuePrivateController;

});
