<?php

	// Start Session ID
	session_name("sid");
	session_start();
	
	$inc_var = @include "settings/variables.php";
	if ( empty( $inc_var ) )
		die( "Error: Couldn't load variables!" );
		
	// Include Rights Determination Script (usage: user_hasrights("rightname"); ) returns TRUE or FALSE;
	$inc_urr = @include "includes/user_hasright.php";
	if ( empty( $inc_urr ) )
		die( "Error: Couldn't load rights management!" );
		
	// Connect to MySQL DB
	$db = @mysql_connect( $db_server , $db_user , $db_pass ) or die( "MySQL Connection Error" );
	$db_select = @mysql_select_db( $db_name );


	if ((user_hasright("tweet_delete")||$_POST['writerID']==$_SESSION['userID'])&&is_int($_POST['tweetID']))) {
		$query = "DELETE FROM pending_tweets WHERE id=" . $_POST['tweetID'];
		mysql_query($query);
		header('Location: http://qake.info/hummingbird/index?pagename=tweet_pending');
	} else {
		header('Location: http://qake.info/error-pages/403');
	}

	// Close MySQL Connection
	$db_close = @mysql_close( $db );


?>