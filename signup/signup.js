$(document).ready(function() {
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
		"siteId": 3
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
