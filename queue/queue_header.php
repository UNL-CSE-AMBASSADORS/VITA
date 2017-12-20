<div class="container-fluid dashboard bg-light py-3" ng-cloak>
	<div class="d-flex flex-column flex-sm-row justify-content-between align-items-center">
		<div class="d-flex flex-row">
			<div class="queue-size-lbl">Queue:</div>
			<div class="queue-size-count">{{appointments.length}}</div>
		</div>
		<select 
			class="btn dropdown-toggle" 
			ng-model="selectedSite" 
			ng-options="site.title for site in sites track by site.siteId"
			ng-change="updateAppointmentInformation()">
			
			<option value="" style="display:none;">-- Select A Site --</option>
		</select> 
		<md-datepicker
			ng-model="currentDate"
			ng-change="updateAppointmentInformation()"
			md-placeholder="Enter date"
			md-min-date="today"
			md-hide-icons="calendar">
		</md-datepicker>
		<div class="d-none d-sm-flex flex-row align-items-center">
			<div class="clock-time">{{updateTime | date: "h:mm"}}</div>
			<div class="clock-period d-flex flex-column align-items-center ml-1">
				<div class="clock-am" ng-class="isAm ? '':'inactive-period'">AM</div>
				<div class="clock-pm" ng-class="isAm ? 'inactive-period':''">PM</div>
			</div>
		</div>
	</div>
</div>
