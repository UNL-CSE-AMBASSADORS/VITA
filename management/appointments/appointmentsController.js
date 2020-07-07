define('appointmentsController', [], function() {

	function appointmentsController($scope, AppointmentsService, AppointmentPickerSharedPropertiesService, AppointmentNotesAreaSharedPropertiesService, NotificationUtilities) {

		$scope.appointmentPickerSharedProperties = AppointmentPickerSharedPropertiesService.getSharedProperties();
		$scope.appointmentNotesAreaSharedProperties = AppointmentNotesAreaSharedPropertiesService.getSharedProperties();
		$scope.submittingReschedule = false;
		$scope.cancelling = false;

		$scope.appointmentTypes = [];

		$scope.getAppointments = () => {
			const year = new Date().getFullYear();
			AppointmentsService.getAppointments(year).then((result) => {
				if(result == null) {
					NotificationUtilities.giveNotice("Failure", 'There was an error loading the appointments. Please try refreshing the page.', false);
				} else {
					if (!result.success) {
						$scope.appointments = [];
						NotificationUtilities.giveNotice("Failure", result.error, false);
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

		$scope.rescheduleAppointment = () => {
			if ($scope.appointmentPickerSharedProperties.selectedDate == null || $scope.appointmentPickerSharedProperties.selectedSite == null || $scope.appointmentPickerSharedProperties.selectedTime == null || $scope.submittingReschedule) {
				return false;
			}

			$scope.submittingReschedule = true;

			const appointmentId = $scope.appointment.appointmentId;
			const appointmentTimeId = $scope.appointmentPickerSharedProperties.selectedAppointmentTimeId;

			AppointmentsService.rescheduleAppointment(appointmentId, appointmentTimeId).then((result) => {
				document.body.scrollTop = document.documentElement.scrollTop = 0;
				
				if (result.success) {
					$scope.appointment.scheduledTime = $scope.appointmentPickerSharedProperties.selectedDate + ' ' + $scope.appointmentPickerSharedProperties.selectedTime;
					$scope.appointment.title = $scope.appointmentPickerSharedProperties.selectedSiteTitle;
					$scope.appointment.appointmentType = $scope.appointmentPickerSharedProperties.appointmentType;

					// Clear the selected values
					$scope.appointmentPickerSharedProperties.selectedDate = null;
					$scope.appointmentPickerSharedProperties.selectedSite = null;
					$scope.appointmentPickerSharedProperties.selectedTime = null;

					$scope.resetAppointmentProperties($scope.appointment);

					$scope.submittingReschedule = false;
					
					// Let the user know it was successful
					NotificationUtilities.giveNotice("Success!", "This appointment was successfully rescheduled.");
				} else {
					$scope.submittingReschedule = false;

					// Let the user know it failed
					NotificationUtilities.giveNotice("Failure", "Something went wrong and this appointment was not rescheduled!", false);
				}
			});
		}

		$scope.resetAppointmentProperties = (appointment) => {
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
		}

		$scope.cancelAppointment = () => {
			if ($scope.cancelling) return false;

			$scope.cancelling = true;

			const appointmentId = $scope.appointment.appointmentId;
			AppointmentsService.cancelAppointment(appointmentId).then((result) => {
				if (result.success) {
					document.body.scrollTop = document.documentElement.scrollTop = 0;

					$scope.appointment.cancelled = true;
					$scope.appointment.notStarted = false;
					$scope.appointment.statusText = "Cancelled";

					// Let the user know it was successful
					NotificationUtilities.giveNotice("Success!", "This appointment was successfully cancelled.", true);
				} else {
					document.body.scrollTop = document.documentElement.scrollTop = 0;

					// Let the user know it failed
					NotificationUtilities.giveNotice("Failure", "Something went wrong and this appointment was not cancelled!", false);
				}

				$scope.cancelling = false;
			});
		}

		$scope.selectAppointment = (appointment) => {
			$scope.appointment = appointment;
			$scope.appointmentNotesAreaSharedProperties.appointmentId = appointment.appointmentId;  // Need to share the appointment id so we can load/add notes
			$scope.appointmentPickerSharedProperties.appointmentType = appointment.appointmentType;
			document.body.scrollTop = document.documentElement.scrollTop = 0;
		};

		$scope.deselectAppointment = () => {
			$scope.appointment = null;
		};

		$scope.getAppointmentTypes = () => {
			AppointmentsService.getAppointmentTypes().then((response) => {
				if (response.length <= 0) {
					NotificationUtilities.giveNotice('Failure', 'Unable to load appointment types from server. Please reload the page.', false);
				}
				$scope.appointmentTypes = response;
			});
		};

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
		};

		// Invoke initially
		$scope.getAppointments();
		$scope.getAppointmentTypes();
		$scope.initializeCancelConfirmationModal();
	}

	appointmentsController.$inject = ['$scope', 'appointmentsDataService', 'appointmentPickerSharedPropertiesService', 'appointmentNotesAreaSharedPropertiesService', 'notificationUtilities'];

	return appointmentsController;

});
