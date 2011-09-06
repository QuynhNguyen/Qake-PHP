<?php 

create_log( "user_logout" , "User is logging out");
session_destroy();
$content = url_redirect( "?pagename=home" );

?>