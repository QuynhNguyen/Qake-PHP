<?php


$err = 0;
$errmsg = "";

if( $_SERVER['REQUEST_METHOD'] == "POST" ) // If form data was submitted
{
	if( strlen( $_POST['add_pagename']) < 1 ) // if username too short
	{
		$err = 1;
		$errmsg .= "Pagename is too short (1 char min)<br>";
	}
	if( strlen( $_POST['add_pagename']) > 30 ) // if username too long
	{
		$err = 1;
		$errmsg .= "Pagename is too long (30 char max)<br>";
	}
	if( strlen( $_POST['description']) > 140 ) // if username too long
	{
		$err = 1;
		$errmsg .= "Description is too long (140 char max)<br>";
	}

	if( !$err ) // If there was no error
	{
		
		$add_pagename = eregi_replace( "[^a-z0-9_]" , "" , $_POST['add_pagename'] );
		$description = addslashes( $_POST['description'] );
		$requires_permission = addslashes( $_POST['requires_permission'] );
		
		if( empty( $_POST['requires_permission'] ))
			$query = "INSERT INTO hb_pagenames ( pagename , description , requires_permission ) 
		VALUES ( '$add_pagename' , '$description' , NULL  )";
		else
			$query = "INSERT INTO hb_pagenames ( pagename , description , requires_permission )
				VALUES ( '$add_pagename' , '$description' , '$requires_permission'  )";
		
		$db_store = mysql_query( $query );
		
		if( $db_store ) // If command was run successfully
		{
			create_log( "page_register" , "Page $add_pagename was registered");
			$content = url_redirect( "?pagename=pages_list" , 2 ,  'Page has been added' );
		}
		else 
		{
			$err = 1;
			$errmsg = "MySQL Error";
		}
	}
}

if( $err || $_SERVER['REQUEST_METHOD'] != "POST" ) // if there was an error or no data submitted yet
{
	$content = loadtemplate( "pages_add.html" );
	
	$content = str_replace( "%add_pagename%" , $_POST['add_pagename'] , $content );
	$content = str_replace( "%description%" , $_POST['description'] , $content );
	$content = str_replace( "%requires_permission%" , $_POST['requires_permission'] , $content );
	
	if( $errmsg )
		$content = str_replace( "%errmsg%" , $errmsg . "<br>" , $content );
	else
		$content = str_replace( "%errmsg%" , "" , $content );
	
	$row = getRow( $content, 0 );
	$content = markRow( $content, 0);
	$db_get = mysql_query( "SELECT * FROM hb_permissions");
	while( $db_getx = mysql_fetch_array( $db_get))
	{
		$tmp_row = $row;
		$tmp_row = str_replace( "%requires_permissionx%", $db_getx['permission_name'] , $tmp_row);
		$tmp_row = str_replace( "%requires_permissionx%", $db_getx['permission_name'] , $tmp_row);
		$content = putRow( $content, $tmp_row, 0);
	}
	
}
?>