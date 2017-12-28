<?php
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	require_once "$root/server/user.class.php";
	$USER = new User();
?>
<ul>
	<li><a href="<?php $root ?>/" title="Need Assistance">Need Assistance</a>
		<ul>
			<li><a href="<?php $root ?>/" title="VITA questionnaire">Can VITA help you?</a></li>
			<li><a href="<?php $root ?>/" title="Signup for an Appointment">Signup for an Appointment</a></li>
		</ul>
	</li>

	<li><a href="<?php $root ?>/" title="Volunteer">Volunteer</a>
		<ul>

			<?php if ($USER->isLoggedIn()): ?>
			<li><a href="<?php $root ?>/logout">Logout</a></li>
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
