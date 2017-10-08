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
	// Independent fields = #student
	var student = $("#student").find('input:radio[name="student"]');
	// Dependent fields = #studentId
	var studentId = $("#studentId");
	var all = studentId;
	// Hide everything and selectively show content
	student.change(function(){
		var value = this.value;
		all.hide();
		if(value === "yes"){
			studentId.show();
		}
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
		"siteId": 4
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
