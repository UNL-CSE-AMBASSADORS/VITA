$(document).ready(function() {
	loadAllSites();
	loadAllShifts();
	updateSitesDatesAndTimes();

	conditionalFormFields();

	validateSignupForm();

	// Since non-required fields are "valid" when they are empty, we need an
	// alternate way to keep labels raised when there is content in their
	// associated input field
	$(".form-textfield input").blur(function() {
		var isBlank = $.trim($(this).val()).length > 0;
		$label = $(this).siblings(".form-label").toggleClass( "form-label__floating", isBlank );
	});

});

let datesAllowed = [];
var sitesWithShifts = [];
let minTime = '0';
let maxTime = '23';

class SiteWithShift {
	constructor(siteId, startTime, endTime) {
		this.siteId = siteId;
		const date = this.getDate(startTime);
		this.dates = [];
		this.dates.push(date);
		this.shiftTimes = [];
		this.shiftTimes.push({
			date: date,
			startTime: startTime,
			endTime: endTime
		});
	}

	getDate(dateTime) {
		let t = dateTime.split(/[- :]/);
		return new Date(t[0],t[1]-1,t[2]);
	}

	addShift(startTime, endTime) {
		const date = this.getDate(startTime);
		this.dates.push(date);
		this.shiftTimes.push({
			date: date,
			startTime: startTime,
			endTime: endTime
		});
	}
}

function loadAllSites() {
	var request = $.ajax({
		url: "/server/api/sites/getAll.php",
		type: "GET",
		dataType: "JSON",
		data: ({
			"siteId": true,
			"title": true
		}),
		cache: false
	})
	.done(function(data) {
		$siteSelect = $("#sitePicker select");
		$siteSelect.append($('<option disabled selected value style="display:none"> -- select an option -- </option>'));
		for(const site of data) {
			$siteSelect.append($('<option>', {
				value: site.siteId,
				text : site.title
			}));
		}
	});
}

// Load all of the shifts and store in a global variable
// TODO year
function loadAllShifts(year=(new Date()).getFullYear()) {
	var request = $.ajax({
		url: "/server/api/shifts/getAllShifts.php",
		type: "GET",
		dataType: "JSON",
		data: ({
			"year": year,
			"startTime": true,
			"endTime": true,
			"siteId": true
		}),
		cache: false
	})
	.done(function(data) {
		for(const shift of data) {
			let sitesArray = sitesWithShifts.map(function(s) {return s.siteId});
			if(sitesArray.includes(shift.siteId)) {
				let i = sitesArray.indexOf(shift.siteId);
				sitesWithShifts[i].addShift(shift.startTime, shift.endTime);
			} else {
				sitesWithShifts.push(new SiteWithShift(shift.siteId, shift.startTime, shift.endTime));
			}
		}
		$("#dateInput").datepicker({
			// Good example: https://stackoverflow.com/a/1962849/7577035
			// called for every date before it is displayed
			beforeShowDay: function(date) {
				if (datesAllowed.includes(date.toDateString())) {
					return [true, ''];
				} else {
					return [false, ''];
				}
			}
		});

		// Documentation: http://jonthornton.github.io/jquery-timepicker/
		$("#timeInput").timepicker({
			'step': 60,
			'forceRoundTime': true,
			'timeFormat': 'g:i A'
		});
	});
}

