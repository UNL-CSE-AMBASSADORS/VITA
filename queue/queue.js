var queueApp = angular.module("queueApp", ["ngMaterial", "ngMessages"])

.controller("QueueController", function($scope, $interval, QueueService) {
	$scope.today = new Date();
	$scope.currentDate = $scope.today;
	$scope.selectedSite = -1;

	// Load the appointment info every 10 seconds
	$scope.updateAppointmentInformation = function() {
		let year = $scope.currentDate.getFullYear(),
			month = $scope.currentDate.getMonth() + 1,
			day = $scope.currentDate.getDate();
		if (month < 10) month = "0" + month;

		if ($scope.selectedSite == null || $scope.selectedSite.siteId == null) return;
		let siteId = $scope.selectedSite.siteId;
		
		QueueService.getAppointments(year + "-" + month + "-" + day, siteId).then(function(data) {
			if(data == null) {
				alert('There was an error loading the queue. Please try refreshing the page.');
			} else if(data.length > 0) {
				$scope.appointments = data.map((appointment) => {
					// We force the time into CST
					appointment.scheduledTime = new Date(appointment.scheduledTime + ' CST');
					appointment.checkedIn = appointment.timeIn != null;
					appointment.paperworkComplete = appointment.timeReturnedPapers != null;
					appointment.preparing = appointment.timeAppointmentStarted != null;
					appointment.ended = appointment.timeAppointmentEnded != null;
					appointment.name = appointment.firstName + " " + appointment.lastName;
					return appointment;
				});
			} else {
				$scope.appointments = [];
			}
		});
	}

	$scope.getSites = function() {
		QueueService.getSites().then(function(data) {
			if(data == null) {
				alert('There was an error loading the queue. Please try refreshing the page.');
			} else if (data.length > 0) {
				$scope.sites = data;
			} else {
				$scope.sites = [];
				$scope.selectedSite = -1;
			}
		});
	}

	// Refresh the clock
	let refreshClockContent = function(){
		$scope.updateTime = new Date();
		$scope.isAm = $scope.updateTime.getHours() < 12;
	}

	// Create interval to update clock every second
	var clockInterval = $interval(function(){
		refreshClockContent();
	}.bind(this), 1000);

	// Create interval to update appointment information every 60 seconds
	var appointmentInterval = $interval(function(){
		$scope.updateAppointmentInformation();
	}.bind(this), 60000);

	// Destroy the intervals when we leave this page
	$scope.$on('$destroy', function () {
		$interval.cancel(clockInterval);
		$interval.cancel(appointmentInterval);
	});

	// Invoke initially
	$scope.getSites();
	$scope.updateAppointmentInformation();
	refreshClockContent();

});
