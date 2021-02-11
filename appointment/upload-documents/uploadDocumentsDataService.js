define('uploadDocumentsDataService', [], function($http) {

	function uploadDocumentsDataService($http) {
		return {
			doesTokenExist: function(token) {
				return $http.get(`/server/appointment/upload-documents/uploadDocuments.php?action=doesTokenExist&token=${token}`).then((response) => {
					return response.data;
				}, (error) => {
					return null;
				});
			},
			validateClientInformation: function(token, firstName, lastName, emailAddress, phoneNumber) {
				return $http({
					url: '/server/appointment/upload-documents/uploadDocuments.php',
					method: 'POST',
					data: 'action=validateClientInformation' +
						`&token=${token}` +
						`&firstName=${firstName}` +
						`&lastName=${lastName}` +
						`&emailAddress=${emailAddress}` + 
						`&phoneNumber=${phoneNumber}`,
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded'
					}
				}).then((response) => {
					return response.data;
				}, (error) => {
					return null;
				});
			},
			uploadDocument: (token, firstName, lastName, emailAddress, phoneNumber, file) => {
				const formData = new FormData();
				formData.append('action', 'upload');
				formData.append('token', token);
				formData.append('firstName', firstName);
				formData.append('lastName', lastName);
				formData.append('emailAddress', emailAddress);
				formData.append('phoneNumber', phoneNumber);
				formData.append('file', file, file.name);

				return $http({
					url: '/server/appointment/upload-documents/uploadDocuments.php',
					method: 'POST',
					data: formData,
					headers: {
						'Content-Type': undefined
					}
				}).then((response) => {
					return response.data;
				}, (error) => {
					return null;
				});
			},
			markAppointmentAsReady: (token, firstName, lastName, emailAddress, phoneNumber) => {
				return $http({
					url: '/server/appointment/upload-documents/uploadDocuments.php',
					method: 'POST',
					data: 'action=markAppointmentAsReady' +
						`&token=${token}` +
						`&firstName=${firstName}` +
						`&lastName=${lastName}` +
						`&emailAddress=${emailAddress}` + 
						`&phoneNumber=${phoneNumber}`,
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded'
					}
				}).then((response) => {
					return response.data;
				}, (error) => {
					return null;
				});
			},
			storeConsent: function(reviewConsent, virtualConsent, signature) {
				return $http({
					url: '/server/appointment/upload-documents/uploadDocuments.php',
					method: 'POST',
					data: 'action=storeConsent' +
						`&reviewConsent=${reviewConsent}` +
						`&virtualConsent=${virtualConsent}`+
						`&signature=${signature}`,
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded' //TODO not sure what this is
					}
				}).then((response) => {
					return response.data;
				}, (error) => {
					return null;
				});
			}
		};
	}

	uploadDocumentsDataService.$inject = ['$http'];

	return uploadDocumentsDataService;
});