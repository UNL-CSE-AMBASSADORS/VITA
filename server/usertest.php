<?php
	session_start();
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	require_once "$root/server/user.class.php";

	$USER = new User();
?>
<!DOCTYPE html>
<html class='no-js theme-light' lang="">
<head>
	<title>Test</title>
	<?php require_once "$root/server/header.php" ?>
</head>
<body>

<p>Am I logged in? <span><?php echo ($USER->isLoggedIn() ? 'Yes' : 'No')?></span></p>

<p>Who am I? <span><?php @print $USER->getUserId() ?: 'N/A'?></span></p>

<p>Do I have edit_user_permission? <span><?php @print $USER->hasPermission('edit_user_permissions') ? 'Yes': 'No'?></span></p>

<p>Do I have edit_user_TYPOrmission? <span><?php @print $USER->hasPermission('edit_user_TYPOrmission') ? 'Yes': 'No'?></span></p>

<p>$_SESSION['LAST_ACTIVITY'] (Unix timestamp): <span><?php echo $_SESSION['LAST_ACTIVITY']?></span></p>

<p>$_SESSION['USER__ID']: <span><?php echo $_SESSION['USER__ID']?></span></p>
