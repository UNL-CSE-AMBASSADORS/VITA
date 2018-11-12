require.config({
	paths: {
		angular: '//ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.min',
		ngAnimate: '//ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular-animate.min',
		ngAria: '//ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular-aria.min',
		selfServiceAppointmentRescheduleDataService: '/dist/appointment/reschedule/selfServiceAppointmentRescheduleDataService',
		selfServiceAppointmentRescheduleController: '/dist/appointment/reschedule/selfServiceAppointmentRescheduleController',
		appointmentPickerSharedPropertiesService: '/dist/components/appointmentPicker/appointmentPickerSharedPropertiesService',
		appointmentPickerDataService: '/dist/components/appointmentPicker/appointmentPickerDataService',
		appointmentPickerController: '/dist/components/appointmentPicker/appointmentPickerController',
		notificationUtilities: '/dist/assets/js/utilities/notificationUtilities'
	},
	shim: {
		'ngAnimate': ['angular'],
		'ngAria': ['angular'],
		'selfServiceAppointmentRescheduleDataService': ['angular'],
		'selfServiceAppointmentRescheduleController': ['angular'],
		'appointmentPickerSharedPropertiesService': ['angular'],
		'appointmentPickerDataService': ['angular'],
		'appointmentPickerController': ['angular']
	}
});

require(['angular', 'ngAnimate', 'ngAria'], function(){

	require([
		'selfServiceAppointmentRescheduleDataService',
		'selfServiceAppointmentRescheduleController',
		'appointmentPickerSharedPropertiesService',
		'appointmentPickerDataService',
		'appointmentPickerController',
		'notificationUtilities'
	],
	function (
		SelfServiceAppointmentRescheduleDataService,
		SelfServiceAppointmentRescheduleController,
		AppointmentPickerSharedPropertiesService,
		AppointmentPickerDataService,
		AppointmentPickerController,
		NotificationUtilities
	) {
		'use strict';

		// Create the module
		var selfServiceAppointmentRescheduleApp = angular.module('selfServiceAppointmentRescheduleApp', []);

		selfServiceAppointmentRescheduleApp.factory('selfServiceAppointmentRescheduleDataService', SelfServiceAppointmentRescheduleDataService);
		selfServiceAppointmentRescheduleApp.controller('selfServiceAppointmentRescheduleController', SelfServiceAppointmentRescheduleController);
		selfServiceAppointmentRescheduleApp.directive('selfServiceAppointmentReschedule', function () {
			return {
				controller: 'selfServiceAppointmentRescheduleController',
				templateUrl: '/appointment/reschedule/selfServiceAppointmentReschedule.php',
				scope: { token: '@' } // Passing a "token" attribute when this directive is used: https://stackoverflow.com/a/26409802/3732003
			};
		});

		// Contents for the appointmentPickerApp module
		selfServiceAppointmentRescheduleApp.service('appointmentPickerSharedPropertiesService', AppointmentPickerSharedPropertiesService)
		selfServiceAppointmentRescheduleApp.factory('appointmentPickerDataService', AppointmentPickerDataService);
		selfServiceAppointmentRescheduleApp.controller('appointmentPickerController', AppointmentPickerController);
		selfServiceAppointmentRescheduleApp.directive('appointmentPicker', function () {
			return {
				controller: 'appointmentPickerController',
				templateUrl: '/components/appointmentPicker/appointmentPicker.php'
			};
		});

		// Notification utilities
		selfServiceAppointmentRescheduleApp.factory('notificationUtilities', NotificationUtilities);

		angular.bootstrap(document.getElementById('selfServiceAppointmentRescheduleApp'), ['selfServiceAppointmentRescheduleApp']);

	});
});
