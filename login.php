<?php
    error_reporting(E_ALL ^ E_NOTICE);
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
        <link rel="stylesheet" href="./css/bootstrap.min.css">
        <link rel="stylesheet" href="./css/login.css">
    </head>
    <body>
        <div class="container">
            <?php
                $error = "";
                $input_style = "";
                if($_POST['loginbtn']) {
                    $username = filter_input(INPUT_POST, username);
                    $password = filter_input(INPUT_POST, password);
                    if(file_exists(CONNECT_FILE)) {
                        require(CONNECT_FILE);
                        if($username && $password) {
                            if($sql = $db->prepare("SELECT id, username, password, email, active, user_group FROM " . USERS_TABLE . " WHERE username=?")) {
                                $sql->bindValue(1, $username, PDO::PARAM_STR);
                                $sql->execute();
                                $user = $sql->fetch();
                                if(password_verify($password, $user["password"])) {
                                    if($user["active"] == 1) {
                                        $_SESSION["id"] = $user["id"];
                                        $_SESSION["username"] = $user["username"];
                                        $_SESSION["group"] = $user["group"];
                                        header("Location: http://" . HOSTNAME . DASH_FILE);
                                    }
                                    else {
                                        $email = $user["email"];
                                        $splitted = explode("@", $email);
                                        $input_style = "has-warning";
                                        $error = "<div class='alert alert-warning' role='alert'><strong>Oh!</strong> This account needs to be activated.<br>We sent an activation e-mail to:<strong> " . substr($email, 0, 4) . "*****@" . $splitted[1] . "</strong></div>";
                                    }
                                }
                                else {
                                    $input_style = "has-error";
                                    $error = "<div class='alert alert-danger' role='alert'><strong>Oops!</strong> The username or password you entered was incorrect!</div>";
                                }
                            }
                            else
                                $error = "<div class='alert alert-danger role='alert'><strong>Uh-oh!</strong> We're sorry, but we were unable to query the database. Please try again later.</div>";
                            $sql = null;
                        }
                        else {
                            $input_style = "has-error";
                            $error = "<div class='alert alert-danger' role='alert'><strong>Oops!</strong> Please enter your username and password.</div>";
                        }
                        $db = null;
                    }
                    else {
                        $error = "<div class='alert alert-danger' role='alert'><strong>Uh-oh!</strong> We're sorry, but we were unable to load the connection file. Please check the configuration.</div>";
                    }
                }
                $form = "<form class='form-login' action='./login.php' method='post'>
                            <h2 class='form-login-heading'>LMS Panel BETA</h2>
                            <div class='form-group " . $input_style . "'>"
                                . $error . "
                                <label for='username' class='sr-only'>Username</label>
                                <div class='input-group'>
                                    <span class='input-group-addon username-addon'><span class='glyphicon glyphicon-user'></span></span>
                                    <input type='text' name='username' class='form-control' placeholder='Username' autofocus>
                                </div>
                            </div>
                            <div class='form-group " . $input_style . "'>
                                <label for='password' class='sr-only'>Password</label>
                                <div class='input-group'>
                                    <span class='input-group-addon password-addon'><span class='glyphicon glyphicon-lock'></span></span>
                                    <input type='password' name='password' class='form-control' placeholder='Password'>
                                </div>
                            </div>
                            <div class='checkbox'>
                                <label><input type='checkbox' value='remember-me'> Remember me</label>
                                <a class='pull-right' href='" . REGISTER_FILE . "'>Register</a>
                            </div>
                            <input class='btn btn-lg btn-primary btn-block' type='submit' name='loginbtn' value='Log in'>
                        </form>";
                echo $form;
            ?>
        </div>

        <!-- Bootstrap & jQuery JS -->
        <script src="./js/jquery-1.11.2.min.js"></script>
        <script src="./js/bootstrap.min.js"></script>
    </body>
</html>