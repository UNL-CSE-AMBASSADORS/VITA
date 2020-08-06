define('sitesController', [], function() {

	function sitesController($scope, SitesDataService, NotificationUtilities) {
		const MINUTES_IN_DAY = 24 * 60;
		const TIME_INTERVAL = 30; // The default interval between displayed times for an AppointmentTime

		// For the site select list
		$scope.selectedSite = null;
		$scope.sites = [];

		// Contains site information after a site has been selected
		$scope.siteInformation = null;

		// For the add appointment time section
		$scope.savingAppointmentTime = false;
		$scope.addAppointmentTimeButtonClicked = false;
		const DEFAULT_ADD_APPOINTMENT_TIME_INFORMATION = {
			minimumNumberOfAppointments: 0,
			maximumNumberOfAppointments: null,
			percentageAppointments: 100,
			approximateLengthInMinutes: 60
		};
		$scope.addAppointmentTimeInformation = DEFAULT_ADD_APPOINTMENT_TIME_INFORMATION;
		$scope.addAppointmentTimeScheduledTimeOptions = [];


		$scope.loadSites = () => {
			SitesDataService.getSites().then((result) => {
				if (result == null) {
					NotificationUtilities.giveNotice('Failure', 'Unable to load the sites.', false);
					return;
				}

				$scope.sites = result;
			});
		};

		$scope.selectSite = (site) => {
			$scope.selectedSite = site;
			$scope.siteInformation = null;

			const siteId = site.siteId;
			SitesDataService.getSiteInformation(siteId).then((result) => {
				if (result == null || !result.success) {
					const errorMessage = result.error || 'There was an error loading site information. Please refresh and try again.';
					NotificationUtilities.giveNotice('Failure', errorMessage, false);
					return;
				}

				result.site.doesInternational = result.site.doesInternational == true; // Do this since we want true/false instead of 1/0
				$scope.siteInformation = result.site;
			});
		};

		$scope.addAppointmentTimeButtonHandler = () => {
			$scope.initializeDatePicker('addAppointmentTimeDateInput', (date) => {
				$scope.addAppointmentTimeInformation.selectedDate = date;
				$scope.updateAddAppointmentTimeScheduledTimeOptions();
				$scope.$apply();
			}, (date) => isDateInPast(date));
			$scope.addAppointmentTimeButtonClicked = true;
		};

		$scope.addAppointmentTimeApproximateLengthInMinutesChanged = (newApproximateLengthInMinutes) => {
			$scope.addAppointmentTimeInformation.approximateLengthInMinutes = newApproximateLengthInMinutes;
			$scope.updateAddAppointmentTimeScheduledTimeOptions();
		};
 
		$scope.updateAddAppointmentTimeScheduledTimeOptions = () => {
			const timeInterval = $scope.addAppointmentTimeInformation.approximateLengthInMinutes;
			if (timeInterval == null || timeInterval <= 0) {
				$scope.addAppointmentTimeScheduledTimeOptions = [];
				return;
			}

			const times = $scope.getAvailableAppointmentTimes(timeInterval);
			$scope.addAppointmentTimeScheduledTimeOptions = times.map(time => time.timeString);
		};

		$scope.getAvailableAppointmentTimes = (timeInterval) => {
			const getTime = (time) => {
				const dateTime = new Date(time);
				return dateTime.getHours() * 60 + dateTime.getMinutes();
			};

			let times = generateTimes(TIME_INTERVAL);

			// Remove any times that already have an appointment time with that time
			for (const appointmentTime of $scope.siteInformation.appointmentTimes) {
				const scheduledTime  = getTime(appointmentTime.scheduledTime);
				times = times.filter(time => time.time != scheduledTime);
			}

			// Sort the times in ascending order
			times.sort((time1, time2) => time1 - time2);

			return times;
		}

		$scope.addAppointmentTimeSaveButtonHandler = () => {
			if ($scope.savingAppointmentTime) {
				return;
			}
			$scope.savingAppointmentTime = true;

			const appointmentTimeInformation = $scope.addAppointmentTimeInformation;
			
			const siteId = $scope.siteInformation.siteId;
			const date = appointmentTimeInformation.selectedDate;
			const scheduledTime = appointmentTimeInformation.selectedScheduledTime;
			const minimumNumberOfAppointments = appointmentTimeInformation.minimumNumberOfAppointments;
			const maximumNumberOfAppointments = appointmentTimeInformation.maximumNumberOfAppointments;
			const percentageAppointments = appointmentTimeInformation.percentageAppointments;
			const approximateLengthInMinutes = appointmentTimeInformation.approximateLengthInMinutes;

			SitesDataService.addAppointmentTime(siteId, date, scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, percentageAppointments, approximateLengthInMinutes).then((result) => {
				if (result == null || !result.success) {
					const errorMessage = result.error || 'There was an error saving the appointment time. Please refresh and try again.';
					NotificationUtilities.giveNotice('Failure', errorMessage, false);
				} else {
					$scope.updateAppointmentTimesTable();
					$scope.addAppointmentTimeInformation = DEFAULT_ADD_APPOINTMENT_TIME_INFORMATION;
					$scope.addAppointmentTimeButtonClicked = false;
	
					NotificationUtilities.giveNotice('Success', 'The appointment time was successfully created');
				}

				$scope.savingAppointmentTime = false;				
			});
		};

		$scope.updateAppointmentTimesTable = () => {
			const siteId = $scope.siteInformation.siteId;

			SitesDataService.getAppointmentTimesForSite(siteId).then((result) => {
				if (result == null || !result.success) {
					const errorMessage = result.error || 'There was an error retrieving the appointment times. Please refresh the page.';
					NotificationUtilities.giveNotice('Failure', errorMessage, false);
				} else {
					$scope.siteInformation.appointmentTimes = result.appointmentTimes;
				}
			});
		};

		$scope.addAppointmentTimeCancelButtonHandler = () => {
			$scope.addAppointmentTimeInformation = DEFAULT_ADD_APPOINTMENT_TIME_INFORMATION;
			$scope.addAppointmentTimeButtonClicked = false;
		};

		$scope.initializeDatePicker = (elementId, onSelectCallback, beforeShowDayPredicate) => {
			WDN.initializePlugin('jqueryui', [function () {
				require(['jquery'], function($){
					$(`#${elementId}`).datepicker({
						dateFormat : 'mm-dd-yy',
						onSelect   : onSelectCallback,
						// Good example: https://stackoverflow.com/a/1962849/7577035
						// called for every date before it is displayed
						beforeShowDay: function(date) {
							const dateShouldBeDisabled = beforeShowDayPredicate(date);

							if (dateShouldBeDisabled) {
								return [false, 'past'];
							}
							return [true, ''];
						}
					});
				});
			}]);
		};

		function isDateInPast(date) {
			return new Date(date.toDateString()) < new Date(new Date().toDateString());
		};

		// Time generation code adapted from https://stackoverflow.com/a/36126706/3732003
		function generateTimes(interval, startTime = 0, endTime = MINUTES_IN_DAY, endTimeInclusive = false) {
			const times = [];
			let time = startTime; // Start at 00:00 (12:00 AM)
			const periods = ['AM', 'PM'];

			for (let i = 0; endTimeInclusive ? (time <= endTime) : (time < endTime); i++) {
				const hour = Math.floor(time / 60); // Get hours of day in 0-23 format
				const minute = (time % 60); // Get minutes of the hour in 0-59 format
				const hourString = ((hour === 12 || hour === 0) ? 12 : hour % 12); // Transform to 1-12 format
				const minuteString = ('0' + minute).slice(-2); // Append a 0 to minute if necessary
				const periodString = periods[Math.floor(hour / 12)];

				times[i] = { 
					timeString: `${hourString}:${minuteString} ${periodString}`,
					time: time
				};
				time = time + interval;
			}

			return times;
		};


		// Invoke initially
		$scope.loadSites();
	};

	sitesController.$inject = ['$scope', 'sitesDataService', 'notificationUtilities'];

	return sitesController;

});
