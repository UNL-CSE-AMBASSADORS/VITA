$(document).ready(function() {
	// Get the information about sites
	loadAllSites();
	// Get all of the shift information and store it Date->Site->Time
	loadAllShifts();
	// Listen for changes to Date and Site fields to conditionally hide/show other fields
	updateSitesDatesAndTimes();

	// Listen for changes to all of the other conditional form fields
	conditionalFormFields();

	// Add validation to the form
	validateSignupForm();

	// Since non-required fields are "valid" when they are empty, we need an
	// alternate way to keep labels raised when there is content in their
	// associated input field
	$(".form-textfield input").blur(function() {
		var isBlank = $.trim($(this).val()).length > 0;
		$label = $(this).siblings(".form-label").toggleClass( "form-label__floating", isBlank );
	});

});

/**
 * getTimeInSeconds - convert a Date to the time of day in seconds, optionally rounded down to the nearest hour or minute
 *
 * @param  {string} time        Date as ISO String
 * @param  {string} zone="GMT"  time zone
 * @param  {string} round="m"   "h" or "m" for rounding down to "hour" or "minute" respectively
 * @return {number}             number of seconds in the day
 */
function getTimeInSeconds(time, zone="GMT", round="m") {
	if (typeof time === "string") {
		if (typeof zone != "string") {
			zone = "GMT";
		}
		time = new Date(time + " " + zone);
		switch(round) {
			case "h":
				return time.getHours() * 3600;
			case "m":
				return time.getHours() * 3600 + time.getMinutes() * 60;
			default:
				return time.getHours() * 3600 + time.getMinutes() * 60 + time.getSeconds();
		}
	} else {
		return undefined;
	}
}

/**
 * getTimeString - convert time (number in seconds) to a formatted time string
 *   function based on a function from http://jonthornton.github.com/jquery-timepicker/
 *
 * @param  {number} timeInt              number of seconds into day
 * @param  {string} timeFormat = "g:i A" formatting based on PHP DateTime
 * @param  {boolean} show2400 = false    whether or not military time goes up through 2400 or if it flips back to 0000
 * @return {string}                      formatted time string
 */
function getTimeString(timeInt, timeFormat = "g:i A", show2400 = false) {
	if (typeof timeInt != "number") {
		return undefined;
	}

	var seconds = parseInt(timeInt % 60),
			minutes = parseInt((timeInt / 60) % 60),
			hours = parseInt((timeInt / (60 * 60)) % 24);

	var time = new Date(1970, 0, 2, hours, minutes, seconds, 0);

	if (isNaN(time.getTime())) {
		return null;
	}

	if ($.type(timeFormat) === "function") {
		return timeFormat(time);
	}

	var output = "";
	var hour, code;
	for (var i = 0; i < timeFormat.length; i++) {
		code = timeFormat.charAt(i);
		switch (code) {
			case "a":
				output += time.getHours() > 11 ? _lang.pm : _lang.am;
				break;

			case "A":
				output += time.getHours() > 11 ? _lang.PM : _lang.AM;
				break;

			case "g":
				hour = time.getHours() % 12;
				output += hour === 0 ? "12" : hour;
				break;

			case "G":
				hour = time.getHours();
				if (timeInt === _ONE_DAY) hour = show2400 ? 24 : 0;
				output += hour;
				break;

			case "h":
				hour = time.getHours() % 12;

				if (hour !== 0 && hour < 10) {
					hour = "0" + hour;
				}

				output += hour === 0 ? "12" : hour;
				break;

			case "H":
				hour = time.getHours();
				if (timeInt === _ONE_DAY) hour = show2400 ? 24 : 0;
				output += hour > 9 ? hour : "0" + hour;
				break;

			case "i":
				var minutes = time.getMinutes();
				output += minutes > 9 ? minutes : "0" + minutes;
				break;

			case "s":
				seconds = time.getSeconds();
				output += seconds > 9 ? seconds : "0" + seconds;
				break;

			case "\\":
				// escape character; add the next character and skip ahead
				i++;
				output += timeFormat.charAt(i);
				break;

			default:
				output += code;
		}
	}

	return output;
}

/** Class representing a mapping from a site to an array of times. */
class SiteTimeMap {
	constructor(siteId) {
		this.siteId = siteId;
		this.times = []; // array of times in 24 hour floating point format
	}

	addTime(times) {
		this.times.push(times);
	}

	hasTime(time) {
		return this.times.includes(time);
	}

	getTimesArray() {
		console.log(this.times);
		return this.times.sort().map(t => getTimeString(t));
	}
}

/** Class representing a mapping from a date to an array of sites. */
class DateSiteMap {
	constructor(date) {
		this.date = date;
		this.sites = []; // array of SiteTimeMap objects
	}

	addSite(siteId) {
		this.sites.push(new SiteTimeMap(siteId));
	}

	hasSite(siteId) {
		return this.sites.map(s => s.siteId).includes(siteId);
	}

	getSite(siteId) {
		return this.sites.find(s => s.siteId === siteId);
	}

	getSitesArray() {
		return this.sites.map(s => ({siteId:s.siteId, title:dateSitesTimes.siteTitles.get(s.siteId)}));
	}
}

/** Class representing a list of dates with mappings to sites and times at those sites. */
class DateSiteTime {
	constructor() {
		this.dates = []; // array of DateSiteMap objects
		this.siteTitles = new Map();
	}

