$(document).ready(function() {
	// Listen for changes to all of the other conditional form fields
	initializeConditionalFormFields();
});

let scrollDown = function(height, animationTime = 0) {
	$('html, body').animate({
		scrollTop: '+=' + height
	}, animationTime);
}

let initializeConditionalFormFields = function() {
	let animationTime = 300;
	
	// All of the questions that required conditions to be viewed.
	let homeBased = $("#homeBased");
	let homeBasedNetLoss = $("#homeBasedNetLoss");
	let homeBased10000 = $("#homeBased10000");
	let homeBasedSEP = $("#homeBasedSEP");
	let homeBasedEmployees = $("#homeBasedEmployees");

	// All the radio buttons
	let homeBasedValues = homeBased.find('input:radio[name="3"]');

	// To help hide everything and selectively show content
	let allUnderHomeBasedValues = homeBasedNetLoss.add(homeBased10000).add(homeBasedSEP).add(homeBasedEmployees);

	// Independent field = #homeBased
	// Dependent field = if yes --> #homeBasedNetLoss,#homeBased10000,#homeBasedSEP,#homeBasedEmployees
	homeBasedValues.change(function() {
		let value = this.value;
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
}