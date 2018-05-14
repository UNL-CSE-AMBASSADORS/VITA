define('appointmentPickerController', [], function() {

	function appointmentsController($scope, AppointmentPickerDataService, sharedPropertiesService) {
		$scope.times = [];
		$scope.sites = [];
		$scope.dates = [];
		$scope.today = new Date();
		$scope.sharedProperties = sharedPropertiesService.getSharedProperties();

		$scope.getAppointments = function(studentScholar = false) {
			let year = new Date().getFullYear();
			AppointmentPickerDataService.loadAllAppointments(year, studentScholar).then(function(result) {
				if(result == null) {
					alert('There was an error loading the appointments. Please try refreshing the page.');
				} else {
					$scope.dates = result.dates;
					$scope.sharedProperties.hasAvailability = result.hasAvailability;
					$scope.sharedProperties.isLoggedIn = result.isLoggedIn;
					WDN.initializePlugin('jqueryui', [function () {
						require(['jquery'], function($){
							$("#dateInput").datepicker({
								dateFormat : 'mm/dd/yy',
								onSelect   : function(dateTime, inst) {
									// Update the currentDay, currentMonth, currentYear variables with values from in the inst variable
									$scope.dateChanged(dateTime);
									$scope.$apply();
								},
								// Good example: https://stackoverflow.com/a/1962849/7577035
								// called for every date before it is displayed
								beforeShowDay: function(date) {
									if ($scope.hasDate(date)) {
										if ($scope.hasTimeSlotsRemaining(date)) {
											return [true, 'available'];
										} else {
											return [false, 'full'];
										}
									} else {
										return [false, ''];
									}
								}
							});
						});
					}]);
				}
			});
		};

		$scope.hasDate = function(dateObj) {
			return dateObj.toISOString().substring(0, 10) in $scope.dates;
		}
	
		$scope.hasTimeSlotsRemaining = function(dateObj) {
			let date = dateObj.toISOString().substring(0, 10);
			return $scope.dates[date]["hasAvailability"];
		}
	
		$scope.updateGlobalSites = function(dateInput) {
			let dateObj = new Date(dateInput);
			let date = dateObj.toISOString().substring(0, 10);
			$scope.sites = $scope.dates[date]["sites"];
		}
	
		$scope.updateGlobalTimes = function(dateInput, site) {
			let dateObj = new Date(dateInput);
			let date  = dateObj.toISOString().substring(0, 10);
			$scope.times = $scope.dates[date]["sites"][site]["times"];
		}

		$scope.dateChanged = function(dateInput) {
			$scope.sharedProperties.selectedDate = dateInput;
			$scope.sharedProperties.selectedSite = null;
			$scope.sharedProperties.selectedTime = null;
			$scope.updateGlobalSites(dateInput);
		}

		$scope.siteChanged = function(site) {
			$scope.sharedProperties.selectedSiteTitle = $scope.sites[site]['site_title'];
			$scope.sharedProperties.selectedTime = null;
			$scope.updateGlobalTimes($scope.sharedProperties.selectedDate, site);
		}

		$scope.timeChanged = function(time) {
			$scope.sharedProperties.selectedAppointmentTimeId = $scope.times[time]['appointmentTimeId'];
		}

		$scope.$watch(
			function() { return $scope.sharedProperties.studentScholar; }, 
			function(newValue, oldValue) {
				$scope.getAppointments(newValue);
				$scope.sharedProperties.selectedDate = null;
				$scope.sharedProperties.selectedSite = null;
				$scope.sharedProperties.selectedTime = null;
	
			}
		);

	}

	appointmentsController.$inject = ['$scope', 'appointmentPickerDataService', 'appointmentPickerSharedPropertiesService'];

	return appointmentsController;

});