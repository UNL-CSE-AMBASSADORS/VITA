
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
	}
}
