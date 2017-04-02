$(function(){
	console.log("%cSTOP! \n%cDo NOT paste/type anything here under any circumstance.", "color: red; font-size:36px;", "color: black; font-size: 12pt"); 

	var dir = document.URL;
	if(dir.indexOf('login') > -1){
		window.onload = LoginControls;
	}else if(dir.indexOf('passwdreset') > -1){
		window.onload = PasswordResetControls;
	}else{
		window.onload = LoginControls;
	}
});

var PasswordResetControls = function(){
// Validate Form
	$("#reset_password_form").on('submit', function(e) {
	
		// Prevent form submission
		e.preventDefault();

		// Define Fields
		var token = $("#reset_password_token").val();
		var email = $("#reset_password_email").val();
		var password = $("#reset_password_npassword").val();
		var vpassword = $("#reset_password_vpassword").val();

		if(email && password && vpassword){
			// Ajax
			$.ajax({
				dataType: 'json',
				method: 'POST',
				url: '/assets/server/callbacks.php',
				data: {
					callback: 'password_reset',
					token: token,
					email: email,
					password: password,
					vpassword: vpassword
				},
				success: function(response){
					if(response.success){
						$("#reset_password_form, #reset_password_info").hide();
						$("#reset_password_success").fadeIn();
					}else{
						alert(response.error);
					}
				}
			});
		}else{
			// display some message about fields
		}

	});
};

// login.php functions
var LoginControls = function(){
	
	// Focus Email Field
	$("#login_email").focus();

	// Toogle Forms
	$(".toggle_form").click(function(){
		$("#login_form")[0].reset();
		$("#register_form")[0].reset();
		$("#register_success").hide();
		$("#register_form, #register_info").show();
		$("#login_panel,#register_panel").toggle("slow", function(){
			if($("#login_form").is(':visible')){
				$("#login_email").focus();
			}else{
				$("#register_email").focus();
			}
		});

	});

	$("#login_form").on('submit', function(e) {
		// Prevent form submission
		e.preventDefault();

		// Define Fields
		var email = $("#login_email").val();
		var password = $("#login_password").val();

		if(email && password){
			// Ajax
			$.ajax({
				dataType: 'json',
				method: 'POST',
				url: '/assets/server/callbacks.php',
				data: {
					callback: 'login',
					email: email,
					password: password
				},
				success: function(response){
					if(response.success){
						window.location = response.redirect;
					}else{
						alert(response.error);
					}
				}
			});
		}else{

		}
	});

	$("#register_form").on('submit', function(e) {
	
		// Prevent form submission
		e.preventDefault();

		// Define Fields
		var email = $("#register_email").val();

		if(email){
			$.ajax({
				dataType: 'json',
				method: 'POST',
				url: '/assets/server/callbacks.php',
				data: {
					callback: 'register',
					email: email
				},
				success: function(response){
					if(response.success){
						$("#register_form, #register_info").hide();
						$("#register_success").fadeIn();
					}else{
						alert(response.error);
					}
				}
			});
		}
	});
};