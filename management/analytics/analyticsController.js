define('analyticsController', [], function() {

	function analyticsController($scope, AnalyticsDataService, NotificationUtilities) {

		$scope.sites = [];

		$scope.loadSites = () => {
			AnalyticsDataService.getSites().then((result) => {
				if (result == null) {
					NotificationUtilities.giveNotice('Failure', 'Unable to load the sites.', false);
					return;
				}

				$scope.sites = result;
			});
		};

		// Number of appointments scheduled
		// Number of UNL/Wesleyan appointments scheduled
		// Number of international appointments scheduled
		

		$scope.addDemoData = () => {
			require(['chartJS'], function() {
				var myChart = new Chart('myChart', {
					type: 'bar',
					data: {
						labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
						datasets: [{
							label: '# of Votes',
							data: [12, 19, 3, 5, 2, 3],
							backgroundColor: [
								'rgba(255, 99, 132, 0.2)',
								'rgba(54, 162, 235, 0.2)',
								'rgba(255, 206, 86, 0.2)',
								'rgba(75, 192, 192, 0.2)',
								'rgba(153, 102, 255, 0.2)',
								'rgba(255, 159, 64, 0.2)'
							],
							borderColor: [
								'rgba(255, 99, 132, 1)',
								'rgba(54, 162, 235, 1)',
								'rgba(255, 206, 86, 1)',
								'rgba(75, 192, 192, 1)',
								'rgba(153, 102, 255, 1)',
								'rgba(255, 159, 64, 1)'
							],
							borderWidth: 1
						}]
					},
					options: {
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

		};
		
		// Invoke initially
		$scope.loadSites();
		$scope.addDemoData();
	};

	analyticsController.$inject = ['$scope', 'analyticsDataService', 'notificationUtilities'];

	return analyticsController;

});