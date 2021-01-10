define('sitesDataService', [], function($http) {

	function sitesDataService($http) {
		return {
			getSites: () => {
				return $http.get('/server/api/sites/getAll.php?siteId=true&title=true').then((response) => {
					return response.data;
				}, (error) => {
					return null;
				});
			},
			getSiteInformation: (siteId) => {
				return $http.get(`/server/management/sites/sites.php?action=getSiteInformation&siteId=${siteId}`).then((response) => {
					return response.data;
				}, (error) => {
					return null;
				});
			},
			getAppointmentTimesForSite: (siteId) => {
				return $http.get(`/server/management/sites/sites.php?action=getAppointmentTimes&siteId=${siteId}`).then((response) => {
					return response.data;
				}, (error) => {
					return null;
				});
			},
			getAppointmentTypes: () => {
				return $http.get('/server/api/appointments/types/getAll.php').then((response) => {
					return response.data;
				}, (error) => {
					return null;
				});
			},
			addAppointmentTime: (siteId, date, scheduledTime, appointmentTypeId, numberOfAppointments) => {
				return $http({
					url: '/server/management/sites/sites.php',
					method: 'POST',
					data: `action=addAppointmentTime&siteId=${siteId}&date=${date}&scheduledTime=${scheduledTime}&appointmentTypeId=${appointmentTypeId}&numberOfAppointments=${numberOfAppointments}`,
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded'
					}
				}).then((response) => {
					return response.data;
				}, (error) => {
					return null;
				});
			},
			updateAppointmentTime: (appointmentTimeId, numberOfAppointments) => {
				return $http({
					url: '/server/management/sites/sites.php',
					method: 'POST',
					data: `action=updateAppointmentTime&appointmentTimeId=${appointmentTimeId}&numberOfAppointments=${numberOfAppointments}`,
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded'
					}
				}).then((response) => {
					return response.data;
				}, (error) => {
					return null
				});
			}
		};
	};

	sitesDataService.$inject = ['$http'];

	return sitesDataService;
	
});