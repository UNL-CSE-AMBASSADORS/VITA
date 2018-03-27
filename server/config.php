<?php
	define('PROD', false);
	define('MY_EXCEPTION', 99999);
	define('NOREPLY_EMAIL', 'noreply@vita.unl.edu');

	if(!PROD){
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
	} else {
		error_reporting(0);
	}

	try {
		$db_user = 'root';
		$db_pass = 'root';

		$DB_CONN = new PDO('mysql:host=127.0.0.1;dbname=vita;', $db_user, $db_pass);
	} catch (Exception $e) {
		print_r($e);
	}
