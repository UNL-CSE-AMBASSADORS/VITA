define('sitesController', [], function() {

	function sitesController($scope, SitesDataService) {
		const MINUTES_IN_DAY = 24 * 60;

		// For the site select list
		$scope.selectedSite = null;
		$scope.sites = [];

		// Contains site information after a site has been selected
		$scope.siteInformation = null;

		// For the add volunteer shift section
		$scope.addShiftButtonClicked = false;
		$scope.addShiftInformation = {};
		const TIME_INTERVAL = 30;
		$scope.addShiftStartTimeOptions = generateTimes(TIME_INTERVAL).map(time => time.timeString);
		$scope.addShiftEndTimeOptions = generateTimes(TIME_INTERVAL).map(time => time.timeString);

		// For the add appointment time section
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
				$scope.sites = result;
			});
		};

		$scope.selectSite = (site) => {
			$scope.selectedSite = site;
			$scope.siteInformation = null;

			const siteId = site.siteId;
			SitesDataService.getSiteInformation(siteId).then((result) => {
				result.site.doesMultilingual = result.site.doesMultilingual == true; // Do this since we want true/false instead of 1/0
				result.site.doesInternational = result.site.doesInternational == true; // Do this since we want true/false instead of 1/0
				$scope.siteInformation = result.site;
			});
		};
		
		$scope.addShiftButtonHandler = () => {
			$scope.initializeDatePicker('addShiftDateInput', (date) => {
				$scope.addShiftInformation.selectedDate = date;
				$scope.$apply();
			}, (date) => $scope.isDateInPast(date));
			$scope.addShiftButtonClicked = true;
		};

		$scope.addShiftSaveButtonHandler = () => {
			const shiftInformation = $scope.addShiftInformation;

			const siteId = $scope.selectedSite.siteId;
			const date = shiftInformation.selectedDate;
			const startTime = shiftInformation.selectedStartTime;
			const endTime = shiftInformation.selectedEndTime;
			SitesDataService.addShift(siteId, date, startTime, endTime).then((result) => {
				if (result && !result.success) {
					alert(result.error);
					return;
				}

				$scope.siteInformation.shifts.push({
					'shiftId': result.shiftId,
					'dateString': date,
					'startTimeString': startTime,
					'endTimeString': endTime
				});
				$scope.addShiftInformation = {};
				$scope.addShiftButtonClicked = false;
			});
		};

		$scope.addShiftCancelButtonHandler = () => {
			$scope.addShiftInformation = {};
			$scope.addShiftButtonClicked = false;
		};

		$scope.addAppointmentTimeButtonHandler = () => {
			const isShiftDateEqualToDate = (shiftDate, date) => {
				return new Date(new Date(shiftDate).toDateString()).getTime() === new Date(date.toDateString()).getTime();
			};

			$scope.initializeDatePicker('addAppointmentTimeDateInput', (date) => {
				$scope.addAppointmentTimeInformation.selectedDate = date;
				const shiftsOnThisDay = $scope.siteInformation.shifts
					.filter(shift => isShiftDateEqualToDate(shift.startTime, new Date(date)));

				const times = $scope.getAvailableAppointmentTimes(shiftsOnThisDay);
				$scope.addAppointmentTimeScheduledTimeOptions = times.map(time => time.timeString);
				
				$scope.$apply();
			}, (date) => {
				const isDateInPast = $scope.isDateInPast(date);
				const anyShiftsOnThisDay = $scope.siteInformation.shifts
					.some(shift => isShiftDateEqualToDate(shift.startTime, date));
				return isDateInPast || !anyShiftsOnThisDay;
			});
			$scope.addAppointmentTimeButtonClicked = true;
		};

		$scope.getAvailableAppointmentTimes = (shifts) => {
			const getTime = (time) => {
				const dateTime = new Date(time);
				return dateTime.getHours() * 60 + dateTime.getMinutes();
			};

			let times = [];

			// Get the possible times within each shift
			for (const shift of shifts) {
				const startTime = getTime(shift.startTime);
				const endTime = getTime(shift.endTime);

				const generatedTimes = generateTimes(TIME_INTERVAL, startTime, endTime);
				for (const generatedTime of generatedTimes) {
					const containsAlready = times.some(time => time.time == generatedTime.time);
					if (!containsAlready) {
						times.push(generatedTime);
					}
				}
			}

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
			const appointmentTimeInformation = $scope.addAppointmentTimeInformation;
			
			const siteId = $scope.selectedSite.siteId;
			const date = appointmentTimeInformation.selectedDate;
			const scheduledTime = appointmentTimeInformation.selectedScheduledTime;
			const minimumNumberOfAppointments = appointmentTimeInformation.minimumNumberOfAppointments;
			const maximumNumberOfAppointments = appointmentTimeInformation.maximumNumberOfAppointments;
			const percentageAppointments = appointmentTimeInformation.percentageAppointments;
			const approximateLengthInMinutes = appointmentTimeInformation.approximateLengthInMinutes;

			SitesDataService.addAppointmentTime(siteId, date, scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, percentageAppointments, approximateLengthInMinutes).then((result) => {
				if (result && !result.success) {
					alert(result.error);
					return;
				}

				$scope.siteInformation.appointmentTimes.push({
					'appointmentTimeId': result.appointmentTimeId,
					'scheduledTimeString': `${date} ${scheduledTime}`,
					'minimumNumberOfAppointments': minimumNumberOfAppointments,
					'maximumNumberOfAppointments': maximumNumberOfAppointments,
					'percentageAppointments': percentageAppointments,
					'approximateLengthInMinutes': approximateLengthInMinutes
				});
				$scope.addAppointmentTimeInformation = DEFAULT_ADD_APPOINTMENT_TIME_INFORMATION;
				$scope.addAppointmentTimeButtonClicked = false;
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

		$scope.isDateInPast = (date) => {
			return new Date(date.toDateString()) < new Date(new Date().toDateString());
		};

		// Time generation code adapted from https://stackoverflow.com/a/36126706/3732003
		function generateTimes(interval, startTime = 0, endTime = MINUTES_IN_DAY) {
			const times = [];
			let time = startTime; // Start at 00:00 (12:00 AM)
			const periods = ['AM', 'PM'];

			for (let i = 0; time < endTime; i++) {
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

		$scope.addShiftStartTimeChanged = (newStartTime) => {
			// Change end time options to only be the ones past the start time
			const period = newStartTime.slice(-2);
			const hourString = newStartTime.split(':')[0];
			const minuteString = newStartTime.split(':')[1];

			let hour = parseInt(hourString);
			if (period === 'AM' && hour === 12) hour = 0;
			if (period === 'PM' && hour !== 12) hour += 12;
			const minute = parseInt(minuteString);
			const time = hour * 60 + minute + TIME_INTERVAL; // + TIME_INTERVAL so the same time can't be selected

			$scope.addShiftEndTimeOptions = generateTimes(TIME_INTERVAL, time).map(time => time.timeString);
		};

		// Invoke initially
		$scope.loadSites();
	}

	sitesController.$inject = ['$scope', 'sitesDataService'];

	return sitesController;

});
