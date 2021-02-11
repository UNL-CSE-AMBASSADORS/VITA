require.config({
	paths: {
		angular: '//ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.min',
		ngAnimate: '//ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular-animate.min',
		ngAria: '//ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular-aria.min',
		uploadDocumentsDataService: '/dist/appointment/upload-documents/uploadDocumentsDataService',
		uploadDocumentsController: '/dist/appointment/upload-documents/uploadDocumentsController',
		toggleDirective: '/dist/assets/js/utilities/button',
		'bootstrap-ui': '/dist/assets/js/bootstrap/ui-bootstrap-buttons-2.5.0.min',
		notificationUtilities: '/dist/assets/js/utilities/notificationUtilities'
	},
	shim: {
		'ngAnimate': ['angular'],
		'ngAria': ['angular'],
		'uploadDocumentsDataService': ['angular'],
		'uploadDocumentsController': ['angular'],
		'toggleDirective': ['angular'],
		'bootstrap-ui': ['angular']
	}
});

require(['angular', 'ngAnimate', 'ngAria', 'bootstrap-ui'], function(){

	require([
		'uploadDocumentsDataService',
		'uploadDocumentsController',
		'toggleDirective',
		'notificationUtilities'
	],
	function (
		UploadDocumentsDataService,
		UploadDocumentsController,
		ToggleDirective,
		NotificationUtilities
	) {
		'use strict';

		// Create the module
		var uploadDocumentsApp = angular.module('uploadDocumentsApp', ['ui.bootstrap']);

		uploadDocumentsApp.factory('uploadDocumentsDataService', UploadDocumentsDataService);
		uploadDocumentsApp.controller('uploadDocumentsController', UploadDocumentsController);
		uploadDocumentsApp.directive('uploadDocuments', function () {
			return {
				controller: 'uploadDocumentsController',
				templateUrl: '/appointment/upload-documents/uploadDocuments.php',
				scope: { token: '@' } // Passing a "token" attribute when this directive is used: https://stackoverflow.com/a/26409802/3732003
			};
		});
		uploadDocumentsApp.directive('toggle', ToggleDirective);

		// Notification utilities
		uploadDocumentsApp.factory('notificationUtilities', NotificationUtilities);

		angular.bootstrap(document.getElementById('uploadDocumentsApp'), ['uploadDocumentsApp']);

	});
});
