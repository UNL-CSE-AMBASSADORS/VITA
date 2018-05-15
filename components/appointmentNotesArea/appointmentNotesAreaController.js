define('appointmentNotesAreaController', [], function() {

	function appointmentNotesAreaController($scope, AppointmentNotesAreaDataService, AppointmentNotesAreaSharedPropertiesService) {
		$scope.sharedProperties = AppointmentNotesAreaSharedPropertiesService.getSharedProperties();
		$scope.notes = [];
		$scope.noteToAddText = null; 
		$scope.addingNote = false;


		$scope.getNotes = function() {
			// TODO: NEED TO FIGURE OUT WHERE THIS COMES FROM
			const appointmentId = 1;
			// const appointmentId = $scope.sharedProperties.appointmentId;

			console.log("CALLED GET NOTES");
			AppointmentNotesAreaDataService.getNotesForAppointment(appointmentId).then(function(result) {
				if (result == null || !result.success) {
					alert(result ? result.error : 'There was an error ');
				} else {
					$scope.notes = result.notes;
				}
			});
		}

		$scope.addNote = function(noteText) {
			if ($scope.addingNote) {
				return false;
			}
			$scope.addingNote = true;

			// TODO: NEED TO FIGURE OUT WHERE THIS COMES FROM
			const appointmentId = 1;
			AppointmentNotesAreaDataService.addNote(appointmentId, encodeUriString(noteText)).then(function(result) {
				if(result == null || !result.success) {
					alert(result ? result.error : 'There was an error adding the note. Please refresh the page and try again.');
				} else {
					// TODO: NEED TO DO THIS
					console.log("TODO SUCCESSFULLY ADDED NOTE, NEED TO ADD IT TO THE NOTES");
					$scope.notes.push({
						note: noteText,
						createdAt: new Date(),
						createdByFirstName: "You",
						createdByLastName: ""
					});
				}
			});

			$scope.addingNote = false;
		}

		function encodeUriString(str) {
			return encodeURIComponent(str).replace(/[!'()]/g, escape).replace(/\*/g, "%2A");
		}

		// Invoke Initially
		$scope.getNotes();
	}

	appointmentNotesAreaController.$inject = ['$scope', 'appointmentNotesAreaDataService', 'appointmentNotesAreaSharedPropertiesService'];

	return appointmentNotesAreaController;
});