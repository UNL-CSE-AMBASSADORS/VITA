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
			getUserTable();
			break;
		case 'updateUserPermissions':
			updateUserPermissions($_REQUEST);
			break;
		case 'updateUserAbilities':
			updateUserAbilities($_POST['userId'], $_POST['addAbilityArr'], $_POST['removeAbilityArr']);
			break;
		case 'addUser':
			addUser($_POST['firstName'], $_POST['lastName'], $_POST['email'], $_POST['phoneNumber']);
			break;
		default:
			die('Unregistered command. This instance has been reported.');
			break;
	}
}

function getUserTable() {
	GLOBAL $DB_CONN;

	$response = array();
	$response['success'] = true;

	$stmt = $DB_CONN->prepare("SELECT userId, firstName, lastName, email, archived 
		FROM User
		WHERE archived = FALSE
		ORDER BY firstName, lastName");

	$stmt->execute(array());

	$thead = "<thead><tr><th>Name</th><th>Email</th><th>Permissions</th><th>Cerifications</th><th>Permissions Count</th></tr>";
	$tbody = "<tbody>";
	while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		$permissionsList = getUserPermissionOptionList($row['userId']);
		$abilitiesList = getUserAbilityOptionList($row['userId']);

		$tbody.= "<tr data-user-id='".$row['userId']."'>";
		$tbody.= "<th data-header='Name'>".$row['firstName']." ".$row['lastName']."</th>";
		$tbody.= "<td data-header='Email'>".$row['email']."</td>";
		$tbody.= "<td data-header='Permissions'><select class='userPermissionList userPermissionsSelectPicker' data-style='wdn-button' multiple=true>".implode('', $permissionsList['options'])."</select></td>";
		$tbody.= "<td data-header='Certifications'><select class='userAbilityList userAbilitiesSelectPicker' data-style='wdn-button' multiple=true>".implode('', $abilitiesList)."</select></td>";		
		$tbody.= "<td data-header='Permissions Count'>".$permissionsList['hasPermissionCount']."</td>";
		$tbody.= "</tr>";
	}
	$tbody.= "</tbody>";
	$thead.= "</tr></thead>";

	$response['table'] = $thead.$tbody;

	print json_encode($response);
}

// Returns string of html <option> elments with all permissions
function getUserPermissionOptionList($userId){
	GLOBAL $DB_CONN;

	$stmt = $DB_CONN->prepare("SELECT 
		permissionId,
		name,
		description,
		lookupName,
		(SELECT userPermissionId FROM UserPermission WHERE userId = ? AND permissionId = p.permissionId) as userPermissionId
	FROM Permission p");

	$stmt->execute(array($userId));

	$options = array();

	$count = 0;
	while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		if($row['userPermissionId']){
			$selected = 'selected=true';
			$data = "data-userPermissionId='".$row['userPermissionId']."'";
			$count++;
		}else{
			$selected = '';
			$data = '';
		}
		$options[] = "<option $data value=".$row['permissionId']." $selected>".$row['name']."</option>";
	}

	return array(
		'options' => $options,
		'hasPermissionCount' => $count
	);
}

// Returns string of html <option> elments with all abilities
function getUserAbilityOptionList($userId) {
	GLOBAL $DB_CONN;

	$stmt = $DB_CONN->prepare("SELECT 
		abilityId, 
		name, 
		description, 
		lookupName, 
		(SELECT userAbilityId FROM UserAbility WHERE userId = ? AND UserAbility.abilityId = Ability.abilityId) as userAbilityId
	FROM Ability
	ORDER BY !verificationRequired");

	$stmt->execute(array($userId));

	$options = array();
	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		if ($row['userAbilityId']) {
			$selected = 'selected=true';
			$data = "data-userAbilityId='".$row['userAbilityId']."'";
		} else {
			$selected = '';
			$data = '';
		}
		$options[] = "<option $data value=".$row['abilityId']." $selected>".$row['name']."</option>";
	}

	return $options;
}

function updateUserPermissions($userId, $addPermissionArr, $removePermissionArr){
	GLOBAL $DB_CONN, $USER;

	$response = array();
	$response['success'] = true;

	// This will disallow a person from removing their own permission that lets them edit permissions.
	if($userId === $USER->getUserId()){
		$stmt = $DB_CONN->prepare("SELECT lookupName 
			FROM Permission
				INNER JOIN UserPermission ON Permission.permissionId = UserPermission.permissionId
			WHERE userPermissionId = ?");

		foreach ($removePermissionArr as $userPermissionId) {
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

	if(isset($removePermissionArr)){
		$stmt = $DB_CONN->prepare("DELETE FROM UserPermission WHERE userPermissionId = ?");

		foreach ($removePermissionArr as $userPermissionId) {
			$stmt->execute(array($userPermissionId));
		}
	}

	if(isset($addPermissionArr)){
		$stmt = $DB_CONN->prepare("INSERT INTO UserPermission (userId, permissionId, createdBy) 
			VALUES  (?, ?, ?)");

		foreach ($addPermissionArr as $permissionId) {
			$stmt->execute(array(
				$userId,
				$permissionId, 
				$USER->getUserId()
			));
		}
	}

	$DB_CONN->commit();

	print json_encode($response);
}

function updateUserAbilities($userId, $addAbilityArr, $removeAbilityArr) {
	GLOBAL $DB_CONN, $USER;

	$response = array();
	$response['success'] = true;

	$DB_CONN->beginTransaction();
	
	if (isset($removeAbilityArr)) {
		$stmt = $DB_CONN->prepare('DELETE FROM UserAbility WHERE userAbilityId = ?');

		foreach ($removeAbilityArr as $userAbilityId) {
			$stmt->execute(array($userAbilityId));
		}
	}

	if (isset($addAbilityArr)){
		$stmt = $DB_CONN->prepare('INSERT INTO UserAbility (userId, abilityId, createdBy)
			VALUES (?, ?, ?)');

		foreach ($addAbilityArr as $abilityId) {
			$stmt->execute(array(
				$userId,
				$abilityId, 
				$USER->getUserId()
			));
		}
	}

	$DB_CONN->commit();

	print json_encode($response);
}

function addUser($firstName, $lastName, $email, $phoneNumber){
	GLOBAL $DB_CONN;

	$response = array();
	$response['success'] = true;

	try {
		$stmt = $DB_CONN->prepare('INSERT INTO User (firstName, lastName, email, phoneNumber)
			VALUES (?, ?, ?, ?)');

		$result = $stmt->execute(array(
			$firstName,
			$lastName,
			trim($email),
			$phoneNumber
		));

		if ($result == 0) {
			throw new Exception('Unable to create the user, a user with that email may already exist.', MY_EXCEPTION);
		}
	} catch (Exception $e) {
		$response['success'] = false;
		$response['error'] = $e->getCode() === MY_EXCEPTION ? $e->getMessage() : 'Sorry, there was an error reaching the server. Please try again later.';
	}

	print json_encode($response);
}