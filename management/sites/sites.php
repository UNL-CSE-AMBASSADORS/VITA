<div class="dcf-wrapper dcf-pb-8">
	<div class="dcf-grid">
		<!-- Site select list -->
		<div class="dcf-col-25%-start dcf-pr-10">
			<ul class="dcf-list-bare">
				<li class="site-list-element"
					ng-repeat="site in sites"
					ng-click="selectSite(site)">
					<a>{{site.title}}</a>
				</li>
			</ul>

			<!-- Shown when there are no sites -->
			<div ng-if="sites == null" class="centered-text">
				<p>There are no sites.</p>
			</div>
		</div>





		<!-- Site Information Area -->
		<div class="dcf-col-75%-end">
			<!-- Shown if a site is selected and loaded from backend -->
			<div ng-if="selectedSite != null && siteInformation != null">
				<h3 class="clear-top">{{siteInformation.title}}</h3>
				<div class="dcf-columns-2 dcf-mb-4">
					<div class="dcf-col">
						<div><b>Address:</b> {{siteInformation.address}}</div>
						<div><b>Phone Number:</b> {{siteInformation.phoneNumber}}</div>
					</div>
					<div class="dcf-col">
						<div><b>Does International:</b> {{siteInformation.doesInternational ? 'Yes' : 'No'}}</div>
					</div>
				</div>


				<!-- Appointment Times Section -->
				<h4>Appointment Times:</h4>
				<div>
					<p>Appointment times represent a time slot in which a client can sign up for an appointment. Additionally, appointment times control the rules around the number of appointments that can be scheduled during the time slot.</p>
					<p>Appointment times must follow these rules:</p>
					<ul>
						<li>Two appointment times cannot have the same scheduled time</li>
					</ul>

					<!-- Displayed when there are no appointment times -->
					<div ng-if="siteInformation.appointmentTimes == undefined || siteInformation.appointmentTimes.length <= 0">
						<p class="clear-top">There are no appointment times</p>
					</div>

					<!-- Displayed when there are appointment times -->
					<div ng-if="siteInformation.appointmentTimes != undefined && siteInformation.appointmentTimes.length > 0">
						<table class="dcf-table dcf-table-striped">
							<thead>
								<tr>
									<th id="appointmentTimeScheduledTimeHeader">Scheduled Time</th>
									<th id="appointmentTimeMinimumNumberOfAppointmentsHeader">
										Min Appts
										<a class="tooltip pointer" title="This is the minimum number of appointments for this time slot, meaning at least this many appointments will be allowed to be scheduled even if there are not enough preparers signed up during this time. Default is 0.">
											<i class="fas fa-info-circle black-icon" aria-hidden="true"></i>
										</a>
									</th>
									<th id="appointmentTimeMaximumNumberOfAppointmentsHeader">
										Max Appts
										<a class="tooltip pointer" title="This is the maximum number of appointments for this time slot, meaning even if there are more preparers than this number, this is the number of appointments that will be allowed to be scheduled. Default is N/A.">
											<i class="fas fa-info-circle black-icon" aria-hidden="true"></i>
										</a>
									</th>
									<th id="appointmentTimePercentageAppointmentsHeader">
										Percentage Appts
										<a class="tooltip pointer" title="This specifies the percentage of the preparers that are allotted for scheduled (as opposed to walk-in) appointments. That is: number of appointments allowed to be scheduled online = number of preparers * percentage appointments. Default is 100%, max is 300%.">
											<i class="fas fa-info-circle black-icon" aria-hidden="true"></i>
										</a>
									</th>
									<th id="appointmentTimeApproximateLengthInMinutesHeader">
										Approx Length (minutes)
										<a class="tooltip pointer" title="This is the approximate length in minutes of an appointment. This number is critical to determining how many preparers are available at a certain time. Typically this number will be 60.">
											<i class="fas fa-info-circle black-icon" aria-hidden="true"></i>
										</a>
									</th>
								</tr>
							</thead>
							<tbody>
								<tr ng-repeat="appointmentTime in siteInformation.appointmentTimes">
									<th id="appointmentTime{{appointmentTime.appointmentTimeId}}">{{appointmentTime.scheduledTimeString}}</div>
									<td headers="appointmentTimeMinimumNumberOfAppointmentsHeader appointmentTime{{appointmentTime.appointmentTimeId}}">{{appointmentTime.minimumNumberOfAppointments == null || appointmentTime.minimumNumberOfAppointments == 0 ? 'N/A' : appointmentTime.minimumNumberOfAppointments}}</td>
									<td headers="appointmentTimeMaximumNumberOfAppointmentsHeader appointmentTime{{appointmentTime.appointmentTimeId}}">{{appointmentTime.maximumNumberOfAppointments == null ? 'N/A' : appointmentTime.maximumNumberOfAppointments}}</td>
									<td headers="appointmentTimePercentageAppointmentsHeader appointmentTime{{appointmentTime.appointmentTimeId}}">{{appointmentTime.percentageAppointments}}%</td>
									<td headers="appointmentTimeApproximateLengthInMinutesHeader appointmentTime{{appointmentTime.appointmentTimeId}}">{{appointmentTime.approximateLengthInMinutes}}</td>
								</tr>
							</tbody>
						</table>
					</div>


					<!-- Add appointment time section -->
					<div ng-if="addAppointmentTimeButtonClicked === true" class="dcf-mt-5">
						<h5>Add an Appointment Time</h5>
						<form class="cmxform dcf-form"
							id="addAppointmentTimeForm"
							name="form"
							ng-submit="form.$valid && addAppointmentTimeSaveButtonHandler()" 
							autocomplete="off" 
							novalidate>

							<section class="dcf-form-group">
								<label class="dcf-label form-required">Date</label>
								<input type="text" 
									id="addAppointmentTimeDateInput" 
									class="dcf-input-text" 
									name="dateInput" 
									placeholder=" -- Select a Date -- " 
									autocomplete="off"
									required>
								<div ng-show="form.$submitted || form.dateInput.$touched">
									<label class="error" ng-show="form.dateInput.$error.required">This field is required.</label>
								</div>
							</section>

							<section class="dcf-form-group">
								<label class="dcf-label form-required" for="addAppointmentTimeApproximateLengthInMinutes">Approximate Length in Minutes (default 60)</label>
								<input id="addAppointmentTimeApproximateLengthInMinutes"
									type="number"
									class="dcf-input-text" 
									name="approximateLengthInMinutes"
									ng-model="addAppointmentTimeInformation.approximateLengthInMinutes"
									ng-change="addAppointmentTimeApproximateLengthInMinutesChanged(addAppointmentTimeInformation.approximateLengthInMinutes)" 
									min="1"
									required />
								<div ng-show="form.$submitted || form.approximateLengthInMinutes.$touched">
									<label class="error" ng-show="form.approximateLengthInMinutes.$error.required">This field is required.</label>
								</div>
							</section>

							<section class="dcf-form-group">
								<label class="dcf-label form-required" for="addAppointmentTimeScheduledTimeSelect">Scheduled Time</label>
								<select id="addAppointmentTimeScheduledTimeSelect" 
									name="scheduledTimeSelect" 
									class="dcf-input-select dcf-mb-0" 
									ng-model="addAppointmentTimeInformation.selectedScheduledTime" 
									ng-options="time for time in addAppointmentTimeScheduledTimeOptions"
									ng-disabled="addAppointmentTimeInformation.selectedDate == null || addAppointmentTimeInformation.approximateLengthInMinutes == null"
									required>
									<option value="" style="display:none;">-- Select a Scheduled Time --</option>
								</select>
								<div ng-show="form.$submitted || form.scheduledTimeSelect.$touched">
									<label class="error" ng-show="form.scheduledTimeSelect.$error.required">This field is required.</label>
								</div>
							</section>

							<section class="dcf-form-group">
								<label class="dcf-label form-required" for="addAppointmentTimeMinimumNumberOfAppointments">Minimum Number of Appointments (default 0)</label>
								<input id="addAppointmentTimeMinimumNumberOfAppointments"
									type="number"
									class="dcf-input-text" 
									name="minimumNumberOfAppointments"
									ng-model="addAppointmentTimeInformation.minimumNumberOfAppointments"
									min="0"
									required />
								<div ng-show="form.$submitted || form.minimumNumberOfAppointments.$touched">
									<label class="error" ng-show="form.minimumNumberOfAppointments.$error.required">This field is required.</label>
								</div>
							</section>

							<section class="dcf-form-group">
								<label class="dcf-label" for="addAppointmentTimeMaximumNumberOfAppointments">Maximum Number of Appointments (default N/A)</label>
								<input id="addAppointmentTimeMaximumNumberOfAppointments"
									type="number"
									class="dcf-input-text" 
									name="maximumNumberOfAppointments"
									ng-model="addAppointmentTimeInformation.maximumNumberOfAppointments"
									min="0"
									placeholder="N/A" />
								<div ng-show="form.$submitted || form.maximumNumberOfAppointments.$touched">
									<label class="error" ng-show="form.maximumNumberOfAppointments.$error.required">This field is required.</label>
								</div>
							</section>

							<section class="dcf-form-group">
								<label class="dcf-label form-required" for="addAppointmentTimePercentageAppointments">Percentage Appointments (default 100)</label>
								<input id="addAppointmentTimePercentageAppointments"
									type="number"
									class="dcf-input-text" 
									name="percentageAppointments"
									ng-model="addAppointmentTimeInformation.percentageAppointments"
									min="0"
									max="300"
									required />
								<div ng-show="form.$submitted || form.percentageAppointments.$touched">
									<label class="error" ng-show="form.percentageAppointments.$error.required">This field is required.</label>
								</div>
							</section>


							<div class="dcf-mt-3">
								<button type="button"
									class="dcf-btn dcf-btn-secondary"
									ng-click="addAppointmentTimeCancelButtonHandler()">Cancel</button>
								<input type="submit" 
									value="Save" 
									class="submit dcf-btn dcf-btn-primary"
									ng-disabled="!form.$valid || savingAppointmentTime === true">
							</div>
						</form>
					</div>

					<!-- Button for adding an appointment time -->
					<button class="dcf-btn dcf-btn-primary dcf-mt-3" 
						ng-click="addAppointmentTimeButtonHandler()" 
						ng-if="addAppointmentTimeButtonClicked === false">Add Appointment Time</button>
				</div>
			</div>

			<!-- Shown if no site is selected -->
			<div ng-if="selectedSite == null" class="centered-text">
				<p>Select a site on the left.</p>
			</div>
		</div>
	</div>
</div>