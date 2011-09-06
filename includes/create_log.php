<?php

// Call: create_log( $type, $description, $details )
// $type = short type name e.g. warning, error, mysqlerror, wrong_password, etc.
// $description = short description e.g. "User $username tried to log in with wrong password"
// (optional) $details = provide more details, e.g. the mysql query used that produced the error

function create_log( $type , $description , $details = "" )
{
	$db_table_log = "hb_logs";
		

	$type = addslashes( $type );
	$description = addslashes( $description );
	$details = addslashes( $details );
	
	if( $_SESSION['loggedin'] )
		$username = "'".$_SESSION['userID']."'";
	else
		$username = "NULL";
	
	$db_create = mysql_query( "INSERT INTO `$db_table_log` 
	( `log_type` , `datetime` , `description` , `details` , `username` , `REMOTE_ADDR`, `REMOTE_HOST`, `X_FORWARDED_FOR` ) 
	VALUES ( '$type' , NOW() , '$description', '$details' , $username , '".$_SERVER['REMOTE_ADDR']."' , '".$_SERVER['REMOTE_HOST']."' , '".$_SERVER['HTTP_X_FORWARDED_FOR']."'  )" );

	if( !empty( $db_create ) )
		return 1;
	else
		return 0;
}



          
?>
