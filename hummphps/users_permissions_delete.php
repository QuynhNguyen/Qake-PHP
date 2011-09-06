<?php

$permission_name = addslashes( $_GET['permission_name'] );

if( $_GET['confirm'])
{
	$db_delete = mysql_query( "DELETE FROM hb_permissions WHERE permission_name='$permission_name'" );
	if( $db_delete )
	{
		$content = url_redirect("?pagename=users_permissions_list" , 2 , "Entry has been deleted.");
		create_log( "permission_delete" , "Permission $permission_name was deleted" );
	}
	else
		$content = "Error";
}
else
{
	$content = loadtemplate( "users_permissions_delete.html");
	$content = str_replace( "%permission_name%" , $permission_name , $content );
}

?>