<!-- TODO: insert authentication logic here -->

<?php
if (!isset($page_title)) {
	$page_title = "VITA";
}

if (!isset($page_subtitle)) {
	$page_subtitle = "Center for People in Need";
}
?>

<div class='section theme-dark nav'>
	<div class='sub-section-full'>
		<div class='wrap-left menu-icon-wrap'>
			<i class='menu-icon material-icons'>menu</i>
			<!-- <i class='menu-icon material-icons'>&#xE5D2;</i> -->
		</div>
		<div class='wrap nav-title-section'>
			<div id='page-title'><?php echo $page_title ?></div>
			<div id='page-subtitle'><?php echo $page_subtitle ?></div>
		</div>
	</div>
</div>
