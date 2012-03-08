function removebook() {
		var $this = $(this);
		var pid = $this.children('.delete').val();  
		$.ajax({
		  type: 'POST',
		  url: 'remove_book.php',
		  data: {
			'delete': pid,
			ajax: 1
		  },
		  dataType: 'json',
		  success: function(data) {
			$('#container'+data).remove();
		  }
		});
		return false;
	}

$(document).ready(function() {

	$(".addbookform").submit(function() {
		//check that forms are filled out
		var title = $("input#title").val();  
		if (title == "") {
			return false;
		}
		var author = $("input#author").val();  
		if (author == "") {
			return false;
		}
		var imageurl = $("input#imageurl").val(); 
		
		$.ajax({
		  type: 'POST',
		  url: 'add_book.php',
		  data: {
			title: title,
			author: author,
			imageurl: imageurl,
			ajax: 1,
		  },
		  dataType: 'html',
		  success: function(data) {
			data = $(data);	
			var $page = $('<div data-role="page"><div data-role="header"></div><div data-role="content" id="content"></div></div>');
			$page.find('#content').append(data);
			$.mobile.pageContainer.append($page);
			$page.page();
			$page.remove();
			data = $page.find('#content').children();
			data.find(".removebookform").submit( removebook );
			$("#booklist").append(data);
		  }
		});
		
		return false;
	});
	
	$(".removebookform").submit( removebook );

	$(".addcommentform").submit(function() {
		
		var $this = $(this);
		var pid = $this.children('.pid').val();  
		var newcomment = $this.children('#newcomment').val();  

		$.ajax({
		  type: 'POST',
		  url: 'add_comment.php',
		  data: {
			pid: pid,
			newcomment: newcomment,
		  },
		  dataType: 'html',
		  success: function(data) {
			var oldcomments = document.getElementById("comments"+pid).innerHTML;
			var updatedcomments = oldcomments + data; 
			document.getElementById("comments"+pid).innerHTML = updatedcomments;
			}
		})
		return false;
	});
	
	$(".#search").submit( function() {
		var query = $("input#query").val();
		$.ajax({
		  type: 'GET',
		  url: 'index.php',
		  data: {
			query: query,
			ajax: 1,
		  },
		  dataType: 'html',
		  success: function(data) {
			data = $(data);	
			var $page = $('<div data-role="page"><div data-role="header"></div><div data-role="content" id="content"></div></div>');
			$page.find('#content').append(data);
			$.mobile.pageContainer.append($page);
			$page.page();
			$page.remove();
			data = $page.find('#content').children();
			$("#booklist").replaceWith(data);
		  }
		})
		return false;
	});
	
	$("#favorites").submit( function() {
		var fav = $("input#fav").val();
		$.ajax({
		  type: 'GET',
		  url: 'index.php',
		  data: {
			fav: fav,
			ajax: 1,
		  },
		  dataType: 'html',
		  success: function(data) {
			data = $(data);	
			var $page = $('<div data-role="page"><div data-role="header"></div><div data-role="content" id="content"></div></div>');
			$page.find('#content').append(data);
			$.mobile.pageContainer.append($page);
			$page.page();
			$page.remove();
			data = $page.find('#content').children();
			$("#booklist").replaceWith(data);
		  }
		})
		return false;
	});
	
});



