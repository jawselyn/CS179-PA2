<?php
	require("common.php");
	
	isset($_POST['delete']) or die("warning");
	
	$delete = $_POST['delete'];
	
	mysql_query("DELETE FROM books WHERE uid = '$delete'");
	
	echo $delete;
	//header("Location: index.php/");
?> 