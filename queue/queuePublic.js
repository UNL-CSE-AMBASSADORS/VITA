require.config({
	paths: {
		angular: '//ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.min',
		ngAnimate: '//ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular-animate.min',
		ngAria: '//ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular-aria.min',
		ngMessages: '//ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular-messages.min',
		ngMaterial: '//ajax.googleapis.com/ajax/libs/angular_material/1.1.4/angular-material.min',
		queueDataService: '/dist/queue/queueDataService',
		queueController: '/dist/queue/queueController',
		queueSearchFilter: '/dist/queue/queueSearchFilter'
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
		'queueSearchFilter': ['angular'],
	}
});

require(['angular', 'ngAnimate', 'ngAria', 'ngMessages', 'ngMaterial'], function(){

	require([
		'queueDataService',
		'queueController',
		'queueSearchFilter'
	],
	function (
		QueueDataService,
		QueueController, 
		QueueSearchFilter
	) {
		'use strict';

		// Create the module
		var queueApp = angular.module('queueApp', []);

		queueApp.factory('queueDataService', QueueDataService);
		queueApp.controller('queueController', QueueController);
		queueApp.directive('vitaQueue', function () {
			return {
				controller: 'queueController',
				templateUrl: '/queue/public.php'
			};
		});
		queueApp.filter('searchFor', QueueSearchFilter);

		angular.bootstrap(document.getElementById('queueApp'), ['queueApp']);

	});
});
