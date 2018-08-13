require.config({
	paths: {
		angular: '//ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.min',
		ngAnimate: '//ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular-animate.min',
		ngAria: '//ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular-aria.min',
		clientRescheduleDataService: '/dist/appointment/reschedule/clientRescheduleDataService',
		clientRescheduleController: '/dist/appointment/reschedule/clientRescheduleController'
	},
	shim: {
		'ngAnimate': ['angular'],
		'ngAria': ['angular'],
		'clientRescheduleDataService': ['angular'],
		'clientRescheduleController': ['angular']
	}
});

require(['angular', 'ngAnimate', 'ngAria'], function(){

	require([
		'clientRescheduleDataService',
		'clientRescheduleController'
	],
	function (
		ClientRescheduleDataService,
		ClientRescheduleController
	) {
		'use strict';

		// Create the module
		var clientRescheduleApp = angular.module('clientRescheduleApp', []);

		clientRescheduleApp.factory('clientRescheduleDataService', ClientRescheduleDataService);
		clientRescheduleApp.controller('clientRescheduleController', ClientRescheduleController);
		clientRescheduleApp.directive('clientreschedule', function () {
			return {
				controller: 'clientRescheduleController',
				templateUrl: '/appointment/reschedule/clientReschedule.php'
			};
		});

		angular.bootstrap(document.getElementById('clientRescheduleApp'), ['clientRescheduleApp']);

	});
});