// This function will watch the site selector and the date selector for changes.
function updateSitesDatesAndTimes() {
	siteSelect = $("#sitePicker select");
	dateInput = $("#dateInput");
	// When the value of the site selector is updated...
	// 1. Display the date picker
	// 2. Update the options for the date selector to disable options that are not available at this site.
	siteSelect.change(function() {
		$("#datePicker").show();
		// Clear any previously selected date
		$("#dateInput").val('');
		var value = this.value;
		// Clear and repopulate the array of dates that are available at the selected site
		datesAllowed.length = 0;
		const i = sitesWithShifts.map(function(s) {return s.siteId}).indexOf(value);
		if(i >= 0) {
			datesAllowed = datesAllowed.concat(sitesWithShifts[i].dates.map(function(d) {return d.toDateString()}));
		}
		// If a site is selected that has no available dates, show this to the user.
		if(datesAllowed.length === 0) {
			$("#dateInput").val('No dates available');
			$("#dateInput").prop("disabled", true);
			$("#timePicker").hide();
		} else {
			$("#dateInput").prop("disabled", false);
		}
	});

	// When the date selector changes...
	// 1. Display the time picker
	// 2. Update the available times selector.
	dateInput.change(function() {
		$("#timePicker").show();
		// Clear any previously selected time
		$("#timeInput").val('');
		var siteId = siteSelect.val();
		var date = this.value;

		let currentSite = sitesWithShifts.find(function(site) {return site.siteId === siteId});
		if(currentSite !== undefined && currentSite.shiftTimes.length > 0) {
			let availableTimes = [];
			currentSite.shiftTimes.forEach(function(shiftTime) {
				if($.datepicker.formatDate("mm/dd/yy",shiftTime.date) === date) {
					availableTimes.push({'start':shiftTime.startTime, 'end':shiftTime.endTime})
				}
			});

			if(availableTimes.length === 1) {
				start = new Date(availableTimes[0].start);
				end = new Date(availableTimes[0].end);
				minTime = start.getHours() + ":" + start.getMinutes();
				maxTime = end.getHours() + ":" + end.getMinutes();
				$("#timeInput").timepicker('option', {'minTime': minTime, 'maxTime': maxTime});
			} else if(availableTimes.length > 1) {
				// TODO: better logic that allows for intervals (disableTimeRanges)
				availableTimes.sort(function(a, b) {
					return Date.parse(a.start) - Date.parse(b.start);
				});
				start = Date.parse(availableTimes[0].start);
				end = Date.parse(availableTimes[availableTimes.length-1].end);
				minTime = start.getHours() + ":" + start.getMinutes();
				maxTime = end.getHours() + ":" + end.getMinutes();
				$("#timeInput").timepicker('option', {'minTime': minTime, 'maxTime': maxTime});
			} else {
				// This shouldn't ever happen, but it is here as a safety net.
				$("#timeInput").val('No times available');
				$("#timeInput").prop("disabled", true);
			}
		} else {
			// This shouldn't ever happen, but it is here as a safety net.
			$("#timeInput").val('No times available');
			$("#timeInput").prop("disabled", true);
		}
	});
}

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
				required: true,
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

function scrollDown(height, animationTime = 0) {
	$('html, body').animate({
		scrollTop: '+=' + height
	}, animationTime);
}

function scrollToElement(element, animationTime = 0) {
	$('.navbar-collapse.collapse').collapse('hide');
	var offset = element.offset().top;
	$('html, body').animate({
		scrollTop: offset - $('.navbar').height() - 50
	}, animationTime);
}

function showAppointmentPicker(animationTime = 300) {
	$("#studentScholarAppointmentPicker").hide();
	$("#appointmentPicker").show();
	scrollToElement($("#appointmentPicker"), animationTime);
}

function showStudentScholarAppointmentPicker(animationTime = 300) {
	$("#appointmentPicker").hide();
	$("#studentScholarAppointmentPicker").show();
	scrollToElement($("#studentScholarAppointmentPicker"), animationTime);
}

