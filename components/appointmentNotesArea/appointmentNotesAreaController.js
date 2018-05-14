define('appointmentNotesAreaController', [], function() {

	function appointmentNotesAreaController($scope, AppointmentNotesAreaDataService, AppointmentNotesAreaSharedPropertiesService) {
		$scope.sharedProperties = AppointmentNotesAreaSharedPropertiesService.getSharedProperties();
		$scope.notes = [];
		$scope.noteToAddText = null; 
		$scope.addingNote = false;


		$scope.getNotes = function() {
			const appointmentId = $scope.sharedProperties.appointmentId;
			console.log("CALLED GET NOTES");

			AppointmentNotesAreaDataService.getNotes(appointmentId).then(function(result) {
				if(result == null) {
					alert('There was an error loading the appointments. Please try refreshing the page.');
				} else {
					// TODO: IDK IF THIS WILL BE RIGHT
					$notes = result.notes;
				}
			});
		}

		$scope.addNote = function() {
			if ($scope.addingNote) {
				return false;
			}
			$scope.addingNote = true;

			const note = $scope.noteToAddText;
			AppointmentNotesAreaDataService.addNote(appointmentId).then(function(result) {
				if(result == null) {
					alert('There was an error loading the appointments. Please try refreshing the page.');
				} else {
					console.log("TODO SUCCESSFULLY ADDED NOTE, NEED TO ADD IT TO THE NOTES");
				}
			});

			$scope.addingNote = false;
		}

		// Invoke Initially
		// $scope.getNotes();
	}

	appointmentNotesAreaController.$inject = ['$scope', 'appointmentNotesAreaDataService', 'appointmentNotesAreaSharedPropertiesService'];


	return appointmentNotesAreaController;
});