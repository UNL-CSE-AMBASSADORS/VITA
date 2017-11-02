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
				<li class="nav-item">
				<?php if ($USER->isLoggedIn()): ?>
					<a class="nav-link" onclick="logout()">Log out</a>
					<script type="text/javascript">
						function logout() {
							$.ajax({
								url : "server/logout.php",
								type: "POST",
								success: function() {
									location.reload();
								}
							});
						}
					</script>
				<?php else: ?>
					<a class="nav-link" href="/login">Volunteer Login</a>
				<?php endif; ?>
				</li>
			<?php if ($USER->hasPermission('can_view_management_tab')): ?>
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Admin</a>
					<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown01">
						<a class="dropdown-item" href="#">Review Certifications</a>
						<a class="dropdown-item" href="#">Adjust Shifts</a>
						<a class="dropdown-item" href="/management/appointments">Print Documents</a>
					</div>
				</li>
			<?php endif; ?>
			</ul>
		</div>
	</div>

</nav>
