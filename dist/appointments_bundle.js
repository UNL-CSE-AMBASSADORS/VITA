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
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


$(document).ready(function () {
	loadAllSites();
});

var downloadSchedule = function downloadSchedule() {
	var siteSelect = document.getElementById("siteSelect");
	var siteSelectedOption = siteSelect.options[siteSelect.selectedIndex];
	var date = document.getElementById("dateInput").value;
	var siteId = siteSelectedOption.value;

	var downloadLink = document.createElement("a");
	downloadLink.setAttribute("href", "/server/management/appointments.php?date=" + date + "&siteId=" + siteId);
	downloadLink.setAttribute("target", "_blank");
	downloadLink.style.display = "none";
	document.body.append(downloadLink);
	downloadLink.click();
};

var loadAllSites = function loadAllSites() {
	$.ajax({
		url: "/server/api/sites/getAll.php",
		type: "GET",
		dataType: "JSON",
		data: {
			"siteId": true,
			"title": true
		},
		cache: false,
		success: function success(response) {
			var siteSelect = document.getElementById("siteSelect");
			siteSelect.options.add(new Option("All Sites", -1));
			for (var i = 0; i < response.length; i++) {
				siteSelect.options.add(new Option(response[i].title, response[i].siteId));
			}
		},
		error: function error(response) {
			alert("Unable to load sites. Please refresh the page in a few minutes.");
		}
	});
};

