<?php
	require("common.php");
	
	(isset($_POST['newcomment']) && $_POST['newcomment'] != '') or die("warning");

	$newcomment = $_POST['newcomment'];
	$pid = $_POST['pid'];
	mysql_query("INSERT INTO comments (`book_uid`, `comment`) VALUES ('$pid', '$newcomment')");
	
	echo $newcomment;
?>