require.config({
	paths: {
		angular: '//ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.min',
		ngAnimate: '//ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular-animate.min',
		ngAria: '//ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular-aria.min',
		queueDataService: '/dist/queue/queueDataService',
		queueController: '/dist/queue/queueController',
		appointmentNotesAreaSharedPropertiesService: '/dist/components/appointmentNotesArea/appointmentNotesAreaSharedPropertiesService',
		appointmentNotesAreaDataService: '/dist/components/appointmentNotesArea/appointmentNotesAreaDataService',
		appointmentNotesAreaController: '/dist/components/appointmentNotesArea/appointmentNotesAreaController',
		notificationUtilities: '/dist/assets/js/utilities/notificationUtilities',
		'angularjs-dragula': '/dist/assets/js/dragula/dragula.min'
	},
	shim: {
		'ngAnimate': ['angular'],
		'ngAria': ['angular'],
		'queueDataService': ['angular'],
		'queueController': ['angular'],
		'notificationUtilities': ['angular'],
		'appointmentNotesAreaSharedPropertiesService': ['angular'],
		'appointmentNotesAreaDataService': ['angular'],
		'appointmentNotesAreaController': ['angular'],
		'angularjs-dragula': ['angular']
	}
});

require(['angular', 'ngAnimate', 'ngAria'], () => {

	require([
		'queueDataService',
		'queueController',
		'appointmentNotesAreaSharedPropertiesService',
		'appointmentNotesAreaDataService',
		'appointmentNotesAreaController',
		'notificationUtilities',
		'angularjs-dragula'
	],
	function (
		QueueDataService,
		QueueController,
		AppointmentNotesAreaSharedPropertiesService,
		AppointmentNotesAreaDataService,
		AppointmentNotesAreaController,
		NotificationUtilities,
		angularDragula
	) {
		'use strict';

		// Create the module
		const queueApp = angular.module('queueApp', [angularDragula(angular)]);

		// Add Appointment Notes Component
		queueApp.service('appointmentNotesAreaSharedPropertiesService', AppointmentNotesAreaSharedPropertiesService)
		queueApp.factory('appointmentNotesAreaDataService', AppointmentNotesAreaDataService);
		queueApp.controller('appointmentNotesAreaController', AppointmentNotesAreaController);
		queueApp.directive('appointmentNotesArea', function () {
			return {
				controller: 'appointmentNotesAreaController',
				templateUrl: '/components/appointmentNotesArea/appointmentNotesArea.php'
			};
		});

		// Add Queue module itself
		queueApp.factory('queueDataService', QueueDataService);
		queueApp.controller('queueController', QueueController);
		queueApp.directive('queue', () => {
			return {
				controller: 'queueController',
				templateUrl: '/queue/queue.php'
			};
		});

		// Notification utilities
		queueApp.factory('notificationUtilities', NotificationUtilities);

		angular.bootstrap(document.getElementById('queueApp'), ['queueApp']);

	});
});
