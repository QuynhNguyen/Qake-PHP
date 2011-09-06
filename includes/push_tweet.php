<?php

function push_tweet($tweetid, $key, $secret, $token, $tokenAccess) {
	include ("includes/twitter.php");

	$result = mysql_query("SELECT * FROM posted_tweets where id=$tweetid");
	
	if(mysql_num_rows($result)==1){
	
		$tweet_c = mysql_fetch_assoc($result);
		
		$twitter = new Twitter($key, $secret);

		//set tokens
		$twitter->setOAuthToken($token);
		$twitter->setOAuthTokenSecret($tokenAccess);

		$tweet = $tweet_c['content'];
		$twitter->statusesUpdate($tweet);
	
	}

}

?>

