<?php

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once "$root/server/config.php";
require_once "$root/server/user.class.php";

$USER = new User();

if (!$USER->hasPermission('edit_user_permissions')) {
	header("Location: /unauthorized");
	die();
}

if(isset($_REQUEST['callback'])){
	switch ($_REQUEST['callback']) {
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
		default:
			die('Unregistered command. This instance has been reported.');
			break;
	}
}

function getUserTable($data){
	GLOBAL $DB_CONN;

	$response = array();
	$response['success'] = true;

	$stmt = $DB_CONN->prepare("SELECT userId, firstName, lastName, email, archived 
		FROM User
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

function updateUserPermissions($data){
	GLOBAL $DB_CONN;
	GLOBAL $USER;

	$response = array();
	$response['success'] = true;

	// This will disallow a person from removing their own permission that lets them edit permissions.
	if($data['userId'] === $USER->getUserId()){
		$stmt = $DB_CONN->prepare("SELECT lookupName 
			FROM Permission
				INNER JOIN UserPermission ON Permission.permissionId = UserPermission.permissionId
			WHERE userPermissionId = ?");

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
		$stmt = $DB_CONN->prepare("DELETE FROM UserPermission WHERE userPermissionId = ?");

		foreach ($data['removePermissionArr'] as $userPermissionId) {
			$stmt->execute(array($userPermissionId));
		}
	}

	if(isset($data['addPermissionArr'])){
		$stmt = $DB_CONN->prepare("INSERT INTO UserPermission 
				(userId, permissionId, createdBy)
			VALUES 
				(?, ?, ?)");

		foreach ($data['addPermissionArr'] as $permissionId) {
			$stmt->execute(array(
				$data['userId'],
				$permissionId, 
				$USER->getUserId()
			));
		}
	}

	$DB_CONN->commit();

	print json_encode($response);
}

function updateUserAbilities($data) {
	GLOBAL $DB_CONN, $USER;

	$response = array();
	$response['success'] = true;

	$DB_CONN->beginTransaction();
	
	if (isset($data['removeAbilityArr'])) {
		$stmt = $DB_CONN->prepare("DELETE FROM UserAbility WHERE userAbilityId = ?");

		foreach ($data['removeAbilityArr'] as $userAbilityId) {
			$stmt->execute(array($userAbilityId));
		}
	}

	if (isset($data['addAbilityArr'])){
		$stmt = $DB_CONN->prepare("INSERT INTO UserAbility 
				(userId, abilityId, createdBy)
			VALUES 
				(?, ?, ?)");

		foreach ($data['addAbilityArr'] as $abilityId) {
			$stmt->execute(array(
				$data['userId'],
				$abilityId, 
				$USER->getUserId()
			));
		}
	}

	$DB_CONN->commit();

	print json_encode($response);
}

function addUser($data){
	GLOBAL $DB_CONN;

	$response = array();
	$response['success'] = true;

	try {
		$stmt = $DB_CONN->prepare("INSERT INTO User 
				(firstName, lastName, email, phoneNumber)
			VALUES 
				(?, ?, ?, ?)");

		$res = $stmt->execute(array(
			$data['firstName'],
			$data['lastName'],
			trim($data['email']),
			$data['phone']
		));

		if ($res == 0) {
			throw new Exception('Unable to create the user, a user with that email may already exist.', MY_EXCEPTION);
		}

		$response['success'] = true;		
	} catch (Exception $e) {
		$response['success'] = false;
		if($e->getCode() === MY_EXCEPTION){
			$response['error'] = $e->getMessage();
		}else{
			$response['error'] = 'Sorry, there was an error reaching the server. Please try again later.';
		}
	}

	print json_encode($response);
}