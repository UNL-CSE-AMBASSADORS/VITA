<?php

define('PROD', false);

if(!PROD){
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
}


try {
	$db_user = 'root';
	$db_pass = 'root';

	$DB_CONN = new PDO('mysql:host=127.0.0.1;dbname=vita;', $db_user, $db_pass);
} catch (Exception $e) {
	print_r($e);
}
