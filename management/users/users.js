require.config({
	shim: {
		/* Bootstrap select is dependent on jquery */
		'bootstrap-select': { deps: ['jquery'] },
	},
	paths: {
		'bootstrap-select': '//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min'
	}
});

WDN.initializePlugin('modal', [function() {
	require(['jquery'], function($) {
		window.jQuery = $;
		require(['bootstrap-select'], function() {
			$(function() {
				initializeAddUserModalEventHandlers();
				initializeUserPermissionsSelectPickerEventHandlers();
				initializeUserAbilitiesSelectPickerEventHandlers();
				initializeEditButtonEventHandlers();

				// Initially, create the user table
				refreshUserTable();
			});

			function initializeAddUserModalEventHandlers() {
				$('#add-user').colorbox({
					inline: true, 
					width: '50%'
				});

				$('.close-modal-button').click(function() {
					$.colorbox.close();
				});

				$('#add-user-form').on('submit', function(event) {
					event.preventDefault();
					$('#add-user-form button[type=submit]').prop('disabled', true);
					
					let isValid = true;

					$(this).find('input').each(function(){
						const noValue = !$(this).val();
						const isRequired = $(this).prop('required');
						const isInvalid = isRequired && noValue;

						if (isInvalid) {
							isValid = false;
						}
						$(this).toggleClass('is-invalid', isInvalid);
					});
	
					const isValidEmail = $('#email').val().match(/.+@.+\..+/);
					if (isValidEmail == null){
						isValid = false;
						$('#email').addClass('is-invalid');
					}

					if (isValid) {
						$.ajax({
							dataType: 'json',
							method: 'POST',
							url: '/server/management/users/users.php',
							data: {
								action: 'addUser',
								firstName: $('#firstName').val(),
								lastName: $('#lastName').val(),
								email: $('#email').val(),
								phoneNumber: $('#phone').val() || ''
							},
							success: function(response) {
								$('#add-user-form button[type=submit]').prop('disabled', false);
								if (!response || !response.success) {
									alert(response.error || 'There was an error adding the user. Please refresh the page and try again.');
									return;
								}

								// Clear inputs
								$('#add-user-form input').val('');
								$.colorbox.close();
								
								refreshUserTable();
							}
						});
					} else {
						$('#add-user-form button[type=submit]').prop('disabled', false);
					}
				});
			};
		
			function initializeUserPermissionsSelectPickerEventHandlers() {
				$('#user-management-table').on('changed.bs.select', '.userPermissionsSelectPicker', function(event){
					const userId = $(this).parents('tr').data('user-id');
		
					// permissions to be removed - all non-selected options with set ids
					const removePermissionArr = $(this).children('option:not(:selected)[data-userPermissionId]').map(function(index, ele){
						return ele.dataset.userpermissionid; // note this is case-sensitive and should be all lowercase
					}).get();
					// permissions to be added - all selected options w/o ids
					const addPermissionArr = $(this).children('option:selected:not([data-userPermissionId])').map(function(index, ele){
						return ele.value;
					}).get();
		
					$.ajax({
						dataType: 'json',
						method: 'POST',
						url: '/server/management/users/users.php',
						data: {
							action: 'updateUserPermissions',
							userId: userId,
							removePermissionArr: removePermissionArr,
							addPermissionArr: addPermissionArr
						},
						success: function(response){
							if (!response  || !response.success) {
								alert(response.error || 'There was an error updating the permissions. Please refresh the page and try again');
							}
							refreshUserTable();
						}
					});
				});
			};

			function initializeUserAbilitiesSelectPickerEventHandlers() {
				$('#user-management-table').on('changed.bs.select', '.userAbilitiesSelectPicker', function(event) {
					const userId = $(this).parents('tr').data('user-id');
		
					// abilities to be removed - all non-selected options with set ids
					const removeAbilityArr = $(this).children('option:not(:selected)[data-userAbilityId]').map((index, element) => {
						return element.dataset.userabilityid; // note this is case-sensitive and should be all lowercase
					}).get();
		
					// abilities to be added - all selected options w/o ids
					const addAbilityArr = $(this).children('option:selected:not([data-userAbilityId])').map((index, element) => {
						return element.value;
					}).get();
		
					$.ajax({
						dataType: 'json',
						method: 'POST',
						url: '/server/management/users/users.php',
						data: {
							action: 'updateUserAbilities',
							userId: userId,
							removeAbilityArr: removeAbilityArr,
							addAbilityArr: addAbilityArr
						},
						success: function (response) {
							if (!response || !response.success) {
								alert(response.error || 'There was an error updating the abilities. Please refresh the page and try again');
							}
							refreshUserTable();
						}
					});
				});
			};

			function initializeEditButtonEventHandlers() {
				// Initialize the modal handlers
				$('.close-modal-button').click(function() {
					$.colorbox.close();
				});

				// Initialize the edit button handlers
				$('#user-management-table').on('click', '.userEditButton', function(event) {
					event.preventDefault();

					// Initialize the colorbox
					$('#user-management-table .userEditButton').colorbox({
						inline: true,
						width: '50%'
					});

					// Get user information
					const userId = $(this).parents('tr').data('user-id');
					getUserInformation(userId).then((userData) => {
						$('#editFirstName').val(userData.firstName);
						$('#editLastName').val(userData.lastName);
						$('#editEmail').val(userData.email);
						$('#editPhoneNumber').val(userData.phoneNumber);
					});
					
					// Submit user information
					$('#edit-user-form').on('submit', function(event) {
						event.preventDefault();
						$('#edit-user-form button[type=submit]').prop('disabled', true);

						let isValid = true;
						$(this).find('input').each(function() {
							const noValue = !$(this).val();
							const isRequired = $(this).prop('required');
							const isInvalid = isRequired && noValue;
							
							if (isInvalid) {
								isValid = false;
							}
							$(this).toggleClass('is-invalid', isInvalid);
						});
		
						const isValidEmail = $('#editEmail').val().match(/.+@.+\..+/);
						if (isValidEmail == null) {
							isValid = false;
							$('#editEmail').addClass('is-invalid');
						}

						if (isValid) {
							const newFirstName = $('#editFirstName').val();
							const newLastName = $('#editLastName').val();
							const newEmail = $('#editEmail').val();
							const newPhoneNumber = $('#editPhoneNumber').val();

							updateUserInformation(userId, newFirstName, newLastName, newEmail, newPhoneNumber).then(() => {
								$.colorbox.close();
								$('#edit-user-form button[type=submit]').prop('disabled', false);
								refreshUserTable();
							});

							// Need to remove the event handlers so that multiple don't exist after submitting the form
							$(this).off();
						} else {
							$('#edit-user-form button[type=submit]').prop('disabled', false);
						}
					});
				});
			};

			function getUserInformation(userId) {
				return $.ajax({
					dataType: 'json',
					method: 'GET',
					url: '/server/management/users/users.php',
					data: {
						action: 'getUserInformation',
						userId: userId
					}
				}).then((response) => {
					if (!response || !response.success) {
						alert(response.error || 'There was an error fetching user data. Please refresh the page and try again.');
						return;
					}

					return response.user;
				});
			};

			function updateUserInformation(userId, newFirstName, newLastName, newEmail, newPhoneNumber) {
				return $.ajax({
					dataType: 'json',
					method: 'POST',
					url: '/server/management/users/users.php',
					data: {
						action: 'updateUserInformation',
						userId: userId,
						firstName: newFirstName,
						lastName: newLastName,
						email: newEmail,
						phoneNumber: newPhoneNumber
					}
				}).then((response) => {
					if (!response || !response.success) {
						alert(response.error || 'There was an error fetching the data. Please refresh the page.');
					}
				});
			};

			function refreshUserTable() {
				$.ajax({
					dataType: 'json',
					method: 'POST',
					url: '/server/management/users/users.php',
					data: {
						action: 'getUserTable'
					},
					success: function(response){
						if (!response || !response.success) {
							alert(response.error || 'There was an error fetching the data. Please refresh the page.');
							return;
						}
						
						$('#user-management-table').html(response.table);
						$('#user-management-table .userPermissionsSelectPicker').selectpicker({
							iconBase: '',
							tickIcon: 'fas fa-check',
							multipleSeparator: ', <br>'
						});
						$('#user-management-table .userAbilitiesSelectPicker').selectpicker({
							iconBase: '',
							tickIcon: 'fas fa-check',
							multipleSeparator: ', <br>'
						});
						$('.dropdown-toggle').on('click', function(){
							// Set up event listener for clicks outside of the element
							$(document).on('click.dropdown', function(e){
								var container = $('.bootstrap-select.open');
								// If the click is not on a child element
								if (container.has(e.target).length === 0) {
									container.removeClass('open');
									$(document).off('click.dropdown');
								}
							});
							// Close all dropdowns
							$('.bootstrap-select.open').removeClass('open');
							// Open this dropdown
							$(this).parent().toggleClass('open');
						});
						$('.dropdown-toggle').siblings('select').hide();
					}
				});
			};
		});
	});
}]);
