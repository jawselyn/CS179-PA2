<?php
	//let's define constants here
	define("DB_USER","cs179");
	define("DB_PASS","cs179pa2");
	define("DB_HOST","mysql.cs179.org");
	define("DB_NAME","cs179pa2");

	//create a DB connection
	$link = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die('Could not connect to mysql server.');
	mysql_select_db(DB_NAME);
?>