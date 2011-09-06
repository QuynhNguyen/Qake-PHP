<?php

	if (user_hasright("delete_tweet"))
	{
		if( !empty( $_POST['tweetID'] ) && is_numeric( $_POST['tweetID'] ) )
		{
			$query = "DELETE FROM pending_tweets WHERE id=" . $_POST['tweetID'];
			mysql_query($query);
			$query = "DELETE FROM tweet_cat where tid=" . $_POST['tweetID']; 
			mysql_query($query);
			$content = url_redirect( '?pagename=tweet_pending' , 2 , "The entry has been deleted" );
			create_log( "delete_tweet" , "Tweet (ID ".$_POST['tweetID'].") has been deleted");
		}
		else
			$content = "No valid ID given: " . $_POST['tweetID'];
	} 
	else 
	{
		$content = "You have no permission to access this page.";
	}


?>