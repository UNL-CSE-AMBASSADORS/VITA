require.config({
	paths: {
		angular: '//ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.min',
		ngAnimate: '//ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular-animate.min',
		ngAria: '//ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular-aria.min',
		ngMessages: '//ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular-messages.min',
		ngMaterial: '//ajax.googleapis.com/ajax/libs/angular_material/1.1.4/angular-material.min',
		queueDataService: '/queue/queueDataService',
		queueController: '/queue/queueController',
		queuePrivateController: '/queue/queuePrivateController',
		queueSearchFilter: '/queue/queueSearchFilter'
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

	}
});

require(['angular', 'ngAnimate', 'ngAria', 'ngMessages', 'ngMaterial'], function(){

	require([
		'queueDataService',
		'queueController',
		'queuePrivateController',
		'queueSearchFilter'
	],
	function (
		QueueDataService,
		QueueController, 
		QueuePrivateController,
		QueueSearchFilter
	) {
		'use strict';

		// Create the module
		var queueApp = angular.module('queueApp', []);

		queueApp.factory('queueDataService', QueueDataService);
		queueApp.controller('queueController', QueueController);
		queueApp.controller('queuePrivateController', QueuePrivateController);
		queueApp.directive('privateQueue', function () {
			return {
				controller: 'queuePrivateController',
				templateUrl: '/queue/private.php'
			};
		});
		queueApp.filter('searchFor', QueueSearchFilter);

	});
});
