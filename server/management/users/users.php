<?php

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once "$root/server/config.php";
require_once "$root/server/user.class.php";

$USER = new User();

if (!$USER->isLoggedIn() || !$USER->hasPermission('edit_user_permissions')) {
	header("Location: /unauthorized");
	die();
}

if(isset($_REQUEST['action'])){
	switch ($_REQUEST['action']) {
		case 'getUserTable':
			getUserTable($_REQUEST);
			break;
		case 'updateUserPermissions':
			updateUserPermissions($_REQUEST);
			break;
		case 'updateUserAbilities':
			updateUserAbilities($_REQUEST);
			break;
		case 'addUser':
			addUser($_REQUEST);
			break;
		case 'getUserInformation':
			getUserInformation($_GET['userId']);
			break;
		case 'updateUserInformation':
			updateUserInformation($_POST['userId'], $_POST['firstName'], $_POST['lastName'], $_POST['email'], $_POST['phoneNumber']);
			break;
		default:
			die('Unregistered command. This instance has been reported.');
			break;
	}
}

function getUserTable($data) {
	GLOBAL $DB_CONN;

	$response = array();
	$response['success'] = true;

	$stmt = $DB_CONN->prepare('SELECT userId, firstName, lastName, email, archived 
		FROM User
		WHERE archived = FALSE
		ORDER BY firstName, lastName');

	$stmt->execute(array());

	$thead = '<thead><tr><th>Name</th><th>Email</th><th>Permissions</th><th>Cerifications</th><th>Edit</th></tr></thead>';
	$tbody = '<tbody>';
	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$permissionsList = getUserPermissionOptionList($row['userId']);
		$abilitiesList = getUserAbilityOptionList($row['userId']);

		$tbody.= "<tr data-user-id='".$row['userId']."'>";
		$tbody.= "<th data-header='Name'>".$row['firstName']." ".$row['lastName']."</th>";
		$tbody.= "<td data-header='Email'>".$row['email']."</td>";
		$tbody.= "<td data-header='Permissions'><select class='userPermissionList userPermissionsSelectPicker' data-style='wdn-button' multiple=true>".implode('', $permissionsList)."</select></td>";
		$tbody.= "<td data-header='Certifications'><select class='userAbilityList userAbilitiesSelectPicker' data-style='wdn-button' multiple=true>".implode('', $abilitiesList)."</select></td>";		
		$tbody.= "<td data-header='Edit'><a href='#edit-user-modal' class='userEditButton'>Edit</a></td>";	
		$tbody.= "</tr>";
	}
	$tbody.= '</tbody>';

	$response['table'] = $thead.$tbody;

	echo json_encode($response);
}

// Returns string of html <option> elments with all permissions
function getUserPermissionOptionList($userId) {
	GLOBAL $DB_CONN;

	$stmt = $DB_CONN->prepare('SELECT permissionId, name, description, lookupName,
		(SELECT userPermissionId FROM UserPermission WHERE userId = ? AND permissionId = p.permissionId) as userPermissionId
	FROM Permission p');

	$stmt->execute(array($userId));

	$options = array();

	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$selected = '';
		$data = '';
		if ($row['userPermissionId']) {
			$selected = 'selected=true';
			$data = "data-userPermissionId='".$row['userPermissionId']."'";
		}
		$options[] = "<option $data value=".$row['permissionId']." $selected>".$row['name']."</option>";
	}

	return $options;
}

// Returns string of html <option> elments with all abilities
function getUserAbilityOptionList($userId) {
	GLOBAL $DB_CONN;

	$stmt = $DB_CONN->prepare('SELECT abilityId, name, description, lookupName, 
		(SELECT userAbilityId FROM UserAbility WHERE userId = ? AND UserAbility.abilityId = Ability.abilityId) as userAbilityId
	FROM Ability
	ORDER BY !verificationRequired');

	$stmt->execute(array($userId));

	$options = array();
	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$selected = '';
		$data = '';
		if ($row['userAbilityId']) {
			$selected = 'selected=true';
			$data = "data-userAbilityId='".$row['userAbilityId']."'";
		}
		$options[] = "<option $data value=".$row['abilityId']." $selected>".$row['name']."</option>";
	}

	return $options;
}

