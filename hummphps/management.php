<?php


$page_title = "Twitter Management";


if( user_hasright("show_dequeue_link"))
$content .= '<a href="?pagename=tweet_dequeue&dequeue_passphrase='.$dequeue_passphrase.'">Trigger Tweet-dequeue</a><br>';

if ( user_hasright("twitter_search")   )
$content .= '<a href="?pagename=twitter_search">Phrase/Hash Tag Twitter Search</a><br>';

if ( user_hasright("write_tweet")  )
$content .= '<a href="?pagename=tweet_create">Create/Schedule Tweets</a><br>';

if ( user_hasright("view_my_pending") )
{
	$num_tweets = mysql_num_rows(mysql_query("SELECT * FROM pending_tweets WHERE writer_id='" . $_SESSION['userID'] . "'"));
	if( $num_tweets )
		$content .= '<a href="?pagename=tweet_my_pending"><b>View Your Pending Tweets ('.$num_tweets.')</b></a><br>';
	else
		$content .= '<a href="?pagename=tweet_my_pending">View Your Pending Tweets</a><br>';
}


if ( user_hasright("view_pending")  )
{
	$num_tweets = mysql_num_rows(mysql_query("SELECT * FROM pending_tweets"));
	if( $num_tweets )
		$content .= '<a href="?pagename=tweet_pending"><b>View all Pending Tweets ('.$num_tweets.')</b></a><br>';
	else
		$content .= '<a href="?pagename=tweet_pending">View all Pending Tweets</a><br>';
}


if ( user_hasright("view_unposted")  )
{
	$num_tweets = mysql_num_rows(mysql_query("SELECT * FROM queued_tweets"));
	if( $num_tweets )
		$content .= '<a href="?pagename=tweet_queued"><b>View all Queued Tweets ('.$num_tweets.')</b></a><br>';
	else
		$content .= '<a href="?pagename=tweet_queued">View all Queued Tweets</a><br>';
}

if ( user_hasright("group_rights")  )
$content .= '<a href="?pagename=category_create">Create new group</a><br>';

if ( user_hasright("group_rights")  )
$content .= '<a href="?pagename=category_management">Manage groups</a><br>';

if ( user_hasright("rbac_manage")  )
$content .= '<a href="?pagename=pages_list">Manage pages ($pagenames)</a><br>';

if ( user_hasright("rbac_manage")  )
$content .= '<a href="?pagename=users_permissions_list">Manage permissions</a><br>';

if ( user_hasright("rbac_manage")  )
$content .= '<a href="?pagename=users_roles_list">Manage roles</a><br>';

if ( user_hasright("user_add") )
$content .= '<a href="?pagename=users_add">Add a user</a><br>';

if( user_hasright( "user_manage" )  )
$content .= '<a href="?pagename=users_list">Manage users</a><br>';

$content .= '<a href="?pagename=users_show_permissions">Display my permissions</a><br>';

$content .= '<a href="?pagename=user_change_password">Change my password</a><br>';



?>