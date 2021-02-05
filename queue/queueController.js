define('queueController', [], function() {

	function queueController($scope, $interval, QueueDataService, AppointmentNotesAreaSharedPropertiesService, DragulaService, NotificationUtilities) {

		$scope.today = new Date();
		$scope.currentDay = $scope.today.getDate();
		$scope.currentMonth = $scope.today.getMonth();
		$scope.currentYear = $scope.today.getFullYear();

		$scope.sites = [];
		$scope.selectedAppointment = null;
		$scope.selectedSite = null;

		$scope.clientSearchString = '';

		// Swimlane arrays
		$scope.appointments = []; // All references to an appointment are stored in this array, and each swimlane array contains a shallow reference to the object.
		$scope.awaitingAppointments = [];
		$scope.checkedInAppointments = [];
		$scope.paperworkCompletedAppointments = [];
		$scope.beingPreparedAppointments = [];
		$scope.completedAppointments = [];

		$scope.appointmentNotesAreaSharedProperties = AppointmentNotesAreaSharedPropertiesService.getSharedProperties();

		// Configure dragula options
		DragulaService.options($scope, 'queue-bag', { // Dragula call a collection of "swimlanes/containers" a "bag"
			accepts: (element, targetContainer, sourceContainer, sibling) => {
				// Make it so elements can only be dropped in adjacent containers				
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
				$scope.markAppointmentAsAwaiting(appointmentId);
			} else if (targetContainerId === 'checkedInAppointmentsContainer') {
				$scope.markAppointmentAsCheckedIn(appointmentId);
			} else if (targetContainerId === 'paperworkCompletedAppointmentsContainer') {
				$scope.markAppointmentAsPaperworkCompleted(appointmentId);
			} else if (targetContainerId === 'beingPreparedAppointmentsContainer') {
				$scope.markAppointmentAsBeingPrepared(appointmentId);
			} else if (targetContainerId === 'completedAppointmentsContainer') {
				$scope.markAppointmentAsCompleted(appointmentId);
			}
		});

		$scope.getAppointments = () => {
			let year = $scope.currentYear,
				month = $scope.currentMonth + 1,
				day = $scope.currentDay;
			if (month < 10) month = '0' + month;
			const isoFormattedDate = year + "-" + month + "-" + day;

			if ($scope.selectedSite == null || $scope.selectedSite.siteId == null) {
				return;
			}
			const siteId = $scope.selectedSite.siteId;

			QueueDataService.getAppointments(isoFormattedDate, siteId)
				.then($scope.checkResponseForError)
				.catch($scope.notifyOfError)
				.then((response) => {
					const appointments = response.appointments.map((appointment) => {
						appointment.name = appointment.firstName + ' ' + appointment.lastName;
						appointment.checkedIn = appointment.timeIn != null;
						appointment.paperworkComplete = appointment.timeReturnedPapers != null;
						appointment.preparing = appointment.timeAppointmentStarted != null;
						appointment.ended = appointment.timeAppointmentEnded != null;
						return appointment;
					});
					
					// Only add appointments if 1) they don't exist in any swimlane, or 2) they've been moved/removed already (by another volunteer)
					// I've ignored case 2 however, as I don't think it'll happen all too often, so it's not worth the extra logic
					appointments.forEach((appointment) => {
						const exists = $scope.appointments.some((appointment2) => appointment.appointmentId === appointment2.appointmentId);
						if (exists) return;

						$scope.appointments.push(appointment);
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
				});
		};

		$scope.getSites = () => {
			QueueDataService.getSites()
				.then($scope.checkResponseForError)
				.catch($scope.notifyOfError)
				.then((data) => {
					$scope.sites = data;
				});
		};

		$scope.markAppointmentAsAwaiting = (appointmentId) => {
			QueueDataService.markAppointmentAsAwaiting(appointmentId)
				.then($scope.checkResponseForError)
				.catch($scope.notifyOfError)
				.then((response) => {
					const appointment = $scope.appointments.find((appointment) => appointment.appointmentId === appointmentId);
					appointment.checkedIn = false;
				});
		};

		$scope.markAppointmentAsCheckedIn = (appointmentId) => {
			QueueDataService.markAppointmentAsCheckedIn(appointmentId)
				.then($scope.checkResponseForError)
				.catch($scope.notifyOfError)
				.then((response) => {
					const appointment = $scope.appointments.find((appointment) => appointment.appointmentId === appointmentId);
					appointment.checkedIn = true;
					appointment.paperworkComplete = false;
					appointment.noShow = false;
				});
		};

		$scope.markAppointmentAsPaperworkCompleted = (appointmentId) => {
			QueueDataService.markAppointmentAsPaperworkCompleted(appointmentId)
				.then($scope.checkResponseForError)
				.catch($scope.notifyOfError)
				.then((response) => {
					const appointment = $scope.appointments.find((appointment) => appointment.appointmentId === appointmentId);
					appointment.paperworkComplete = true;
					appointment.preparing = false;
				});
		};

		$scope.markAppointmentAsBeingPrepared = (appointmentId) => {
			QueueDataService.markAppointmentAsBeingPrepared(appointmentId)
				.then($scope.checkResponseForError)
				.catch($scope.notifyOfError)
				.then((response) => {
					const appointment = $scope.appointments.find((appointment) => appointment.appointmentId === appointmentId);
					appointment.preparing = true;
					appointment.ended = false;
				});
		};

		$scope.markAppointmentAsCompleted = (appointmentId) => {
			QueueDataService.markAppointmentAsCompleted(appointmentId)
				.then($scope.checkResponseForError)
				.catch($scope.notifyOfError)
				.then((response) => {
					const appointment = $scope.appointments.find((appointment) => appointment.appointmentId === appointmentId);
					appointment.ended = true;
				});
		};

		$scope.markAppointmentAsIncomplete = () => {
			const appointmentId = $scope.selectedAppointment.appointmentId;
			QueueDataService.markAppointmentAsIncomplete(appointmentId)
				.then($scope.checkResponseForError)
				.catch($scope.notifyOfError)
				.then((response) => {
					$scope.selectedAppointment.ended = true;
					$scope.removeAppointmentFromSwimlanes(appointmentId);
				});
		};

		$scope.markAppointmentAsCancelled = () => {
			const appointmentId = $scope.selectedAppointment.appointmentId;
			QueueDataService.markAppointmentAsCancelled(appointmentId)
				.then($scope.checkResponseForError)
				.catch($scope.notifyOfError)
				.then((response) => {
					$scope.selectedAppointment.ended = true;
					$scope.selectedAppointment.cancelled = true;
					$scope.removeAppointmentFromSwimlanes(appointmentId);
				});
		};

		$scope.selectAppointment = (appointment) => {
			$scope.selectedAppointment = appointment;
			$scope.appointmentNotesAreaSharedProperties.appointmentId = $scope.selectedAppointment.appointmentId;
		};

		$scope.deselectAppointment = () => {
			$scope.selectedAppointment = null;
		};

		$scope.siteChanged = () => {
			$scope.resetSwimlanes();
			$scope.getAppointments();
		};

		$scope.dateChanged = () => {
			$scope.resetSwimlanes();
			$scope.getAppointments();
		};

		$scope.resetSwimlanes = () => {
			$scope.appointments = [];
			$scope.awaitingAppointments = [];
			$scope.checkedInAppointments = [];
			$scope.paperworkCompletedAppointments = [];
			$scope.beingPreparedAppointments = [];
			$scope.completedAppointments = [];
		};

		$scope.removeAppointmentFromSwimlanes = (appointmentId) => {
			$scope.appointments = $scope.appointments.filter((appointment) => appointment.appointmentId != appointmentId);
			$scope.awaitingAppointments = $scope.awaitingAppointments.filter((appointment) => appointment.appointmentId != appointmentId);
			$scope.checkedInAppointments = $scope.checkedInAppointments.filter((appointment) => appointment.appointmentId != appointmentId);
			$scope.paperworkCompletedAppointments = $scope.paperworkCompletedAppointments.filter((appointment) => appointment.appointmentId != appointmentId);
			$scope.beingPreparedAppointments = $scope.beingPreparedAppointments.filter((appointment) => appointment.appointmentId != appointmentId);
			$scope.completedAppointments = $scope.completedAppointments.filter((appointment) => appointment.appointmentId != appointmentId);
		};

		$scope.passesSearchFilter = (appointment) => {
			const searchString = $scope.clientSearchString.toLowerCase();
			if (!searchString) return true;
			return appointment.name.toLowerCase().indexOf(searchString) !== -1 ||
				appointment.appointmentId.toString().indexOf(searchString) !== -1;
		};

		$scope.checkResponseForError = (response) => {
			if (response == null || (!!response.success && !response.success)) {
				const errorMessage = (response && response.error) ? response.error : 'There was an error on the server. Please refresh the page and try again.';
				throw new Error(errorMessage);
			}
			return response;
		};

		$scope.notifyOfError = (errorMessage) => {
			NotificationUtilities.giveNotice('Failure', errorMessage, false)
		};

		// Initialize the date picker plugin
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

		// Create interval to update appointment information every 15 seconds
		const appointmentInterval = $interval((() => {
			$scope.getAppointments();
		}).bind(this), 15000);

		// Destroy the intervals when we leave this page
		$scope.$on('$destroy', () => {
			$interval.cancel(appointmentInterval);
		});
		

		// Invoke initially
		$scope.getSites();
	};

	queueController.$inject = ['$scope', '$interval', 'queueDataService', 'appointmentNotesAreaSharedPropertiesService', 'dragulaService', 'notificationUtilities'];

	return queueController;

});