function updateUserPermissions($data){
	GLOBAL $DB_CONN, $USER;

	$response = array();
	$response['success'] = true;

	// This will disallow a person from removing their own permission that lets them edit permissions.
	if($data['userId'] === $USER->getUserId()){
		$stmt = $DB_CONN->prepare('SELECT lookupName 
			FROM Permission
				INNER JOIN UserPermission ON Permission.permissionId = UserPermission.permissionId
			WHERE userPermissionId = ?');

		foreach ($data['removePermissionArr'] as $userPermissionId) {
			$stmt->execute(array($userPermissionId));
			$lookupName = $stmt->fetch(PDO::FETCH_ASSOC)['lookupName'];

			if($lookupName === 'edit_user_permissions'){
				$firstName = $USER->getUserDetails()['firstName'];

				$response['success'] = false;
				$response['error'] = "I'm sorry, $firstName, I'm afraid I can't do that.";
				print json_encode($response);
				die();
			}
		}
	}

	$DB_CONN->beginTransaction();

	if(isset($data['removePermissionArr'])){
		$stmt = $DB_CONN->prepare('DELETE FROM UserPermission WHERE userPermissionId = ?');

		foreach ($data['removePermissionArr'] as $userPermissionId) {
			$stmt->execute(array($userPermissionId));
		}
	}

	if(isset($data['addPermissionArr'])){
		$stmt = $DB_CONN->prepare('INSERT INTO UserPermission (userId, permissionId, createdBy)
			VALUES (?, ?, ?)');

		foreach ($data['addPermissionArr'] as $permissionId) {
			$stmt->execute(array(
				$data['userId'],
				$permissionId, 
				$USER->getUserId()
			));
		}
	}

	$DB_CONN->commit();

	echo json_encode($response);
}

function updateUserAbilities($data) {
	GLOBAL $DB_CONN, $USER;

	$response = array();
	$response['success'] = true;

	$DB_CONN->beginTransaction();
	
	if (isset($data['removeAbilityArr'])) {
		$stmt = $DB_CONN->prepare('DELETE FROM UserAbility WHERE userAbilityId = ?');

		foreach ($data['removeAbilityArr'] as $userAbilityId) {
			$stmt->execute(array($userAbilityId));
		}
	}

	if (isset($data['addAbilityArr'])){
		$stmt = $DB_CONN->prepare('INSERT INTO UserAbility (userId, abilityId, createdBy)
			VALUES (?, ?, ?)');

		foreach ($data['addAbilityArr'] as $abilityId) {
			$stmt->execute(array(
				$data['userId'],
				$abilityId, 
				$USER->getUserId()
			));
		}
	}

	$DB_CONN->commit();

	echo json_encode($response);
}

function addUser($data){
	GLOBAL $DB_CONN;

	$response = array();
	$response['success'] = true;

	try {
		$stmt = $DB_CONN->prepare('INSERT INTO User (firstName, lastName, email, phoneNumber)
			VALUES (?, ?, ?, ?)');

		$res = $stmt->execute(array(
			$data['firstName'],
			$data['lastName'],
			trim($data['email']),
			$data['phoneNumber']
		));

		if ($res == 0) {
			throw new Exception('Unable to create the user, a user with that email may already exist.', MY_EXCEPTION);
		}

		$response['success'] = true;		
	} catch (Exception $e) {
		$response['success'] = false;
		$response['error'] = $e->getCode() === MY_EXCEPTION ? $e->getMessage() : 'Sorry, there was an error reaching the server. Please try again later.';
	}

	echo json_encode($response);
}

function getUserInformation($userId) {
	GLOBAL $DB_CONN;

	$response = array();
	$response['success'] = true;

	try {
		$query = 'SELECT firstName, lastName, email, phoneNumber
			FROM User
			WHERE userId = ?';
		$stmt = $DB_CONN->prepare($query);
		$stmt->execute(array($userId));
		$userData = $stmt->fetch();

		$userExists = (bool)$userData !== false;
		$response['user'] = array();
		if ($userExists) {
			$response['user'] = array(
				'firstName' => $userData['firstName'],
				'lastName' => $userData['lastName'],
				'email' => $userData['email'],
				'phoneNumber' => $userData['phoneNumber']
			);
		}

		$response['success'] = true;
	} catch (Exception $e) {
		$response['success'] = false;
		$response['error'] = $e->getCode() === MY_EXCEPTION ? $e->getMessage() : 'Sorry, there was an error reaching the server. Please try again later.';
	}

	echo json_encode($response);
}

function updateUserInformation($userId, $newFirstName, $newLastName, $newEmail, $newPhoneNumber) {
	GLOBAL $DB_CONN;

	$response = array();
	$response['success'] = true;

	try {
		$query = 'UPDATE User
			SET firstName = ?, lastName = ?, email = ?, phoneNumber = ?
			WHERE userId = ?';
		$stmt = $DB_CONN->prepare($query);
		$stmt->execute(array(
			$newFirstName,
			$newLastName,
			trim($newEmail),
			$newPhoneNumber,
			$userId
		));
	} catch (Exception $e) {
		$response['success'] = false;
		$response['error'] = $e->getCode() === MY_EXCEPTION ? $e->getMessage() : 'Sorry, there was an error reaching the server. Please try again later.';
	}

	echo json_encode($response);
}