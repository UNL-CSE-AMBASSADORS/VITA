<div class="dcf-wrapper dcf-mb-8">
	<!-- Queue Section -->
	<div>
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
				<input type="text" id="queueSearch" class="dcf-w-100% dcf-input-text" ng-model="clientSearch" />

				<!-- Message if there are no appointments that match the search -->
				<p class="dcf-txt-center" 
					ng-show="(appointments | searchFor: clientSearch).length === 0">
					No results for "{{clientSearch}}".
				</p>
			</div>
		</div>
		<!-- End Header -->

		<!-- Shown if no site has been selected -->
		<div class="dcf-txt-center" ng-if="selectedSite == null">
			Select a site and date.
		</div>

		<!-- Shown if there are appointments -->
		<div ng-if="appointments.length > 0">
			<!-- Swimlane headers -->
			<div class="dcf-grid dcf-grid-fifths@md dcf-col-gap-2">
				<div class="dcf-d-flex dcf-jc-center"><h5>Awaiting</h5></div>
				<div class="dcf-d-flex dcf-jc-center"><h5>Checked-In</h5></div>
				<div class="dcf-d-flex dcf-jc-center"><h5>Paperwork Complete</h5></div>
				<div class="dcf-d-flex dcf-jc-center"><h5>Preparing</h5></div>
				<div class="dcf-d-flex dcf-jc-center"><h5>Complete</h5></div>
			</div>

			<!-- Swimlanes -->
			<div class="dcf-grid-fifths@md dcf-col-gap-2">
				<div class="container" id="awaitingAppointmentsContainer" dragula="'queue-bag'" dragula-model="awaitingAppointments">
					<div ng-repeat="appointment in awaitingAppointments | searchFor: clientSearch" data-appointment-id="{{appointment.appointmentId}}" data-container-id="awaitingAppointmentsContainer">{{appointment.name}}</div>
				</div>
				<div class="container" id="checkedInAppointmentsContainer" dragula="'queue-bag'" dragula-model="checkedInAppointments">
					<div ng-repeat="appointment in checkedInAppointments | searchFor: clientSearch" data-appointment-id="{{appointment.appointmentId}}" data-container-id="checkedInAppointmentsContainer">{{appointment.name}}</div>
				</div>
				<div class="container" id="paperworkCompletedAppointmentsContainer" dragula="'queue-bag'" dragula-model="paperworkCompletedAppointments">
					<div ng-repeat="appointment in paperworkCompletedAppointments | searchFor: clientSearch" data-appointment-id="{{appointment.appointmentId}}" data-container-id="paperworkCompletedAppointmentsContainer">{{appointment.name}}</div>
				</div>
				<div class="container" id="beingPreparedAppointmentsContainer" dragula="'queue-bag'" dragula-model="beingPreparedAppointments">
					<div ng-repeat="appointment in beingPreparedAppointments | searchFor: clientSearch" data-appointment-id="{{appointment.appointmentId}}" data-container-id="beingPreparedAppointmentsContainer">{{appointment.name}}</div>
				</div>
				<div class="container" id="completedAppointmentsContainer" dragula="'queue-bag'" dragula-model="completedAppointments">
					<div ng-repeat="appointment in completedAppointments | searchFor: clientSearch" data-appointment-id="{{appointment.appointmentId}}" data-container-id="completedAppointmentsContainer">{{appointment.name}}</div>
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

	<!-- End of Appointment Information Section -->

</div>