<?php


$err = 0;
$errmsg = "";

if( $_SERVER['REQUEST_METHOD'] == "POST" ) // If form data was submitted
{
	if( strlen( $_POST['permission_name']) < 1 ) // if username too short
	{
		$err = 1;
		$errmsg .= "permission_name is too short (1 char min)<br>";
	}
	if( strlen( $_POST['permission_name']) > 30 ) // if username too long
	{
		$err = 1;
		$errmsg .= "permission_name is too long (30 char max)<br>";
	}
	if( strlen( $_POST['permission_description']) > 140 ) // if username too long
	{
		$err = 1;
		$errmsg .= "Description is too long (140 char max)<br>";
	}

	if( !$err ) // If there was no error
	{
		
		$permission_name = addslashes( $_POST['permission_name'] );
		$permission_description = addslashes( $_POST['permission_description'] );
		

		$db_store = mysql_query( "INSERT INTO hb_permissions ( permission_name , permission_description ) 
		VALUES ( '$permission_name' , '$permission_description'  )");
		
		if( $db_store ) // If command was run successfully
		{
			$content = url_redirect( "?pagename=users_permissions_list" , 2 ,  'Permission has been added' );
			create_log( "permission_add" , "Pasmission $permission_name was added" );
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
	$content = loadtemplate( "users_permissions_add.html" );
	
	$content = str_replace( "%permission_name%" , $_POST['%permission_name%'] , $content );
	$content = str_replace( "%permission_description%" , $_POST['permission_description'] , $content );
	
	if( $errmsg )
		$content = str_replace( "%errmsg%" , $errmsg . "<br>" , $content );
	else
		$content = str_replace( "%errmsg%" , "" , $content );
	
	
}
?>