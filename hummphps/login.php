<?php 


	
if(  $_SERVER['REQUEST_METHOD'] == "POST" ) // if username and password were submitted
{
	$content = loadtemplate( "login.html" );
	$content = str_replace( "%errmsg%" , "Username/Password not found.", $content);
	
	
	$username = stop_injection( $_POST['login_username'] );
	$password = stop_injection( $_POST['login_password'] );
	
	$un_inj = check_injection($_POST['login_username']);
	$pw_inj = check_injection($_POST['login_password']);
	
	if( $un_inj || $pw_inj )
	{
		create_log( "sql_injection_warning" , "Possible injection detected in login (dummy)", "Username: $username ($un_inj)\nPassword: $password ($pw_inj)\n");
	}
		
	create_log( "dummy_login" , "Client tried to log in on dummy page", "Username: $username\nPassword: $password");
}

else // if no data submitted
{
	$content = loadtemplate( "login.html" );
	$content = str_replace( "%errmsg%" , "" , $content);
}

?>