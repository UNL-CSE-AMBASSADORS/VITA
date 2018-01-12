define('appointmentPickerController', [], function() {

	function appointmentsController($scope, AppointmentPickerDataService) {
		$scope.times = [];
		$scope.sites = [];
		$scope.dates = [];
		$scope.today = new Date();
		$scope.selectedDate = [];

		$scope.getAppointments = function() {
			let year = new Date().getFullYear();
			AppointmentPickerDataService.loadAllAppointments(year).then(function(result) {
				if(result == null) {
					alert('There was an error loading the appointments. Please try refreshing the page.');
				} else {
					$scope.dates = result;
					WDN.initializePlugin('jqueryui', [function () {
						require(['jquery'], function($){
							$("#dateInput").datepicker({
								dateFormat : 'mm/dd/yy',
								onSelect   : function(dateTime, inst) {
									// Update the currentDay, currentMonth, currentYear variables with values from in the inst variable
									$scope.dateChanged(dateTime);
								},
								// Good example: https://stackoverflow.com/a/1962849/7577035
								// called for every date before it is displayed
								beforeShowDay: function(date) {
									if (dateSitesTimes.hasDate(date)) {
										if (dateSitesTimes.hasTimeSlotsRemaining(date)) {
											return [true, ''];
										} else {
											return [false, 'full'];
										}
									} else {
										return [false, ''];
									}
								},
								beforeShow: function() {
									setTimeout(function(){
										angular.element('.ui-datepicker').css('z-index', 100);
									}, 0);
								}
							}).datepicker( "setDate", $scope.today );
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
			let localSites = $scope.dates[date]["sites"];
			$scope.sites = [];
			for (let site in localSites) {
				$scope.sites.push({
					"siteId": site,
					"title": localSites[site]["site_title"],
					"hasTimeSlotsRemaining": localSites[site]["hasAvailability"]
				});
			}
		}
	
		$scope.updateGlobalTimes = function(dateInput, site) {
			let dateObj = new Date(dateInput);
			let date  = dateObj.toISOString().substring(0, 10);
			$scope.times = $scope.dates[date]["sites"][site]["times"];
		}

		$scope.dateChanged = function(dateInput) {
			$scope.selectedDate = dateInput;
			$scope.updateGlobalSites(dateInput);
		}

		$scope.siteChanged = function(site) {
			$scope.updateGlobalTimes($scope.selectedDate, site)
		}
	}

	appointmentsController.$inject = ['$scope', 'appointmentPickerDataService'];

	return appointmentsController;

});