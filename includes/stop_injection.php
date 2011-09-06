<?php
	
function stop_injection($string){					
		if(get_magic_quotes_gpc())			
			$string = stripslashes($string);			
		return htmlentities(mysql_real_escape_string($string));				
}

function check_injection($string)
{
	if( stripos( $string , '\'' ) !== false )
		return "Single Quote";
	
	if( stripos( $string , '"' ) !== false )
		return "Double Quote";
	
	if( stripos( $string , 'DROP' ) !== false )
		return "DROP";
	
	if( stripos( $string , 'DELETE' ) !== false )
		return "DELETE";
	
	if( stripos( $string , 'TABLE' ) !== false )
		return "TABLE";
	
	if( stripos( $string , ';' ) !== false )
		return "SEMI-COLON";
	
	return false;
}
?>