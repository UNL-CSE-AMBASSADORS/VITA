require.config({
	paths: {
		angular: '//ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.min',
		ngAnimate: '//ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular-animate.min',
		ngAria: '//ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular-aria.min',
		queueDataService: '/dist/queue/new/queueDataService',
		queueController: '/dist/queue/new/queueController',
		queueSearchFilter: '/dist/queue/new/queueSearchFilter',
		notificationUtilities: '/dist/assets/js/utilities/notificationUtilities',
		'angularjs-dragula': '/dist/assets/js/dragula/dragula.min'
	},
	shim: {
		'ngAnimate': ['angular'],
		'ngAria': ['angular'],
		'queueDataService': ['angular'],
		'queueController': ['angular'],
		'queueSearchFilter': ['angular'],
		'angularjs-dragula': ['angular']
	}
});

require(['angular', 'ngAnimate', 'ngAria'], () => {

	require([
		'queueDataService',
		'queueController',
		'queueSearchFilter',
		'notificationUtilities',
		'angularjs-dragula'
	],
	function (
		QueueDataService,
		QueueController,
		QueueSearchFilter,
		NotificationUtilities,
		angularDragula
	) {
		'use strict';

		// Create the module
		const queueApp = angular.module('queueApp', [angularDragula(angular)]);

		queueApp.factory('queueDataService', QueueDataService);
		queueApp.controller('queueController', QueueController);
		queueApp.directive('queue', () => {
			return {
				controller: 'queueController',
				templateUrl: '/queue/new/queue.php'
			};
		});
		queueApp.filter('searchFor', QueueSearchFilter);

		// Notification utilities
		queueApp.factory('notificationUtilities', NotificationUtilities);

		angular.bootstrap(document.getElementById('queueApp'), ['queueApp']);

	});
});
