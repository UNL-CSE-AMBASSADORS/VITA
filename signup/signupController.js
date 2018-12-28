define('signupController', [], function() {

	function signupController($scope, $sce, SignupService, sharedPropertiesService, NotificationUtilities) {
		
		$scope.sharedProperties = sharedPropertiesService.getSharedProperties();
		$scope.successMessage = null;
		$scope.appointmentId = null; // The id of the client's appointment once they successfully sign up
		$scope.data = {};
		$scope.questions = [];
		$scope.emailButton = {};
		$scope.emailButton.disabled = false
		$scope.emailButton.text = 'Email Me this Confirmation';
		
		$scope.storeAppointments = function() {

			var questions = [];
			Object.keys($scope.questions).forEach(function(key) {
				if($scope.questions[key] != null) {
					questions.push({
						id: key,
						value: $scope.questions[key]
					});
				}
			});

			//TODO assume english for right now until support for other languages is added
			$scope.data.language = "eng";

			var data = {
				"action": "storeAppointment",
				"firstName": $scope.data.firstName,
				"lastName": $scope.data.lastName,
				"email": $scope.data.email,
				"phone": $scope.data.phone,
				"language": $scope.data.language,
				"questions": questions,
				"appointmentTimeId": $scope.sharedProperties.selectedAppointmentTimeId,
				"siteId": $scope.sharedProperties.selectedSite
			};

			SignupService.storeAppointments(data).then(function(response) {
				if(typeof response !== 'undefined' && response && response.success){
					document.body.scrollTop = document.documentElement.scrollTop = 0;
					$scope.appointmentId = response.appointmentId;
					$scope.successMessage = $sce.trustAsHtml(response.message);
					NotificationUtilities.giveNotice('Success', 'You have successfully scheduled an appointment!');
				} else {
					NotificationUtilities.giveNotice('Failure', 'There was an error on the server! Please refresh the page in a few minutes and try again.', false);
				}
			});
		}

		$scope.emailConfirmation = function() {
			$scope.emailButton.disabled = true;
			var data = {
				"action": "emailConfirmation",
				"appointmentId": $scope.appointmentId,
				"email": $scope.data.email
			};

			SignupService.emailConfirmation(data).then(function(response) {
				if(typeof response !== 'undefined' && response){
					if (response.success) {
						$scope.emailButton.text = "Sent!";
						NotificationUtilities.giveNotice('Success', 'The email has been sent!');
					} else {
						$scope.emailButton.disabled = false;
						NotificationUtilities.giveNotice('Failure', response.error, false);
					}
				}else{
					$scope.emailButton.disabled = false;
					NotificationUtilities.giveNotice('Failure', 'There was an error on the server! Try again or please print this page instead.', false);
				}
			});
		}

		$scope.intStudentChanged = function() {
			$scope.questions[3] = null;
			$scope.questions[4] = null;
			$scope.questions[5] = null;
			$scope.sharedProperties.studentScholar = false
		}

		$scope.visaChanged = function() {
			$scope.questions[4] = null;
			$scope.questions[5] = null;
			$scope.sharedProperties.studentScholar = false;
		}

		$scope.standardAppointment = function() {
			$scope.sharedProperties.studentScholar = false;
		}

		$scope.studentScholarAppointment = function() {
			$scope.sharedProperties.studentScholar = true;
		}

	}

	signupController.$inject = ['$scope', '$sce', 'signupDataService', 'appointmentPickerSharedPropertiesService', 'notificationUtilities'];

	return signupController;

});
