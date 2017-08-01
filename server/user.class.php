<?
require_once 'login.class.php';
require_once 'callbacks.php';
require_once 'config.php';

define('PrivilegeControl', 'PrivilegeControl');
define('UserShiftControl', 'UserShiftControl');
define('AppointmentControl', 'AppointmentControl');


/**
* Class for easily checking user privileges
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
	* Checks whether the user has specifed privilege
	* 
	* @return boolean
	*/ 
	public function hasPrivilege($privilegeTag){
		GLOBAL $DB_CONN;

		$userId = $this->getUserId();

		$query = "SELECT userId 
			FROM userPrivilege
				INNER JOIN privilege ON privilege.privilegeId = userprivilege.userPrivilegeId
			WHERE 1=1
				AND userId = ?
				AND tag LIKE ?";


		$stmt = $DB_CONN->prepare($query);
		$stmt->execute(array($userId,$privilegeTag));

		$results = $stmt->fetchAll();

		if(empty($results)){
			return false;
		}else{
			return true;
		}
	}
}