/***/ })
/******/ ]);
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vd2VicGFjay9ib290c3RyYXAgZjQ1NzQ5ZTk4OGEyNjhkYTU4MzQiLCJ3ZWJwYWNrOi8vLy4vbWFuYWdlbWVudC9hcHBvaW50bWVudHMvYXBwb2ludG1lbnRzLmpzIl0sIm5hbWVzIjpbIiQiLCJkb2N1bWVudCIsInJlYWR5IiwibG9hZEFsbFNpdGVzIiwiZG93bmxvYWRTY2hlZHVsZSIsInNpdGVTZWxlY3QiLCJnZXRFbGVtZW50QnlJZCIsInNpdGVTZWxlY3RlZE9wdGlvbiIsIm9wdGlvbnMiLCJzZWxlY3RlZEluZGV4IiwiZGF0ZSIsInZhbHVlIiwic2l0ZUlkIiwiZG93bmxvYWRMaW5rIiwiY3JlYXRlRWxlbWVudCIsInNldEF0dHJpYnV0ZSIsInN0eWxlIiwiZGlzcGxheSIsImJvZHkiLCJhcHBlbmQiLCJjbGljayIsImFqYXgiLCJ1cmwiLCJ0eXBlIiwiZGF0YVR5cGUiLCJkYXRhIiwiY2FjaGUiLCJzdWNjZXNzIiwicmVzcG9uc2UiLCJhZGQiLCJPcHRpb24iLCJpIiwibGVuZ3RoIiwidGl0bGUiLCJlcnJvciIsImFsZXJ0Il0sIm1hcHBpbmdzIjoiO0FBQUE7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7OztBQUdBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQUs7QUFDTDtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBLG1DQUEyQiwwQkFBMEIsRUFBRTtBQUN2RCx5Q0FBaUMsZUFBZTtBQUNoRDtBQUNBO0FBQ0E7O0FBRUE7QUFDQSw4REFBc0QsK0RBQStEOztBQUVySDtBQUNBOztBQUVBO0FBQ0E7Ozs7Ozs7Ozs7QUM3REFBLEVBQUVDLFFBQUYsRUFBWUMsS0FBWixDQUFrQixZQUFXO0FBQzVCQztBQUNBLENBRkQ7O0FBSUEsSUFBSUMsbUJBQW1CLFNBQW5CQSxnQkFBbUIsR0FBVztBQUNqQyxLQUFJQyxhQUFhSixTQUFTSyxjQUFULENBQXdCLFlBQXhCLENBQWpCO0FBQ0EsS0FBSUMscUJBQXFCRixXQUFXRyxPQUFYLENBQW1CSCxXQUFXSSxhQUE5QixDQUF6QjtBQUNBLEtBQUlDLE9BQU9ULFNBQVNLLGNBQVQsQ0FBd0IsV0FBeEIsRUFBcUNLLEtBQWhEO0FBQ0EsS0FBSUMsU0FBU0wsbUJBQW1CSSxLQUFoQzs7QUFFQSxLQUFJRSxlQUFlWixTQUFTYSxhQUFULENBQXVCLEdBQXZCLENBQW5CO0FBQ0FELGNBQWFFLFlBQWIsQ0FBMEIsTUFBMUIsZ0RBQThFTCxJQUE5RSxnQkFBNkZFLE1BQTdGO0FBQ0FDLGNBQWFFLFlBQWIsQ0FBMEIsUUFBMUIsRUFBb0MsUUFBcEM7QUFDQUYsY0FBYUcsS0FBYixDQUFtQkMsT0FBbkIsR0FBNkIsTUFBN0I7QUFDQWhCLFVBQVNpQixJQUFULENBQWNDLE1BQWQsQ0FBcUJOLFlBQXJCO0FBQ0FBLGNBQWFPLEtBQWI7QUFDQSxDQVpEOztBQWNBLElBQUlqQixlQUFlLFNBQWZBLFlBQWUsR0FBVztBQUM3QkgsR0FBRXFCLElBQUYsQ0FBTztBQUNOQyxPQUFLLDhCQURDO0FBRU5DLFFBQU0sS0FGQTtBQUdOQyxZQUFVLE1BSEo7QUFJTkMsUUFBTztBQUNOLGFBQVUsSUFESjtBQUVOLFlBQVM7QUFGSCxHQUpEO0FBUU5DLFNBQU8sS0FSRDtBQVNOQyxXQUFTLGlCQUFTQyxRQUFULEVBQW1CO0FBQzNCLE9BQUl2QixhQUFhSixTQUFTSyxjQUFULENBQXdCLFlBQXhCLENBQWpCO0FBQ0FELGNBQVdHLE9BQVgsQ0FBbUJxQixHQUFuQixDQUF1QixJQUFJQyxNQUFKLENBQVcsV0FBWCxFQUF3QixDQUFDLENBQXpCLENBQXZCO0FBQ0EsUUFBSyxJQUFJQyxJQUFJLENBQWIsRUFBZ0JBLElBQUlILFNBQVNJLE1BQTdCLEVBQXFDRCxHQUFyQyxFQUEwQztBQUN6QzFCLGVBQVdHLE9BQVgsQ0FBbUJxQixHQUFuQixDQUF1QixJQUFJQyxNQUFKLENBQVdGLFNBQVNHLENBQVQsRUFBWUUsS0FBdkIsRUFBOEJMLFNBQVNHLENBQVQsRUFBWW5CLE1BQTFDLENBQXZCO0FBQ0E7QUFDRCxHQWZLO0FBZ0JOc0IsU0FBTyxlQUFTTixRQUFULEVBQW1CO0FBQ3pCTyxTQUFNLGlFQUFOO0FBQ0E7QUFsQkssRUFBUDtBQW9CQSxDQXJCRCxDIiwiZmlsZSI6ImFwcG9pbnRtZW50c19idW5kbGUuanMiLCJzb3VyY2VzQ29udGVudCI6WyIgXHQvLyBUaGUgbW9kdWxlIGNhY2hlXG4gXHR2YXIgaW5zdGFsbGVkTW9kdWxlcyA9IHt9O1xuXG4gXHQvLyBUaGUgcmVxdWlyZSBmdW5jdGlvblxuIFx0ZnVuY3Rpb24gX193ZWJwYWNrX3JlcXVpcmVfXyhtb2R1bGVJZCkge1xuXG4gXHRcdC8vIENoZWNrIGlmIG1vZHVsZSBpcyBpbiBjYWNoZVxuIFx0XHRpZihpbnN0YWxsZWRNb2R1bGVzW21vZHVsZUlkXSkge1xuIFx0XHRcdHJldHVybiBpbnN0YWxsZWRNb2R1bGVzW21vZHVsZUlkXS5leHBvcnRzO1xuIFx0XHR9XG4gXHRcdC8vIENyZWF0ZSBhIG5ldyBtb2R1bGUgKGFuZCBwdXQgaXQgaW50byB0aGUgY2FjaGUpXG4gXHRcdHZhciBtb2R1bGUgPSBpbnN0YWxsZWRNb2R1bGVzW21vZHVsZUlkXSA9IHtcbiBcdFx0XHRpOiBtb2R1bGVJZCxcbiBcdFx0XHRsOiBmYWxzZSxcbiBcdFx0XHRleHBvcnRzOiB7fVxuIFx0XHR9O1xuXG4gXHRcdC8vIEV4ZWN1dGUgdGhlIG1vZHVsZSBmdW5jdGlvblxuIFx0XHRtb2R1bGVzW21vZHVsZUlkXS5jYWxsKG1vZHVsZS5leHBvcnRzLCBtb2R1bGUsIG1vZHVsZS5leHBvcnRzLCBfX3dlYnBhY2tfcmVxdWlyZV9fKTtcblxuIFx0XHQvLyBGbGFnIHRoZSBtb2R1bGUgYXMgbG9hZGVkXG4gXHRcdG1vZHVsZS5sID0gdHJ1ZTtcblxuIFx0XHQvLyBSZXR1cm4gdGhlIGV4cG9ydHMgb2YgdGhlIG1vZHVsZVxuIFx0XHRyZXR1cm4gbW9kdWxlLmV4cG9ydHM7XG4gXHR9XG5cblxuIFx0Ly8gZXhwb3NlIHRoZSBtb2R1bGVzIG9iamVjdCAoX193ZWJwYWNrX21vZHVsZXNfXylcbiBcdF9fd2VicGFja19yZXF1aXJlX18ubSA9IG1vZHVsZXM7XG5cbiBcdC8vIGV4cG9zZSB0aGUgbW9kdWxlIGNhY2hlXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLmMgPSBpbnN0YWxsZWRNb2R1bGVzO1xuXG4gXHQvLyBkZWZpbmUgZ2V0dGVyIGZ1bmN0aW9uIGZvciBoYXJtb255IGV4cG9ydHNcbiBcdF9fd2VicGFja19yZXF1aXJlX18uZCA9IGZ1bmN0aW9uKGV4cG9ydHMsIG5hbWUsIGdldHRlcikge1xuIFx0XHRpZighX193ZWJwYWNrX3JlcXVpcmVfXy5vKGV4cG9ydHMsIG5hbWUpKSB7XG4gXHRcdFx0T2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsIG5hbWUsIHtcbiBcdFx0XHRcdGNvbmZpZ3VyYWJsZTogZmFsc2UsXG4gXHRcdFx0XHRlbnVtZXJhYmxlOiB0cnVlLFxuIFx0XHRcdFx0Z2V0OiBnZXR0ZXJcbiBcdFx0XHR9KTtcbiBcdFx0fVxuIFx0fTtcblxuIFx0Ly8gZ2V0RGVmYXVsdEV4cG9ydCBmdW5jdGlvbiBmb3IgY29tcGF0aWJpbGl0eSB3aXRoIG5vbi1oYXJtb255IG1vZHVsZXNcbiBcdF9fd2VicGFja19yZXF1aXJlX18ubiA9IGZ1bmN0aW9uKG1vZHVsZSkge1xuIFx0XHR2YXIgZ2V0dGVyID0gbW9kdWxlICYmIG1vZHVsZS5fX2VzTW9kdWxlID9cbiBcdFx0XHRmdW5jdGlvbiBnZXREZWZhdWx0KCkgeyByZXR1cm4gbW9kdWxlWydkZWZhdWx0J107IH0gOlxuIFx0XHRcdGZ1bmN0aW9uIGdldE1vZHVsZUV4cG9ydHMoKSB7IHJldHVybiBtb2R1bGU7IH07XG4gXHRcdF9fd2VicGFja19yZXF1aXJlX18uZChnZXR0ZXIsICdhJywgZ2V0dGVyKTtcbiBcdFx0cmV0dXJuIGdldHRlcjtcbiBcdH07XG5cbiBcdC8vIE9iamVjdC5wcm90b3R5cGUuaGFzT3duUHJvcGVydHkuY2FsbFxuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5vID0gZnVuY3Rpb24ob2JqZWN0LCBwcm9wZXJ0eSkgeyByZXR1cm4gT2JqZWN0LnByb3RvdHlwZS5oYXNPd25Qcm9wZXJ0eS5jYWxsKG9iamVjdCwgcHJvcGVydHkpOyB9O1xuXG4gXHQvLyBfX3dlYnBhY2tfcHVibGljX3BhdGhfX1xuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5wID0gXCJcIjtcblxuIFx0Ly8gTG9hZCBlbnRyeSBtb2R1bGUgYW5kIHJldHVybiBleHBvcnRzXG4gXHRyZXR1cm4gX193ZWJwYWNrX3JlcXVpcmVfXyhfX3dlYnBhY2tfcmVxdWlyZV9fLnMgPSAwKTtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyB3ZWJwYWNrL2Jvb3RzdHJhcCBmNDU3NDllOTg4YTI2OGRhNTgzNCIsIiQoZG9jdW1lbnQpLnJlYWR5KGZ1bmN0aW9uKCkge1xyXG5cdGxvYWRBbGxTaXRlcygpO1xyXG59KTtcclxuXHJcbmxldCBkb3dubG9hZFNjaGVkdWxlID0gZnVuY3Rpb24oKSB7XHJcblx0bGV0IHNpdGVTZWxlY3QgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZChcInNpdGVTZWxlY3RcIik7XHJcblx0bGV0IHNpdGVTZWxlY3RlZE9wdGlvbiA9IHNpdGVTZWxlY3Qub3B0aW9uc1tzaXRlU2VsZWN0LnNlbGVjdGVkSW5kZXhdO1xyXG5cdGxldCBkYXRlID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoXCJkYXRlSW5wdXRcIikudmFsdWU7XHJcblx0bGV0IHNpdGVJZCA9IHNpdGVTZWxlY3RlZE9wdGlvbi52YWx1ZTtcclxuXHJcblx0bGV0IGRvd25sb2FkTGluayA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoXCJhXCIpO1xyXG5cdGRvd25sb2FkTGluay5zZXRBdHRyaWJ1dGUoXCJocmVmXCIsIGAvc2VydmVyL21hbmFnZW1lbnQvYXBwb2ludG1lbnRzLnBocD9kYXRlPSR7ZGF0ZX0mc2l0ZUlkPSR7c2l0ZUlkfWApO1xyXG5cdGRvd25sb2FkTGluay5zZXRBdHRyaWJ1dGUoXCJ0YXJnZXRcIiwgXCJfYmxhbmtcIik7XHJcblx0ZG93bmxvYWRMaW5rLnN0eWxlLmRpc3BsYXkgPSBcIm5vbmVcIjtcclxuXHRkb2N1bWVudC5ib2R5LmFwcGVuZChkb3dubG9hZExpbmspO1xyXG5cdGRvd25sb2FkTGluay5jbGljaygpO1xyXG59O1xyXG5cclxubGV0IGxvYWRBbGxTaXRlcyA9IGZ1bmN0aW9uKCkge1xyXG5cdCQuYWpheCh7XHJcblx0XHR1cmw6IFwiL3NlcnZlci9hcGkvc2l0ZXMvZ2V0QWxsLnBocFwiLFxyXG5cdFx0dHlwZTogXCJHRVRcIixcclxuXHRcdGRhdGFUeXBlOiBcIkpTT05cIixcclxuXHRcdGRhdGE6ICh7XHJcblx0XHRcdFwic2l0ZUlkXCI6IHRydWUsXHJcblx0XHRcdFwidGl0bGVcIjogdHJ1ZVxyXG5cdFx0fSksXHJcblx0XHRjYWNoZTogZmFsc2UsXHJcblx0XHRzdWNjZXNzOiBmdW5jdGlvbihyZXNwb25zZSkge1xyXG5cdFx0XHRsZXQgc2l0ZVNlbGVjdCA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKFwic2l0ZVNlbGVjdFwiKTtcclxuXHRcdFx0c2l0ZVNlbGVjdC5vcHRpb25zLmFkZChuZXcgT3B0aW9uKFwiQWxsIFNpdGVzXCIsIC0xKSk7XHJcblx0XHRcdGZvciAobGV0IGkgPSAwOyBpIDwgcmVzcG9uc2UubGVuZ3RoOyBpKyspIHtcclxuXHRcdFx0XHRzaXRlU2VsZWN0Lm9wdGlvbnMuYWRkKG5ldyBPcHRpb24ocmVzcG9uc2VbaV0udGl0bGUsIHJlc3BvbnNlW2ldLnNpdGVJZCkpO1xyXG5cdFx0XHR9XHJcblx0XHR9LFxyXG5cdFx0ZXJyb3I6IGZ1bmN0aW9uKHJlc3BvbnNlKSB7XHJcblx0XHRcdGFsZXJ0KFwiVW5hYmxlIHRvIGxvYWQgc2l0ZXMuIFBsZWFzZSByZWZyZXNoIHRoZSBwYWdlIGluIGEgZmV3IG1pbnV0ZXMuXCIpO1xyXG5cdFx0fVxyXG5cdH0pO1xyXG59XG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vbWFuYWdlbWVudC9hcHBvaW50bWVudHMvYXBwb2ludG1lbnRzLmpzIl0sInNvdXJjZVJvb3QiOiIifQ==