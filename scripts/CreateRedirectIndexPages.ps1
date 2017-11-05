# You may have to run the Unblock-File INSERT_RELATIVE_FILE_PATH_TO_THIS_FILE_HERE
# If powershell is saying it won't run this due to a security risk

# Recurse the entire code directory and create the index.php redirect file if necessary
$path = '../'
foreach ($directory in Get-ChildItem -recurse -dir $path) {
	if (!(Test-Path ($directory.FullName + '\index.php'))) {
		New-Item ($directory.FullName + '\index.php') -type file -value @"
<?php 
`$root = realpath(`$_SERVER["DOCUMENT_ROOT"]);
require_once "`$root/server/redirect.php";
?>
"@
	} 
} 