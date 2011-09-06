<?php

	$page_title = "Search Result";
	$results = "";
	$content = loadtemplate("search_result.html");
	
	
	$search_query = stop_injection($_POST['search_query']);
	
	
	$inj_term = check_injection($_POST['search_query']);
	
	if( $inj_term )
		create_log( "sql_injection_warning" , "Possible injection detected in search_result", "Search Term: $search_query ($inj_term)");
		
	

	$query = "SELECT * FROM posted_tweets WHERE content LIKE '%$search_query%' LIMIT 30";
	$result = mysql_query($query);
	
	if(mysql_num_rows($result) == 0){
		$results .= 'No Matching Tweets';
	}else{
		while($row = mysql_fetch_assoc($result)){
		$results .=  '<section class="search_result_list">
						<img src="http://a1.twimg.com/profile_images/1497500068/cake-logo_normal.png" />
						<b>'.$row["writer_id"].'</b><br/>
						'.$row["content"].'
					  </section><br/>';
		}	
	}
	


	$content = str_replace("%search_result%", $results, $content);

?>