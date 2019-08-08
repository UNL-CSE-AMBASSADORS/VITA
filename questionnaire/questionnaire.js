require.config({
	paths: {
		angular: '//ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.min',
		ngAnimate: '//ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular-animate.min',
		ngAria: '//ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular-aria.min',
		ngTouch: '//ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular-touch',
		questionnaireController: '/dist/questionnaire/questionnaireController',
		toggleDirective: '/dist/assets/js/utilities/button',
		'bootstrap-ui': '/dist/assets/js/bootstrap/ui-bootstrap-buttons-2.5.0.min'
	},
	shim: {
		'ngAnimate': ['angular'],
		'ngAria': ['angular'],
		'ngTouch': ['angular'],
		'questionnaireController': ['angular'],
		'toggleDirective': ['angular'],
		'bootstrap-ui': ['angular']
	}
});

require(['angular', 'ngAnimate', 'ngAria', 'ngTouch', 'bootstrap-ui'], function(){

	require([
		'questionnaireController',
		'toggleDirective'
	],
	function (
		QuestionnaireController,
		ToggleDirective
	) {
		'use strict';

		// Create the module
		const questionnaireApp = angular.module('questionnaireApp', ['ui.bootstrap']);

		questionnaireApp.controller('questionnaireController', QuestionnaireController);
		questionnaireApp.directive('questionnaire', function () {
			return {
				controller: 'questionnaireController',
				templateUrl: '/questionnaire/questionnaire.php'
			}
		});

		questionnaireApp.directive('toggle', ToggleDirective);

		angular.bootstrap(document.getElementById('questionnaireApp'), ['questionnaireApp']);

	});
});
