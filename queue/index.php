<?php
	if(isset($_REQUEST['public'])){
		require_once 'public.php';
	}
	// if is logged in
	else require_once 'private.php';
	// else
	// require_once '/queue_public.php';
?>
