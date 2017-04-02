<?php


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
            
                throw new Exception("Please provide both your email address and password.");

            }

            $stmt = $this->conn->prepare(
                "SELECT 
                    u.id, u.email, 
                    l.password, l.failed_login_count, 
                    CASE WHEN DATE_ADD(l.lockout_time, INTERVAL 30 MINUTE) > CURRENT_TIMESTAMP THEN 1 ELSE 0 END AS locked_out          
                FROM ".$this->database.".login l
                    INNER JOIN ".$this->database.".user u ON u.id = l.user__id
                WHERE u.archive = 0 
                    AND u.email = ?");
            $stmt->execute(array($email));
            $results = $stmt->fetchAll();

            ## Make Sure Account Exists
            if(count($results) != 1){
                throw new Exception("Email Address and/or Password is incorrect!");
            }

            ## Get User Info
            $row = $results[0];

            $user__id = $row['id'];

            $dbemail = strtolower($row['email']);
            $dbpassword = $row['password'];
            $locked_out = $row['locked_out'];
            $failed_login_count = $row['failed_login_count'];

            ## Unlock Account If Needed
            if(!$locked_out && $failed_login_count >= LOGIN_THRESHOLD){
                $stmt = $this->conn->prepare(
                    "UPDATE ".$this->database.".login l
                    SET failed_login_count = 0 
                    WHERE user__id = ?");
                $stmt->execute(array($user__id));
                $failed_login_count = 0;
            }


            ## If Not Locked Out
            if($failed_login_count < LOGIN_THRESHOLD ){
                ## Check Credentials
                if(($email === $dbemail)  && password_verify($password, $dbpassword)){

                    ## Check If Password Needs Rehashed
                    if(password_needs_rehash($dbpassword, PASSWORD_BCRYPT)) {

                        ## Define Rehash
                        $rehash = password_hash($dbpassword, PASSWORD_BCRYPT);

                        ## Create Statement
                        $stmt = $this->conn->prepare(
                            "UPDATE ".$this->database.".login l
                            SET password = ? 
                            WHERE user__id = ?;");
                        $stmt->execute(array($rehash, $user__id));
                    }

                    ## Store Session Variables
                    $_SESSION['LAST_ACTIVITY'] = time();
                    $_SESSION['USER__ID'] = $user__id;

                    $response['success'] = true;
                    $response['redirect'] = $this->login_url;

                    ## Record Login
                    $stmt = $this->conn->prepare(
                        "INSERT INTO ".$this->database.".login_history 
                            (user__id, IP_address) 
                        VALUES 
                            (?, ?)");
                    $stmt->execute(array($user__id, $_SERVER['REMOTE_ADDR']));
                }else{

                    ## Wrong password, record failure
                    $stmt = $this->conn->prepare(
                        "UPDATE ".$this->database.".login l
                        SET 
                            failed_login_count = failed_login_count+1, 
                            lockout_time = CURRENT_TIMESTAMP 
                        WHERE user__id = ?;");
                    $stmt->execute(array($user__id));

                    throw new Exception("Email Address and/or Password is incorrect.");
                }
            }else{

                ## Too many failures, lock account
                $stmt = $this->conn->prepare(
                    "UPDATE ".$this->database.".login 
                    SET lockout_time = current_timestamp() 
                    WHERE user__id = ?");
                $stmt->execute(array($user__id));

                throw new Exception("You have entered the wrong login information too many times and you have been temporarily locked out.");
            }
        }catch(PDOException $e){
            $response['success'] = false;
            $response['error'] = "Sorry, there was an error reaching the server. Please try again later.";
        }catch(Exception $e){
            $response['success'] = false;
            $response['error'] = $e->getMessage();
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

                throw new Exception("Please enter a valid email address");
            }

            ## Verify That The Email Address Is Allowed
            $stmt = $this->conn->prepare(
                "SELECT u.email, u.first_name, u.id
                FROM ".$this->database.".user u
                WHERE archive = 0 
                    AND email = ?");
            $stmt->execute(array($email));
            $results = $stmt->fetchAll();

            if(count($results) != 1){
                throw new Exception("There was an error registering your account!");
            }

            ## Verify That Email Doesn't Already Exist
            $row = $results[0];
            $user__id = $row['id'];
            $dbemail = $row['email'];
            $dbfirst_name = $row['first_name'];

            $stmt = $this->conn->prepare(
                "SELECT *
                FROM ".$this->database.".login 
                WHERE user__id = ?;");
            $stmt->execute(array($user__id));

            if(count($stmt->fetchAll()) > 0){
                throw new Exception("Account already exists!");
            }

            ## Add User
            $temp_password = $this->rand_string(10);
            $password = password_hash($temp_password, PASSWORD_BCRYPT);
            $stmt = $this->conn->prepare(
                "INSERT INTO ".$this->database.".login 
                    (user__id, password) 
                VALUES 
                    (?, ?)");
            $stmt->execute(array($user__id, $password));

            ## Set Unique Token
            $token = $this->getPasswordResetToken($user__id);

            ## Build Email Body
            $path = "<a href='".$this->register_and_password_reset_url."/?token=".$token."'>".$this->register_and_password_reset_url."/?token=".$token."</a>";

            $subject = $this->name." - Account Created";
            $mail_body = "<p>".$dbfirst_name.",</p>";
            $mail_body .= "<p>Your account has been successfully created for the ".$this->name." System.
                In order to activate your account, you must visit the link below to set up a password. This token will expire after 30 minutes if unused.</p><br />";
            $mail_body .= $path."<br /><br />";
            $mail_body .= "<font style='font-size:11px;'>Please do NOT reply to this message.</font>";
            if($this->contact_email){
                $mail_body .= "<br />You can reach us at: <a href='mailto:".$this->contact_email."'>".$this->contact_email."</a>";
            }

            ## Build Email Headers
            $headers = "From: ".$this->noreply_email."\r\n";
            $headers .= 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            mail($dbemail, $subject, $mail_body, $headers);

        }catch(PDOException $e){
            $response['success'] = false;
            $response['error'] = "Sorry, there was an error reaching the server. Please try again later.";
        }catch(Exception $e){
            $response['success'] = false;
            $response['error'] = $e->getMessage();
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

                throw new Exception("Please enter a valid email address");
            }

            ## Verify That Email Exists
            $stmt = $this->conn->prepare(
                "SELECT u.email, u.first_name, u.id
                FROM ".$this->database.".login l
                    INNER JOIN ".$this->database.".user u ON u.id = l.user__id
                WHERE u.archive = 0 
                    AND u.email = ?");
            $stmt->execute(array($email));
            $results = $stmt->fetchAll();

            if(count($results) === 1){
                ## Get User Info
                $row = $results[0];

                $user__id = $row['id'];
                $dbemail = $row['email'];
                $dbfirst_name = $row['first_name'];

                ## Set Unique Token
                $token = $this->getPasswordResetToken($user__id);

                $response['success'] = true;

                ## Build Email Body
                $path = "<a href='".$this->register_and_password_reset_url."/?token=".$token."'>".$this->register_and_password_reset_url."/?token=".$token."</a>";

                $subject = $this->name." Password Reset Request";
                $mail_body = "<p>".$dbfirst_name.",</p>";
                $mail_body .= "<p>You have recently requested a password reset at ".URL_BASE.". Click or copy and paste the 
                    link below to reset your password. If you are receiving this email unexpectedly and have not requested a password reset, you may disregard this 
                    email and continue to logon normally.</p><br />";
                $mail_body .= $path."<br /><br />";
                $mail_body .= "<font style='font-size:11px;'>Please do NOT reply to this message.</font>";
                if($this->contact_email){
                    $mail_body .= "<br />You can reach us at: <a href='mailto:".$this->contact_email."'>".$this->contact_email."</a>";
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
        }catch(PDOException $e){
            $response['success'] = false;
            $response['error'] = "Sorry, there was an error reaching the server. Please try again later.";
        }catch(Exception $e){
            $response['success'] = false;
            $response['error'] = $e->getMessage();
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

                throw new Exception("Please provide all form fields.");
            }

            if($password != $vpassword){

                throw new Exception("Passwords do not match");
            }

            ## Make Sure Passwords Are Strong(LOL)
            /*
            * Must be at least 8 characters long
            * Must contain at least one upper case character
            * Must contain at least one lower case character
            * Must contain at least one digit OR at least one special character
            */
            
            if(!preg_match('/(?=^.{7,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/',$password)){

                throw new Exception("The password provided does not meet the minimum requirements.");
            }

            $this->clearOldTokens();

            ## Check That Email Address Exists
            $stmt = $this->conn->prepare("SELECT u.id
                FROM ".$this->database.".password_reset r
                    INNER JOIN ".$this->database.".user u ON u.id = r.user__id
                WHERE u.archive = 0 
                    AND r.token = ?
                    AND r.archive = 0
                    AND u.email = ?");

            $stmt->execute(array($token, $email));
            $results = $stmt->fetchAll();

            if(count($results) != 1){

                throw new Exception("There was an error processing your request.");

            }

            ## Make Sure User ID Is Associated With Provided Email
            $row = $results[0];
            $reset_user__id = $row['user__id'];
            $stmt = $this->conn->prepare("SELECT user__id, email as email, first_name 
                FROM ".$this->database.".user 
                WHERE archive = 0 
                    AND email = ?");
            $stmt->execute(array($email));
            $row = $stmt->fetch();
            $user__id = $row['user__id'];
            $dbemail = $row['email'];
            $dbfirst_name = $row['first_name'];

            ## Check That User ID's Match From Each Table
            if($reset_user__id != $user__id){
                throw new Exception("There was an error processing your request.");
            }

            ## At This Point It Is Okay To Reset The Password
            $password_hash = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $this->conn->prepare("UPDATE ".$this->database.".login SET password = ? WHERE user__id = ?");
            $stmt->execute(array($password_hash, $user__id));

            ## Delete Row From password_reset Table
            $stmt = $this->conn->prepare("UPDATE ".$this->database.".password_reset SET archive = 1 WHERE user__id = ?");
            $stmt->execute(array($user__id));
            $response['success'] = true;

            ## Build Email Body
            $path = "<a href='".URL_BASE."'>".URL_BASE."</a>";
            $subject = $this->name." Password Reset Success";
            $mail_body = "<p>".$dbfirst_name.",</p>";
            $mail_body .= "<p>Your password has been reset successfully. You may now login with your new password by following the link below. If you are receiving 
                this email unexpectedly and have not reset your password. Please contact support, as your account may have been compromised.</p><br />";
            $mail_body .= $path."<br /><br />";
            $mail_body .= "<font style='font-size:11px;'>Please do NOT reply to this message.</font>";
            if($this->contact_email){
                $mail_body .= "<br />You can reach us at: <a href='mailto:".$this->contact_email."'>".$this->contact_email."</a>";
            }

            ## Build Email Headers
            $headers = "From: ".$this->noreply_email."\r\n";
            $headers .= 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            mail($dbemail, $subject, $mail_body, $headers);


            $this->login($email, $password);
        }catch(PDOException $e){
            $response['success'] = false;
            $response['error'] = "Sorry, there was an error reaching the server. Please try again later.";
        }catch(Exception $e){
            $response['success'] = false;
            $response['error'] = $e->getMessage();
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
                throw new Exception("Please provide all form fields");
            }

            ## Fetch Current Password
            $stmt = $this->conn->prepare("SELECT password 
                FROM ".$this->database.".login l
                    INNER JOIN ".$this->database.".user u ON u.id = l.user__id
                WHERE u.archive = 0
                    AND l.user__id = ?");
            $stmt->execute(array($_SESSION['USER__ID']));
            $results = $stmt->fetchAll();

            ## Make Sure User Exists
            if(count($results) != 1){
                throw new Exception("There was an error changing your password.");
            }

            ## Define Database Results
            $row = $results[0];
            $dbpassword = $row['password'];

            ## Check That Current Password Is Correct
            if(!password_verify($password, $dbpassword)){
                throw new Exception("Invalid password");
            }

            ## Make Sure Passwords Match
            if($npassword != $vpassword){
                throw new Exception("Passwords do not match");
            }

            ## Make Sure Passwords Are Strong
            /*
            * Must be at least 8 characters long
            * Must contain at least one upper case character
            * Must contain at least one lower case character
            * Must contain at least one digit OR at least one special character
            */
            
            if(!preg_match('/(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/',$password)){
                throw new Exception("The password provided does not meet the minimum requirements.");
            }

            ## Set New Password
            $password = password_hash($npassword, PASSWORD_BCRYPT);
            $stmt = $this->conn->prepare("UPDATE ".$this->database.".login SET password = ? WHERE user__id = ?");
            $stmt->execute(array($password, $_SESSION['USER__ID']));
            $response['success'] = true;
        }catch(PDOException $e){
            $response['success'] = false;
            $response['error'] = "Sorry, there was an error reaching the server. Please try again later.";
        }catch(Exception $e){
            $response['success'] = false;
            $response['error'] = $e->getMessage();
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

            if($_SESSION['LAST_ACTIVITY'] + SESSION_THRESHOLD >= time()){
                
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
            "UPDATE ".$this->database.".password_reset 
            SET archive = 1
            WHERE timestamp < current_timestamp() - INTERVAL ".$this->TOKEN_THRESHOLD." MINUTE;");
    }

    /**
    * Get New Token
    * 
    * Returns a new Token as well as clearing tokens with that user id
    * 
    * @return string
    */
    private function getPasswordResetToken($user__id) {
        $token = bin2hex(openssl_random_pseudo_bytes(16));

        ## Delete Existing Records
        $stmt = $this->conn->prepare(
            "UPDATE ".$this->database.".password_reset 
            SET archive = 1
            WHERE user__id = ?");
        $stmt->execute(array($user__id));

        ## Create Reset Record
        $stmt = $this->conn->prepare(
            "INSERT INTO ".$this->database.".password_reset 
                (user__id, token, IP_address) 
            VALUES 
                (?, ?)");
        $stmt->execute(array($user__id, $token, $_SERVER['REMOTE_ADDR']));
    }
}