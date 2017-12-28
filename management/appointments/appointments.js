let appointmentsApp = angular.module("appointmentsApp", ["ngMaterial", "ngMessages"]);

appointmentsApp.controller("AppointmentsController", function($scope, $interval, AppointmentsService) {
	$scope.getAppointments = function() {
		let year = new Date().getFullYear();
		AppointmentsService.getAppointments(year).then(function(data) {
			if(data == null) {
				alert('There was an error loading the appointments. Please try refreshing the page.');
			} else {
				if (!data.success) {
					$scope.appointments = [];
					alert(data.error);
					return;
				}

				if (data.appointments.length > 0) {
					$scope.appointments = data.appointments.map((appointment) => {
						// We force the time into CST
						appointment.scheduledTime = new Date(appointment.scheduledTime + ' CST');
						appointment.name = appointment.firstName + " " + appointment.lastName;
						return appointment;
					});
				} else {
					$scope.appointments = [];
				}
			}
		});
	};

	$scope.selectAppointment = function(appointment) {
		$scope.appointment = appointment;
	};

	$scope.deselectAppointment = function() {
		$scope.appointment = null;
	}

	// Invoke
	$scope.getAppointments();
});

appointmentsApp.filter('searchFor', function() {
	// All filters must return a function. The first parameter
	// is the data that is to be filtered, and the second is an
	// argument that may be passed with a colon (searchFor:searchString)

	return function(arr, searchString){
		if(!searchString){
			return arr;
		}

		let result = [];

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
