<div class="dcf-bleed unl-bg-lightest-gray dcf-p-5" ng-cloak>
	<div class="dcf-d-flex dcf-jc-between">
		<div>
			<h1 class="dcf-m-0">
				<span class="dcf-txt-right">Queue:&nbsp;</span>
				<span>{{appointments.length}}</span>
			</h1>
		</div>

		<div class="dcf-d-flex dcf-flex-row">
			<div class="clock-time">{{updateTime | date: "h:mm"}}</div>
			<div class="clock-period">
				<div class="clock-am" ng-class="isAm ? 'active-period':'inactive-period'">AM</div>
				<div class="clock-pm" ng-class="isAm ? 'inactive-period':'active-period'">PM</div>
			</div>
		</div>
	</div>

	<div class="dcf-grid dcf-grid-halves dcf-col-gap-2">
		<div>
			<label for="siteSelect" class="dcf-label">Site</label>
			<select id="siteSelect"
				class="dropdown-toggle dcf-w-100% dcf-input-select dcf-mb-0" 
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
	</div>
</div>
