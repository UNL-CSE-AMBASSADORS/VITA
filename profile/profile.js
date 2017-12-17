$(document).ready(function() {
	loadProfileInformation();
	loadAbilities();
	loadShifts();

	initializeEventListeners();
});

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
			$('#abilitiesSelect').find('option').remove();
			for (let i = 0; i < response.abilities.length; i++) {
				let ability = response.abilities[i];
				let modifiers = ability.has ? `data-userAbilityId=${ability.userAbilityId} selected` : '';
				let option = $(`<option value=${ability.abilityId} ${modifiers}>${ability.name}</option>`);
				$('#abilitiesSelect').append(option);
			}
			$('#abilitiesSelect').selectpicker();

			$('#certificationsDiv').find('div').remove();
			for (let i = 0; i < response.abilitiesRequiringVerification.length; i++) {
				let ability = response.abilitiesRequiringVerification[i];
				
				let abilityDiv = $('<div></div>');
				let abilityName = $('<span></span>').html(ability.name);
				let abilityStatus = $('<i></i>').addClass(`${ability.has ? 'fa fa-check icon green-icon' : 'fa fa-times icon red-icon'}`);

				abilityDiv.append(abilityName, abilityStatus);
				$('#certificationsDiv').append(abilityDiv);
			}
		},
		error: function(response) {
			alert("Unable to load abilities. Please refresh the page in a few minutes.");
		}
	});
};

// Maps a siteId -> dates that site is open -> shifts for that date
let shiftsMap = new Map();
// Maps a siteId to the title of the site
let sitesMap = new Map(); 

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

				// Create the shiftsMap
				if (!sitesMap.has(shift.siteId)) sitesMap.set(shift.siteId, shift.title);
				if (!shiftsMap.has(shift.siteId)) shiftsMap.set(shift.siteId, new Map());
				
				let startDateTime = new Date(shift.startTime + ' CST');
				let endDateTime = new Date(shift.endTime + ' CST');

				let date = startDateTime.toLocaleDateString();

				let datesMap = shiftsMap.get(shift.siteId);
				if (!datesMap.has(date)) datesMap.set(date, []);

				let startTime = startDateTime.toLocaleTimeString();
				let endTime = endDateTime.toLocaleTimeString();

				let shiftTimes = datesMap.get(date);
				shiftTimes.push({
					'shiftId': shift.shiftId,
					'startTime': startTime,
					'endTime': endTime,
					'signedUp': shift.signedUp
				});

				// Append any shifts the person is already signed up for
				if (shift.signedUp) {
					appendSignedUpShift(shift.title, date, startTime, endTime, shift.userShiftId);
				}
			}
		},
		error: function(response) {
			alert("Unable to load shift. Please refresh the page in a few minutes.");
		}
	});
}

let appendSignedUpShift = function(title, dateString, startTimeString, endTimeString, userShiftId) {
	let shiftRow = $('<div></div>');
	let shiftInformation = $('<span></span>').html(`${title}: ${dateString} ${startTimeString}-${endTimeString}`);
	let removeButton = $('<i></i>').addClass('fa fa-trash-o icon clickable').click(function() {
		$.ajax({
			url: "/server/profile/profile.php",
			type: "POST",
			dataType: "JSON",
			data: {
				callback: 'removeShift',
				userShiftId: userShiftId
			},
			cache: false,
			success: function(response) {
				if (response.success) {
					shiftRow.remove();
				} else {
					alert(response.error);
				}
			},
			error: function(response) {
				alert('Unable to communicate with server. Try again in a few minutes.');
			}
		});
	});
	
	shiftRow.append(shiftInformation, removeButton);
	$('#shiftsSignedUpFor').append(shiftRow);
}

