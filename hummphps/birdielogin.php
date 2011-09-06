<?php 


function resetFailedLogins( $username )
{
	$db_update = mysql_query( "UPDATE hb_users SET login_fails='0' WHERE username='$username'");
	return $db_update;
}

function addFailedLogin( $username )
{
	$db_update = mysql_query( "UPDATE hb_users SET login_fails=login_fails+1 WHERE username='$username'");
	return $db_update;
}


if( $user_loggedin )
{
	$content = url_redirect( "?pagename=management" );
}
else
{
	
	if(  $_SERVER['REQUEST_METHOD'] == "POST" ) // if username and password were submitted
	{
		$content = loadtemplate( "login.html" );
		$username = stop_injection( $_POST['login_username'] );
		$password = stop_injection( $_POST['login_password'] );
		
		$un_inj = check_injection($_POST['login_username']);
		$pw_inj = check_injection($_POST['login_password']);
		
		if( $un_inj || $pw_inj )
		{
			create_log( "sql_injection_warning" , "Possible injection detected in birdielogin (real)", "Username: $username ($un_inj)\nPassword: $password ($pw_inj)\n");
		}
	
		// If there are less than or 5 failed login attempts in the past 5 minutes
		$tmp = time()-300;
		if( mysql_num_rows( mysql_query( "SELECT id FROM hb_logs WHERE log_type='login_error' AND REMOTE_ADDR='".$_SERVER['REMOTE_ADDR']."' AND datetime > FROM_UNIXTIME(".$tmp.")" ) ) <= 5 )
		{
			$db_getuser = mysql_query("SELECT * FROM hb_users WHERE username='$username'");
			if( $db_getuser = mysql_fetch_array( $db_getuser ) ) // if entry found
			{
				if( $username == $db_getuser['username'] ) // if username exists / probably redundant
				{
					if( strtotime( $db_getuser['login_suspended_until'] ) < time() ) // If account isnt suspended
					{
						if( $db_getuser['login_fails'] <= 10 ) // If wrong password hasnt been entered more than 10 times
						{
							if( md5( $password ) == $db_getuser['password_md5'] ) // If passwords match
							{
								if( empty( $db_getuser['active_until']) || strtotime( $db_getuser['active_until']) > time() ) // if account is still active
								{
									$_SESSION['loggedin'] = true;
									$_SESSION['userID'] = $db_getuser['username'];
									if($_POST['remember']=="ON")
									{
										session_set_cookie_params('2592000');
										session_regenerate_id(true);
									}
									$_SESSION['user'] = $db_getuser;
									
									// Get users permissions
									$db_getpermissions = mysql_query( "
				SELECT permission_name 
				FROM hb_users_roles
				NATURAL JOIN hb_roles
				NATURAL JOIN hb_roles_permissions
				WHERE ( assignment_valid_until IS NULL OR assignment_valid_until > NOW() ) 
				AND (  date_valid_until IS NULL OR date_valid_until > NOW() )
				AND ( date_assigned_until IS NULL OR date_assigned_until > NOW() )
				AND username='".$_SESSION['userID']."'");
									
									$permission = ""; $tmp=0;
									
									while( $db_getpermission = mysql_fetch_array( $db_getpermissions ) ) 
									{
										$permission[$tmp] = $db_getpermission['permission_name'];
										$tmp++; 
									}
									
									$_SESSION['permissions'] = $permission;
									
									$content = url_redirect( "?pagename=management" , 2 , "You are now logged in" );
									create_log( "user_loggedin" , "User successfully logged in");
									resetFailedLogins( $username );
									
								}
								else // If account isnt active anymore
								{
									$content = str_replace( "%errmsg%" , "This account is no longer active.", $content);
									create_log( "login_error" , "Client tried to log in with expired account ($username)" , "Username: $username\nPassword: $password");
										
								}
							}
							else //if pw doesnt match
							{
								
								$content = str_replace( "%errmsg%" , "Username/Password not found.", $content);
								create_log( "login_error" , "Client tried to log in with wrong password ($password)" , "Username: $username\nPassword: $password");
								addFailedLogin( $username );
							}
						}
						else // If wrong password entered too many times
						{
							$content = str_replace( "%errmsg%" , "This account is suspended!", $content);
							$tmp = time() + 3600; // block access for one hour
							$db_update = mysql_query( "UPDATE hb_users SET login_suspended_until = FROM_UNIXTIME($tmp) WHERE username='$username'");
							resetFailedLogins($username);
							create_log( "login_error" , "Wrong password entered too many times - Account suspended ($username)", "Username: $username\nPassword: $password");
						}
					}
					else // If account is suspended
					{
						$content = str_replace( "%errmsg%" , "This account is suspended!", $content);
						create_log( "login_error" , "Client tried to log into suspended account ($username)", "Username: $username\nPassword: $password");
					}
				}
				else //If un doesnt match
				{
					$content = str_replace( "%errmsg%" , "Username/Password not found.", $content);
					create_log( "login_error" , "Client tried to log in with non matching username?? ($username)", "Username: $username\nPassword: $password");
				}
			}
			else // if username not found
			{
				$content = str_replace( "%errmsg%" , "Username/Password not found.", $content);
				create_log( "login_error" , "Client tried to log in with non-existing username ($username)", "Username: $username\nPassword: $password");
			}
		} // If there are more than 5 failed login attempts
		else
		{
			$content = str_replace( "%errmsg%" , "Entered wrong password too many times.", $content);
			create_log( "login_warning" , "Client tried to log in but was rejected because of too many fails", "Username: $username\nPassword: $password");
		}
	}
	else // if no data submitted
	{
		$content = loadtemplate( "login.html" );
		$content = str_replace( "%errmsg%" , "" , $content);
	}
}

?>