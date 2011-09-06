<?php

include("./includes/tweet_form_helper.php");
$content = loadtemplate("tweet_edit.html");

if($_SERVER['REQUEST_METHOD'] == 'POST') { 
	if(isset($_POST['tid']))
		$tid = $_POST['tid'];
} else {
	if(isset($_GET['tid']))
		$tid = $_GET['tid'];
}

if(empty($tid)||!is_numeric($tid)) {
	$content = url_redirect( "?pagename=tweet_pending" , 2 ,  "Invalid id. Your ip has been logged." );
	$errors = "true";
} else {$tweet_r = mysql_query("SELECT * FROM pending_tweets WHERE id=$tid");
	$tweet = mysql_fetch_assoc($tweet_r);
	if(!$tweet) {
		$content = url_redirect( "?pagename=tweet_pending" , 2 ,  "The tweet you are trying to edit does not exist. It may have been deleted or authorized." );
		$errors = "true";
	} else if (!user_hasright("edit_tweet")&&$_SESSION['userID']!=$tweet['writer_id']) {
		$content = url_redirect( "?pagename=tweet_pending" , 2 ,  "You do not have permission to edit tweets you have not written." );
		$errors = "true";
	}

}









$page_title = "Edit Tweet";


if(!$errors) {
	$content = str_replace("%ActionType%", "Edit Tweet", $content);
	$content = str_replace("%tweetContent%", $tweet['content'], $content);
	if(!empty($tweet['post_date'])) {
		$content = str_replace("%selected2%", 'selected="selected"', $content);
		$content = str_replace("%selected1%", "", $content);
		$date_s = date("Y-m-d", (int) $tweet['post_date']);
		$time_s = date("H:i", (int) $tweet['post_date']);
	}		
	else {
		$content = str_replace("%selected1%", 'selected="selected"', $content);
		$content = str_replace("%selected2%", "", $content);
		$date_s = "";
		$time_s = "";
	}
	
	
	$content = str_replace("%date%", $date_s, $content);
	$content = str_replace("%time%", $time_s, $content);
	$content = str_replace("%tid%", $tid, $content);
	
	$get_cats = "SELECT * FROM category";	
	$groups = mysql_query($get_cats);
	$checkbox = getRow($content,0);
			$content = markRow($content,0);
			while($group = mysql_fetch_assoc($groups)) {
				$tmp = $checkbox;
				$tmp = str_replace("%group_num%", $group['id'], $tmp);
				$tmp = str_replace("%group_name%", $group['name'], $tmp);			
				$gid = $group['id'];
				$test_cat = mysql_query("SELECT * FROM tweet_cat WHERE tid=$tid AND cid=$gid");
				if(mysql_num_rows($test_cat)==1) // if was selected before
					$tmp = str_replace( "%checked%" , "checked" , $tmp ); // check it
				else
					$tmp = str_replace( "%checked%" , "" , $tmp ); 
		
				$content = putRow( $content, $tmp , 0 );	
			}


		
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	
	//Populate values if error
	
	
	
	//Assign global post value to local variable	
	$tweet_content = stop_injection($_POST['tweetContent']);
	
	$post_date = strtotime($_POST['date'] . " " . $_POST['time'] );
	
	
	
	
	mysql_data_seek($groups,0);
	$cat_selected = false;
	while($group = mysql_fetch_assoc($groups)) {
		if( isset($_POST['group_' . $group['id']]) ){
			$cat_selected = true;
			break;
		}
	}
	
	
	$errors = tweetFormValidation($tweet_content, $cat_selected, $post_date, $_POST['schedule'] == 2, $_POST['date'], $_POST['time']);
			
	if(empty($errors)){
		if($_POST['schedule'] == 1) {
			$query = "UPDATE pending_tweets SET content='$tweet_content', post_date=null WHERE id=$tid";
		} else {
			$query = "UPDATE pending_tweets SET content='$tweet_content', post_date=$post_date WHERE id=$tid ";
		} 

	
		//execute query
		$result = mysql_query($query);
		if(!$result)
			die("bad query");
			
		mysql_query("DELETE FROM tweet_cat where tid=$tid");
			
		//register categories
		mysql_data_seek($groups,0);
		while($group = mysql_fetch_assoc($groups)) {
			$gid = $group['id'];
			if( $_POST['group_' . $gid] ) {
					mysql_query("INSERT INTO tweet_cat (tid, cid) VALUES ($tid, $gid)"); // register
			}
		}
	
		//Result Page
		$content = url_redirect( "?pagename=tweet_pending" , 2 ,  "Tweet edited." );
		
		create_log( "tweet_edit" , "Tweet (ID $tid) has been edited.");
			
	} else {
		//Result Page
		
		$content = str_replace("%success%", "", $content);
		$content = str_replace("%error%", "<div class=\"error\">".$errors."</div>", $content);	
	}

}
else {

	$content = str_replace("%success%", "", $content);
	$content = str_replace("%error%", "", $content);
}



?>