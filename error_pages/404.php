<?php $root = realpath($_SERVER["DOCUMENT_ROOT"]) ?>
<!DOCTYPE html>
<html class="no-js" lang="">
<head>
	<title>Not Found</title>
	<link rel="stylesheet" href="/assets/css/global.css">
	<link rel="stylesheet" href="/error_pages/error_page.css">
	<?php require_once "$root/server/header.php" ?>
</head>
<body>
	<?php
		$page_subtitle = "Not Found";
		require_once "$root/components/nav.php";
	?>

	<div class="container">
		<div class='mt-5 error-number'>404</div>
		<div class='error-name'>Not Found</div>
		<div class='mt-3 error-description'>I couldn't find the page you were looking for.<br>Please contact vita@cse.unl.edu if you think this is an error.</div>
	</div>

	<?php require_once "$root/server/footer.php" ?>
</body>
</html>