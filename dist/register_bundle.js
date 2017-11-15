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
/******/ 	return __webpack_require__(__webpack_require__.s = 5);
/******/ })
/************************************************************************/
/******/ ({

/***/ 5:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


$(document).ready(function () {
	console.log("%cSTOP! \n%cDo NOT paste/type anything here under any circumstance.", "color: red; font-size:36px;", "color: black; font-size: 12pt");
	PasswordResetControls();
});

var PasswordResetControls = function PasswordResetControls() {
	// Validate Form
	$("#reset_password_form").on('submit', function (e) {

		// Prevent form submission
		e.preventDefault();

		// Define Fields
		var token = $("#reset_password_token").val();
		var email = $("#reset_password_email").val();
		var password = $("#reset_password_npassword").val();
		var vpassword = $("#reset_password_vpassword").val();

		if (email && password && vpassword) {
			// Ajax
			$.ajax({
				dataType: 'json',
				method: 'POST',
				url: '/server/callbacks.php',
				data: {
					callback: 'password_reset',
					token: token,
					email: email,
					password: password,
					vpassword: vpassword
				},
				success: function success(response) {
					if (response.success) {
						$("#reset_password_form, #reset_password_info").hide();
						$("#reset_password_success").fadeIn();
					} else {
						alert(response.error);
					}
				}
			});
		} else {
			// display some message about fields
		}
	});
};

/***/ })