let initializeEventListeners = function() {
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
		$(this).prop('disabled', true);

		$.ajax({
			url: "/server/profile/profile.php",
			type: "POST",
			dataType: "JSON",
			data: {
				firstName: $("#firstNameInput").val(),
				lastName: $("#lastNameInput").val(),
				email: $("#emailInput").val(),
				phoneNumber: $("#phoneNumberInput").val(),
				callback: "updatePersonalInformation"
			},
			cache: false,
			success: function(response) {
				if (response.success) {
					$("#firstNameText").html($("#firstNameInput").val());
					$("#lastNameText").html($("#lastNameInput").val());
					$("#emailText").html($("#emailInput").val());
					$("#phoneNumberText").html($("#phoneNumberInput").val());

					$(".personal-info").find('p').show();		
					$(".personal-info").find('input').hide();

					$("#personalInformationSaveButton").hide();
					$("#personalInformationEditButton").show();
				} else {
					alert(response.error);
				}
			},
			error: function(response) {
				alert("Unable to communicate with the server. Please try again later.")
			}
		});
	});

	$("#addShiftButton").click(function(e) {
		let shiftRow = $('<div></div>').addClass("add-shift-div");
		let siteSelect = $('<select></select>').addClass("siteSelect");
		let dateSelect = $('<select></select>').addClass("dateSelect").attr('disabled', true);
		let timeSelect = $('<select></select>').addClass("timeSelect").attr('disabled', true);
		
		siteSelect.append($('<option disabled selected value="" style="display:none"> -- Select a site -- </option>'));
		dateSelect.append($('<option disabled selected value="" style="display:none"> -- Select a date -- </option>'));
		timeSelect.append($('<option disabled selected value="" style="display:none"> -- Select a time -- </option>'));

		for (const [siteId, title] of sitesMap) {
			siteSelect.append($('<option>', {
				value: siteId,
				text: title
			}));
		}

		siteSelect.change(function() {
			timeSelect.children('option').remove();
			timeSelect.append($('<option disabled selected value="" style="display:none"> -- Select a time -- </option>'));
			timeSelect.attr('disabled', true);			
			dateSelect.children('option').remove();
			dateSelect.append($('<option disabled selected value="" style="display:none"> -- Select a date -- </option>'));
			dateSelect.attr('disabled', false);
			
			let siteId = $(this).val();

			// populate dates select now based on which site was chosen
			for (const dateString of shiftsMap.get(siteId).keys()) {
				dateSelect.append($('<option>', {
					value: dateString,
					text: dateString
				}));
			}
		});

		dateSelect.change(function() {
			timeSelect.children('option').remove();
			timeSelect.append($('<option disabled selected value="" style="display:none"> -- Select a time -- </option>'));			
			timeSelect.attr('disabled', false);
			let siteId = siteSelect.val();
			let dateString = $(this).val();

			// populate shift times select now based on which date was chosen AND the user is not already signed up for it
			for (const shift of shiftsMap.get(siteId).get(dateString)) {
				if (shift.signedUp) continue;
				timeSelect.append($('<option>', {
					value: shift.shiftId,
					text: `${shift.startTime}-${shift.endTime}`
				}));
			}
		});

		let signUpButton = $('<button type="button"></button>').addClass('btn btn-primary').html('Sign Up').click(function() {
			let siteId = siteSelect.val();
			let dateString = dateSelect.val();
			let shiftId = timeSelect.val();

			$.ajax({
				url: "/server/profile/profile.php",
				type: "POST",
				dataType: "JSON",
				data: {
					shiftId: shiftId,
					callback: "signUpForShift"
				},
				cache: false,
				success: function(response) {
					if (response.success) {
						shiftRow.remove();
						// Find the shift and append the p tags to show the user is signed up
						for (const shift of shiftsMap.get(siteId).get(dateString)) {
							if (shift.shiftId === shiftId) {
								let title = sitesMap.get(siteId);
								appendSignedUpShift(title, dateString, shift.startTime, shift.endTime, response.userShiftId);
								break;
							}
						}
					} else {
						alert(response.error);
					}
				},
				error: function(response) {
					alert("Unable to communicate with the server. Please try again later.");
				}
			});
		});
		let cancelButton = $('<button type="button"></button>').addClass("btn btn-seconday").html("Cancel").click(function(){
			$(this).parent().remove();
		});

		shiftRow.append(siteSelect, dateSelect, timeSelect, signUpButton, cancelButton);
		$("#shifts").append(shiftRow);	
	});

	$('#abilitiesSelect').on('changed.bs.select', function(event){
		// abilities to be removed - all non-selected options with set ids
		let removeUserAbilityIds = $(this).children('option:not(:selected)[data-userAbilityId]').map(function(index, ele){
			return ele.dataset.userabilityid; // note this is case-sensitive and should be all lowercase
		}).get();

		// abilities to be added - all selected options w/o ids
		let addAbilityIds = $(this).children('option:selected:not([data-userAbilityId])').map(function(index, ele){
			return ele.value;
		}).get();

		$.ajax({
			dataType: 'JSON',
			method: 'POST',
			url: '/server/profile/profile.php',
			data: {
				callback: 'updateAbilities',
				removeAbilityArray: removeUserAbilityIds,
				addAbilityArray: addAbilityIds
			},
			success: function(response){
				if (response.success) {
					loadAbilities();
				} else {
					alert(response.error);
				}
			}
		});
	});
}
