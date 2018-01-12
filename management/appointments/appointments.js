require.config({
	paths: {
		angular: '//ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.min',
		ngAnimate: '//ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular-animate.min',
		ngAria: '//ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular-aria.min',
		ngMessages: '//ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular-messages.min',
		ngMaterial: '//ajax.googleapis.com/ajax/libs/angular_material/1.1.4/angular-material.min',
		appointmentsDataService: '/dist/management/appointments/appointmentsDataService',
		appointmentsController: '/dist/management/appointments/appointmentsController',
		appointmentsSearchFilter: '/dist/management/appointments/appointmentsSearchFilter'
	},
	shim: {
		'ngAnimate': ['angular'],
		'ngAria': ['angular'],
		'ngMaterial': {
			deps: ['ngAnimate', 'ngAria']
		},
		'ngMessages': ['angular'],
		'appointmentsDataService': ['angular'],
		'appointmentsController': ['angular'],
		'appointmentsSearchFilter': ['angular'],
	}
});

require(['angular', 'ngAnimate', 'ngAria', 'ngMessages', 'ngMaterial'], function(){

	require([
		'appointmentsDataService',
		'appointmentsController',
		'appointmentsSearchFilter'
	],
	function (
		AppointmentsDataService,
		AppointmentsController, 
		AppointmentsSearchFilter
	) {
		'use strict';

		// Create the module
		var appointmentsApp = angular.module('appointmentsApp', []);

		appointmentsApp.factory('appointmentsDataService', AppointmentsDataService);
		appointmentsApp.controller('appointmentsController', AppointmentsController);
		appointmentsApp.directive('appointments', function () {
			return {
				controller: 'appointmentsController',
				templateUrl: '/management/appointments/appointments.php'
			};
		});
		appointmentsApp.filter('searchFor', AppointmentsSearchFilter);

		angular.bootstrap(document.getElementById('appointmentsApp'), ['appointmentsApp']);

	});
});
