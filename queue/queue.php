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
					ng-show="clientSearchString && !appointments.some(passesSearchFilter)"
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

		<!-- Shown if there are appointments -->
		<div ng-if="appointments.length > 0" ng-cloak>
			<!-- Swimlane headers -->
			<div class="dcf-grid dcf-grid-fifths@md dcf-col-gap-2">
				<div class="dcf-d-flex dcf-jc-center"><h5>Awaiting ({{awaitingAppointments.length}})</h5></div>
				<div class="dcf-d-flex dcf-jc-center"><h5>Checked-In ({{checkedInAppointments.length}})</h5></div>
				<div class="dcf-d-flex dcf-jc-center"><h5>Paperwork Done ({{paperworkCompletedAppointments.length}})</h5></div>
				<div class="dcf-d-flex dcf-jc-center"><h5>Preparing ({{beingPreparedAppointments.length}})</h5></div>
				<div class="dcf-d-flex dcf-jc-center"><h5>Complete ({{completedAppointments.length}})</h5></div>
			</div>

			<!-- Swimlanes -->
			<div class="dcf-grid dcf-grid-fifths@md dcf-col-gap-2">
				<div class="container" id="awaitingAppointmentsContainer" dragula="'queue-bag'" dragula-model="awaitingAppointments">
					<div ng-repeat="appointment in awaitingAppointments"
						data-appointment-id="{{appointment.appointmentId}}"
						ng-show="passesSearchFilter(appointment)"
						ng-click="selectAppointment(appointment)">{{appointment.name}} ({{appointment.scheduledTime}})</div>
				</div>
				<div class="container" id="checkedInAppointmentsContainer" dragula="'queue-bag'" dragula-model="checkedInAppointments">
					<div ng-repeat="appointment in checkedInAppointments" 
						data-appointment-id="{{appointment.appointmentId}}"
						ng-show="passesSearchFilter(appointment)"
						ng-click="selectAppointment(appointment)">{{appointment.name}}</div>
				</div>
				<div class="container" id="paperworkCompletedAppointmentsContainer" dragula="'queue-bag'" dragula-model="paperworkCompletedAppointments">
					<div ng-repeat="appointment in paperworkCompletedAppointments" 
						data-appointment-id="{{appointment.appointmentId}}"
						ng-show="passesSearchFilter(appointment)"
						ng-click="selectAppointment(appointment)">{{appointment.name}}</div>
				</div>
				<div class="container" id="beingPreparedAppointmentsContainer" dragula="'queue-bag'" dragula-model="beingPreparedAppointments">
					<div ng-repeat="appointment in beingPreparedAppointments" 
						data-appointment-id="{{appointment.appointmentId}}"
						ng-show="passesSearchFilter(appointment)"
						ng-click="selectAppointment(appointment)">{{appointment.name}}</div>
				</div>
				<div class="container" id="completedAppointmentsContainer" dragula="'queue-bag'" dragula-model="completedAppointments">
					<div ng-repeat="appointment in completedAppointments" 
						data-appointment-id="{{appointment.appointmentId}}"
						ng-show="passesSearchFilter(appointment)"
						ng-click="selectAppointment(appointment)">{{appointment.name}}</div>
				</div>
			</div>
		</div>

		<!-- Shown if there are no appointments -->
		<div class="dcf-txt-center" ng-if="appointments.length === 0">
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
					<span class="pill" ng-class="selectedAppointment.checkedIn ? 'pill-complete': 'pill-incomplete'">Checked In</span>
					<span class="pill" ng-class="selectedAppointment.paperworkComplete ? 'pill-complete': 'pill-incomplete'">Completed Paperwork</span>
					<span class="pill" ng-class="selectedAppointment.preparing ? 'pill-complete': 'pill-incomplete'">Preparing</span>
					<span class="pill" ng-class="selectedAppointment.ended ? 'pill-complete': 'pill-incomplete'">Appointment Complete</span>
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
			<div ng-if="selectedAppointment.appointmentType != null" ng-cloak>
				<span><b>Appointment Type:</b> {{selectedAppointment.appointmentType}}</span>
			</div>
		</div>


		<div appointment-notes-area class="dcf-mb-6"></div>

		<div class="dcf-mb-2">
			<h4>Appointment Not Completed:</h4>
			<button class="dcf-btn dcf-btn-primary" 
				ng-show="!selectedAppointment.checkedIn" 
				ng-disabled="selectedAppointment.ended" 
				ng-click="markAppointmentAsCancelled()">
				Cancel Appointment
			</button>

			<button class="dcf-btn dcf-btn-primary" 
				ng-show="selectedAppointment.checkedIn" 
				ng-disabled="selectedAppointment.ended" 
				ng-click="markAppointmentAsIncomplete()">
				Mark as Incomplete
			</button>
		</div>

		<div class=" dcf-txt-xs dcf-txt-right"><b>Appointment ID: </b>{{selectedAppointment.appointmentId}}</div>
	</div>
	<!-- End of Appointment Information Section -->

</div>