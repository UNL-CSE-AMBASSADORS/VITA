define('signupController', [], function() {

	function signupController($scope, SignupService, sharedPropertiesService) {
		
		$scope.sharedProperties = sharedPropertiesService.getSharedProperties();
		$scope.successMessage = null;
		$scope.data = {};
		$scope.questions = [];
		$scope.dependents = [];
		
		$scope.storeAppointments = function() {

			var scheduledTime = new Date($scope.sharedProperties.selectedDate + " " + $scope.sharedProperties.selectedTime + " GMT").toISOString();

			var questions = [];
			Object.keys($scope.questions).forEach(function(key) {
				if($scope.questions[key] != null) {
					questions.push({
						id: key,
						value: $scope.questions[key]
					});
				}
			});

			console.log($scope.data.firstName);
			console.log($scope.data.lastName);
			console.log($scope.data.email);
			console.log($scope.data.phone);
			console.log($scope.data.language)
			console.log(questions);
			console.log($scope.dependents);
			console.log(scheduledTime);
			console.log($scope.sharedProperties.selectedSite);

			var data = {
				"firstName": $scope.data.firstName,
				"lastName": $scope.data.lastName,
				"email": $scope.data.email,
				"phone": $scope.data.phone,
				"language": $scope.data.language,
				"questions": questions,
				"dependents": $scope.dependents,
				"scheduledTime": scheduledTime,
				"siteId": $scope.sharedProperties.selectedSite
			};

			// $scope.successMessage = data;

			SignupService.storeAppointments(data).then(function(response) {
				if(typeof response !== 'undefined' && response && response.success){
					$scope.successMessage = response.message;
				}else{
					alert('There was an error on the server! Please refresh the page in a few minutes and try again.');
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
			console.log(dependent);
			var index = $scope.dependents.indexOf(dependent);
			if (index > -1) {
				$scope.dependents.splice(index, 1);
			}
		}

	}

	signupController.$inject = ['$scope', 'signupDataService', 'sharedPropertiesService'];

	return signupController;

});
