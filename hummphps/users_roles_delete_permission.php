<?php

$role_name = addslashes( $_GET['role_name'] );
$permission_name = addslashes( $_GET['permission_name'] );

if( $_GET['confirm'])
{
	$db_delete = mysql_query( "DELETE FROM hb_roles_permissions WHERE role_name='$role_name' AND permission_name='$permission_name'" );
	if( $db_delete )
	{
		create_log( "users_roles_delete_permission" , "Permission $permission_name was deleted from role $role_name");
		$content = url_redirect("?pagename=users_roles_permissions&role_name=$role_name" , 2 , "Permission <b>$permission_name</b> for role <b>$role_name</b> has been deleted.");
	}
	else
		$content = "Error";
}
else
{
	$content = loadtemplate( "users_roles_delete_permission.html");
	$content = str_replace( "%role_name%" , $role_name , $content );
	$content = str_replace( "%permission_name%" , $permission_name , $content );
}

?>