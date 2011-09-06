<?php


$page_title = "Write Tweet";

include("./includes/tweet_form_helper.php");
$content = loadtemplate("tweet_create.html");
$errors = "";

$content = str_replace("%ActionType%", "Write Tweet", $content);


$get_cats = "SELECT * FROM category";	
$groups = mysql_query($get_cats);
$checkbox = getRow($content,0);
		$content = markRow($content,0);
		while($group = mysql_fetch_assoc($groups)) {
			$tmp = $checkbox;
			$tmp = str_replace("%group_num%", $group['id'], $tmp);
			$tmp = str_replace("%group_name%", $group['name'], $tmp);			
		
			if($_POST['group_' . $group['id']] == 1) // if was selected before
				$tmp = str_replace( "%checked%" , "checked" , $tmp ); // check it
			else
				$tmp = str_replace( "%checked%" , "" , $tmp ); // check it
	
			$content = putRow( $content, $tmp , 0 );	
		}


if(isset($_POST['tweetContent']))
		$content = str_replace("%tweetContent%", $_POST['tweetContent'], $content);
	else
		$content = str_replace("%tweetContent%", "", $content);
	if($_POST['schedule'] == 2) {
		$content = str_replace("%selected2%", 'selected="selected"', $content);
		$content = str_replace("%selected1%", "", $content);
	}		
	else {
		$content = str_replace("%selected1%", 'selected="selected"', $content);
		$content = str_replace("%selected2%", "", $content);
	}
	if(isset($_POST['date']))
		$content = str_replace("%date%", $_POST['date'], $content);
	else
		$content = str_replace("%date%", "", $content);
	if(isset($_POST['time']))
		$content = str_replace("%time%", $_POST['time'], $content);
	else
		$content = str_replace("%time%", "", $content);
	

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	
	//Populate values if error
	
	
	
	//Assign global post value to local variable	
	$tweet_content = stop_injection($_POST['tweetContent']);
	
	$post_date = strtotime($_POST['date'] . " " . $_POST['time'] );
	$writer_id = $_SESSION['userID'];
	
	
	
	
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
			$query = "INSERT INTO pending_tweets (content, writer_id) VALUES ('$tweet_content', '$writer_id')";
		} else {
			$query = "INSERT INTO pending_tweets (content, writer_id, post_date) VALUES ('$tweet_content', '$writer_id', $post_date)";
		} 

	
		//execute query
		$result = mysql_query($query);
		if(!$result)
			die("bad query");
			
		//register categories
		mysql_data_seek($groups,0);
		$iid = mysql_insert_id();
		while($group = mysql_fetch_assoc($groups)) {
			$gid = $group['id'];
			if( $_POST['group_' . $gid] ) // if was selected 
					mysql_query("INSERT INTO tweet_cat (tid, cid) VALUES ($iid, $gid)"); // register	
		}
	
		//Result Page
		
		create_log( "tweet_create" , "Tweet has been created");
		$content = url_redirect( "?pagename=tweet_my_pending" , 2 ,  "Tweet submitted." );
			
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