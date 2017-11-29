$(document).ready(function() {
	validateProfilePage();
	getDataLoad();
	$(".form-textfield input").blur(function() {
		var isBlank = $.trim($(this).val()).length > 0;
		$label = $(this).siblings(".form-label").toggleClass("form-label__floating", isBlank);
	});
});

function validateProfilePage() {
	$("#vitaProfileEdit").validate({
		rules: {
			"firstNameProfile": "required",
			"lastNameProfile": "required",
			email: {
				required: true,
				email: true
			},
			phone: {
				required: true
			}
		},
		messages: {
			email: {
				required: "We need your email to confirm your shift times",
				email: "Your email must be in the form of name@example.com"
			}
		}
	});
}

function allowShiftSelect() {
	$("#shiftTime").prop("disabled",false);
}

function addNewShift() {
	var newShiftEntry = $("#shiftSelectDiv");
	$("#selectShiftContainer").append(newShiftEntry);
}

function editSubmit() {
	var dataOut = {
		"firstName": firstNameProfile.value,
		"lastName": lastNameProfile.value,
		"phoneNumber": phoneProfile.value,
		"email": emailProfile.value,
		"abilityId": languageSkills.value
	};
	$.ajax({
		url: "/server/profileStore.php",
		type: "POST",
		dataType: 'JSON',
		data: dataOut,
		cache: false,
		complete: function(response) {
			response = response.responseJSON;
			if (typeof response !== 'undefined' && response && response.success) {
				$(vitaProfileEdit).hide();
				$(responsePlaceholder).show();
				responsePlaceholder.innerHTML = response.message;
			} else {
				alert('There was an error on the server! Please refresh the page in a few minutes and try again.');
			}
			window.location.replace("/profile/index.php");
		}
	});
}

window.onload = function getDataLoad() {
	//AJAX for intial prefilled form
	$.ajax({
		url: "/server/profileGet.php",
		type: "GET",
		dataType: "JSON",
		cache: false,
		complete: function(response) {
			if (typeof response !== 'undefined' && response && response.success) {
				response = response.responseJSON;
				$("#nameProfile").html(response.firstName + " " + response.lastName);
				$("#firstNameProfile").html(response.firstName);
				$("#lastNameProfile").html(reponse.lastName);
				$("#phoneProfile").html(reponse.phoneNumber);
				$("#emailProfileStatic").html(reponse.email);
				$("#taxSkills").html(response.preparesTaxes);
				for (var i  = 0; i < response.length; i++) {
					$("#shiftLocation").append('<option value='+response.siteId[i]+'>'+response.siteId[i]+'</option>');
				}
				for (var i  = 0; i < response.length; i++) {
					$("#shiftTime").append('<option value='+response.shiftId[i]+'>'+response.shiftId[i]+'</option>');
				}
			}
		}
	});
}
