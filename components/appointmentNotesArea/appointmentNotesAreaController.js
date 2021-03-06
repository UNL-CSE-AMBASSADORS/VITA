define('appointmentNotesAreaController', [], function() {

	function appointmentNotesAreaController($scope, AppointmentNotesAreaDataService, AppointmentNotesAreaSharedPropertiesService) {
		
		$scope.appointmentNotesAreaSharedProperties = AppointmentNotesAreaSharedPropertiesService.getSharedProperties();
		$scope.notes = [];
		$scope.noteToAddText = null; 
		$scope.addingNote = false;
		$scope.MAX_NOTE_LENGTH = 1000;

		$scope.$watch(
			function() { return $scope.appointmentNotesAreaSharedProperties.appointmentId }, 
			function(newValue, oldValue) {
				if (newValue == null) return;
				
				$scope.getNotes();
			}
		);

		$scope.getNotes = function() {
			$scope.notes = [];

			const appointmentId = $scope.appointmentNotesAreaSharedProperties.appointmentId;
			AppointmentNotesAreaDataService.getNotesForAppointment(appointmentId).then(function(result) {
				if (result == null || !result.success) {
					alert(result ? result.error : 'There was an error ');
				} else {
					$scope.notes = result.notes;
				}
			});
		}

		$scope.addNote = function(noteText) {
			if ($scope.addingNote || noteText == null || noteText === "") {
				return false;
			}
			$scope.addingNote = true;
			
			const appointmentId = $scope.appointmentNotesAreaSharedProperties.appointmentId;
			AppointmentNotesAreaDataService.addNote(appointmentId, encodeUriString(noteText)).then(function(result) {
				if(result == null || !result.success) {
					alert(result ? result.error : 'There was an error adding the note. Please refresh the page and try again.');
				} else {
					$scope.notes.push({
						note: noteText,
						createdAt: "1 Minute Ago",
						createdByFirstName: "You",
						createdByLastName: ""
					});

					$scope.noteToAddText = "";
				}

				$scope.addingNote = false;
			});
		}

		function encodeUriString(str) {
			return encodeURIComponent(str).replace(/[!'()]/g, escape).replace(/\*/g, "%2A");
		}
	}

	appointmentNotesAreaController.$inject = ['$scope', 'appointmentNotesAreaDataService', 'appointmentNotesAreaSharedPropertiesService'];

	return appointmentNotesAreaController;
});
