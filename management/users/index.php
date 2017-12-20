<?php

$root = realpath($_SERVER["DOCUMENT_ROOT"]);

require_once "$root/server/user.class.php";
$USER = new User();
if (!$USER->hasPermission('edit_user_permissions')) {
	header("Location: /unauthorized");
	die();
}

?>

<!DOCTYPE html>
<html class="no-js" lang="">
<head>
	<title>User Management</title>
	<?php require_once "$root/server/header.php" ?>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css" />
	<link rel="stylesheet" href="users.css" />
</head>
<body>
	<?php require_once "$root/components/nav.php"; ?>
	<div class="container">
		<div class="row pt-5">
			<div class="col-md-9">
				<h1>User Management</h1>
			</div>
			<div class="col-md-3">
				<button class="btn btn-default pull-right" id="btn-add-user">Add User</button>
			</div>
		</div>
		<div class="row" id="user-management-table-row">
			<div class="col-md-12">
				<table class="table table-condensed table-hover" id="user-management-table">
					<!-- table data injected through JS -->
				</table>
			</div>
		</div>
	</div>
	<div class="hide">
		<div class="modal" id="add-user-modal" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Add a new user</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<form id="add-user-form">
						<div class="modal-body">
							<section class="form-group">
								<label for="firstName">First Name:</label>
								<input type="text" class="form-control" id="firstName">
							</section>
							<section class="form-group">
								<label for="lastName">Last Name:</label>
								<input type="text" class="form-control" id="lastName">
							</section>
							<section class="form-group">
								<label for="email">Email Address:</label>
								<input type="text" class="form-control" id="email" placeholder="student@huskers.unl.edu">
							</section>
							<section class="form-group">
								<label for="phone">Phone Number:</label>
								<input type="text" class="form-control" id="phone">
							</section>
							<section class="form-group">
								<label for="prepareTaxes">Prepares Taxes: <input type="checkbox" id="prepareTaxes">
								</label>
							</section>
						</div>
						<div class="modal-footer">
							<button type="submit" class="btn btn-primary">Add User</button>
							<button type="button" class="btn btn-secondary" data-dismiss="modal" data-target="#add-user-modal">Close</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<?php require_once "$root/server/footer.php" ?>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>
	<script src="/dist/management/users/users.js"></script>
</body>
</html>
