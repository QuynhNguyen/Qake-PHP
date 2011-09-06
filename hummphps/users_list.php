<?php

$content = loadtemplate( "users_list.html");

$row = getRow( $content, 0);

$db_get = mysql_query( "SELECT * FROM hb_users");

while( $db_getx = mysql_fetch_array($db_get) )
{
	$tmp_row = $row;
	
	// get roles assigned to this user
	$roles ="";
	$db_getroles = mysql_query( "SELECT * FROM hb_users_roles WHERE username='".$db_getx['username']."'");
	while( $db_getrole = mysql_fetch_array( $db_getroles))
	{
		$role_name = $db_getrole['role_name'];
		$roles .= "<a href='?pagename=users_roles_permissions&role_name=$role_name'>$role_name</a> ";
	}
	
	if( $roles )
		$tmp_row = str_replace( "%roles%" , $roles , $tmp_row );
	else
		$tmp_row = str_replace( "%roles%" , "No roles assigned yet" , $tmp_row );
	
	$date_string = datetostring( $db_getx['date_created'] );
	$date_string[0] .= " ago";
	
	$tmp_row = str_replace( "%username%" , $db_getx['username'] , $tmp_row );
	$tmp_row = str_replace( "%real_name%" , $db_getx['real_name'] , $tmp_row );
	$tmp_row = str_replace( "%date_created%" , $db_getx['date_created'] , $tmp_row );
	$tmp_row = str_replace( "%date_created_string%" , $date_string[0] , $tmp_row );
	
	$content = putRow( $content , $tmp_row , 0);
	
}

?>