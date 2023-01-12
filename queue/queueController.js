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

		$scope.pools = {}
		$scope.previousAppointmentIds = []
		// Swimlane arrays
		//$scope.appointments = []; // All references to an appointment are stored in this array, and each swimlane array contains a shallow reference to the object.
	


		$scope.appointmentNotesAreaSharedProperties = AppointmentNotesAreaSharedPropertiesService.getSharedProperties();

		// Configure dragula options
		DragulaService.options($scope, 'queue-bag', { // Dragula call a collection of "swimlanes/containers" a "bag"
			//accepts: (element, targetContainer, sourceContainer, sibling) => {
			accepts: (element, targetIndex, sourceIndex, sibling) => {
				// Make it so elements can only be dropped in adjacent containers				
				const containerOrder = ['awaitingAppointmentsContainer', 'checkedInAppointmentsContainer', 'paperworkCompletedAppointmentsContainer', 'beingPreparedAppointmentsContainer', 'completedAppointmentsContainer'];
				// targetContainer is now a swimlane, or a step
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

		// have to pull these upfront because for instance, if no appointments in progressionType 1
		// have reached the last step, then there won't be a row for that step and it won't show up in getProgressionSteps
		$scope.getPossibleSwimlanes = () => {
			QueueDataService.getPossibleSwimlanes()
			.then($scope.checkResponseForError)
			.catch($scope.notifyOfError)
			.then((response) => {
				if (response === null) {
					const errorMessage = result.error || 'There was an error retrieving the appointment times. Please refresh the page.';
					console.log(errorMessage);
					//NotificationUtilities.giveNotice('Failure', errorMessage, false);
				} else {
					console.log('not null, is it undefined?');
					//should probably put rest of logic in here. do same for getProgressionSteps
				}
				const possibleSwimlanes = response.potentialProgressionSteps.map((step) => {
					return step;
				});
				possibleSwimlanes.forEach((step) => {
					// if progType (a set of specific swimlanes, or a "pool")
					// is new, make new entry for it here doesn't exist, add new dict to pools{} with progTypeName
					if(!(step.progressionTypeId in $scope.pools)) { // this could be changed to PoolTypeID, or just PoolID
						$scope.pools[step.progressionTypeId] = {
							progressionTypeName: step.progressionTypeName,
							swimlanes: {
								0: {
									stepName: 'Awaiting',
									appointments: [] //TODO arrays are just special objects in javascript i think. when i log pools, it comes out looking like a normal object. i think it inherits the key/val from the appointments object.
								},
								[step.progressionStepOrdinal] : {
									stepName: step.progressionStepName,
									appointments: [] // will add appointments once we pull their steps in getProgressionSteps
								}
							}
						};
					} else {
						$scope.pools[step.progressionTypeId]['swimlanes'][step.progressionStepOrdinal] =
							{
								stepName: step.progressionStepName,
								appointments: []
							};
					}
				});
			});	
		};

		$scope.getProgressionSteps = () => {
			// TODO need to clear work here so when date or site changes, we start tabula rasa. looks like that's done in resetSwimlanes
			let year = $scope.currentYear,
				month = $scope.currentMonth + 1,
				day = $scope.currentDay;
			if (month < 10) month = '0' + month;
			const isoFormattedDate = year + "-" + month + "-" + day;

			if ($scope.selectedSite == null || $scope.selectedSite.siteId == null) {
				return;
			}
			const siteId = $scope.selectedSite.siteId;
			// 1. Get progression steps
			QueueDataService.getProgressionSteps(isoFormattedDate, siteId)
				.then($scope.checkResponseForError)
				.catch($scope.notifyOfError)
				.then((response) => {
					if(response === undefined) {
						console.log("We had a problem accessing the appointment information.")
					}
					const progressionSteps = response.progressionSteps.map((step) => { //todo need to error handle this, can't access .map if progSteps doesn't exist. throw a banner and return out of this function.
						// this is not done in sql because we don't concat so we can hide last name if wanted
						step.clientName = step.firstName + ' ' + step.lastName;
						return step;
					});
					// 2. Fill the progression steps into appointment objects before adding them
					// to the correct swimlanes in the correct pools in $scope.pools
					const appointments = {};
					progressionSteps.forEach((step) => {
						if (!(step.appointmentId in appointments)) {
							appointments[step.appointmentId] = {
								scheduledTime: step.scheduledTime,
								clientName: step.clientName,
								steps: [{
									stepOrdinal: step.progressionStepOrdinal,
									stepName: step.progressionStepName,
									stepTimeStamp: step.timestamp,
									subStep: step.progressionSubStepName
								}],
								cancelled: step.cancelled,
								language: step.language,
								walkin: step.walkin,
								visa: step.visa,
								// TODO need to add noshow logic here, and make sure phone number/name is handled on front end
								phoneNumber: step.phoneNumber,
								emailAddress: step.emailAddress
							}
						} else {
							// if the appointment object is already made, we only need to add to its list of steps
							// (first might be checked-in, then here we add the timestamp for the "paperwork complete" step)
							appointments[step.appointmentId]['steps'].push(
								{
									stepOrdinal: step.progressionStepOrdinal,
									stepName: step.progressionStepName,
									stepTimeStamp: step.timestamp,
									subStep:step.progressionSubStepName
								}
							)
						}
						// we sort in sql so that the most recent step is advancement_rank = 1 and comes last.
						// So if advancement_rank = 1, this is the step in the database for this appointment,
						// and we can proceed to use this appointment's object, now that it's complete
						// TODO write data contract specifying that we always delete when a thing is moved backwards,
						// and don't make a step entry unless it's moved forwards in the swimlanes.
						if(step.advancement_rank == 1) {
							// Only add appointments if 1) they don't exist in any swimlane, or 2) they've been moved/removed already (by another volunteer)
							// I've ignored case 2 however, as I don't think it'll happen all too often, so it's not worth the extra logic
							// TODO could store previousAppointmentIds with their previous Ordinal step and see if it changed here.
							const exists = $scope.previousAppointmentIds.some((apptId) => apptId = step.appointmentId);
							if (!exists) {
								// if timestamp if null, then this is the default beginning progressionStep made in
								// storeAppointment.php, insertNullProgressionStepTimestamp().
	
								const ordinal = ((step.timestamp === null) ? 0 : step.advancement_rank);
								$scope.pools[step.progressionTypeId]["swimlanes"][ordinal]["appointments"].push(
									appointments[step.appointmentId]
								)
							}
						}
					});
					 // this is so at auto-refresh every 15 seconds, we can skip an appointment if it's already been added.
					$scope.previousAppointmentIds = Object.keys(appointments); //TODO can we totally reset it like this, or do we need to add on? an appt shouldn't go away so this should be okay.
					console.log($scope.pools);
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
			$scope.getProgressionSteps();
		};

		$scope.dateChanged = () => {
			$scope.resetSwimlanes();
			$scope.getProgressionSteps();
		};

		$scope.resetSwimlanes = () => {
			for (const [key, pool] of Object.entries($scope.pools)) {
				for (const [key, swimlane] of Object.entries(pool.swimlanes)) {
					if (swimlane.appointments.length > 0) {
						swimlane.appointments = [];
					}
				}
			}
		};

		$scope.removeAppointmentFromSwimlanes = (appointmentId) => {
			$scope.appointments = $scope.appointments.filter((appointment) => appointment.appointmentId != appointmentId);
			$scope.awaitingAppointments = $scope.awaitingAppointments.filter((appointment) => appointment.appointmentId != appointmentId);
			$scope.checkedInAppointments = $scope.checkedInAppointments.filter((appointment) => appointment.appointmentId != appointmentId);
			$scope.paperworkCompletedAppointments = $scope.paperworkCompletedAppointments.filter((appointment) => appointment.appointmentId != appointmentId);
			$scope.beingPreparedAppointments = $scope.beingPreparedAppointments.filter((appointment) => appointment.appointmentId != appointmentId);
			$scope.completedAppointments = $scope.completedAppointments.filter((appointment) => appointment.appointmentId != appointmentId);
		};
		//todo make this smarter, pass in appointment id but also figure out what pool, swimlane the appointment is in
		// and delete the appt from that specific swimlane.

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

		// get info to render groups of swimlanes, i.e., pools.
		// These are called "progressionType"s in the database.
		// They are different queue sequences.
		// Do this first so the pools data structure is ready. todo might be able to move it to the bottom.
		$scope.getPossibleSwimlanes();
		
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
			$scope.getProgressionSteps();
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
