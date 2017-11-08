
function taxFunction() {
	var taxValue = $("#taxSkills").val();
	if (taxValue === "Yes") {
		var myDiv = $("#skillType");
		var array = ["international", "1040", "Visa"];
		var selectList = $("<select></select>");
		var visaLabel = $("<label></label>");
		visaLabel.addClass("form-label form-required");
		visaLabel.attr("for", "taxSkillsType");
		visaLabel.attr("id", "taxSkillsLabel");
		var labelText = "Which type of taxes can you file?"
		visaLabel.append(labelText);
		myDiv.append(visaLabel);
		selectList.attr("id", "taxSkillsType");
		selectList.addClass("form-control");
		selectList.attr("name", "taxSkillsType");
		myDiv.append(selectList);
		for (var i = 0; i < array.length; i++) {
			var option = document.createElement("option");
			option.value = array[i];
			option.text = array[i];
			selectList.append(option);

			}
		}  else if (taxValue === "No") {
			$("#skillType").detach();
	}
}
var dataOut = {
	"firstNameProfile":firstNameProfile.value,
	"lastNameProfile":lastNameProfile.value,
	"phoneProfile":phoneProfile.value,
	"emailProfile":emailProfile.value,
	"languageSkills":languageSkills.value,
	"taxSkills" : taxSkills.value,
	"taxSkillsType": taxSkillsType.value,
	"shiftsWorking": sbTwo.value
};

var dataOut = {
	"firstNameProfile":firstNameProfile.value,
	"lastNameProfile":lastNameProfile.value,
	"phoneProfile":phoneProfile.value,
	"emailProfile":emailProfile.value,
	"languageSkills":languageSkills.value,
	"taxSkills" : taxSkills.value,
	"taxSkillsType": taxSkillsType.value,
	"shiftsAvailable": sbOne.value,
	"shiftsWorking":sbTwo.value
	"siteId": 3
};

//AJAX for intial prefilled form
$.ajax({
	url: "/server/storeProfile.php"
	type: "get",
	dataType: "json",
	data:(dataIn)
})

// AJAX Code To Submit Form.
$.ajax({
	url: "/server/storeProfile.php",
	type: "post",
	dataType: 'json',
	data: (dataOut),
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
	}
});
