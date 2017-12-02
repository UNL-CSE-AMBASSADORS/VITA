<?php $root = realpath($_SERVER["DOCUMENT_ROOT"]) ?>
<!DOCTYPE html>
<html class="no-js" lang="">
<head>
	<title>Not Found</title>
	<?php require_once "$root/server/header.php" ?>
</head>
<body>
	<?php
		$page_subtitle = "Not Found";
		require_once "$root/components/nav.php";
	?>

	<div class="container">
		I couldn't find the page you were looking for.
	</div>
	<div class="container">
		If you think this is a mistake, please contact vita@cse.unl.edu
	</div>

	<?php require_once "$root/server/footer.php" ?>
</body>
</html>