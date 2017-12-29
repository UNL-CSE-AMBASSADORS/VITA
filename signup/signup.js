$(document).ready(function() {
	// Listen for changes to all of the other conditional form fields
	conditionalFormFields();

	// Add validation to the form
	validateSignupForm();

	// Since non-required fields are "valid" when they are empty, we need an
	// alternate way to keep labels raised when there is content in their
	// associated input field
	$(".form-textfield input").blur(function() {
		var isBlank = $.trim($(this).val()).length > 0;
		$(this).siblings(".form-label").toggleClass( "form-label__floating", isBlank );
	});
});

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
		}
	});
}

$("#addDependentButton").click(function(e) {
	var currentLastName = $('#lastName').val();
	var id = $('.firstName').length;

	var dependentRow = $('<div></div>').addClass("dependent-div row");
	var firstNameBlock = $('<div></div>').addClass("col-5 form-textfield");
	var firstNameInput = $(`<input type="text" name="firstNameInput${id}" id="firstNameInput${id}" required/>`).addClass("firstName");
	var firstNameSpan = $('<span></span>').addClass("form-bar");
	var firstNameLabel = $(`<label for="firstNameInput${id}">First Name</label>`).addClass("form-label form-required");
	firstNameBlock.append(firstNameInput, firstNameSpan, firstNameLabel);

	var lastNameBlock = $('<div></div>').addClass("col-5 form-textfield");
	var lastNameInput = $(`<input type="text" name="lastNameInput${id}" id="lastNameInput${id}" value="${currentLastName}" required/>`).addClass("lastName");
	var lastNameSpan = $('<span></span>').addClass("form-bar");
	var lastNameLabel = $(`<label for="lastNameInput${id}">Last Name</label>`).addClass("form-label form-required");
	lastNameBlock.append(lastNameInput, lastNameSpan, lastNameLabel);

	// Since the last name is inherited from the top-most last name input, we need to raise the label if there was a last name
	if (currentLastName.trim().length > 0) lastNameLabel.addClass("form-label__floating");

	var removeBlock = $('<button type="button"></button>').addClass("btn btn-danger col-2").html("Remove").click(function(){
		$(this).parent().remove();
	});

	dependentRow.append(firstNameBlock, lastNameBlock, removeBlock);
	$("#dependents").append(dependentRow);

	// Since non-required fields are "valid" when they are empty, we need an
	// alternate way to keep labels raised when there is content in their
	// associated input field
	firstNameInput.blur(function() {
		var isBlank = $.trim($(this).val()).length > 0;
		var label = $(this).siblings(".form-label").toggleClass( "form-label__floating", isBlank );
	});
	lastNameInput.blur(function() {
		var isBlank = $.trim($(this).val()).length > 0;
		var label = $(this).siblings(".form-label").toggleClass( "form-label__floating", isBlank );
	});
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
	$(".dependent-div").each(function() { 
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
