<?php


	if (user_hasright("tweet_delete") || $_POST['writerID']==$_SESSION['userID'] )
	{
		if( !empty( $_POST['tweetID'] ) && is_numeric( $_POST['tweetID'] ) )
		{
			$query = "DELETE FROM queued_tweets WHERE id=" . $_POST['tweetID'];
			mysql_query($query);
			$content = url_redirect( 'http://qake.info/hummingbird/index?pagename=tweet_queued' , 2 , "The entry has been deleted" );
			create_log( "delete_tweet" , "Queued Tweet (ID ".$_POST['tweetID'].") has been deleted");
		}
		else
			$content = "No valid ID given: " . $_POST['tweetID'];
	} 
	else 
	{
		$content = "You have no permission to access this page.";
	}


?>