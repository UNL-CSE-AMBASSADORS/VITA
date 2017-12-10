$(document).ready(function() {
	loadProfileInformation();
	loadAbilities();
	loadShifts();
});

// TODO: Need to combine all these methods into one, and same in the PHP

let loadProfileInformation = function() {
	$.ajax({
		url: "/server/profile/profile.php",
		type: "GET",
		dataType: "JSON",
		data: {
			callback: 'getUserInformation'
		},
		cache: false,
		success: function(response) {
			$("#firstName").html(response.firstName);
			$("#lastName").html(response.lastName);
			$("#email").html(response.email);
			$("#phoneNumber").html(response.phoneNumber);
		},
		error: function(response) {
			alert("Unable to load information. Please refresh the page in a few minutes.");
		}
	});
};

let loadAbilities = function() {
	$.ajax({
		url: "/server/profile/profile.php",
		type: "GET",
		dataType: "JSON",
		data: {
			callback: 'getAbilities'
		},
		cache: false,
		success: function(response) {
			for (let i = 0; i < response.abilities.length; i++) {
				let ability = response.abilities[i];
				$("#abilitiesDiv").append($('<p></p>').html(`${ability.name} ${ability.has ? 'HAS' : 'DOES NOT HAVE'}`));
			}

			for (let i = 0; i < response.abilitiesRequiringVerification.length; i++) {
				let ability = response.abilitiesRequiringVerification[i];
				$("#abilitiesRequiringVerificationDiv").append($('<p></p>').html(`${ability.name} ${ability.has ? 'HAS' : 'DOES NOT HAVE'}`));
			}
		},
		error: function(response) {
			alert("Unable to load abilities. Please refresh the page in a few minutes.");
		}
	});
};

let loadShifts = function() {
	$.ajax({
		url: "/server/profile/profile.php",
		type: "GET",
		dataType: "JSON",
		data: {
			callback: 'getShifts'
		},
		cache: false,
		success: function(response) {
			for (let i = 0; i < response.shifts.length; i++) {
				let shift = response.shifts[i];
				let option = $(`<option value=${shift.shiftId} ${shift.signedUp ? 'selected' : ''}>${shift.title} ${shift.startTime}-${shift.endTime}</option>`);
				$("#shiftsSelect").append(option);
			}
			$('#shiftsSelect').selectpicker();			
		},
		error: function(response) {
			alert("Unable to load shift. Please refresh the page in a few minutes.");
		}
	});
}


/*
- Personal certifications (ex. can speak Spanish) (editable)
- Verified certifications
Shifts
- Currently signed up shifts
Ability to sign up for more shifts

*/