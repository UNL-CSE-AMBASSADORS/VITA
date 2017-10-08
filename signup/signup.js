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

function conditionalFormFields() {
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
	var studenth1bValues = studenth1b.find('input:radio[name="18"]');

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
				allUnderHomeBasedValues.slideUp(300);
				allUnderHomeBasedValues.slideDown(300);
			} else if(value === "2"){
				allUnderHomeBasedValues.slideUp(300);
			}
		}
	});

	// Independent field = #studentUNL
	// Dependent field = if yes --> #studentInt
	//                   if no --> appointmentPicker
	studentUNLValues.change(function(){
		var value = this.value;
		allUnderStudentUNLValues.slideUp(300);
		if(value === "1"){
			studentInt.slideDown(300);
			studentIntValues.change();
		} else if(value === "2"){
			// TODO
		}
	});

	// Independent field = #studentInt
	// Dependent field = if yes --> #studentIntVisa
	//                   if no --> appointmentPicker
	studentIntValues.change(function() {
		var value = this.value;
		if(this.checked){
			if(value === "1"){
				allUnderStudentIntValues.slideUp(300);
				studentIntVisa.slideDown(300);
				studentIntVisaValues.change();
			} else if(value === "2"){
				allUnderStudentIntValues.slideUp(300);
				// TODO
			}
		}
	});

	// Independent field = #studentIntVisa
	// Dependent field = if f1 --> #studentf1
	//                   if ja --> #studentja
	//                   if h1b --> #studenth1b
	studentIntVisaValues.change(function() {
		var value = this.value;
		if(this.checked){
			if(value === "4"){
				allUnderstudentIntVisaValues.slideUp(300);
				studentf1.slideDown(300);
				studentf1Values.change();
			} else if(value === "5"){
				allUnderstudentIntVisaValues.slideUp(300);
				studentj1.slideDown(300);
				studentj1Values.change();
			} else if(value === "6"){
				allUnderstudentIntVisaValues.slideUp(300);
				studenth1b.slideDown(300);
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
				appointmentPicker.hide();
				studentScholarAppointmentPicker.show();
			} else {
				studentScholarAppointmentPicker.hide();
				appointmentPicker.show();
			}
		}
	});

	// Independent field = #studentj1
	// Dependent field = any --> appointmentPicker
	studentj1Values.change(function() {
		studentScholarAppointmentPicker.hide();
		appointmentPicker.show();
	});

	// Independent field = #studenth1b
	// Dependent field = any --> appointmentPicker
	studenth1bValues.change(function() {
		studentScholarAppointmentPicker.hide();
		appointmentPicker.show();
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

		questions.push({
			id: checkedRadioBox.attr('name'),
			value: checkedRadioBox.val()
		});
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
