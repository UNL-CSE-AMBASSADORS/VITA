<?php

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once "$root/server/config.php";
if(isset($_REQUEST['callback'])){
	switch ($_REQUEST['callback']) {
		case 'login':
			login($_REQUEST);
			break;
		case 'logout':
			logout();
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
	GLOBAL $DB_CONN, $root;
	require_once "$root/server/Login.class.php";

	return new Login($DB_CONN, 'VITA', 'VITA', '/index.php', '/register/index.php', 'noreply@vita.unl.edu', 'vita@cse.unl.edu');
}

function login($params){
	$LOGIN = getLoginClass();

	print $LOGIN->login($params['email'], $params['password']);
	exit;
}

function logout() {
	$LOGIN = getLoginClass();

	print $LOGIN->logout();
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
