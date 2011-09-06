<?php

$delete_pagename = addslashes( $_GET['delete_pagename'] );

if( $_GET['confirm'])
{
	$db_delete = mysql_query( "DELETE FROM hb_pagenames WHERE pagename='$delete_pagename'" );
	if( $db_delete )
	{
		create_log( "page_delete" , "Page $delete_pagename was deleted");
		$content = url_redirect("?pagename=pages_list" , 2 , "Entry has been deleted.");
	}
	else
		$content = "Error";
}
else
{
	$content = loadtemplate( "pages_delete.html");
	$content = str_replace( "%delete_pagename%" , $delete_pagename , $content );
}

?>