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
/******/ 	return __webpack_require__(__webpack_require__.s = 6);
/******/ })
/************************************************************************/
/******/ ({

/***/ 6:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


$(document).ready(function () {
	conditionalFormFields();

	validateSignupForm();

	// Since non-required fields are "valid" when they are empty, we need an
	// alternate way to keep labels raised when there is content in their
	// associated input field
	$(".form-textfield input").blur(function () {
		var isBlank = $.trim($(this).val()).length > 0;
		$label = $(this).siblings(".form-label").toggleClass("form-label__floating", isBlank);
	});
});

function validateSignupForm() {
	$("#vitaSignupForm").validate({
		rules: {
			"firstName": "required",
			"lastName": "required",
			email: {
				required: true,
				email: true
			},
			phone: {
				required: true
				// phoneUS: true
			}
		},
		messages: {
			email: {
				required: "We need your email address to confirm your appointment",
				email: "Your email address must be in the format of name@domain.com"
			}
		}
	});
}

function scrollDown(height) {
	var animationTime = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 0;

	$('html, body').animate({
		scrollTop: '+=' + height
	}, animationTime);
}

function scrollToElement(element) {
	var animationTime = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 0;

	$('.navbar-collapse.collapse').collapse('hide');
	var offset = element.offset().top;
	$('html, body').animate({
		scrollTop: offset - $('.navbar').height() - 50
	}, animationTime);
}

function showAppointmentPicker() {
	var animationTime = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 300;

	$("#studentScholarAppointmentPicker").hide();
	$("#appointmentPicker").show();
	scrollToElement($("#appointmentPicker"), animationTime);
}

function showStudentScholarAppointmentPicker() {
	var animationTime = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 300;

	$("#appointmentPicker").hide();
	$("#studentScholarAppointmentPicker").show();
	scrollToElement($("#studentScholarAppointmentPicker"), animationTime);
}

function conditionalFormFields() {
	var animationTime = 300;

	// All of the questions that required conditions to be viewed.
	var homeBased = $("#homeBased");
	var homeBasedNetLoss = $("#homeBasedNetLoss");
	var homeBased10000 = $("#homeBased10000");
	var homeBasedSEP = $("#homeBasedSEP");
	var homeBasedEmployees = $("#homeBasedEmployees");
	var studentUNL = $("#studentUNL");
	var studentInt = $("#studentInt");
	var studentIntVisa = $("#studentIntVisa");
	var studentf1 = $("#studentf1");
	var studentj1 = $("#studentj1");
	var studenth1b = $("#studenth1b");
	var appointmentPicker = $("#appointmentPicker");
	var studentScholarAppointmentPicker = $("#studentScholarAppointmentPicker");

	// All the radio buttons
	var homeBasedValues = homeBased.find('input:radio[name="3"]');
	var studentUNLValues = studentUNL.find('input:radio[name="15"]');
	var studentIntValues = studentInt.find('input:radio[name="16"]');
	var studentIntVisaValues = studentIntVisa.find('input:radio[name="17"]');
	var studentf1Values = studentf1.find('input:radio[name="18"]');
	var studentj1Values = studentj1.find('input:radio[name="18"]');
	var studenth1bValues = studenth1b.find('input:radio[name="19"]');

	// To help hide everything and selectively show content
	var allUnderHomeBasedValues = homeBasedNetLoss.add(homeBased10000).add(homeBasedSEP).add(homeBasedEmployees);
	var allUnderstudentIntVisaValues = studentf1.add(studentj1).add(studenth1b);
	var allUnderStudentIntValues = studentIntVisa.add(allUnderstudentIntVisaValues);
	var allUnderStudentUNLValues = studentInt.add(allUnderStudentIntValues).add(allUnderstudentIntVisaValues);

	// Independent field = #homeBased
	// Dependent field = if yes --> #homeBasedNetLoss,#homeBased10000,#homeBasedSEP,#homeBasedEmployees
	homeBasedValues.change(function () {
		var value = this.value;
		if (this.checked) {
			if (value === "1") {
				allUnderHomeBasedValues.slideUp(animationTime);
				allUnderHomeBasedValues.slideDown(animationTime);
				scrollDown(homeBased.height(), animationTime);
			} else if (value === "2") {
				allUnderHomeBasedValues.slideUp(animationTime);
			}
		}
	});

	// Independent field = #studentUNL
	// Dependent field = if yes --> #studentInt
	//                   if no --> appointmentPicker
	studentUNLValues.change(function () {
		var value = this.value;
		allUnderStudentUNLValues.slideUp(animationTime);
		if (value === "1") {
			studentInt.slideDown(animationTime);
			studentIntValues.change();
			scrollDown(studentUNL.height(), animationTime);
		} else if (value === "2") {
			showAppointmentPicker();
		}
	});

	// Independent field = #studentInt
	// Dependent field = if yes --> #studentIntVisa
	//                   if no --> appointmentPicker
	studentIntValues.change(function () {
		var value = this.value;
		if (this.checked) {
			allUnderStudentIntValues.slideUp(animationTime);
			if (value === "1") {
				studentIntVisa.slideDown(animationTime);
				scrollDown(studentInt.height(), animationTime);
				studentIntVisaValues.change();
			} else if (value === "2") {
				showAppointmentPicker();
			}
		}
	});

	// Independent field = #studentIntVisa
	// Dependent field = if f1 --> #studentf1
	//                   if j1 --> #studentj1
	//                   if h1b --> #studenth1b
	studentIntVisaValues.change(function () {
		var value = this.value;
		if (this.checked) {
			if (value === "4") {
				allUnderstudentIntVisaValues.hide(animationTime);
				studentf1.show(animationTime);
				studentf1Values.change();
			} else if (value === "5") {
				allUnderstudentIntVisaValues.hide(animationTime);
				studentj1.show(animationTime);
				studentj1Values.change();
			} else if (value === "6") {
				allUnderstudentIntVisaValues.hide(animationTime);
				studenth1b.show(animationTime);
				studenth1bValues.change();
			}
		}
	});

	// Independent field = #studentf1
	// Dependent field = if 2011 or earlier --> studentScholarAppointmentPicker
	//                   if 2012 or later --> appointmentPicker
	studentf1Values.change(function () {
		var value = this.value;
		if (this.checked) {
			if (value === "8") {
				scrollDown(appointmentPicker.height(), animationTime);
				showStudentScholarAppointmentPicker(animationTime);
			} else {
				scrollDown(studentScholarAppointmentPicker.height(), animationTime);
				showAppointmentPicker(animationTime);
			}
		}
	});

	// Independent field = #studentj1
	// Dependent field = any --> appointmentPicker
	studentj1Values.change(function () {
		showAppointmentPicker(animationTime);
	});

	// Independent field = #studenth1b
	// Dependent field = any --> appointmentPicker
	studenth1bValues.change(function () {
		showAppointmentPicker(animationTime);
	});
}

// Form submission
$('#vitaSignupForm').submit(function (e) {
	// Stop default form submit action
	// e.preventDefault();

	if (!$(this).valid()) {
		return false;
	}

	var questions = [];
	$('.form-radio').each(function () {
		var checkedRadioBox = $(this).find('input[type="radio"]:checked');

		if (checkedRadioBox.length > 0) {
			questions.push({
				id: checkedRadioBox.attr('name'),
				value: checkedRadioBox.val()
			});
		}
	});

	var data = {
		"firstName": firstName.value,
		"lastName": lastName.value,
		"email": email.value,
		"phone": phone.value,
		"questions": questions,

		//TODO
		"scheduledTime": '2017-07-26T15:30:00',
		"siteId": 3
	};

	// AJAX Code To Submit Form.
	$.ajax({
		url: "/server/storeAppointment.php",
		type: "post",
		dataType: 'json',
		data: data,
		cache: false,
		complete: function complete(response) {
			response = response.responseJSON;

			if (typeof response !== 'undefined' && response && response.success) {
				$(vitaSignupForm).hide();
				$(responsePlaceholder).show();
				responsePlaceholder.innerHTML = response.message;
			} else {
				alert('There was an error on the server! Please refresh the page in a few minutes and try again.');
			}
		}
	});

	return false;
});

/***/ })

