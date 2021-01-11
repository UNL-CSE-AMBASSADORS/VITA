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
				<div class="dcf-mb-4">
					<div><b>Address:</b> {{siteInformation.address}}</div>
					<div><b>Phone Number:</b> {{siteInformation.phoneNumber}}</div>
				</div>


				<!-- Appointment Times Section -->
				<h4>Appointment Times:</h4>
				<div>
					<p>Appointment times represents a time slot in which a client can sign up for an appointment and the rules surrounding the number of appointments during that time slot.

					<!-- Add appointment time section -->
					<div class="dcf-mb-5">
						<div ng-if="addAppointmentTimeButtonClicked === true">
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
									<label class="dcf-label form-required" for="addAppointmentTimeScheduledTimeSelect">Scheduled Time</label>
									<select id="addAppointmentTimeScheduledTimeSelect" 
										name="scheduledTimeSelect" 
										class="dcf-input-select dcf-mb-0" 
										ng-model="addAppointmentTimeInformation.selectedScheduledTime" 
										ng-options="time for time in addAppointmentTimeScheduledTimeOptions"
										required>
										<option value="" style="display:none;">-- Select a Scheduled Time --</option>
									</select>
									<div ng-show="form.$submitted || form.scheduledTimeSelect.$touched">
										<label class="error" ng-show="form.scheduledTimeSelect.$error.required">This field is required.</label>
									</div>
								</section>

								<section class="dcf-form-group">
									<label class="dcf-label form-required" for="addAppointmentTimeAppointmentTypeSelect">Appointment Type</label>
									<select id="addAppointmentTimeAppointmentTypeSelect"
										name="appointmentTypeSelect"
										class="dcf-input-select"
										ng-model="addAppointmentTimeInformation.selectedAppointmentTypeId"
										required>
										<option value="" style="display:none;">-- Select An Appointment Type --</option>
										<option ng-repeat="option in appointmentTypes" ng-value="option.appointmentTypeId">{{option.name}}</option>
									</select>
									<div ng-show="form.$submitted || form.appointmentTypeSelect.$touched">
										<label class="error" ng-show="form.appointmentTypeSelect.$error.required">This field is required.</label>
									</div>
								</section>

								<section class="dcf-form-group">
									<label class="dcf-label form-required" for="addAppointmentTimeNumberOfAppointments">Number of Appointments</label>
									<input id="addAppointmentTimeNumberOfAppointments"
										type="number"
										class="dcf-input-text" 
										name="numberOfAppointments"
										ng-model="addAppointmentTimeInformation.numberOfAppointments"
										min="0"
										required />
									<div ng-show="form.$submitted || form.numberOfAppointments.$touched">
										<label class="error" ng-show="form.numberOfAppointments.$error.required">This field is required.</label>
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



					<!-- Displayed when there are no appointment times -->
					<div ng-if="siteInformation.appointmentTimes == undefined || siteInformation.appointmentTimes.length <= 0">
						<p>There are no appointment times yet</p>
					</div>

					<!-- Displayed when there are appointment times -->
					<div ng-if="siteInformation.appointmentTimes != undefined && siteInformation.appointmentTimes.length > 0">
						<div ng-repeat="(scheduledDateString, appointmentTimes) in siteInformation.appointmentTimesMap | fromMapFilter" class="dcf-mb-5">
							<h5>{{scheduledDateString}}</h5>
							<table class="dcf-table dcf-table-striped dcf-w-100%">
								<thead>
									<tr>
										<th id="appointmentTimeScheduledTimeHeader">Scheduled Time</th>
										<th id="appointmentTimeAppointmentTypeHeader">Type</th>
										<th id="appointmentTimeNumberOfAppointmentsHeader">Appointments (scheduled/allowed)</th>
										<th id="appointmentTimeEditHeader">Edit</th>
									</tr>
								</thead>
								<tbody>
									<tr ng-repeat="appointmentTime in appointmentTimes">
										<th id="appointmentTime{{appointmentTime.appointmentTimeId}}">{{appointmentTime.scheduledTimeString}}</th>
										<td headers="appointmentTimeAppointmentTypeHeader appointmentTime{{appointmentTime.appointmentTimeId}}">{{appointmentTime.appointmentTypeName}}</td>
										<td headers="appointmentTimeNumberOfAppointmentsHeader appointmentTime{{appointmentTime.appointmentTimeId}}">
											{{appointmentTime.numberOfAppointmentsAlreadyScheduled}} /
											<span ng-show="!isAppointmentTimeRowBeingEdited(appointmentTime.appointmentTimeId)">
												{{appointmentTime.numberOfAppointments}}
											</span>
											<span ng-show="isAppointmentTimeRowBeingEdited(appointmentTime.appointmentTimeId)">
												<input id="addAppointmentTimeNumberOfAppointments"
													type="number"
													class="dcf-input-text dcf-d-inline" 
													ng-model="appointmentTimesEditMap.get(appointmentTime.appointmentTimeId).newNumberOfAppointments"
													min="0"
													required />
											</span>
										</td>
										<td headers="appointmentTimeEditHeader appointmentTime{{appointmentTime.appointmentTimeId}}">
											<div ng-show="!isAppointmentTimeRowBeingEdited(appointmentTime.appointmentTimeId)">
												<button type="button"
													class="dcf-btn dcf-btn-primary"
													ng-click="editAppointmentTimesEditButtonHandler(appointmentTime)">
													Edit
												</button>
											</div>

											<div ng-show="isAppointmentTimeRowBeingEdited(appointmentTime.appointmentTimeId)">
												<button type="button"
													class="dcf-btn dcf-btn-secondary"
													ng-click="appointmentTimesEditMap.get(appointmentTime.appointmentTimeId).editing = false">
													Cancel
												</button>
												<button type="button"
													class="dcf-btn dcf-btn-primary"
													ng-click="editAppointmentTimeSaveButtonHandler(appointmentTime)">
													Save
												</button>
											</div>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>

			<!-- Shown if no site is selected -->
			<div ng-if="selectedSite == null" class="centered-text">
				<p>Select a site on the left.</p>
			</div>
		</div>
	</div>
</div>