define('signupController', [], function() {

	function signupController($scope, $sce, SignupService, sharedPropertiesService, NotificationUtilities) {
		
		$scope.sharedProperties = sharedPropertiesService.getSharedProperties();
		$scope.successMessage = null;
		$scope.appointmentId = null; // The id of the client's appointment once they successfully sign up
		$scope.data = {};
		$scope.questions = [];
		$scope.agreeToVirtualPreparationCheckbox = {
			checked: false
		};

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

			// The country question has to give the treatyType instead of the entire country object
			const countryQuestionDatabaseId = "6";
			const indexOfCountryQuestionInQuestionsArray = questions.findIndex((question) => question.id === countryQuestionDatabaseId);
			if (questions[indexOfCountryQuestionInQuestionsArray]) {
				const country = questions[indexOfCountryQuestionInQuestionsArray].value;

				// These ids were pulled manually from the database
				let answerDatabaseId = -1;
				if(country.treatyType === 'china') answerDatabaseId = "11";
				else if(country.treatyType === 'india') answerDatabaseId = "12";
				else if(country.treatyType === 'treaty') answerDatabaseId = "13";
				else if(country.treatyType === 'non-treaty') answerDatabaseId = "14";

				questions[indexOfCountryQuestionInQuestionsArray] = {
					id: countryQuestionDatabaseId,
					value: answerDatabaseId
				};
			}

			// TODO: assume english for right now until support for other languages is added
			$scope.data.language = "eng";

			const data = {
				"action": "storeAppointment",
				"firstName": $scope.data.firstName,
				"lastName": $scope.data.lastName,
				"email": $scope.data.email,
				"phone": $scope.data.phone,
				"bestTimeToCall": $scope.data.bestTimeToCall,
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

					// Send the confirmation email
					if ($scope.data.email != null && $scope.data.email.length > 0) {
						$scope.emailConfirmation();
					}
				} else {
					NotificationUtilities.giveNotice('Failure', 'There was an error on the server! Please refresh the page in a few minutes and try again.', false);
				}
			});
		};

		$scope.emailConfirmation = function() {
			const data = {
				"action": "emailConfirmation",
				"appointmentId": $scope.appointmentId,
				"email": $scope.data.email
			};

			SignupService.emailConfirmation(data).then(function(response) {
				if (typeof response !== 'undefined' && response){
					if (response.success) {
						NotificationUtilities.giveNotice('Success', 'A confirmation email has been sent!');
					} else {
						NotificationUtilities.giveNotice('Failure', response.error, false);
					}
				} else {
					NotificationUtilities.giveNotice('Failure', 'There was an error sending a confirmation email! Please print this page instead.', false);
				}
			});
		};

		$scope.unlStudentChanged = () => {
			$scope.sharedProperties.tenantName = 'unl';
		};

		$scope.intStudentChanged = function() {
			$scope.questions[3] = null;
			$scope.questions[4] = null;
			$scope.questions[5] = null;
			$scope.questions[6] = null;
			$scope.sharedProperties.appointmentType = 'residential';
		};

		$scope.visaChanged = function() {
			$scope.questions[4] = null;
			$scope.questions[5] = null;
			$scope.questions[6] = null;
			$scope.sharedProperties.appointmentType = 'residential';
		};

		$scope.residentialAppointment = function() {
			$scope.questions[6] = null;
			$scope.sharedProperties.appointmentType = 'residential';
		};

		$scope.studentCountryChanged = function(country) {
			if (country) {
				$scope.sharedProperties.appointmentType = country.treatyType;
			}
		};

		$scope.isEmailRequired = () => {
			return $scope.sharedProperties.isSelectedSiteVirtual === true;
		};

		$scope.isIowaFiler = () => $scope.sharedProperties.tenantName === 'uiowa';

		// If the tenant changes, reset the checkbox because there is a different document they have to agree to. TODO: This can be removed when multi-tenancy with UIowa is removed
		$scope.$watch(
			() => $scope.sharedProperties.tenantName,
			() => $scope.agreeToVirtualPreparationCheckbox.checked = false
		);

		$scope.downloadForm14446 = () => {
			const fileUrl = $scope.isIowaFiler() 
				? '/server/download/downloadForm14446VirtualApptIowa.php' 
				: '/server/download/downloadForm14446VirtualAppt.php';

			let iframe = document.getElementById('hiddenDownloader');
			if (iframe == null) {
				iframe = document.createElement('iframe');
				iframe.id = 'hiddenDownloader';
				iframe.style.visibility = 'hidden';
				iframe.style.display = 'none';
				document.body.appendChild(iframe);
			}
		
			iframe.src = fileUrl;
		};

	}

	signupController.$inject = ['$scope', '$sce', 'signupDataService', 'appointmentPickerSharedPropertiesService', 'notificationUtilities'];

	return signupController;

});
