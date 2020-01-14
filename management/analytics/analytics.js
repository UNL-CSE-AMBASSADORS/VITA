require.config({
	paths: {
		angular: '//ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.min',
		ngAnimate: '//ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular-animate.min',
		ngAria: '//ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular-aria.min',
		chartJS: '//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.bundle.min',
		analyticsDataService: '/dist/management/analytics/analyticsDataService',
		analyticsController: '/dist/management/analytics/analyticsController',
		notificationUtilities: '/dist/assets/js/utilities/notificationUtilities'
	},
	shim: {
		'ngAnimate': ['angular'],
		'ngAria': ['angular'],
		'analyticsDataService': ['angular'],
		'analyticsController': ['angular']
	}
});

require(['angular', 'ngAnimate', 'ngAria'], function(){

	require([
		'analyticsDataService',
		'analyticsController',
		'notificationUtilities'
	],
	function (
		AnalyticsDataService,
		AnalyticsController,
		NotificationUtilities
	) {
		'use strict';

		// Create the module
		const analyticsApp = angular.module('analyticsApp', []);
		
		analyticsApp.factory('analyticsDataService', AnalyticsDataService);
		analyticsApp.controller('analyticsController', AnalyticsController);
		analyticsApp.directive('analytics', function () {
			return {
				controller: 'analyticsController',
				templateUrl: '/management/analytics/analytics.php'
			};
		});

		// Set up Notification utilities
		analyticsApp.factory('notificationUtilities', NotificationUtilities);

		angular.bootstrap(document.getElementById('analyticsApp'), ['analyticsApp']);

	});
});
