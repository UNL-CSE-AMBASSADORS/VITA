define('sitesController', [], function() {

	function sitesController($scope, SitesDataService) {
		// For the site select list
		$scope.selectedSite = null;
		$scope.sites = [];

		// Contains site information after a site has been selected
		$scope.siteInformation = null;

		$scope.loadSites = function() {
			SitesDataService.getSites().then((result) => {
				$scope.sites = result;
			});
		};

		$scope.selectSite = function(site) {
			$scope.selectedSite = site;
			$scope.siteInformation = null;

			const siteId = site.siteId;
			SitesDataService.getSiteInformation(siteId).then((result) => {
				console.log(result);
				result.site.doesMultilingual = result.site.doesMultilingual == true; // Do this since we want true/false instead of 1/0
				result.site.doesInternational = result.site.doesMultilingual == true; // Do this since we want true/false instead of 1/0
				$scope.siteInformation = result.site;
			});
		};

		// Invoke initially
		$scope.loadSites();
		WDN.initializePlugin('tooltip');
	}

	sitesController.$inject = ['$scope', 'sitesDataService'];

	return sitesController;

});
