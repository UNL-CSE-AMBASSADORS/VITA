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

		const MAX_FILE_NAME_LENGTH = 200;
		const MAX_FILE_SIZE_IN_BYTES = 2000000;
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
			$scope.clientInformationValidated = true;
			return;
			if ($scope.validatingClientInformation || $scope.clientInformationValidated) {
				return;
			}
			
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
					}
				}

				document.body.scrollTop = document.documentElement.scrollTop = 0;
				$scope.validatingClientInformation = false;
			});
		};


		$scope.uploadDocuments = () => {
			const token = $scope.token;
			const firstName = $scope.clientData.firstName || '';
			const lastName = $scope.clientData.lastName || '';
			const emailAddress = $scope.clientData.email || '';
			const phoneNumber = $scope.clientData.phone || '';

			const fileElements = document.getElementsByName("files[]");
			fileElements.forEach((fileElement) => {
				const file = fileElement.files[0];
				if (file) {
					if (!file.name || file.name.trim().length === 0) {
						// Invalid file name
						return;
					}
					if (file.name.trim().length > MAX_FILE_NAME_LENGTH) {
						// File name is too long
						return;
					}
					if (file.size <= 0) {
						// File is empty
						return;
					}
					if (file.size > MAX_FILE_SIZE_IN_BYTES) {
						// Too big
						return;
					}
					if (!ACCEPTABLE_FILE_TYPES.includes(file.type)) {
						// Invalid file type
						return;
					}
					const validFileExtension = ACCEPTABLE_FILE_EXTENSIONS.some((extension) => file.name.endsWith(extension));
					if (!validFileExtension) {
						// Invalid file extension
						return;
					}

					// File is good to go!
					console.log("UPLOADING");
					console.log(file);
					UploadDocumentsDataService.uploadDocument(token, firstName, lastName, emailAddress, phoneNumber, file).then((response) => {
						if (response == null || !response.success) {
							const errorMessage = response ? response.error : 'Something went wrong and the file could not be uploaded! Please try again later.';
							NotificationUtilities.giveNotice('Failure', errorMessage, false);
						} else {
							NotificationUtilities.giveNotice('Success!', 'File successfully uploaded.');
						}
					});
				} else {
					// No file selected
				}
			});
		};

	}

	uploadDocumentsController.$inject = ['$scope', 'uploadDocumentsDataService', 'notificationUtilities'];

	return uploadDocumentsController;

});
