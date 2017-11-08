<?php

require_once 'config.php';
if(isset($_REQUEST['callback'])){
	switch ($_REQUEST['callback']) {
		case 'login':
			login($_REQUEST);
			break;
		case 'register':
			register($_REQUEST);
			break;
		case 'password_reset':
			passwordReset($_REQUEST);
			break;
		default:
			# code...
			break;
	}
}

function getLoginClass(){
	global $DB_CONN;
	require_once 'Login.class.php';

	return new Login($DB_CONN, 'VITA', 'VITA', '/index.php', '/register/index.php', 'noreply@vita-lincoln.org', 'hmmm@hmmm.com');
}

function login($params){
	$LOGIN = getLoginClass();

	print $LOGIN->login($params['email'], $params['password']);
	exit;
}

function register($params){
	$LOGIN = getLoginClass();

	print $LOGIN->register($params['email']);
	exit;
}

function passwordReset($params){
	$LOGIN = getLoginClass();

	print $LOGIN->passwordReset($params['email'], $params['token'], $params['password'], $params['vpassword']);
	exit;
}
