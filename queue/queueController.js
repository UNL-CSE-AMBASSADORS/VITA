define('queueController', [], function() {

	function queueController($scope, $interval, QueueDataService, AppointmentNotesAreaSharedPropertiesService, DragulaService, NotificationUtilities) {

		$scope.today = new Date();
		$scope.currentDay = $scope.today.getDate();
		$scope.currentMonth = $scope.today.getMonth();
		$scope.currentYear = $scope.today.getFullYear();

		$scope.sites = [];
		$scope.selectedAppointment = null;
		// These are things we don't need to store inside the object tnen worry about updating, we can just pull them when we click on one. 
		$scope.selectedAppointmentOrdinal = null;
		$scope.selectedAppointmentOnLastStep = null;
		$scope.selectedAppointmentStepsForPills = null;
		$scope.selectedAppointmentSubStepId = null;
		
		$scope.selectedSite = null;

		$scope.clientSearchString = '';

		$scope.pools = {}
		$scope.previousAppointmentIds = []


		$scope.appointmentNotesAreaSharedProperties = AppointmentNotesAreaSharedPropertiesService.getSharedProperties();

		// Configure dragula options
		DragulaService.options($scope, 'queue-bag', { // Dragula call a collection of "swimlanes/containers" a "bag"
			 accepts: (element, targetContainer, sourceContainer, sibling) => {
				// Make it so elements can only be dropped in adjacent containers				
				// targetContainer is now a swimlane, or a step
				if (targetContainer != null && sourceContainer != null) {
					const sourceProgressionTypeId = sourceContainer.dataset.progTypeId;
					const sourceStepId = sourceContainer.dataset.stepId;
					const sourceStepOrdinal = sourceContainer.dataset.stepOrdinal;
		
					const targetProgressionTypeId = targetContainer.dataset.progTypeId;
					const targetStepId = targetContainer.dataset.stepId;
					const targetStepOrdinal = targetContainer.dataset.stepOrdinal;
		
					// Checks we want to run before we write or delete from the database:
					// 1. Check if it's in same pool. Very important! b/c we don't have logic for changing an appt's queue type.
					if (sourceProgressionTypeId !== targetProgressionTypeId) {
						return;
					}
					// 2. Else if it is the same pool, ignore the move if it's within a container
					else if (sourceStepId === targetStepId) {
						return;
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

			const source = {
				progressionTypeId: sourceContainer[0].dataset.progTypeId,
				stepId: sourceContainer[0].dataset.stepId,
				stepOrdinal: sourceContainer[0].dataset.stepOrdinal,
				maxOrdinal: sourceContainer[0].dataset.maxOrdinal
			}

			const target = {
				progressionTypeId: targetContainer[0].dataset.progTypeId,
				stepId: targetContainer[0].dataset.stepId,
				stepOrdinal: targetContainer[0].dataset.stepOrdinal,
				maxOrdinal: targetContainer[0].dataset.maxOrdinal
			}

			// move the appointment to the correct swimlane
			$scope.pools[target.progressionTypeId]['swimlanes'][target.stepOrdinal]['appointments'][appointmentId] = $scope.pools[source.progressionTypeId]['swimlanes'][source.stepOrdinal]['appointments'][appointmentId];
			// delete from the source swimlane (TODO would this be done automatically by dragula if we used arrays for dragula-model?)
			delete $scope.pools[source.progressionTypeId]['swimlanes'][source.stepOrdinal]['appointments'][appointmentId];

			// consider client not a noShow, no matter how late they are, if they have started their appointment.
			// easier to just set to false than it is to check if true, then set to false.
			if (target.stepOrdinal == 1) {
				$scope.pools[target.progressionTypeId]['swimlanes'][target.stepOrdinal]['appointments'][appointmentId].noShow = false;
			}
			// we only need to remove a row in progressionTimeStamp if an appointment was moved back (left) a swimlane. 
			if(target.stepOrdinal - source.stepOrdinal == -1) {
				// we don't really make updates to the appointments on the front end, we just move the appointments themselves in the pool
				// this is an exception, so if the appointment is moved from a substep, if it's moved back it will start blank
				// If an appointment starts as awaiting, then moves to Complete and is given a substep, it will only have a full object
				// for the awaiting step, plus a skeleton object with just a subStepId for the complete step (no intermediate steps).
				// So if the appt isn't given a substep in complete, it will only have an awaiting step, which is why we check if exists here.
				// If you leave the appointment on a step and refresh the page, then it will have a full step object for each one.
				// TODO could update objects every 15 sec, but risk overriding local changes made. Probably shouldn't do that.
				if ([source.stepOrdinal] in $scope.pools[target.progressionTypeId]['swimlanes'][target.stepOrdinal]['appointments'][appointmentId]['steps']) {
					$scope.pools[target.progressionTypeId]['swimlanes'][target.stepOrdinal]['appointments'][appointmentId]['steps'][source.stepOrdinal].subStepId = null;
					$scope.pools[target.progressionTypeId]['swimlanes'][target.stepOrdinal]['appointments'][appointmentId]['steps'][source.stepOrdinal].subStepName = null;
				}
				$scope.regressAppointment(appointmentId, source, target);
			} else {
				$scope.advanceAppointment(appointmentId, source, target);
			}
		});

		
		$scope.advanceAppointment = (appointmentId, source, target) => {
			// not updating appointments steps' object here. could get datetime back from sql and input it, seems unecessary. will update in 15 sec

			// if appt moved back, we are going to update the timestamp in an exiting row
			// if appt moved forward, we are going to insert a new row
			// MySQL "ON DUPLICATE KEY UPDATE" statement handles this logic
			$scope.enterTimestamp(appointmentId, source, target)
				.then($scope.checkResponseForError)
				.catch($scope.notifyOfError)
				.then((response) => { //TODO above is for when we don't even get this far?
					if (response == null || !response.success) {
						const errorMessage = response ? response.error : 'There was an error on the server entering timestamps. Please refresh the page and try again.';
						// NotificationUtilities.giveNotice('Failure', errorMessage, false); //TODO need to clean up errors
					} else {
						if(target.stepOrdinal === target.maxOrdinal) {
							$scope.markAppointmentAsCompleted(appointmentId)
								.then($scope.checkResponseForError)
								.catch($scope.notifyOfError)
								.then((response) => {
									if (response == null || !response.success) {
										const errorMessage = response ? response.error : 'There was an error on the server marking your appointment as completed. Please refresh the page and try again.';
										// NotificationUtilities.giveNotice('Failure', errorMessage, false);
									}
							});
						}
					}
				});
		}

		$scope.regressAppointment = (appointmentId, source, target) => {
			// not updating appointments' steps object here.			
			// if we went back in the queue, we need to delete the previous entry.
			$scope.deleteTimestamp(appointmentId, source.stepId)
				.then($scope.checkResponseForError)
				.catch($scope.notifyOfError)
				.then((response) => {
					if (response == null || !response.success) {
						const errorMessage = response ? response.error : 'There was an error on the server altering queue records. Please refresh the page and try again.';
						NotificationUtilities.giveNotice('Failure', errorMessage, false);
					} else {
						$scope.enterTimestamp(appointmentId, source, target)
						.then($scope.checkResponseForError)
						.catch($scope.notifyOfError)
						.then((response) => {
							if (response == null || !response.success) {
								const errorMessage = response ? response.error : 'There was an error on the server entering updates. Please refresh the page and try again.';
								NotificationUtilities.giveNotice('Failure', errorMessage, false);
							} else {
								// if we are going left from the last step, remove complete status
								if(source.stepOrdinal === source.maxOrdinal) {
									$scope.markAppointmentAsIncomplete()
									.then($scope.checkResponseForError)
									.catch($scope.notifyOfError)
									.then((response) => {
										if (response == null || !response.success) {
											const errorMessage = response ? response.error : 'There was an error on the server marking the appointment as incomplete. Please refresh the page and try again.';
											NotificationUtilities.giveNotice('Failure', errorMessage, false);
										}
									});
								}
							}
						});
					}
				}
			);
		}

		$scope.enterTimestamp = (appointmentId, source, target) => {
			// if we dropped an appt in the leftmost swimlane (Awaiting), that would be represented in the database
			// by a null timestamp for the first ordinal step in the database, which is the second left most swimlane on the UI.
			// This is the default because an appointment starts off as "Awaiting" so there can't/shouldn't be a timestamp for it.
			var setTimeStampToNull, stepId;
			if(target.stepOrdinal === '0') { // THIS IS BASED ON THE FRONT-END RESTRICTION OF ONLY MOVING ONE SWIMLANE AT A TIME! If that changes, so must this.
				setTimeStampToNull = true;
				stepId = source.stepId;
			} else {
				setTimeStampToNull = false;
				stepId = target.stepId;
			}
			return $scope.insertStepTimestamp(appointmentId, stepId, setTimeStampToNull)
				.then($scope.checkResponseForError)
				.catch($scope.notifyOfError)
				.then((response) => {
					if (response == null || !response.success) {
						const errorMessage = response ? response.error : "There was an error on the server updating the appointment's timestamps. Please refresh the page and try again.";
						NotificationUtilities.giveNotice('Failure', errorMessage, false);
					} else {
					}
					return response;
				}
			);
		};

		// have to pull these upfront because for instance, if no appointments in progressionType 1
		// have reached the last step, then there won't be a row for that step and it won't show up in getProgressionSteps
		$scope.getPossibleSwimlanes = () => {
			return QueueDataService.getPossibleSwimlanes()
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
						if(!(step.progressionTypeId in $scope.pools)) {
							$scope.pools[step.progressionTypeId] = {
								progressionTypeId: step.progressionTypeId,
								progressionTypeName: step.progressionTypeName,
								progressionTypeMaxOrdinal: step.progressionStepOrdinal,
								swimlanes: {
									0: {
										stepId: null,
										stepOrdinal: "0",
										stepName: 'Awaiting',
										appointments: {},
										possibleSubsteps: {}
									},
									[step.progressionStepOrdinal] : {
										stepId: step.progressionStepId,
										stepOrdinal: step.progressionStepOrdinal,
										stepName: step.progressionStepName,
										appointments: {}, // will add appointments once we pull their steps in getProgressionSteps
										possibleSubsteps: {}
									}
								}
							};
						} else {
							$scope.pools[step.progressionTypeId]['progressionTypeMaxOrdinal'] = Math.max($scope.pools[step.progressionTypeId]['progressionTypeMaxOrdinal'], step.progressionStepOrdinal).toString();
							$scope.pools[step.progressionTypeId]['swimlanes'][step.progressionStepOrdinal] =
							{
								stepId: step.progressionStepId,
								stepOrdinal: step.progressionStepOrdinal,
								stepName: step.progressionStepName,
								appointments: {},
								possibleSubsteps: {}
							};
						}
					});
				}
				return {success: true};
			});	
		};

		$scope.getPossibleSubsteps = () => {
			QueueDataService.getPossibleSubsteps()
			.then($scope.checkResponseForError)
			.catch($scope.notifyOfError)
			.then((response) => {
				if (response == null || !response.success) {
					const errorMessage = response ? response.error : 'There was an error on the server accessing appointment subcategories. Please refresh the page and try again.';
					NotificationUtilities.giveNotice('Failure', errorMessage, false);
				} else {
					const possibleSubsteps = response.possibleSubsteps.map((substep) => {return substep;});
					possibleSubsteps.forEach((substep) => {
						$scope.pools[substep.progressionTypeId]['swimlanes'][substep.progressionStepOrdinal]
							['possibleSubsteps'][substep.progressionSubStepId] = substep.progressionSubStepName;
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
			// 1. Get filtered rows of appointments and their progressionStep info
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
									appointmentType: step.appointmentType,
									scheduledTime: step.scheduledTime,
									clientName: step.clientName,
									steps: {[step.progressionStepOrdinal]: {
										stepOrdinal: step.progressionStepOrdinal,
										stepName: step.progressionStepName,
										stepTimeStamp: step.timestamp,
										subStepId: step.progressionSubStepId,
										subStepName: step.progressionSubStepName
									}},
									cancelled: step.cancelled,
									language: step.language,
									walkin: step.walkin,
									visa: step.visa,
									phoneNumber: step.phoneNumber,
									emailAddress: step.emailAddress
								}
							} else {
								// if the appointment object is already made, we only need to add to its list of steps
								// (first might be checked-in, then here we add the timestamp for the "paperwork complete" step)
								appointments[step.appointmentId]['steps'][step.progressionStepOrdinal] =
									{
										stepOrdinal: step.progressionStepOrdinal,
										stepName: step.progressionStepName,
										stepTimeStamp: step.timestamp,
										subStepId: step.progressionSubStepId,
										subStepName: step.progressionSubStepName
									};
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
									// if we are adding it, last thing to add to the appt object is no-show status
									// Appt is no-show if they are 30 minutes late and they haven't started their appt
									// Since we are on the most recent step for this appointment (see comment above),
									// if this step is the first with a null timestamp, they haven't started their appt
									var noShow = false; // default to false;
									var ordinal = null;
									if (step.timestamp === null) {
										const noShowDeadline = new Date(step.scheduledDatetime + 30*60000); // 30 minutes to show up
										noShow = noShowDeadline < new Date(); // TODO need to test this
										// (unrelated to noShow) if timestamp if null, then this is the default beginning
										// progressionStep made in storeAppointment.php, insertNullProgressionStepTimestamp().
										ordinal = 0;
									} else {
										ordinal = step.progressionStepOrdinal
									}

									appointments[step.appointmentId].noShow = noShow;
									// ex: in the pool or progression type 'Virtual', in the second ordinal swimlane,
									// we are going to add this appointment object with the appt id as the key.
									$scope.pools[step.progressionTypeId]["swimlanes"][ordinal]["appointments"][step.appointmentId] = appointments[step.appointmentId];
								}
							}
						});
						// at auto-refresh every 15 seconds, we can skip an appointment if it's already been added.
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

		// need appointmentId and subStepId for SQL, subStepName for front end to show in queue.
		// name comes through ng-model 
		$scope.selectSubStep = () => {
			const appointmentId = $scope.selectedAppointment.appointmentId;
			// The appt won't have a full step object here
			const subStepId = $scope.selectedAppointment.steps[$scope.selectedAppointmentOrdinal]['subStepId'];
			// get the substep name through the temporary pills object // TODO is there a way to pass key/val straight to here?
			const subStepName = $scope.selectedAppointmentStepsForPills[$scope.selectedAppointmentOrdinal]['possibleSubsteps'][subStepId];
			$scope.selectedAppointment.steps[$scope.selectedAppointmentOrdinal].subStepName = subStepName;

			$scope.insertSubStepTimestamp(appointmentId, subStepId)
				.then($scope.checkResponseForError)
				.catch($scope.notifyOfError)
				.then((response) => {
					if (response == null || !response.success) {
						const errorMessage = response ? response.error : "There was an error on the server updating the appointment's sub-category. Please refresh the page and try again.";
						NotificationUtilities.giveNotice('Failure', errorMessage, false); //todo i belive this will pop up and say "undefined" if the session ends. that should be falsy, look into it.
					}
				});
		};

		$scope.insertSubStepTimestamp = (appointmentId, subStepId) => {
			return QueueDataService.insertSubStepTimestamp(appointmentId, subStepId)
				.then($scope.checkResponseForError)
				.catch($scope.notifyOfError)
				.then((response) => {
					return response;
				});
				//TODO how to check rows affected?
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

		$scope.selectAppointment = (appointment, progressionTypeId, currentStepOrdinal, appointmentOnLastStep) => {
			$scope.selectedAppointment = appointment;
			$scope.selectedAppointmentStepsForPills = $scope.getAppointmentPills(currentStepOrdinal, progressionTypeId);
			$scope.appointmentNotesAreaSharedProperties.appointmentId = $scope.selectedAppointment.appointmentId;
			$scope.selectedAppointmentOrdinal = Number(currentStepOrdinal);
			$scope.selectedAppointmentOnLastStep = appointmentOnLastStep;
		};

		$scope.getAppointmentPills = (currentStepOrdinal, progressionTypeId) => {
			// want to return [{stepName, done or not}]
			const stepsForPills = {}
			for (const [key, val] of Object.entries($scope.pools[progressionTypeId]['swimlanes'])) {
				stepsForPills[val.stepOrdinal] = // "key" is ordinal, this is more future-proof.
					{
						stepName: val.stepName,
						stepCompleted: val.stepOrdinal <= currentStepOrdinal,
						possibleSubsteps: val.possibleSubsteps
					};
			}
			return stepsForPills;
		};

		$scope.deselectAppointment = () => {
			$scope.selectedAppointment = null;
			$scope.selectedAppointmentOrdinal = null;
			$scope.selectedAppointmentOnLastStep = null;
			$scope.selectedAppointmentStepsForPills = null;
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
						case 'areThereAnyAppointments':
							if (!(Object.keys(swimlane.appointments).length === 0)) {
								return true;
							}
							break;
						case 'doSomePassSearchFilter':
							for (const [appointmentId, appointment] of Object.entries(swimlane.appointments)) {	
								if ($scope.passesSearchFilter(appointment)) {
										return true;
								}
							}
							break;
						default:
							// todo error handle here
							console.log("something went wrong");
					}
				}
			}
			if(action === 'areThereAnyAppointments' || action === 'doSomePassSearchFilter') {
				return false;
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

		$scope.anyAppointments = () => {
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
		$scope.getPossibleSwimlanes()
			.then($scope.checkResponseForError)
			.catch($scope.notifyOfError)
			.then((response) => {
				if (response == null || !response.success) {
					const errorMessage = response ? response.error : 'There was an error on the server accessing appointment queue types. Please refresh the page and try again.';
					NotificationUtilities.giveNotice('Failure', errorMessage, false);
				} else {
					$scope.getPossibleSubsteps();
				}
			});
		
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
