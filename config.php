<?php
	/* SERVER */
	DEFINE("HOSTNAME", "ewsmith2000.github.io");	// Hostname e.g. example.com
	DEFINE("SITENAME", "LMS Beta");	// The site name e.g. My Awesome Site
	DEFINE("WEBMASTER", "registration@localhost");	// Webmaster Contact Info
	/* FILES */
	DEFINE("CONNECT_FILE", "connect.php");	// Database Connection File
	DEFINE("LOGIN_FILE", "login.php");	// Login File
	DEFINE("LOGOUT_FILE", "logout.php");	// Logout File
	DEFINE("REGISTER_FILE", "register.php");	// Registration File
	DEFINE("DASH_FILE", "dash.php");	// Dashboard File
	DEFINE("ACTIVATE_FILE", "activate.php");	// Account Activation File
	DEFINE("RESET_FILE", "reset.php");	// Password Reset File
	/* DATABASE */
	DEFINE("DB_TYPE", "mysql");	// Database type (e.g. MySQL, Mongo, Oracle)
	DEFINE("DB_SERVER", "localhost");	// Server to connect to
	DEFINE("DB_USER", "root");	// Username
	DEFINE("DB_PASSWORD", "root");	// Password
	DEFINE("DATABASE", "lms");	// Database Name
	DEFINE("USERS_TABLE", "users");	// Users Table
	DEFINE("GROUPS_TABLE", "groups");	// Groups Table
	/* EMAIL */
	DEFINE("REGISTRATION_EMAIL", "registration@localhost");	// Email used for sending activation codes
	/* PREFERENCES */
	DEFINE("UNIQUE_EMAIL", false);	// Require that emails can be used only once or not
?>
