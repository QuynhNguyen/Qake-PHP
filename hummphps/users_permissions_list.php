<?php

$content = loadtemplate( "users_permissions_list.html");

$row = getRow( $content, 0);

$db_get = mysql_query( "SELECT * FROM hb_permissions");

while( $db_getx = mysql_fetch_array($db_get) )
{
	$tmp_row = $row;
	
	$tmp_row = str_replace( "%permission_name%" , $db_getx['permission_name'] , $tmp_row );
	$tmp_row = str_replace( "%permission_description%" , $db_getx['permission_description'] , $tmp_row );
	
	$content = putRow( $content , $tmp_row , 0);
	
}

?>