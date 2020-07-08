require.config({
	paths: {
		angular: '//ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.min',
		ngAnimate: '//ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular-animate.min',
		ngAria: '//ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular-aria.min',
		sitesDataService: '/dist/management/sites/sitesDataService',
		sitesController: '/dist/management/sites/sitesController',
		// TODO: Consider moving this filter to a utilities or something?
		fromMapFilter: '/dist/management/sites/fromMapFilter',
		notificationUtilities: '/dist/assets/js/utilities/notificationUtilities'
	},
	shim: {
		'ngAnimate': ['angular'],
		'ngAria': ['angular'],
		'sitesDataService': ['angular'],
		'sitesController': ['angular'],
		'fromMapFilter': ['angular']
	}
});

require(['angular', 'ngAnimate', 'ngAria'], function(){

	require([
		'sitesDataService',
		'sitesController',
		'fromMapFilter',
		'notificationUtilities'
	],
	function (
		SitesDataService,
		SitesController,
		FromMapFilter,
		NotificationUtilities
	) {
		'use strict';

		// Create the module
		const sitesApp = angular.module('sitesApp', []);
		
		sitesApp.factory('sitesDataService', SitesDataService);
		sitesApp.controller('sitesController', SitesController);
		sitesApp.directive('sites', function () {
			return {
				controller: 'sitesController',
				templateUrl: '/management/sites/sites.php'
			};
		});

		// Set up FromMap utility
		sitesApp.filter('fromMapFilter', FromMapFilter);

		// Set up Notification utilities
		sitesApp.factory('notificationUtilities', NotificationUtilities);

		angular.bootstrap(document.getElementById('sitesApp'), ['sitesApp']);

	});
});
