<?php
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	require_once "$root/server/user.class.php";
	$USER = new User();

	if (!isset($page_title)) {
		$page_title = "VITA";
	}

	if (!isset($page_subtitle)) {
		$page_subtitle = "";
	}
?>

<?php
	date_default_timezone_set('America/Chicago'); // Use CST
	$now = date('Y-m-d H:i:s');
	$signupBeginsDate = '2018-01-15 00:00:00';
	if ($now < $signupBeginsDate) {
?>
	<div class="navbar navbar-expand-md navbar-light bg-light sticky-top">
		<div class="container">
			<img src="https://openclipart.org/download/29833/warning.svg" />
			<h2 class="pt-5">Warning: This site is still under construction until January 15th, 2018. Please check back then.</div>
		</div>
	</div>
<?php } ?>

<nav class="navbar navbar-expand-md navbar-dark bg-dark sticky-top">
	<div class="container">
		<a class="navbar-brand" href="/"><?php echo $page_title ?></a>

		<button class="navbar-toggler border-0" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="navbarsExampleDefault">
			<span class="navbar-text d-none d-md-inline mr-auto">
				<?php echo $page_subtitle ?>
			</span>

			<ul class="navbar-nav">
				<li class="nav-item">
					<a class="nav-link" href="/queue">Queue</a>
				</li>
			<?php if ($USER->hasPermission('use_admin_tools')): ?>
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Admin</a>
					<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown01">
						<a class="dropdown-item" href="#">Review Certifications</a>
						<a class="dropdown-item" href="#">Adjust Shifts</a>
						<a class="dropdown-item" href="/management/documents">Print Documents</a>
					</div>
				</li>
			<?php endif; ?>
			<?php if ($USER->isLoggedIn()): ?>
				<li class="nav-item">
					<a class="nav-link" onclick="logout()">Log out</a>
				</li>
				<script type="text/javascript">
					function logout() {
						$.ajax({
							url : "/server/callbacks.php",
							data: {"callback":"logout"},
							type: "POST",
							success: function() {
								window.location.href = "/";
							}
						});
					}
				</script>
			<?php else: ?>
				<li class="nav-item">
					<a class="nav-link" href="/login">Volunteer Login</a>
				</li>
			<?php endif; ?>
			</ul>
		</div>
	</div>

</nav>
