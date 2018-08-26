define('sitesController', [], function() {

	function sitesController($scope, SitesDataService) {
		// For the site select list
		$scope.selectedSite = null;
		$scope.sites = [];

		// Contains site information after a site has been selected
		$scope.siteInformation = null;

		// For the add volunteer shift section
		$scope.addShiftButtonClicked = false;
		$scope.addShiftInformation = {};
		const TIME_INTERVAL = 30;
		$scope.startTimeOptions = generateTimes(TIME_INTERVAL);
		$scope.endTimeOptions = generateTimes(TIME_INTERVAL);

		// Time generation code adapted from https://stackoverflow.com/a/36126706/3732003
		function generateTimes(interval, startTime = 0) {
			const times = [];
			let time = startTime; // Start at 00:00 (12:00 AM)
			const periods = ['AM', 'PM'];
			const MINUTES_IN_DAY = 24 * 60;

			for (let i = 0; time < MINUTES_IN_DAY; i++) {
				const hour = Math.floor(time / 60); // Get hours of day in 0-23 format
				const minute = (time % 60); // Get minutes of the hour in 0-59 format
				const hourString = ((hour === 12 || hour === 0) ? 12 : hour % 12); // Transform to 1-12 format
				const minuteString = ('0' + minute).slice(-2); // Append a 0 to minute if necessary
				const periodString = periods[Math.floor(hour / 12)];

				times[i] = `${hourString}:${minuteString} ${periodString}`
				time = time + interval;
			}

			return times;
		}

		$scope.startTimeChanged = function(newStartTime) {
			// Change end time options to only be the ones past the start time
			const period = newStartTime.slice(-2);
			const hourString = newStartTime.split(':')[0];
			const minuteString = newStartTime.split(':')[1];

			const hour = parseInt(hourString) + (period === 'PM' ? 12 : 0);
			const minute = parseInt(minuteString);
			const time = hour * 60 + minute + TIME_INTERVAL; // + TIME_INTERVAL so the same time can't be selected

			$scope.endTimeOptions = generateTimes(TIME_INTERVAL, time);
		}

		$scope.loadSites = function() {
			SitesDataService.getSites().then((result) => {
				$scope.sites = result;
			});
		};

		$scope.selectSite = function(site) {
			$scope.selectedSite = site;
			$scope.siteInformation = null;

			const siteId = site.siteId;
			SitesDataService.getSiteInformation(siteId).then((result) => {
				result.site.doesMultilingual = result.site.doesMultilingual == true; // Do this since we want true/false instead of 1/0
				result.site.doesInternational = result.site.doesMultilingual == true; // Do this since we want true/false instead of 1/0
				$scope.siteInformation = result.site;
			});
		};

		$scope.addShiftButtonHandler = function() {
			console.log('HIT ADD SHIFT BUTTON');
			$scope.initializeDatePicker();
			$scope.addShiftButtonClicked = true;

		};

		$scope.addShiftSaveButtonHandler = function() {
			console.log('SAVE BUTTON HIT');
			console.log($scope.addShiftInformation);
			$scope.addShiftButtonClicked = false;
		};

		$scope.addShiftCancelButtonHandler = function() {
			console.log('CANCEL BUTTON HIT');
			$scope.addShiftButtonClicked = false;
		};

		$scope.initializeDatePicker = function() {
			WDN.initializePlugin('jqueryui', [function () {
				require(['jquery'], function($){
					$("#dateInput").datepicker({
						dateFormat : 'mm/dd/yy',
						onSelect   : function(dateTime, inst) {
							$scope.addShiftInformation.selectedDate = dateTime;
							$scope.$apply();
						},
						// Good example: https://stackoverflow.com/a/1962849/7577035
						// called for every date before it is displayed
						beforeShowDay: function(date) {
							const isDateBeforeToday = new Date(date.toDateString()) < new Date(new Date().toDateString());
							if (isDateBeforeToday) {
								return [false, 'past'];
							}
							return [true, ''];
						}
					});
				});
			}]);
		};

		// Invoke initially
		$scope.loadSites();
		WDN.initializePlugin('tooltip');
	}

	sitesController.$inject = ['$scope', 'sitesDataService'];

	return sitesController;

});
