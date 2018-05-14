define('appointmentNotesAreaController', [], function() {

	function appointmentNotesAreaController($scope, AppointmentNotesAreaDataService) {

		$scope.addNote = function(note) {
			console.log(`TODO ADD NOTE: ${note}`);
		}

	}

	appointmentNotesAreaController.$inject = ['$scope', 'appointmentNotesAreaDataService'];

	return appointmentNotesAreaController;
});