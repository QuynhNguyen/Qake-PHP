<?php

# VERSION 1.0

if( !empty( $_GET['skin'] ) )
{
	if( $_GET['skin'] == 'default' )
	    $_SESSION['skin'] = "";
	else
		$_SESSION['skin'] = $_GET['skin'];
	
	$content = url_redirect( "$PHP_SELF?" , 3 , "Skinauswahl gespeichert." );
}

else
{
	$content = "Fehler, kein Skin angegeben!";
}

?>
