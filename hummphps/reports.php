<?php

function getReport($username, $find) {
	$page = file_get_contents('http://twitter.com/users/show/'.$username.'.xml');
	$begin = '<'.$find.'>';
	$end = '</'.$find.'>';
	$parts = explode($begin,$page);
	$page = $parts[1];
	$parts = explode($end,$page);
	$tcount = $parts[0];
	if($tcount == '') { $tcount = '0'; }
	return $tcount;
}
//Page Load
$page_title =  "Reports Facility";
$page_sidebar_enable = 1; // If set to TRUE sidebar will show up on this page
$content = loadtemplate( "reports.html" );
//Variables
$rf_TnameID = "TeamQake"; //Your Team
$rf_Timg = getReport($rf_TnameID, "profile_image_url");
$rf_Ttweet = getReport($rf_TnameID, "statuses_count");
$rf_Tfollowing = getReport($rf_TnameID, "friends_count");
$rf_Tfollowers = getReport($rf_TnameID, "followers_count");
$rf_Tfavorites = getReport($rf_TnameID, "favourites_count");
$rf_Tlisted = getReport($rf_TnameID, "listed_count");
$rf_Tretweet = getReport($rf_TnameID, "retweet_count");

$rf_nameID = stop_injection($_POST["rf_id"]); //Search Team
$rf_img = getReport($rf_nameID, "profile_image_url");
$rf_tweet = getReport($rf_nameID, "statuses_count");
$rf_following = getReport($rf_nameID, "friends_count");
$rf_followers = getReport($rf_nameID, "followers_count");
$rf_favorites = getReport($rf_nameID, "favourites_count");
$rf_listed = getReport($rf_nameID, "listed_count");
$rf_retweet = getReport($rf_nameID, "retweet_count");

$rf_chartMax = max($rf_Ttweet, $rf_Tfollowing, $rf_Tfollowers, $rf_Tfavorites, $rf_Tlisted, $rf_Tretweet, $rf_tweet, $rf_following, $rf_favorites, $rf_listed, $rf_retweet);
	
if ($rf_Timg != "0") {
	$content = str_replace("%rf_error%", "none", $content);
	$content = str_replace("%warning%", "", $content);

	//Page Start
	$content = str_replace("%twt_img%", '<img src="' . $rf_Timg . '" alt="' . $rf_TnameID . '" />' , $content );
	$tmp_row = getRow($content,0);
	$tmp_row = str_replace( "%rf_id%" , $rf_TnameID, $tmp_row );
	$tmp_row = str_replace( "%rf_twt%" , $rf_Ttweet, $tmp_row );
	$tmp_row = str_replace( "%rf_fing%" , $rf_Tfollowing, $tmp_row );
	$tmp_row = str_replace( "%rf_fers%" , $rf_Tfollowers, $tmp_row );
	$tmp_row = str_replace( "%rf_fav%" , $rf_Tfavorites, $tmp_row );
	$tmp_row = str_replace( "%rf_list%" , $rf_Tlisted, $tmp_row );
	$tmp_row = str_replace( "%rf_retwt%" , $rf_Tretweet, $tmp_row );
	$content = putRow( $content, $tmp_row , 0 );

	$content = str_replace( "%reports_image1%" , '<img src="http://chart.apis.google.com/chart?chs=500x225&amp;cht=p&amp;chd=t:' . $rf_Tfollowing . ',' . $rf_Tfollowers . '&amp;chdl=Following|Followers&amp;chl=Following|Followers&amp;chma=20,25,25,25&amp;chco=80C65A|4D89F9&amp;chtt=Following+v.s.+Followers+on+Twitter" width="500" height="225" alt="Following vs Followers on Twitter" />' , $content );
	if ($rf_nameID) {
		$content = str_replace("%vis%", "none", $content);
		
		$content = str_replace("%twt_img1%", '<img src="'. $rf_img .'" alt="' . $rf_nameID . '" />' , $content );
		$tmp_row = getRow($content,1);
		$tmp_row = str_replace( "%rf_id1%" , $rf_nameID, $tmp_row );
		$tmp_row = str_replace( "%rf_twt1%" , $rf_tweet, $tmp_row );
		$tmp_row = str_replace( "%rf_fing1%" , $rf_following, $tmp_row );
		$tmp_row = str_replace( "%rf_fers1%" , $rf_followers, $tmp_row );
		$tmp_row = str_replace( "%rf_fav1%" , $rf_favorites, $tmp_row );
		$tmp_row = str_replace( "%rf_list1%" , $rf_listed, $tmp_row );
		$tmp_row = str_replace( "%rf_retwt1%" , $rf_retweet, $tmp_row );
		$content = putRow( $content, $tmp_row , 1 );
		
		$content = str_replace( "%reports_image2%" , '<img src="http://chart.apis.google.com/chart?
		cht=bvg&chs=450x200&chd=t:' . $rf_Ttweet . ',' . $rf_Tfollowing . ',' . $rf_Tfollowers . ',' . $rf_Tfavorites. ',' . $rf_Tlisted . ',' . $rf_Tretweet . '|' . $rf_tweet . ',' . $rf_following . ',' . $rf_followers . ',' . $rf_favorites . ',' . $rf_listed . ',' . $rf_retweet . '&chxr=1,0,' . $rf_chartMax . '&chds=0,' . $rf_chartMax . '&chco=80C65A,4D89F9&chbh=15,0,20&chxt=x,y&chxl=0:|Tweets|Following|Followers|Favorites|Listed|Retweet&chdl=' . $rf_TnameID . '|' . $rf_nameID . '&chg=0,8.3,5,5" alt="Comparison Chart"/>', $content );	
	}
	else {
		$content = str_replace("%vis%", "hidden", $content);
	}
}
else {
	$content = str_replace("%rf_error%", "hidden", $content);
	$content = str_replace("%vis%", "hidden", $content);
	$content = str_replace("%warning%", '
		<header><h1>Connection loss!</h1></header>
			<p>Sorry! Our connection to Twitter is currently unavailable.
			<br />
			Please try again in a couple of minutes.</p>
			<section>
				<a href="/index">Return to index</a>
			</section>
			
	', $content);
}	
?>