	/**
	 * addShift - Adds a new shift to the object,
	 *   automatically sorting by date and site,
	 *   creating new ones if necessary.
	 *
	 * @param  {string} siteId    site ID
	 * @param  {string} startTime startTime formatted as ISO String
	 * @param  {string} endTime   endTime formatted as ISO String
	 */
	addShift(siteId, startTime, endTime) {
		const date = new Date(startTime);
		if(!this.hasDate(date)) {
			this._addDate(date);
		}

		let dateObj = this.getDate(date);
		if(!dateObj.hasSite(siteId)) {
			dateObj.addSite(siteId);
		}

		let newTimes = this._getAppointmentTimes(startTime, endTime);

		let siteObj = dateObj.getSite(siteId);
		for (const time of newTimes) {
			if(!siteObj.hasTime(time)) {
				siteObj.addTime(time);
			}
		}
	}

	_addDate(date) {
		this.dates.push(new DateSiteMap(date));
	}

	hasDate(date) {
		return this.getDatesArray().includes(date.toDateString());
	}

	getDate(date) {
		return this.dates.find(d => d.date.toDateString() === date.toDateString());
	}

	getDatesArray() {
		return this.dates.map(d => d.date.toDateString());
	}

	/**
	 * _getAppointmentTimes - Gets the time of the day as a number of seconds
	 *
	 * @param  {string} startTime     startTime for appointment
	 * @param  {string} endTime       endTime for appointment
	 * @param  {number} interval=1800 seconds between appointments
	 * @return {object}               array of appointment times in seconds
	 */
	_getAppointmentTimes(startTime, endTime, interval=1800) {
		if (typeof interval != "number") {
			interval = 1800;
		}
		if (startTime > endTime) {
			return [];
		}
		const startTimeInSeconds = getTimeInSeconds(startTime),
					endTimeInSeconds = getTimeInSeconds(endTime);

		let out = [],
				ct = startTimeInSeconds;

		out.push(startTimeInSeconds);

		while (ct < endTimeInSeconds - interval) {
			ct += interval;
			out.push(ct);
		}

		return out;
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
		for(const site of data) {
			dateSitesTimes.siteTitles.set(site.siteId, site.title);
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
			dateSitesTimes.addShift(shift.siteId, shift.startTime, shift.endTime);
		}
		$("#dateInput").datepicker({
			// Good example: https://stackoverflow.com/a/1962849/7577035
			// called for every date before it is displayed
			beforeShowDay: function(date) {
				if (dateSitesTimes.hasDate(date)) {
					return [true, ''];
				} else {
					return [false, ''];
				}
			},
			beforeShow: function() {
				setTimeout(function(){
					$('.ui-datepicker').css('z-index', 100);
				}, 0);
			}
		});
	});
}

// This function will watch the date selector and the site selector for changes.
function updateSitesDatesAndTimes() {
	dateInput = $("#dateInput");
	siteSelect = $("#sitePicker select");

	// When the date selector changes...
	// 1. Display the site picker
	// 2. Update the site picker with the sites related to the selected date.
	dateInput.change(function() {
		$("#sitePicker").show();
		$("#timePicker").hide();
		// Clear any previously selected input
		$("#timePickerSelect").html('');
		$("#sitePickerSelect").html('');
		var date = this.value;

		let dateObj = dateSitesTimes.getDate(new Date(date));
		siteSelect.append($('<option disabled selected value style="display:none"> -- select an option -- </option>'));
		for(const site of dateObj.getSitesArray()) {
			siteSelect.append($('<option>', {
				value: site.siteId,
				text : site.title
			}));
		}
	});

	// When the value of the site selector is updated...
	// 1. Display the date picker
	// 2. Update the options for the date selector to disable options that are not available at this site.
	siteSelect.change(function() {
		$("#timePicker").show();
		// Clear any previously selected date
		$("#timePickerSelect").html('');
		var value = this.value;
		var date = $("#dateInput").val();

		let siteObj = dateSitesTimes.getDate(new Date(date)).getSite(value);
		var timeSelect = $("#timePicker select")
		timeSelect.append($('<option disabled selected value style="display:none"> -- select an option -- </option>'));
		for(const time of siteObj.getTimesArray()) {
			timeSelect.append($('<option>', {
				value: time,
				text : time
			}));
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
	// $("#appointmentPicker").hide();
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
	//									 if no --> appointmentPicker
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
	//									 if no --> appointmentPicker
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
	//									 if j1 --> #studentj1
	//									 if h1b --> #studenth1b
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
	//									 if 2012 or later --> appointmentPicker
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

	var scheduledTime = new Date($("#dateInput").val() + " " + $("#timeInput").val()).toISOString();

	var data = {
		"firstName":firstName.value,
		"lastName":lastName.value,
		"email":email.value,
		"phone":phone.value,
		"questions": questions,
		"scheduledTime":scheduledTime,
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

let datesAllowed = [],
		sitesWithShifts = [],
		minTime = '0',
		maxTime = '23',
		dateSitesTimes = new DateSiteTime(),
		_lang = {
			am: "am",
			pm: "pm",
			AM: "AM",
			PM: "PM",
			decimal: ".",
			mins: "mins",
			hr: "hr",
			hrs: "hrs"
		},
		_ONE_DAY = 86400;
