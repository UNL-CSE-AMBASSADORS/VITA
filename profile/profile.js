WDN.initializePlugin('modal', [function() {
	require(['jquery'], function($) {
		$(function(){
			loadProfileInformation();

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

		function initializeEventListeners() {
			initializePersonalInformationEventListeners();
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


	});
}]);