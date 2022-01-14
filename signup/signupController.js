define('signupController', [], function() {

	function signupController($scope, $sce, SignupService, sharedPropertiesService, NotificationUtilities) {
		
		$scope.sharedProperties = sharedPropertiesService.getSharedProperties();
		$scope.successMessage = null;
		$scope.appointmentId = null; // The id of the client's appointment once they successfully sign up
		$scope.hasExistingAppointment = null;
		$scope.data = {
			language: 'eng'
		};
		$scope.questions = [];
		$scope.agreeToVirtualPreparationCheckbox = {
			checked: false
		};

		$scope.countries = [ 
			{ 'name': 'China', 'appointmentType': 'china' },
			{ 'name': 'India', 'appointmentType': 'india' },

			{ 'name': 'Armenia', 'appointmentType': 'treaty' },
			{ 'name': 'Azerbaijan', 'appointmentType': 'treaty' },
			{ 'name': 'Bangladesh', 'appointmentType': 'treaty' },
			{ 'name': 'Belarus', 'appointmentType': 'treaty' },
			{ 'name': 'Canada', 'appointmentType': 'treaty' },
			{ 'name': 'Cyprus', 'appointmentType': 'treaty' },
			{ 'name': 'Czech Republic', 'appointmentType': 'treaty' },
			{ 'name': 'Egypt', 'appointmentType': 'treaty' },
			{ 'name': 'France', 'appointmentType': 'treaty' },
			{ 'name': 'Georgia', 'appointmentType': 'treaty' },
			{ 'name': 'Germany', 'appointmentType': 'treaty' },
			{ 'name': 'Iceland', 'appointmentType': 'treaty' },
			{ 'name': 'Indonesia', 'appointmentType': 'treaty' },
			{ 'name': 'Israel', 'appointmentType': 'treaty' },
			{ 'name': 'Kazakhstan', 'appointmentType': 'treaty' },
			{ 'name': 'Kyrgyzstan', 'appointmentType': 'treaty' },
			{ 'name': 'Latvia', 'appointmentType': 'treaty' },
			{ 'name': 'Lithuania', 'appointmentType': 'treaty' },
			{ 'name': 'Moldova', 'appointmentType': 'treaty' },
			{ 'name': 'Morocco', 'appointmentType': 'treaty' },
			{ 'name': 'Netherlands', 'appointmentType': 'treaty' },
			{ 'name': 'Norway', 'appointmentType': 'treaty' },
			{ 'name': 'Pakistan', 'appointmentType': 'treaty' },
			{ 'name': 'Philippines', 'appointmentType': 'treaty' },
			{ 'name': 'Poland', 'appointmentType': 'treaty' },
			{ 'name': 'Portugal', 'appointmentType': 'treaty' },
			{ 'name': 'Romania', 'appointmentType': 'treaty' },
			{ 'name': 'Russia', 'appointmentType': 'treaty' },
			{ 'name': 'Slovak Republic', 'appointmentType': 'treaty' },
			{ 'name': 'Slovenia', 'appointmentType': 'treaty' },
			{ 'name': 'South Korea', 'appointmentType': 'treaty' },
			{ 'name': 'Spain', 'appointmentType': 'treaty' },
			{ 'name': 'Tajikistan', 'appointmentType': 'treaty' },
			{ 'name': 'Thailand', 'appointmentType': 'treaty' },
			{ 'name': 'Trinidad and Tobago', 'appointmentType': 'treaty' },
			{ 'name': 'Tunisia', 'appointmentType': 'treaty' },
			{ 'name': 'Turkmenistan', 'appointmentType': 'treaty' },
			{ 'name': 'Ukraine', 'appointmentType': 'treaty' },
			{ 'name': 'Uzbekistan', 'appointmentType': 'treaty' },
			{ 'name': 'Venezuela', 'appointmentType': 'treaty' },

			{ 'name': 'Other', 'appointmentType': 'non-treaty' }
		];

		$scope.findExistingAppointment = () => {
			const firstName = $scope.data.firstName || '';
			const lastName = $scope.data.lastName || '';
			SignupService.getExistingClientAppointments(firstName, lastName).then((response) => {
				if (typeof response !== 'undefined' && response && response.success){
					console.log("success, num appointments is:");
					console.log(response.numberExistingAppointments);
					if(response.numberExistingAppointments === 0) {
						console.log('no existing appts');
						$scope.hasExistingAppointment = false;
					} else {
						console.log("appts exist, num appointments is:");
						console.log(response.numberExistingAppointments);	
						$scope.hasExistingAppointment = true;
					}
				} else {
					NotificationUtilities.giveNotice('Failure', 'There was an error on the server! Please refresh the page in a few minutes and try again.', false);
				}
			})
		};
		
		$scope.storeAppointments = () => {
			let questions = [];
			Object.keys($scope.questions).forEach((key) => {
				if($scope.questions[key] != null) {
					questions.push({
						id: key,
						value: $scope.questions[key]
					});
				}
			});

			// The country question has to give the appointmentType instead of the entire country object
			const countryQuestionDatabaseId = "6";
			const indexOfCountryQuestionInQuestionsArray = questions.findIndex((question) => question.id === countryQuestionDatabaseId);
			if (questions[indexOfCountryQuestionInQuestionsArray]) {
				const country = questions[indexOfCountryQuestionInQuestionsArray].value;

				// These ids were pulled manually from the database
				let answerDatabaseId = -1;
				if(country.appointmentType === 'china') answerDatabaseId = "11";
				else if(country.appointmentType === 'india') answerDatabaseId = "12";
				else if(country.appointmentType === 'treaty') answerDatabaseId = "13";
				else if(country.appointmentType === 'non-treaty') answerDatabaseId = "14";

				questions[indexOfCountryQuestionInQuestionsArray] = {
					id: countryQuestionDatabaseId,
					value: answerDatabaseId
				};
			}

			// $scope.data.language = "eng";

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

			console.log("to store appt, first check for existing");
			$scope.findExistingAppointment();
			if(!$scope.hasExistingAppointment) {
				console.log("found no existing appts, hasExistingAppointment is:");
				console.log($scope.hasExistingAppointment);
				SignupService.storeAppointments(data).then((response) => {
					if (typeof response !== 'undefined' && response && response.success){
						document.body.scrollTop = document.documentElement.scrollTop = 0;
						$scope.appointmentId = response.appointmentId;
						$scope.successMessage = $sce.trustAsHtml(response.message);

						// Send the confirmation email
						if ($scope.data.email != null && $scope.data.email.length > 0) {
							$scope.emailConfirmation();
						}
					} else {
						NotificationUtilities.giveNotice('Failure', 'There was an error on the server while storing your appointment. Please refresh the page in a few minutes and try again.', false);
					}
				});
				} else {
					// TODO finExistingAppoitnment has already thrown an error, do we need that one?
					// TODO can i notify after changing lcoation? need to makme this a next?
					NotificationUtilities.giveNotice('Failure', "1You may not sign up for an appointment if you already have an existing one.", false);
					window.location.replace("../cancel/index.php");
					NotificationUtilities.giveNotice('Failure', "2You may not sign up for an appointment if you already have an existing one.", false);
				}
		};

		$scope.emailConfirmation = () => {
			const data = {
				"action": "emailConfirmation",
				"appointmentId": $scope.appointmentId,
				"email": $scope.data.email
			};

			SignupService.emailConfirmation(data).then(function(response) {
				if (typeof response !== 'undefined' && response){
					if (response.success) {
						NotificationUtilities.giveNotice('Success', 'A confirmation email for your scheduled appointment has been sent!');
					} else {
						NotificationUtilities.giveNotice('Failure', response.error, false);
					}
				} else {
					NotificationUtilities.giveNotice('Failure', 'There was an error sending a confirmation email! Please print this page instead.', false);
				}
			});
		};

		$scope.updateAppointmentType = () => {
			if ($scope.isVirtualAppointmentRequested() && !$scope.sharedProperties.appointmentType.includes('virtual-')) {
				$scope.sharedProperties.appointmentType = 'virtual-' + $scope.sharedProperties.appointmentType;
			}
		};

		$scope.intStudentChanged = () => {
			$scope.questions[3] = null;
			$scope.questions[4] = null;
			$scope.questions[5] = null;
			$scope.questions[6] = null;
			$scope.sharedProperties.appointmentType = 'residential';
			$scope.updateAppointmentType();
		};

		$scope.visaChanged = () => {
			$scope.questions[4] = null;
			$scope.questions[5] = null;
			$scope.questions[6] = null;
			$scope.sharedProperties.appointmentType = 'residential';
			$scope.updateAppointmentType();
		};

		$scope.residentialAppointment = () => {
			$scope.questions[6] = null;
			$scope.sharedProperties.appointmentType = 'residential';
			$scope.updateAppointmentType();
		};

		$scope.studentCountryChanged = (country) => {
			if (country) {
				$scope.sharedProperties.appointmentType = country.appointmentType;
			}
			$scope.updateAppointmentType();
		};

		$scope.isEmailRequired = () => {
			return $scope.isVirtualAppointmentRequested();
		};

		$scope.isVirtualAppointmentRequested = () => {
			// TODO: This is hard-coded to true right now since all appointments are virtual, should eventually be replaced with a question
			return false;
		};

		$scope.downloadForm14446 = () => {
			const fileUrl = '/server/download/downloadForm14446VirtualAppt.php';
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

		// TODO This makes sure getTimes calls for virtual-residential appointments instead of residential so the DatePicker shows up.
		// This should be removed once we have a button that toggles virtual/in-person.
		$scope.updateAppointmentType();

	}

	signupController.$inject = ['$scope', '$sce', 'signupDataService', 'appointmentPickerSharedPropertiesService', 'notificationUtilities'];

	return signupController;

});
