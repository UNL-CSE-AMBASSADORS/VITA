$(function(){
	$('#btn-add-user').on('click', function(){
		// display new user modal
		$('#add-user-modal').modal({});

		$('#add-user-form').on('submit', function(event){
			event.preventDefault();
			
			var valid = true;

			$(this).find('input').each(function(){
				var hasVal = !$(this).val();
				if(hasVal){
					valid = false;
				}
				$(this).toggleClass('is-invalid',hasVal);
			});

			if(!$('#email').val().match(/.+@.+\..+/)){
				valid = false;
				$('#email').addClass('is-invalid');
			}
			if(valid){
				$.ajax({
					dataType: 'json',
					method: 'POST',
					url: '/server/manageuser.php',
					data: {
						callback: 'addUser',
						firstName: $('#firstName').val(),
						lastName: $('#lastName').val(),
						email: $('#email').val(),
						phone: $('#phone').val(),
						prepareTaxes: !!$('#prepareTaxes').prop('checked')
					},
					success: function(response){
						if(response.success){
							$('#add-user-modal').modal('hide');
							refreshUserTable();
						}else{
							alert(response.error);
						}
					}
				});
			}
		});
	});

	refreshUserTable();
	$('#user-permission-table').on('changed.bs.select', '.selectpicker', function(event){
		var userId = $(this).parents('tr').data('user-id');

		// permissions to be removed - all non-selected options with set ids
		var removePermissionArr = $(this).children('option:not(:selected)[data-userPermissionId]').map(function(index, ele){
			return ele.dataset.userpermissionid;
		}).get();
		// permissions to be added - all selected options w/o ids
		var addPermissionArr = $(this).children('option:selected:not([data-userPermissionId])').map(function(index, ele){
			return ele.value;
		}).get();

		$.ajax({
			dataType: 'json',
			method: 'POST',
			url: '/server/manageuser.php',
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
});

function refreshUserTable(){

	$.ajax({
		dataType: 'json',
		method: 'POST',
		url: '/server/manageuser.php',
		data: {
			callback: 'getUserTable'
		},
		success: function(response){
			if(response.success){
				$('#user-permission-table').html(response.table);
				$('#user-permission-table .selectpicker').selectpicker();
			}else{
				alert(response.error);
			}
		}
	});
}