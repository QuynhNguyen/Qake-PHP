<?php

$username = addslashes( $_GET['edit_username'] );

$db_get_user = mysql_query( "SELECT * FROM hb_users WHERE username='$username'" ); // Find username
if( $db_get_user = mysql_fetch_array( $db_get_user) ) // If username exists
{
	$err = 0;
	$errmsg = "";
	
	if( $_SERVER['REQUEST_METHOD'] == "POST" ) // If form data was submitted
	{
		if( strlen( $_POST['real_name']) > 100 ) // if real name too long
		{
			$err = 1;
			$errmsg .= "Real Name is too long (100 char max)<br>";
		}
		if( strlen( $_POST['password']) < 5 && !empty( $_POST['password']) ) // if password too short
		{
			$err = 1;
			$errmsg .= "Password is too short (5 char min)<br>";
		}
		if( strlen( $_POST['password']) > 128 ) // if password too long
		{
			$err = 1;
			$errmsg .= "Password is too long (128 char max)<br>";
		}
		if( $_POST['password_confirm'] != $_POST['password'] ) // If passwords dont match
		{
			$err = 1;
			$errmsg .= "Passwords don't match<br>";
		}
		
		if( !$err ) // If there was no error
		{
			
			$md5_pass = md5($_POST['password']);
			$real_name = addslashes( $_POST['real_name'] );
			
			$query = "UPDATE hb_users SET real_name='$real_name'";
			if( !empty( $_POST['password'] ))
				$query .= ", password_md5='$md5_pass', date_next_pw_reset=NOW()";
			$query .= " WHERE username='$username'";
			$db_update = mysql_query( $query );
			
			if( $db_update )
			{
				create_log( "user_edit" , "User $username was edited" );
				$content = url_redirect( "?pagename=users_list" , 2 , "User was edited");
			}
			else
				$content = "Error: $query";
		}
	}
	
	if( $err || $_SERVER['REQUEST_METHOD'] != "POST" ) // if there was an error or no data submitted yet
	{
		$content = loadtemplate( "user_edit.html" );
		
		$content = str_replace( "%edit_username%" , $_GET['edit_username'] , $content );
		$content = str_replace( "%password%" , "" , $content );
		$content = str_replace( "%real_name%" , $db_get_user['real_name'] , $content );
		$content = str_replace( "%password_confirm%" , "" , $content );
		
		if( $errmsg )
			$content = str_replace( "%errmsg%" , $errmsg . "<br>" , $content );
		else
			$content = str_replace( "%errmsg%" , "" , $content );
		
	}
}
else
	$content = "Error: Username not found";

?>