/******/ });
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vd2VicGFjay9ib290c3RyYXAgZjQ1NzQ5ZTk4OGEyNjhkYTU4MzQiLCJ3ZWJwYWNrOi8vLy4vcmVnaXN0ZXIvcmVnaXN0ZXIuanMiXSwibmFtZXMiOlsiJCIsImRvY3VtZW50IiwicmVhZHkiLCJjb25zb2xlIiwibG9nIiwiUGFzc3dvcmRSZXNldENvbnRyb2xzIiwib24iLCJlIiwicHJldmVudERlZmF1bHQiLCJ0b2tlbiIsInZhbCIsImVtYWlsIiwicGFzc3dvcmQiLCJ2cGFzc3dvcmQiLCJhamF4IiwiZGF0YVR5cGUiLCJtZXRob2QiLCJ1cmwiLCJkYXRhIiwiY2FsbGJhY2siLCJzdWNjZXNzIiwicmVzcG9uc2UiLCJoaWRlIiwiZmFkZUluIiwiYWxlcnQiLCJlcnJvciJdLCJtYXBwaW5ncyI6IjtBQUFBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOzs7QUFHQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFLO0FBQ0w7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQSxtQ0FBMkIsMEJBQTBCLEVBQUU7QUFDdkQseUNBQWlDLGVBQWU7QUFDaEQ7QUFDQTtBQUNBOztBQUVBO0FBQ0EsOERBQXNELCtEQUErRDs7QUFFckg7QUFDQTs7QUFFQTtBQUNBOzs7Ozs7Ozs7OztBQzdEQUEsRUFBRUMsUUFBRixFQUFZQyxLQUFaLENBQWtCLFlBQVU7QUFDM0JDLFNBQVFDLEdBQVIsQ0FBWSxxRUFBWixFQUFtRiw2QkFBbkYsRUFBa0gsK0JBQWxIO0FBQ0FDO0FBQ0EsQ0FIRDs7QUFLQSxJQUFJQSx3QkFBd0IsU0FBeEJBLHFCQUF3QixHQUFVO0FBQ3JDO0FBQ0FMLEdBQUUsc0JBQUYsRUFBMEJNLEVBQTFCLENBQTZCLFFBQTdCLEVBQXVDLFVBQVNDLENBQVQsRUFBWTs7QUFFbEQ7QUFDQUEsSUFBRUMsY0FBRjs7QUFFQTtBQUNBLE1BQUlDLFFBQVFULEVBQUUsdUJBQUYsRUFBMkJVLEdBQTNCLEVBQVo7QUFDQSxNQUFJQyxRQUFRWCxFQUFFLHVCQUFGLEVBQTJCVSxHQUEzQixFQUFaO0FBQ0EsTUFBSUUsV0FBV1osRUFBRSwyQkFBRixFQUErQlUsR0FBL0IsRUFBZjtBQUNBLE1BQUlHLFlBQVliLEVBQUUsMkJBQUYsRUFBK0JVLEdBQS9CLEVBQWhCOztBQUVBLE1BQUdDLFNBQVNDLFFBQVQsSUFBcUJDLFNBQXhCLEVBQWtDO0FBQ2pDO0FBQ0FiLEtBQUVjLElBQUYsQ0FBTztBQUNOQyxjQUFVLE1BREo7QUFFTkMsWUFBUSxNQUZGO0FBR05DLFNBQUssdUJBSEM7QUFJTkMsVUFBTTtBQUNMQyxlQUFVLGdCQURMO0FBRUxWLFlBQU9BLEtBRkY7QUFHTEUsWUFBT0EsS0FIRjtBQUlMQyxlQUFVQSxRQUpMO0FBS0xDLGdCQUFXQTtBQUxOLEtBSkE7QUFXTk8sYUFBUyxpQkFBU0MsUUFBVCxFQUFrQjtBQUMxQixTQUFHQSxTQUFTRCxPQUFaLEVBQW9CO0FBQ25CcEIsUUFBRSw0Q0FBRixFQUFnRHNCLElBQWhEO0FBQ0F0QixRQUFFLHlCQUFGLEVBQTZCdUIsTUFBN0I7QUFDQSxNQUhELE1BR0s7QUFDSkMsWUFBTUgsU0FBU0ksS0FBZjtBQUNBO0FBQ0Q7QUFsQkssSUFBUDtBQW9CQSxHQXRCRCxNQXNCSztBQUNKO0FBQ0E7QUFFRCxFQXJDRDtBQXNDQSxDQXhDRCxDIiwiZmlsZSI6InJlZ2lzdGVyX2J1bmRsZS5qcyIsInNvdXJjZXNDb250ZW50IjpbIiBcdC8vIFRoZSBtb2R1bGUgY2FjaGVcbiBcdHZhciBpbnN0YWxsZWRNb2R1bGVzID0ge307XG5cbiBcdC8vIFRoZSByZXF1aXJlIGZ1bmN0aW9uXG4gXHRmdW5jdGlvbiBfX3dlYnBhY2tfcmVxdWlyZV9fKG1vZHVsZUlkKSB7XG5cbiBcdFx0Ly8gQ2hlY2sgaWYgbW9kdWxlIGlzIGluIGNhY2hlXG4gXHRcdGlmKGluc3RhbGxlZE1vZHVsZXNbbW9kdWxlSWRdKSB7XG4gXHRcdFx0cmV0dXJuIGluc3RhbGxlZE1vZHVsZXNbbW9kdWxlSWRdLmV4cG9ydHM7XG4gXHRcdH1cbiBcdFx0Ly8gQ3JlYXRlIGEgbmV3IG1vZHVsZSAoYW5kIHB1dCBpdCBpbnRvIHRoZSBjYWNoZSlcbiBcdFx0dmFyIG1vZHVsZSA9IGluc3RhbGxlZE1vZHVsZXNbbW9kdWxlSWRdID0ge1xuIFx0XHRcdGk6IG1vZHVsZUlkLFxuIFx0XHRcdGw6IGZhbHNlLFxuIFx0XHRcdGV4cG9ydHM6IHt9XG4gXHRcdH07XG5cbiBcdFx0Ly8gRXhlY3V0ZSB0aGUgbW9kdWxlIGZ1bmN0aW9uXG4gXHRcdG1vZHVsZXNbbW9kdWxlSWRdLmNhbGwobW9kdWxlLmV4cG9ydHMsIG1vZHVsZSwgbW9kdWxlLmV4cG9ydHMsIF9fd2VicGFja19yZXF1aXJlX18pO1xuXG4gXHRcdC8vIEZsYWcgdGhlIG1vZHVsZSBhcyBsb2FkZWRcbiBcdFx0bW9kdWxlLmwgPSB0cnVlO1xuXG4gXHRcdC8vIFJldHVybiB0aGUgZXhwb3J0cyBvZiB0aGUgbW9kdWxlXG4gXHRcdHJldHVybiBtb2R1bGUuZXhwb3J0cztcbiBcdH1cblxuXG4gXHQvLyBleHBvc2UgdGhlIG1vZHVsZXMgb2JqZWN0IChfX3dlYnBhY2tfbW9kdWxlc19fKVxuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5tID0gbW9kdWxlcztcblxuIFx0Ly8gZXhwb3NlIHRoZSBtb2R1bGUgY2FjaGVcbiBcdF9fd2VicGFja19yZXF1aXJlX18uYyA9IGluc3RhbGxlZE1vZHVsZXM7XG5cbiBcdC8vIGRlZmluZSBnZXR0ZXIgZnVuY3Rpb24gZm9yIGhhcm1vbnkgZXhwb3J0c1xuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5kID0gZnVuY3Rpb24oZXhwb3J0cywgbmFtZSwgZ2V0dGVyKSB7XG4gXHRcdGlmKCFfX3dlYnBhY2tfcmVxdWlyZV9fLm8oZXhwb3J0cywgbmFtZSkpIHtcbiBcdFx0XHRPYmplY3QuZGVmaW5lUHJvcGVydHkoZXhwb3J0cywgbmFtZSwge1xuIFx0XHRcdFx0Y29uZmlndXJhYmxlOiBmYWxzZSxcbiBcdFx0XHRcdGVudW1lcmFibGU6IHRydWUsXG4gXHRcdFx0XHRnZXQ6IGdldHRlclxuIFx0XHRcdH0pO1xuIFx0XHR9XG4gXHR9O1xuXG4gXHQvLyBnZXREZWZhdWx0RXhwb3J0IGZ1bmN0aW9uIGZvciBjb21wYXRpYmlsaXR5IHdpdGggbm9uLWhhcm1vbnkgbW9kdWxlc1xuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5uID0gZnVuY3Rpb24obW9kdWxlKSB7XG4gXHRcdHZhciBnZXR0ZXIgPSBtb2R1bGUgJiYgbW9kdWxlLl9fZXNNb2R1bGUgP1xuIFx0XHRcdGZ1bmN0aW9uIGdldERlZmF1bHQoKSB7IHJldHVybiBtb2R1bGVbJ2RlZmF1bHQnXTsgfSA6XG4gXHRcdFx0ZnVuY3Rpb24gZ2V0TW9kdWxlRXhwb3J0cygpIHsgcmV0dXJuIG1vZHVsZTsgfTtcbiBcdFx0X193ZWJwYWNrX3JlcXVpcmVfXy5kKGdldHRlciwgJ2EnLCBnZXR0ZXIpO1xuIFx0XHRyZXR1cm4gZ2V0dGVyO1xuIFx0fTtcblxuIFx0Ly8gT2JqZWN0LnByb3RvdHlwZS5oYXNPd25Qcm9wZXJ0eS5jYWxsXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLm8gPSBmdW5jdGlvbihvYmplY3QsIHByb3BlcnR5KSB7IHJldHVybiBPYmplY3QucHJvdG90eXBlLmhhc093blByb3BlcnR5LmNhbGwob2JqZWN0LCBwcm9wZXJ0eSk7IH07XG5cbiBcdC8vIF9fd2VicGFja19wdWJsaWNfcGF0aF9fXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLnAgPSBcIlwiO1xuXG4gXHQvLyBMb2FkIGVudHJ5IG1vZHVsZSBhbmQgcmV0dXJuIGV4cG9ydHNcbiBcdHJldHVybiBfX3dlYnBhY2tfcmVxdWlyZV9fKF9fd2VicGFja19yZXF1aXJlX18ucyA9IDUpO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIHdlYnBhY2svYm9vdHN0cmFwIGY0NTc0OWU5ODhhMjY4ZGE1ODM0IiwiJChkb2N1bWVudCkucmVhZHkoZnVuY3Rpb24oKXtcclxuXHRjb25zb2xlLmxvZyhcIiVjU1RPUCEgXFxuJWNEbyBOT1QgcGFzdGUvdHlwZSBhbnl0aGluZyBoZXJlIHVuZGVyIGFueSBjaXJjdW1zdGFuY2UuXCIsIFwiY29sb3I6IHJlZDsgZm9udC1zaXplOjM2cHg7XCIsIFwiY29sb3I6IGJsYWNrOyBmb250LXNpemU6IDEycHRcIik7XHJcblx0UGFzc3dvcmRSZXNldENvbnRyb2xzKCk7XHJcbn0pO1xyXG5cclxudmFyIFBhc3N3b3JkUmVzZXRDb250cm9scyA9IGZ1bmN0aW9uKCl7XHJcblx0Ly8gVmFsaWRhdGUgRm9ybVxyXG5cdCQoXCIjcmVzZXRfcGFzc3dvcmRfZm9ybVwiKS5vbignc3VibWl0JywgZnVuY3Rpb24oZSkge1xyXG5cclxuXHRcdC8vIFByZXZlbnQgZm9ybSBzdWJtaXNzaW9uXHJcblx0XHRlLnByZXZlbnREZWZhdWx0KCk7XHJcblxyXG5cdFx0Ly8gRGVmaW5lIEZpZWxkc1xyXG5cdFx0dmFyIHRva2VuID0gJChcIiNyZXNldF9wYXNzd29yZF90b2tlblwiKS52YWwoKTtcclxuXHRcdHZhciBlbWFpbCA9ICQoXCIjcmVzZXRfcGFzc3dvcmRfZW1haWxcIikudmFsKCk7XHJcblx0XHR2YXIgcGFzc3dvcmQgPSAkKFwiI3Jlc2V0X3Bhc3N3b3JkX25wYXNzd29yZFwiKS52YWwoKTtcclxuXHRcdHZhciB2cGFzc3dvcmQgPSAkKFwiI3Jlc2V0X3Bhc3N3b3JkX3ZwYXNzd29yZFwiKS52YWwoKTtcclxuXHJcblx0XHRpZihlbWFpbCAmJiBwYXNzd29yZCAmJiB2cGFzc3dvcmQpe1xyXG5cdFx0XHQvLyBBamF4XHJcblx0XHRcdCQuYWpheCh7XHJcblx0XHRcdFx0ZGF0YVR5cGU6ICdqc29uJyxcclxuXHRcdFx0XHRtZXRob2Q6ICdQT1NUJyxcclxuXHRcdFx0XHR1cmw6ICcvc2VydmVyL2NhbGxiYWNrcy5waHAnLFxyXG5cdFx0XHRcdGRhdGE6IHtcclxuXHRcdFx0XHRcdGNhbGxiYWNrOiAncGFzc3dvcmRfcmVzZXQnLFxyXG5cdFx0XHRcdFx0dG9rZW46IHRva2VuLFxyXG5cdFx0XHRcdFx0ZW1haWw6IGVtYWlsLFxyXG5cdFx0XHRcdFx0cGFzc3dvcmQ6IHBhc3N3b3JkLFxyXG5cdFx0XHRcdFx0dnBhc3N3b3JkOiB2cGFzc3dvcmRcclxuXHRcdFx0XHR9LFxyXG5cdFx0XHRcdHN1Y2Nlc3M6IGZ1bmN0aW9uKHJlc3BvbnNlKXtcclxuXHRcdFx0XHRcdGlmKHJlc3BvbnNlLnN1Y2Nlc3Mpe1xyXG5cdFx0XHRcdFx0XHQkKFwiI3Jlc2V0X3Bhc3N3b3JkX2Zvcm0sICNyZXNldF9wYXNzd29yZF9pbmZvXCIpLmhpZGUoKTtcclxuXHRcdFx0XHRcdFx0JChcIiNyZXNldF9wYXNzd29yZF9zdWNjZXNzXCIpLmZhZGVJbigpO1xyXG5cdFx0XHRcdFx0fWVsc2V7XHJcblx0XHRcdFx0XHRcdGFsZXJ0KHJlc3BvbnNlLmVycm9yKTtcclxuXHRcdFx0XHRcdH1cclxuXHRcdFx0XHR9XHJcblx0XHRcdH0pO1xyXG5cdFx0fWVsc2V7XHJcblx0XHRcdC8vIGRpc3BsYXkgc29tZSBtZXNzYWdlIGFib3V0IGZpZWxkc1xyXG5cdFx0fVxyXG5cclxuXHR9KTtcclxufTtcclxuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vcmVnaXN0ZXIvcmVnaXN0ZXIuanMiXSwic291cmNlUm9vdCI6IiJ9