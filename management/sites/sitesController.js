define('sitesController', [], function() {

	function sitesController($scope, SitesDataService, NotificationUtilities) {
		const MINUTES_IN_DAY = 24 * 60;
		const TIME_INTERVAL = 30; // The default interval between displayed times for an AppointmentTime
		const START_TIME = 60 * 7; // The first allowable time for a new appointment time (this is only for UX convenience)
		const END_TIME_INCLUSIVE = 60 * 22; // The last allowable time for a new appointment time (this is only for UX convenience)

		// For the site select list
		$scope.selectedSite = null;
		$scope.sites = [];

		// Contains site information after a site has been selected
		$scope.siteInformation = null;

		// For the add appointment time section
		$scope.savingAppointmentTime = false;
		$scope.addAppointmentTimeButtonClicked = false;
		$scope.DEFAULT_ADD_APPOINTMENT_TIME_INFORMATION = () => ({
			numberOfAppointments: 0
		});
		$scope.addAppointmentTimeInformation = $scope.DEFAULT_ADD_APPOINTMENT_TIME_INFORMATION();
		// TODO: Consider not showing times that aren't allowable based on the type+date
		$scope.addAppointmentTimeScheduledTimeOptions = generateTimes(TIME_INTERVAL, START_TIME, END_TIME_INCLUSIVE, true)
			.sort((time1, time2) => time1 - time2) // Sort in ascending order
			.map((time) => time.timeString);
		$scope.appointmentTypes = [];

		$scope.appointmentTimesEditMap = new Map();


		$scope.loadSites = () => {
			SitesDataService.getSites().then((result) => {
				if (result == null) {
					NotificationUtilities.giveNotice('Failure', 'Unable to load the sites.', false);
					return;
				}
				$scope.sites = result;
			});
		};

		$scope.getAppointmentTypes = () => {
			SitesDataService.getAppointmentTypes().then((result) => {
				if (result == null) {
					NotificationUtilities.giveNotice('Failure', 'Unable to load appointment types from server. Please reload the page.', false);
				}
				$scope.appointmentTypes = result;
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

				$scope.siteInformation = result.site;
				$scope.createAppointmentTimesMap();
			});
		};

		$scope.createAppointmentTimesMap = () => {
			// Maps date -> AppointmentTimes for that date
			$scope.siteInformation.appointmentTimesMap = new Map();
			for (const appointmentTime of $scope.siteInformation.appointmentTimes) {
				if (!$scope.siteInformation.appointmentTimesMap.has(appointmentTime.scheduledDateString)) {
					$scope.siteInformation.appointmentTimesMap.set(appointmentTime.scheduledDateString, []);
				}
				$scope.siteInformation.appointmentTimesMap.get(appointmentTime.scheduledDateString).push(appointmentTime);
			}
		};

		$scope.addAppointmentTimeButtonHandler = () => {
			$scope.initializeDatePicker('addAppointmentTimeDateInput', (date) => {
				$scope.addAppointmentTimeInformation.selectedDate = date;
				$scope.$apply();
			}, (date) => isDateInPast(date));
			$scope.addAppointmentTimeButtonClicked = true;
		};

		$scope.addAppointmentTimeSaveButtonHandler = () => {
			if ($scope.savingAppointmentTime) {
				return;
			}
			$scope.savingAppointmentTime = true;

			const appointmentTimeInformation = $scope.addAppointmentTimeInformation;
			
			const siteId = $scope.siteInformation.siteId;
			const date = appointmentTimeInformation.selectedDate;
			const scheduledTime = appointmentTimeInformation.selectedScheduledTime;
			const appointmentTypeId = appointmentTimeInformation.selectedAppointmentTypeId;
			const numberOfAppointments = appointmentTimeInformation.numberOfAppointments;

			SitesDataService.addAppointmentTime(siteId, date, scheduledTime, appointmentTypeId, numberOfAppointments).then((result) => {
				if (result == null || !result.success) {
					const errorMessage = result.error || 'There was an error saving the appointment time. Please refresh and try again.';
					NotificationUtilities.giveNotice('Failure', errorMessage, false);
				} else {
					$scope.updateAppointmentTimesTable();
					$scope.addAppointmentTimeInformation = $scope.DEFAULT_ADD_APPOINTMENT_TIME_INFORMATION();
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
					$scope.createAppointmentTimesMap();
				}
			});
		};

		$scope.addAppointmentTimeCancelButtonHandler = () => {
			$scope.addAppointmentTimeInformation = $scope.DEFAULT_ADD_APPOINTMENT_TIME_INFORMATION();
			$scope.addAppointmentTimeButtonClicked = false;
		};

		$scope.isAppointmentTimeRowBeingEdited = (appointmentTimeId) => {
			return $scope.appointmentTimesEditMap.has(appointmentTimeId) && $scope.appointmentTimesEditMap.get(appointmentTimeId).editing;
		};

		$scope.editAppointmentTimesEditButtonHandler = (appointmentTime) => {
			$scope.appointmentTimesEditMap.set(appointmentTime.appointmentTimeId, { 
				editing: true, 
				newNumberOfAppointments: parseInt(appointmentTime.numberOfAppointments) 
			});
		};

		$scope.editAppointmentTimeSaveButtonHandler = (appointmentTime) => {
			const appointmentTimeId = appointmentTime.appointmentTimeId;
			const newNumberOfAppointments = $scope.appointmentTimesEditMap.get(appointmentTimeId).newNumberOfAppointments;
			SitesDataService.updateAppointmentTime(appointmentTimeId, newNumberOfAppointments).then((result) => {
				if (result == null || !result.success) {
					const errorMessage = result.error || 'There was an error updating the appointment time. Please refresh the page.';
					NotificationUtilities.giveNotice('Failure', errorMessage, false);
				} else {
					appointmentTime.numberOfAppointments = newNumberOfAppointments;
					$scope.appointmentTimesEditMap.get(appointmentTimeId).editing = false;
				}
			});
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
		$scope.getAppointmentTypes();
	};

	sitesController.$inject = ['$scope', 'sitesDataService', 'notificationUtilities'];

	return sitesController;

});
