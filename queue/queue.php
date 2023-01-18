<div class="dcf-wrapper dcf-mb-8">
	<!-- Queue Section -->
	<div ng-show="selectedAppointment == null">
		<!-- Header -->
		<div class="dcf-grid dcf-grid-thirds dcf-col-gap-2 dcf-mb-6">
			<div>
				<label for="siteSelect" class="dcf-label">Site</label>
				<select id="siteSelect"
					class="dropdown-toggle dcf-w-100% dcf-input-select" 
					ng-model="selectedSite" 
					ng-options="site.title for site in sites track by site.siteId"
					ng-change="siteChanged()">
					<option value="" style="display:none;">-- Select A Site --</option>
				</select> 
			</div>
			
			<div>
				<label for="dateInput" class="dcf-label">Date</label>
				<input type="text" id="dateInput" class="dcf-w-100% dcf-input-text" />
			</div>

			<!-- Search box -->
			<div>
				<label for="queueSearch" class="dcf-label">Client Search</label>
				<input type="text" 
					id="queueSearch" 
					class="dcf-w-100% dcf-input-text" 
					ng-model="clientSearchString" />

				<!-- Message if there are no appointments that match the search -->
				<p class="dcf-txt-center" 
					ng-show="clientSearchString && !parseAppointments('doSomePassSearchFilter')"
					ng-cloak>
					No results for "{{clientSearchString}}".
				</p>
			</div>
		</div>
		<!-- End Header -->

		<!-- Shown if no site has been selected -->
		<div class="dcf-txt-center" ng-if="selectedSite == null" ng-cloak>
			Select a site and date.
		</div>


		<div ng-repeat="(key, progressionType) in pools" ng-show="hasAppointments(progressionType)">
			<div class="dcf-d-flex dcf-jc-center"><h2>{{progressionType['progressionTypeName']}} Queue</h2></div>
			<!-- Swimlane headers -->
			<div class="dcf-grid dcf-grid-fifths@md dcf-col-gap-2">
				<div ng-repeat="(key, swimlane) in progressionType['swimlanes']" class="dcf-d-flex dcf-jc-center">
					<h5>{{swimlane['stepName']}} ({{objectLengthHelper(swimlane.appointments)}})</h5>
				</div>			
			</div>

			<!-- Swimlanes -->
			<div class="dcf-grid dcf-grid-fifths@md dcf-col-gap-2">
				<!--<div ng-repeat="(key, swimlane) in progressionType['swimlanes']" class="container" id="awaitingAppointmentsContainer" dragula="'queue-bag'" dragula-model="swimlane.stepName">Swimlane headers -->
				<div ng-repeat="(stepOrdinal, swimlane) in progressionType['swimlanes']" class="container" 
					id="{{progressionType.progressionTypeId+'_'+swimlane.stepOrdinal}}"
					data-prog-type-id="{{progressionType.progressionTypeId}}" data-step-id="{{swimlane.stepId}}" 
					data-step-ordinal="{{stepOrdinal}}" data-max-ordinal="{{stepOrdinal===progressionType.progressionTypeMaxOrdinal}}" 
					dragula="'queue-bag'" dragula-model="Object.values(swimlane['appointments'])"> <!-- TODO I think dragula-model needs an array (when I moved appts, I was getting error "a.splice is not a function"), should check if I can just pass in an object. -->
					<div ng-repeat="(appointmentId, appointment) in swimlane['appointments']"
						data-appointment-id="{{appointmentId}}"
						ng-show="passesSearchFilter(appointment)"
						ng-click="selectAppointment(appointment, progressionType.progressionTypeId, 
							stepOrdinal, (stepOrdinal===progressionType.progressionTypeMaxOrdinal))">
							{{appointment.clientName}} ({{appointment.scheduledTime}})
							<i ng-show="appointment.steps[stepOrdinal]['subStepName']">{{appointment.steps[stepOrdinal]['subStepName']}}</i>
					</div>
				</div>				
			</div>
		</div>

		<!-- Shown if there are no appointments -->
		<div class="dcf-txt-center" ng-if="!(selectedSite == null) && !parseAppointments('areThereAnyAppointments')">
			There are no appointments on this day at this site.
		</div>
	</div>
	<!-- End of Queue Section -->



	<!-- Appointment Information Section (shown when an appointment is clicked) -->
	<div ng-if="selectedAppointment != null" ng-cloak>
		<!-- Provide a way to get back to the queue -->
		<button type="button" class="dcf-btn dcf-btn-secondary dcf-mb-8" ng-click="deselectAppointment()">Back to Queue</button>

		<div class="dcf-mb-4">
			<h2>{{selectedAppointment.name}}</h2>
			<div class="dcf-mb-4">
				<span class="pill pill-red" ng-if="selectedAppointment.noShow">No-show</span>
				<span ng-if="!selectedAppointment.noShow">
					<span class="pill pill-walk-in" ng-if="selectedAppointment.walkIn">Walk-In</span>
					<div class="pill" ng-repeat="(stepOrdinal, step) in selectedAppointmentStepsForPills">
						<span class="pill" ng-class="step.stepCompleted ? 'pill-complete': 'pill-incomplete'">{{step.stepName}}</span>
						<!-- https://stackoverflow.com/questions/21734524/key-value-pairs-in-ng-options -->
						<select ng-show="objectLengthHelper(step.possibleSubsteps) > 0 && stepOrdinal <= selectedAppointmentOrdinal"
							ng-change="selectSubStep(stepOrdinal)"
							ng-model="selectedAppointment.steps[stepOrdinal].subStepId"
							ng-options="subStepId as subStepName for (subStepId, subStepName) in step.possibleSubsteps">
						</select>
					</div>
				</span>
			</div>
			<div><b>Scheduled Appointment Time: </b>{{selectedAppointment.scheduledTime}}</div>
			<div>
				<span><b>Language:</b> {{selectedAppointment.language}}</span>
			</div>
			<div ng-if="selectedAppointment.emailAddress != null" ng-cloak>
				<span><b>Email:</b> {{selectedAppointment.emailAddress}}</span>
			</div>
			<div ng-if="selectedAppointment.phoneNumber != null" ng-cloak>
				<span><b>Phone Number:</b> {{selectedAppointment.phoneNumber}}</span>
			</div>
			<div ng-cloak>
				<span><b>Visa Type:</b> {{(selectedAppointment.visa || "None").trim()}}</span>
			</div>
			<div ng-cloak>
				<span><b>Appointment Type:</b> {{selectedAppointment.appointmentType}}</span>
			</div>
		</div>


		<div appointment-notes-area class="dcf-mb-6"></div>

		<div class="dcf-mb-2">
			<h4>Appointment Not Completed:</h4>
			<button class="dcf-btn dcf-btn-primary" 
				ng-show="selectedAppointmentOrdinal == 0" 
				ng-disabled="selectedAppointmentOnLastStep" 
				ng-click="markAppointmentAsCancelled()">
				Cancel Appointment
			</button>

			<button class="dcf-btn dcf-btn-primary" 
				ng-show="selectedAppointmentOrdinal > 0" 
				ng-disabled="selectedAppointmentOnLastStep" 
				ng-click="markAppointmentAsIncomplete()">
				Mark as Incomplete
			</button>
		</div>

		<div class=" dcf-txt-xs dcf-txt-right"><b>Appointment ID: </b>{{selectedAppointment.appointmentId}}</div>
	</div>
	<!-- End of Appointment Information Section -->

</div>