/******/ });
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vd2VicGFjay9ib290c3RyYXAgZjQ1NzQ5ZTk4OGEyNjhkYTU4MzQiLCJ3ZWJwYWNrOi8vLy4vc2lnbnVwL3NpZ251cC5qcyJdLCJuYW1lcyI6WyIkIiwiZG9jdW1lbnQiLCJyZWFkeSIsImNvbmRpdGlvbmFsRm9ybUZpZWxkcyIsInZhbGlkYXRlU2lnbnVwRm9ybSIsImJsdXIiLCJpc0JsYW5rIiwidHJpbSIsInZhbCIsImxlbmd0aCIsIiRsYWJlbCIsInNpYmxpbmdzIiwidG9nZ2xlQ2xhc3MiLCJ2YWxpZGF0ZSIsInJ1bGVzIiwiZW1haWwiLCJyZXF1aXJlZCIsInBob25lIiwibWVzc2FnZXMiLCJzY3JvbGxEb3duIiwiaGVpZ2h0IiwiYW5pbWF0aW9uVGltZSIsImFuaW1hdGUiLCJzY3JvbGxUb3AiLCJzY3JvbGxUb0VsZW1lbnQiLCJlbGVtZW50IiwiY29sbGFwc2UiLCJvZmZzZXQiLCJ0b3AiLCJzaG93QXBwb2ludG1lbnRQaWNrZXIiLCJoaWRlIiwic2hvdyIsInNob3dTdHVkZW50U2Nob2xhckFwcG9pbnRtZW50UGlja2VyIiwiaG9tZUJhc2VkIiwiaG9tZUJhc2VkTmV0TG9zcyIsImhvbWVCYXNlZDEwMDAwIiwiaG9tZUJhc2VkU0VQIiwiaG9tZUJhc2VkRW1wbG95ZWVzIiwic3R1ZGVudFVOTCIsInN0dWRlbnRJbnQiLCJzdHVkZW50SW50VmlzYSIsInN0dWRlbnRmMSIsInN0dWRlbnRqMSIsInN0dWRlbnRoMWIiLCJhcHBvaW50bWVudFBpY2tlciIsInN0dWRlbnRTY2hvbGFyQXBwb2ludG1lbnRQaWNrZXIiLCJob21lQmFzZWRWYWx1ZXMiLCJmaW5kIiwic3R1ZGVudFVOTFZhbHVlcyIsInN0dWRlbnRJbnRWYWx1ZXMiLCJzdHVkZW50SW50VmlzYVZhbHVlcyIsInN0dWRlbnRmMVZhbHVlcyIsInN0dWRlbnRqMVZhbHVlcyIsInN0dWRlbnRoMWJWYWx1ZXMiLCJhbGxVbmRlckhvbWVCYXNlZFZhbHVlcyIsImFkZCIsImFsbFVuZGVyc3R1ZGVudEludFZpc2FWYWx1ZXMiLCJhbGxVbmRlclN0dWRlbnRJbnRWYWx1ZXMiLCJhbGxVbmRlclN0dWRlbnRVTkxWYWx1ZXMiLCJjaGFuZ2UiLCJ2YWx1ZSIsImNoZWNrZWQiLCJzbGlkZVVwIiwic2xpZGVEb3duIiwic3VibWl0IiwiZSIsInZhbGlkIiwicXVlc3Rpb25zIiwiZWFjaCIsImNoZWNrZWRSYWRpb0JveCIsInB1c2giLCJpZCIsImF0dHIiLCJkYXRhIiwiZmlyc3ROYW1lIiwibGFzdE5hbWUiLCJhamF4IiwidXJsIiwidHlwZSIsImRhdGFUeXBlIiwiY2FjaGUiLCJjb21wbGV0ZSIsInJlc3BvbnNlIiwicmVzcG9uc2VKU09OIiwic3VjY2VzcyIsInZpdGFTaWdudXBGb3JtIiwicmVzcG9uc2VQbGFjZWhvbGRlciIsImlubmVySFRNTCIsIm1lc3NhZ2UiLCJhbGVydCJdLCJtYXBwaW5ncyI6IjtBQUFBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOzs7QUFHQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFLO0FBQ0w7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQSxtQ0FBMkIsMEJBQTBCLEVBQUU7QUFDdkQseUNBQWlDLGVBQWU7QUFDaEQ7QUFDQTtBQUNBOztBQUVBO0FBQ0EsOERBQXNELCtEQUErRDs7QUFFckg7QUFDQTs7QUFFQTtBQUNBOzs7Ozs7Ozs7OztBQzdEQUEsRUFBRUMsUUFBRixFQUFZQyxLQUFaLENBQWtCLFlBQVc7QUFDNUJDOztBQUVBQzs7QUFFQTtBQUNBO0FBQ0E7QUFDQUosR0FBRSx1QkFBRixFQUEyQkssSUFBM0IsQ0FBZ0MsWUFBVztBQUMxQyxNQUFJQyxVQUFVTixFQUFFTyxJQUFGLENBQU9QLEVBQUUsSUFBRixFQUFRUSxHQUFSLEVBQVAs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RUFBUStDLElBQVIsQ0FBYSw2QkFBYixDQUF0Qjs7QUFFQSxNQUFHc0IsZ0JBQWdCNUQsTUFBaEIsR0FBdUIsQ0FBMUIsRUFBNkI7QUFDNUIwRCxhQUFVRyxJQUFWLENBQWU7QUFDZEMsUUFBSUYsZ0JBQWdCRyxJQUFoQixDQUFxQixNQUFyQixDQURVO0FBRWRaLFdBQU9TLGdCQUFnQjdELEdBQWhCO0FBRk8sSUFBZjtBQUlBO0FBQ0QsRUFURDs7QUFXQSxLQUFJaUUsT0FBTztBQUNWLGVBQVlDLFVBQVVkLEtBRFo7QUFFVixjQUFXZSxTQUFTZixLQUZWO0FBR1YsV0FBUTdDLE1BQU02QyxLQUhKO0FBSVYsV0FBUTNDLE1BQU0yQyxLQUpKO0FBS1YsZUFBYU8sU0FMSDs7QUFPVjtBQUNBLG1CQUFpQixxQkFSUDtBQVNWLFlBQVU7QUFUQSxFQUFYOztBQVlBO0FBQ0FuRSxHQUFFNEUsSUFBRixDQUFPO0FBQ05DLE9BQUssOEJBREM7QUFFTkMsUUFBTSxNQUZBO0FBR05DLFlBQVUsTUFISjtBQUlOTixRQUFPQSxJQUpEO0FBS05PLFNBQU8sS0FMRDtBQU1OQyxZQUFVLGtCQUFTQyxRQUFULEVBQWtCO0FBQzNCQSxjQUFXQSxTQUFTQyxZQUFwQjs7QUFFQSxPQUFHLE9BQU9ELFFBQVAsS0FBb0IsV0FBcEIsSUFBbUNBLFFBQW5DLElBQStDQSxTQUFTRSxPQUEzRCxFQUFtRTtBQUNsRXBGLE1BQUVxRixjQUFGLEVBQWtCdkQsSUFBbEI7QUFDQTlCLE1BQUVzRixtQkFBRixFQUF1QnZELElBQXZCO0FBQ0F1RCx3QkFBb0JDLFNBQXBCLEdBQWdDTCxTQUFTTSxPQUF6QztBQUNBLElBSkQsTUFJSztBQUNKQyxVQUFNLDJGQUFOO0FBQ0E7QUFDRDtBQWhCSyxFQUFQOztBQW1CQSxRQUFPLEtBQVA7QUFDQSxDQXJERCxFIiwiZmlsZSI6InNpZ251cF9idW5kbGUuanMiLCJzb3VyY2VzQ29udGVudCI6WyIgXHQvLyBUaGUgbW9kdWxlIGNhY2hlXG4gXHR2YXIgaW5zdGFsbGVkTW9kdWxlcyA9IHt9O1xuXG4gXHQvLyBUaGUgcmVxdWlyZSBmdW5jdGlvblxuIFx0ZnVuY3Rpb24gX193ZWJwYWNrX3JlcXVpcmVfXyhtb2R1bGVJZCkge1xuXG4gXHRcdC8vIENoZWNrIGlmIG1vZHVsZSBpcyBpbiBjYWNoZVxuIFx0XHRpZihpbnN0YWxsZWRNb2R1bGVzW21vZHVsZUlkXSkge1xuIFx0XHRcdHJldHVybiBpbnN0YWxsZWRNb2R1bGVzW21vZHVsZUlkXS5leHBvcnRzO1xuIFx0XHR9XG4gXHRcdC8vIENyZWF0ZSBhIG5ldyBtb2R1bGUgKGFuZCBwdXQgaXQgaW50byB0aGUgY2FjaGUpXG4gXHRcdHZhciBtb2R1bGUgPSBpbnN0YWxsZWRNb2R1bGVzW21vZHVsZUlkXSA9IHtcbiBcdFx0XHRpOiBtb2R1bGVJZCxcbiBcdFx0XHRsOiBmYWxzZSxcbiBcdFx0XHRleHBvcnRzOiB7fVxuIFx0XHR9O1xuXG4gXHRcdC8vIEV4ZWN1dGUgdGhlIG1vZHVsZSBmdW5jdGlvblxuIFx0XHRtb2R1bGVzW21vZHVsZUlkXS5jYWxsKG1vZHVsZS5leHBvcnRzLCBtb2R1bGUsIG1vZHVsZS5leHBvcnRzLCBfX3dlYnBhY2tfcmVxdWlyZV9fKTtcblxuIFx0XHQvLyBGbGFnIHRoZSBtb2R1bGUgYXMgbG9hZGVkXG4gXHRcdG1vZHVsZS5sID0gdHJ1ZTtcblxuIFx0XHQvLyBSZXR1cm4gdGhlIGV4cG9ydHMgb2YgdGhlIG1vZHVsZVxuIFx0XHRyZXR1cm4gbW9kdWxlLmV4cG9ydHM7XG4gXHR9XG5cblxuIFx0Ly8gZXhwb3NlIHRoZSBtb2R1bGVzIG9iamVjdCAoX193ZWJwYWNrX21vZHVsZXNfXylcbiBcdF9fd2VicGFja19yZXF1aXJlX18ubSA9IG1vZHVsZXM7XG5cbiBcdC8vIGV4cG9zZSB0aGUgbW9kdWxlIGNhY2hlXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLmMgPSBpbnN0YWxsZWRNb2R1bGVzO1xuXG4gXHQvLyBkZWZpbmUgZ2V0dGVyIGZ1bmN0aW9uIGZvciBoYXJtb255IGV4cG9ydHNcbiBcdF9fd2VicGFja19yZXF1aXJlX18uZCA9IGZ1bmN0aW9uKGV4cG9ydHMsIG5hbWUsIGdldHRlcikge1xuIFx0XHRpZighX193ZWJwYWNrX3JlcXVpcmVfXy5vKGV4cG9ydHMsIG5hbWUpKSB7XG4gXHRcdFx0T2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsIG5hbWUsIHtcbiBcdFx0XHRcdGNvbmZpZ3VyYWJsZTogZmFsc2UsXG4gXHRcdFx0XHRlbnVtZXJhYmxlOiB0cnVlLFxuIFx0XHRcdFx0Z2V0OiBnZXR0ZXJcbiBcdFx0XHR9KTtcbiBcdFx0fVxuIFx0fTtcblxuIFx0Ly8gZ2V0RGVmYXVsdEV4cG9ydCBmdW5jdGlvbiBmb3IgY29tcGF0aWJpbGl0eSB3aXRoIG5vbi1oYXJtb255IG1vZHVsZXNcbiBcdF9fd2VicGFja19yZXF1aXJlX18ubiA9IGZ1bmN0aW9uKG1vZHVsZSkge1xuIFx0XHR2YXIgZ2V0dGVyID0gbW9kdWxlICYmIG1vZHVsZS5fX2VzTW9kdWxlID9cbiBcdFx0XHRmdW5jdGlvbiBnZXREZWZhdWx0KCkgeyByZXR1cm4gbW9kdWxlWydkZWZhdWx0J107IH0gOlxuIFx0XHRcdGZ1bmN0aW9uIGdldE1vZHVsZUV4cG9ydHMoKSB7IHJldHVybiBtb2R1bGU7IH07XG4gXHRcdF9fd2VicGFja19yZXF1aXJlX18uZChnZXR0ZXIsICdhJywgZ2V0dGVyKTtcbiBcdFx0cmV0dXJuIGdldHRlcjtcbiBcdH07XG5cbiBcdC8vIE9iamVjdC5wcm90b3R5cGUuaGFzT3duUHJvcGVydHkuY2FsbFxuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5vID0gZnVuY3Rpb24ob2JqZWN0LCBwcm9wZXJ0eSkgeyByZXR1cm4gT2JqZWN0LnByb3RvdHlwZS5oYXNPd25Qcm9wZXJ0eS5jYWxsKG9iamVjdCwgcHJvcGVydHkpOyB9O1xuXG4gXHQvLyBfX3dlYnBhY2tfcHVibGljX3BhdGhfX1xuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5wID0gXCJcIjtcblxuIFx0Ly8gTG9hZCBlbnRyeSBtb2R1bGUgYW5kIHJldHVybiBleHBvcnRzXG4gXHRyZXR1cm4gX193ZWJwYWNrX3JlcXVpcmVfXyhfX3dlYnBhY2tfcmVxdWlyZV9fLnMgPSA2KTtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyB3ZWJwYWNrL2Jvb3RzdHJhcCBmNDU3NDllOTg4YTI2OGRhNTgzNCIsIiQoZG9jdW1lbnQpLnJlYWR5KGZ1bmN0aW9uKCkge1xyXG5cdGNvbmRpdGlvbmFsRm9ybUZpZWxkcygpO1xyXG5cclxuXHR2YWxpZGF0ZVNpZ251cEZvcm0oKTtcclxuXHJcblx0Ly8gU2luY2Ugbm9uLXJlcXVpcmVkIGZpZWxkcyBhcmUgXCJ2YWxpZFwiIHdoZW4gdGhleSBhcmUgZW1wdHksIHdlIG5lZWQgYW5cclxuXHQvLyBhbHRlcm5hdGUgd2F5IHRvIGtlZXAgbGFiZWxzIHJhaXNlZCB3aGVuIHRoZXJlIGlzIGNvbnRlbnQgaW4gdGhlaXJcclxuXHQvLyBhc3NvY2lhdGVkIGlucHV0IGZpZWxkXHJcblx0JChcIi5mb3JtLXRleHRmaWVsZCBpbnB1dFwiKS5ibHVyKGZ1bmN0aW9uKCkge1xyXG5cdFx0dmFyIGlzQmxhbmsgPSAkLnRyaW0oJCh0aGlzKS52YWwoKSkubGVuZ3RoID4gMDtcclxuXHRcdCRsYWJlbCA9ICQodGhpcykuc2libGluZ3MoXCIuZm9ybS1sYWJlbFwiKS50b2dnbGVDbGFzcyggXCJmb3JtLWxhYmVsX19mbG9hdGluZ1wiLCBpc0JsYW5rICk7XHJcblx0fSk7XHJcblxyXG59KTtcclxuXHJcbmZ1bmN0aW9uIHZhbGlkYXRlU2lnbnVwRm9ybSgpIHtcclxuXHQkKFwiI3ZpdGFTaWdudXBGb3JtXCIpLnZhbGlkYXRlKHtcclxuXHRcdHJ1bGVzOiB7XHJcblx0XHRcdFwiZmlyc3ROYW1lXCI6IFwicmVxdWlyZWRcIixcclxuXHRcdFx0XCJsYXN0TmFtZVwiOiBcInJlcXVpcmVkXCIsXHJcblx0XHRcdGVtYWlsOiB7XHJcblx0XHRcdFx0cmVxdWlyZWQ6IHRydWUsXHJcblx0XHRcdFx0ZW1haWw6IHRydWVcclxuXHRcdFx0fSxcclxuXHRcdFx0cGhvbmU6IHtcclxuXHRcdFx0XHRyZXF1aXJlZDogdHJ1ZSxcclxuXHRcdFx0XHQvLyBwaG9uZVVTOiB0cnVlXHJcblx0XHRcdH1cclxuXHRcdH0sXHJcblx0XHRtZXNzYWdlczoge1xyXG5cdFx0XHRlbWFpbDoge1xyXG5cdFx0XHRcdHJlcXVpcmVkOiBcIldlIG5lZWQgeW91ciBlbWFpbCBhZGRyZXNzIHRvIGNvbmZpcm0geW91ciBhcHBvaW50bWVudFwiLFxyXG5cdFx0XHRcdGVtYWlsOiBcIllvdXIgZW1haWwgYWRkcmVzcyBtdXN0IGJlIGluIHRoZSBmb3JtYXQgb2YgbmFtZUBkb21haW4uY29tXCJcclxuXHRcdFx0fVxyXG5cdFx0fVxyXG5cdH0pO1xyXG59XHJcblxyXG5mdW5jdGlvbiBzY3JvbGxEb3duKGhlaWdodCwgYW5pbWF0aW9uVGltZSA9IDApIHtcclxuXHQkKCdodG1sLCBib2R5JykuYW5pbWF0ZSh7XHJcblx0XHRzY3JvbGxUb3A6ICcrPScgKyBoZWlnaHRcclxuXHR9LCBhbmltYXRpb25UaW1lKTtcclxufVxyXG5cclxuZnVuY3Rpb24gc2Nyb2xsVG9FbGVtZW50KGVsZW1lbnQsIGFuaW1hdGlvblRpbWUgPSAwKSB7XHJcblx0JCgnLm5hdmJhci1jb2xsYXBzZS5jb2xsYXBzZScpLmNvbGxhcHNlKCdoaWRlJyk7XHJcblx0dmFyIG9mZnNldCA9IGVsZW1lbnQub2Zmc2V0KCkudG9wO1xyXG5cdCQoJ2h0bWwsIGJvZHknKS5hbmltYXRlKHtcclxuXHRcdHNjcm9sbFRvcDogb2Zmc2V0IC0gJCgnLm5hdmJhcicpLmhlaWdodCgpIC0gNTBcclxuXHR9LCBhbmltYXRpb25UaW1lKTtcclxufVxyXG5cclxuZnVuY3Rpb24gc2hvd0FwcG9pbnRtZW50UGlja2VyKGFuaW1hdGlvblRpbWUgPSAzMDApIHtcclxuXHQkKFwiI3N0dWRlbnRTY2hvbGFyQXBwb2ludG1lbnRQaWNrZXJcIikuaGlkZSgpO1xyXG5cdCQoXCIjYXBwb2ludG1lbnRQaWNrZXJcIikuc2hvdygpO1xyXG5cdHNjcm9sbFRvRWxlbWVudCgkKFwiI2FwcG9pbnRtZW50UGlja2VyXCIpLCBhbmltYXRpb25UaW1lKTtcclxufVxyXG5cclxuZnVuY3Rpb24gc2hvd1N0dWRlbnRTY2hvbGFyQXBwb2ludG1lbnRQaWNrZXIoYW5pbWF0aW9uVGltZSA9IDMwMCkge1xyXG5cdCQoXCIjYXBwb2ludG1lbnRQaWNrZXJcIikuaGlkZSgpO1xyXG5cdCQoXCIjc3R1ZGVudFNjaG9sYXJBcHBvaW50bWVudFBpY2tlclwiKS5zaG93KCk7XHJcblx0c2Nyb2xsVG9FbGVtZW50KCQoXCIjc3R1ZGVudFNjaG9sYXJBcHBvaW50bWVudFBpY2tlclwiKSwgYW5pbWF0aW9uVGltZSk7XHJcbn1cclxuXHJcbmZ1bmN0aW9uIGNvbmRpdGlvbmFsRm9ybUZpZWxkcygpIHtcclxuXHRsZXQgYW5pbWF0aW9uVGltZSA9IDMwMDtcclxuXHJcblx0Ly8gQWxsIG9mIHRoZSBxdWVzdGlvbnMgdGhhdCByZXF1aXJlZCBjb25kaXRpb25zIHRvIGJlIHZpZXdlZC5cclxuXHR2YXIgaG9tZUJhc2VkID0gJChcIiNob21lQmFzZWRcIik7XHJcblx0dmFyIGhvbWVCYXNlZE5ldExvc3MgPSAkKFwiI2hvbWVCYXNlZE5ldExvc3NcIik7XHJcblx0dmFyIGhvbWVCYXNlZDEwMDAwID0gJChcIiNob21lQmFzZWQxMDAwMFwiKTtcclxuXHR2YXIgaG9tZUJhc2VkU0VQID0gJChcIiNob21lQmFzZWRTRVBcIik7XHJcblx0dmFyIGhvbWVCYXNlZEVtcGxveWVlcyA9ICQoXCIjaG9tZUJhc2VkRW1wbG95ZWVzXCIpO1xyXG5cdHZhciBzdHVkZW50VU5MID0gJChcIiNzdHVkZW50VU5MXCIpO1xyXG5cdHZhciBzdHVkZW50SW50ID0gJChcIiNzdHVkZW50SW50XCIpO1xyXG5cdHZhciBzdHVkZW50SW50VmlzYSA9ICQoXCIjc3R1ZGVudEludFZpc2FcIik7XHJcblx0dmFyIHN0dWRlbnRmMSA9ICQoXCIjc3R1ZGVudGYxXCIpO1xyXG5cdHZhciBzdHVkZW50ajEgPSAkKFwiI3N0dWRlbnRqMVwiKTtcclxuXHR2YXIgc3R1ZGVudGgxYiA9ICQoXCIjc3R1ZGVudGgxYlwiKTtcclxuXHR2YXIgYXBwb2ludG1lbnRQaWNrZXIgPSAkKFwiI2FwcG9pbnRtZW50UGlja2VyXCIpO1xyXG5cdHZhciBzdHVkZW50U2Nob2xhckFwcG9pbnRtZW50UGlja2VyID0gJChcIiNzdHVkZW50U2Nob2xhckFwcG9pbnRtZW50UGlja2VyXCIpO1xyXG5cclxuXHQvLyBBbGwgdGhlIHJhZGlvIGJ1dHRvbnNcclxuXHR2YXIgaG9tZUJhc2VkVmFsdWVzID0gaG9tZUJhc2VkLmZpbmQoJ2lucHV0OnJhZGlvW25hbWU9XCIzXCJdJyk7XHJcblx0dmFyIHN0dWRlbnRVTkxWYWx1ZXMgPSBzdHVkZW50VU5MLmZpbmQoJ2lucHV0OnJhZGlvW25hbWU9XCIxNVwiXScpO1xyXG5cdHZhciBzdHVkZW50SW50VmFsdWVzID0gc3R1ZGVudEludC5maW5kKCdpbnB1dDpyYWRpb1tuYW1lPVwiMTZcIl0nKTtcclxuXHR2YXIgc3R1ZGVudEludFZpc2FWYWx1ZXMgPSBzdHVkZW50SW50VmlzYS5maW5kKCdpbnB1dDpyYWRpb1tuYW1lPVwiMTdcIl0nKTtcclxuXHR2YXIgc3R1ZGVudGYxVmFsdWVzID0gc3R1ZGVudGYxLmZpbmQoJ2lucHV0OnJhZGlvW25hbWU9XCIxOFwiXScpO1xyXG5cdHZhciBzdHVkZW50ajFWYWx1ZXMgPSBzdHVkZW50ajEuZmluZCgnaW5wdXQ6cmFkaW9bbmFtZT1cIjE4XCJdJyk7XHJcblx0dmFyIHN0dWRlbnRoMWJWYWx1ZXMgPSBzdHVkZW50aDFiLmZpbmQoJ2lucHV0OnJhZGlvW25hbWU9XCIxOVwiXScpO1xyXG5cclxuXHQvLyBUbyBoZWxwIGhpZGUgZXZlcnl0aGluZyBhbmQgc2VsZWN0aXZlbHkgc2hvdyBjb250ZW50XHJcblx0dmFyIGFsbFVuZGVySG9tZUJhc2VkVmFsdWVzID0gaG9tZUJhc2VkTmV0TG9zcy5hZGQoaG9tZUJhc2VkMTAwMDApLmFkZChob21lQmFzZWRTRVApLmFkZChob21lQmFzZWRFbXBsb3llZXMpO1xyXG5cdHZhciBhbGxVbmRlcnN0dWRlbnRJbnRWaXNhVmFsdWVzID0gc3R1ZGVudGYxLmFkZChzdHVkZW50ajEpLmFkZChzdHVkZW50aDFiKTtcclxuXHR2YXIgYWxsVW5kZXJTdHVkZW50SW50VmFsdWVzID0gc3R1ZGVudEludFZpc2EuYWRkKGFsbFVuZGVyc3R1ZGVudEludFZpc2FWYWx1ZXMpXHJcblx0dmFyIGFsbFVuZGVyU3R1ZGVudFVOTFZhbHVlcyA9IHN0dWRlbnRJbnQuYWRkKGFsbFVuZGVyU3R1ZGVudEludFZhbHVlcykuYWRkKGFsbFVuZGVyc3R1ZGVudEludFZpc2FWYWx1ZXMpO1xyXG5cclxuXHQvLyBJbmRlcGVuZGVudCBmaWVsZCA9ICNob21lQmFzZWRcclxuXHQvLyBEZXBlbmRlbnQgZmllbGQgPSBpZiB5ZXMgLS0+ICNob21lQmFzZWROZXRMb3NzLCNob21lQmFzZWQxMDAwMCwjaG9tZUJhc2VkU0VQLCNob21lQmFzZWRFbXBsb3llZXNcclxuXHRob21lQmFzZWRWYWx1ZXMuY2hhbmdlKGZ1bmN0aW9uKCkge1xyXG5cdFx0dmFyIHZhbHVlID0gdGhpcy52YWx1ZTtcclxuXHRcdGlmKHRoaXMuY2hlY2tlZCl7XHJcblx0XHRcdGlmKHZhbHVlID09PSBcIjFcIil7XHJcblx0XHRcdFx0YWxsVW5kZXJIb21lQmFzZWRWYWx1ZXMuc2xpZGVVcChhbmltYXRpb25UaW1lKTtcclxuXHRcdFx0XHRhbGxVbmRlckhvbWVCYXNlZFZhbHVlcy5zbGlkZURvd24oYW5pbWF0aW9uVGltZSk7XHJcblx0XHRcdFx0c2Nyb2xsRG93bihob21lQmFzZWQuaGVpZ2h0KCksIGFuaW1hdGlvblRpbWUpO1xyXG5cdFx0XHR9IGVsc2UgaWYodmFsdWUgPT09IFwiMlwiKXtcclxuXHRcdFx0XHRhbGxVbmRlckhvbWVCYXNlZFZhbHVlcy5zbGlkZVVwKGFuaW1hdGlvblRpbWUpO1xyXG5cdFx0XHR9XHJcblx0XHR9XHJcblx0fSk7XHJcblxyXG5cdC8vIEluZGVwZW5kZW50IGZpZWxkID0gI3N0dWRlbnRVTkxcclxuXHQvLyBEZXBlbmRlbnQgZmllbGQgPSBpZiB5ZXMgLS0+ICNzdHVkZW50SW50XHJcblx0Ly8gICAgICAgICAgICAgICAgICAgaWYgbm8gLS0+IGFwcG9pbnRtZW50UGlja2VyXHJcblx0c3R1ZGVudFVOTFZhbHVlcy5jaGFuZ2UoZnVuY3Rpb24oKXtcclxuXHRcdHZhciB2YWx1ZSA9IHRoaXMudmFsdWU7XHJcblx0XHRhbGxVbmRlclN0dWRlbnRVTkxWYWx1ZXMuc2xpZGVVcChhbmltYXRpb25UaW1lKTtcclxuXHRcdGlmKHZhbHVlID09PSBcIjFcIil7XHJcblx0XHRcdHN0dWRlbnRJbnQuc2xpZGVEb3duKGFuaW1hdGlvblRpbWUpO1xyXG5cdFx0XHRzdHVkZW50SW50VmFsdWVzLmNoYW5nZSgpO1xyXG5cdFx0XHRzY3JvbGxEb3duKHN0dWRlbnRVTkwuaGVpZ2h0KCksIGFuaW1hdGlvblRpbWUpO1xyXG5cdFx0fSBlbHNlIGlmKHZhbHVlID09PSBcIjJcIil7XHJcblx0XHRcdHNob3dBcHBvaW50bWVudFBpY2tlcigpO1xyXG5cdFx0fVxyXG5cdH0pO1xyXG5cclxuXHQvLyBJbmRlcGVuZGVudCBmaWVsZCA9ICNzdHVkZW50SW50XHJcblx0Ly8gRGVwZW5kZW50IGZpZWxkID0gaWYgeWVzIC0tPiAjc3R1ZGVudEludFZpc2FcclxuXHQvLyAgICAgICAgICAgICAgICAgICBpZiBubyAtLT4gYXBwb2ludG1lbnRQaWNrZXJcclxuXHRzdHVkZW50SW50VmFsdWVzLmNoYW5nZShmdW5jdGlvbigpIHtcclxuXHRcdHZhciB2YWx1ZSA9IHRoaXMudmFsdWU7XHJcblx0XHRpZih0aGlzLmNoZWNrZWQpe1xyXG5cdFx0XHRhbGxVbmRlclN0dWRlbnRJbnRWYWx1ZXMuc2xpZGVVcChhbmltYXRpb25UaW1lKTtcclxuXHRcdFx0aWYodmFsdWUgPT09IFwiMVwiKXtcclxuXHRcdFx0XHRzdHVkZW50SW50VmlzYS5zbGlkZURvd24oYW5pbWF0aW9uVGltZSk7XHJcblx0XHRcdFx0c2Nyb2xsRG93bihzdHVkZW50SW50LmhlaWdodCgpLCBhbmltYXRpb25UaW1lKTtcclxuXHRcdFx0XHRzdHVkZW50SW50VmlzYVZhbHVlcy5jaGFuZ2UoKTtcclxuXHRcdFx0fSBlbHNlIGlmKHZhbHVlID09PSBcIjJcIil7XHJcblx0XHRcdFx0c2hvd0FwcG9pbnRtZW50UGlja2VyKCk7XHJcblx0XHRcdH1cclxuXHRcdH1cclxuXHR9KTtcclxuXHJcblx0Ly8gSW5kZXBlbmRlbnQgZmllbGQgPSAjc3R1ZGVudEludFZpc2FcclxuXHQvLyBEZXBlbmRlbnQgZmllbGQgPSBpZiBmMSAtLT4gI3N0dWRlbnRmMVxyXG5cdC8vICAgICAgICAgICAgICAgICAgIGlmIGoxIC0tPiAjc3R1ZGVudGoxXHJcblx0Ly8gICAgICAgICAgICAgICAgICAgaWYgaDFiIC0tPiAjc3R1ZGVudGgxYlxyXG5cdHN0dWRlbnRJbnRWaXNhVmFsdWVzLmNoYW5nZShmdW5jdGlvbigpIHtcclxuXHRcdHZhciB2YWx1ZSA9IHRoaXMudmFsdWU7XHJcblx0XHRpZih0aGlzLmNoZWNrZWQpe1xyXG5cdFx0XHRpZih2YWx1ZSA9PT0gXCI0XCIpe1xyXG5cdFx0XHRcdGFsbFVuZGVyc3R1ZGVudEludFZpc2FWYWx1ZXMuaGlkZShhbmltYXRpb25UaW1lKTtcclxuXHRcdFx0XHRzdHVkZW50ZjEuc2hvdyhhbmltYXRpb25UaW1lKTtcclxuXHRcdFx0XHRzdHVkZW50ZjFWYWx1ZXMuY2hhbmdlKCk7XHJcblx0XHRcdH0gZWxzZSBpZih2YWx1ZSA9PT0gXCI1XCIpe1xyXG5cdFx0XHRcdGFsbFVuZGVyc3R1ZGVudEludFZpc2FWYWx1ZXMuaGlkZShhbmltYXRpb25UaW1lKTtcclxuXHRcdFx0XHRzdHVkZW50ajEuc2hvdyhhbmltYXRpb25UaW1lKTtcclxuXHRcdFx0XHRzdHVkZW50ajFWYWx1ZXMuY2hhbmdlKCk7XHJcblx0XHRcdH0gZWxzZSBpZih2YWx1ZSA9PT0gXCI2XCIpe1xyXG5cdFx0XHRcdGFsbFVuZGVyc3R1ZGVudEludFZpc2FWYWx1ZXMuaGlkZShhbmltYXRpb25UaW1lKTtcclxuXHRcdFx0XHRzdHVkZW50aDFiLnNob3coYW5pbWF0aW9uVGltZSk7XHJcblx0XHRcdFx0c3R1ZGVudGgxYlZhbHVlcy5jaGFuZ2UoKTtcclxuXHRcdFx0fVxyXG5cdFx0fVxyXG5cdH0pO1xyXG5cclxuXHQvLyBJbmRlcGVuZGVudCBmaWVsZCA9ICNzdHVkZW50ZjFcclxuXHQvLyBEZXBlbmRlbnQgZmllbGQgPSBpZiAyMDExIG9yIGVhcmxpZXIgLS0+IHN0dWRlbnRTY2hvbGFyQXBwb2ludG1lbnRQaWNrZXJcclxuXHQvLyAgICAgICAgICAgICAgICAgICBpZiAyMDEyIG9yIGxhdGVyIC0tPiBhcHBvaW50bWVudFBpY2tlclxyXG5cdHN0dWRlbnRmMVZhbHVlcy5jaGFuZ2UoZnVuY3Rpb24oKSB7XHJcblx0XHR2YXIgdmFsdWUgPSB0aGlzLnZhbHVlO1xyXG5cdFx0aWYodGhpcy5jaGVja2VkKXtcclxuXHRcdFx0aWYodmFsdWUgPT09IFwiOFwiKXtcclxuXHRcdFx0XHRzY3JvbGxEb3duKGFwcG9pbnRtZW50UGlja2VyLmhlaWdodCgpLCBhbmltYXRpb25UaW1lKTtcclxuXHRcdFx0XHRzaG93U3R1ZGVudFNjaG9sYXJBcHBvaW50bWVudFBpY2tlcihhbmltYXRpb25UaW1lKTtcclxuXHRcdFx0fSBlbHNlIHtcclxuXHRcdFx0XHRzY3JvbGxEb3duKHN0dWRlbnRTY2hvbGFyQXBwb2ludG1lbnRQaWNrZXIuaGVpZ2h0KCksIGFuaW1hdGlvblRpbWUpO1xyXG5cdFx0XHRcdHNob3dBcHBvaW50bWVudFBpY2tlcihhbmltYXRpb25UaW1lKTtcclxuXHRcdFx0fVxyXG5cdFx0fVxyXG5cdH0pO1xyXG5cclxuXHQvLyBJbmRlcGVuZGVudCBmaWVsZCA9ICNzdHVkZW50ajFcclxuXHQvLyBEZXBlbmRlbnQgZmllbGQgPSBhbnkgLS0+IGFwcG9pbnRtZW50UGlja2VyXHJcblx0c3R1ZGVudGoxVmFsdWVzLmNoYW5nZShmdW5jdGlvbigpIHtcclxuXHRcdHNob3dBcHBvaW50bWVudFBpY2tlcihhbmltYXRpb25UaW1lKTtcclxuXHR9KTtcclxuXHJcblx0Ly8gSW5kZXBlbmRlbnQgZmllbGQgPSAjc3R1ZGVudGgxYlxyXG5cdC8vIERlcGVuZGVudCBmaWVsZCA9IGFueSAtLT4gYXBwb2ludG1lbnRQaWNrZXJcclxuXHRzdHVkZW50aDFiVmFsdWVzLmNoYW5nZShmdW5jdGlvbigpIHtcclxuXHRcdHNob3dBcHBvaW50bWVudFBpY2tlcihhbmltYXRpb25UaW1lKTtcclxuXHR9KTtcclxuXHJcbn1cclxuXHJcbi8vIEZvcm0gc3VibWlzc2lvblxyXG4kKCcjdml0YVNpZ251cEZvcm0nKS5zdWJtaXQoZnVuY3Rpb24oZSkge1xyXG5cdC8vIFN0b3AgZGVmYXVsdCBmb3JtIHN1Ym1pdCBhY3Rpb25cclxuXHQvLyBlLnByZXZlbnREZWZhdWx0KCk7XHJcblxyXG5cdGlmICghJCh0aGlzKS52YWxpZCgpKSB7XHJcblx0XHRyZXR1cm4gZmFsc2U7XHJcblx0fVxyXG5cclxuXHR2YXIgcXVlc3Rpb25zID0gW107XHJcblx0JCgnLmZvcm0tcmFkaW8nKS5lYWNoKGZ1bmN0aW9uKCl7XHJcblx0XHR2YXIgY2hlY2tlZFJhZGlvQm94ID0gJCh0aGlzKS5maW5kKCdpbnB1dFt0eXBlPVwicmFkaW9cIl06Y2hlY2tlZCcpO1xyXG5cclxuXHRcdGlmKGNoZWNrZWRSYWRpb0JveC5sZW5ndGg+MCkge1xyXG5cdFx0XHRxdWVzdGlvbnMucHVzaCh7XHJcblx0XHRcdFx0aWQ6IGNoZWNrZWRSYWRpb0JveC5hdHRyKCduYW1lJyksXHJcblx0XHRcdFx0dmFsdWU6IGNoZWNrZWRSYWRpb0JveC52YWwoKVxyXG5cdFx0XHR9KTtcclxuXHRcdH1cclxuXHR9KTtcclxuXHJcblx0dmFyIGRhdGEgPSB7XHJcblx0XHRcImZpcnN0TmFtZVwiOmZpcnN0TmFtZS52YWx1ZSxcclxuXHRcdFwibGFzdE5hbWVcIjpsYXN0TmFtZS52YWx1ZSxcclxuXHRcdFwiZW1haWxcIjplbWFpbC52YWx1ZSxcclxuXHRcdFwicGhvbmVcIjpwaG9uZS52YWx1ZSxcclxuXHRcdFwicXVlc3Rpb25zXCI6IHF1ZXN0aW9ucyxcclxuXHJcblx0XHQvL1RPRE9cclxuXHRcdFwic2NoZWR1bGVkVGltZVwiOiAnMjAxNy0wNy0yNlQxNTozMDowMCcsXHJcblx0XHRcInNpdGVJZFwiOiAzXHJcblx0fTtcclxuXHJcblx0Ly8gQUpBWCBDb2RlIFRvIFN1Ym1pdCBGb3JtLlxyXG5cdCQuYWpheCh7XHJcblx0XHR1cmw6IFwiL3NlcnZlci9zdG9yZUFwcG9pbnRtZW50LnBocFwiLFxyXG5cdFx0dHlwZTogXCJwb3N0XCIsXHJcblx0XHRkYXRhVHlwZTogJ2pzb24nLFxyXG5cdFx0ZGF0YTogKGRhdGEpLFxyXG5cdFx0Y2FjaGU6IGZhbHNlLFxyXG5cdFx0Y29tcGxldGU6IGZ1bmN0aW9uKHJlc3BvbnNlKXtcclxuXHRcdFx0cmVzcG9uc2UgPSByZXNwb25zZS5yZXNwb25zZUpTT047XHJcblxyXG5cdFx0XHRpZih0eXBlb2YgcmVzcG9uc2UgIT09ICd1bmRlZmluZWQnICYmIHJlc3BvbnNlICYmIHJlc3BvbnNlLnN1Y2Nlc3Mpe1xyXG5cdFx0XHRcdCQodml0YVNpZ251cEZvcm0pLmhpZGUoKTtcclxuXHRcdFx0XHQkKHJlc3BvbnNlUGxhY2Vob2xkZXIpLnNob3coKTtcclxuXHRcdFx0XHRyZXNwb25zZVBsYWNlaG9sZGVyLmlubmVySFRNTCA9IHJlc3BvbnNlLm1lc3NhZ2U7XHJcblx0XHRcdH1lbHNle1xyXG5cdFx0XHRcdGFsZXJ0KCdUaGVyZSB3YXMgYW4gZXJyb3Igb24gdGhlIHNlcnZlciEgUGxlYXNlIHJlZnJlc2ggdGhlIHBhZ2UgaW4gYSBmZXcgbWludXRlcyBhbmQgdHJ5IGFnYWluLicpO1xyXG5cdFx0XHR9XHJcblx0XHR9XHJcblx0fSk7XHJcblxyXG5cdHJldHVybiBmYWxzZTtcclxufSk7XHJcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL3NpZ251cC9zaWdudXAuanMiXSwic291cmNlUm9vdCI6IiJ9