define('appointmentsController', [], function() {

	function appointmentsController($scope, AppointmentsService) {
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
			if (!$('#rescheduleForm').valid() || !$("#sitePickerSelect").valid() || !$("#timePickerSelect").valid()) {
				return false;
			}
	
			$('#rescheduleButton').prop('disabled', true);
			let appointmentId = $scope.appointment.appointmentId;
			let scheduledTime = new Date($('#dateInput').val() + ' ' + $('#timePickerSelect').val() + ' GMT').toISOString();
			let siteId = sitePickerSelect.value;
	
			AppointmentsService.rescheduleAppointment(appointmentId, scheduledTime, siteId).then(function(result) {
				if (result.success) {
					// We force it to be in CST
					$scope.appointment.scheduledTime = new Date($('#dateInput').val() + ' ' + $('#timePickerSelect').val() + ' CST');
					$scope.appointment.title = $('#sitePickerSelect option:selected').text();
	
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
	
		// Invoke initially
		$scope.getAppointments();

		// // Create interval to update appointment information every 10 seconds
		// var appointmentInterval = $interval(function() {
		// 	$scope.updateAppointmentInformation();
		// }.bind(this), 10000);

		// // Destroy the intervals when we leave this page
		// $scope.$on('$destroy', function () {
		// 	$interval.cancel(clockInterval);
		// 	$interval.cancel(appointmentInterval);
		// });

		// // Invoke initially
		// $scope.getSites();
		// $scope.updateAppointmentInformation();
		// refreshClockContent();


	}

	appointmentsController.$inject = ['$scope', 'appointmentsDataService'];

	return appointmentsController;

});
