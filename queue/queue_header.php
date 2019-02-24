<div class="dcf-bleed unl-bg-lightest-gray" ng-cloak>
	<div class="dcf-grid-full dcf-grid-halves@md dcf-col-gap-1 dcf-row-gap-4 dcf-p-5">
	<!-- <div class="dcf-d-flex dcf-p-5"> -->

		<div class="dcf-d-flex dcf-m-0 dcf-flex-row dcf-jc-center dcf-1st">
			<h1 class="dcf-m-0">
				<span class="dcf-txt-right">Queue:&nbsp;</span>
				<span>{{appointments.length}}</span>
			</h1>
		</div>

		<div class="dcf-d-flex dcf-flex-row dcf-jc-flex-end dcf-2nd">
			<div class="clock-time">{{updateTime | date: "h:mm"}}</div>
			<div class="clock-period">
				<div class="clock-am" ng-class="isAm ? '':'inactive-period'">AM</div>
				<div class="clock-pm" ng-class="isAm ? 'inactive-period':''">PM</div>
			</div>
		</div>

		<div class="dcf-1st dcf-2nd@md">
			<label for="siteSelect" class="dcf-label">Site</label>
			<select id="siteSelect"
				class="dropdown-toggle wdn-col dcf-input-select dcf-mb-0" 
				ng-model="selectedSite" 
				ng-options="site.title for site in sites track by site.siteId"
				ng-change="siteChanged()">
				<option value="" style="display:none;">-- Select A Site --</option>
			</select> 
		</div>
		
		<div class="dcf-1st dcf-2nd@md">
			<label for="dateInput" class="dcf-label">Date</label>
			<input type="text" id="dateInput" class="wdn-col dcf-input-text" />
		</div>

	</div>
</div>
