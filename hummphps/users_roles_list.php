<?php

$content = loadtemplate( "users_roles_list.html");

$row = getRow( $content, 0);

$db_get = mysql_query( "SELECT * FROM hb_roles");

while( $db_getx = mysql_fetch_array($db_get) )
{
	$tmp_row = $row;
	
	$expires_in = datetostring( $db_getx['date_valid_until'] );
	if( $expires_in[1] == 1 ) // if role already expired (date in past)
		$expires_in[0] = "Expired";
	
	$tmp_row = str_replace( "%role_name%" , $db_getx['role_name'] , $tmp_row );
	$tmp_row = str_replace( "%role_description%" , $db_getx['role_description'] , $tmp_row );
	$tmp_row = str_replace( "%valid_until%" , $expires_in[0] , $tmp_row );
	
	
	
	$db_get2 = mysql_query( "SELECT * FROM hb_roles_permissions WHERE role_name='".$db_getx['role_name']."'");
	
	$permissions = "";
	while( $db_get2x = mysql_fetch_array($db_get2) )
	{
		if( !empty( $permissions ) )
			$permissions .= "<br>";
		$permissions .= $db_get2x['permission_name'];
	}
	
	if( !empty( $permissions ))
		$tmp_row = str_replace( "%permissions%" , $permissions , $tmp_row );
	else
		$tmp_row = str_replace( "%permissions%" , "-" , $tmp_row );
	
	
	$content = putRow( $content , $tmp_row , 0);
	
}

?>