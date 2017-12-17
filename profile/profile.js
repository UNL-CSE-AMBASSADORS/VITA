$(document).ready(function() {
	loadProfileInformation();
	loadAbilities();
	loadShifts();
	loadSites();

	initializeObservers();
});

let loadSites = function() {
	// TODO LOAD SITES
	console.log('SITES');
}

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
			$("#firstNameText").html(response.firstName);
			$("#lastNameText").html(response.lastName);
			$("#emailText").html(response.email);
			$("#phoneNumberText").html(response.phoneNumber);
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
				let modifiers = ability.has ? `data-userAbilityId=${ability.userAbilityId} selected` : '';
				let option = $(`<option value=${ability.abilityId} ${modifiers}>${ability.name}</option>`);
				$("#abilitiesSelect").append(option);
			}
			$('#abilitiesSelect').selectpicker();

			for (let i = 0; i < response.abilitiesRequiringVerification.length; i++) {
				let ability = response.abilitiesRequiringVerification[i];
				
				$("#abilitiesRequiringVerificationDiv").append($('<p></p>').html(`${ability.name}`).addClass(`${ability.has ? 'show-icon-check' : 'show-icon-x'}`));
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
			firstName: $("#firstNameInput").val(),
			callback: "updateFirstName"
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

let updateLastName = function() {
	$.ajax({
		url: "/server/profile/profile.php",
		type: "POST",
		dataType: "JSON",
		data: {
			lastName: $("#lastNameInput").val(),
			callback: "updateLastName"
		},
		cache: false,
		success: function(response) {
			if (!response.success) {
				alert(response.error);
			}
		},
		error: function(response) {
			alert("Unable to communicate with the server. Please try again later.")
		}
	});
}


let updateEmail = function() {
	$.ajax({
		url: "/server/profile/profile.php",
		type: "POST",
		dataType: "JSON",
		data: {
			email: $("#emailInput").val(),
			callback: "updateEmail"
		},
		cache: false,
		success: function(response) {
			if (!response.success) {
				alert(response.error);
			}
		},
		error: function(response) {
			alert("Unable to communicate with the server. Please try again later.")
		}
	});
}


let updatePhoneNumber = function() {
	$.ajax({
		url: "/server/profile/profile.php",
		type: "POST",
		dataType: "JSON",
		data: {
			phoneNumber: $("#phoneNumberInput").val(),
			callback: "updatePhoneNumber"
		},
		cache: false,
		success: function(response) {
			if (!response.success) {
				alert(response.error);
			}
		},
		error: function(response) {
			alert("Unable to communicate with the server. Please try again later.")
		}
	});
}

let initializeObservers = function() {
	$("#personalInformationEditButton").click(function(e) {
		$("#firstNameInput").val($("#firstNameText").html());
		$("#lastNameInput").val($("#lastNameText").html());
		$("#emailInput").val($("#emailText").html());
		$("#phoneNumberInput").val($("#phoneNumberText").html());
		
		$(".personal-info").find('input').show();		
		$(".personal-info").find('p').hide();

		$(this).hide();
		$("#personalInformationSaveButton").show();
	});

	$("#personalInformationSaveButton").click(function(e) {
		$("#firstNameText").html($("#firstNameInput").val());
		$("#lastNameText").html($("#lastNameInput").val());
		$("#emailText").html($("#emailInput").val());
		$("#phoneNumberText").html($("#phoneNumberInput").val());

		updateFirstName();
		updateLastName();
		updateEmail();
		updatePhoneNumber();
		
		$(".personal-info").find('p').show();		
		$(".personal-info").find('input').hide();

		$(this).hide();
		$("#personalInformationEditButton").show();
	});

	$("#addShiftButton").click(function(e) {
		let shiftRow = $('<div></div>').addClass("shift-div");
		let siteSelect = $('<select></select>').addClass("siteSelect");
		let dateSelect = $('<select></select>').addClass("dateSelect").attr('disabled', true);
		let timeSelect = $('<select></select>').addClass("timeSelect").attr('disabled', true);
		let removeButton = $('<button type="button"></button>').addClass("btn btn-danger").html("Remove").click(function(){
			$(this).parent().remove();
		});

		siteSelect.append($('<option disabled selected value="" style="display:none"> -- select an option -- </option>'));
		// TODO FINISH IMPLEMENTING SITES
		// for(const site of sites) {
		// 	siteSelect.append($('<option>', {
		// 		value: site.siteId,
		// 		text : site.title
		// 	}));
		// }

		siteSelect.change(function() {
			dateSelect.attr('disabled', false);
		});

		shiftRow.append(siteSelect, dateSelect, timeSelect, removeButton);
		$("#shifts").append(shiftRow);	
	});

	$('#abilitiesSelect').on('changed.bs.select', function(event){
		// abilities to be removed - all non-selected options with set ids
		let removeAbilityArray = $(this).children('option:not(:selected)[data-userAbilityId]').map(function(index, ele){
			return ele.dataset.userabilityid; // note this is case-sensitive and should be all lowercase
		}).get();
		// abilities to be added - all selected options w/o ids
		let addAbilityArray = $(this).children('option:selected:not([data-userAbilityId])').map(function(index, ele){
			return ele.value;
		}).get();

		$.ajax({
			dataType: 'JSON',
			method: 'POST',
			url: '/server/profile/profile.php',
			data: {
				callback: 'updateAbilities',
				removeAbilityArray: removeAbilityArray,
				addAbilityArray: addAbilityArray
			},
			success: function(response){
				if (!response.success) {
					alert(response.error);
				}
			}
		});
	});


	$('#shiftsSelect').on('changed.bs.select', function(event){
		// abilities to be removed - all non-selected options with set ids
		let removeShiftArray = $(this).children('option:not(:selected)[data-userAbilityId]').map(function(index, ele){
			return ele.dataset.userabilityid; // note this is case-sensitive and should be all lowercase
		}).get();
		// abilities to be added - all selected options w/o ids
		let addAbilityArray = $(this).children('option:selected:not([data-userAbilityId])').map(function(index, ele){
			return ele.value;
		}).get();

		$.ajax({
			dataType: 'JSON',
			method: 'POST',
			url: '/server/profile/profile.php',
			data: {
				callback: 'updateAbilities',
				removeAbilityArray: removeAbilityArray,
				addAbilityArray: addAbilityArray
			},
			success: function(response){
				if (!response.success) {
					alert(response.error);
				}
			}
		});
	});


}