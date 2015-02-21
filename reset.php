<?php
    error_reporting(0);
    session_start();
    if(!file_exists("./config.php")) {
        echo "Unable to load the configuration file.";
        die();
    }
    require_once("./config.php");
    if(!($_SESSION['id'] && $_SESSION['username'])) {
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
		<link rel="stylesheet" href="css/reset.css">
	</head>
	<body>
		<div class="container">
			<?php
				$error = "";
				$input_style = "";
				if($_POST['resetbtn']) {
					if(file_exists(CONNECT_FILE)) {
						require(CONNECT_FILE);
						if($_POST['old'] && $_POST['new'] && $_POST['confirm']) {
							$old = filter_input(INPUT_POST, "old");
							$new = filter_input(INPUT_POST, "new");
							$confirm = filter_input(INPUT_POST, "confirm");
							if($sql = $db->prepare("SELECT COUNT(*) FROM " . USERS_TABLE . " WHERE id=? AND username=?")) {
								$sql->bindValue(1, $_SESSION["id"], PDO::PARAM_INT);
								$sql->bindValue(2, $_SESSION["username"], PDO::PARAM_STR);
								$sql->execute();
								$num_users = $sql->fetchColumn();
								$sql = null;
								if($num_users == 1) {
									if($new == $confirm) {
										if($sql = $db->prepare("SELECT password FROM " . USERS_TABLE . " WHERE id=?")) {
											$sql->bindValue(1, $_SESSION["id"], PDO::PARAM_INT);
											$sql->execute();
											$password = $sql->fetchColumn();
											$sql = null;
											if(password_verify($old, $password)) {
												if($sql = $db->prepare("UPDATE " . USERS_TABLE . " SET password=? WHERE id=?")) {
													$sql->bindValue(1, password_hash($new, PASSWORD_DEFAULT));
													$sql->bindValue(2, $_SESSION["id"], PDO::PARAM_INT);
													$sql->execute();
													$sql = null;
													$error = "<div class='alert alert-success' role='alert'><strong>Yay!</strong> Your password has been reset.</div>";
												}
												else
													$error = "<div class='alert alert-danger' role='alert'><strong>Uh-oh!</strong> We're sorry, but we were unable to query the database. Please try again later.</div>";
											}
											else {
												$input_style = "has-error";
												$error = "<div class='alert alert-danger' role='alert'><strong>Oops!</strong> Please make sure your old password is correct.</div>";
											}
										}
										else
											$error = "<div class='alert alert-danger' role='alert'><strong>Uh-oh!</strong> We're sorry, but we were unable to query the database. Please try again later.</div>";
									}
									else {
										$input_style = "has-error";
										$error = "<div class='alert alert-danger' role='alert'><strong>Oops!</strong> Please make sure your new passwords match!</div>";
									}
								}
								else {
									$input_style = "has-error";
									$error = "<div class='alert alert-danger' role='alert'><strong>Oops!</strong> Please make sure your old password is correct.</div>";
								}
							}
							else
								$error = "<div class='alert alert-danger' role='alert'><strong>Uh-oh!</strong> We're sorry, but we were unable to query the database. Please try again later.</div>";
						}
						else
							$error = "<div class='alert alert-danger' role='alert'><strong>Oops!</strong> Please make sure you have completed the form.</div>";
						$db = null;
					}
					else
						$error = "<div class='alert alert-danger' role='alert'><strong>Uh-oh!</strong> We're sorry, but we were unable to load the connection file. Please check the configuration.</div>";
				}
				$form = "<form class='form-login' action='' method='post'>
			                <h2 class='form-login-heading'>LMS Panel Beta <small>Reset</small></h2>
			                <div class='form-group " . $input_style . "'>"
			                	. $error . "
			                    <label for='old' class='sr-only'>Current password</label>
			                    <div class='input-group'>
			                        <span class='input-group-addon old-addon'><span class='glyphicon glyphicon-save'></span></span>
			                        <input type='password' name='old' class='form-control old' placeholder='Current password' autofocus>
			                    </div>
			                </div>
			                <div class='form-group " . $input_style . "'>
			                    <label for='new' class='sr-only'>New password</label>
			                    <div class='input-group'>
			                        <span class='input-group-addon new-addon'><span class='glyphicon glyphicon-lock'></span></span>
			                        <input type='password' name='new' class='form-control new' placeholder='New password'>
			                    </div>
			                </div>
			                <div class='form-group " . $input_style . "'>
			                    <label for='confirm' class='sr-only'>Confirm new password</label>
			                    <div class='input-group'>
			                        <span class='input-group-addon confirm-addon'><span class='glyphicon glyphicon-repeat'></span></span>
			                        <input type='password' name='confirm' class='form-control confirm' placeholder='Confirm new password'>
			                    </div>
			                </div>
			                <div class='small-area'>
			                    <a href='./profile.php?user=" . $_SESSION['username'] . "' class='center-block'>Go back</a>
			                </div>
			                <input type='submit' name='resetbtn' class='btn btn-lg btn-primary btn-block' value='Reset password'>
			            </form>";
				echo $form;
			?>
		</div>
		<!-- Bootstrap & jQuery -->
		<script src="js/jquery-1.11.2.min.js"></script>
		<script src="js/bootstrap.min.css"></script>
	</body>
</html>