
$(document).ready(function() {
  validateProfilePage();
  getDataLoad();
  $(".form-textfield input").blur(function() {
    var isBlank = $.trim($(this).val()).length > 0;
    $label = $(this).siblings(".form-label").toggleClass("form-label__floating",isBlank);

  });
});

function validateProfilePage() {
  $("#vitaProfileEdit").validate({
    rules: {
      "firstNameProfile":"required",
      "lastNameProfile":"required",
      email: {
        required:true,
        email: true
      },
      phone: {
        required:true
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

function editSubmit() {

var dataOut = {
	"firstName":firstNameProfile.value,
	"lastName":lastNameProfile.value,
	"phoneNumber":phoneProfile.value,
	"email":emailProfile.value,
  "abilityId":languageSkills.value
};

$.ajax({
	url: "/server/profileStore.php",
	type: "POST",
	dataType: 'JSON',
	data: dataOut,
	cache: false,
	complete: function(response){
		response = response.responseJSON;

		if(typeof response !== 'undefined' && response && response.success){
			$(vitaProfileEdit).hide();
			$(responsePlaceholder).show();
			responsePlaceholder.innerHTML = response.message;
		}else{
			alert('There was an error on the server! Please refresh the page in a few minutes and try again.');
		}
    location.href = "/profile/index.php";
	}

});

}


function getDataLoad() {
//AJAX for intial prefilled form
$.ajax({
	url: "/server/profileGet.php",
	type: "GET",
	dataType: "JSON",
	cache: false,
	complete: function(response){
    console.log(response);
		response = response.responseJSON;
    $("#nameProfile").html(response[0].firstName + + response[0].lastName);
    $("#firstNameProfile").html(response[0].firstName);
    $("#lastNameProfile").html(reponse[0].lastName);
    $("#phoneProfile").html(reponse[0].phoneNumber);
    $("#emailProfileStatic").html(reponse[0].email);
    $("#taxSkills").html(response[0].preparesTaxes);
	}
});
}
