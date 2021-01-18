define('uploadDocumentsController', [], function() {

	function uploadDocumentsController($scope, UploadDocumentsDataService, NotificationUtilities) {
		// Token variables
		$scope.token = '';
		$scope.tokenExists = null;
		const EXPECTED_TOKEN_LENGTH = 32;
		
		// Client Info Validation Variables
		$scope.clientData = {};
		$scope.validatingClientInformation = false;
		$scope.clientInformationValidated = false;
		$scope.invalidClientInformation = false;
		$scope.isResidentialAppointment = true;

		// Agree to virtual preparation variables
		// For some reason, you can't bind the checkbox to a primitive boolean, it has to be in an object: https://stackoverflow.com/a/23943930
		$scope.agreeToVirtualPreparationCheckbox = {
			checked: false
		};

		// Ready button variables
		$scope.readyCheckbox = {
			checked: false
		};
		$scope.submittingAppointmentReady = false;
		$scope.appointmentMarkedAsReadySuccessfully = false;

		// Files
		// Copied from https://stackoverflow.com/a/2117523
		$scope.uuidv4 = () => {
			return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
				var r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
				return v.toString(16);
			  });
		};
		$scope.GET_DEFAULT_FILE_REPRESENTATIVE = () => ({
			id: $scope.uuidv4(),
			uploading: false,
			uploadSucceeded : false,
			error: false,
			statusMessage: "Awaiting file upload"
		});
		$scope.fileRepresentatives = [ $scope.GET_DEFAULT_FILE_REPRESENTATIVE() ];

		const MAX_FILE_NAME_LENGTH = 200;
		const BYTES_IN_A_MEGABYTE = 1000000;
		const MAX_FILE_SIZE_IN_BYTES = 10 * BYTES_IN_A_MEGABYTE;
		const ACCEPTABLE_FILE_TYPES = ['application/pdf', 'image/jpeg', 'image/png'];
		const ACCEPTABLE_FILE_EXTENSIONS = ['.pdf', '.jpeg', '.jpg', '.png'];

		$scope.doesTokenExist = function(token) {
			if (!token || 0 === token.length || EXPECTED_TOKEN_LENGTH !== token.length) {
				$scope.tokenExists = false;
				return;
			}

			UploadDocumentsDataService.doesTokenExist(token).then((result) => {
				if (result == null) {
					NotificationUtilities.giveNotice('Failure', 'There was an error loading appointment information. Please refresh and try again.', false);
					$scope.tokenExists = null;
				} else {
					$scope.tokenExists = result.exists || false;
				}
			});
		};

		// The token value isn't reflected in this controller until the DOM for the uploadDocuments directive
		// is actually created, so we have to watch for the value change instead of simply invoking the method 
		$scope.$watch('token', (newValue, oldValue) => {
			$scope.doesTokenExist(newValue);
		});

		$scope.validateClientInformation = function() {
			if ($scope.validatingClientInformation || $scope.clientInformationValidated) {
				return;
			}
			$scope.validatingClientInformation = true;
			
			const token = $scope.token;
			const firstName = $scope.clientData.firstName || '';
			const lastName = $scope.clientData.lastName || '';
			const emailAddress = $scope.clientData.email || '';
			const phoneNumber = $scope.clientData.phone || '';

			UploadDocumentsDataService.validateClientInformation(token, firstName, lastName, emailAddress, phoneNumber).then((response) => {
				if (response == null || !response.success) {
					const errorMessage = response ? response.error : 'There was an error on the server. Please refresh the page and try again.';
					NotificationUtilities.giveNotice('Failure', errorMessage, false);
				} else {
					$scope.clientInformationValidated = response.validated;
					if (!response.validated) {
						$scope.invalidClientInformation = true;
						$scope.clientData = {};
					} else {
						$scope.isResidentialAppointment = response.residentialAppointment;
						$scope.appointmentTimeStr = response.appointmentTimeStr;
						$scope.uploadDeadlineStr = response.uploadDeadlineStr;
					}
				}

				document.body.scrollTop = document.documentElement.scrollTop = 0;
				$scope.validatingClientInformation = false;
			});
		};

		$scope.uploadDocument = (fileRepresentative) => {
			if (fileRepresentative.uploading || fileRepresentative.uploadSucceeded) {
				return;
			}
			
			fileRepresentative.uploading = true;
			fileRepresentative.error = false;
			fileRepresentative.statusMessage = "Uploading... Please wait.";

			const token = $scope.token;
			const firstName = $scope.clientData.firstName || '';
			const lastName = $scope.clientData.lastName || '';
			const emailAddress = $scope.clientData.email || '';
			const phoneNumber = $scope.clientData.phone || '';

			const fileElement = document.getElementById(fileRepresentative.id);
			try {
				if (fileElement == null) {
					throw "Error: Please refresh the page and try again";
				}
				if (fileElement.files == null || fileElement.files.length <= 0 || fileElement.files[0] == null) {
					throw "Error: No file has been selected";
				}
				const file = fileElement.files[0];
				if (!file.name || file.name.trim().length === 0) {
					throw "Error: Invalid file name, the file name cannot be empty";
				}
				if (file.name.trim().length > MAX_FILE_NAME_LENGTH) {
					throw `Error: File name is too long, the max length is ${MAX_FILE_NAME_LENGTH}`;
				}
				if (file.size <= 0) {
					throw `Error: File is empty`;
				}
				if (file.size > MAX_FILE_SIZE_IN_BYTES) {
					throw `Error: File is too big, the max size is ${MAX_FILE_SIZE_IN_BYTES / BYTES_IN_A_MEGABYTE}MB`;
				}
				if (!ACCEPTABLE_FILE_TYPES.includes(file.type)) {
					throw `Error: Invalid file type. Allowed file types are: ${ACCEPTABLE_FILE_EXTENSIONS.join(', ')}`;
				}
				const validFileExtension = ACCEPTABLE_FILE_EXTENSIONS.some((extension) => file.name.trim().toLowerCase().endsWith(extension));
				if (!validFileExtension) {
					throw `Error: Invalid file extension. Allowed file extensions are: ${ACCEPTABLE_FILE_EXTENSIONS.join(', ')}`;
				}

				// File is good to go!
				UploadDocumentsDataService.uploadDocument(token, firstName, lastName, emailAddress, phoneNumber, file).then((response) => {
					if (response == null || !response.success) {
						fileRepresentative.error = true;
						const errorMessage = response ? response.error : 'Something went wrong and the file could not be uploaded! Please try again later.';
						fileRepresentative.statusMessage = errorMessage;
					} else {
						fileRepresentative.uploadSucceeded = true;
						fileRepresentative.statusMessage = "Upload successful!";
					}
					fileRepresentative.uploading = false;
				});
			} catch (error) {
				fileRepresentative.error = true;
				fileRepresentative.statusMessage = error;
				fileRepresentative.uploading = false;
			}
		};

		$scope.addAnotherDocument = () => {
			$scope.fileRepresentatives.push($scope.GET_DEFAULT_FILE_REPRESENTATIVE());
		};

		$scope.removeDocument = (fileToRemove) => {
			$scope.fileRepresentatives = $scope.fileRepresentatives.filter((file) => file.id !== fileToRemove.id)
			if ($scope.fileRepresentatives.length <= 0) {
				$scope.addAnotherDocument();
			}
		};

		$scope.markAppointmentAsReady = () => {
			if (!$scope.readyCheckbox.checked || $scope.submittingAppointmentReady || $scope.appointmentMarkedAsReadySuccessfully) {
				return;
			}
			$scope.submittingAppointmentReady = true;

			const token = $scope.token;
			const firstName = $scope.clientData.firstName || '';
			const lastName = $scope.clientData.lastName || '';
			const emailAddress = $scope.clientData.email || '';
			const phoneNumber = $scope.clientData.phone || '';

			UploadDocumentsDataService.markAppointmentAsReady(token, firstName, lastName, emailAddress, phoneNumber).then((response) => {
				if (response == null || !response.success) {
					const errorMessage = response ? response.error : 'Something went wrong and the appointment could not be marked as ready! Please try again later.';
					NotificationUtilities.giveNotice('Failure', errorMessage, false);
				} else {
					NotificationUtilities.giveNotice('Success!', 'Your appointment was successfully marked as ready.');
					$scope.appointmentMarkedAsReadySuccessfully = true;
				}
				$scope.submittingAppointmentReady = false;
			});
		};

		$scope.downloadResidentIntakeForm = () => {
			$scope.downloadFile('/server/download/downloadIntakeForm.php');
		};

		$scope.downloadResidentIntakeFormSpanish = () => {
			$scope.downloadFile('/server/download/downloadIntakeFormSpanish.php');
		};

		$scope.downloadNonResidentIntakeForm = () => {
			$scope.downloadFile('/server/download/downloadNonResidentIntakeForm.php');
		};

		$scope.downloadForm14446 = () => {
			$scope.downloadFile('/server/download/downloadForm14446VirtualAppt.php');
		};

		$scope.downloadFile = (fileUrl) => {
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

	uploadDocumentsController.$inject = ['$scope', 'uploadDocumentsDataService', 'notificationUtilities'];

	return uploadDocumentsController;

});
