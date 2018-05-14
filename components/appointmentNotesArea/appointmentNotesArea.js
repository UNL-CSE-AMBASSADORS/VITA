require.config({
	paths: {
		angular: '//ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.min',
		ngAnimate: '//ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular-animate.min',
		ngAria: '//ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular-aria.min',
		appointmentNotesAreaSharedPropertiesService: '/dist/components/appointmentNotesArea/appointmentNotesAreaSharedPropertiesService',
		appointmentNotesAreaDataService: '/dist/components/appointmentNotesArea/appointmentNotesAreaDataService',
		appointmentNotesAreaController: '/dist/components/appointmentNotesArea/appointmentNotesAreaController'
	},
	shim: {
		'ngAnimate': ['angular'],
		'ngAria': ['angular'],
		'appointmentNotesAreaSharedPropertiesService': ['angular'],
		'appointmentNotesAreaDataService': ['angular'],
		'appointmentNotesAreaController': ['angular']
	}
});

require(['angular', 'ngAnimate', 'ngAria'], function(){

	require([
		'appointmentNotesAreaSharedPropertiesService',
		'appointmentNotesAreaDataService',
		'appointmentNotesAreaController'
	],
	function (
		AppointmentNotesAreaSharedPropertiesService,
		AppointmentNotesAreaDataService,
		AppointmentNotesAreaController
	) {
		'use strict';

		// Create the module
		var appointmentNotesAreaApp = angular.module('appointmentNotesAreaApp', []);

		appointmentNotesAreaApp.service('appointmentNotesAreaSharedPropertiesService', AppointmentNotesAreaSharedPropertiesService)
		appointmentNotesAreaApp.factory('appointmentNotesAreaDataService', AppointmentNotesAreaDataService);
		appointmentNotesAreaApp.controller('appointmentNotesAreaController', AppointmentNotesAreaController);
		appointmentNotesAreaApp.directive('appointmentNotesArea', function () {
			return {
				controller: 'appointmentNotesAreaController',
				templateUrl: '/components/appointmentNotesArea/appointmentNotesArea.php'
			};
		});

		angular.bootstrap(document.getElementById('appointmentNotesAreaApp'), ['appointmentNotesAreaApp']);

	});
});
