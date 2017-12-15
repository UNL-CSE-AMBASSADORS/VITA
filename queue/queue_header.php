<div class="container-fluid dashboard bg-light py-3" ng-cloak>
	<div class="d-flex flex-row justify-content-between align-items-center">
		<div class="d-flex flex-row">
			<div class="queue-size-lbl">Queue:</div>
			<div class="queue-size-count">{{appointments.length}}</div>
		</div>
		<div class="siteSelection">
        <button class="btn btn-default dropdown-toggle" type="button" id="site" data-toggle="dropdown"></button>
        <ul class="dropdown-menu">
            <li ng-repeat="site in sites">{{site.title}}</li>
        </ul>
    </div>
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
