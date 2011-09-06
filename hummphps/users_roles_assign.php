<?php


$err = 0;
$errmsg = "";

if( $_SERVER['REQUEST_METHOD'] == "POST" ) // If form data was submitted
{
		
	$username = addslashes($_POST['username']);
	$succ = 0;
	$fail = 0;
	// ASSIGN ROLES TO USER
	$db_getroles = mysql_query( "SELECT * FROM hb_roles"); // get all roles
	while( $db_getrole = mysql_fetch_array( $db_getroles ) )
	{
		if( $_POST[$db_getrole['role_name']] ) // if user should be assigned this role
		{
			$db_store = mysql_query( "INSERT INTO hb_users_roles ( role_name, username ,date_assigned ,assigned_by_username) VALUES
			( '".$db_getrole['role_name']."','$username' , NOW(), '".$_SESSION['userID']."' )");
			if( $db_store )
			{
				$succ++;
				create_log( "user_role_assign" , "User $username was assigned role ".$db_getrole['role_name']);
			}
			else
				$fail++;
			
		}
	}
	
	$msg = "$succ roles have been successfully added to user $username ($fail fails)";
	$content = url_redirect( "?pagename=users_roles&username=$username" , 2 , $msg );

}

?>