<?php

require_once 'config.php';
require_once "user.class.php";

$USER = new User();

if (!$USER->hasPermission('edit_user_permission')) {
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

	$stmt = $DB_CONN->prepare("SELECT userId, firstName, lastName, email, preparesTaxes, archived 
		FROM vita.user
		ORDER BY firstName");

	$stmt->execute(array());

	$thead = "<thead><tr><th>Name</th><th>Email</th><th>Permissions</th><th>Count</th></tr>";
	$tbody = "<tbody>";
	while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		$optionList = getUserPermissionOptionList($row['userId']);

		$tbody.= "<tr data-user-id='".$row['userId']."'>";
		$tbody.= "<td>".$row['firstName']." ".$row['lastName']."</td>";
		$tbody.= "<td>".$row['email']."</td>";
		$tbody.= "<td><select class='userPermissionList selectpicker' multiple=true>".implode('', $optionList['options'])."</select></td>";
		$tbody.= "<td>".$optionList['hasPermissionCount']."</td>";
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
        (SELECT userPermissionId FROM userpermission WHERE userId = ? AND permissionId = p.permissionId) as userPermissionId
	FROM permission p");

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
		array_push($options, "<option $data value=".$row['permissionId']." $selected>".$row['name']."</option>");
	}

	return array(
		'options' => $options,
		'hasPermissionCount' => $count
	);
}

function updateUserPermissions($data){
	GLOBAL $DB_CONN;
	GLOBAL $USER;


	$response = array();
	$response['success'] = true;

	if($data['userId'] == $USER->getUserId()){
		$stmt = $DB_CONN->prepare("SELECT lookupName 
			FROM vita.permission
				INNER JOIN vita.userpermission ON permission.permissionId = userpermission.permissionId
			WHERE userPermissionId = ?");

		foreach ($data['removePermissionArr'] as $userPermissionId) {
			$stmt->execute(array($userPermissionId));
			$lookupName = $stmt->fetch(PDO::FETCH_ASSOC)['lookupName'];

			if($lookupName == 'edit_user_permission'){
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

function addUser($data){
	GLOBAL $DB_CONN;

	$response = array();
	$response['success'] = true;

	$stmt = $DB_CONN->prepare("INSERT INTO User 
			(firstName, lastName, email, phoneNumber, preparesTaxes)
		VALUES 
			(?, ?, ?, ?, ?)");

	$res = $stmt->execute(array(
		$data['firstName'],
		$data['lastName'],
		$data['email'],
		$data['phone'],
		$data['prepareTaxes']
	));

	$response['success'] = !!$res;

	print json_encode($response);
}