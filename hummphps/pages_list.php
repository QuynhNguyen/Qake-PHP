<?php

$content = loadtemplate( "pages_list.html");

$row = getRow( $content, 0);
$content = markRow( $content, 0 );

$db_get = mysql_query( "SELECT * FROM hb_pagenames");

while( $db_getx = mysql_fetch_array($db_get) )
{
	$tmp_row = $row;
	
	$tmp_row = str_replace( "%pagename%" , $db_getx['pagename'] , $tmp_row );
	$tmp_row = str_replace( "%description%" , $db_getx['description'] , $tmp_row );
	if( empty( $db_getx['requires_permission']))
		$tmp_row = str_replace( "%requires_permission%" , "-" , $tmp_row );
	else
	{
		$db_getpermission = mysql_query("SELECT permission_description FROM hb_permissions WHERE permission_name='".$db_getx['requires_permission']."'");
		$db_getpermission = mysql_fetch_array($db_getpermission);
		
		$tmp_row = str_replace( "%permission_description%" , $db_getpermission['permission_description'] , $tmp_row );
		$tmp_row = str_replace( "%requires_permission%" , $db_getx['requires_permission'] , $tmp_row );
	}
	$content = putRow( $content , $tmp_row , 0);
	
}

?>