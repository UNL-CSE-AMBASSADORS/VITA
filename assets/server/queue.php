<?php
	$server_name = 'localhost';
	$username = 'username';
	$password = 'password';
	$db_name = 'myDB';

	$conn = new mysqli($server_name, $username, $password, $db_name);
	if ($conn->connect_error) {
		die('Unable to connect to queue');
	}

	//TODO make this handle multiple locations if necessary
	$stmt = $conn->prepare('SELECT id, time, firstName, lastName FROM appointment
		where (date = getdate() && archived = 0)');
	$stmt->execute();
	$results = $stmt->fetchAll();

	// We must only display the first letter of the last name, we do this server-side since we can't disclose the data client-side
	$appointments = [];
	foreach($results as $result) {
		$result['lastName'] = substr($result['lastName'], 0, 1);
		$appointments[] = $result;
	}

	echo json_encode($appointments);

	$stmt->close();
	$conn->close();
?>