function conditionalFormFields() {
	let animationTime = 300;

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
	var allUnderStudentIntValues = studentIntVisa.add(allUnderstudentIntVisaValues)
	var allUnderStudentUNLValues = studentInt.add(allUnderStudentIntValues).add(allUnderstudentIntVisaValues);

	// Independent field = #homeBased
	// Dependent field = if yes --> #homeBasedNetLoss,#homeBased10000,#homeBasedSEP,#homeBasedEmployees
	homeBasedValues.change(function() {
		var value = this.value;
		if(this.checked){
			if(value === "1"){
				allUnderHomeBasedValues.slideUp(animationTime);
				allUnderHomeBasedValues.slideDown(animationTime);
				scrollDown(homeBased.height(), animationTime);
			} else if(value === "2"){
				allUnderHomeBasedValues.slideUp(animationTime);
			}
		}
	});

	// Independent field = #studentUNL
	// Dependent field = if yes --> #studentInt
	//                   if no --> appointmentPicker
	studentUNLValues.change(function(){
		var value = this.value;
		allUnderStudentUNLValues.slideUp(animationTime);
		if(value === "1"){
			studentInt.slideDown(animationTime);
			studentIntValues.change();
			scrollDown(studentUNL.height(), animationTime);
		} else if(value === "2"){
			showAppointmentPicker();
		}
	});

	// Independent field = #studentInt
	// Dependent field = if yes --> #studentIntVisa
	//                   if no --> appointmentPicker
	studentIntValues.change(function() {
		var value = this.value;
		if(this.checked){
			allUnderStudentIntValues.slideUp(animationTime);
			if(value === "1"){
				studentIntVisa.slideDown(animationTime);
				scrollDown(studentInt.height(), animationTime);
				studentIntVisaValues.change();
			} else if(value === "2"){
				showAppointmentPicker();
			}
		}
	});

	// Independent field = #studentIntVisa
	// Dependent field = if f1 --> #studentf1
	//                   if j1 --> #studentj1
	//                   if h1b --> #studenth1b
	studentIntVisaValues.change(function() {
		var value = this.value;
		if(this.checked){
			if(value === "4"){
				allUnderstudentIntVisaValues.hide(animationTime);
				studentf1.show(animationTime);
				studentf1Values.change();
			} else if(value === "5"){
				allUnderstudentIntVisaValues.hide(animationTime);
				studentj1.show(animationTime);
				studentj1Values.change();
			} else if(value === "6"){
				allUnderstudentIntVisaValues.hide(animationTime);
				studenth1b.show(animationTime);
				studenth1bValues.change();
			}
		}
	});

	// Independent field = #studentf1
	// Dependent field = if 2011 or earlier --> studentScholarAppointmentPicker
	//                   if 2012 or later --> appointmentPicker
	studentf1Values.change(function() {
		var value = this.value;
		if(this.checked){
			if(value === "8"){
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
	studentj1Values.change(function() {
		showAppointmentPicker(animationTime);
	});

	// Independent field = #studenth1b
	// Dependent field = any --> appointmentPicker
	studenth1bValues.change(function() {
		showAppointmentPicker(animationTime);
	});

}

// Form submission
$('#vitaSignupForm').submit(function(e) {
	// Stop default form submit action
	// e.preventDefault();

	if (!$(this).valid()) {
		return false;
	}

	var questions = [];
	$('.form-radio').each(function(){
		var checkedRadioBox = $(this).find('input[type="radio"]:checked');

		if(checkedRadioBox.length>0) {
			questions.push({
				id: checkedRadioBox.attr('name'),
				value: checkedRadioBox.val()
			});
		}
	});

	var data = {
		"firstName":firstName.value,
		"lastName":lastName.value,
		"email":email.value,
		"phone":phone.value,
		"questions": questions,

		//TODO
		"scheduledTime": '2017-07-26T15:30:00',
		"siteId":sitePickerSelect.value
	};

	// AJAX Code To Submit Form.
	$.ajax({
		url: "/server/storeAppointment.php",
		type: "post",
		dataType: 'json',
		data: (data),
		cache: false,
		complete: function(response){
			response = response.responseJSON;

			if(typeof response !== 'undefined' && response && response.success){
				$(vitaSignupForm).hide();
				$(responsePlaceholder).show();
				responsePlaceholder.innerHTML = response.message;
			}else{
				alert('There was an error on the server! Please refresh the page in a few minutes and try again.');
			}
		}
	});

	return false;
});
