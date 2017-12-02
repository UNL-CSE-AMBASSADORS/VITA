<?php
	$page_subtitle = 'Queue';
	$root = realpath($_SERVER['DOCUMENT_ROOT']);	

	require_once "$root/server/user.class.php";
	$USER = new User();
	if ($USER->isLoggedIn()) {
		require_once "$root/queue/private.php";
	} else {
		require_once "$root/queue/public.php";
	}
?>
