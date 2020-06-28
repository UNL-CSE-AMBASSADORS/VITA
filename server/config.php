<?php
	define('PROD', false);
	define('MY_EXCEPTION', 99999);
	define('NOREPLY_EMAIL', 'noreply@vita.unl.edu');
	define('AZURE_BLOB_STORAGE_CONNECTION_STRING', '');
	define('UIOWA_AZURE_BLOB_STORAGE_CONNECTION_STRING', '');

	error_reporting(E_ALL);
	if (PROD) {
		ini_set('display_errors', 0);
		ini_set('display_startup_errors', 0);
		ini_set('log_errors', 1);
		ini_set('error_log', './var/log/php-error.log');
	} else {
		ini_set('display_errors', 1);
	}

	try {
		$db_host = '127.0.0.1';
		$db_name   = 'vita';
		$db_user = 'root';
		$db_pass = 'root';
		$db_charset = 'utf8mb4';

		$data_source_name = "mysql:host=$db_host;dbname=$db_name;charset=$db_charset";
		$options = [
			PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
		];

		$DB_CONN = new PDO($data_source_name, $db_user, $db_pass, $options);
	} catch (Exception $e) {
		echo "<h3>Unable to Connect to Database</h3>";
	} finally {
		unset($db_pass);
		unset($db_host);
		unset($db_name);
		unset($db_user);
		unset($db_charset);
		unset($data_source_name);
	}
