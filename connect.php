<?php
	error_reporting(E_ALL);
	if(file_exists("config.php")) {
		require_once("config.php");
		try {
			$db = new PDO(DB_TYPE . ":" . "host=" . DB_SERVER . ";dbname=" . DATABASE, DB_USER, DB_PASSWORD);
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch(PDOException $e) {
			echo "Error: " . $e->getMessage();
			die();
		}
	}
	else {
		echo "The configuration file could not be found.";
	}
?>