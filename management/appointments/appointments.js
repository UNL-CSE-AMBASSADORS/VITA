require.config({
	paths: {
		angular: '//ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.min',
		ngAnimate: '//ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular-animate.min',
		ngAria: '//ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular-aria.min',
		appointmentsDataService: '/dist/management/appointments/appointmentsDataService',
		appointmentsController: '/dist/management/appointments/appointmentsController',
		appointmentsSearchFilter: '/dist/management/appointments/appointmentsSearchFilter',
		appointmentPickerSharedPropertiesService: '/dist/components/appointmentPicker/appointmentPickerSharedPropertiesService',
		appointmentPickerDataService: '/dist/components/appointmentPicker/appointmentPickerDataService',
		appointmentPickerController: '/dist/components/appointmentPicker/appointmentPickerController',
		appointmentNotesAreaSharedPropertiesService: '/dist/components/appointmentNotesArea/appointmentNotesAreaSharedPropertiesService',
		appointmentNotesAreaDataService: '/dist/components/appointmentNotesArea/appointmentNotesAreaDataService',
		appointmentNotesAreaController: '/dist/components/appointmentNotesArea/appointmentNotesAreaController'
	},
	shim: {
		'ngAnimate': ['angular'],
		'ngAria': ['angular'],
		'appointmentsDataService': ['angular'],
		'appointmentsController': ['angular'],
		'appointmentsSearchFilter': ['angular'],
		'appointmentPickerSharedPropertiesService': ['angular'],
		'appointmentPickerDataService': ['angular'],
		'appointmentPickerController': ['angular'],
		'appointmentNotesAreaSharedPropertiesService': ['angular'],
		'appointmentNotesAreaDataService': ['angular'],
		'appointmentNotesAreaController': ['angular']
	}
});

require(['angular', 'ngAnimate', 'ngAria'], function(){

	require([
		'appointmentsDataService',
		'appointmentsController',
		'appointmentsSearchFilter',
		'appointmentPickerSharedPropertiesService',
		'appointmentPickerDataService',
		'appointmentPickerController',
		'appointmentNotesAreaSharedPropertiesService',
		'appointmentNotesAreaDataService',
		'appointmentNotesAreaController'
	],
	function (
		AppointmentsDataService,
		AppointmentsController, 
		AppointmentsSearchFilter,
		AppointmentPickerSharedPropertiesService,
		AppointmentPickerDataService,
		AppointmentPickerController,
		AppointmentNotesAreaSharedPropertiesService,
		AppointmentNotesAreaDataService,
		AppointmentNotesAreaController
	) {
		'use strict';

		// Create the module
		var appointmentsApp = angular.module('appointmentsApp', []);
		
		appointmentsApp.service('appointmentPickerSharedPropertiesService', AppointmentPickerSharedPropertiesService)
		appointmentsApp.service('appointmentNotesAreaSharedPropertiesService', AppointmentNotesAreaSharedPropertiesService)
		appointmentsApp.factory('appointmentsDataService', AppointmentsDataService);
		appointmentsApp.controller('appointmentsController', AppointmentsController);
		appointmentsApp.directive('appointments', function () {
			return {
				controller: 'appointmentsController',
				templateUrl: '/management/appointments/appointments.php'
			};
		});
		appointmentsApp.filter('searchFor', AppointmentsSearchFilter);

		// Contents for the appointmentPickerApp module
		appointmentsApp.factory('appointmentPickerDataService', AppointmentPickerDataService);
		appointmentsApp.controller('appointmentPickerController', AppointmentPickerController);
		appointmentsApp.directive('appointmentPicker', function () {
			return {
				controller: 'appointmentPickerController',
				templateUrl: '/components/appointmentPicker/appointmentPicker.php'
			};
		});

		// Contents for the appointmentNotesAreaApp module
		appointmentsApp.factory('appointmentNotesAreaDataService', AppointmentNotesAreaDataService);
		appointmentsApp.controller('appointmentNotesAreaController', AppointmentNotesAreaController);
		appointmentsApp.directive('appointmentNotesArea', function () {
			return {
				controller: 'appointmentNotesAreaController',
				templateUrl: '/components/appointmentNotesArea/appointmentNotesArea.php'
			};
		});

		angular.bootstrap(document.getElementById('appointmentsApp'), ['appointmentsApp']);

	});
});
