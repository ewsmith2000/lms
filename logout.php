<?php
	error_reporting(E_ALL ^ E_NOTICE);
	session_start();
	if(!file_exists("./config.php")) {
		echo "Unable to load the configuration file.";
		die();
	}
	require_once("./config.php");
	if($_SESSION['id'] && $_SESSION['username']) {
		session_destroy();
	}
	header("Location: http://" . HOSTNAME . LOGIN_FILE);
?>