define('appointmentNotesAreaController', [], function() {

	function appointmentNotesAreaController($scope, AppointmentNotesAreaDataService, AppointmentNotesAreaSharedPropertiesService) {
		$scope.sharedProperties = AppointmentNotesAreaSharedPropertiesService.getSharedProperties();
		$scope.noteToAddText = null; 
		$scope.addingNote = false;


		$scope.getNotes = function() {
			const appointmentId = $scope.sharedProperties.appointmentId;
			console.log("CALLED GET NOTES");
			// TODO: NEED TO GET NOTES
		}

		$scope.addNote = function(noteText) {
			if ($scope.addingNote) {
				return false;
			}
			$scope.addingNote = true;

			// TODO: NEED TO FIGURE OUT WHERE THIS COMES FROM
			const appointmentId = 1;
			AppointmentNotesAreaDataService.addNote(appointmentId, encodeUriString(noteText)).then(function(result) {
				if(result == null) {
					alert('There was an error loading the appointments. Please try refreshing the page.');
				} else {
					console.log("TODO SUCCESSFULLY ADDED NOTE, NEED TO ADD IT TO THE NOTES");
				}
			});

			$scope.addingNote = false;
		}

		function encodeUriString(str) {
			return encodeURIComponent(str).replace(/[!'()]/g, escape).replace(/\*/g, "%2A");
		}
	}

	appointmentNotesAreaController.$inject = ['$scope', 'appointmentNotesAreaDataService', 'appointmentNotesAreaSharedPropertiesService'];

	return appointmentNotesAreaController;
});