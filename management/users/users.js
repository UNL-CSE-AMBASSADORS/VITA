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
						if (noValue) {
							isValid = false;
						}
						$(this).toggleClass('is-invalid', noValue);
					});
	
					const isValidEmail = $('#email').val().match(/.+@.+\..+/);
					if (!isValidEmail){
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
								phoneNumber: $('#phone').val()
							},
							success: function(response) {
								$('#add-user-form button[type=submit]').prop('disabled', false);
	
								if (response.success) {
									// Clear inputs
									$('#add-user-form input').val('');
									$.colorbox.close();
									
									refreshUserTable();
								} else {
									alert(response.error);
								}
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
							tickIcon: 'wdn-icon-ok',
							multipleSeparator: ', <br>'
						});
						$('#user-management-table .userAbilitiesSelectPicker').selectpicker({
							iconBase: '',
							tickIcon: 'wdn-icon-ok',
							multipleSeparator: ', <br>'
						});
						$('.dropdown-toggle').on('click', function(){
							// Set up event listener for clicks outside of the element
							$(document).on('click.dropdown', function(e){
								const container = $('.bootstrap-select.open');
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
