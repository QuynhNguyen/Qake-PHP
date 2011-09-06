<?php

#VERSION 1.0

if( !empty( $_GET['lang'] ) )
{
	if( $_GET['lang'] == 'default' )
	    $_SESSION['lang'] = $site_default_lang;
	else
		$_SESSION['lang'] = $_GET['lang'];
		
		if( !empty( $_GET['url'] ) )
		{
			header( "Location: http://" . $_SERVER['HTTP_HOST'] . $_GET['url'] );
		}
    else if( !empty( $_SESSION['URI'] ) )
    {
      header( "Location: http://" . $_SERVER['HTTP_HOST'] . $_SESSION['URI'] );
    }
    else
    {
      header( "Location: http://" . $_SERVER['HTTP_HOST'] . $PHP_SELF );
    }
    
    $_SERVER['REQUEST_URI'] = "";
}

else
{
	$content = "Fehler, keine Sprache angegeben!<br>Error, no language given!";
}

?>
