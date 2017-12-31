require.config({
	shim: {
		/* Bootstrap is dependent on jquery */
		'bootstrap-select': { deps: ['jquery'] },
	},
	paths: {
		'bootstrap-select': 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min'
	}
});

WDN.initializePlugin('modal', [function() {
	require(['jquery'], function($) {
		window.jQuery = $;
		require(['bootstrap-select'], function() {
			$(function(){
				// display new user modal
				$('#add-user').colorbox({
					inline: true, 
					width: '50%'
				});

				$('.close-modal-button').click(function(){
					$.colorbox.close();
				});

				$('#add-user-form').on('submit', function(event){
					event.preventDefault();
					$('#add-user-form button[type=submit]').prop('disabled', true);
					
					var valid = true;
	
					$(this).find('input').each(function(){
						var noValue = !$(this).val();
						if(noValue){
							valid = false;
						}
						$(this).toggleClass('is-invalid',noValue);
					});
	
					if(!$('#email').val().match(/.+@.+\..+/)){
						valid = false;
						$('#email').addClass('is-invalid');
					}
					if(valid){
						$.ajax({
							dataType: 'json',
							method: 'POST',
							url: '/server/management/users/users.php',
							data: {
								callback: 'addUser',
								firstName: $('#firstName').val(),
								lastName: $('#lastName').val(),
								email: $('#email').val(),
								phone: $('#phone').val()
							},
							success: function(response){
								$('#add-user-form button[type=submit]').prop('disabled', false);
	
								if(response.success){
									// Clear inputs
									$('#add-user-form input').val('');
	
									// Close box
									$.colorbox.close();
									refreshUserTable();
								}else{
									alert(response.error);
								}
							}
						});
					}else{
						$('#add-user-form button[type=submit]').prop('disabled', false);
					}
				});
		
				refreshUserTable();
				$('#user-management-table').on('changed.bs.select', '.userPermissionsSelectPicker', function(event){
					var userId = $(this).parents('tr').data('user-id');
		
					// permissions to be removed - all non-selected options with set ids
					var removePermissionArr = $(this).children('option:not(:selected)[data-userPermissionId]').map(function(index, ele){
						return ele.dataset.userpermissionid; // note this is case-sensitive and should be all lowercase
					}).get();
					// permissions to be added - all selected options w/o ids
					var addPermissionArr = $(this).children('option:selected:not([data-userPermissionId])').map(function(index, ele){
						return ele.value;
					}).get();
		
					$.ajax({
						dataType: 'json',
						method: 'POST',
						url: '/server/management/users/users.php',
						data: {
							callback: 'updateUserPermissions',
							userId: userId,
							removePermissionArr: removePermissionArr,
							addPermissionArr: addPermissionArr
						},
						success: function(response){
							if(response.success){
								refreshUserTable();
							}else{
								refreshUserTable();
								alert(response.error);
							}
						}
					});
				});
		
				$('#user-management-table').on('changed.bs.select', '.userAbilitiesSelectPicker', function(event) {
					let userId = $(this).parents('tr').data('user-id');
		
					// abilities to be removed - all non-selected options with set ids
					let removeAbilityArr = $(this).children('option:not(:selected)[data-userAbilityId]').map((index, element) => {
						return element.dataset.userabilityid; // note this is case-sensitive and should be all lowercase
					}).get();
		
					// abilities to be added - all selected options w/o ids
					let addAbilityArr = $(this).children('option:selected:not([data-userAbilityId])').map((index, element) => {
						return element.value;
					}).get();
		
					$.ajax({
						dataType: 'json',
						method: 'POST',
						url: '/server/management/users/users.php',
						data: {
							callback: 'updateUserAbilities',
							userId: userId,
							removeAbilityArr: removeAbilityArr,
							addAbilityArr: addAbilityArr
						},
						success: function (response) {
							if (response.success) {
								refreshUserTable();
							} else {
								refreshUserTable();
								alert(response.error);
							}
						}
					});
				});
			});
		
			function refreshUserTable(){
				$.ajax({
					dataType: 'json',
					method: 'POST',
					url: '/server/management/users/users.php',
					data: {
						callback: 'getUserTable'
					},
					success: function(response){
						if(response.success){
							$('#user-management-table').html(response.table);
							$('#user-management-table .userPermissionsSelectPicker').selectpicker({
								iconBase: '',
								tickIcon: 'wdn-icon-ok'
							});
							$('#user-management-table .userAbilitiesSelectPicker').selectpicker({
								iconBase: '',
								tickIcon: 'wdn-icon-ok'
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
						}else{
							alert(response.error);
						}
					}
				});
			}
		});
	});
}]);
