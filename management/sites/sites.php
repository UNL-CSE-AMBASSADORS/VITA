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
				<div ng-if="siteInformation.shifts == undefined || siteInformation.shifts.length <= 0">
					There are no shifts
				</div>
				<div>
					<table class="wdn_responsive_table">
						<caption>Volunteer Shifts</caption>
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














				<h4>Appointment Times:</h4>
				<div>
					A bunch of appointment times coming up!
				</div>
			</div>

			<!-- Shown if no site is selected -->
			<div ng-if="selectedSite == null" class="centered-text">
				<p>Select a site on the left.</p>
			</div>
		</div>
	</div>
</div>