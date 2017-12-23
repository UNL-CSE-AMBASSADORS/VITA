queueApp.controller("QueuePrivateController", function($scope, $controller, QueueService) {
	angular.extend(this, $controller('QueueController', {$scope: $scope}));

	// We override the updateAppointmentInformation to update the volunteer select as well in the private queue on date changes
	let parentUpdateAppointmentInformation = $scope.updateAppointmentInformation;
	$scope.updateAppointmentInformation = function() {
		parentUpdateAppointmentInformation();
		$scope.getVolunteers();
	}

	function fixedEncodeURIComponent (str) {
  		return encodeURIComponent(str).replace(/[!'()]/g, escape).replace(/\*/g, "%2A");
	}

	$scope.selectClient = function(client) {
		$scope.client = client;
		for (let filingStatus of $scope.filingStatuses) {
			filingStatus.checked = false;
		}
	};

	$scope.checkIn = function() {
		$scope.client.checkedIn = true;
		QueueService.checkInNow(new Date().toISOString(), $scope.client.appointmentId);
	};

	$scope.pwFilledOut = function() {
		$scope.client.paperworkComplete = true;
		QueueService.turnInPapers(new Date().toISOString(), $scope.client.appointmentId);
	};

	$scope.nowPreparing = function() {
		$scope.client.preparing = true;
		QueueService.beginAppointment(new Date().toISOString(), $scope.client.appointmentId);
	};

	$scope.completeAppointment = function() {
		if ($scope.client.selectedVolunteer == null) {
			alert('You must select who prepared the taxes');
			return;
		}

		let selectedFilingStatuses = [];
		for (let filingStatus of $scope.filingStatuses) {
			if (filingStatus.checked) {
				selectedFilingStatuses.push(filingStatus.filingStatusId);
			}
		}

		$scope.client.ended = true;
		QueueService.finishAppointment(new Date().toISOString(), $scope.client.appointmentId, $scope.client.selectedVolunteer.userId, selectedFilingStatuses);
	};

	$scope.incompleteAppointment = function(explanation) {
		$scope.client.ended = true;
		let urlSafeExplanation = fixedEncodeURIComponent(explanation);
		QueueService.incompleteAppointment(urlSafeExplanation, $scope.client.appointmentId);
		$('textarea').val('');
	};

	$scope.cancelledAppointment = function() {
		$scope.client.ended = true;
		QueueService.cancelledAppointment($scope.client.appointmentId);
	};

	$scope.getVolunteers = function() {
		let year = $scope.currentDate.getFullYear(),
		month = $scope.currentDate.getMonth() + 1,
		day = $scope.currentDate.getDate();
		if (month < 10) month = "0" + month;

		if ($scope.selectedSite == null || $scope.selectedSite.siteId == null) return;
		let siteId = $scope.selectedSite.siteId;
		
		QueueService.getVolunteers(year + "-" + month + "-" + day, siteId).then(function(data) {
			if(data == null) {
				console.log('server error');
			} else if(data.length > 0) {
				$scope.volunteers = data.map((volunteer) => {
					volunteer.name = `${volunteer.firstName} ${volunteer.lastName}`;
					return volunteer;
				});
			} else {
				$scope.volunteers = [];
			}
		});
	}

	$scope.getFilingStatuses = function() {
		QueueService.getFilingStatuses().then(function(data) {
			if(data == null) {
				console.log('server error');
			} else if(data.length > 0) {
				$scope.filingStatuses = data;
			} else {
				$scope.filingStatuses = [];
			}
		});
	}

	// Invoke initially
	$scope.getVolunteers();
	$scope.getFilingStatuses();
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
