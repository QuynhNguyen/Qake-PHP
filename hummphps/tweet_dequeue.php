<?php
		
include ("includes/push_tweet.php");
	
if($_GET['dequeue_passphrase']=="384924h9erwgh983e89hghSDLFKJLKWNE8") {
	$query = "SELECT * FROM queued_tweets";
	$tweet = mysql_query($query);
	while($tweet_c = mysql_fetch_assoc($tweet)) {
		
		$tcontent = $tweet_c['content'];
		$tid = $tweet_c['id'];
		$wid = $tweet_c['writer_id'];
		$pid = $_SESSION['userID'];
		$post_date = $tweet_c['post_date'];
		
		
		if($post_date < time()) {
			$move_query = "INSERT INTO posted_tweets (id, content, writer_id, poster_id) VALUES ($tid, '$tcontent', '$wid', '$pid')";
			mysql_query($move_query);
			$categories = mysql_query("SELECT * FROM tweet_cat where tid=$tid");
			if(!$categories) {
				die("bad query");
			}
			while($cat = mysql_fetch_assoc($categories)) {
				$ccid = $cat['cid'];
				mysql_query("INSERT INTO posted_cat (tid, cid) VALUES ($tid, $ccid)");
			}
			
			mysql_query("DELETE FROM queued_tweets where id=$tid");
			mysql_query("DELETE FROM tweet_cat where tid=$tid");
			
			push_tweet($tid);
					
			
			
		}
		
		
	} 
	
} else {
	$content = NO_ACCESS;
}

?>