<?php

session_start();

/**
* Login Class
* Uses PHP session to determine whether the user's logged in
* $_SESSION['USER__ID'] has the user's id from the user table
* $_SESSION['LAST_ACTIVITY'] is a timestamp from the user's last activity, it will get updated with every call to checkLogin()
*
* Public Functions
* login($email, $password)
* logout()
* register($email)
* passwordResetRequest($email)
* passwordReset($email, $token, $password, $vpassword)
* changePassword($password, $npassword, $vpassword)
* checkLogin()
*/
class Login
{
	## Define Variables
	private $conn;

	## (String) Database with login tables, ex: EmployeePortal
	private $database;

	## (String) Name of the application, ex: Hoovestol Portal
	private $name;

	## (String) URL Paths to login, logout, etc
	private $login_url;
	private $register_and_password_reset_url;

	## (String) Contact Email address - For users to email with questions
	private $contact_email;
	private $noreply_email;

	## How long tokens are active
	public $TOKEN_THRESHOLD = 30;
	public $LOGIN_THRESHOLD = 5;
	public $SESSION_THRESHOLD = 3600; //units in seconds

	function __construct($conn, $database, $name, $login_url, $register_url, $noreply_email, $contact_email = ""){
		$this->conn = $conn;
		$this->database = $database;

		$this->login_url = $login_url;
		$this->register_and_password_reset_url = $register_url;

		$this->contact_email = $contact_email;
		$this->noreply_email = $noreply_email;

	}

