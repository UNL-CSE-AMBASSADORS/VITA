<?php
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	require_once "$root/server/user.class.php";
	$USER = new User();

	if (!isset($page_title)) {
		$page_title = "VITA";
	}

	if (!isset($page_subtitle)) {
		$page_subtitle = "Center for People in Need";
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
								url : "/server/callbacks.php",
								data: {"callback":"logout"},
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
			</ul>
		</div>
	</div>

</nav>
