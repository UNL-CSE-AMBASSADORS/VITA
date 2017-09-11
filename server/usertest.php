<?

session_start();
require_once 'user.class.php';

$USER = new User();

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
?>
<!DOCTYPE html>
<html class='no-js theme-light' lang="">
<head>
	<title>Test</title>
	<?php require_once "$root/server/header.php" ?>
</head>
<body>

<p>Am I logged in? <span><?=($USER->isLoggedIn() ? 'Yes' : 'No')?></span></p>

<p>Who am I? <span><?@print $USER->getUserId() ?: 'N/A'?></span></p>

<p>Do I have edit_user_permission? <span><?@print $USER->hasPermission('edit_user_permission') ? 'Yes': 'No'?></span></p>

<p>Do I have edit_user_TYPOrmission? <span><?@print $USER->hasPermission('edit_user_TYPOrmission') ? 'Yes': 'No'?></span></p>

<p>$_SESSION['LAST_ACTIVITY'] (Unix timestamp): <span><?=$_SESSION['LAST_ACTIVITY']?></span></p>

<p>$_SESSION['USER__ID']: <span><?=$_SESSION['USER__ID']?></span></p>