<?php

$page_title = "Advance Search";
	
	
/*include ("includes/twitter.php");

$twitter = new Twitter($new_tweet_val1, $new_tweet_val2);

//set tokens
$twitter->setOAuthToken($tweet_token);
$twitter->setOAuthTokenSecret($tweet_token_secret);

$tweet = rand();
$twitter->statusesUpdate($tweet); */


$aDate = getdate();
$search_date;
$output = "";

if(isset($_POST['date_range'])){
	
	$date_range = stop_injection($_POST['date_range']);
	$search_query = stop_injection($_POST['search_string']); 
	
	$inj_date = check_injection($_POST['date_range']);
	$inj_term = check_injection($_POST['search_string']);
	
	if( $inj_date || $inj_term )
		create_log( "sql_injection_warning" , "Possible injection detected in search_advance", "Date: $date_range ($inj_date)\nSearch Term: $search_query ($inj_term)");
	
	
	switch($date_range){
		case 0: $search_date = date('Y-m-d H:i:s',$aDate[0] - (60*60*24)); break;
		case 7: $search_date = date('Y-m-d H:i:s',$aDate[0] - (60*60*24*7)); break;
		case 14: $search_date = date('Y-m-d H:i:s',$aDate[0] - (60*60*24*14)); break;
		case 30: $search_date = date('Y-m-d H:i:s',$aDate[0] - (60*60*24*31)); break;
		case 365: $search_date = date('Y-m-d H:i:s',$aDate[0] - (60*60*24*365)); break;
	}
	
	
	$query = "SELECT * FROM posted_tweets WHERE content LIKE '%$search_query%' AND date_posted >= '$search_date'";
	$result = mysql_query($query);
	if(mysql_num_rows($result) <= 0){
		$output = "No Result!";
	}else{
		while($row = mysql_fetch_assoc($result)){
			$tweet_content = $row["content"];
			$tweet_author = $row["writer_id"];
		
			 $output .= '<section class="search_result_list">
				<img src="http://a1.twimg.com/profile_images/1497500068/cake-logo_normal.png" alt="qake"/>
				<b>'.$tweet_author.'</b><br/>'.$tweet_content.'
			</section>	';
		}
	}
	
	
}


$content = loadtemplate("search_advance.html");
$content = str_replace("%search_result%", $output, $content);
?>