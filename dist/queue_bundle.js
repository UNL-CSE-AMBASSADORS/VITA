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
/******/ 	return __webpack_require__(__webpack_require__.s = 2);
/******/ })
/************************************************************************/
/******/ ({

/***/ 2:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var queueApp = angular.module("queueApp", ["ngMaterial", "ngMessages"]).controller("QueueController", function ($scope, $interval, QueueService) {
	$scope.today = new Date();
	$scope.currentDate = $scope.today;

	// Load the appointment info every 10 seconds
	$scope.updateAppointmentInformation = function () {
		var year = $scope.currentDate.getFullYear(),
		    month = $scope.currentDate.getMonth() + 1,
		    day = $scope.currentDate.getDate();
		if (month < 10) month = "0" + month;
		QueueService.getAppointments(year + "-" + month + "-" + day).then(function (data) {
			if (data == null) {
				console.log('server error');
				// TODO
			} else if (data.length > 0) {
				$scope.appointments = data.map(function (appointment) {
					// This map converts the MySQL Datatime into a Javascript Date object
					var t = appointment.scheduledTime.split(/[- :]/);
					appointment.scheduledTime = new Date(Date.UTC(t[0], t[1] - 1, t[2], t[3], t[4], t[5]));

					appointment.name = appointment.firstName + " " + appointment.lastName;
					return appointment;
				});
			} else {
				$scope.appointments = [];
			}
		});
	};
	// Refresh the clock
	var refreshClockContent = function refreshClockContent() {
		$scope.updateTime = new Date();
		$scope.isAm = $scope.updateTime.getHours() < 12;
	};

	// Create interval to update clock every second
	var clockInterval = $interval(function () {
		refreshClockContent();
	}.bind(this), 1000);

	// Create interval to update appointment information every 10 seconds
	var appointmentInterval = $interval(function () {
		$scope.updateAppointmentInformation();
	}.bind(this), 10000);

	// Destroy the intervals when we leave this page
	$scope.$on('$destroy', function () {
		$interval.cancel(clockInterval);
		$interval.cancel(appointmentInterval);
	});

	// Invoke initially
	$scope.updateAppointmentInformation();
	refreshClockContent();
});

/***/ })

