/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 3);
/******/ })
/************************************************************************/
/******/ ({

/***/ 3:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


queueApp.controller("QueuePrivateController", function ($scope, $controller, QueueService) {
	angular.extend(this, $controller('QueueController', { $scope: $scope }));

	$scope.selectClient = function (client) {
		$scope.client = client;
	};
});

queueApp.filter('searchFor', function () {

	// All filters must return a function. The first parameter
	// is the data that is to be filtered, and the second is an
	// argument that may be passed with a colon (searchFor:searchString)

	return function (arr, searchString) {

		if (!searchString) {
			return arr;
		}

		var result = [];

		searchString = searchString.toLowerCase();

		// Using the forEach helper method to loop through the array
		angular.forEach(arr, function (item) {

			if (item.name.toLowerCase().indexOf(searchString) !== -1 || item.appointmentId.toString().indexOf(searchString) !== -1 || ("#" + item.appointmentId.toString()).indexOf(searchString) !== -1) {
				result.push(item);
			}
		});

		return result;
	};
});

/***/ })

/******/ });
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vd2VicGFjay9ib290c3RyYXAgZjQ1NzQ5ZTk4OGEyNjhkYTU4MzQiLCJ3ZWJwYWNrOi8vLy4vcXVldWUvcXVldWVfcHJpdmF0ZS5qcyJdLCJuYW1lcyI6WyJxdWV1ZUFwcCIsImNvbnRyb2xsZXIiLCIkc2NvcGUiLCIkY29udHJvbGxlciIsIlF1ZXVlU2VydmljZSIsImFuZ3VsYXIiLCJleHRlbmQiLCJzZWxlY3RDbGllbnQiLCJjbGllbnQiLCJmaWx0ZXIiLCJhcnIiLCJzZWFyY2hTdHJpbmciLCJyZXN1bHQiLCJ0b0xvd2VyQ2FzZSIsImZvckVhY2giLCJpdGVtIiwibmFtZSIsImluZGV4T2YiLCJhcHBvaW50bWVudElkIiwidG9TdHJpbmciLCJwdXNoIl0sIm1hcHBpbmdzIjoiO0FBQUE7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7OztBQUdBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQUs7QUFDTDtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBLG1DQUEyQiwwQkFBMEIsRUFBRTtBQUN2RCx5Q0FBaUMsZUFBZTtBQUNoRDtBQUNBO0FBQ0E7O0FBRUE7QUFDQSw4REFBc0QsK0RBQStEOztBQUVySDtBQUNBOztBQUVBO0FBQ0E7Ozs7Ozs7Ozs7O0FDN0RBQSxTQUFTQyxVQUFULENBQW9CLHdCQUFwQixFQUE4QyxVQUFTQyxNQUFULEVBQWlCQyxXQUFqQixFQUE4QkMsWUFBOUIsRUFBNEM7QUFDekZDLFNBQVFDLE1BQVIsQ0FBZSxJQUFmLEVBQXFCSCxZQUFZLGlCQUFaLEVBQStCLEVBQUNELFFBQVFBLE1BQVQsRUFBL0IsQ0FBckI7O0FBRUFBLFFBQU9LLFlBQVAsR0FBc0IsVUFBU0MsTUFBVCxFQUFpQjtBQUN0Q04sU0FBT00sTUFBUCxHQUFnQkEsTUFBaEI7QUFDQSxFQUZEO0FBR0EsQ0FORDs7QUFRQVIsU0FBU1MsTUFBVCxDQUFnQixXQUFoQixFQUE2QixZQUFVOztBQUV0QztBQUNBO0FBQ0E7O0FBRUEsUUFBTyxVQUFTQyxHQUFULEVBQWNDLFlBQWQsRUFBMkI7O0FBRWpDLE1BQUcsQ0FBQ0EsWUFBSixFQUFpQjtBQUNoQixVQUFPRCxHQUFQO0FBQ0E7O0FBRUQsTUFBSUUsU0FBUyxFQUFiOztBQUVBRCxpQkFBZUEsYUFBYUUsV0FBYixFQUFmOztBQUVBO0FBQ0FSLFVBQVFTLE9BQVIsQ0FBZ0JKLEdBQWhCLEVBQXFCLFVBQVNLLElBQVQsRUFBYzs7QUFFbEMsT0FBR0EsS0FBS0MsSUFBTCxDQUFVSCxXQUFWLEdBQXdCSSxPQUF4QixDQUFnQ04sWUFBaEMsTUFBa0QsQ0FBQyxDQUFuRCxJQUNESSxLQUFLRyxhQUFMLENBQW1CQyxRQUFuQixHQUE4QkYsT0FBOUIsQ0FBc0NOLFlBQXRDLE1BQXdELENBQUMsQ0FEeEQsSUFFRCxDQUFDLE1BQU1JLEtBQUtHLGFBQUwsQ0FBbUJDLFFBQW5CLEVBQVAsRUFBc0NGLE9BQXRDLENBQThDTixZQUE5QyxNQUFnRSxDQUFDLENBRm5FLEVBRXFFO0FBQ3BFQyxXQUFPUSxJQUFQLENBQVlMLElBQVo7QUFDQTtBQUVELEdBUkQ7O0FBVUEsU0FBT0gsTUFBUDtBQUNBLEVBdEJEO0FBd0JBLENBOUJELEUiLCJmaWxlIjoicXVldWVfcHJpdmF0ZV9idW5kbGUuanMiLCJzb3VyY2VzQ29udGVudCI6WyIgXHQvLyBUaGUgbW9kdWxlIGNhY2hlXG4gXHR2YXIgaW5zdGFsbGVkTW9kdWxlcyA9IHt9O1xuXG4gXHQvLyBUaGUgcmVxdWlyZSBmdW5jdGlvblxuIFx0ZnVuY3Rpb24gX193ZWJwYWNrX3JlcXVpcmVfXyhtb2R1bGVJZCkge1xuXG4gXHRcdC8vIENoZWNrIGlmIG1vZHVsZSBpcyBpbiBjYWNoZVxuIFx0XHRpZihpbnN0YWxsZWRNb2R1bGVzW21vZHVsZUlkXSkge1xuIFx0XHRcdHJldHVybiBpbnN0YWxsZWRNb2R1bGVzW21vZHVsZUlkXS5leHBvcnRzO1xuIFx0XHR9XG4gXHRcdC8vIENyZWF0ZSBhIG5ldyBtb2R1bGUgKGFuZCBwdXQgaXQgaW50byB0aGUgY2FjaGUpXG4gXHRcdHZhciBtb2R1bGUgPSBpbnN0YWxsZWRNb2R1bGVzW21vZHVsZUlkXSA9IHtcbiBcdFx0XHRpOiBtb2R1bGVJZCxcbiBcdFx0XHRsOiBmYWxzZSxcbiBcdFx0XHRleHBvcnRzOiB7fVxuIFx0XHR9O1xuXG4gXHRcdC8vIEV4ZWN1dGUgdGhlIG1vZHVsZSBmdW5jdGlvblxuIFx0XHRtb2R1bGVzW21vZHVsZUlkXS5jYWxsKG1vZHVsZS5leHBvcnRzLCBtb2R1bGUsIG1vZHVsZS5leHBvcnRzLCBfX3dlYnBhY2tfcmVxdWlyZV9fKTtcblxuIFx0XHQvLyBGbGFnIHRoZSBtb2R1bGUgYXMgbG9hZGVkXG4gXHRcdG1vZHVsZS5sID0gdHJ1ZTtcblxuIFx0XHQvLyBSZXR1cm4gdGhlIGV4cG9ydHMgb2YgdGhlIG1vZHVsZVxuIFx0XHRyZXR1cm4gbW9kdWxlLmV4cG9ydHM7XG4gXHR9XG5cblxuIFx0Ly8gZXhwb3NlIHRoZSBtb2R1bGVzIG9iamVjdCAoX193ZWJwYWNrX21vZHVsZXNfXylcbiBcdF9fd2VicGFja19yZXF1aXJlX18ubSA9IG1vZHVsZXM7XG5cbiBcdC8vIGV4cG9zZSB0aGUgbW9kdWxlIGNhY2hlXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLmMgPSBpbnN0YWxsZWRNb2R1bGVzO1xuXG4gXHQvLyBkZWZpbmUgZ2V0dGVyIGZ1bmN0aW9uIGZvciBoYXJtb255IGV4cG9ydHNcbiBcdF9fd2VicGFja19yZXF1aXJlX18uZCA9IGZ1bmN0aW9uKGV4cG9ydHMsIG5hbWUsIGdldHRlcikge1xuIFx0XHRpZighX193ZWJwYWNrX3JlcXVpcmVfXy5vKGV4cG9ydHMsIG5hbWUpKSB7XG4gXHRcdFx0T2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsIG5hbWUsIHtcbiBcdFx0XHRcdGNvbmZpZ3VyYWJsZTogZmFsc2UsXG4gXHRcdFx0XHRlbnVtZXJhYmxlOiB0cnVlLFxuIFx0XHRcdFx0Z2V0OiBnZXR0ZXJcbiBcdFx0XHR9KTtcbiBcdFx0fVxuIFx0fTtcblxuIFx0Ly8gZ2V0RGVmYXVsdEV4cG9ydCBmdW5jdGlvbiBmb3IgY29tcGF0aWJpbGl0eSB3aXRoIG5vbi1oYXJtb255IG1vZHVsZXNcbiBcdF9fd2VicGFja19yZXF1aXJlX18ubiA9IGZ1bmN0aW9uKG1vZHVsZSkge1xuIFx0XHR2YXIgZ2V0dGVyID0gbW9kdWxlICYmIG1vZHVsZS5fX2VzTW9kdWxlID9cbiBcdFx0XHRmdW5jdGlvbiBnZXREZWZhdWx0KCkgeyByZXR1cm4gbW9kdWxlWydkZWZhdWx0J107IH0gOlxuIFx0XHRcdGZ1bmN0aW9uIGdldE1vZHVsZUV4cG9ydHMoKSB7IHJldHVybiBtb2R1bGU7IH07XG4gXHRcdF9fd2VicGFja19yZXF1aXJlX18uZChnZXR0ZXIsICdhJywgZ2V0dGVyKTtcbiBcdFx0cmV0dXJuIGdldHRlcjtcbiBcdH07XG5cbiBcdC8vIE9iamVjdC5wcm90b3R5cGUuaGFzT3duUHJvcGVydHkuY2FsbFxuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5vID0gZnVuY3Rpb24ob2JqZWN0LCBwcm9wZXJ0eSkgeyByZXR1cm4gT2JqZWN0LnByb3RvdHlwZS5oYXNPd25Qcm9wZXJ0eS5jYWxsKG9iamVjdCwgcHJvcGVydHkpOyB9O1xuXG4gXHQvLyBfX3dlYnBhY2tfcHVibGljX3BhdGhfX1xuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5wID0gXCJcIjtcblxuIFx0Ly8gTG9hZCBlbnRyeSBtb2R1bGUgYW5kIHJldHVybiBleHBvcnRzXG4gXHRyZXR1cm4gX193ZWJwYWNrX3JlcXVpcmVfXyhfX3dlYnBhY2tfcmVxdWlyZV9fLnMgPSAzKTtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyB3ZWJwYWNrL2Jvb3RzdHJhcCBmNDU3NDllOTg4YTI2OGRhNTgzNCIsInF1ZXVlQXBwLmNvbnRyb2xsZXIoXCJRdWV1ZVByaXZhdGVDb250cm9sbGVyXCIsIGZ1bmN0aW9uKCRzY29wZSwgJGNvbnRyb2xsZXIsIFF1ZXVlU2VydmljZSkge1xyXG5cdGFuZ3VsYXIuZXh0ZW5kKHRoaXMsICRjb250cm9sbGVyKCdRdWV1ZUNvbnRyb2xsZXInLCB7JHNjb3BlOiAkc2NvcGV9KSk7XHJcblxyXG5cdCRzY29wZS5zZWxlY3RDbGllbnQgPSBmdW5jdGlvbihjbGllbnQpIHtcclxuXHRcdCRzY29wZS5jbGllbnQgPSBjbGllbnQ7XHJcblx0fTtcclxufSk7XHJcblxyXG5xdWV1ZUFwcC5maWx0ZXIoJ3NlYXJjaEZvcicsIGZ1bmN0aW9uKCl7XHJcblxyXG5cdC8vIEFsbCBmaWx0ZXJzIG11c3QgcmV0dXJuIGEgZnVuY3Rpb24uIFRoZSBmaXJzdCBwYXJhbWV0ZXJcclxuXHQvLyBpcyB0aGUgZGF0YSB0aGF0IGlzIHRvIGJlIGZpbHRlcmVkLCBhbmQgdGhlIHNlY29uZCBpcyBhblxyXG5cdC8vIGFyZ3VtZW50IHRoYXQgbWF5IGJlIHBhc3NlZCB3aXRoIGEgY29sb24gKHNlYXJjaEZvcjpzZWFyY2hTdHJpbmcpXHJcblxyXG5cdHJldHVybiBmdW5jdGlvbihhcnIsIHNlYXJjaFN0cmluZyl7XHJcblxyXG5cdFx0aWYoIXNlYXJjaFN0cmluZyl7XHJcblx0XHRcdHJldHVybiBhcnI7XHJcblx0XHR9XHJcblxyXG5cdFx0dmFyIHJlc3VsdCA9IFtdO1xyXG5cclxuXHRcdHNlYXJjaFN0cmluZyA9IHNlYXJjaFN0cmluZy50b0xvd2VyQ2FzZSgpO1xyXG5cclxuXHRcdC8vIFVzaW5nIHRoZSBmb3JFYWNoIGhlbHBlciBtZXRob2QgdG8gbG9vcCB0aHJvdWdoIHRoZSBhcnJheVxyXG5cdFx0YW5ndWxhci5mb3JFYWNoKGFyciwgZnVuY3Rpb24oaXRlbSl7XHJcblxyXG5cdFx0XHRpZihpdGVtLm5hbWUudG9Mb3dlckNhc2UoKS5pbmRleE9mKHNlYXJjaFN0cmluZykgIT09IC0xIHx8XHJcblx0XHRcdFx0IGl0ZW0uYXBwb2ludG1lbnRJZC50b1N0cmluZygpLmluZGV4T2Yoc2VhcmNoU3RyaW5nKSAhPT0gLTEgfHxcclxuXHRcdFx0XHQgKFwiI1wiICsgaXRlbS5hcHBvaW50bWVudElkLnRvU3RyaW5nKCkpLmluZGV4T2Yoc2VhcmNoU3RyaW5nKSAhPT0gLTEpe1xyXG5cdFx0XHRcdHJlc3VsdC5wdXNoKGl0ZW0pO1xyXG5cdFx0XHR9XHJcblxyXG5cdFx0fSk7XHJcblxyXG5cdFx0cmV0dXJuIHJlc3VsdDtcclxuXHR9O1xyXG5cclxufSk7XHJcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL3F1ZXVlL3F1ZXVlX3ByaXZhdGUuanMiXSwic291cmNlUm9vdCI6IiJ9