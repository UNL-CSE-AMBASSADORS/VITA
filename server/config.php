<?

try {
	$db_user = 'root';
	$db_pass = 'root';

	$DB_CONN = new PDO('mysql:host=127.0.0.1;dbname=vita;', $db_user, $db_pass);
} catch (Exception $e) {
	print_r($e);
}