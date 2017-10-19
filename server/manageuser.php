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
        (SELECT COUNT(*) FROM userpermission WHERE userId = ? AND permissionId = p.permissionId) as hasPermission
	FROM permission p");

	$stmt->execute(array($userId));

	$options = array();

	$count = 0;
	while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		if($row['hasPermission']){
			$selected = 'selected=true';
			$count++;
		}else{
			$selected = '';
		}
		array_push($options, "<option value=".$row['permissionId']." $selected>".$row['name']."</option>");
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

	$DB_CONN->beginTransaction();

	$stmt = $DB_CONN->prepare("DELETE FROM UserPermission 
		WHERE userId = ?");
	$stmt->execute(array($data['userId']));

	//if not set then we're just removing existing permissions, not adding new ones
	if(isset($data['permissionIdArr'])){
		$stmt = $DB_CONN->prepare("INSERT INTO UserPermission 
				(userId, permissionId, createdBy)
			VALUES 
				(?, ?, ?)
			ON DUPLICATE KEY 
			UPDATE createdBy = ?");

		foreach ($data['permissionIdArr'] as $permissionId) {
			$stmt->execute(array(
				$data['userId'],
				$permissionId, 
				$USER->getUserId(), 
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