define('appointmentPickerController', [], function() {

	function appointmentsController($scope, AppointmentPickerDataService, sharedPropertiesService) {
		$scope.times = [];
		$scope.sites = [];
		$scope.dates = [];
		$scope.today = new Date();
		$scope.appointmentPickerSharedProperties = sharedPropertiesService.getSharedProperties();

		$scope.getAppointments = function(appointmentType = "residential", tenantName = "unl") {
			let year = new Date().getFullYear();
			AppointmentPickerDataService.loadAllAppointments(year, appointmentType, tenantName).then(function(result) {
				if(result == null) {
					alert('There was an error loading the appointments. Please try refreshing the page.');
				} else {
					$scope.dates = result.dates;
					$scope.appointmentPickerSharedProperties.hasAvailability = result.hasAvailability;
					$scope.appointmentPickerSharedProperties.isLoggedIn = result.isLoggedIn;
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
			$scope.appointmentPickerSharedProperties.selectedDate = dateInput;
			$scope.appointmentPickerSharedProperties.selectedSite = null;
			$scope.appointmentPickerSharedProperties.selectedTime = null;
			$scope.updateGlobalSites(dateInput);
		}

		$scope.siteChanged = function(site) {
			$scope.appointmentPickerSharedProperties.selectedSiteTitle = $scope.sites[site]['site_title'];
			$scope.appointmentPickerSharedProperties.isSelectedSiteVirtual = $scope.sites[site]['is_virtual'];
			$scope.appointmentPickerSharedProperties.selectedTime = null;
			$scope.updateGlobalTimes($scope.appointmentPickerSharedProperties.selectedDate, site);
		}

		$scope.timeChanged = function(time) {
			$scope.appointmentPickerSharedProperties.selectedAppointmentTimeId = $scope.times[time]['appointmentTimeId'];
		}

		$scope.getTimeText = (time, info) => {
			const isPhysicalSite = !$scope.appointmentPickerSharedProperties.isSelectedSiteVirtual;
			const appointmentsStillAvailable = info.appointmentsAvailable > 0;
			if (isPhysicalSite) {
				if (appointmentsStillAvailable) {
					return time;
				}
				return (time + ' - FULL' + ($scope.appointmentPickerSharedProperties.isLoggedIn ? ' - overscheduled by ' + Math.abs(info.appointmentsAvailable) + ' appointments' : ''));
			} else { // Virtual site
				if (appointmentsStillAvailable) {
					return 'No particular time--Your appointment will occur sometime during the week of the selected date.'
				}
				return ('No appointments available during the week of the selected date - FULL' + ($scope.appointmentPickerSharedProperties.isLoggedIn ? ' - overscheduled by ' + Math.abs(info.appointmentsAvailable) + ' appointments' : ''))
			}
		}

		$scope.$watch(
			() => $scope.appointmentPickerSharedProperties.appointmentType,
			(newValue, oldValue) => {
				$scope.getAppointments(newValue, $scope.appointmentPickerSharedProperties.tenantName);
				$scope.appointmentPickerSharedProperties.selectedDate = null;
				$scope.appointmentPickerSharedProperties.selectedSite = null;
				$scope.appointmentPickerSharedProperties.selectedTime = null;
			}
		);

		$scope.$watch(
			() => $scope.appointmentPickerSharedProperties.tenantName,
			(newValue, oldValue) => {
				$scope.getAppointments($scope.appointmentPickerSharedProperties.appointmentType, newValue);
				$scope.appointmentPickerSharedProperties.selectedDate = null;
				$scope.appointmentPickerSharedProperties.selectedSite = null;
				$scope.appointmentPickerSharedProperties.selectedTime = null;
			}
		);

	}

	appointmentsController.$inject = ['$scope', 'appointmentPickerDataService', 'appointmentPickerSharedPropertiesService'];

	return appointmentsController;

});