define('analyticsController', [], function() {

	function analyticsController($scope, AnalyticsDataService, NotificationUtilities) {
		$scope.COLORS = ['#DCECC9', '#AADACC', '#78C6D0', '#48B3D3', '#3E94C0', '#3474AC', '#2A5599', '#203686', '#18216B', '#11174B'];

		$scope.initializeAggregateAppointmentHistoryCharts = () => {
			AnalyticsDataService.getAggregateAppointmentHistory().then((result) => {
				if (result == null || !result.success) {
					NotificationUtilities.giveNotice('Failure', 'Unable to load aggregate appointment history data.', false);
					return;
				}

				const aggregateAppointmentHistory = result.aggregateAppointmentHistory;
				const years = aggregateAppointmentHistory.map((datum) => datum.year);

				require(['chartJS'], () => {
					// Initialize stacked appointment counts chart
					const residentialAppointmentCounts = aggregateAppointmentHistory.map((datum) => datum.numberOfResidentialAppointments);
					const internationalAppointmentCounts = aggregateAppointmentHistory.map((datum) => datum.numberOfInternationalAppointments);
					new Chart('stackedAppointmentCountsChart', {
						type: 'bar',
						data: {
							labels: years,
							datasets: [
								{
									label: '# of Residential Appointments',
									data: residentialAppointmentCounts,
									backgroundColor: 'rgba(208, 0, 0, 0.5)'
								},
								{
									label: '# of International Appointments',
									data: internationalAppointmentCounts,
									backgroundColor: 'rgba(0, 208, 0, 0.5)'
								}
							]
						},
						options: {
							title: {
								display: true,
								text: 'Number of Appointments Per Year'
							},
							tooltips: {
								mode: 'index',
								intersect: false
							},
							scales: {
								xAxes: [{
								   stacked: true
								}],
								yAxes: [{
								   stacked: true
								}]
							}
						}
					});

					// Initialize UNL or Wesleyan counts charts
					const unlOrWesleyanCounts = aggregateAppointmentHistory.map((datum) => datum.numberOfUnlOrWesleyanAppointments);
					new Chart('unlOrWesleyanCountsChart', {
						type: 'bar',
						data: {
							labels: years,
							datasets: [{
								data: unlOrWesleyanCounts,
								backgroundColor: 'rgba(208, 0, 0, 0.5)'
							}]
						},
						options: {
							title: {
								display: true,
								text: 'Number of UNL/Wesleyan Appointments Per Year'
							},
							legend: {
								display: false
							},
							scales: {
								yAxes: [{
									ticks: {
										beginAtZero: true
									}
								}]
							}
						}
					});
				});
			});
		};

		$scope.initializeAppointmentHistoryPerSiteCharts = () => {
			AnalyticsDataService.getAppointmentCountsPerSiteHistory().then((result) => {
				if (result == null || !result.success) {
					NotificationUtilities.giveNotice('Failure', 'Unable to load aggregate appointment history data.', false);
					return;
				}

				const appointmentCountsPerSiteHistory = result.appointmentCountsPerSiteHistory;
				const years = [...new Set(Object.values(appointmentCountsPerSiteHistory).flatMap((site) => Object.keys(site.appointmentCounts)))].sort();
				const dataPerSite = Object.values(appointmentCountsPerSiteHistory).map((site, index) => {
					const color = $scope.COLORS[index % $scope.COLORS.length];
					return {
						label: site.title,
						data: years.map((year) => site.appointmentCounts[year]),
						backgroundColor: color,
						borderColor: color,
						fill: false
					};
				});

				require(['chartJS'], () => {
					new Chart('appointmentCountsPerSiteChart', {
						type: 'line',
						data: {
							labels: years,
							datasets: dataPerSite
						},
						options: {
							responsive: true,
							title: {
								display: true,
								text: 'Number of Appointments Per Site'
							},
							legend: {
								position: 'right'
							},
							tooltips: {
								mode: 'index',
								intersect: false
							},
							hover: {
								mode: 'index',
								intersect: false
							},
							scales: {
								yAxes: [{
									ticks: {
										beginAtZero: true
									}
								}]
							}
						}
					});
				});
			});
		};

		// Invoke initially
		$scope.initializeAggregateAppointmentHistoryCharts();
		$scope.initializeAppointmentHistoryPerSiteCharts();
	};

	analyticsController.$inject = ['$scope', 'analyticsDataService', 'notificationUtilities'];

	return analyticsController;
});
