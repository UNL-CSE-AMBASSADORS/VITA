define('sitesController', [], function() {

	function sitesController($scope, SitesDataService) {

		$scope.selectedSite = null;
		$scope.sites = [];

		$scope.loadSites = function() {
			SitesDataService.getSites().then((result) => {
				$scope.sites = result;
			});
		}

		// Invoke initially
		$scope.loadSites();

	}

	sitesController.$inject = ['$scope', 'sitesDataService'];

	return sitesController;

});