	/**
	* Login
	*
	* Logs in the user
	*
	* @param string $email user's email
	* @param string $password password
	*
	* @return response
	*/
	public function login($email, $password) {

		## Define Globals
		$response = array();
		$response['success'] = false;

		try{

			## Lowercase Email Input
			$email = strtolower($email);

			## Verify Form Fields
			if(!isset($email) || !isset($password)){

				throw new Exception("Please provide both your email address and password.", MY_EXCEPTION);

			}

			$stmt = $this->conn->prepare(
				"SELECT
					u.userId, u.email,
					l.password, l.failedLoginCount as failed_login_count,
					CASE WHEN DATE_ADD(l.lockoutTime, INTERVAL 30 MINUTE) > CURRENT_TIMESTAMP THEN 1 ELSE 0 END AS locked_out
				FROM Login l
					INNER JOIN User u ON u.userId = l.userId
				WHERE u.archived = 0
					AND u.email = ?");
			$stmt->execute(array($email));
			$results = $stmt->fetchAll();

			## Make Sure Account Exists
			if(count($results) != 1){
				throw new Exception("Email Address and/or Password is incorrect!", MY_EXCEPTION);
			}

			## Get User Info
			$row = $results[0];

			$userId = $row['userId'];

			$dbemail = strtolower($row['email']);
			$dbpassword = $row['password'];
			$locked_out = $row['locked_out'];
			$failed_login_count = $row['failed_login_count'];

			## Unlock Account If Needed
			if(!$locked_out && $failed_login_count >= $this->LOGIN_THRESHOLD){
				$stmt = $this->conn->prepare(
					"UPDATE Login l
					SET failed_login_count = 0
					WHERE userId = ?");
				$stmt->execute(array($userId));
				$failed_login_count = 0;
			}


			## If Not Locked Out
			if($failed_login_count < $this->LOGIN_THRESHOLD ){
				## Check Credentials
				if(($email === $dbemail)  && password_verify($password, $dbpassword)){

					## Check If Password Needs Rehashed
					if(password_needs_rehash($dbpassword, PASSWORD_BCRYPT)) {

						## Define Rehash
						$rehash = password_hash($dbpassword, PASSWORD_BCRYPT);

						## Create Statement
						$stmt = $this->conn->prepare(
							"UPDATE Login l
							SET password = ?
							WHERE userId = ?;");
						$stmt->execute(array($rehash, $userId));
					}

					## Store Session Variables
					$_SESSION['LAST_ACTIVITY'] = time();
					$_SESSION['USER__ID'] = $userId;

					$response['success'] = true;
					$response['redirect'] = $this->login_url;

					## Record Login
					$stmt = $this->conn->prepare(
						"INSERT INTO LoginHistory
							(userId, ipAddress)
						VALUES
							(?, ?)");
					$stmt->execute(array($userId, $_SERVER['REMOTE_ADDR']));
				}else{

					## Wrong password, record failure
					$stmt = $this->conn->prepare(
						"UPDATE Login l
						SET
							failed_login_count = failed_login_count+1,
							lockout_time = CURRENT_TIMESTAMP
						WHERE userId = ?;");
					$stmt->execute(array($userId));

					throw new Exception("Email Address and/or Password is incorrect.", MY_EXCEPTION);
				}
			}else{

				## Too many failures, lock account
				$stmt = $this->conn->prepare(
					"UPDATE Login
					SET lockout_time = current_timestamp()
					WHERE userId = ?");
				$stmt->execute(array($userId));

				throw new Exception("You have entered the wrong login information too many times and you have been temporarily locked out.", MY_EXCEPTION);
			}
		}catch(Exception $e){
			$this->processException($e, $response);
			$response['success'] = false;
		}

		## Return
		return json_encode($response);
	}

	/**
	* Logout
	*
	* Kills session, logs user out
	*
	* @return response
	*/
	public function logout() {

		## Define Globals
		$response = array();
		$response['success'] = true;

		## Kill Session
		session_destroy();

		## Set Redirect
		$response['expired'] = true;
		$response['redirect'] = $this->login_url;

		## Return
		return json_encode($response);
	}

	/**
	* Register
	*
	* Registers an account for the user
	*
	* @param string $email user's email
	*
	* @return response
	*/
	public function register($email){

		## Define Globals
		$response = array();
		$response['success'] = false;

		try{
			$this->clearOldTokens();

			## Validate Email
			$email = trim(strtolower($email));
			if(!preg_match('/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/',$email)){

				throw new Exception("Please enter a valid email address.", MY_EXCEPTION);
			}

			## Verify That The Email Address Is Allowed
			$stmt = $this->conn->prepare(
				"SELECT u.email, u.firstName as first_name, u.userId as id
				FROM User u
				WHERE archived = 0
					AND email = ?");
			$stmt->execute(array($email));
			$results = $stmt->fetchAll();

			if(count($results) != 1){
				throw new Exception("Your email has not been approved for use yet. Please contact your administrator for further instruction.", MY_EXCEPTION);
			}

			## Verify That Email Doesn't Already Exist
			$row = $results[0];
			$userId = $row['id'];
			$dbemail = $row['email'];
			$dbfirst_name = $row['first_name'];

			$stmt = $this->conn->prepare(
				"SELECT *
				FROM Login
				WHERE userId = ?;");
			$stmt->execute(array($userId));

			if(count($stmt->fetchAll()) > 0){
				return $this->passwordResetRequest($email);
			}

			## Add User
			$temp_password = $this->rand_string(10);
			$password = password_hash($temp_password, PASSWORD_BCRYPT);
			$stmt = $this->conn->prepare(
				"INSERT INTO Login
					(userId, password)
				VALUES
					(?, ?)");
			$stmt->execute(array($userId, $password));

			## Set Unique Token
			$token = $this->getPasswordResetToken($userId);
			if(!$token){
				throw new Exception("Error retrieving token.", MY_EXCEPTION);
			}

			## Build Email Body
			$path = "<a href='"."https://".$_SERVER['SERVER_NAME'].$this->register_and_password_reset_url."/?token=".$token."'>"."https://".$_SERVER['SERVER_NAME'].$this->register_and_password_reset_url."/?token=".$token."</a>";

			$subject = $this->name." - Account Created";
			$mail_body = "<p>".$dbfirst_name.",</p>\r\n";
			$mail_body .= "<p>Your account has been successfully created for the ".$this->name." System.
				In order to activate your account, you must visit the link below to set up a password. This token will expire after 30 minutes if unused.</p><br />\r\n";
			$mail_body .= $path."<br /><br />\r\n";
			$mail_body .= "<font style='font-size:11px;'>Please do NOT reply to this message.</font>\r\n";
			if($this->contact_email){
				$mail_body .= "<br />You can reach us at: <a href='mailto:".$this->contact_email."'>".$this->contact_email."</a>";
			}

			## Build Email Headers
			$headers = "From: ".$this->noreply_email."\r\n";
			$headers .= 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			if(!mail($dbemail, $subject, $mail_body, $headers) && !PROD){
				print $mail_body;
			}

		}catch(Exception $e){
			$this->processException($e, $response);
		}

		## Return
		return json_encode($response);
	}

	/**
	* Password Reset Request
	*
	* Sends an a message with a password reset url to the specified email
	* Will register an account if no account is found with that email
	*
	* @param string $email user's email
	*
	* @return response
	*/
	public function passwordResetRequest($email) {

		## Define Globals
		$response = array();
		$response['success'] = false;

		try{
			$this->clearOldTokens();

			## Validate Email
			$email = trim(strtolower($email));
			if(!preg_match('/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/',$email)){

				throw new Exception("Please enter a valid email address", MY_EXCEPTION);
			}

			## Verify That Email Exists
			$stmt = $this->conn->prepare(
				"SELECT u.email, u.firstName, u.userId
				FROM Login l
					INNER JOIN User u ON u.userId = l.userId
				WHERE u.archived = 0
					AND u.email = ?");
			$stmt->execute(array($email));
			$results = $stmt->fetchAll();

			if(count($results) === 1){
				## Get User Info
				$row = $results[0];

				$userId = $row['userId'];
				$dbemail = $row['email'];
				$dbfirst_name = $row['first_name'];

				## Set Unique Token
				$token = $this->getPasswordResetToken($userId);
				if(!$token){
					throw new Exception("Error retrieving token.", MY_EXCEPTION);
				}

				$response['success'] = true;

				## Build Email Body
				$path = "<a href='"."https://".$_SERVER['SERVER_NAME'].$this->register_and_password_reset_url."/?token=".$token."'>"."https://".$_SERVER['SERVER_NAME'].$this->register_and_password_reset_url."/?token=".$token."</a>";

				$subject = $this->name." Password Reset Request\r\n";
				$mail_body = "<p>".$dbfirst_name.",</p>\r\n";
				$mail_body .= "<p>You have recently requested a password reset at ".$_SERVER['SERVER_NAME'].". Click or copy and paste the
					link below to reset your password. If you are receiving this email unexpectedly and have not requested a password reset, you may disregard this
					email and continue to logon normally.</p><br />\r\n";
				$mail_body .= $path."<br /><br />\r\n";
				$mail_body .= "<font style='font-size:11px;'>Please do NOT reply to this message.</font>";
				if($this->contact_email){
					$mail_body .= "<br />You can reach us at: <a href='mailto:".$this->contact_email."'>".$this->contact_email."</a>\r\n";
				}

				## Build Email Headers
				$headers = "From: ".$this->noreply_email."\r\n";
				$headers .= 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				mail($dbemail, $subject, $mail_body, $headers);
			}else{

				## No Results Found For Email Address - Register Them
				$response = $this->register($email);
			}
		}catch(Exception $e){
			$this->processException($e, $response);
		}

		## Return
		return json_encode($response);
	}

	/**
	* Password Reset
	*
	* @param string $email user's email
	* @param string $token token that was used to reset password
	* @param string $password  new password
	* @param string $vpassword  new password second entry
	*
	* @return response
	*/
	public function passwordReset($email, $token, $password, $vpassword) {

		## Define Globals
		$response = array();
		$response['success'] = false;

		try{
			if(!isset($token) || !isset($email) || !isset($password) || !isset($vpassword)){

				throw new Exception("Please provide all form fields.", MY_EXCEPTION);
			}

			if($password != $vpassword){

				throw new Exception("Passwords do not match", MY_EXCEPTION);
			}

			## Make Sure Passwords Are Strong(LOL)
			/*
			* Must be at least 8 characters long
			* Must contain at least one upper case character
			* Must contain at least one lower case character
			* Must contain at least one digit OR at least one special character
			*/

			if(!preg_match('/(?=^.{7,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/',$password)){

				throw new Exception("The password provided does not meet the minimum requirements.", MY_EXCEPTION);
			}

			$this->clearOldTokens();

			## Check That Email Address Exists
			$stmt = $this->conn->prepare("SELECT u.userId
				FROM PasswordReset r
					INNER JOIN User u ON u.userId = r.userId
				WHERE u.archived = 0
					AND r.token = ?
					AND r.archived = 0
					AND u.email = ?");

			$stmt->execute(array($token, $email));
			$results = $stmt->fetchAll();

			if(count($results) != 1){

				throw new Exception("There was an error processing your request. 010", MY_EXCEPTION);

			}

			## Make Sure User ID Is Associated With Provided Email
			$row = $results[0];
			$reset_userId = $row['userId'];
			$stmt = $this->conn->prepare("SELECT u.userId, u.email as email, u.firstName as first_name
				FROM User u
				WHERE u.archived = 0
					AND u.email = ?");
			$stmt->execute(array($email));
			$row = $stmt->fetch();
			$userId = $row['userId'];
			$dbemail = $row['email'];
			$dbfirst_name = $row['first_name'];

			## Check That User ID's Match From Each Table
			if($reset_userId != $userId){
				throw new Exception("There was an error processing your request. 020", MY_EXCEPTION);
			}

			## At This Point It Is Okay To Reset The Password
			$password_hash = password_hash($password, PASSWORD_BCRYPT);
			$stmt = $this->conn->prepare("UPDATE Login SET password = ? WHERE userId = ?");
			$stmt->execute(array($password_hash, $userId));

			## Delete Row From password_reset Table
			$stmt = $this->conn->prepare("UPDATE PasswordReset SET archived = 1 WHERE userId = ?");
			$stmt->execute(array($userId));
			$response['success'] = true;

			## Build Email Body
			$path = "<a href='".$_SERVER['SERVER_NAME']."'>".$_SERVER['SERVER_NAME']."</a>";
			$subject = $this->name." Password Reset Success";
			$mail_body = "<p>".$dbfirst_name.",</p>\r\n";
			$mail_body .= "<p>Your password has been reset successfully. You may now login with your new password by following the link below. If you are receiving
				this email unexpectedly and have not reset your password. Please contact support, as your account may have been compromised.</p><br />\r\n";
			$mail_body .= $path."<br /><br />";
			$mail_body .= "<font style='font-size:11px;'>Please do NOT reply to this message.</font>\r\n";
			if($this->contact_email){
				$mail_body .= "<br />You can reach us at: <a href='mailto:".$this->contact_email."'>".$this->contact_email."</a>\r\n";
			}

			## Build Email Headers
			$headers = "From: ".$this->noreply_email."\r\n";
			$headers .= 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			mail($dbemail, $subject, $mail_body, $headers);


			$this->login($email, $password);
		}catch(Exception $e){
			$this->processException($e, $response);
		}

		## Return
		return json_encode($response);
	}

	/**
	* Change Password
	*
	* @param string $password  old password
	* @param string $npassword  new password
	* @param string $vpassword  new password second entry
	* @return response
	*/
	public function changePassword($password, $npassword, $vpassword) {

		## Define Globals
		$response = array();
		$response['expired'] = false;

		try{
			## Check Login Status
			if(!$this->checkLogin()){
				return $this->logout();
			}

			## Check Required Fields
			if(!isset($password) || !isset($npassword) || !isset($vpassword)){
				throw new Exception("Please provide all form fields", MY_EXCEPTION);
			}

			## Fetch Current Password
			$stmt = $this->conn->prepare("SELECT password
				FROM Login l
					INNER JOIN User u ON u.userId = l.userId
				WHERE u.archived = 0
					AND l.userId = ?");
			$stmt->execute(array($_SESSION['USER__ID']));
			$results = $stmt->fetchAll();

			## Make Sure User Exists
			if(count($results) != 1){
				throw new Exception("There was an error changing your password.", MY_EXCEPTION);
			}

			## Define Database Results
			$row = $results[0];
			$dbpassword = $row['password'];

			## Check That Current Password Is Correct
			if(!password_verify($password, $dbpassword)){
				throw new Exception("Invalid password", MY_EXCEPTION);
			}

			## Make Sure Passwords Match
			if($npassword != $vpassword){
				throw new Exception("Passwords do not match", MY_EXCEPTION);
			}

			## Make Sure Passwords Are Strong
			/*
			* Must be at least 8 characters long
			* Must contain at least one upper case character
			* Must contain at least one lower case character
			* Must contain at least one digit OR at least one special character
			*/

			if(!preg_match('/(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/',$password)){
				throw new Exception("The password provided does not meet the minimum requirements.", MY_EXCEPTION);
			}

			## Set New Password
			$password = password_hash($npassword, PASSWORD_BCRYPT);
			$stmt = $this->conn->prepare("UPDATE Login SET password = ? WHERE userId = ?");
			$stmt->execute(array($password, $_SESSION['USER__ID']));
			$response['success'] = true;
		}catch(Exception $e){
			$this->processException($e, $response);
		}

		## Return
		return json_encode($response);
	}

	/**
	* Logged In
	*
	* Checks whether the user is still loggin in
	*
	* @return boolean
	*/
	public function checkLogin(){

		$rval = false;

		if(isset($_SESSION['USER__ID']) && isset($_SESSION['LAST_ACTIVITY'])){

			if($_SESSION['LAST_ACTIVITY'] + $this->SESSION_THRESHOLD >= time()){

				## Update Session
				$_SESSION['LAST_ACTIVITY'] = time();

				## Return True
				$rval = true;
			}
		}

		return $rval;
	}

	/**
	* Random String
	*
	* Generates a random string for internal use like password reset
	*
	* @return string
	*/
	private function rand_string($length) {

		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
		$size = strlen( $chars );
		$str = "";

		for( $i = 0; $i < $length; $i++ ) {
			$str .= $chars[ rand( 0, $size - 1 ) ];
		}

		return $str;
	}

	/**
	* Clear Old Tokens
	*
	* Clears all tokens in the db older than X minutes
	*
	* @return string
	*/
	private function clearOldTokens() {
		$this->conn->query(
			"UPDATE PasswordReset
			SET archived = 1
			WHERE timestamp < current_timestamp() - INTERVAL ".$this->TOKEN_THRESHOLD." MINUTE;");
	}

	/**
	* Get New Token
	*
	* Returns a new Token as well as clearing tokens with that user id
	*
	* @return string
	*/
	private function getPasswordResetToken($userId) {
		$token = bin2hex(openssl_random_pseudo_bytes(16));

		## Delete Existing Records
		$stmt = $this->conn->prepare(
			"UPDATE PasswordReset
			SET archived = 1
			WHERE userId = ?");
		$stmt->execute(array($userId));

		## Create Reset Record
		$stmt = $this->conn->prepare(
			"INSERT INTO PasswordReset
				(userId, token, ipAddress)
			VALUES
				(?, ?, ?)");
		if($stmt->execute(array($userId, $token, $_SERVER['REMOTE_ADDR']))){
			return $token;
		}else{
			return false;
		}
	}

	private function processException($exception, &$response){
		if($exception->getCode() === MY_EXCEPTION){
			$response['error'] = $exception->getMessage();
		}else{
			$response['error'] = "Sorry, there was an error reaching the server. Please try again later.";
		}
		$response['success'] = false;
	}
}
