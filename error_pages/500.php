<?php $root = realpath($_SERVER["DOCUMENT_ROOT"]) ?>
<!DOCTYPE html>
<html class="no-js" lang="">
<head>
	<title>Server Error</title>
	<link rel="stylesheet" href="/dist/assets/css/global.css">
	<link rel="stylesheet" href="/dist/error_pages/error_page.css">
	<?php require_once "$root/server/header.php" ?>
</head>
<body>
	<?php
		$page_subtitle = "Server Error";
		require_once "$root/components/nav.php";
	?>

	<div class="container">
		<div class='mt-5 error-number'>500</div>
		<div class='error-name'>Server Error</div>
		<div class='mt-3 error-description'>The server encountered an error trying to help you.<br>Please try again later, and if the problem persists, contact vita@cse.unl.edu</div>
	</div>

	<?php require_once "$root/server/footer.php" ?>
</body>
</html>