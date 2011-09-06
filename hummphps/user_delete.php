<?php

$username = addslashes( $_GET['delete_username'] );

$db_get_user = mysql_query( "SELECT * FROM hb_users WHERE username='$username'" ); // Find username
if( $db_get_user = mysql_fetch_array( $db_get_user) ) // If username exists
{
	if( $_GET['confirm'])
	{
		$db_delete = mysql_query( "DELETE FROM hb_users WHERE username='$username'" );
		if( $db_delete )
		{
			create_log( "user_delete" , "User $username was deleted");
			$content = url_redirect("?pagename=users_list" , 2 , "User has been deleted.");
		}
		else
			$content = "Error";
	}
	else
	{
		$content = loadtemplate( "user_delete.html");
		$content = str_replace( "%username%" , $db_get_user['username'] , $content );
		$content = str_replace( "%real_name%" , $db_get_user['real_name'] , $content );
	}
}
else
	$content = "Error: Username not found";

?>