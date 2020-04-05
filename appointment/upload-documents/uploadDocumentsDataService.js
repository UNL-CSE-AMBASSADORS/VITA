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
				formData.set('action', 'upload');
				formData.set('token', token);
				formData.set('firstName', firstName);
				formData.set('lastName', lastName);
				formData.set('emailAddress', emailAddress);
				formData.set('phoneNumber', phoneNumber);
				formData.set('file', file);

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
			}
		};
	}

	uploadDocumentsDataService.$inject = ['$http'];

	return uploadDocumentsDataService;
});