<?php


$err = 0;
$errmsg = "";

if( $_SERVER['REQUEST_METHOD'] == "POST" ) // If form data was submitted
{
	if( strlen( $_POST['username']) < 2 ) // if username too short
	{
		$err = 1;
		$errmsg .= "Username is too short (2 char min)<br>";
	}
	if( strlen( $_POST['username']) > 10 ) // if username too long
	{
		$err = 1;
		$errmsg .= "Username is too long (10 char max)<br>";
	}
	if( strlen( $_POST['real_name']) > 100 ) // if real name too long
	{
		$err = 1;
		$errmsg .= "Real Name is too long (100 char max)<br>";
	}
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
	
	if( !$err ) // If there was no error
	{
		
		$md5_pass = md5($_POST['password']);
		$username = addslashes( $_POST['username'] );
		$real_name = addslashes( $_POST['real_name'] );
		
		$db_get_user = mysql_query( "SELECT username FROM hb_users WHERE username='$username'" ); // See if username exists
		if( mysql_num_rows($db_get_user) ) // If username already exists
		{
			$err = 1;
			$errmsg = "Username is already in use<br>";
		}
		else // If username doesnt exist
		{	
			
			$db_store = mysql_query( "INSERT INTO hb_users ( username , real_name , password_md5 , date_created , date_next_pw_reset , created_by_username ) 
			VALUES ( '$username' , '$real_name' , '$md5_pass' , NOW() , NOW() , '".$_SESSION['userID']."' )");
			
			if( $db_store ) // If command was run successfully
			{
				create_log( "user_add" , "User $username was created");
				
				// ASSIGN ROLES TO USER
				$db_getroles = mysql_query( "SELECT * FROM hb_roles"); // get all roles
				while( $db_getrole = mysql_fetch_array( $db_getroles ) )
				{
					if( $_POST['role_' . $db_getrole['role_name']] ) // if user should be assigned this role
					{
						$db_store = mysql_query( "INSERT INTO hb_users_roles (username, role_name, assigned_by_username , date_assigned) VALUES
						( '$username' , '".$db_getrole['role_name']."','".$_SESSION['userID']."',NOW())");
						
						create_log( "user_role_assign" , "User $username was assigned role ".$db_getrole['role_name']);
					}
				}
				
				$content = url_redirect( "?pagename=users_list" , 2 ,  "User has been created" );
				
			}
			else
				$content = "Error";
		}
	}
}

if( $err || $_SERVER['REQUEST_METHOD'] != "POST" ) // if there was an error or no data submitted yet
{
	$content = loadtemplate( "users_add.html" );
	
	$content = str_replace( "%username%" , $_POST['username'] , $content );
	$content = str_replace( "%password%" , $_POST['password'] , $content );
	$content = str_replace( "%real_name%" , $_POST['real_name'] , $content );
	$content = str_replace( "%password_confirm%" , $_POST['password_confirm'] , $content );
	if( $errmsg )
		$content = str_replace( "%errmsg%" , $errmsg . "<br>" , $content );
	else
		$content = str_replace( "%errmsg%" , "" , $content );
	
	
	if( user_hasright("assign_role") )
	{
		$row = getRow( $content , 0);
		$content = markRow( $content , 0);
		$db_getroles = mysql_query( "SELECT * FROM hb_roles");
		while( $db_getrole = mysql_fetch_array( $db_getroles ) )
		{
			$tmp_row = $row;
			$tmp_row = str_replace( "%role_name%" , $db_getrole['role_name'] , $tmp_row );
			if( $_POST['role_' . $db_getrole['role_name']] ) // if was selected before
				$tmp_row = str_replace( "%checked%" , "checked" , $tmp_row ); // check it
			else
				$tmp_row = str_replace( "%checked%" , "" , $tmp_row ); // check it
			
			$content = putRow( $content , $tmp_row , 0 );
		}
	}
	else
	{
		$row = getRow( $content , 0);
		$content = putRow( $content , "You have no permission to assign roles" , 0);
	}
}
?>