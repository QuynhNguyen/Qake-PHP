<?php

$page_sidebar_enable = 1;
$content = loadtemplate( "home.html" );

$output = "";
$tweet_list = "";
$tab_links = "";
$div_tabs = "";
$i = 2;

//Query From Category
$query = "SELECT * FROM category";
$result = mysql_query($query);

if(mysql_num_rows($result) > 0){
	/*
	 * Make sure to check what happen if no tid associate with category id
	 * */
	while($row = mysql_fetch_assoc($result)){
		$category_id = $row["id"];
		
		//After we got category id, we want to find the associated tid with category id
		//Multiple Tweet into 1 category
		$query2 = "SELECT * FROM posted_cat WHERE cid = '$category_id' ORDER BY id DESC";
		$result2 = mysql_query($query2);
		
		if(mysql_num_rows($result2) > 0){
			
			while($row2 = mysql_fetch_assoc($result2)){
			
				$tweet_id = $row2["tid"];
				
				//After we got the tweet_id, we want to find the tweet content
				$query3 = "SELECT * FROM posted_tweets WHERE id = '$tweet_id'";
				$result3 = mysql_query($query3);
				$row3 = mysql_fetch_assoc($result3);
				
				if(mysql_num_rows($result3) > 0){
					//Assign value to local variable
					$tweet_content = $row3["content"];
					$tweet_author_id = $row3["writer_id"];
					$get_author = mysql_query( "SELECT real_name FROM hb_users WHERE username='$tweet_author_id'");
					if( $get_author = mysql_fetch_array( $get_author ))
						$tweet_author = $get_author['real_name'];
					else
						$tweet_author = "Unknown";
					$tweet_list .= '<section class="search_result_list">
							<img src="http://a1.twimg.com/profile_images/1497500068/cake-logo_normal.png" alt="qake"/>
							<b>'.$tweet_author.'</b><br/>'.$tweet_content.'
						</section>	';	
				}
			}
		}else{
			$tweet_list .= "No Tweets Yet!";
		}
		
		
		//Appending Stuff
		$tab_links .= '<li><a href="#tabs-'.$i.'">'.$row["name"].'</a></li> ';
		$div_tabs .= '<div id="tabs-'.$i.'">'.$tweet_list.'</div>';
		$i++; 
		$tweet_list = ""; //reset
	}	
}


$content = str_replace("%tab_links%", $tab_links, $content);
$content = str_replace("%tab_div%", $div_tabs , $content);

//Getting Global Tweets
/*$query = "SELECT * FROM posted_cat";
$result = mysql_query($query);
if(mysql_num_rows($result) <= 0){
	$tweet_list .= "No Tweets Yet!";
}else{
	while($row = mysql_fetch_assoc($result)){
		$tweet_id = $row["tid"];
		
		$query2 = "SELECT * FROM pending_tweets WHERE id = '$tweet_id'";
		$result2 = mysql_query($query2);
		if(mysql_num_rows($result2) > 0){
			while($row2 = mysql_fetch_assoc($result2)){
					//Assign value to local variable
					$tweet_content = $row2["content"];
					$tweet_author = $row2["writer_id"];
					
					$tweet_list .= '<section class="search_result_list">
							<img src="http://a1.twimg.com/profile_images/1497500068/cake-logo_normal.png" alt="qake"/>
							<b>'.$tweet_author.'</b><br/>'.$tweet_content.'
						</section>	';	
			}
		}else{
			$tweet_list .= "No Tweets Yet!";
		}
	}	
}*/



$query = "SELECT * FROM posted_tweets ORDER BY date_posted DESC";
$result = mysql_query($query);

	while($row = mysql_fetch_assoc($result)){
		//Assign value to local variable
		$tweet_content = $row["content"];
		$tweet_author_id = $row["writer_id"];
		$get_author = mysql_query( "SELECT real_name FROM hb_users WHERE username='$tweet_author_id'");
		if( $get_author = mysql_fetch_array( $get_author ))
			$tweet_author = $get_author['real_name'];
		else
			$tweet_author = "Unknown";
		
		$tweet_list .= '<section class="search_result_list">
				<img src="http://a1.twimg.com/profile_images/1497500068/cake-logo_normal.png" alt="qake"/>
				<b>'.$tweet_author.'</b><br/>'.$tweet_content.'
			</section>	';	
	}




$content = str_replace("%global_tweets%", $tweet_list , $content);




?>