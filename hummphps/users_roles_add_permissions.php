<?php


$err = 0;
$errmsg = "";

if( $_SERVER['REQUEST_METHOD'] == "POST" ) // If form data was submitted
{
		
	$succ = 0;
	$fail = 0;
	// ASSIGN ROLES TO USER
	$db_getpermissions = mysql_query( "SELECT * FROM hb_permissions"); // get all roles
	while( $db_getpermission = mysql_fetch_array( $db_getpermissions ) )
	{
		if( $_POST[$db_getpermission['permission_name']] ) // if user should be assigned this role
		{
			$db_store = mysql_query( "INSERT INTO hb_roles_permissions ( role_name, permission_name ) VALUES
			( '".$_POST['role_name']."','".$db_getpermission['permission_name']."' )");
			if( $db_store )
			{
				create_log( "users_roles_add_permission" , "Permission ".$db_getpermission['permission_name']." was added to role ".$_POST['role_name'] );
				$succ++;
			}
			else
				$fail++;
		}
	}
	
	$msg = "$succ permissions have been successfully added ($fail fails)";
	$content = url_redirect( "?pagename=users_roles_permissions&role_name=" . $_POST['role_name'] , 2 , $msg );

}

?>