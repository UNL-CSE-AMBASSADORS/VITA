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
		You are not authorized to access this page.
	</div>

	<?php require_once "$root/server/footer.php" ?>
</body>
</html>