define('signupController', [], function() {

	function signupController($scope, $sce, SignupService, sharedPropertiesService) {
		
		$scope.sharedProperties = sharedPropertiesService.getSharedProperties();
		$scope.successMessage = null;
		$scope.data = {};
		$scope.questions = [];
		$scope.dependents = [];
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
				"dependents": $scope.dependents,
				"appointmentTimeId": $scope.sharedProperties.selectedAppointmentTimeId,
				"siteId": $scope.sharedProperties.selectedSite
			};

			SignupService.storeAppointments(data).then(function(response) {
				if(typeof response !== 'undefined' && response && response.success){
					document.body.scrollTop = document.documentElement.scrollTop = 0;
					$scope.successMessage = $sce.trustAsHtml(response.message);
				}else{
					alert('There was an error on the server! Please refresh the page in a few minutes and try again.');
				}
			});
		}

		$scope.emailConfirmation = function() {
			$scope.emailButton.disabled = true;
			var data = {
				"action": "emailConfirmation",
				"firstName": $scope.data.firstName,
				"email": $scope.data.email,
				"siteId": $scope.sharedProperties.selectedSite,
				"appointmentTimeId": $scope.sharedProperties.selectedAppointmentTimeId
			};

			SignupService.emailConfirmation(data).then(function(response) {
				if(typeof response !== 'undefined' && response){
					if (response.success) {
						$scope.emailButton.text = "Sent!";
					} else {
						alert(response.error);
						$scope.emailButton.disabled = false;
					}
				}else{
					alert('There was an error on the server! Try again or please print this page instead.');
					$scope.emailButton.disabled = false;
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

		$scope.addDependent = function() {
			$scope.dependents.push({
				firstName: '',
				lastName: $scope.data.lastName ? $scope.data.lastName : ''
			});
		}

		$scope.removeDependent = function(dependent) {
			var index = $scope.dependents.indexOf(dependent);
			if (index > -1) {
				$scope.dependents.splice(index, 1);
			}
		}

	}

	signupController.$inject = ['$scope', '$sce', 'signupDataService', 'sharedPropertiesService'];

	return signupController;

});
