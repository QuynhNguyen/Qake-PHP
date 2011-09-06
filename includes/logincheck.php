<?php



if( $_SESSION['loggedin'] )
{	
	$loggedin = 1;
	$user_loggedin = 1;
	$user['pages']++;
	
	
	if( strtotime( $_SESSION['user']['date_next_pw_reset'] ) < time() && $pagename != 'logout') // If password needs to be changed
	{
		$errmsg = "You password expired.<br>";
		$pagename = "user_change_password";
	}
	
}



?>
