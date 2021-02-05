define('appointmentPickerController', [], function() {

	function appointmentsController($scope, AppointmentPickerDataService, sharedPropertiesService) {
		$scope.times = [];
		$scope.sites = [];
		$scope.dates = [];
		$scope.today = new Date();
		$scope.appointmentPickerSharedProperties = sharedPropertiesService.getSharedProperties();

		$scope.getAppointments = (appointmentType = "residential") => {
			const year = new Date().getFullYear();
			AppointmentPickerDataService.loadAllAppointments(year, appointmentType).then((result) => {
				if(result == null) {
					alert('There was an error loading the appointments. Please try refreshing the page.');
				} else {
					$scope.dates = result.dates;
					$scope.appointmentPickerSharedProperties.hasAvailability = result.hasAvailability;
					$scope.appointmentPickerSharedProperties.isLoggedIn = result.isLoggedIn;
					WDN.initializePlugin('jqueryui', [() => {
						require(['jquery'], ($) => {
							$("#dateInput").datepicker({
								dateFormat : 'mm/dd/yy',
								onSelect   : (dateTime, inst) => {
									// Update the currentDay, currentMonth, currentYear variables with values from in the inst variable
									$scope.dateChanged(dateTime);
									$scope.$apply();
								},
								// Good example: https://stackoverflow.com/a/1962849/7577035
								// called for every date before it is displayed
								beforeShowDay: (date) => {
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
		
		$scope.isResidentialAppointmentType = () => {
			return $scope.appointmentPickerSharedProperties.appointmentType.includes('residential');
		};

		$scope.hasDate = (dateObj) => {
			return dateObj.toISOString().substring(0, 10) in $scope.dates;
		};
	
		$scope.hasTimeSlotsRemaining = (dateObj) => {
			const date = dateObj.toISOString().substring(0, 10);
			return $scope.dates[date]["hasAvailability"];
		};
	
		$scope.updateGlobalSites = (dateInput) => {
			const dateObj = new Date(dateInput);
			const date = dateObj.toISOString().substring(0, 10);
			$scope.sites = $scope.dates[date]["sites"];
		};
	
		$scope.updateGlobalTimes = (dateInput, site) => {
			const dateObj = new Date(dateInput);
			const date  = dateObj.toISOString().substring(0, 10);
			$scope.times = $scope.dates[date]["sites"][site]["times"];
		};

		$scope.dateChanged = (dateInput) => {
			$scope.appointmentPickerSharedProperties.selectedDate = dateInput;
			$scope.appointmentPickerSharedProperties.selectedSite = null;
			$scope.appointmentPickerSharedProperties.selectedTime = null;
			$scope.updateGlobalSites(dateInput);
		};

		$scope.siteChanged = (site) => {
			$scope.appointmentPickerSharedProperties.selectedSiteTitle = $scope.sites[site]['siteTitle'];
			$scope.appointmentPickerSharedProperties.selectedTime = null;
			$scope.updateGlobalTimes($scope.appointmentPickerSharedProperties.selectedDate, site);
		};

		$scope.timeChanged = (time) => {
			$scope.appointmentPickerSharedProperties.selectedAppointmentTimeId = $scope.times[time]['appointmentTimeId'];
		};

		$scope.getTimeText = (time, info) => {
			const isPhysicalSite = !$scope.isVirtualAppointmentType();
			const appointmentsStillAvailable = info.appointmentsAvailable > 0;
			if (isPhysicalSite) {
				if (appointmentsStillAvailable) {
					return time;
				}
				return (time + ' - FULL' + ($scope.appointmentPickerSharedProperties.isLoggedIn ? ' - overscheduled by ' + Math.abs(info.appointmentsAvailable) + ' appointments' : ''));
			} else { // Virtual site
				if (appointmentsStillAvailable) {
					return time;
				}
				return ('No appointments available during the week of the selected date - FULL' + ($scope.appointmentPickerSharedProperties.isLoggedIn ? ' - overscheduled by ' + Math.abs(info.appointmentsAvailable) + ' appointments' : ''))
			}
		};

		$scope.isVirtualAppointmentType = () => {
			return $scope.appointmentPickerSharedProperties.appointmentType.includes('virtual-');
		};

		$scope.$watch(
			() => { return $scope.appointmentPickerSharedProperties.appointmentType; },
			(newValue, oldValue) => {
				$scope.getAppointments(newValue);
				$scope.appointmentPickerSharedProperties.selectedDate = null;
				$scope.appointmentPickerSharedProperties.selectedSite = null;
				$scope.appointmentPickerSharedProperties.selectedTime = null;
			}
		);

	}

	appointmentsController.$inject = ['$scope', 'appointmentPickerDataService', 'appointmentPickerSharedPropertiesService'];

	return appointmentsController;

});