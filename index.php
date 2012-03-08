<?php
	require("common.php");

	if (isset($_GET['query']) && $_GET['query'] != '') {
		$query = "SELECT * FROM books WHERE (author LIKE '%{$_GET['query']}%') 
			OR (title LIKE '%{$_GET['query']}%') OR uid IN (SELECT book_uid FROM comments WHERE comment LIKE '%{$_GET['query']}%')";
	}
	elseif (isset($_GET['fav']) && $_GET['fav'] != '') {
		$query = "SELECT * FROM books WHERE title = 'Band of Brothers'";
	}
	else {
		$query = "SELECT * FROM books";
	}

	$result = mysql_query($query) or die("SELECT Error:".mysql_error());	
	
?>

<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.0.1/jquery.mobile-1.0.1.min.css" />
	<script src="http://code.jquery.com/jquery-1.6.4.min.js"></script>
	<script src="http://code.jquery.com/mobile/1.0.1/jquery.mobile-1.0.1.min.js"></script>
	<script src="jquery.json-2.3.min.js"></script>
	<script src="jstorage.js"></script>
	<script src="main.js"></script>
	<script type="text/javascript">
		function NewFavorite(id) 
		{
			$.jStorage.set("favorite"+id, id);
			alert("Added to Favorites!");
		}
		function DeFavorite(id) {
			$.jStorage.deleteKey("favorite"+id);
			alert("Removed from  Favorites!");
			document.getElementById("container"+id).style.background = 'none';
		}
		function HighlightFavorites () {
			var favorites = $.jStorage.index();
			var x;
			for (x in favorites) {
				var id = $.jStorage.get(favorites[x])
				document.getElementById("container"+id).style.background = 'yellow';
			}
		}
	</script>
	<link href="style.css" rel="stylesheet" type="text/css">
	<title>CS 179 - Ben B's Book List</title>
</head>

<body>
	<div data-role="page" data-url="/progassn2/index.php">
		<div data-role="header">
			<h1> READING SUGGESTIONS </h1>
		</div>
		<div data-role="content" id="content">
			<form id="search" class="search" name="search" method="get" action="index.php"> 
					<input type="text" name="query" id="query" align="center" placeholder="Search title, author, or comments"/>
					<input type="submit" value="Search" />
			</form>
			<input type='button' onclick='HighlightFavorites()' value='Highlight Favorites'/>
			<br>
			<div class="ui-grid-b" id='booklist' align="center">
				<div class ="ui-bar ui-bar-b">
					<div class="ui-block-a"><b>IMAGE</b></div>
					<div class="ui-block-b"><b>TITLE (AUTHOR)</b></div>
					<div class="ui-block-c"><b>COMMENTS</b></div>
				</div>
				<br>
				<?php
				while ($entry = mysql_fetch_array($result)){
					$pid = $entry['uid'];

					// display the cover image in the 1st column
					echo "<div id='container".$pid."' class = 'ui-bar ui-bar-c'> 
						<div class='ui-block-a'>
						<img src=". $entry['image_url'] . " height='100' alt=Book Cover> </div>";
					
					// display the title and author in the middle
					echo "<div class='ui-block-b'>
						<b>" . $entry['title'] . "</b><br/> (" . $entry['author'] . ")<br>
						<input type='button' onclick='NewFavorite(".$pid.")' value='Favorite'/>
						<input type='button' onclick='DeFavorite(".$pid.")' value='De-Favorite'/>
						<form class='removebookform' method='post' action='remove_book.php'>
							<input type='hidden' name='delete' class='delete' value='" . $pid . "' />
							<input type='submit' value='Delete Book'/>
						</form></div>";
					
					// display the comments
					$commentquery = "SELECT * FROM comments WHERE book_uid='$pid'";
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
					//form for adding comment
					echo "<div class='ui-block-c'> 
							<div data-role='collapsible' data-mini='true'>
								<h6>".$num_comments." Comments</h6>
								<p id='comments".$pid."' style='font-size: 10px'>" . $printthis . "
									<form method='post' class='addcommentform' action='add_comment.php'>
										<textarea name='newcomment' id='newcomment' rows='2' cols='8' placeholder='Add your comment'></textarea>
										<input type='hidden' class='pid' name='pid' id='comment".$pid."' value='" . $pid . "' />
										<br><input type='submit' value='Add Comment'/>
									</form>
								</p>
							</div>
						</div>
					</div>";
				}
				
				?>
			</div>
			<h3 align="Center"> Add to the Book List: </h3>
				<form id="addbookform" class="addbookform" method="post" action="add_book.php"> 
					<input type="text" name="title" id="title" placeholder="Title"/>
					<input type="text" name="author" id="author" placeholder="Author"/>
					<input type="text" name="imageurl" id="imageurl" placeholder="Image URL (Optional)"/>
					<input type="submit" value="Add Book" />
				</form>
		</div>
		<div data-role="footer"></div>
	</div>
</body>

<?
?>