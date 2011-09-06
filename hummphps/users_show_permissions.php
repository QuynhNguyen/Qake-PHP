<?php

$content = loadtemplate( "users_show_permissions.html");

$permissions = "";
foreach( $_SESSION['permissions'] as $tmp )
{
	if( !empty( $permissions ))
		$permissions .= "<br>";
	
	$permissions .= $tmp;
}

$content = str_replace( "%permissions%" , $permissions , $content );

?>