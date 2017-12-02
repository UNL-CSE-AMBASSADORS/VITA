<?php $root = realpath($_SERVER["DOCUMENT_ROOT"]) ?>
<!DOCTYPE html>
<html class="no-js" lang="">
<head>
	<title>Server Error</title>
	<?php require_once "$root/server/header.php" ?>
</head>
<body>
	<?php
		$page_subtitle = "Server Error";
		require_once "$root/components/nav.php";
	?>

	<div class="container">
		The server encountered an error while trying to help you :(
	</div>
	<div class="container">
		Please try again in a few minutes, and if the problem persists, contact vita@cse.unl.edu
	</div>

	<?php require_once "$root/server/footer.php" ?>
</body>
</html>