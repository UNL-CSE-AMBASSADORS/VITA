<?php
    require_once 'include/header.php';
?>
		<main class='container'>
			<div class='row'>
				<section class='col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3'>
					<h1 class='text-center'>VITA - Lincoln, NE</h1>
					<hr />
				</section>
			</div>
			<div class='row'>
				<section class='col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3'>
					<article id="login_panel" class='panel panel-default'>
						<div class='panel-heading'>
							<h3 class='panel-title'>User Login</h3>
						</div>
						<div class='panel-body'>
							<form id='login_form'>
								<section class='form-group col-xs-12'>
									<label class='control-label'>E-mail Address</label>
									<input id="login_email" class="form-control" placeholder="E-mail" type="text" autocomplete="off" />
								</section>
								<section class='form-group col-xs-12'>
									<label class='control-label'>Password</label>
									<input id="login_password" class="form-control" placeholder="Password" type="password" />
								</section>
								<section class='form-group col-xs-12'>
									<button type='submit' class='btn btn-block btn-lg btn-primary'>Login</button>
								</section>
								<section class='form-group col-xs-12'>
									<a id="login_register_link" href="javascript:void(0);" class="toggle_form" tabindex="-1">Forgot Password/Register</a>
								</section>
							</form>
						</div>
					</article>
				</section>
			</div>
			<div class='row'>
				<div class=" col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
					<div id="register_panel" class="panel panel-primary" style="display:none;">
						<div class="panel-heading">
							<h3 class="panel-title">Reset Password/Register</h3>
						</div>
						<div class="panel-body">
							<p id="register_success" style="display:none;">
								<span style="font-weight:bold; color:#288700;">
									Your request was received successfully. Please check your email for further instructions.
								</span>
								<br /><br />
								<a id="register_success_link" href="javascript:void(0);" class="toggle_form">
									<i class="fa fa-hand-o-left"></i> Back To Login Form
								</a>
							</p>
							<p id="register_info">
								Please provide the e-mail address associated with your account. Once the form is submitted, you will receive an e-mail with further instructions.
							</p>
							<form id="register_form" accept-charset="UTF-8" role="form">
								<fieldset>
									<div class="form-group">
										<label class='control-label'>E-mail Address</label>
										<input id="register_email" class="form-control" placeholder="E-mail" type="text" autocomplete="off" />
									</div>
									<div class="form-group">
										<a id="register_back_link" href="javascript:void(0);" class="toggle_form" tabindex="-1"><i class="fa fa-hand-o-left"></i> Back To Login Form</a>
									</div>
									<input id="register_submit" class="btn btn-lg btn-primary btn-block" type="submit" value="Reset Password/Register" />
								</fieldset>
							</form>
						</div>
					</div>
				</div>
			</div>
		</main>


		<script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
		<!-- <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.12.0.min.js"><\/script>')</script> -->
		<!-- <script src="assets/js/plugins.js"></script> -->
		<script src="assets/js/main.js"></script>

		<!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
		<script>
			(function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
			function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
			e=o.createElement(i);r=o.getElementsByTagName(i)[0];
			e.src='https://www.google-analytics.com/analytics.js';
			r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
			ga('create','UA-XXXXX-X','auto');ga('send','pageview');
		</script>
	</body>
<?php
    require_once 'include/footer.php';
?>