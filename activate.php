<?php
	error_reporting(0);
	session_start();
	if(!file_exists("./config.php")) {
		echo "Unable to load configuration file.";
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
		<link rel="stylesheet" href="css/activate.css">
	</head>
	<body>
		<div class="container">
			<div class="page-heading"><h1>LMS Panel Beta <small>Activate</small></h1></div>
			<div class="well well-lg">
				<?php
					if($_GET['user'] && $_GET['code']) {
						$username = filter_input(INPUT_GET, user);
						$code = filter_input(INPUT_GET, code);
						if(file_exists(CONNECT_FILE)) {
							require(CONNECT_FILE);
							if(strlen($username) > 35 || strlen($code) != 32)
								header("Location: http://" . HOSTNAME . DASH_FILE);
							if($sql = $db->prepare("SELECT COUNT(*) FROM " . USERS_TABLE . " WHERE username=? AND active=0 AND code=?")) {
								$sql->bindValue(1, $username, PDO::PARAM_STR);
								$sql->bindValue(2, $code, PDO::PARAM_STR);
								$sql->execute();
								$num_users = $sql->fetchColumn();
								$sql = null;
								if($num_users != 1)
									header("Location: http://" . HOSTNAME . DASH_FILE);
								if($sql = $db->prepare("UPDATE " . USERS_TABLE . " SET active=1 WHERE username=?")) {
									$sql->bindValue(1, $username, PDO::PARAM_STR);
									$sql->execute();
									$sql = null;
									echo "<div class='alert alert-success' role='alert'><strong>Yay!</strong> Your account has been activated. You may now login.</div>";
									echo "<a class='btn btn-lg btn-primary' href='" . LOGIN_FILE . "'>Log in</a>";
								}
								else
									echo "<div class='alert alert-danger' role='alert'><strong>Uh-oh!</strong> We're sorry, but we were unable to query the database. Please try again later. <a class='link' href='" . DASH_FILE . "'>Go back.</a></div>";
							}
							else
								echo "<div class='alert alert-danger' role='alert'><strong>Uh-oh!</strong> We're sorry, but we were unable to query the database. Please try again later. <a class='link' href='" . DASH_FILE . "'>Go back.</a></div>";
							$db = null;
						}
						else
							echo "<div class='alert alert-danger' role='alert'><strong>Uh-oh!</strong> We're sorry, but we were unable to load the connection file. Please check the configuration. <a class='link' href='" . DASH_FILE . "'>Go back</a></div>";
					}
					else
						header("Location: http://" . HOSTNAME . DASH_FILE);
				?>
			</div>
		</div>
		<!-- Bootstrap & jQuery -->
		<script src="js/jquery-1.11.2.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
	</body>
</html>