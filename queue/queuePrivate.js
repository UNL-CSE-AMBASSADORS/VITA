require.config({
	paths: {
		angular: '//ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.min',
		ngAnimate: '//ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular-animate.min',
		ngAria: '//ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular-aria.min',
		ngMessages: '//ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular-messages.min',
		ngMaterial: '//ajax.googleapis.com/ajax/libs/angular_material/1.1.4/angular-material.min',
		queueDataService: '/dist/queue/queueDataService',
		queueController: '/dist/queue/queueController',
		queuePrivateController: '/dist/queue/queuePrivateController',
		queueSearchFilter: '/dist/queue/queueSearchFilter',
		appointmentNotesAreaSharedPropertiesService: '/dist/components/appointmentNotesArea/appointmentNotesAreaSharedPropertiesService',
		appointmentNotesAreaDataService: '/dist/components/appointmentNotesArea/appointmentNotesAreaDataService',
		appointmentNotesAreaController: '/dist/components/appointmentNotesArea/appointmentNotesAreaController'
	},
	shim: {
		'ngAnimate': ['angular'],
		'ngAria': ['angular'],
		'ngMaterial': {
			deps: ['ngAnimate', 'ngAria']
		},
		'ngMessages': ['angular'],
		'queueDataService': ['angular'],
		'queueController': ['angular'],
		'queuePrivateController': ['angular'],
		'queueSearchFilter': ['angular'],
		'appointmentNotesAreaSharedPropertiesService': ['angular'],
		'appointmentNotesAreaDataService': ['angular'],
		'appointmentNotesAreaController': ['angular']
	}
});

require(['angular', 'ngAnimate', 'ngAria', 'ngMessages', 'ngMaterial'], function(){

	require([
		'queueDataService',
		'queueController',
		'queuePrivateController',
		'queueSearchFilter',
		'appointmentNotesAreaSharedPropertiesService',
		'appointmentNotesAreaDataService',
		'appointmentNotesAreaController'
	],
	function (
		QueueDataService,
		QueueController, 
		QueuePrivateController,
		QueueSearchFilter,
		AppointmentNotesAreaSharedPropertiesService,
		AppointmentNotesAreaDataService,
		AppointmentNotesAreaController
	) {
		'use strict';

		// Create the module
		var queueApp = angular.module('queueApp', []);

		queueApp.factory('queueDataService', QueueDataService);
		queueApp.service('appointmentNotesAreaSharedPropertiesService', AppointmentNotesAreaSharedPropertiesService)
		queueApp.controller('queueController', QueueController);
		queueApp.controller('queuePrivateController', QueuePrivateController);
		queueApp.directive('vitaQueue', function () {
			return {
				controller: 'queuePrivateController',
				templateUrl: '/queue/private.php'
			};
		});
		queueApp.filter('searchFor', QueueSearchFilter);

		// Contents for the appointmentNotesAreaApp module
		queueApp.factory('appointmentNotesAreaDataService', AppointmentNotesAreaDataService);
		queueApp.controller('appointmentNotesAreaController', AppointmentNotesAreaController);
		queueApp.directive('appointmentNotesArea', function () {
			return {
				controller: 'appointmentNotesAreaController',
				templateUrl: '/components/appointmentNotesArea/appointmentNotesArea.php'
			};
		});

		angular.bootstrap(document.getElementById('queueApp'), ['queueApp']);

	});
});
