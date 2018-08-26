<div class="wdn-inner-wrapper wdn-inner-padding-no-top">
	<div class="wdn-grid-set">
		<!-- Site select list -->
		<div class="wdn-col-one-fourth">
			<ul class="unstyled-list wdn-list-reset">
				<li class="site-list-element"
					ng-repeat="site in sites"
					ng-click="selectSite(site)">
					<a>{{site.title}}</a>
				</li>
			</ul>
		</div>

		<!-- Site Information Area -->
		<div class="wdn-col-three-fourths">
			<!-- Shown if a site is selected and loaded from backend -->
			<div ng-if="selectedSite != null && siteInformation != null">
				<h3 class="clear-top">{{siteInformation.title}}</h3>
				<div class="wdn-grid-set-halves">
					<div class="wdn-col">
						<div><b>Address:</b> {{siteInformation.address}}</div>
						<div><b>Phone Number:</b> {{siteInformation.phoneNumber}}</div>
					</div>
					<div class="wdn-col">
						<div><b>Does Multilingual:</b> {{siteInformation.doesMultilingual ? 'Yes' : 'No'}}</div>
						<div><b>Does International:</b> {{siteInformation.doesInternational ? 'Yes' : 'No'}}</div>
					</div>
				</div>










				<h4>Volunteer Shifts:</h4>
				<div>
					<!-- Displayed when there are no volunteer shifts -->
					<div ng-if="siteInformation.shifts == undefined || siteInformation.shifts.length <= 0">
						<p class="clear-top">There are no shifts</p>
					</div>

					<!-- Displayed when there are volunteer shifts -->
					<div ng-if="siteInformation.shifts != undefined && siteInformation.shifts.length > 0">
						<table class="wdn_responsive_table">
							<thead>
								<tr>
									<th id="shiftDateHeader">Date</th>
									<th id="shiftStartTimeHeader">Start Time</th>
									<th id="shiftEndTimeHeader">End Time</th>
								</tr>
							</thead>
							<tbody>
								<tr ng-repeat="shift in siteInformation.shifts">
									<th id="shift{{shift.shiftId}}">{{shift.date}}</div>
									<td headers="shiftStartTimeHeader shift{{shift.shiftId}}">{{shift.startTime}}</td>
									<td headers="shiftEndTimeHeader shift{{shift.shiftId}}">{{shift.endTime}}</td>
								</tr>
							</tbody>
						</table>
					</div>

					<!-- Add volunteer shift section -->
					<div ng-if="addShiftButtonClicked === true">
						<h5>Add a Shift</h5>
						<form class="cmxform"
							id="addShiftForm"
							name="form"
							ng-submit="form.$valid && addShiftSaveButtonHandler()" 
							autocomplete="off" 
							novalidate>

							<div id="datePicker" 
								class="form-textfield">
								<label class="form-label form-required">Date</label>
								<input type="text" 
									id="dateInput" 
									name="dateInput" 
									placeholder=" -- Select a Date -- " 
									autocomplete="off"
									required>
								<div ng-show="form.$submitted || form.dateInput.$touched">
									<label class="error" ng-show="form.dateInput.$error.required">This field is required.</label>
								</div>
							</div>

							<div id="startTimeDiv" 
								class="form-select">
								<label class="form-label form-required" for="startTimeSelect">Start Time</label>
								<select id="startTimeSelect" 
									name="startTimeSelect" 
									ng-model="addShiftInformation.selectedStartTime" 
									ng-options="time for time in startTimeOptions"
									ng-change="startTimeChanged(addShiftInformation.selectedStartTime)" 
									required>
									<option value="" style="display:none;">-- Select a Start Time --</option>
								</select>
								<div ng-show="form.$submitted || form.startTimeSelect.$touched">
									<label class="error" ng-show="form.startTimeSelect.$error.required">This field is required.</label>
								</div>
							</div>

							<div id="endTimeDiv" 
								class="form-select">
								<label class="form-label form-required" for="endTimeSelect">End Time</label>
								<select id="endTimeSelect" 
									name="endTimeSelect" 
									ng-model="addShiftInformation.selectedEndTime" 
									ng-options="time for time in endTimeOptions" 
									required>
									<option value="" style="display:none;">-- Select an End Time --</option>
								</select>
								<div ng-show="form.$submitted || form.endTimeSelect.$touched">
									<label class="error" ng-show="form.endTimeSelect.$error.required">This field is required.</label>
								</div>
							</div>


							<button type="button"
								class="wdn-button wdn-button-brand"
								ng-click="addShiftCancelButtonHandler()">Cancel</button>
							<input type="submit" 
								value="Save" 
								class="submit wdn-button wdn-button-triad"
								ng-disabled="form.$valid">
						</form>





						
					</div>
					<button class="wdn-button" 
						ng-click="addShiftButtonHandler()" 
						ng-if="addShiftButtonClicked === false">Add Shift</button>

				</div>














				<h4>Appointment Times:</h4>
				<div>
					<p>Appointment times represent a time slot in which a client can sign up for an appointment. Additionally, appointment times control the rules around the number of appointments that can be scheduled during the time slot.</p>
					<p>Appointment times must follow these rules:</p>
					<ul>
						<li>Scheduled time must be within a volunteer shift</li>
						<li>Scheduled time + approximate length in minutes must be within a volunteer shift</li>
						<li>Two appointment times cannot have the same scheduled time</li>
					</ul>

					<!-- Displayed when there are no appointment times -->
					<div ng-if="siteInformation.appointmentTimes == undefined || siteInformation.appointmentTimes.length <= 0">
						<p class="clear-top">There are no appointment times</p>
					</div>

					<!-- Displayed when there are appointment times -->
					<div ng-if="siteInformation.appointmentTimes != undefined && siteInformation.appointmentTimes.length > 0">
						<table class="wdn_responsive_table">
							<thead>
								<tr>
									<th id="appointmentTimeScheduledTimeHeader">Scheduled Time</th>
									<th id="appointmentTimeMinimumNumberOfAppointmentsHeader">
										Min Appts										
										<a class="tooltip pointer" title="This is the minimum number of appointments for this time slot, meaning at least this many appointments will be allowed to be scheduled even if there are not enough preparers signed up during this time. Default is N/A.">
											<span class="wdn-icon-info" aria-hidden="true"></span>
										</a>
									</th>
									<th id="appointmentTimeMaximumNumberOfAppointmentsHeader">
										Max Appts
										<a class="tooltip pointer" title="This is the maximum number of appointments for this time slot, meaning even if there are more preparers than this number, this is the number of appointments that will be allowed to be scheduled. Default is N/A.">
											<span class="wdn-icon-info" aria-hidden="true"></span>
										</a>
									</th>
									<th id="appointmentTimePercentageAppointmentsHeader">
										Percentage Appts
										<a class="tooltip pointer" title="This specifies the percentage of the preparers that are allotted for appointments, that is: number of appointments allowed to be scheduled = number of preparers * percentage appointments. Default is 100%.">
											<span class="wdn-icon-info" aria-hidden="true"></span>
										</a>
									</th>
									<th id="appointmentTimeApproximateLengthInMinutesHeader">
										Approx Length (minutes)
										<a class="tooltip pointer" title="This is the approximate length in minutes of an appointment. This number is critical to determining how many preparers are available at a certain time. Typically this number will be 60.">
											<span class="wdn-icon-info" aria-hidden="true"></span>
										</a>
									</th>
								</tr>
							</thead>
							<tbody>
								<tr ng-repeat="appointmentTime in siteInformation.appointmentTimes">
									<th id="appointmentTime{{appointmentTime.appointmentTimeId}}">{{appointmentTime.scheduledTime}}</div>
									<td headers="appointmentTimeMinimumNumberOfAppointmentsHeader appointmentTime{{appointmentTime.appointmentTimeId}}">{{appointmentTime.minimumNumberOfAppointments == null ? 'N/A' : appointmentTime.minimumNumberOfAppointments}}</td>
									<td headers="appointmentTimeMaximumNumberOfAppointmentsHeader appointmentTime{{appointmentTime.appointmentTimeId}}">{{appointmentTime.maximumNumberOfAppointments == null ? 'N/A' : appointmentTime.maximumNumberOfAppointments}}</td>
									<td headers="appointmentTimePercentageAppointmentsHeader appointmentTime{{appointmentTime.appointmentTimeId}}">{{appointmentTime.percentageAppointments}}%</td>
									<td headers="appointmentTimeApproximateLengthInMinutesHeader appointmentTime{{appointmentTime.appointmentTimeId}}">{{appointmentTime.approximateLengthInMinutes}}</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>

				<h5>How number of appointments is calculated:</h5>
				<ol>
					<li>If max_appts is not N/A, set available_appointment_spots = max_appts. Otherwise, set available_appointment_spots = max(min_appts, number_of_preparers_signed_up)</li>
					<li>Next, set number_of_appointments_allowed_to_be_scheduled = available_appointment_spots * percentage_appointments</li>
					<li>Finally, round up number_of_appointments_allowed_to_be_scheduled so get a whole number</li>
				</ol>
			</div>

			<!-- Shown if no site is selected -->
			<div ng-if="selectedSite == null" class="centered-text">
				<p>Select a site on the left.</p>
			</div>
		</div>
	</div>
</div>