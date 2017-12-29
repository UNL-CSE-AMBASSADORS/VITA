let appointmentsApp = angular.module("appointmentsApp", ["ngMaterial", "ngMessages"]);

appointmentsApp.controller("AppointmentsController", function($scope, $interval, AppointmentsService) {
	$scope.getAppointments = function() {
		let year = new Date().getFullYear();
		AppointmentsService.getAppointments(year).then(function(result) {
			if(result == null) {
				alert('There was an error loading the appointments. Please try refreshing the page.');
			} else {
				if (!result.success) {
					$scope.appointments = [];
					alert(result.error);
					return;
				}

				if (result.appointments.length > 0) {
					$scope.appointments = result.appointments.map((appointment) => {
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

	$scope.rescheduleAppointment = function() {
		$('#rescheduleButton').prop('disabled', true);
		let appointmentId = $scope.appointment.appointmentId;
		let scheduledTime = new Date($('#dateInput').val() + ' ' + $('#timePickerSelect').val() + ' GMT').toISOString();
		let siteId = sitePickerSelect.value;

		AppointmentsService.rescheduleAppointment(appointmentId, scheduledTime, siteId).then(function(result) {
			if (result.success) {
				// We force it to be in CST
				$scope.appointment.scheduledTime = new Date($('#dateInput').val() + ' ' + $('#timePickerSelect').val() + ' CST');
				$scope.appointment.title = $('#sitePickerSelect').text();

				// Clear the selected values
				$('#dateInput').val('');
				$('#sitePicker').hide();
				$('#timePicker').hide();
				$('#timePickerSelect').html('');
				$('#sitePickerSelect').html('');

				// Let the user know it was successful
				let rescheduleButton = $('#rescheduleButton');
				rescheduleButton.removeClass('btn-primary').addClass('btn-success').val('Successfully Rescheduled');
				window.setTimeout(function() {
					rescheduleButton.val('Reschedule')
						.removeClass('btn-success')
						.addClass('btn-primary')
						.prop('disabled', false);
				}, 1500);
			} else {
				alert(result.error);

				// Let the user know it failed
				let rescheduleButton = $('#rescheduleButton');
				rescheduleButton.removeClass('btn-primary').addClass('btn-danger').val('Failed to Reschedule');
				window.setTimeout(function() {
					rescheduleButton.val('Reschedule')
						.removeClass('btn-danger')
						.addClass('btn-primary')
						.prop('disabled', false);
				}, 1500);
			}
		});
	}

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
