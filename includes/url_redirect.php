<?php

# VERSION 1.0

function url_redirect( $redirecturl = '$PHP_SELF' , $redirectdelay = 2 , $redirectmsg = 'Please wait...' )
{
	$prevent_uri_update = 1;

	$ret = loadtemplate ( "url_redirect.html" );

    $sid = session_id();
    if( !empty( $sid ) )
    {
		if( $redirecturl != '$PHP_SELF' )
	    	$redirecturl .= "&sid=$sid";
		else
		    $redirecturl .= "?sid=$sid";
	}

	$ret = str_replace ( "%redirecturl%" , $redirecturl , $ret );
	$ret = str_replace ( "%redirectdelay%" , $redirectdelay , $ret );
	$ret = str_replace ( "%redirectmessage%" , $redirectmsg , $ret );

	return $ret;
}


?>
