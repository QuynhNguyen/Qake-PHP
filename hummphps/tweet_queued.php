<?php


$page_title = "All Queued Tweets";
$content = loadtemplate( "tweet_queued.html" );

//Get number of pending tweets
$query = "SELECT * FROM queued_tweets";
$tweets = mysql_query($query);
$num_tweets = mysql_num_rows($tweets);

//Get row
$row = getRow($content,0);
$content = markRow($content,0);

while($record = mysql_fetch_assoc($tweets)) {
	$tmp_row = $row;
	$tmp_row = str_replace("%tweetID%", $record['id'], $tmp_row);
	$tmp_row = str_replace("%tweetContent%", $record['content'], $tmp_row);
	$tmp_row = str_replace("%writerID%", $record['writer_id'], $tmp_row);
	$tmp_row = str_replace("%postDate%", date("m/d/Y H:i:s e" , (int) $record['post_date']), $tmp_row);

	
	$id = $record['id'];
	$q = "SELECT * FROM tweet_cat WHERE tid=$id";
	$cats = mysql_query($q);
	while($cat = mysql_fetch_assoc($cats)) {
		$cid = $cat['cid'];
		$cat_r = mysql_fetch_assoc(mysql_query("SELECT * FROM category WHERE id=$cid"));
		if (empty($cat_names)) {
			$cat_names = $cat_r['name'];
		} else {
			$cat_names .= ", " . $cat_r['name'];
		}
	}
	
	$tmp_row = str_replace("%categories%", $cat_names, $tmp_row);
	$cat_names = "";
	$content = putRow( $content, $tmp_row , 0 );
}



?>