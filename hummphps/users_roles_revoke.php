<?php

$role_name = addslashes( $_GET['role_name'] );
$username = addslashes( $_GET['username'] );

if( $_GET['confirm'])
{
	$db_delete = mysql_query( "DELETE FROM hb_users_roles WHERE role_name='$role_name' AND username='$username'" );
	if( $db_delete )
	{
		create_log( "users_role_revoke" , "Role $role_name was revoked from user $username");
		$content = url_redirect("?pagename=users_roles&username=$username" , 2 , "Role has been revoked.");
	}
	else
		$content = "Error";
}
else
{
	$content = loadtemplate( "users_roles_revoke.html");
	$content = str_replace( "%role_name%" , $role_name , $content );
	$content = str_replace( "%username%" , $username , $content );
}

?>