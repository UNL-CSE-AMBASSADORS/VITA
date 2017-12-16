<?php
	$page_subtitle = 'Queue';
	$root = realpath($_SERVER['DOCUMENT_ROOT']);	

	require_once "$root/server/user.class.php";
	$USER = new User();
	if ($USER->isLoggedIn() && !isset($_REQUEST['public'])) { // we enable logged in users to get to the public queue by having ?public in the URL
		require_once "$root/queue/private.php";
	} else {
		require_once "$root/queue/public.php";
	}
?>
