<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once "$root/server/Login.class.php";
require_once "$root/server/config.php";
require_once "$root/server/callbacks.php";



/**
* Class for easily checking user permissions
* Simply instantiate and use
*/

class User
{

	/**
	* Returns user id, will return FALSE if not logged in!
	*
	* @return int
	*/
	public function getUserId(){
		$LOGIN = getLoginClass();

		## Check Login Status
		if(!$LOGIN->checkLogin()){
			$LOGIN->logout();
			return false;
		}

		return $_SESSION['USER__ID'];
	}


	/**
	* Checks whether the user is still lagged in
	*
	* @return boolean
	*/
	public function isLoggedIn(){
		$LOGIN = getLoginClass();

		return $LOGIN->checkLogin();
	}

	/**
	* Checks whether the user has specifed permission
	*
	* @return boolean
	*/
	public function hasPermission($permissionLookupName){
		GLOBAL $DB_CONN;

		$userId = $this->getUserId();

		$query = "SELECT userId
			FROM UserPermission
				INNER JOIN Permission ON Permission.permissionId = UserPermission.permissionId
			WHERE 1=1
				AND userId = ?
				AND lookupName LIKE ?";


		$stmt = $DB_CONN->prepare($query);
		$stmt->execute(array($userId,$permissionLookupName));

		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return !empty($results);
	}

	/**
	* Returns basic information about the logged in user
	*
	* @return boolean
	*/
	public function getUserDetails(){
		GLOBAL $DB_CONN;

		$userId = $this->getUserId();

		$query = "SELECT firstName, lastName, email, phoneNumber
			FROM User
			WHERE 1=1
				AND userId = ?";


		$stmt = $DB_CONN->prepare($query);
		$stmt->execute(array($userId));

		return $stmt->fetch(PDO::FETCH_ASSOC);
	}
}