/******/ });
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vd2VicGFjay9ib290c3RyYXAgZjQ1NzQ5ZTk4OGEyNjhkYTU4MzQiLCJ3ZWJwYWNrOi8vLy4vcXVldWUvcXVldWUuanMiXSwibmFtZXMiOlsicXVldWVBcHAiLCJhbmd1bGFyIiwibW9kdWxlIiwiY29udHJvbGxlciIsIiRzY29wZSIsIiRpbnRlcnZhbCIsIlF1ZXVlU2VydmljZSIsInRvZGF5IiwiRGF0ZSIsImN1cnJlbnREYXRlIiwidXBkYXRlQXBwb2ludG1lbnRJbmZvcm1hdGlvbiIsInllYXIiLCJnZXRGdWxsWWVhciIsIm1vbnRoIiwiZ2V0TW9udGgiLCJkYXkiLCJnZXREYXRlIiwiZ2V0QXBwb2ludG1lbnRzIiwidGhlbiIsImRhdGEiLCJjb25zb2xlIiwibG9nIiwibGVuZ3RoIiwiYXBwb2ludG1lbnRzIiwibWFwIiwiYXBwb2ludG1lbnQiLCJ0Iiwic2NoZWR1bGVkVGltZSIsInNwbGl0IiwiVVRDIiwibmFtZSIsImZpcnN0TmFtZSIsImxhc3ROYW1lIiwicmVmcmVzaENsb2NrQ29udGVudCIsInVwZGF0ZVRpbWUiLCJpc0FtIiwiZ2V0SG91cnMiLCJjbG9ja0ludGVydmFsIiwiYmluZCIsImFwcG9pbnRtZW50SW50ZXJ2YWwiLCIkb24iLCJjYW5jZWwiXSwibWFwcGluZ3MiOiI7QUFBQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7O0FBR0E7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBSztBQUNMO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0EsbUNBQTJCLDBCQUEwQixFQUFFO0FBQ3ZELHlDQUFpQyxlQUFlO0FBQ2hEO0FBQ0E7QUFDQTs7QUFFQTtBQUNBLDhEQUFzRCwrREFBK0Q7O0FBRXJIO0FBQ0E7O0FBRUE7QUFDQTs7Ozs7Ozs7Ozs7QUM3REEsSUFBSUEsV0FBV0MsUUFBUUMsTUFBUixDQUFlLFVBQWYsRUFBMkIsQ0FBQyxZQUFELEVBQWUsWUFBZixDQUEzQixFQUVkQyxVQUZjLENBRUgsaUJBRkcsRUFFZ0IsVUFBU0MsTUFBVCxFQUFpQkMsU0FBakIsRUFBNEJDLFlBQTVCLEVBQTBDO0FBQ3hFRixRQUFPRyxLQUFQLEdBQWUsSUFBSUMsSUFBSixFQUFmO0FBQ0FKLFFBQU9LLFdBQVAsR0FBcUJMLE9BQU9HLEtBQTVCOztBQUVBO0FBQ0FILFFBQU9NLDRCQUFQLEdBQXNDLFlBQVc7QUFDaEQsTUFBSUMsT0FBT1AsT0FBT0ssV0FBUCxDQUFtQkcsV0FBbkIsRUFBWDtBQUFBLE1BQ0NDLFFBQVFULE9BQU9LLFdBQVAsQ0FBbUJLLFFBQW5CLEtBQWdDLENBRHpDO0FBQUEsTUFFQ0MsTUFBTVgsT0FBT0ssV0FBUCxDQUFtQk8sT0FBbkIsRUFGUDtBQUdBLE1BQUlILFFBQVEsRUFBWixFQUFnQkEsUUFBUSxNQUFNQSxLQUFkO0FBQ2hCUCxlQUFhVyxlQUFiLENBQTZCTixPQUFPLEdBQVAsR0FBYUUsS0FBYixHQUFxQixHQUFyQixHQUEyQkUsR0FBeEQsRUFBNkRHLElBQTdELENBQWtFLFVBQVNDLElBQVQsRUFBZTtBQUNoRixPQUFHQSxRQUFRLElBQVgsRUFBaUI7QUFDaEJDLFlBQVFDLEdBQVIsQ0FBWSxjQUFaO0FBQ0E7QUFDQSxJQUhELE1BR08sSUFBR0YsS0FBS0csTUFBTCxHQUFjLENBQWpCLEVBQW9CO0FBQzFCbEIsV0FBT21CLFlBQVAsR0FBc0JKLEtBQUtLLEdBQUwsQ0FBUyxVQUFDQyxXQUFELEVBQWlCO0FBQy9DO0FBQ0EsU0FBSUMsSUFBSUQsWUFBWUUsYUFBWixDQUEwQkMsS0FBMUIsQ0FBZ0MsT0FBaEMsQ0FBUjtBQUNBSCxpQkFBWUUsYUFBWixHQUE0QixJQUFJbkIsSUFBSixDQUFTQSxLQUFLcUIsR0FBTCxDQUFTSCxFQUFFLENBQUYsQ0FBVCxFQUFlQSxFQUFFLENBQUYsSUFBSyxDQUFwQixFQUF1QkEsRUFBRSxDQUFGLENBQXZCLEVBQTZCQSxFQUFFLENBQUYsQ0FBN0IsRUFBbUNBLEVBQUUsQ0FBRixDQUFuQyxFQUF5Q0EsRUFBRSxDQUFGLENBQXpDLENBQVQsQ0FBNUI7O0FBRUFELGlCQUFZSyxJQUFaLEdBQW1CTCxZQUFZTSxTQUFaLEdBQXdCLEdBQXhCLEdBQThCTixZQUFZTyxRQUE3RDtBQUNBLFlBQU9QLFdBQVA7QUFDQSxLQVBxQixDQUF0QjtBQVFBLElBVE0sTUFTQTtBQUNOckIsV0FBT21CLFlBQVAsR0FBc0IsRUFBdEI7QUFDQTtBQUNELEdBaEJEO0FBaUJBLEVBdEJEO0FBdUJBO0FBQ0EsS0FBSVUsc0JBQXNCLFNBQXRCQSxtQkFBc0IsR0FBVTtBQUNuQzdCLFNBQU84QixVQUFQLEdBQW9CLElBQUkxQixJQUFKLEVBQXBCO0FBQ0FKLFNBQU8rQixJQUFQLEdBQWMvQixPQUFPOEIsVUFBUCxDQUFrQkUsUUFBbEIsS0FBK0IsRUFBN0M7QUFDQSxFQUhEOztBQUtBO0FBQ0EsS0FBSUMsZ0JBQWdCaEMsVUFBVSxZQUFVO0FBQ3ZDNEI7QUFDQSxFQUY2QixDQUU1QkssSUFGNEIsQ0FFdkIsSUFGdUIsQ0FBVixFQUVOLElBRk0sQ0FBcEI7O0FBSUE7QUFDQSxLQUFJQyxzQkFBc0JsQyxVQUFVLFlBQVU7QUFDN0NELFNBQU9NLDRCQUFQO0FBQ0EsRUFGbUMsQ0FFbEM0QixJQUZrQyxDQUU3QixJQUY2QixDQUFWLEVBRVosS0FGWSxDQUExQjs7QUFJQTtBQUNBbEMsUUFBT29DLEdBQVAsQ0FBVyxVQUFYLEVBQXVCLFlBQVk7QUFDbENuQyxZQUFVb0MsTUFBVixDQUFpQkosYUFBakI7QUFDQWhDLFlBQVVvQyxNQUFWLENBQWlCRixtQkFBakI7QUFDQSxFQUhEOztBQUtBO0FBQ0FuQyxRQUFPTSw0QkFBUDtBQUNBdUI7QUFFQSxDQXhEYyxDQUFmLEMiLCJmaWxlIjoicXVldWVfYnVuZGxlLmpzIiwic291cmNlc0NvbnRlbnQiOlsiIFx0Ly8gVGhlIG1vZHVsZSBjYWNoZVxuIFx0dmFyIGluc3RhbGxlZE1vZHVsZXMgPSB7fTtcblxuIFx0Ly8gVGhlIHJlcXVpcmUgZnVuY3Rpb25cbiBcdGZ1bmN0aW9uIF9fd2VicGFja19yZXF1aXJlX18obW9kdWxlSWQpIHtcblxuIFx0XHQvLyBDaGVjayBpZiBtb2R1bGUgaXMgaW4gY2FjaGVcbiBcdFx0aWYoaW5zdGFsbGVkTW9kdWxlc1ttb2R1bGVJZF0pIHtcbiBcdFx0XHRyZXR1cm4gaW5zdGFsbGVkTW9kdWxlc1ttb2R1bGVJZF0uZXhwb3J0cztcbiBcdFx0fVxuIFx0XHQvLyBDcmVhdGUgYSBuZXcgbW9kdWxlIChhbmQgcHV0IGl0IGludG8gdGhlIGNhY2hlKVxuIFx0XHR2YXIgbW9kdWxlID0gaW5zdGFsbGVkTW9kdWxlc1ttb2R1bGVJZF0gPSB7XG4gXHRcdFx0aTogbW9kdWxlSWQsXG4gXHRcdFx0bDogZmFsc2UsXG4gXHRcdFx0ZXhwb3J0czoge31cbiBcdFx0fTtcblxuIFx0XHQvLyBFeGVjdXRlIHRoZSBtb2R1bGUgZnVuY3Rpb25cbiBcdFx0bW9kdWxlc1ttb2R1bGVJZF0uY2FsbChtb2R1bGUuZXhwb3J0cywgbW9kdWxlLCBtb2R1bGUuZXhwb3J0cywgX193ZWJwYWNrX3JlcXVpcmVfXyk7XG5cbiBcdFx0Ly8gRmxhZyB0aGUgbW9kdWxlIGFzIGxvYWRlZFxuIFx0XHRtb2R1bGUubCA9IHRydWU7XG5cbiBcdFx0Ly8gUmV0dXJuIHRoZSBleHBvcnRzIG9mIHRoZSBtb2R1bGVcbiBcdFx0cmV0dXJuIG1vZHVsZS5leHBvcnRzO1xuIFx0fVxuXG5cbiBcdC8vIGV4cG9zZSB0aGUgbW9kdWxlcyBvYmplY3QgKF9fd2VicGFja19tb2R1bGVzX18pXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLm0gPSBtb2R1bGVzO1xuXG4gXHQvLyBleHBvc2UgdGhlIG1vZHVsZSBjYWNoZVxuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5jID0gaW5zdGFsbGVkTW9kdWxlcztcblxuIFx0Ly8gZGVmaW5lIGdldHRlciBmdW5jdGlvbiBmb3IgaGFybW9ueSBleHBvcnRzXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLmQgPSBmdW5jdGlvbihleHBvcnRzLCBuYW1lLCBnZXR0ZXIpIHtcbiBcdFx0aWYoIV9fd2VicGFja19yZXF1aXJlX18ubyhleHBvcnRzLCBuYW1lKSkge1xuIFx0XHRcdE9iamVjdC5kZWZpbmVQcm9wZXJ0eShleHBvcnRzLCBuYW1lLCB7XG4gXHRcdFx0XHRjb25maWd1cmFibGU6IGZhbHNlLFxuIFx0XHRcdFx0ZW51bWVyYWJsZTogdHJ1ZSxcbiBcdFx0XHRcdGdldDogZ2V0dGVyXG4gXHRcdFx0fSk7XG4gXHRcdH1cbiBcdH07XG5cbiBcdC8vIGdldERlZmF1bHRFeHBvcnQgZnVuY3Rpb24gZm9yIGNvbXBhdGliaWxpdHkgd2l0aCBub24taGFybW9ueSBtb2R1bGVzXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLm4gPSBmdW5jdGlvbihtb2R1bGUpIHtcbiBcdFx0dmFyIGdldHRlciA9IG1vZHVsZSAmJiBtb2R1bGUuX19lc01vZHVsZSA/XG4gXHRcdFx0ZnVuY3Rpb24gZ2V0RGVmYXVsdCgpIHsgcmV0dXJuIG1vZHVsZVsnZGVmYXVsdCddOyB9IDpcbiBcdFx0XHRmdW5jdGlvbiBnZXRNb2R1bGVFeHBvcnRzKCkgeyByZXR1cm4gbW9kdWxlOyB9O1xuIFx0XHRfX3dlYnBhY2tfcmVxdWlyZV9fLmQoZ2V0dGVyLCAnYScsIGdldHRlcik7XG4gXHRcdHJldHVybiBnZXR0ZXI7XG4gXHR9O1xuXG4gXHQvLyBPYmplY3QucHJvdG90eXBlLmhhc093blByb3BlcnR5LmNhbGxcbiBcdF9fd2VicGFja19yZXF1aXJlX18ubyA9IGZ1bmN0aW9uKG9iamVjdCwgcHJvcGVydHkpIHsgcmV0dXJuIE9iamVjdC5wcm90b3R5cGUuaGFzT3duUHJvcGVydHkuY2FsbChvYmplY3QsIHByb3BlcnR5KTsgfTtcblxuIFx0Ly8gX193ZWJwYWNrX3B1YmxpY19wYXRoX19cbiBcdF9fd2VicGFja19yZXF1aXJlX18ucCA9IFwiXCI7XG5cbiBcdC8vIExvYWQgZW50cnkgbW9kdWxlIGFuZCByZXR1cm4gZXhwb3J0c1xuIFx0cmV0dXJuIF9fd2VicGFja19yZXF1aXJlX18oX193ZWJwYWNrX3JlcXVpcmVfXy5zID0gMik7XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gd2VicGFjay9ib290c3RyYXAgZjQ1NzQ5ZTk4OGEyNjhkYTU4MzQiLCJ2YXIgcXVldWVBcHAgPSBhbmd1bGFyLm1vZHVsZShcInF1ZXVlQXBwXCIsIFtcIm5nTWF0ZXJpYWxcIiwgXCJuZ01lc3NhZ2VzXCJdKVxyXG5cclxuLmNvbnRyb2xsZXIoXCJRdWV1ZUNvbnRyb2xsZXJcIiwgZnVuY3Rpb24oJHNjb3BlLCAkaW50ZXJ2YWwsIFF1ZXVlU2VydmljZSkge1xyXG5cdCRzY29wZS50b2RheSA9IG5ldyBEYXRlKCk7XHJcblx0JHNjb3BlLmN1cnJlbnREYXRlID0gJHNjb3BlLnRvZGF5O1xyXG5cclxuXHQvLyBMb2FkIHRoZSBhcHBvaW50bWVudCBpbmZvIGV2ZXJ5IDEwIHNlY29uZHNcclxuXHQkc2NvcGUudXBkYXRlQXBwb2ludG1lbnRJbmZvcm1hdGlvbiA9IGZ1bmN0aW9uKCkge1xyXG5cdFx0bGV0IHllYXIgPSAkc2NvcGUuY3VycmVudERhdGUuZ2V0RnVsbFllYXIoKSxcclxuXHRcdFx0bW9udGggPSAkc2NvcGUuY3VycmVudERhdGUuZ2V0TW9udGgoKSArIDEsXHJcblx0XHRcdGRheSA9ICRzY29wZS5jdXJyZW50RGF0ZS5nZXREYXRlKCk7XHJcblx0XHRpZiAobW9udGggPCAxMCkgbW9udGggPSBcIjBcIiArIG1vbnRoO1xyXG5cdFx0UXVldWVTZXJ2aWNlLmdldEFwcG9pbnRtZW50cyh5ZWFyICsgXCItXCIgKyBtb250aCArIFwiLVwiICsgZGF5KS50aGVuKGZ1bmN0aW9uKGRhdGEpIHtcclxuXHRcdFx0aWYoZGF0YSA9PSBudWxsKSB7XHJcblx0XHRcdFx0Y29uc29sZS5sb2coJ3NlcnZlciBlcnJvcicpO1xyXG5cdFx0XHRcdC8vIFRPRE9cclxuXHRcdFx0fSBlbHNlIGlmKGRhdGEubGVuZ3RoID4gMCkge1xyXG5cdFx0XHRcdCRzY29wZS5hcHBvaW50bWVudHMgPSBkYXRhLm1hcCgoYXBwb2ludG1lbnQpID0+IHtcclxuXHRcdFx0XHRcdC8vIFRoaXMgbWFwIGNvbnZlcnRzIHRoZSBNeVNRTCBEYXRhdGltZSBpbnRvIGEgSmF2YXNjcmlwdCBEYXRlIG9iamVjdFxyXG5cdFx0XHRcdFx0dmFyIHQgPSBhcHBvaW50bWVudC5zY2hlZHVsZWRUaW1lLnNwbGl0KC9bLSA6XS8pO1xyXG5cdFx0XHRcdFx0YXBwb2ludG1lbnQuc2NoZWR1bGVkVGltZSA9IG5ldyBEYXRlKERhdGUuVVRDKHRbMF0sIHRbMV0tMSwgdFsyXSwgdFszXSwgdFs0XSwgdFs1XSkpO1xyXG5cclxuXHRcdFx0XHRcdGFwcG9pbnRtZW50Lm5hbWUgPSBhcHBvaW50bWVudC5maXJzdE5hbWUgKyBcIiBcIiArIGFwcG9pbnRtZW50Lmxhc3ROYW1lO1xyXG5cdFx0XHRcdFx0cmV0dXJuIGFwcG9pbnRtZW50O1xyXG5cdFx0XHRcdH0pO1xyXG5cdFx0XHR9IGVsc2Uge1xyXG5cdFx0XHRcdCRzY29wZS5hcHBvaW50bWVudHMgPSBbXTtcclxuXHRcdFx0fVxyXG5cdFx0fSk7XHJcblx0fVxyXG5cdC8vIFJlZnJlc2ggdGhlIGNsb2NrXHJcblx0bGV0IHJlZnJlc2hDbG9ja0NvbnRlbnQgPSBmdW5jdGlvbigpe1xyXG5cdFx0JHNjb3BlLnVwZGF0ZVRpbWUgPSBuZXcgRGF0ZSgpO1xyXG5cdFx0JHNjb3BlLmlzQW0gPSAkc2NvcGUudXBkYXRlVGltZS5nZXRIb3VycygpIDwgMTI7XHJcblx0fVxyXG5cclxuXHQvLyBDcmVhdGUgaW50ZXJ2YWwgdG8gdXBkYXRlIGNsb2NrIGV2ZXJ5IHNlY29uZFxyXG5cdHZhciBjbG9ja0ludGVydmFsID0gJGludGVydmFsKGZ1bmN0aW9uKCl7XHJcblx0XHRyZWZyZXNoQ2xvY2tDb250ZW50KCk7XHJcblx0fS5iaW5kKHRoaXMpLCAxMDAwKTtcclxuXHJcblx0Ly8gQ3JlYXRlIGludGVydmFsIHRvIHVwZGF0ZSBhcHBvaW50bWVudCBpbmZvcm1hdGlvbiBldmVyeSAxMCBzZWNvbmRzXHJcblx0dmFyIGFwcG9pbnRtZW50SW50ZXJ2YWwgPSAkaW50ZXJ2YWwoZnVuY3Rpb24oKXtcclxuXHRcdCRzY29wZS51cGRhdGVBcHBvaW50bWVudEluZm9ybWF0aW9uKCk7XHJcblx0fS5iaW5kKHRoaXMpLCAxMDAwMCk7XHJcblxyXG5cdC8vIERlc3Ryb3kgdGhlIGludGVydmFscyB3aGVuIHdlIGxlYXZlIHRoaXMgcGFnZVxyXG5cdCRzY29wZS4kb24oJyRkZXN0cm95JywgZnVuY3Rpb24gKCkge1xyXG5cdFx0JGludGVydmFsLmNhbmNlbChjbG9ja0ludGVydmFsKTtcclxuXHRcdCRpbnRlcnZhbC5jYW5jZWwoYXBwb2ludG1lbnRJbnRlcnZhbCk7XHJcblx0fSk7XHJcblxyXG5cdC8vIEludm9rZSBpbml0aWFsbHlcclxuXHQkc2NvcGUudXBkYXRlQXBwb2ludG1lbnRJbmZvcm1hdGlvbigpO1xyXG5cdHJlZnJlc2hDbG9ja0NvbnRlbnQoKTtcclxuXHJcbn0pO1xyXG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9xdWV1ZS9xdWV1ZS5qcyJdLCJzb3VyY2VSb290IjoiIn0=