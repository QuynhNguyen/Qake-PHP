<?php
		
	include ("includes/push_tweet.php");
	
	if( !empty( $_POST['tweetID'] ) && is_numeric( $_POST['tweetID'] ) ) {
			$query = "SELECT * FROM pending_tweets WHERE id=" . $_POST['tweetID'];
			$tweet = mysql_query($query);
			if(mysql_num_rows($tweet)==1) {
				$tweet_c = mysql_fetch_assoc($tweet);
				$tcontent = $tweet_c['content'];
				$tid = $tweet_c['id'];
				$wid = $tweet_c['writer_id'];
				$pid = $_SESSION['userID'];
				$post_date = $tweet_c['post_date'];
				
				
				if(empty($tweet_c['post_date'])) {
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
					
					mysql_query("DELETE FROM pending_tweets where id=$tid");
					mysql_query("DELETE FROM tweet_cat where tid=$tid");
					
					push_tweet($tid, $new_tweet_val1, $new_tweet_val2, $tweet_token, $tweet_token_secret);
					
					create_log( "tweet_auth" , "Tweet (ID $tid) has been authorized");
					$content = url_redirect( "?pagename=tweet_pending" , 2 ,  "Tweet authorized." );
					
				}
				else {
					$move_query = "INSERT INTO queued_tweets (id, writer_id, content, post_date, poster_id) VALUES ($tid, '$wid', '$tcontent', $post_date, '$pid')";
					$content = url_redirect( "?pagename=tweet_queued" , 2 ,  "Tweet queued." );
					$result = mysql_query($move_query);
					
					mysql_query("DELETE FROM pending_tweets where id=$tid");

					if(!$result)
						die("bad query");
				}	
				
			} else {
				$content = url_redirect( "?pagename=tweet_pending" , 2 ,  "No tweet with that id in pending." );
			}
			
			
			
	}
	else
		$content = url_redirect( "?pagename=tweet_pending" , 2 ,  "Invalid id. Your ip has been logged." );
	 
	


?>