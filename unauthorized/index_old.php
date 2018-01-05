<?php $root = realpath($_SERVER["DOCUMENT_ROOT"]) ?>
<!DOCTYPE html>
<html class="no-js" lang="">
<head>
	<title>Unauthorized</title>
	<link rel="stylesheet" href="/dist/assets/css/global.css">
	<link rel="stylesheet" href="/dist/error_pages/error_page.css">
</head>
<body>
	<?php
		$page_subtitle = "Unauthorized";
		require_once "$root/components/nav.php";
	?>

	<div class="container">
		<div class='mt-5 error-number'>401</div>
		<div class='error-name'>Unauthorized</div>
		<div class='mt-3 error-description'>You are not authorized to access this page.<br>Contact your site coordinator if you feel this to be an error.</div>
	</div>
</body>
</html>