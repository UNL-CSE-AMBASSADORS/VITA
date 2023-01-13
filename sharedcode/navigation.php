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
			<li><a href="<?php $root ?>/cancel" title="Cancel/Reschedule an Appointment">Cancel/Reschedule an Appointment</a></li>
			<li><a href="<?php $root ?>/self_assist" title="Facilitated Self-Assistance (FSA)">Facilitated Self-Assistance (FSA)</a></li>
		</ul>
	</li>

	<li><a href="<?php $root ?>/volunteer" title="Volunteer">Staff</a>
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

	<?php if ($USER->isLoggedIn()): ?>
		<li><a href="<?php $root ?>/queue" title="Queue">Queue</a></li>
	<li><a href="<?php $root ?>/management" title="Management Tools">Management</a>
		<ul>
			<li><a href="<?php $root ?>/management/appointments" title="Appointment Management">Appointment Management</a></li>
			
			<?php if ($USER->hasPermission('use_admin_tools')): ?>
			<li><a href="<?php $root ?>/management/users" title="User Management">User Management</a></li>
			<li><a href="<?php $root ?>/management/sites" title="Site Management">Site Management</a></li>
			<li><a href="<?php $root ?>/management/documents" title="Print Documents">Print Documents</a></li>
			<li><a href="<?php $root ?>/management/analytics" title="Analytics">Analytics</a></li>
			<?php endif; ?>
		</ul>
	</li>
	<?php endif; ?>
</ul>
