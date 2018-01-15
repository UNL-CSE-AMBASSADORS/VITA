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
		'bootstrap-ui': '/dist/assets/js/bootstrap/ui-bootstrap-buttons-2.5.0.min'
	},
	shim: {
		'ngAnimate': ['angular'],
		'ngAria': ['angular'],
		'ngTouch': ['angular'],
		'signupDataService': ['angular'],
		'signupController': ['angular'],
		'sharedPropertiesService': ['angular'],
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
		'appointmentPickerController'
	],
	function (
		SignupDataService,
		SignupController, 
		SharedPropertiesService,
		AppointmentPickerDataService,
		AppointmentPickerController
	) {
		'use strict';

		// Create the module
		var signupApp = angular.module('signupApp', ['ui.bootstrap']);

		signupApp.service('sharedPropertiesService', SharedPropertiesService)
		signupApp.factory('signupDataService', SignupDataService);
		signupApp.controller('signupController', SignupController);
		signupApp.directive('signup', function () {
			return {
				controller: 'signupController',
				templateUrl: '/signup/signup.php'
			};
		});

		// Contents for the appointmentPickerApp module
		signupApp.factory('appointmentPickerDataService', AppointmentPickerDataService);
		signupApp.controller('appointmentPickerController', AppointmentPickerController);
		signupApp.directive('appointmentPicker', function () {
			return {
				controller: 'appointmentPickerController',
				templateUrl: '/components/appointmentPicker/appointmentPicker.php'
			};
		});

		angular.bootstrap(document.getElementById('signupApp'), ['signupApp']);

	});
});
