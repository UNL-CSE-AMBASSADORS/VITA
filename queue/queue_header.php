<div class="wdn-band wdn-light-neutral-band" ng-cloak>
	<div class="wdn-grid-set dashboard">

		<div class="queue-count bp768-wdn-col-one-fourth bp480-wdn-col-one-third">
			<div class="queue-size-lbl left-half">Queue:</div>
			<div class="queue-size-count right-half">{{appointments.length}}</div>
		</div>

		<div class="bp768-wdn-col-one-fourth bp480-wdn-col-one-third">
			<label for="siteSelect">Site</label>
			<select id="siteSelect"
				class="dropdown-toggle wdn-col" 
				ng-model="selectedSite" 
				ng-options="site.title for site in sites track by site.siteId"
				ng-change="siteChanged()">
				<option value="" style="display:none;">-- Select A Site --</option>
			</select> 
		</div>
		
		<div class="bp768-wdn-col-one-fourth bp480-wdn-col-one-third">
			<label for="dateInput">Date</label>
			<input type="text" id="dateInput" class="wdn-col" />
		</div>

		<div class="bp768-wdn-col-one-fourth bp-768-d-none">
			<div class="clock-time left-half">{{updateTime | date: "h:mm"}}</div>
			<div class="clock-period right-half">
				<div class="clock-am" ng-class="isAm ? '':'inactive-period'">AM</div>
				<div class="clock-pm" ng-class="isAm ? 'inactive-period':''">PM</div>
			</div>
		</div>

	</div>
</div>
