require.config({
	paths: {
		angular: '//ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.min',
		ngAnimate: '//ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular-animate.min',
		ngAria: '//ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular-aria.min',
		shiftsDataService: '/dist/management/shifts/shiftsDataService',
		shiftsController: '/dist/management/shifts/shiftsController'
	},
	shim: {
		'ngAnimate': ['angular'],
		'ngAria': ['angular'],
		'shiftsDataService': ['angular'],
		'shiftsController': ['angular']
	}
});

require(['angular', 'ngAnimate', 'ngAria'], function(){

	require([
		'shiftsDataService',
		'shiftsController'
	],
	function (
		ShiftsDataService,
		ShiftsController
	) {
		'use strict';

		// Create the module
		var shiftsApp = angular.module('shiftsApp', []);
		
		shiftsApp.factory('shiftsDataService', ShiftsDataService);
		shiftsApp.controller('shiftsController', ShiftsController);
		shiftsApp.directive('shifts', function () {
			return {
				controller: 'shiftsController',
				templateUrl: '/management/shifts/shifts.php'
			};
		});

		angular.bootstrap(document.getElementById('shiftsApp'), ['shiftsApp']);

	});
});
