<?php $root = realpath($_SERVER["DOCUMENT_ROOT"]) ?>
<!DOCTYPE html>
<html class="no-js" lang="">
<head>
	<title>Unauthorized</title>
	<?php require_once "$root/server/header.php" ?>
</head>
<body>
	<?php
		$page_subtitle = "Unauthorized";
		require_once "$root/components/nav.php";
	?>

	<div class="container">
		<div class='h1 mt-5'>401 - Unauthorized</div>
		<div class='h5'>You are not authorized to access this page. Contact your site coordinator if you feel this to be in error.</div>
	</div>

	<?php require_once "$root/server/footer.php" ?>
</body>
</html>