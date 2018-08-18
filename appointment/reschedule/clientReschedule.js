require.config({
	paths: {
		angular: '//ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.min',
		ngAnimate: '//ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular-animate.min',
		ngAria: '//ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular-aria.min',
		clientRescheduleDataService: '/dist/appointment/reschedule/clientRescheduleDataService',
		clientRescheduleController: '/dist/appointment/reschedule/clientRescheduleController',
		appointmentPickerSharedPropertiesService: '/dist/components/appointmentPicker/appointmentPickerSharedPropertiesService',
		appointmentPickerDataService: '/dist/components/appointmentPicker/appointmentPickerDataService',
		appointmentPickerController: '/dist/components/appointmentPicker/appointmentPickerController',
		notificationUtilities: '/dist/assets/js/utilities/notificationUtilities'
	},
	shim: {
		'ngAnimate': ['angular'],
		'ngAria': ['angular'],
		'clientRescheduleDataService': ['angular'],
		'clientRescheduleController': ['angular'],
		'appointmentPickerSharedPropertiesService': ['angular'],
		'appointmentPickerDataService': ['angular'],
		'appointmentPickerController': ['angular']
	}
});

require(['angular', 'ngAnimate', 'ngAria'], function(){

	require([
		'clientRescheduleDataService',
		'clientRescheduleController',
		'appointmentPickerSharedPropertiesService',
		'appointmentPickerDataService',
		'appointmentPickerController',
		'notificationUtilities'
	],
	function (
		ClientRescheduleDataService,
		ClientRescheduleController,
		AppointmentPickerSharedPropertiesService,
		AppointmentPickerDataService,
		AppointmentPickerController,
		NotificationUtilities
	) {
		'use strict';

		// Create the module
		var clientRescheduleApp = angular.module('clientRescheduleApp', []);

		clientRescheduleApp.factory('clientRescheduleDataService', ClientRescheduleDataService);
		clientRescheduleApp.controller('clientRescheduleController', ClientRescheduleController);
		clientRescheduleApp.directive('clientReschedule', function () {
			return {
				controller: 'clientRescheduleController',
				templateUrl: '/appointment/reschedule/clientReschedule.php',
				scope: { token: '@' } // Passing a "token" attribute when this directive is used: https://stackoverflow.com/a/26409802/3732003
			};
		});

		// Contents for the appointmentPickerApp module
		clientRescheduleApp.service('appointmentPickerSharedPropertiesService', AppointmentPickerSharedPropertiesService)
		clientRescheduleApp.factory('appointmentPickerDataService', AppointmentPickerDataService);
		clientRescheduleApp.controller('appointmentPickerController', AppointmentPickerController);
		clientRescheduleApp.directive('appointmentPicker', function () {
			return {
				controller: 'appointmentPickerController',
				templateUrl: '/components/appointmentPicker/appointmentPicker.php'
			};
		});

		// Notification utilities
		clientRescheduleApp.factory('notificationUtilities', NotificationUtilities);

		angular.bootstrap(document.getElementById('clientRescheduleApp'), ['clientRescheduleApp']);

	});
});
