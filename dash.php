<?php
	error_reporting(0);
	session_start();
	if(!file_exists("./config.php")) {
		echo "Unable to load the configuration file.";
		die();
	}
	require_once("./config.php");
	if(!($_SESSION['id'] && $_SESSION['username'])) {
		header("Location: http://" . HOSTNAME . LOGIN_FILE);
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width-device-width, initial-scale=1.0, maximum-scale=1.0">
		<title>LMS Panel BETA</title>
		<link rel="stylesheet" href="./css/bootstrap.min.css">
		<link rel="stylesheet" href="./css/dash.css">
	</head>
	<body>

		<nav class="navbar navbar-inverse navbar-fixed-top">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" area-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">LMS Panel BETA</a>
                </div>
                <div id="navbar" class="navbar-collapse collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="./profile.php?user=<?php echo $_SESSION['username']; ?>"><?php echo $_SESSION['username']; ?></a></li>
                        <li><a href="./logout.php">Logout <span class="glyphicon glyphicon-log-out"></span></a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-3 col-md-2 sidebar">
                    <ul class="nav nav-sidebar">
                        <li class="active"><a href="#">Dashboard</a></li>
                        <li><a href="#">Manage</a></li>
                        <li><a href="#">Reports</a></li>
                    </ul>
                    <ul class="nav nav-sidebar">
                        <li><a href="#">Settings <span class="glyphicon glyphicon-cog"></span></a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <h1 class="page-header">Dashboard</h1>
            <?php
                if(!file_exists(CONNECT_FILE)) {
                    echo "<div class='alert alert-danger'><strong>Uh-oh!</strong> We're sorry, but we were unable to locate the connection file. Please check the configuration file.</div>";;
                }
                require_once(CONNECT_FILE);
            ?>

            <h2 class="sub-header">Users</h2>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>E-mail</th>
                            <th>Group</th>
                            <th>Active</th>
                            <th>Code</th>
                        </tr>
                    </thead>
                    <tbody>
                    	<?php
                    		foreach($users = $db->query("SELECT * FROM " . USERS_TABLE) as $user_row) {
                    			echo "<tr><td>" . $user_row["id"] . "</td><td>" . $user_row["username"] . "</td><td>" . $user_row["email"] . "</td><td>" . $user_row["group"] . "</td><td>" . $user_row["active"] . "</td><td>" . $user_row["code"] . "</td></tr>";
                    		}
                    	?>
                    </tbody>
                </table>
            </div>

            <h2 class="sub-header">Groups</h2>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Permission</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        	foreach($groups = $db->query("SELECT * FROM " . GROUPS_TABLE) as $group_row) {
                                echo "<tr><td>" . $group_row["id"] . "</td><td>" . $group_row["name"] . "</td><td>" . $group_row["permission"] . "</td><td>" . "</td></tr>";
                            }
                            $db = null;
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

		<!-- Bootstrap & jQuery JS -->
        <script src="./js/jquery-1.11.2.min.js"></script>
        <script src="./js/bootstrap.min.js"></script>
	</body>
</html>