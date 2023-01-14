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


		$scope.appointmentNotesAreaSharedProperties = AppointmentNotesAreaSharedPropertiesService.getSharedProperties();

		// Configure dragula options
		DragulaService.options($scope, 'queue-bag', { // Dragula call a collection of "swimlanes/containers" a "bag"
			 accepts: (element, targetContainer, sourceContainer, sibling) => { // todo look into sibling.
				// Make it so elements can only be dropped in adjacent containers				
				// targetContainer is now a swimlane, or a step
				if (targetContainer != null && sourceContainer != null) {
					const sourceSplit = sourceContainer.id.split("*_*");
					const sourceProgressionTypeId = sourceSplit[0], sourceStepOrdinal = sourceSplit[2];


					const targetSplit = targetContainer.id.split("*_*");
					const targetProgressionTypeId = targetSplit[0], targetStepOrdinal = targetSplit[2];


					 // can NOT allow an appointment to move from one queue/pool to another.
					 // this is very important because our sql architecture and logic isn't built to handle this yet.
					if(sourceProgressionTypeId !== targetProgressionTypeId) {
						return false;
					} else {
						// don't allow them to move them more than one column over at a time.
						return Math.abs(targetStepOrdinal - sourceStepOrdinal) <= 1;
					}
				}

				return true; // Default to allowing the move
			},
		});

		$scope.$on('queue-bag.drop', (event, element, targetContainer, sourceContainer) => {
			const appointmentId = element[0].dataset.appointmentId;
			if (appointmentId == null) {
				return;
			}

			const sourceContainerId = sourceContainer[0].id;
			const targetContainerId = targetContainer[0].id; // different way of accessing as the "accepts", not sure why.
			
			// Checks we want to run before we write or delete from the database:
			// 1. Check if it's in same pool (this is disallowed in DragulaService "accepts")
			// 2. Ignore the move if it's within a container (looks like unecessary because this doesn't even trigger a "drop event")
			if (sourceContainerId === targetContainerId) {
				return;
			}

			const sourceSplit = sourceContainer[0].id.split("*_*");
			const sourceProgressionTypeId = sourceSplit[0], sourceStepId = sourceSplit[1], sourceStepOrdinal = sourceSplit[2];

			const targetSplit = targetContainer[0].id.split("*_*");
			const targetProgressionTypeId = targetSplit[0], targetStepId = targetSplit[1], targetStepOrdinal = targetSplit[2];
			
			// don't have to know if it's a substep or step. can figure it out in the schema.
			// if ordinal increased, insert timestamp.
			//if ordinal decreased, delete entry where step or stepfromsubstep = soucestepid

			//todo put moveforward and movebackward in their own function
			//send over only appointmentId and targetProgressionStepId (or substepid) here.
			if(targetStepOrdinal - sourceStepOrdinal == -1) {
				// if we went back in the queue, we need to delete the previous entry.
				const response = $scope.deleteTimestamp(appointmentId, sourceStepId)
					.then($scope.checkResponseForError)
					.catch($scope.notifyOfError)
					.then((response) => {
						if (response == null || !response.success) {
							const errorMessage = response ? response.error : 'There was an error on the server altering queue records. Please refresh the page and try again.';
							NotificationUtilities.giveNotice('Failure', errorMessage, false);
						} //TODO put an else here and give some kind of confirmation
					}
				);
			}
			// if we dropped an appt in the leftmost swimlane (Awaiting), that would be represented in the database
			// by a null timestamp for the first ordinal step in the database, which is the second left most swimlane on the UI.
			// This is the default because an appointment starts off as "Awaiting" so there can't/shouldn't be a timestamp for it.
			var setTimeStampToNull, stepId;
			if(targetStepId == 'null') { // THIS IS BASED ON THE FRONT-END RESTRICTION OF ONLY MOVING ONE SWIMLANE AT A TIME! If that changes, so must this.
				setTimeStampToNull = true;
				stepId = sourceStepId;
			} else {
				setTimeStampToNull = false;
				stepId = targetStepId;
			}
			console.log(stepId);
			console.log(setTimeStampToNull);
			// MySQL "ON DUPLICATE KEY UPDATE" statement will update timestamp if regressing, insert if advancing in queue.
			$scope.insertStepTimestamp(appointmentId, stepId, setTimeStampToNull)
				.then($scope.checkResponseForError)
				.catch($scope.notifyOfError)
				.then((response) => {
					if (response == null || !response.success) {
						const errorMessage = response ? response.error : "There was an error on the server updating the appointment's timestamps. Please refresh the page and try again.";
						NotificationUtilities.giveNotice('Failure', errorMessage, false);
					} else {
						// move the appointment to the correct swimlane
						$scope.pools[targetProgressionTypeId]['swimlanes'][targetStepOrdinal]['appointments'][appointmentId] = $scope.pools[sourceProgressionTypeId]['swimlanes'][sourceStepOrdinal]['appointments'][appointmentId];
						// delete from the source swimlane (TODO would this be done automatically by dragula if we used arrays for dragula-model?)
						delete $scope.pools[sourceProgressionTypeId]['swimlanes'][sourceStepOrdinal]['appointments'][appointmentId];
						//const appointment = $scope.appointments.find((appointment) => appointment.appointmentId === appointmentId);
						//appointment.checkedIn = false; //TODO have to handle the pills and other things. that might have to wait unless ben can handle it with the general popup/formatting task.
					}
				}
			);
		});

		// have to pull these upfront because for instance, if no appointments in progressionType 1
		// have reached the last step, then there won't be a row for that step and it won't show up in getProgressionSteps
		$scope.getPossibleSwimlanes = () => {
			QueueDataService.getPossibleSwimlanes()
			.then($scope.checkResponseForError)
			.catch($scope.notifyOfError)
			.then((response) => {
				if (response == null || !response.success) {
					const errorMessage = response ? response.error : 'There was an error on the server accessing appointment queue types. Please refresh the page and try again.';
					NotificationUtilities.giveNotice('Failure', errorMessage, false);
				} else {
					const possibleSwimlanes = response.potentialProgressionSteps.map((step) => {return step;});
					possibleSwimlanes.forEach((step) => {
						// if progType (a set of specific swimlanes, or a "pool")
						// is new, make new entry for it here doesn't exist, add new dict to pools{} with progTypeName
						if(!(step.progressionTypeId in $scope.pools)) { // this could be changed to PoolTypeID, or just PoolID
							$scope.pools[step.progressionTypeId] = {
								progressionTypeId: step.progressionTypeId,
								progressionTypeName: step.progressionTypeName,
								swimlanes: {
									0: {
										stepId: null,
										stepOrdinal: "0",
										stepName: 'Awaiting',
										appointments: {}
									},
									[step.progressionStepOrdinal] : {
										stepId: step.progressionStepId,
										stepOrdinal: step.progressionStepOrdinal,
										stepName: step.progressionStepName,
										appointments: {} // will add appointments once we pull their steps in getProgressionSteps
									}
								}
							};
						} else {
							$scope.pools[step.progressionTypeId]['swimlanes'][step.progressionStepOrdinal] =
							{
								stepId: step.progressionStepId,
								stepOrdinal: step.progressionStepOrdinal,
								stepName: step.progressionStepName,
								appointments: {}
							};
						}
					});
				}
			});	
		};

		$scope.getProgressionSteps = () => {
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
					if (response == null || !response.success) {
						const errorMessage = response ? response.error : 'There was an error on the server updating appointment information. Please refresh the page and try again.';
						NotificationUtilities.giveNotice('Failure', errorMessage, false); //todo i belive this will pop up and say "undefined" if the session ends. that should be falsy, look into it.
					} else {
						const progressionSteps = response.progressionSteps.map((step) => {
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
									appointmentId: step.appointmentId,
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
							// we sort in sql so that the most recent step (advancement_rank = 1) comes last.
							// So if advancement_rank = 1, we are on the last database entry for this appointment,
							// and we can proceed to use this appointment's object now that it's complete.
							if(step.advancement_rank == 1) {
								// Only add appointments if 1) they don't exist in any swimlane, or 2) they've been moved/removed already (by another volunteer)
								// I've ignored case 2 however, as I don't think it'll happen all too often, so it's not worth the extra logic
								// TODO could store previousAppointmentIds with their previous Ordinal step and see if it changed here.
								const exists = $scope.previousAppointmentIds.some((apptId) => apptId = step.appointmentId);
								if (!exists) {
									// if timestamp if null, then this is the default beginning progressionStep made in
									// storeAppointment.php, insertNullProgressionStepTimestamp().
		
									const ordinal = ((step.timestamp === null) ? 0 : step.advancement_rank);
									// ex: in the pool or progression type 'Virtual', in the second ordinal swimlane,
									// we are going to add this appointment object with the appt id as the key.
									$scope.pools[step.progressionTypeId]["swimlanes"][ordinal]["appointments"][step.appointmentId] = appointments[step.appointmentId];
								}
							}
						});
						// this is so at auto-refresh every 15 seconds, we can skip an appointment if it's already been added.
						$scope.previousAppointmentIds = Object.keys(appointments);
						console.log($scope.pools);
					}
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

		$scope.deleteTimestamp = (appointmentId, stepId) => {
			return QueueDataService.deleteTimestamp(appointmentId, stepId)
				.then($scope.checkResponseForError)
				.catch($scope.notifyOfError)
				.then((response) => {
					return response;
				});
		};

		$scope.insertStepTimestamp = (appointmentId, stepId, setTimeStampToNull) => {
			return QueueDataService.insertStepTimestamp(appointmentId, stepId, setTimeStampToNull)
				.then($scope.checkResponseForError)
				.catch($scope.notifyOfError)
				.then((response) => {
					return response;
				});
		};

		$scope.insertSubStepTimestamp = (appointmentId, subStepId) => {
			return QueueDataService.insertSubStepTimestamp(appointmentId, subStepId)
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
					$scope.parseAppointments('markAppointmentAsBeingPrepared');
				});
		};

		$scope.markAppointmentAsCompleted = (appointmentId) => {
			QueueDataService.markAppointmentAsCompleted(appointmentId)
				.then($scope.checkResponseForError)
				.catch($scope.notifyOfError)
				.then((response) => {
					$scope.parseAppointments('markAppointmentAsCompleted');
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

		// helper function to loop through the appointments since they are buried in our data structure.
		// TODO looking for a more elegant way to do this, shouldn't be necessary though since we aren't scaling much.
		$scope.parseAppointments = (action, id) => {
			for (const [key, pool] of Object.entries($scope.pools)) {
				for (const [key, swimlane] of Object.entries(pool.swimlanes)) {
					switch(action) {
						case 'resetSwimlanes':
							//if (swimlane.appointments.length > 0) {
								swimlane.appointments = {};
							//}
							break;
						case 'removeAppointmentFromSwimlanes':
							if (swimlane.hasOwnProperty(id)) {
								delete swimlane[id]; // todo need to test!! this is why we shouldn't use array and should use object with apptID as key.
							}
							break;
						case 'markAppointmentAsCompleted':
							if (swimlane.hasOwnProperty(id)) {
								swimlane[id].appointment.ended = true; // todo do i need to init the appt object with this? or if it's undefined/null it's assumed not to have ended
							}
							break;
						case 'markAppointmentAsBeingPrepared':
							if (swimlane.hasOwnProperty(id)) {
								swimlane[id].appointment.preparing = true;
								swimlane[id].appointment.ended = false;
							}
							break;
						default:
							// todo error handle here
							console.log("something went wrong");
					}
				}
			}
		};
		
		$scope.hasAppointments = (pool) => {
			for (const [key, swimlane] of Object.entries(pool.swimlanes)) {
				if (!(Object.keys(swimlane.appointments).length === 0)) {
					return true;
				}
			}
			return false;
		}

		$scope.resetSwimlanes = () => {
			$scope.parseAppointments('resetSwimlanes', null);
		};

		$scope.removeAppointmentFromSwimlanes = (appointmentId) => {
			$scope.parseAppointments('removeAppointmentFromSwimlanes', appointmentId);
		};
		//todo make this smarter, pass in appointment id but also figure out what pool, swimlane the appointment is in
		// and delete the appt from that specific swimlane.
		
		// https://stackoverflow.com/questions/35916610/object-keys-is-not-working-in-angularjs2
		$scope.objectLengthHelper = (object) => {
			return Object.keys(object).length;
		};

		$scope.passesSearchFilter = (appointment) => {
			const searchString = $scope.clientSearchString.toLowerCase();
			if (!searchString) return true;
			return appointment.clientName.toLowerCase().indexOf(searchString) !== -1 ||
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
