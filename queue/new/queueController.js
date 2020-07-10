define('queueController', [], function() {

	// TODO: Organize methods/variables
	function queueController($scope, $interval, QueueDataService, DragulaService, NotificationUtilities) {

		$scope.today = new Date();
		$scope.currentDay = $scope.today.getDate();
		$scope.currentMonth = $scope.today.getMonth();
		$scope.currentYear = $scope.today.getFullYear();

		$scope.apppointments = [];
		$scope.sites = [];
		$scope.selectedAppointment = null;
		$scope.selectedSite = null;

		// Swimlane arrays
		$scope.awaitingAppointments = [];
		$scope.checkedInAppointments = [];
		$scope.paperworkCompletedAppointments = [];
		$scope.beingPreparedAppointments = [];
		$scope.completedAppointments = [];

		// Configure dragula options
		DragulaService.options($scope, 'queue-bag', { // Dragula call a collection of "swimlanes/containers" a "bag"
			accepts: (element, targetContainer, sourceContainer, sibling) => {
				// Elements can only be dropped in adjacent containers				
				const containerOrder = ['awaitingAppointmentsContainer', 'checkedInAppointmentsContainer', 'paperworkCompletedAppointmentsContainer', 'beingPreparedAppointmentsContainer', 'completedAppointmentsContainer'];
				if (targetContainer != null && sourceContainer != null) {
					const sourceIndex = containerOrder.indexOf(sourceContainer.id);
					const targetIndex = containerOrder.indexOf(targetContainer.id);
					return Math.abs(targetIndex - sourceIndex) <= 1;
				}

				return true; // Default to allowing the move
			},
		});

		$scope.$on('queue-bag.drop-model', (event, element, targetContainer, sourceContainer) => {
			const appointmentId = element[0].dataset.appointmentId;
			if (appointmentId == null) {
				return;
			}

			const sourceContainerId = sourceContainer[0].id;
			const targetContainerId = targetContainer[0].id;
			if (sourceContainerId === targetContainerId) { // Ignore the move if it's within a container
				return;
			}

			if (targetContainerId === 'awaitingAppointmentsContainer') {
				QueueDataService.markAppointmentAsAwaiting(appointmentId).then((response) => {
					// TODO: Consider pulling this helper method for displaying failure notification to a method
					if (response == null || !response.success) {
						const errorMessage = response ? response.error : 'There was an error on the server. Please refresh the page and try again.';
						NotificationUtilities.giveNotice('Failure', errorMessage, false);
					}
				});
			} else if (targetContainerId === 'checkedInAppointmentsContainer') {
				QueueDataService.markAppointmentAsCheckedIn(appointmentId).then((response) => {
					if (response == null || !response.success) {
						const errorMessage = response ? response.error : 'There was an error on the server. Please refresh the page and try again.';
						NotificationUtilities.giveNotice('Failure', errorMessage, false);
					}
				});
			} else if (targetContainerId === 'paperworkCompletedAppointmentsContainer') {
				QueueDataService.markAppointmentAsPaperworkCompleted(appointmentId).then((response) => {
					if (response == null || !response.success) {
						const errorMessage = response ? response.error : 'There was an error on the server. Please refresh the page and try again.';
						NotificationUtilities.giveNotice('Failure', errorMessage, false);
					}
				});
			} else if (targetContainerId === 'beingPreparedAppointmentsContainer') {
				QueueDataService.markAppointmentAsBeingPrepared(appointmentId).then((response) => {
					if (response == null || !response.success) {
						const errorMessage = response ? response.error : 'There was an error on the server. Please refresh the page and try again.';
						NotificationUtilities.giveNotice('Failure', errorMessage, false);
					}
				});
			} else if (targetContainerId === 'completedAppointmentsContainer') {
				QueueDataService.markAppointmentAsCompleted(appointmentId).then((response) => {
					if (response == null || !response.success) {
						const errorMessage = response ? response.error : 'There was an error on the server. Please refresh the page and try again.';
						NotificationUtilities.giveNotice('Failure', errorMessage, false);
					}
				});
			}
		});
	
		$scope.getAppointments = () => {
			// TODO: Pull this into helper method?
			let year = $scope.currentYear,
				month = $scope.currentMonth + 1,
				day = $scope.currentDay;
			if (month < 10) month = '0' + month;
			const isoFormattedDate = year + "-" + month + "-" + day;

			if ($scope.selectedSite == null || $scope.selectedSite.siteId == null) return;
			const siteId = $scope.selectedSite.siteId;

			QueueDataService.getAppointments(isoFormattedDate, siteId).then((response) => {
				if (response == null || !response.success) {
					const errorMessage = response ? response.error : 'There was an error on the server. Please refresh the page and try again.';
					NotificationUtilities.giveNotice('Failure', errorMessage, false);
					return;
				}
				
				// Only add appointments if 1) they don't exist in any swimlane, or 2) they've been moved already (by another volunteer)
				// I've ignored case 2 however, as I don't think it'll happen all too often, so it's not worth the extra logic
				response.appointments.forEach((appointment) => {
					const exists = $scope.appointments.some((appointment2) => appointment.appointmentId === appointment2.appointmentId);
					if (exists) return;

					if (appointment.timeAppointmentEnded != null || appointment.completed) {
						$scope.completedAppointments.push(appointment);
					} else if (appointment.timeAppointmentStarted != null) {
						$scope.beingPreparedAppointments.push(appointment);
					} else if (appointment.timeReturnedPapers != null) {
						$scope.paperworkCompletedAppointments.push(appointment);
					} else if (appointment.timeIn != null) {
						$scope.checkedInAppointments.push(appointment);
					} else {
						$scope.awaitingAppointments.push(appointment);
					}
				});

				$scope.appointments = response.appointments.map((appointment) => {
					appointment.name = appointment.firstName + ' ' + appointment.lastName;
					return appointment;
				});
			});
		};

		$scope.siteChanged = () => {
			$scope.resetSwimlanes();
			$scope.getAppointments();
			$scope.selectedAppointment = null;
		};

		$scope.dateChanged = () => {
			$scope.resetSwimlanes();
			$scope.getAppointments();
			$scope.selectedAppointment = null;
		};

		$scope.resetSwimlanes = () => {
			$scope.appointments = [];
			$scope.awaitingAppointments = [];
			$scope.checkedInAppointments = [];
			$scope.paperworkCompletedAppointments = [];
			$scope.beingPreparedAppointments = [];
			$scope.completedAppointments = [];
		};

		$scope.getSites = () => {
			QueueDataService.getSites().then((data) => {
				if (data == null) {
					NotificationUtilities.giveNotice('Failure', 'There was an error getting the sites. Please try refreshing the page.', false);
				} else {
					$scope.sites = data;
				}
			});
		};

		WDN.initializePlugin('jqueryui', [() => {
			require(['jquery'], ($) => {
				$('#dateInput').datepicker({
					dateFormat : 'mm/dd/yy',
					onSelect   : (dateTime, inst) => {
						$scope.currentDay = inst.currentDay;
						$scope.currentMonth = inst.currentMonth;
						$scope.currentYear = inst.currentYear;
						$scope.dateChanged();
					}
				}).datepicker('setDate', $scope.today);
			});
		}]);

		// Create interval to update appointment information every 10 seconds
		const appointmentInterval = $interval((() => {
			$scope.getAppointments();
		}).bind(this), 10000);

		// Destroy the intervals when we leave this page
		$scope.$on('$destroy', () => {
			$interval.cancel(appointmentInterval);
		});
		

		// Invoke initially
		$scope.getSites();

		// TODO: Appointments need to be loaded still every 15 seconds or so, and then only added to the
		// arrays if they are not already existent
	};

	queueController.$inject = ['$scope', '$interval', 'queueDataService', 'dragulaService', 'notificationUtilities'];

	return queueController;

});
