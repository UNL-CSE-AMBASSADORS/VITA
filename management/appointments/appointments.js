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
		appointmentPickerController: '/dist/components/appointmentPicker/appointmentPickerController'
	},
	shim: {
		'ngAnimate': ['angular'],
		'ngAria': ['angular'],
		'appointmentsDataService': ['angular'],
		'appointmentsController': ['angular'],
		'appointmentsSearchFilter': ['angular'],
		'sharedPropertiesService': ['angular'],
		'appointmentPickerDataService': ['angular'],
		'appointmentPickerController': ['angular']
	}
});

require(['angular', 'ngAnimate', 'ngAria'], function(){

	require([
		'appointmentsDataService',
		'appointmentsController',
		'appointmentsSearchFilter',
		'appointmentPickerSharedPropertiesService',
		'appointmentPickerDataService',
		'appointmentPickerController'
	],
	function (
		AppointmentsDataService,
		AppointmentsController, 
		AppointmentsSearchFilter,
		SharedPropertiesService,
		AppointmentPickerDataService,
		AppointmentPickerController
	) {
		'use strict';

		// Create the module
		var appointmentsApp = angular.module('appointmentsApp', []);

		appointmentsApp.service('sharedPropertiesService', SharedPropertiesService)
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

		angular.bootstrap(document.getElementById('appointmentsApp'), ['appointmentsApp']);

	});
});
