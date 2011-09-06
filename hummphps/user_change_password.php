<?php



if( $_SERVER['REQUEST_METHOD'] == "POST" && isSet( $_POST['password_confirm'])) // if there was an error or no data submitted yet
{
	$err = 0;
	$errmsg = "";
	
	if( strlen( $_POST['password']) < 5 ) // if password too short
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
	if( md5( $_POST['password'] ) == $_SESSION['user']['password_md5']) // If passwords dont match
	{
		$err = 1;
		$errmsg .= "Can't use default password!<br>";
	}
	
	if( !$err ) // if there was no error
	{
		$password_md5 = md5( $_POST['password'] );
		$tmp = time()+3600*24*365; // one year in future
		$db_update = mysql_query( "UPDATE hb_users SET password_md5='$password_md5',date_next_pw_reset=FROM_UNIXTIME(". $tmp .") WHERE username='".$_SESSION['userID']."'");
		$content = url_redirect( "?pagename=logout" , 1 , "Your password has been changed, you will now be logged out.");
		//include "logout.php";
		
		create_log( "user_change_password" , "User $username changed their password");
	}
	
}

if( $err || $_SERVER['REQUEST_METHOD'] != "POST" ) // if there was an error or no data submitted yet
{
	$content = loadtemplate( "user_change_password.html");
	
	if( !$errmsg )
	{
		$expires = datetostring( $_SESSION['user']['date_next_pw_reset'] );
		$errmsg = "Info: Your password expires in " . $expires[0] ."<br>";
	}
		
	$content = str_replace( "%errmsg%" , $errmsg , $content );
}

?>