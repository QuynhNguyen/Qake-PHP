<?php

$username = addslashes( $_GET['username'] );
$content = loadtemplate( "users_roles.html");

$row = getRow( $content, 0);
$content = markRow( $content, 0 );

$db_get = mysql_query( "SELECT * FROM hb_users_roles NATURAL JOIN hb_roles WHERE username='$username'");
$role=""; $tmp=0;

while( $db_getx = mysql_fetch_array($db_get) )
{
	$tmp_row = $row;
	
	$assigned_until = datetostring( $db_getx['date_assigned_until'] );
	if( $assigned_until[1] == 1 ) // If date is in past
		$assigned_until[0] = "Expired";
	
	$date_assigned = datetostring( $db_getx['date_assigned'] );
	
	$tmp_row = str_replace( "%role_name%" , $db_getx['role_name'] , $tmp_row );
	$tmp_row = str_replace( "%role_description%" , $db_getx['role_description'] , $tmp_row );
	$tmp_row = str_replace( "%date_assigned_until%" , $db_getx['date_assigned_until'] , $tmp_row );
	$tmp_row = str_replace( "%date_assigned_until_string%" , $assigned_until[0] , $tmp_row );
	$tmp_row = str_replace( "%date_assigned%" , $db_getx['date_assigned'] , $tmp_row );
	$tmp_row = str_replace( "%date_assigned_string%" , $date_assigned[0] , $tmp_row );
	$tmp_row = str_replace( "%username%" , $db_getx['username'] , $tmp_row );
	
	$role[$tmp] = $db_getx['role_name'];
	$tmp++;
	
	$content = putRow( $content , $tmp_row , 0);
}

if( !$tmp )
	$content = putRow( $content , "<tr><td colspan='100'>No roles have been assigned to this user</td></tr>" , 0);

$content = str_replace( "%username%" , $_GET['username'] , $content);

$row = getRow( $content, 1 );
$content = markRow( $content, 1);

$db_get = mysql_query( "SELECT * FROM hb_roles"); // get all permissions
$permissions_available = 0;
while( $db_getx = mysql_fetch_array( $db_get ) )
{
	$role_already_granted = 0; // stores whether role already has this permission
	foreach( $role as $tmp )
	{
		if( $tmp == $db_getx['role_name']) 
			$role_already_granted = 1;
	}
	if( !$role_already_granted ) //if role doesnt have this permission yet
	{
		$tmp_row = $row;
		$tmp_row = str_replace( "%add_role_name%" , $db_getx['role_name'] , $tmp_row );
		$content = putRow( $content, $tmp_row , 1);
		$permissions_available++;
	}
}
if( !$permissions_available )
{
	$content = putRow( $content, "No permissions available<br>" , 1);
}

?>