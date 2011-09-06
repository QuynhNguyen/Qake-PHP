<?php

$content = loadtemplate( "users_roles_permissions.html");

$row = getRow( $content, 0);
$content = markRow( $content, 0 );

$db_get = mysql_query( "SELECT * FROM hb_roles_permissions, hb_permissions WHERE role_name='".$_GET['role_name']."' AND hb_roles_permissions.permission_name = hb_permissions.permission_name");
$permission=""; $tmp=0;

while( $db_getx = mysql_fetch_array($db_get) )
{
	$tmp_row = $row;
	
	$tmp_row = str_replace( "%permission_name%" , $db_getx['permission_name'] , $tmp_row );
	$tmp_row = str_replace( "%permission_description%" , $db_getx['permission_description'] , $tmp_row );
	$tmp_row = str_replace( "%valid_until%" , "unlimited" , $tmp_row );
	
	$permission[$tmp] = $db_getx['permission_name'];
	$tmp++;
	
	$content = putRow( $content , $tmp_row , 0);
}

$content = str_replace( "%role_name%" , $_GET['role_name'] , $content);

$row = getRow( $content, 1 );
$content = markRow( $content, 1);

$db_get = mysql_query( "SELECT * FROM hb_permissions"); // get all permissions
$permissions_available = 0;
while( $db_getx = mysql_fetch_array( $db_get ) )
{
	$permission_already_granted = 0; // stores whether role already has this permission
	foreach( $permission as $tmp )
	{
		if( $tmp == $db_getx['permission_name']) 
			$permission_already_granted = 1;
	}
	if( !$permission_already_granted ) //if role doesnt have this permission yet
	{
		$tmp_row = $row;
		$tmp_row = str_replace( "%add_permission_name%" , $db_getx['permission_name'] , $tmp_row );
		$content = putRow( $content, $tmp_row , 1);
		$permissions_available++;
	}
}
if( !$permissions_available )
{
	$content = putRow( $content, "No permissions available<br>" , 1);
}

?>