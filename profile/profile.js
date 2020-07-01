WDN.initializePlugin('modal', [function() {
	require(['jquery'], function($) {
		$(function(){
			loadProfileInformation();
			loadAbilities();

			initializeEventListeners();
		});

		function loadProfileInformation() {
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


	
		function loadAbilities() {
			$.ajax({
				url: "/server/profile/profile.php",
				type: "GET",
				dataType: "JSON",
				data: {
					action: 'getAbilities'
				},
				cache: false,
				success: function(response) {
					$("#abilitiesEditButton").show();
					$("#abilitiesCancelButton").hide()
					$('#abilitiesSelect').empty();
					for (let i = 0; i < response.abilities.length; i++) {
						let ability = response.abilities[i];
						let modifiers = ability.has ? `data-userAbilityId=${ability.userAbilityId} checked` : '';
						let checkbox = $(`<input id=${i} type="checkbox" value=${ability.abilityId} ${modifiers} />`);
						let editLabel = $(`<label for=${i} class="dcf-pl-1">${ability.name}</label>`);
						let editContainer = $(`<div style="display:none;"></div>`).addClass("editView");
						editContainer.append(checkbox, editLabel);
						let status = $(`<i></i>`).addClass(ability.has ? "fas fa-check green-icon" : "fas fa-times red-icon");
						let label = $(`<label class="dcf-pl-1">${ability.name}</label>`);
						let container = $(`<div></div>`).addClass("preview");
						container.append(status, label);
						$('#abilitiesSelect').append(editContainer, container);
					}
	
					$('#certificationsDiv').find('div').remove();
					for (let i = 0; i < response.abilitiesRequiringVerification.length; i++) {
						let ability = response.abilitiesRequiringVerification[i];
						
						let abilityDiv = $('<div></div>');
						let abilityName = $('<span class="dcf-pl-1"></span>').html(ability.name);
						let abilityStatus = $('<i></i>').addClass(ability.has ? "fas fa-check green-icon" : "fas fa-times red-icon");
	
						abilityDiv.append(abilityStatus, abilityName);
						$('#certificationsDiv').append(abilityDiv);
					}
				},
				error: function(response) {
					alert("Unable to load abilities. Please refresh the page in a few minutes.");
				}
			});
		};

		function initializeEventListeners() {
			initializePersonalInformationEventListeners();
			initializeAbilitiesEventListeners();
		}

		function initializePersonalInformationEventListeners() {
			$("#personalInformationEditButton").click(function(e) {
				$("#firstNameInput").val($("#firstNameText").html());
				$("#lastNameInput").val($("#lastNameText").html());
				$("#emailInput").val($("#emailText").html());
				$("#phoneNumberInput").val($("#phoneNumberText").html());
				
				$(".personal-info").find('input').show();		
				$(".personal-info").find('span').hide();
	
				$(this).hide();
				$("#personalInformationSaveButton").show();
				$("#personalInformationCancelButton").show();
			});

			$("#personalInformationCancelButton").click(function(e) {
				$(this).hide();
				$("#personalInformationSaveButton").hide();
				$("#personalInformationEditButton").show();
	
				$(".personal-info").find('span').show();
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
	
							$(".personal-info").find('span').show();		
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
		}
	
		function initializeAbilitiesEventListeners() {
			$("#abilitiesEditButton").click(function(e) {
				$("#abilitiesSelect").find('.editView').show();		
				$("#abilitiesSelect").find('.preview').hide();
				$(this).hide();
				$("#abilitiesCancelButton").show()
			});
	
			$("#abilitiesCancelButton").click(function(e) {
				$("#abilitiesSelect").find('.editView').hide();		
				$("#abilitiesSelect").find('.preview').show();
				$(this).hide();
				$("#abilitiesEditButton").show()
			});
	
			$('#abilitiesSelect').on('change', function(event){
				// abilities to be removed - all non-selected options with set ids
				let removeUserAbilityIds = $(this).children('.editView').children('input:not(:checked)[data-userAbilityId]').map(function(index, ele) {
					return ele.dataset.userabilityid; // note this is case-sensitive and should be all lowercase
				}).get();
	
				// abilities to be added - all selected options w/o ids
				let addAbilityIds = $(this).children('.editView').children('input:checked:not([data-userAbilityId])').map(function(index, ele){
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


	});
}]);