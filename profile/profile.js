$(document).ready(function() {
	validateProfilePage();
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
	$("#shiftSelectDiv").clone().appendTo("#shiftSelectFullDiv");
}


function editSubmit() {
	$.ajax({
		url: "/server/profileStore.php",
		type: "POST",
		dataType: 'JSON',
		data: `action=storeProfile&firstName=${profileUser.firstName}&lastName=${profileUser.lastName}&phoneNumber=${profileUser.phoneNumber}&email=${profileUser.email}$abilityId=${profileAbility.languageSkills}`,
		cache: false,
		complete: function(response) {
			response = response.responseJSON;
			console.log(response);
			if (typeof response !== 'undefined' && response && response.success) {
				$(vitaProfileEdit).hide();
				$(responsePlaceholder).show();
				responsePlaceholder.innerHTML = response.message;
			} else {
				alert('There was an error on the server! Please refresh the page in a few minutes and try again.');
				window.location.replace("/profile/index.php");
			}

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
			response = response.responseJSON;
			console.log(response);

			if (typeof response !== 'undefined' && response && response.success) {
				$("#nameProfile").html(response.userInformation[0].firstName + " " + response.userInformation[0].lastName);
				$("#firstNameProfile").val(response.userInformation[0].firstName);
				$("#lastNameProfile").val(response.userInformation[0].lastName);
				$("#phoneProfile").html(response.userInformation[0].phoneNumber);
				$("#phoneProfile").val(response.userInformation[0].phoneNumber);
				$("#emailProfile").html(response.userInformation[0].email);
				$("#emailProfile").val(response.userInformation[0].email);
				$("#taxSkills").html(response.userInformation[0].preparesTaxes ? 'true' : 'false');
				for (var i  = 0; i < response.userShiftsInformation.length; i++) {
					let shiftInformation = response.userShiftsInformation[i]
					$("#shiftRegistration").append("<li>" +`${shiftInformation.title} : ${shiftInformation.startTime} - ${shiftInformation.endTime}` + "</li>");
				}
				for (var i  = 0; i < response.length; i++) {
					$("#shiftTime").html('<option value='+response.shiftId[i]+'>'+response.shiftId[i]+'</option>');
				}
			}
		}
	});
}
