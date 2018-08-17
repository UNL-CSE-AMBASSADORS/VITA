define('clientRescheduleController', [], function() {

	function clientRescheduleController($scope, $sce, ClientRescheduleDataService, AppointmentPickerSharedPropertiesService) {
		// Token variables
		$scope.token = '';
		$scope.tokenExists = null;
		const EXPECTED_TOKEN_LENGTH = 32;

		// Invalid for Reschedule Variables
		$scope.validForReschedule = true;
		$scope.invalidForRescheduleReason = null;
		
		// Client Info Validation Variables
		// TODO: NEED TO REMOVE THIS TEST DATA
		$scope.clientData = {
			'firstName': 'Matthew',
			'lastName': 'Meacham',
			'email': 'mmeacham6@gmail.com',
			'phone': '4029536746'
		};
		$scope.validatingClientInformation = false;
		$scope.clientInformationValidated = false;
		$scope.invalidClientInformation = false;

		// Current Appointment Variables
		$scope.currentAppointment = {};

		// Reschedule variables
		$scope.appointmentPickerSharedProperties = AppointmentPickerSharedPropertiesService.getSharedProperties();
		$scope.submittingReschedule = false;

		// Successful Reschedule Variables
		$scope.rescheduleSuccessMessage = null;
		$scope.emailButton = {
			'disabled': false,
			'text': 'Email Me this Confirmation'
		};

		// Cancel Appointment Variables
		$scope.cancellingAppointment = false;
		$scope.appointmentCancelled = false;


		$scope.doesTokenExist = function(token) {
			if (!token || 0 === token.length || EXPECTED_TOKEN_LENGTH !== token.length) {
				$scope.tokenExists = false;
				return;
			}

			ClientRescheduleDataService.doesTokenExist(token).then((result) => {
				if (result == null) {
					alert('There was an error loading appointment information. Please refresh and try again.');
					$scope.tokenExists = false;
				} else {
					$scope.tokenExists = result.exists || false;
					$scope.validForReschedule = result.valid;
					if (result.valid === false) {
						$scope.invalidForRescheduleReason = result.reason;
					}
				}
			});
		};

		// The token value isn't reflected in this controller until the DOM for the clientReschedule directive
		// is actually created, so we have to watch for the value change instead of simply invoking the method 
		$scope.$watch('token', (newValue, oldValue) => {
			$scope.doesTokenExist(newValue);
		});

		$scope.validateClientInformation = function() {
			if ($scope.validatingClientInformation || $scope.clientInformationValidated) {
				return;
			}
			
			const token = $scope.token;
			const firstName = $scope.clientData.firstName;
			const lastName = $scope.clientData.lastName;
			const emailAddress = $scope.clientData.email;
			const phoneNumber = $scope.clientData.phone;

			ClientRescheduleDataService.validateClientInformation(token, firstName, lastName, emailAddress, phoneNumber).then((response) => {
				if (response == null || !response.success) {
					alert(response ? response.error : 'There was an error on the server. Please refresh the page and try again.');
					$scope.validatingClientInformation = false;
				} else {
					$scope.clientInformationValidated = response.validated;
					if (response.validated) {
						$scope.currentAppointment = {
							'site': {
								'title': response.site.title,
								'address': response.site.address
							},
							'scheduledTime': response.scheduledTime
						};

						$scope.initializeCancelConfirmationModal();
					} else {
						$scope.invalidClientInformation = true;
						$scope.clientData = {};
						// TODO: DISPLAY A MESSAGE SAYING HOW MANY ATTEMPTS THEY HAVE LEFT?
					}
				}

				document.body.scrollTop = document.documentElement.scrollTop = 0;
				$scope.validatingClientInformation = false;
			});
		};

		$scope.rescheduleAppointment = function() {
			if ($scope.appointmentPickerSharedProperties.selectedDate == null || $scope.appointmentPickerSharedProperties.selectedSite == null || $scope.appointmentPickerSharedProperties.selectedTime == null || $scope.submittingReschedule) {
				return false;
			}
			$scope.submittingReschedule = true;

			const token = $scope.token;
			const firstName = $scope.clientData.firstName;
			const lastName = $scope.clientData.lastName;
			const emailAddress = $scope.clientData.email;
			const phoneNumber = $scope.clientData.phone;
			const appointmentTimeId = $scope.appointmentPickerSharedProperties.selectedAppointmentTimeId;

			ClientRescheduleDataService.rescheduleAppointment(token, firstName, lastName, emailAddress, phoneNumber, appointmentTimeId).then(function(response) {
				document.body.scrollTop = document.documentElement.scrollTop = 0;
				
				if (response == null || !response.success) {
					alert(response ? response.error : 'There was an error on the server. Please refresh the page and try again.');
					$scope.giveNotice("Failure", "Something went wrong and your appointment was not rescheduled! Please try again later.", false);
				} else {
					$scope.rescheduleSuccessMessage = $sce.trustAsHtml(response.message);
					$scope.giveNotice("Success!", "Your appointment was successfully rescheduled.");
				}

				$scope.submittingReschedule = false;
			});
		}

		$scope.cancelAppointment = function() {
			if ($scope.cancellingAppointment) {
				return;
			}
			$scope.cancellingAppointment = true;

			const token = $scope.token;
			const firstName = $scope.clientData.firstName;
			const lastName = $scope.clientData.lastName;
			const emailAddress = $scope.clientData.email;
			const phoneNumber = $scope.clientData.phone;

			ClientRescheduleDataService.cancelAppointment(token, firstName, lastName, emailAddress, phoneNumber).then(function(response) {
				document.body.scrollTop = document.documentElement.scrollTop = 0;
				
				// Close the cancel confirmation modal
				require(['jquery'], function($) {
					$.colorbox.close();
				});

				if (response == null || !response.success) {
					alert(response ? response.error : 'There was an error on the server. Please refresh the page and try again.');
					$scope.giveNotice("Failure", "Something went wrong and your appointment was not cancelled! Please try again later.", false);
				} else {
					$scope.appointmentCancelled = true;
					$scope.giveNotice("Success!", "Your appointment was successfully cancelled.");
				}

				$scope.cancellingAppointment = false;
			});
		}

		$scope.emailConfirmation = function() {
			$scope.emailButton.disabled = true;

			const token = $scope.token;
			const firstName = $scope.clientData.firstName;
			const lastName = $scope.clientData.lastName;
			const emailAddress = $scope.clientData.email;
			const phoneNumber = $scope.clientData.phone;

			ClientRescheduleDataService.emailConfirmation(token, firstName, lastName, emailAddress, phoneNumber).then(function(response) {
				if (response == null || !response.success) {
					alert(response ? response.error : 'There was an error on the server. Please refresh the page and try again.');
					$scope.emailButton.disabled = false;
					return;
				}

				$scope.emailButton.text = 'Sent!';
			});
		}

		$scope.giveNotice = function(title, message, affirmative = true) {
			WDN.initializePlugin('notice');
			const body = angular.element( document.querySelector( 'body' ) );
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

		$scope.initializeCancelConfirmationModal = function() {
			WDN.initializePlugin('modal', [ function() {
				require(['jquery'], function($) {
					$(function() {
						$('#confirm-cancel-modal-opener').colorbox({
							inline: true
						});

						$('.close-modal-button').click(function(){
							$.colorbox.close();
						});
					});
				});
			} ]);
		}

	}

	clientRescheduleController.$inject = ['$scope', '$sce', 'clientRescheduleDataService', 'appointmentPickerSharedPropertiesService'];

	return clientRescheduleController;

});
