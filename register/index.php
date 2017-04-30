<!doctype html>
<html class="no-js" lang="">
<?php
    require_once '../server/header.php';
?>
    <body>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <?php
        require_once '../server/config.php';

        if(isset($_REQUEST['token']) && strlen($_REQUEST['token']) == 32){
            ## Define Passed Variables
            $token = $_REQUEST['token'];

            ## Make Sure There Is A Row With That Token
            $stmt = $DB_CONN->prepare("SELECT * FROM vita.passwordreset WHERE token = ?");
            $stmt->execute(array($token));
            if(count($stmt->fetchAll()) === 1){
    ?>
        <div class='container'>
            <div class='row'>
                <section class='col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3'>
                    <h1 class='text-center'>Hoovestol, inc.</h1>
                    <h1 class='text-center'>Eagle Express Lines, inc.</h1>
                    <hr />
                </section>
            </div>
            <div id="reset_wrap" class="row">
                <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
                <div id="reset_password_panel" class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">Password Reset</h3>
                    </div>
                    <div class="panel-body">
                    <section id='reset_password_success' style='display:none;'>
                        <p>Your password has been reset successfully. You may login to your account using the link below.</p>
                        <p><a href='<?php echo URL_BASE;?>'>Login</a></p>
                    </section>
                    <section id='reset_password_info'>
                        <p>Please provide your current password, and your new password below. Your password must mee the following requirements:</p>
                        <p class='text-primary'><b>- At least 8 characters</b><br />
                        <b>- At least 1 uppercase</b><br />
                        <b>- At least 1 lowercase</b><br />
                        <b>- At least 1 number or special character</b><br /></p>
                    </section>
                    <form id='reset_password_form'>
                        <input id="reset_password_token" type="hidden" value="<?php echo $_REQUEST['token']; ?>" />
                        <section class='form-group'>
                            <label class='control-label'>Email Address</label>
                            <input id='reset_password_email' type='text' class='form-control' placeholder='john.doe@email.com' autocomplete='off' />
                        </section>
                        <section class='form-group'>
                            <label class='control-label'>New Password</label>
                            <input id='reset_password_npassword' type='password' class='form-control' />
                        </section>
                        <section class='form-group'>
                            <label class='control-label'>Confirm Password</label>
                            <input id='reset_password_vpassword' type='password' class='form-control' />
                        </section>
                        <section>
                            <button type='submit' class='btn btn-primary pull-right'>Submit</button>
                        </section>
                    </form>
                </div>
                </div>
                </div>
            </div>
        </div>
    <?php
            }else{
                ?>
                <a href="/login">Your password reset link has expired. Please request a new one here.</a>
                <?php
            }
        }else{
            ?>
            <a href="/">You appear to have reached this page in error. Please click this link to return home.</a>
            <?php
        }
        require_once '../server/footer.php';
    ?>
        <script src="/assets/js/login.js"></script>
    </body>
</html>