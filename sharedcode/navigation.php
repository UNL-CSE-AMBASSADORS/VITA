<?php
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	require_once "$root/server/user.class.php";
	$USER = new User();
?>
<ul>
	<li><a href="<?php $root ?>/" title="Need Assistance">Need Assistance</a>
		<ul>
			<li><a href="<?php $root ?>/questionnaire" title="VITA questionnaire">Can VITA help you?</a></li>
			<li><a href="<?php $root ?>/signup" title="Signup for an Appointment">Sign Up for a VITA Appointment</a></li>
		</ul>
	</li>

	<li><a href="<?php $root ?>/volunteer" title="Volunteer">Volunteer</a>
		<ul>

			<?php if ($USER->isLoggedIn()): ?>
			<li><a href="" onclick="logout()">Logout</a></li>
			<script type="text/javascript">
				function logout() {
					require(['jquery'], function($) {
						$.ajax({
							url : "/server/callbacks.php",
							data: {"callback":"logout"},
							type: "POST",
							success: function() {
								window.location.href = "/";
							}
						});
					});
				}
			</script>
			<li><a href="<?php $root ?>/profile">Profile</a></li>
			<?php else: ?>
			<li><a href="<?php $root ?>/login">Login</a></li>
			<?php endif; ?>

		</ul>
	</li>

	<li><a href="<?php $root ?>/queue" title="Queue">Queue</a>
	</li>

	<?php if ($USER->hasPermission('use_admin_tools')): ?>
	<li><a href="<?php $root ?>/management" title="Site Admin">Admin</a>
		<ul>
			<li><a href="<?php $root ?>/management/users" title="Manage Users">Manage Users</a></li>
			<li><a href="<?php $root ?>/management/documents" title="Print Documents">Print Documents</a></li>
		</ul>
	</li>
	<?php endif; ?>
</ul>
