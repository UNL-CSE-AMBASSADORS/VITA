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
			action: 'getUserInformation'
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
			action: 'getAbilities'
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
			action: 'getShifts'
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

				let date = getDateString(startDateTime);

				let datesMap = shiftsMap.get(shift.siteId);
				if (!datesMap.has(date)) datesMap.set(date, []);

				let startTimeString = getTimeString(startDateTime);
				let endTimeString = getTimeString(endDateTime);

				let shiftTimes = datesMap.get(date);
				shiftTimes.push({
					'shiftId': shift.shiftId,
					'startTime': startTimeString,
					'endTime': endTimeString,
					'signedUp': shift.signedUp
				});

				// Append any shifts the person is already signed up for
				if (shift.signedUp) {
					appendSignedUpShift(shift.title, date, startTimeString, endTimeString, shift.userShiftId);
				}
			}
		},
		error: function(response) {
			alert("Unable to load shift. Please refresh the page in a few minutes.");
		}
	});
}

let getTimeString = function(dateTime) {
	let hour = dateTime.getHours();
	if (hour > 12) hour = hour % 12;
	let minutes = dateTime.getMinutes();
	if (minutes.toString().length < 2) minutes = '0' + minutes;
	let timeOfDay = dateTime.getHours() < 12 ? 'AM' : 'PM';
	return `${hour}:${minutes} ${timeOfDay}`;
}

let getDateString = function(dateTime) {
	let day = dateTime.getDate();
	let month = dateTime.getMonth() + 1;
	let year = dateTime.getFullYear();
	return `${month}/${day}/${year}`;
}

let appendSignedUpShift = function(title, dateString, startTimeString, endTimeString, userShiftId) {
	let shiftRow = $('<div></div>');
	let shiftInformation = $('<span></span>').html(`${title}: ${dateString} ${startTimeString} - ${endTimeString}`);
	let removeButton = $('<i></i>').addClass('fa fa-trash-o icon clickable').click(function() {
		$.ajax({
			url: "/server/profile/profile.php",
			type: "POST",
			dataType: "JSON",
			data: {
				action: 'removeShift',
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
		$("#personalInformationCancelButton").show();
	});

	$("#personalInformationCancelButton").click(function(e) {
		$(this).hide();
		$("#personalInformationSaveButton").hide();
		$("#personalInformationEditButton").show();

		$(".personal-info").find('p').show();
		$(".personal-info").find('input').hide();
	});

	$("#personalInformationSaveButton").click(function(e) {
		$(this).prop('disabled', true);
		$("#personalInformationCancelButton").prop('disabled', true);

		$.ajax({
			url: "/server/profile/profile.php",
			type: "POST",
			dataType: "JSON",
			data: {
				firstName: $("#firstNameInput").val(),
				lastName: $("#lastNameInput").val(),
				email: $("#emailInput").val(),
				phoneNumber: $("#phoneNumberInput").val(),
				action: "updatePersonalInformation"
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
					$("#personalInformationCancelButton").hide();
					$("#personalInformationEditButton").show();
				} else {
					alert(response.error);
				}

				$("#personalInformationSaveButton").prop('disabled', false);
				$("#personalInformationCancelButton").prop('disabled', false);
			},
			error: function(response) {
				alert("Unable to communicate with the server. Please try again later.");
				$("#personalInformationSaveButton").prop('disabled', false);
				$("#personalInformationCancelButton").prop('disabled', false);
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
					text: `${shift.startTime} - ${shift.endTime}`
				}));
			}
		});

		let cancelButton = $('<button type="button"></button>').addClass("btn btn-seconday").html("Cancel").click(function(){
			$(this).parent().remove();
		});

		let signUpButton = $('<button type="button"></button>').addClass('btn btn-primary').html('Sign Up').click(function() {
			$(this).prop('disabled', true);
			cancelButton.prop('disabled', true);

			let siteId = siteSelect.val();
			let dateString = dateSelect.val();
			let shiftId = timeSelect.val();

			$.ajax({
				url: "/server/profile/profile.php",
				type: "POST",
				dataType: "JSON",
				data: {
					shiftId: shiftId,
					action: "signUpForShift"
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

					signUpButton.prop('disabled', false);
					cancelButton.prop('disabled', false);
				},
				error: function(response) {
					alert("Unable to communicate with the server. Please try again later.");
					signUpButton.prop('disabled', false);
					cancelButton.prop('disabled', false);
				}
			});
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
				action: 'updateAbilities',
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
