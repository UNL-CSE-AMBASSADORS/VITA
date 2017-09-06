var queueApp = angular.module("queueApp", ["ngMaterial", "ngMessages"])

.controller("QueueController", function($scope, $interval, QueueService) {
	$scope.today = new Date();
	$scope.currentDate = $scope.today;

	/**
	 * Loads the appointment info every 10 seconds
	 */
	$scope.updateAppointmentInformation = function (){
		let year = $scope.currentDate.getFullYear(),
			month = $scope.currentDate.getMonth() + 1,
			day = $scope.currentDate.getDate();
		if (month < 10) month = "0" + month;
		QueueService.getAppointments(year + "-" + month + "-" + day).then(function(data){
			if (data != null && data.length > 0){
				$scope.appointments = data.map((appointment) => {
					// This map converts the MySQL Datatime into a Javascript Date object
					var t = appointment.scheduledTime.split(/[- :]/);
					appointment.scheduledTime = new Date(Date.UTC(t[0], t[1]-1, t[2], t[3], t[4], t[5]));
					return appointment;
				});
			} else {
				$scope.appointments = [];
				console.log('server error');
			}
		});
	}
	/**
	 * Refreshes the clock
	 */
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

	$scope.$on('$destroy', function () {
		$interval.cancel(clockInterval);
		$interval.cancel(appointmentInterval);
	});

	//invoke initialy
	$scope.updateAppointmentInformation();
	refreshClockContent();

})

;
