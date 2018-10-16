define('appointmentsController', [], function() {

	function appointmentsController($scope, AppointmentsService, AppointmentPickerSharedPropertiesService, AppointmentNotesAreaSharedPropertiesService) {

		$scope.appointmentPickerSharedProperties = AppointmentPickerSharedPropertiesService.getSharedProperties();
		$scope.appointmentNotesAreaSharedProperties = AppointmentNotesAreaSharedPropertiesService.getSharedProperties();
		$scope.submittingReschedule = false;
		$scope.cancelling = false;

		$scope.getAppointments = function() {
			const year = new Date().getFullYear();
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
							appointment.name = appointment.firstName + " " + appointment.lastName;

							appointment.completed = appointment.completed == true; // Do this since the SQL returns 0/1, and we want it to be false/true
							appointment.cancelled = appointment.cancelled == true; // Do this since the SQL returns 0/1, and we want it to be false/true
							appointment.notStarted = !appointment.cancelled && appointment.timeIn == null;
							// TODO: Not sure this logic for incomplete is good
							appointment.incomplete = !appointment.cancelled && !appointment.completed;
							appointment.inProgress = !appointment.cancelled && !appointment.incomplete && appointment.timeIn != null && !appointment.completed;
							
							if (appointment.completed) appointment.statusText = "Complete";
							else if (appointment.cancelled) appointment.statusText = "Cancelled";
							else if (appointment.notStarted) appointment.statusText = "Not Started";
							else if (appointment.inProgress) appointment.statusText = "In Progress";
							else if (appointment.incomplete) appointment.statusText = "Incomplete";
							else appointment.statusText = "Unknown";

							return appointment;
						});
					} else {
						$scope.appointments = [];
					}
				}
			});
		};

		$scope.rescheduleAppointment = function() {

			if ($scope.appointmentPickerSharedProperties.selectedDate == null || $scope.appointmentPickerSharedProperties.selectedSite == null || $scope.appointmentPickerSharedProperties.selectedTime == null || $scope.submittingReschedule) {
				return false;
			}

			$scope.submittingReschedule = true;

			let appointmentId = $scope.appointment.appointmentId;
			let appointmentTimeId = $scope.appointmentPickerSharedProperties.selectedAppointmentTimeId;

			AppointmentsService.rescheduleAppointment(appointmentId, appointmentTimeId).then(function(result) {
				document.body.scrollTop = document.documentElement.scrollTop = 0;
				
				if (result.success) {
					$scope.appointment.scheduledTime = $scope.appointmentPickerSharedProperties.selectedDate + ' ' + $scope.appointmentPickerSharedProperties.selectedTime;
					$scope.appointment.title = $scope.appointmentPickerSharedProperties.selectedSiteTitle;

					// Clear the selected values
					$scope.appointmentPickerSharedProperties.selectedDate = null;
					$scope.appointmentPickerSharedProperties.selectedSite = null;
					$scope.appointmentPickerSharedProperties.selectedTime = null;

					$scope.resetAppointmentProperties($scope.appointment);

					$scope.submittingReschedule = false;
					
					// Let the user know it was successful
					$scope.giveNotice("Success!", "This appointment was successfully rescheduled.");
				} else {
					alert(result.error);

					$scope.submittingReschedule = false;

					// Let the user know it failed
					$scope.giveNotice("Failure", "Something went wrong and this appointment was not rescheduled!", false);
				}
			});
		}

		$scope.resetAppointmentProperties = function(appointment) {
			// Reset the status properties on the appointment
			appointment.cancelled = false;
			appointment.completed = false;
			appointment.notStarted = true;
			appointment.incomplete = false;
			appointment.inProgress = false;
			appointment.statusText = "Not Started";

			// Reset times on the appointment
			appointment.timeIn = null;
			appointment.timeReturnedPapers = null;
			appointment.timeAppointmentStarted = null;
			appointment.timeAppointmentEnded = null;
			appointment.servicedByStation = null;
		}

		$scope.cancelAppointment = function() {
			if ($scope.cancelling) return false;

			$scope.cancelling = true;

			const appointmentId = $scope.appointment.appointmentId;
			AppointmentsService.cancelAppointment(appointmentId).then(function(result) {
				if (result.success) {
					document.body.scrollTop = document.documentElement.scrollTop = 0;

					$scope.appointment.cancelled = true;
					$scope.appointment.notStarted = false;
					$scope.appointment.statusText = "Cancelled";

					// Let the user know it was successful
					$scope.giveNotice("Success!", "This appointment was successfully cancelled.", true);
				} else {
					document.body.scrollTop = document.documentElement.scrollTop = 0;
					alert(result.error);

					// Let the user know it failed
					$scope.giveNotice("Failure", "Something went wrong and this appointment was not cancelled!", false);
				}

				$scope.cancelling = false;
			});
		}

		$scope.selectAppointment = function(appointment) {
			$scope.appointment = appointment;
			$scope.appointmentNotesAreaSharedProperties.appointmentId = appointment.appointmentId;  // Need to share the appointment id so we can load/add notes
			document.body.scrollTop = document.documentElement.scrollTop = 0;
		};

		$scope.deselectAppointment = function() {
			$scope.appointment = null;
		}

		$scope.initializeCancelConfirmationModal = () => {
			WDN.initializePlugin('modal', [() => {
				const $ = require('jquery');

				$('#confirm-cancel-modal-opener').colorbox({
					inline: true
				});
				$('.close-modal-button').click(function() {
					$.colorbox.close();
				});
				$('#cancel-button').click(function() {
					$.colorbox.close();
				});
			}]);
		}

		$scope.giveNotice = function(title, message, affirmative = true) {
			WDN.initializePlugin('notice');
			var body = angular.element( document.querySelector( 'body' ) );
			body.append(`
				<div class="wdn_notice ${affirmative ? 'affirm' : 'negate'}" data-overlay="maincontent" data-duration="10">
					<div class="close">
						<a href="#">Close this notice</a>
					</div>
					<div class="message">
						<p class="title">${title}</p>
						<p>${message}</a>
						</p>
					</div>
				</div>`);
		}

		// Invoke initially
		$scope.getAppointments();
		$scope.initializeCancelConfirmationModal();
	}

	appointmentsController.$inject = ['$scope', 'appointmentsDataService', 'appointmentPickerSharedPropertiesService', 'appointmentNotesAreaSharedPropertiesService'];

	return appointmentsController;

});
