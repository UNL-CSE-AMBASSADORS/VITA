define('appointmentsController', [], function() {

	function appointmentsController($scope, AppointmentsService, sharedPropertiesService) {

		$scope.sharedProperties = sharedPropertiesService.getSharedProperties();
		$scope.submittingReschedule = false;
		$scope.cancelling = false;

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
							appointment.name = appointment.firstName + " " + appointment.lastName;
							appointment.completed = appointment.completed == true; // Do this since the SQL returns 0/1, and we want it to be false/true
							
							appointment.cancelled = appointment.notCompletedDescription === "Cancelled Appointment";
							appointment.notStarted = !appointment.cancelled && appointment.timeIn == null;
							appointment.incomplete = !appointment.cancelled && appointment.notCompletedDescription != null;
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

			if ($scope.sharedProperties.selectedDate == null || $scope.sharedProperties.selectedSite == null || $scope.sharedProperties.selectedTime == null || $scope.submittingReschedule) {
				return false;
			}

			$scope.submittingReschedule = true;

			let appointmentId = $scope.appointment.appointmentId;
			let appointmentTimeId = $scope.sharedProperties.selectedAppointmentTimeId;

			AppointmentsService.rescheduleAppointment(appointmentId, appointmentTimeId).then(function(result) {
				document.body.scrollTop = document.documentElement.scrollTop = 0;
				
				if (result.success) {
					$scope.appointment.scheduledTime = $scope.sharedProperties.selectedDate + ' ' + $scope.sharedProperties.selectedTime;
					$scope.appointment.title = $scope.sharedProperties.selectedSiteTitle;

					// Clear the selected values
					$scope.sharedProperties.selectedDate = null;
					$scope.sharedProperties.selectedSite = null;
					$scope.sharedProperties.selectedTime = null;

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
					$scope.appointment.notCompletedDescription = "Cancelled Appointment";

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
			document.body.scrollTop = document.documentElement.scrollTop = 0;
		};

		$scope.deselectAppointment = function() {
			$scope.appointment = null;
		}

		$scope.initializeCancelConfirmationModal = function() {
			WDN.initializePlugin('modal', [ function() {
				require(['jquery'], function($) {
					$(function() {
						$('#confirm-cancel-modal-opener').colorbox({
							inline: true
						});
 						$('.close-modal-button').click(function() {
							$.colorbox.close();
						});
						$('#cancel-button').click(function() {
							$.colorbox.close();
						});
					});
				});
			} ]);
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

	appointmentsController.$inject = ['$scope', 'appointmentsDataService', 'sharedPropertiesService'];

	return appointmentsController;

});
