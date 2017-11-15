"use strict";

$(document).ready(function () {
	console.log("%cSTOP! \n%cDo NOT paste/type anything here under any circumstance.", "color: red; font-size:36px;", "color: black; font-size: 12pt");
	PasswordResetControls();
});

var PasswordResetControls = function PasswordResetControls() {
	// Validate Form
	$("#reset_password_form").on('submit', function (e) {

		// Prevent form submission
		e.preventDefault();

		// Define Fields
		var token = $("#reset_password_token").val();
		var email = $("#reset_password_email").val();
		var password = $("#reset_password_npassword").val();
		var vpassword = $("#reset_password_vpassword").val();

		if (email && password && vpassword) {
			// Ajax
			$.ajax({
				dataType: 'json',
				method: 'POST',
				url: '/server/callbacks.php',
				data: {
					callback: 'password_reset',
					token: token,
					email: email,
					password: password,
					vpassword: vpassword
				},
				success: function success(response) {
					if (response.success) {
						$("#reset_password_form, #reset_password_info").hide();
						$("#reset_password_success").fadeIn();
					} else {
						alert(response.error);
					}
				}
			});
		} else {
			// display some message about fields
		}
	});
};