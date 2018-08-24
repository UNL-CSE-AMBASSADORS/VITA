define('queueController', [], function() {

	function queueController($scope, $interval, QueueDataService) {
		$scope.today = new Date();
		$scope.currentDay = $scope.today.getDate();
		$scope.currentMonth = $scope.today.getMonth();
		$scope.currentYear = $scope.today.getFullYear();
		$scope.selectedSite = -1;

		// Load the appointment info every 10 seconds
		$scope.updateAppointmentInformation = function() {
			let year = $scope.currentYear,
				month = $scope.currentMonth + 1,
				day = $scope.currentDay;
			if (month < 10) month = "0" + month;

			let isoFormattedDate = year + "-" + month + "-" + day;

			if ($scope.selectedSite == null || $scope.selectedSite.siteId == null) return;
			let siteId = $scope.selectedSite.siteId;
			
			QueueDataService.getAppointments(isoFormattedDate, siteId).then(function(data) {
				if(data == null) {
					alert('There was an error loading the queue. Please try refreshing the page.');
				} else if(data.length > 0) {
					$scope.appointments = data.map((appointment) => {
						appointment.checkedIn = appointment.timeIn != null;
						appointment.paperworkComplete = appointment.timeReturnedPapers != null;
						appointment.preparing = appointment.timeAppointmentStarted != null;
						appointment.ended = appointment.timeAppointmentEnded != null;
						appointment.name = appointment.firstName + " " + appointment.lastName;
						appointment.noShow = appointment.noShow == true; // Do this since the SQL returns 0/1 for false/true, and we want it to be true/false instead of 0/1
						return appointment;
					});
				} else {
					$scope.appointments = [];
				}
			});
		}

		$scope.siteChanged = function() {
			$scope.updateAppointmentInformation();

			// Ensure the client is no longer selected
			$scope.client = null;
		}

		$scope.dateChanged = function() {
			$scope.updateAppointmentInformation();

			// Ensure the client is no longer selected
			$scope.client = null;
		}

		$scope.getSites = function() {
			QueueDataService.getSites().then(function(data) {
				if(data == null) {
					alert('There was an error loading the queue. Please try refreshing the page.');
				} else if (data.length > 0) {
					$scope.sites = data;
				} else {
					$scope.sites = [];
					$scope.selectedSite = -1;
				}
			});
		}

		// Refresh the clock
		let refreshClockContent = function() {
			$scope.updateTime = new Date();
			$scope.isAm = $scope.updateTime.getHours() < 12;
		}

		// Create interval to update clock every second
		var clockInterval = $interval(function() {
			refreshClockContent();
		}.bind(this), 1000);

		// Create interval to update appointment information every 10 seconds
		var appointmentInterval = $interval(function() {
			$scope.updateAppointmentInformation();
		}.bind(this), 10000);

		// Destroy the intervals when we leave this page
		$scope.$on('$destroy', function () {
			$interval.cancel(clockInterval);
			$interval.cancel(appointmentInterval);
		});

		// Invoke initially
		$scope.getSites();
		$scope.updateAppointmentInformation();
		refreshClockContent();

		WDN.initializePlugin('jqueryui', [function () {
			require(['jquery'], function($){
				$("#dateInput").datepicker({
					dateFormat : 'mm/dd/yy',
					onSelect   : function(dateTime, inst) {
						// Update the currentDay, currentMonth, currentYear variables with values from in the inst variable
						$scope.currentDay = inst.currentDay;
						$scope.currentMonth = inst.currentMonth;
						$scope.currentYear = inst.currentYear;
						$scope.dateChanged();
					}
				}).datepicker( "setDate", $scope.today );
			});
		}]);

	}

	queueController.$inject = ['$scope', '$interval', 'queueDataService'];

	return queueController;

});
