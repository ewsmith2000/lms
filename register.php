<?php
    error_reporting(0);
    session_start();
    if(!file_exists("./config.php")) {
        echo "Unable to load the configuration file.";
        die();
    }
    require_once("./config.php");
    if($_SESSION['id'] && $_SESSION['username']) {
        header("Location: http://" . HOSTNAME . DASH_FILE);
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
        <title>LMS Panel BETA</title>
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/register.css">
    </head>
    <body>
        <div class="container">

            <?php
                $error = "";
                $input_style = "";
                if($_POST['submitbtn']) {
                    $username = filter_input(INPUT_POST, username);
                    $email = filter_input(INPUT_POST, email);
                    $password = filter_input(INPUT_POST, password);
                    $confirm = filter_input(INPUT_POST, confirm);
                    if(file_exists(CONNECT_FILE)) {
                        require(CONNECT_FILE);
                        if($username && $email && $password && $confirm) {
                            if((strstr($email, "@") && strstr($email, ".") == true)) {
                                if($password === $confirm) {
                                    if(strlen($password) > 7) {
                                        if($sql = $db->prepare("SELECT COUNT(*) FROM " . USERS_TABLE . " WHERE username=?")) {
                                            $sql->bindValue(1, $username, PDO::PARAM_STR);
                                            $sql->execute();
                                            $num_users = $sql->fetchColumn();
                                            $sql = null;
                                            if($num_users == 0) {
                                                $password = password_hash($password, PASSWORD_DEFAULT);
                                                $date = date("F j, Y");
                                                $code = md5(md5($date) . md5(rand()));
                                                if($sql = $db->prepare("INSERT INTO " . USERS_TABLE . " VALUES('', ?, ?, ?, 'standard', '0', ?, ?)")) {
                                                    $sql->execute(array($username, $password, $email, $code, $date));
                                                    $sql = null;
                                                    $headers = "From: LMS Registration Service <" . REGISTRATION_EMAIL . ">";
                                                    $subject = "Active Your Account at " . SITENAME;
                                                    $message = "Thanks for registering at " . SITENAME . "!\n";
                                                    $message .= "Click on the link below to activate your account.\n";
                                                    $message .= "http://" . HOSTNAME . ACTIVATE_FILE . "?user=" . $username . "&code=" . $code . "\n";
                                                    $message .= "You must activate your account before you can log in.";
                                                    if(mail($email, $subject, $message, $headers)) {
                                                        $error = "<div class='alert alert-info' role='alert'><strong>Yay!</strong> Your account has been created!<br>Please check your email to activate your account.</div>";
                                                        $username = "";
                                                        $email = "";
                                                    }
                                                    else
                                                        $error = "<div class='alert alert-danger' role='alert'><strong>Uh-oh!</strong> We're sorry, but your activation email was not sent successfully. Please contact " . WEBMASTER . "</div>";
                                                }
                                                else
                                                    $error = "<div class='alert alert-danger' role='alert'><strong>Uh-oh!</strong> We're sorry, but we were unable to query the database. Please try again later.</div>";
                                            }
                                            else
                                                $error = "<div class='alert alert-warning' role='alert'><strong>Oh!</strong> It looks like this username is already in use.</div>";
                                        }
                                        else 
                                            $error = "<div class='alert alert-danger' role='alert'><strong>Uh-oh!</strong> We're sorry, but we were unable to query the database. Please try again later.</div>";
                                    }
                                    else {
                                        $input_style = "has-error";
                                        $error = "<div class='alert alert-danger' role='alert'><strong>Oops!</strong> Your password must be at least 8 characters long!</div>";
                                    }
                                }
                                else {
                                    $input_style = "has-error";
                                    $error = "<div class='alert alert-danger' role='alert'><strong>Oops!</strong> The passwords you entered do not match.</div>";
                                }
                            }
                            else {
                                $input_style = "has-error";
                                $error = "<div class='alert alert-danger' role='alert'><strong>Oops!</strong> Please enter a valid email.</div>";
                            }
                        }
                        else {
                            $input_style = "has-error";
                            $error = "<div class='alert alert-danger' role='alert'><strong>Oops!</strong> Please make sure you have completed the form.</div>";
                        }
                        $db = null;
                    }
                    else {
                        $error = "<div class='alert alert-danger' role='alert'><strong>Uh-oh! </strong>We're sorry, but we were unable to load the connection file. Please check the configuration.<div>";
                    }
                }
                $form = "<form class='form-login' action='' method='post'>
                            <h2 class='form-login-heading'>LMS Panel BETA <small>Register</small></h2>
                            <div class='form-group " . $input_style . "'>"
                                . $error . "
                                <label for='username' class='sr-only'>Username</label>
                                <div class='input-group'>
                                    <span class='input-group-addon username-addon'><span class='glyphicon glyphicon-user'></span></span>
                                    <input type='text' name='username' class='form-control' placeholder='Username' value='" . $username . "' autofocus>
                                </div>
                            </div>
                            <div class='form-group " . $input_style . "'>
                                <label for='email' class='sr-only'>E-mail</label>
                                <div class='input-group'>
                                    <span class='input-group-addon email-addon'><span class='glyphicon glyphicon-envelope'></span></span>
                                    <input type='email' name='email' class='form-control' value='" . $email . "' placeholder='Email'>
                                </div>
                            </div>
                            <div class='form-group " . $input_style . "'>
                                <label for='password' class='sr-only'>Password</label>
                                <div class='input-group'>
                                    <span class='input-group-addon password-addon'><span class='glyphicon glyphicon-lock'></span></span>
                                    <input type='password' name='password' class='form-control password' placeholder='Password'>
                                </div>
                            </div>
                            <div class='form-group " . $input_style . "'>
                                <label for='confirm' class='sr-only'>Confirm password</label>
                                <div class='input-group'>
                                    <span class='input-group-addon confirm-addon'><span class='glyphicon glyphicon-repeat'></span></span>
                                    <input type='password' name='confirm' class='form-control confirm' placeholder='Confirm password'>
                                </div>
                            </div>
                            <div class='small-area'>
                                <a href='" . LOGIN_FILE . "' class='center-block'>Log in</a>
                            </div>
                            <input type='submit' name='submitbtn' class='btn btn-lg btn-primary btn-block' value='Register'>
                        </form>";
                echo $form;
            ?>
        </div>
        <!-- Bootstrap & jQuery -->
        <script src="js/jquery-1.11.2.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
    </body>
</html>