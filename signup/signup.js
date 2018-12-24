require.config({
	paths: {
		angular: '//ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.min',
		ngAnimate: '//ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular-animate.min',
		ngAria: '//ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular-aria.min',
		ngTouch: '//ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular-touch',
		signupDataService: '/dist/signup/signupDataService',
		signupController: '/dist/signup/signupController',
		appointmentPickerSharedPropertiesService: '/dist/components/appointmentPicker/appointmentPickerSharedPropertiesService',
		appointmentPickerDataService: '/dist/components/appointmentPicker/appointmentPickerDataService',
		appointmentPickerController: '/dist/components/appointmentPicker/appointmentPickerController',
		'bootstrap-ui': '/dist/assets/js/bootstrap/ui-bootstrap-buttons-2.5.0.min',
		notificationUtilities: '/dist/assets/js/utilities/notificationUtilities'
	},
	shim: {
		'ngAnimate': ['angular'],
		'ngAria': ['angular'],
		'ngTouch': ['angular'],
		'signupDataService': ['angular'],
		'signupController': ['angular'],
		'appointmentPickerSharedPropertiesService': ['angular'],
		'appointmentPickerDataService': ['angular'],
		'appointmentPickerController': ['angular'],
		'bootstrap-ui': ['angular']
	}
});

require(['angular', 'ngAnimate', 'ngAria', 'ngTouch', 'bootstrap-ui'], function(){

	require([
		'signupDataService',
		'signupController',
		'appointmentPickerSharedPropertiesService',
		'appointmentPickerDataService',
		'appointmentPickerController',
		'notificationUtilities'
	],
	function (
		SignupDataService,
		SignupController, 
		AppointmentPickerSharedPropertiesService,
		AppointmentPickerDataService,
		AppointmentPickerController,
		NotificationUtilities
	) {
		'use strict';

		// Create the module
		var signupApp = angular.module('signupApp', ['ui.bootstrap']);

		signupApp.factory('signupDataService', SignupDataService);
		signupApp.controller('signupController', SignupController);
		// Please note the slight discrepancy here "signUp" instead of "signup". 
		// The UNL Web Audit didn't like the signup tag for some reason, but seems to be fine with the sign-up tag
		signupApp.directive('signUp', function () {
			return {
				controller: 'signupController',
				templateUrl: '/signup/signup.php'
			};
		});

		// Contents for the appointmentPickerApp module
		signupApp.service('appointmentPickerSharedPropertiesService', AppointmentPickerSharedPropertiesService)
		signupApp.factory('appointmentPickerDataService', AppointmentPickerDataService);
		signupApp.controller('appointmentPickerController', AppointmentPickerController);
		signupApp.directive('appointmentPicker', function () {
			return {
				controller: 'appointmentPickerController',
				templateUrl: '/components/appointmentPicker/appointmentPicker.php'
			};
		});

		// Notification utilities
		signupApp.factory('notificationUtilities', NotificationUtilities);

		angular.bootstrap(document.getElementById('signupApp'), ['signupApp']);

	});
});
