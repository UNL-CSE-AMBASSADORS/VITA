var queueApp = angular.module("queueApp", ["ngMaterial", "ngMessages"])

.controller("QueueController", function($scope, $interval, QueueService) {
	$scope.today = new Date();
	$scope.currentDate = $scope.today;

	// Load the appointment info every 10 seconds
	$scope.updateAppointmentInformation = function() {
		let year = $scope.currentDate.getFullYear(),
			month = $scope.currentDate.getMonth() + 1,
			day = $scope.currentDate.getDate();
		if (month < 10) month = "0" + month;
		QueueService.getAppointments(year + "-" + month + "-" + day).then(function(data) {
			if(data == null) {
				console.log('server error');
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
	// Refresh the clock
	let refreshClockContent = function(){
		$scope.updateTime = new Date();
		$scope.isAm = $scope.updateTime.getHours() < 12;
	}

	// Create interval to update clock every second
	var clockInterval = $interval(function(){
		refreshClockContent();
	}.bind(this), 1000);

	// Create interval to update appointment information every 10 seconds
	var appointmentInterval = $interval(function(){
		$scope.updateAppointmentInformation();
	}.bind(this), 10000);

	// Destroy the intervals when we leave this page
	$scope.$on('$destroy', function () {
		$interval.cancel(clockInterval);
		$interval.cancel(appointmentInterval);
	});

	// Invoke initially
	$scope.updateAppointmentInformation();
	refreshClockContent();

});
