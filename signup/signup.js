require.config({
	shim: {
		/* Bootstrap is dependent on jquery */
		'bootstrap/button': { deps: ['jquery'] },
	},
	paths: {
		'bootstrap/button': '/assets/js/bootstrap/button',
		jqueryvalidation: 'https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/jquery.validate.min'
	}
});

WDN.initializePlugin('jqueryui', [function () {
	require(['jquery', 'jqueryvalidation'], function ($) {
		window.jQuery = $;
		require(['bootstrap/button']);

		const NUM_SECONDS_IN_HOUR = 60*60; // 3600
		const NUM_SECONDS_IN_DAY = 24*60*60; // 86400

		$(document).ready(function() {
			// Get all of the appointment information stored Date->Site->Time
			loadAllAppointments();
			// Listen for changes to Date and Site fields to conditionally hide/show other fields
			updateSitesDatesAndTimes();

			// Listen for changes to all of the other conditional form fields
			conditionalFormFields();

			// Add validation to the form
			validateSignupForm();

		});

		/** Class representing a map of dates => sites => times => remaining spots. */
		class DateSiteTime {
			constructor() {
				this.dates = new Object(); // array of DateSiteMap objects
				this.siteTitles = new Map();
			}

			addMap(datesMap) {
				this.dates = datesMap;
			}

			hasDate(dateObj) {
				return dateObj.toISOString().substring(0, 10) in this.dates;
			}

			hasTimeSlotsRemaining(dateObj) {
				let date = dateObj.toISOString().substring(0, 10);
				return this.dates[date]["hasAvailability"];
			}

			updateGlobalSites(dateInput) {
				let dateObj = new Date(dateInput);
				let date = dateObj.toISOString().substring(0, 10);
				let localSites = this.dates[date]["sites"];
				sites = [];
				for (let site in localSites) {
					sites.push({
						"siteId": site,
						"title": localSites[site]["site_title"],
						"hasTimeSlotsRemaining": localSites[site]["hasAvailability"]
					});
				}
			}

			updateGlobalTimes(dateInput, site) {
				let dateObj = new Date(dateInput);
				let date  = dateObj.toISOString().substring(0, 10);
				times = this.dates[date]["sites"][site]["times"];
			}
		}

		// Load all of the appointments and store in a global variable
		// Default is to only load the current year
		function loadAllAppointments(year=(new Date()).getFullYear()) {
			var request = $.ajax({
				url: "/server/api/appointments/getTimes.php",
				type: "GET",
				dataType: "JSON",
				data: ({
					"year": year
				}),
				cache: false
			})
			.done(function(data) {
				dateSitesTimes.addMap(data.dates);
				$("#dateInput").datepicker({
					// Good example: https://stackoverflow.com/a/1962849/7577035
					// called for every date before it is displayed
					beforeShowDay: function(date) {
						if (dateSitesTimes.hasDate(date)) {
							if (dateSitesTimes.hasTimeSlotsRemaining(date)) {
								return [true, ''];
							} else {
								return [false, 'full'];
							}
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
			let dateInput = $("#dateInput");
			let siteSelect = $("#sitePicker select");

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

				dateSitesTimes.updateGlobalSites(date);
				siteSelect.append($('<option disabled selected value="" style="display:none"> -- Select a Site -- </option>'));
				for(let site in sites) {
					if (!sites[site]["hasTimeSlotsRemaining"]) continue;
					siteSelect.append($('<option>', {
						value: sites[site]["siteId"],
						text : sites[site]["title"]
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
				var site = this.value;
				var date = $("#dateInput").val();

				dateSitesTimes.updateGlobalTimes(date, site);
				var timeSelect = $("#timePicker select")
				timeSelect.append($('<option disabled selected value="" style="display:none"> -- Select a Time -- </option>'));
				for(const time in times) {
					timeSelect.append($('<option>', {
						value: time,
						text : time + (times[time] > 0 ? '' : ' - FULL'),
						disabled: times[time] > 0 ? false : true
					}));
				}
			});
		}

		function showAppointmentPicker() {
			$("#studentScholarAppointmentPicker").hide();
			$("#appointmentPicker").show();
		}

		function showStudentScholarAppointmentPicker() {
			// $("#appointmentPicker").hide();
			$("#studentScholarAppointmentPicker").show();
		}

		function conditionalFormFields() {
			let animationTime = 300;

			// All of the questions that required conditions to be viewed.
			var studentUNL = $("#studentUNL");
			var studentInt = $("#studentInt");
			var studentIntVisa = $("#studentIntVisa");
			var studentf1 = $("#studentf1");
			var studentj1 = $("#studentj1");
			var studenth1b = $("#studenth1b");
			var appointmentPicker = $("#appointmentPicker");
			var studentScholarAppointmentPicker = $("#studentScholarAppointmentPicker");

			// All the radio buttons
			var studentUNLValues = studentUNL.find('input:radio[name="1"]');
			var studentIntValues = studentInt.find('input:radio[name="2"]');
			var studentIntVisaValues = studentIntVisa.find('input:radio[name="3"]');
			var studentf1Values = studentf1.find('input:radio[name="4"]');
			var studentj1Values = studentj1.find('input:radio[name="4"]');
			var studenth1bValues = studenth1b.find('input:radio[name="5"]');

			// To help hide everything and selectively show content
			var allUnderstudentIntVisaValues = studentf1.add(studentj1).add(studenth1b);
			var allUnderStudentIntValues = studentIntVisa.add(allUnderstudentIntVisaValues)
			var allUnderStudentUNLValues = studentInt.add(allUnderStudentIntValues).add(allUnderstudentIntVisaValues);

			// Independent field = #studentUNL
			// Dependent field = if yes --> #studentInt
			//									 if no --> appointmentPicker
			studentUNLValues.change(function(){
				var value = this.value;
				allUnderStudentUNLValues.slideUp(animationTime);
				if(value === "1"){
					studentInt.slideDown(animationTime);
					studentIntValues.change();
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
						showStudentScholarAppointmentPicker();
					} else {
						showAppointmentPicker();
					}
				}
			});

			// Independent field = #studentj1
			// Dependent field = any --> appointmentPicker
			studentj1Values.change(function() {
				showAppointmentPicker();
			});

			// Independent field = #studenth1b
			// Dependent field = any --> appointmentPicker
			studenth1bValues.change(function() {
				showAppointmentPicker();
			});
		}

		function validateSignupForm() {
			$("#vitaSignupForm").validate({
				rules: {
					email: {
						email: true
					}
				},
				messages: {
					email: {
						email: "Your email address must be in the format of name@domain.com"
					}
				},
				errorPlacement: function(error, element) {
					error.insertAfter( element.closest(".error-placement") );
				}
			});
		}

		$("#addDependentButton").click(function(e) {
			var currentLastName = $('#lastName').val();
			var id = $('.firstName').length;

			var dependentRow = $('<li></li>').addClass("wdn-grid-set dependent");
			var firstNameBlock = $('<div></div>').addClass("bp768-wdn-col-two-fifths");
			var firstNameLabel = $(`<label for="firstNameInput${id}">First Name</label>`).addClass("form-label form-required");
			var firstNameInput = $(`<input type="text" name="firstNameInput${id}" id="firstNameInput${id}" required/>`).addClass("firstName error-placement");
			firstNameBlock.append(firstNameLabel, firstNameInput);

			var lastNameBlock = $('<div></div>').addClass("bp768-wdn-col-two-fifths");
			var lastNameLabel = $(`<label for="lastNameInput${id}">Last Name</label>`).addClass("form-label form-required");
			var lastNameInput = $(`<input type="text" name="lastNameInput${id}" id="lastNameInput${id}" value="${currentLastName}" required/>`).addClass("lastName error-placement");
			lastNameBlock.append(lastNameLabel, lastNameInput);

			var removeBlock = $('<div></div>').addClass("bp768-wdn-col-one-fifth px-1rem");
			var removeButton = $('<button type="button"></button>').addClass("wdn-button wdn-button-brand").html("Remove Dependent").click(function(){
				$(this).parent().parent().remove();
			});
			removeBlock.append(removeButton);

			dependentRow.append(firstNameBlock, lastNameBlock, removeBlock);
			$("#dependents").append(dependentRow);
		});

		// Form submission
		$('#vitaSignupForm').submit(function(e) {
			// Stop default form submit action
			e.preventDefault();

			// With jQuery UI styling on the radio buttons, a new listener is necessary to update error messages
			// However, this is only necessary after we have tried submitting once first.
			$("input:radio").change(function() {
				$("#vitaSignupForm").valid();
			});

			if (!$(this).valid() || !$("#sitePickerSelect").valid() || !$("#timePickerSelect").valid()) {
				return false;
			}

			var questions = [];
			$('.form-radio').each(function(){
				var checkedRadioBox = $(this).find('input[type="radio"]:checked');

				if(checkedRadioBox.length>0) {
					if (checkedRadioBox.attr('name') !== 'languageRadio') {
						questions.push({
							id: checkedRadioBox.attr('name'),
							value: checkedRadioBox.val()
						});
					}
				}
			});

			var dependents = [];
			$(".dependent").each(function() { 
				var dependentFirstName = $(this).find('.firstName').val().trim();
				var dependentLastName = $(this).find('.lastName').val().trim();

				if(dependentFirstName.length > 0 && dependentLastName.length > 0) { // empty strings
					dependents.push({
						firstName: dependentFirstName,
						lastName: dependentLastName
					});
				}
			});

			var scheduledTime = new Date($("#dateInput").val() + " " + $("#timePickerSelect").val() + " GMT").toISOString();
			var language = $('#language').find('input[type="radio"]:checked').val();

			var data = {
				"firstName":firstName.value,
				"lastName":lastName.value,
				"email":email.value,
				"phone":phone.value,
				"language":language,
				"questions":questions,
				"dependents":dependents,
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

		let dateSitesTimes = new DateSiteTime(),
			sites = [],
			times = [];

	});
}]);