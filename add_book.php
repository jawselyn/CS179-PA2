<?php
	require("common.php");
		
	(isset($_POST['title']) && $_POST['title'] != '') or die("warning");
	(isset($_POST['author']) && $_POST['author'] != '') or die("warning");
	
	$title = $_POST['title'];
	$author = $_POST['author'];
	
	if ((isset($_POST['imageurl'])) && $_POST['imageurl'] != '') {
		$imageurl = $_POST['imageurl'];
	}
	else {
		$imageurl = 'noimage.jpg';
	}
	
	mysql_query("INSERT INTO books (`title`, `author`, `image_url`) VALUES ('$title', '$author', '$imageurl')");
	
	$id = mysql_insert_id();
	
	//if(isset($_POST['ajax'])) {
		// display the cover image in the 1st column
		echo "<div id='container".$id."' class = 'ui-bar ui-bar-c'> 
			<div class='ui-block-a'>
			<img src=". $imageurl . " height='100' alt=Book Cover> </div>";
		
		// display the title and author in the middle
		echo "<div class='ui-block-b'>
			<br/><b>" . $title . "</b><br/> (" . $author . ")<br>
			<form class='removebookform' method='post' action='remove_book.php'>
				<input type='hidden' name='delete' class='delete' value='" . $id . "' />
				<input type='submit' value='Delete Book'/>
			</form></div>";
		
		// display the comments and option to delete the entry
		$commentquery = "SELECT * FROM comments WHERE book_uid='$id'";
		$commentresult = mysql_query($commentquery);
		$num_comments = mysql_num_rows($commentresult);
		$comments=array();
		while ($row = mysql_fetch_array($commentresult)) {
			$comments[]=$row['comment'];
		} //now have an array with all the comments for this book entry
		//make that array into one string to be printed
		$printthis = NULL;
		foreach ($comments as $onecomment) {
			$printthis = $printthis . $onecomment . "<br><br>" ;
		} 
		echo "<div class='ui-block-c'> 
				<div data-role='collapsible' data-mini='true'>
					<h6> ".$num_comments." Comments </h6>
					<p style='font-size: 10px'>
						<form method='post' class='addcommentform' action='add_comment.php'>
							<textarea name='newcomment' id='newcomment' rows='2' cols='13' placeholder='Add your comment'></textarea>
							<input type='hidden' name='pid' id='pid".$id."' value='" . $id . "' />
							<br><input type='submit' value='Add Comment'/>
						</form>
					</p>
				</div>
			</div>
		</div>";
	//} 
	//else {
	//	header("Location: index.php/");
	//}
?>