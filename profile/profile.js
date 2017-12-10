$(document).ready(function() {
	loadProfileInformation();
	loadAbilities();
	loadShifts();

	initializeObservers();
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
			$("#firstName").val(response.firstName);
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
				let option = $(`<option value=${ability.abilityId} ${ability.has ? 'selected' : ''}>${ability.name}</option>`);
				$("#abilitiesSelect").append(option);
			}
			$('#abilitiesSelect').selectpicker();				

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

let updateFirstName = function() {
	$.ajax({
		url: "/server/profile/profile.php",
		type: "POST",
		dataType: "JSON",
		data: {
			firstName: $("#firstName").val(),
			callback: 'updateFirstName'
		},
		cache: false,
		success: function(response) {
			if (!response.success) {
				alert(response.error);
			}
		},
		error: function(response) {
			alert("Unable to communicate with the server. Please try again later.");
		}
	});
}

let initializeObservers = function() {
	$("#firstNameSaveButton").click(function(e) {
		$(this).prop("disabled", true);
		updateFirstName();
		$(this).prop("disabled", false);		
	});
}