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

		$scope.countries = [ 
			{ 'name': 'China', 'treatyType': 'china' },
			{ 'name': 'India', 'treatyType': 'india' },
			
			{ 'name': 'Armenia', 'treatyType': 'treaty' },
			{ 'name': 'Azerbaijan', 'treatyType': 'treaty' },
			{ 'name': 'Bangladesh', 'treatyType': 'treaty' },
			{ 'name': 'Belarus', 'treatyType': 'treaty' },
			{ 'name': 'Canada', 'treatyType': 'treaty' },
			{ 'name': 'Cyprus', 'treatyType': 'treaty' },
			{ 'name': 'Czech Republic', 'treatyType': 'treaty' },
			{ 'name': 'Egypt', 'treatyType': 'treaty' },
			{ 'name': 'France', 'treatyType': 'treaty' },
			{ 'name': 'Georgia', 'treatyType': 'treaty' },
			{ 'name': 'Germany', 'treatyType': 'treaty' },
			{ 'name': 'Iceland', 'treatyType': 'treaty' },
			{ 'name': 'Indonesia', 'treatyType': 'treaty' },
			{ 'name': 'Israel', 'treatyType': 'treaty' },
			{ 'name': 'Kazakhstan', 'treatyType': 'treaty' },
			{ 'name': 'Kyrgyzstan', 'treatyType': 'treaty' },
			{ 'name': 'Latvia', 'treatyType': 'treaty' },
			{ 'name': 'Lithuania', 'treatyType': 'treaty' },
			{ 'name': 'Moldova', 'treatyType': 'treaty' },
			{ 'name': 'Morocco', 'treatyType': 'treaty' },
			{ 'name': 'Netherlands', 'treatyType': 'treaty' },
			{ 'name': 'Norway', 'treatyType': 'treaty' },
			{ 'name': 'Pakistan', 'treatyType': 'treaty' },
			{ 'name': 'Philippines', 'treatyType': 'treaty' },
			{ 'name': 'Poland', 'treatyType': 'treaty' },
			{ 'name': 'Portugal', 'treatyType': 'treaty' },
			{ 'name': 'Romania', 'treatyType': 'treaty' },
			{ 'name': 'Russia', 'treatyType': 'treaty' },
			{ 'name': 'Slovak Republic', 'treatyType': 'treaty' },
			{ 'name': 'Slovenia', 'treatyType': 'treaty' },
			{ 'name': 'South Korea', 'treatyType': 'treaty' },
			{ 'name': 'Spain', 'treatyType': 'treaty' },
			{ 'name': 'Tajikistan', 'treatyType': 'treaty' },
			{ 'name': 'Thailand', 'treatyType': 'treaty' },
			{ 'name': 'Trinidad and Tobago', 'treatyType': 'treaty' },
			{ 'name': 'Tunisia', 'treatyType': 'treaty' },
			{ 'name': 'Turkmenistan', 'treatyType': 'treaty' },
			{ 'name': 'Ukraine', 'treatyType': 'treaty' },
			{ 'name': 'Uzbekistan', 'treatyType': 'treaty' },
			{ 'name': 'Venezuela', 'treatyType': 'treaty' },

			{ 'name': 'Other', 'treatyType': 'non-treaty' }
		];
		
		$scope.storeAppointments = function() {

			let questions = [];
			Object.keys($scope.questions).forEach(function(key) {
				if($scope.questions[key] != null) {
					questions.push({
						id: key,
						value: $scope.questions[key]
					});
				}
			});

			console.log('QUESTIONS');
			console.log(questions);
			console.log('\n');

			// TODO: assume english for right now until support for other languages is added
			$scope.data.language = "eng";

			const data = {
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
				if (typeof response !== 'undefined' && response && response.success){
					document.body.scrollTop = document.documentElement.scrollTop = 0;
					$scope.appointmentId = response.appointmentId;
					$scope.successMessage = $sce.trustAsHtml(response.message);
					NotificationUtilities.giveNotice('Success', 'You have successfully scheduled an appointment!');
				} else {
					NotificationUtilities.giveNotice('Failure', 'There was an error on the server! Please refresh the page in a few minutes and try again.', false);
				}
			});
		};

		$scope.emailConfirmation = function() {
			$scope.emailButton.disabled = true;
			const data = {
				"action": "emailConfirmation",
				"appointmentId": $scope.appointmentId,
				"email": $scope.data.email
			};

			SignupService.emailConfirmation(data).then(function(response) {
				if (typeof response !== 'undefined' && response){
					if (response.success) {
						$scope.emailButton.text = "Sent!";
						NotificationUtilities.giveNotice('Success', 'The email has been sent!');
					} else {
						$scope.emailButton.disabled = false;
						NotificationUtilities.giveNotice('Failure', response.error, false);
					}
				} else {
					$scope.emailButton.disabled = false;
					NotificationUtilities.giveNotice('Failure', 'There was an error on the server! Try again or please print this page instead.', false);
				}
			});
		};

		$scope.intStudentChanged = function() {
			$scope.questions[3] = null;
			$scope.questions[4] = null;
			$scope.questions[5] = null;
			$scope.sharedProperties.appointmentType = 'residential';
		};

		$scope.visaChanged = function() {
			$scope.questions[4] = null;
			$scope.questions[5] = null;
			$scope.sharedProperties.appointmentType = 'residential';
		};

		$scope.residentialAppointment = function() {
			$scope.sharedProperties.appointmentType = 'residential';
		};

		$scope.studentCountryChanged = function(country) {
			$scope.sharedProperties.appointmentType = country.treatyType;
		};

	}

	signupController.$inject = ['$scope', '$sce', 'signupDataService', 'appointmentPickerSharedPropertiesService', 'notificationUtilities'];

	return signupController;

});
