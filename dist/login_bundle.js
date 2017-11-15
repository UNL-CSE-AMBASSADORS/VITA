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
/******/ 	return __webpack_require__(__webpack_require__.s = 1);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */,
/* 1 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


$(function () {
	console.log("%cSTOP! \n%cDo NOT paste/type anything here under any circumstance.", "color: red; font-size:36px;", "color: black; font-size: 12pt");
	LoginControls();
});

// login.php functions
var LoginControls = function LoginControls() {

	// Focus Email Field
	$("#login_email").focus();

	// Toogle Forms
	$(".toggle_form").click(function () {
		$("#login_form")[0].reset();
		$("#register_form")[0].reset();
		$("#register_success").hide();
		$("#register_form, #register_info").show();
		$("#login_panel,#register_panel").toggle("slow", function () {
			if ($("#login_form").is(':visible')) {
				$("#login_email").focus();
			} else {
				$("#register_email").focus();
			}
		});
	});

	$("#login_form").on('submit', function (e) {
		// Prevent form submission
		e.preventDefault();

		// Define Fields
		var email = $("#login_email").val();
		var password = $("#login_password").val();

		if (email && password) {
			// Ajax
			$.ajax({
				dataType: 'json',
				method: 'POST',
				url: '/server/callbacks.php',
				data: {
					callback: 'login',
					email: email,
					password: password
				},
				success: function success(response) {
					if (response.success) {
						window.location = response.redirect;
					} else {
						alert(response.error);
					}
				}
			});
		} else {}
	});

	$("#register_form").on('submit', function (e) {

		// Prevent form submission
		e.preventDefault();

		// Define Fields
		var email = $("#register_email").val();

		if (email) {
			$.ajax({
				dataType: 'json',
				method: 'POST',
				url: '/server/callbacks.php',
				data: {
					callback: 'register',
					email: email
				},
				success: function success(response) {
					if (response.success) {
						$("#register_form, #register_info").hide();
						$("#register_success").fadeIn();
					} else {
						alert(response.error);
					}
				}
			});
		}
	});
};

/***/ })
/******/ ]);
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vd2VicGFjay9ib290c3RyYXAgZjQ1NzQ5ZTk4OGEyNjhkYTU4MzQiLCJ3ZWJwYWNrOi8vLy4vbG9naW4vbG9naW4uanMiXSwibmFtZXMiOlsiJCIsImNvbnNvbGUiLCJsb2ciLCJMb2dpbkNvbnRyb2xzIiwiZm9jdXMiLCJjbGljayIsInJlc2V0IiwiaGlkZSIsInNob3ciLCJ0b2dnbGUiLCJpcyIsIm9uIiwiZSIsInByZXZlbnREZWZhdWx0IiwiZW1haWwiLCJ2YWwiLCJwYXNzd29yZCIsImFqYXgiLCJkYXRhVHlwZSIsIm1ldGhvZCIsInVybCIsImRhdGEiLCJjYWxsYmFjayIsInN1Y2Nlc3MiLCJyZXNwb25zZSIsIndpbmRvdyIsImxvY2F0aW9uIiwicmVkaXJlY3QiLCJhbGVydCIsImVycm9yIiwiZmFkZUluIl0sIm1hcHBpbmdzIjoiO0FBQUE7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7OztBQUdBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQUs7QUFDTDtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBLG1DQUEyQiwwQkFBMEIsRUFBRTtBQUN2RCx5Q0FBaUMsZUFBZTtBQUNoRDtBQUNBO0FBQ0E7O0FBRUE7QUFDQSw4REFBc0QsK0RBQStEOztBQUVySDtBQUNBOztBQUVBO0FBQ0E7Ozs7Ozs7Ozs7O0FDN0RBQSxFQUFFLFlBQVU7QUFDWEMsU0FBUUMsR0FBUixDQUFZLHFFQUFaLEVBQW1GLDZCQUFuRixFQUFrSCwrQkFBbEg7QUFDQUM7QUFDQSxDQUhEOztBQUtBO0FBQ0EsSUFBSUEsZ0JBQWdCLFNBQWhCQSxhQUFnQixHQUFVOztBQUU3QjtBQUNBSCxHQUFFLGNBQUYsRUFBa0JJLEtBQWxCOztBQUVBO0FBQ0FKLEdBQUUsY0FBRixFQUFrQkssS0FBbEIsQ0FBd0IsWUFBVTtBQUNqQ0wsSUFBRSxhQUFGLEVBQWlCLENBQWpCLEVBQW9CTSxLQUFwQjtBQUNBTixJQUFFLGdCQUFGLEVBQW9CLENBQXBCLEVBQXVCTSxLQUF2QjtBQUNBTixJQUFFLG1CQUFGLEVBQXVCTyxJQUF2QjtBQUNBUCxJQUFFLGdDQUFGLEVBQW9DUSxJQUFwQztBQUNBUixJQUFFLDhCQUFGLEVBQWtDUyxNQUFsQyxDQUF5QyxNQUF6QyxFQUFpRCxZQUFVO0FBQzFELE9BQUdULEVBQUUsYUFBRixFQUFpQlUsRUFBakIsQ0FBb0IsVUFBcEIsQ0FBSCxFQUFtQztBQUNsQ1YsTUFBRSxjQUFGLEVBQWtCSSxLQUFsQjtBQUNBLElBRkQsTUFFSztBQUNKSixNQUFFLGlCQUFGLEVBQXFCSSxLQUFyQjtBQUNBO0FBQ0QsR0FORDtBQVFBLEVBYkQ7O0FBZUFKLEdBQUUsYUFBRixFQUFpQlcsRUFBakIsQ0FBb0IsUUFBcEIsRUFBOEIsVUFBU0MsQ0FBVCxFQUFZO0FBQ3pDO0FBQ0FBLElBQUVDLGNBQUY7O0FBRUE7QUFDQSxNQUFJQyxRQUFRZCxFQUFFLGNBQUYsRUFBa0JlLEdBQWxCLEVBQVo7QUFDQSxNQUFJQyxXQUFXaEIsRUFBRSxpQkFBRixFQUFxQmUsR0FBckIsRUFBZjs7QUFFQSxNQUFHRCxTQUFTRSxRQUFaLEVBQXFCO0FBQ3BCO0FBQ0FoQixLQUFFaUIsSUFBRixDQUFPO0FBQ05DLGNBQVUsTUFESjtBQUVOQyxZQUFRLE1BRkY7QUFHTkMsU0FBSyx1QkFIQztBQUlOQyxVQUFNO0FBQ0xDLGVBQVUsT0FETDtBQUVMUixZQUFPQSxLQUZGO0FBR0xFLGVBQVVBO0FBSEwsS0FKQTtBQVNOTyxhQUFTLGlCQUFTQyxRQUFULEVBQWtCO0FBQzFCLFNBQUdBLFNBQVNELE9BQVosRUFBb0I7QUFDbkJFLGFBQU9DLFFBQVAsR0FBa0JGLFNBQVNHLFFBQTNCO0FBQ0EsTUFGRCxNQUVLO0FBQ0pDLFlBQU1KLFNBQVNLLEtBQWY7QUFDQTtBQUNEO0FBZkssSUFBUDtBQWlCQSxHQW5CRCxNQW1CSyxDQUVKO0FBQ0QsRUE5QkQ7O0FBZ0NBN0IsR0FBRSxnQkFBRixFQUFvQlcsRUFBcEIsQ0FBdUIsUUFBdkIsRUFBaUMsVUFBU0MsQ0FBVCxFQUFZOztBQUU1QztBQUNBQSxJQUFFQyxjQUFGOztBQUVBO0FBQ0EsTUFBSUMsUUFBUWQsRUFBRSxpQkFBRixFQUFxQmUsR0FBckIsRUFBWjs7QUFFQSxNQUFHRCxLQUFILEVBQVM7QUFDUmQsS0FBRWlCLElBQUYsQ0FBTztBQUNOQyxjQUFVLE1BREo7QUFFTkMsWUFBUSxNQUZGO0FBR05DLFNBQUssdUJBSEM7QUFJTkMsVUFBTTtBQUNMQyxlQUFVLFVBREw7QUFFTFIsWUFBT0E7QUFGRixLQUpBO0FBUU5TLGFBQVMsaUJBQVNDLFFBQVQsRUFBa0I7QUFDMUIsU0FBR0EsU0FBU0QsT0FBWixFQUFvQjtBQUNuQnZCLFFBQUUsZ0NBQUYsRUFBb0NPLElBQXBDO0FBQ0FQLFFBQUUsbUJBQUYsRUFBdUI4QixNQUF2QjtBQUNBLE1BSEQsTUFHSztBQUNKRixZQUFNSixTQUFTSyxLQUFmO0FBQ0E7QUFDRDtBQWZLLElBQVA7QUFpQkE7QUFDRCxFQTNCRDtBQTRCQSxDQWpGRCxDIiwiZmlsZSI6ImxvZ2luX2J1bmRsZS5qcyIsInNvdXJjZXNDb250ZW50IjpbIiBcdC8vIFRoZSBtb2R1bGUgY2FjaGVcbiBcdHZhciBpbnN0YWxsZWRNb2R1bGVzID0ge307XG5cbiBcdC8vIFRoZSByZXF1aXJlIGZ1bmN0aW9uXG4gXHRmdW5jdGlvbiBfX3dlYnBhY2tfcmVxdWlyZV9fKG1vZHVsZUlkKSB7XG5cbiBcdFx0Ly8gQ2hlY2sgaWYgbW9kdWxlIGlzIGluIGNhY2hlXG4gXHRcdGlmKGluc3RhbGxlZE1vZHVsZXNbbW9kdWxlSWRdKSB7XG4gXHRcdFx0cmV0dXJuIGluc3RhbGxlZE1vZHVsZXNbbW9kdWxlSWRdLmV4cG9ydHM7XG4gXHRcdH1cbiBcdFx0Ly8gQ3JlYXRlIGEgbmV3IG1vZHVsZSAoYW5kIHB1dCBpdCBpbnRvIHRoZSBjYWNoZSlcbiBcdFx0dmFyIG1vZHVsZSA9IGluc3RhbGxlZE1vZHVsZXNbbW9kdWxlSWRdID0ge1xuIFx0XHRcdGk6IG1vZHVsZUlkLFxuIFx0XHRcdGw6IGZhbHNlLFxuIFx0XHRcdGV4cG9ydHM6IHt9XG4gXHRcdH07XG5cbiBcdFx0Ly8gRXhlY3V0ZSB0aGUgbW9kdWxlIGZ1bmN0aW9uXG4gXHRcdG1vZHVsZXNbbW9kdWxlSWRdLmNhbGwobW9kdWxlLmV4cG9ydHMsIG1vZHVsZSwgbW9kdWxlLmV4cG9ydHMsIF9fd2VicGFja19yZXF1aXJlX18pO1xuXG4gXHRcdC8vIEZsYWcgdGhlIG1vZHVsZSBhcyBsb2FkZWRcbiBcdFx0bW9kdWxlLmwgPSB0cnVlO1xuXG4gXHRcdC8vIFJldHVybiB0aGUgZXhwb3J0cyBvZiB0aGUgbW9kdWxlXG4gXHRcdHJldHVybiBtb2R1bGUuZXhwb3J0cztcbiBcdH1cblxuXG4gXHQvLyBleHBvc2UgdGhlIG1vZHVsZXMgb2JqZWN0IChfX3dlYnBhY2tfbW9kdWxlc19fKVxuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5tID0gbW9kdWxlcztcblxuIFx0Ly8gZXhwb3NlIHRoZSBtb2R1bGUgY2FjaGVcbiBcdF9fd2VicGFja19yZXF1aXJlX18uYyA9IGluc3RhbGxlZE1vZHVsZXM7XG5cbiBcdC8vIGRlZmluZSBnZXR0ZXIgZnVuY3Rpb24gZm9yIGhhcm1vbnkgZXhwb3J0c1xuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5kID0gZnVuY3Rpb24oZXhwb3J0cywgbmFtZSwgZ2V0dGVyKSB7XG4gXHRcdGlmKCFfX3dlYnBhY2tfcmVxdWlyZV9fLm8oZXhwb3J0cywgbmFtZSkpIHtcbiBcdFx0XHRPYmplY3QuZGVmaW5lUHJvcGVydHkoZXhwb3J0cywgbmFtZSwge1xuIFx0XHRcdFx0Y29uZmlndXJhYmxlOiBmYWxzZSxcbiBcdFx0XHRcdGVudW1lcmFibGU6IHRydWUsXG4gXHRcdFx0XHRnZXQ6IGdldHRlclxuIFx0XHRcdH0pO1xuIFx0XHR9XG4gXHR9O1xuXG4gXHQvLyBnZXREZWZhdWx0RXhwb3J0IGZ1bmN0aW9uIGZvciBjb21wYXRpYmlsaXR5IHdpdGggbm9uLWhhcm1vbnkgbW9kdWxlc1xuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5uID0gZnVuY3Rpb24obW9kdWxlKSB7XG4gXHRcdHZhciBnZXR0ZXIgPSBtb2R1bGUgJiYgbW9kdWxlLl9fZXNNb2R1bGUgP1xuIFx0XHRcdGZ1bmN0aW9uIGdldERlZmF1bHQoKSB7IHJldHVybiBtb2R1bGVbJ2RlZmF1bHQnXTsgfSA6XG4gXHRcdFx0ZnVuY3Rpb24gZ2V0TW9kdWxlRXhwb3J0cygpIHsgcmV0dXJuIG1vZHVsZTsgfTtcbiBcdFx0X193ZWJwYWNrX3JlcXVpcmVfXy5kKGdldHRlciwgJ2EnLCBnZXR0ZXIpO1xuIFx0XHRyZXR1cm4gZ2V0dGVyO1xuIFx0fTtcblxuIFx0Ly8gT2JqZWN0LnByb3RvdHlwZS5oYXNPd25Qcm9wZXJ0eS5jYWxsXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLm8gPSBmdW5jdGlvbihvYmplY3QsIHByb3BlcnR5KSB7IHJldHVybiBPYmplY3QucHJvdG90eXBlLmhhc093blByb3BlcnR5LmNhbGwob2JqZWN0LCBwcm9wZXJ0eSk7IH07XG5cbiBcdC8vIF9fd2VicGFja19wdWJsaWNfcGF0aF9fXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLnAgPSBcIlwiO1xuXG4gXHQvLyBMb2FkIGVudHJ5IG1vZHVsZSBhbmQgcmV0dXJuIGV4cG9ydHNcbiBcdHJldHVybiBfX3dlYnBhY2tfcmVxdWlyZV9fKF9fd2VicGFja19yZXF1aXJlX18ucyA9IDEpO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIHdlYnBhY2svYm9vdHN0cmFwIGY0NTc0OWU5ODhhMjY4ZGE1ODM0IiwiJChmdW5jdGlvbigpe1xyXG5cdGNvbnNvbGUubG9nKFwiJWNTVE9QISBcXG4lY0RvIE5PVCBwYXN0ZS90eXBlIGFueXRoaW5nIGhlcmUgdW5kZXIgYW55IGNpcmN1bXN0YW5jZS5cIiwgXCJjb2xvcjogcmVkOyBmb250LXNpemU6MzZweDtcIiwgXCJjb2xvcjogYmxhY2s7IGZvbnQtc2l6ZTogMTJwdFwiKTtcclxuXHRMb2dpbkNvbnRyb2xzKCk7XHJcbn0pO1xyXG5cclxuLy8gbG9naW4ucGhwIGZ1bmN0aW9uc1xyXG52YXIgTG9naW5Db250cm9scyA9IGZ1bmN0aW9uKCl7XHJcblx0XHJcblx0Ly8gRm9jdXMgRW1haWwgRmllbGRcclxuXHQkKFwiI2xvZ2luX2VtYWlsXCIpLmZvY3VzKCk7XHJcblxyXG5cdC8vIFRvb2dsZSBGb3Jtc1xyXG5cdCQoXCIudG9nZ2xlX2Zvcm1cIikuY2xpY2soZnVuY3Rpb24oKXtcclxuXHRcdCQoXCIjbG9naW5fZm9ybVwiKVswXS5yZXNldCgpO1xyXG5cdFx0JChcIiNyZWdpc3Rlcl9mb3JtXCIpWzBdLnJlc2V0KCk7XHJcblx0XHQkKFwiI3JlZ2lzdGVyX3N1Y2Nlc3NcIikuaGlkZSgpO1xyXG5cdFx0JChcIiNyZWdpc3Rlcl9mb3JtLCAjcmVnaXN0ZXJfaW5mb1wiKS5zaG93KCk7XHJcblx0XHQkKFwiI2xvZ2luX3BhbmVsLCNyZWdpc3Rlcl9wYW5lbFwiKS50b2dnbGUoXCJzbG93XCIsIGZ1bmN0aW9uKCl7XHJcblx0XHRcdGlmKCQoXCIjbG9naW5fZm9ybVwiKS5pcygnOnZpc2libGUnKSl7XHJcblx0XHRcdFx0JChcIiNsb2dpbl9lbWFpbFwiKS5mb2N1cygpO1xyXG5cdFx0XHR9ZWxzZXtcclxuXHRcdFx0XHQkKFwiI3JlZ2lzdGVyX2VtYWlsXCIpLmZvY3VzKCk7XHJcblx0XHRcdH1cclxuXHRcdH0pO1xyXG5cclxuXHR9KTtcclxuXHJcblx0JChcIiNsb2dpbl9mb3JtXCIpLm9uKCdzdWJtaXQnLCBmdW5jdGlvbihlKSB7XHJcblx0XHQvLyBQcmV2ZW50IGZvcm0gc3VibWlzc2lvblxyXG5cdFx0ZS5wcmV2ZW50RGVmYXVsdCgpO1xyXG5cclxuXHRcdC8vIERlZmluZSBGaWVsZHNcclxuXHRcdHZhciBlbWFpbCA9ICQoXCIjbG9naW5fZW1haWxcIikudmFsKCk7XHJcblx0XHR2YXIgcGFzc3dvcmQgPSAkKFwiI2xvZ2luX3Bhc3N3b3JkXCIpLnZhbCgpO1xyXG5cclxuXHRcdGlmKGVtYWlsICYmIHBhc3N3b3JkKXtcclxuXHRcdFx0Ly8gQWpheFxyXG5cdFx0XHQkLmFqYXgoe1xyXG5cdFx0XHRcdGRhdGFUeXBlOiAnanNvbicsXHJcblx0XHRcdFx0bWV0aG9kOiAnUE9TVCcsXHJcblx0XHRcdFx0dXJsOiAnL3NlcnZlci9jYWxsYmFja3MucGhwJyxcclxuXHRcdFx0XHRkYXRhOiB7XHJcblx0XHRcdFx0XHRjYWxsYmFjazogJ2xvZ2luJyxcclxuXHRcdFx0XHRcdGVtYWlsOiBlbWFpbCxcclxuXHRcdFx0XHRcdHBhc3N3b3JkOiBwYXNzd29yZFxyXG5cdFx0XHRcdH0sXHJcblx0XHRcdFx0c3VjY2VzczogZnVuY3Rpb24ocmVzcG9uc2Upe1xyXG5cdFx0XHRcdFx0aWYocmVzcG9uc2Uuc3VjY2Vzcyl7XHJcblx0XHRcdFx0XHRcdHdpbmRvdy5sb2NhdGlvbiA9IHJlc3BvbnNlLnJlZGlyZWN0O1xyXG5cdFx0XHRcdFx0fWVsc2V7XHJcblx0XHRcdFx0XHRcdGFsZXJ0KHJlc3BvbnNlLmVycm9yKTtcclxuXHRcdFx0XHRcdH1cclxuXHRcdFx0XHR9XHJcblx0XHRcdH0pO1xyXG5cdFx0fWVsc2V7XHJcblxyXG5cdFx0fVxyXG5cdH0pO1xyXG5cclxuXHQkKFwiI3JlZ2lzdGVyX2Zvcm1cIikub24oJ3N1Ym1pdCcsIGZ1bmN0aW9uKGUpIHtcclxuXHJcblx0XHQvLyBQcmV2ZW50IGZvcm0gc3VibWlzc2lvblxyXG5cdFx0ZS5wcmV2ZW50RGVmYXVsdCgpO1xyXG5cclxuXHRcdC8vIERlZmluZSBGaWVsZHNcclxuXHRcdHZhciBlbWFpbCA9ICQoXCIjcmVnaXN0ZXJfZW1haWxcIikudmFsKCk7XHJcblxyXG5cdFx0aWYoZW1haWwpe1xyXG5cdFx0XHQkLmFqYXgoe1xyXG5cdFx0XHRcdGRhdGFUeXBlOiAnanNvbicsXHJcblx0XHRcdFx0bWV0aG9kOiAnUE9TVCcsXHJcblx0XHRcdFx0dXJsOiAnL3NlcnZlci9jYWxsYmFja3MucGhwJyxcclxuXHRcdFx0XHRkYXRhOiB7XHJcblx0XHRcdFx0XHRjYWxsYmFjazogJ3JlZ2lzdGVyJyxcclxuXHRcdFx0XHRcdGVtYWlsOiBlbWFpbFxyXG5cdFx0XHRcdH0sXHJcblx0XHRcdFx0c3VjY2VzczogZnVuY3Rpb24ocmVzcG9uc2Upe1xyXG5cdFx0XHRcdFx0aWYocmVzcG9uc2Uuc3VjY2Vzcyl7XHJcblx0XHRcdFx0XHRcdCQoXCIjcmVnaXN0ZXJfZm9ybSwgI3JlZ2lzdGVyX2luZm9cIikuaGlkZSgpO1xyXG5cdFx0XHRcdFx0XHQkKFwiI3JlZ2lzdGVyX3N1Y2Nlc3NcIikuZmFkZUluKCk7XHJcblx0XHRcdFx0XHR9ZWxzZXtcclxuXHRcdFx0XHRcdFx0YWxlcnQocmVzcG9uc2UuZXJyb3IpO1xyXG5cdFx0XHRcdFx0fVxyXG5cdFx0XHRcdH1cclxuXHRcdFx0fSk7XHJcblx0XHR9XHJcblx0fSk7XHJcbn07XHJcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2xvZ2luL2xvZ2luLmpzIl0sInNvdXJjZVJvb3QiOiIifQ==