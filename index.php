<?php

// Start Session ID
session_name("sid");
session_start();

// Set these variables to ZERO to avoid having them manipulated by $_GET or $_POST
$user_loggedin = 0; // Specifies wheter a user is logged in 
$loggedin = $user_loggedin; // For downwards compatiblity -- please use user_loggedin instead
$user = ""; //This will be the array holding all the user information stored in the db (e.g. $user['username'] holds the username, $user['email'] the email address)
$meta = ""; //Called scripts can store meta information (as html) in this variable

// If no ?pagename= was given in the URL set $pagename to "", if it was given remove all non alpha_num chars
empty( $_GET['pagename'] ) ? $pagename = "" : $pagename = eregi_replace( "[^a-z0-9_]" , "" , $_GET['pagename'] );

// $_SESSION['pages'] stores how many requests (of index.php) have been made during this sesssion
isset( $_SESSION['pages'] ) ? $_SESSION['pages']++ : $_SESSION['pages'] = 0;

// This variable holds the current url (with all _GETs)
$url_current = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER["REQUEST_URI"];

// Include Variables
$inc_var = @include "settings/variables.php";
if ( empty( $inc_var ) )
	die( "Error: Couldn't load variables!" );

// If language wasn't set, set default language
isset( $_SESSION['lang'] ) ? 0 : $_SESSION['lang'] = $site_default_lang;


// Include loadtemplate - this is a function to load templates, usage: loadtemplate( "filename.html" )
$inc_load = @include "includes/loadtemplate.php";
if ( empty( $inc_load ) )
	die( "Error: Couldn't start template loader!" );

// Include editrows - this is a function that makes use of tables with databses easier, 
// Functions included: getRow( $content [, $rowname] ) -- putRow( $content, $row [, $rowname] )
$inc_load = @include "includes/editrows.php";
if ( empty( $inc_load ) )
die( "Error: Couldn't start row editor!" );
	
// Include datetostring.php (converts a date to a string which outputs the seconds/days/etc. relative to the current date, $lang: de, en
// Usage: datetostring( $olddate [, $lang] );
$inc_load = @include "includes/datetostring.php";
if ( empty( $inc_load ) )
	die( "Error: Couldn't load datetostring() function!" );


// Include Redirect Script (usage: url_redirect( "URL", "Delay in sec", "Message" );
$inc_urr = @include "includes/url_redirect.php";
if ( empty( $inc_urr ) )
	die( "Error: Couldn't load url_redirect()!" );

// Include Rights Determination Script (usage: user_hasright("rightname"); ) returns TRUE or FALSE;
$inc_urr = @include "includes/user_hasright.php";
if ( empty( $inc_urr ) )
die( "Error: Couldn't load rights management!" );

// Include sequel injection eliminator
$inc_inj = @include "includes/stop_injection.php";
if(empty($inc_inj))
	die("Error: Couldn't load sequel injection stopper function!");

// Include create_log function to create a log entry
// Call: create_log( $type, $description, $details )
$inc_load = @include "includes/create_log.php";
if ( empty( $inc_load ) )
	die( "Error: Couldn't load log writer!" );

// Connect to MySQL DB
$db = @mysql_connect( $db_server , $db_user , $db_pass ) or die( "MySQL Connection Error" );
$db_select = @mysql_select_db( $db_name );




// Check whether UserDB-script is present
if( file_exists( "includes/logincheck.php" ) )
{
	// SOME VARIABLES HERE ARE SUBJECT TO CHANGE
    /* If this script runs successfully and the user is successfully logged in, it sets the user variables:
    $user_loggedin = 1 (so scripts can tell whether a user is logged in)
    $user = User ID
    $user_position = User Position (0=unpriviledged user, 1=test moderator, 2=moderator, 3=supermod, 4=co-admin, 5=admin
    $user[$VARIABLE] = Any Users variable stored in the Database*/
    
	$inc_udb = @include "includes/logincheck.php";
	if ( empty( $inc_udb ) )
		die( "Error: Couldn't include UserDB!" );
}



// Load Index Template
// This is the main template which contains only the basic interface.
// This site should contain a %content% placeholder where the content which is loaded will be placed
$tml_index = loadtemplate( "index.html" );



// If no $pagename was given "home" will be used as $pagename
empty( $pagename ) ? $pagename = "home" : 0;


define( "NO_ACCESS" , "You don't have the necessary permissions to access this page" );
define( "RED_PILL" , '<img src="images/taketheredpill.png">' );


// Check how many possible injection attempts occured
$tmp = time()-7200; // 2 hours ago
$db_get_errors = mysql_query( "SELECT id FROM hb_logs WHERE log_type='sql_injection_warning' AND REMOTE_ADDR='".$_SERVER['REMOTE_ADDR']."' AND datetime > FROM_UNIXTIME(".$tmp.")" );
if( mysql_num_rows($db_get_errors) < 20 ) // Check if there havent been too many faulty requests
{
	// Check if there have been too many error-access attempts
	$db_get_errors = mysql_query( "SELECT id FROM hb_logs WHERE log_type='pagename_non_existing' AND REMOTE_ADDR='".$_SERVER['REMOTE_ADDR']."' AND datetime > FROM_UNIXTIME(".$tmp.")" );
	if( mysql_num_rows($db_get_errors) < 20 ) // Check if there havent been too many faulty requests
	{
		$db_get_errors = mysql_query( "SELECT id FROM hb_logs WHERE log_type='pagename_no_permission' AND REMOTE_ADDR='".$_SERVER['REMOTE_ADDR']."' AND datetime > FROM_UNIXTIME(".$tmp.")");
		if( mysql_num_rows($db_get_errors) < 20 ) // Check if there havent been too many faulty requests
		{
			// Get rights necesssary to access page
			$db_getpermission = mysql_query( "SELECT * FROM hb_pagenames WHERE pagename='$pagename'" );
	
			if( $db_getpermission = mysql_fetch_array( $db_getpermission) ) // if pagename is registered
			$pagename_registered = 1;
			else
			$pagename_registered = 0;
	
			if( $pagename_registered || user_hasright("do_everything") ) // if page is registered or user is allowed to do everything
			{
				$required_permission = $db_getpermission['requires_permission'];
	
				if( empty( $required_permission ) || user_hasright($required_permission) ) // if required_permission = empty or user has the right to view
				{
					// If $pagename.php is present it will be loaded, otherwise the $pagename template will be loaded
					if( file_exists ( "hummphps/$pagename.php" ) )
					{
						include "hummphps/$pagename.php"; // Include a file whichs output must be send to a $content variable
					}
					else
					{
						$content = loadtemplate( "$pagename$site_tmlext" ); // Load the content which will replace the %content% placeholder
					}
				} // If user doesnt have required permissions to view pagename
				else
				{
					create_log( "pagename_no_permission" , "Client tried to access existing page ($pagename) without permission" );
					$content = NO_ACCESS;
				}
			}
			else // If page is not in database
			{
				create_log( "pagename_non_existing" , "Client tried to access non-existing page ($pagename)" );
				$content = NO_ACCESS;
			}
		}
		else // If too many requests for unaccessible page in 2 hours
		{
			create_log( "spam_block_403" , "Client attempted to access too many non-accessible pages" , "Attempted to access: $pagename" );
			$content = NO_ACCESS;
		}
	} // If too many requests for non existing pages in 2 hours
	else
	{
		create_log( "spam_block_404" , "Client attempted to access too many non-existent pages" , "Attempted to access: $pagename" );
		$content = NO_ACCESS;
	}
}
else // If there have been too many injection attempts in the 2 hours
{
	create_log( "spam_block_injection" , "Client has too many injection warnings" , "Attempted to access: $pagename" );
	$content = NO_ACCESS;
}




if( $page_sidebar_enable ) // If sidebar should be shown
$tml_index = str_replace( "%content%" , loadtemplate("sidebar.html") , $tml_index ); // Load sidebar

// Puts the loaded $pagename content on the %content% placeholder
$tml_index = str_replace( "%content%" , $content , $tml_index ); 

if( $user_loggedin )
	$tml_index = str_replace( "%index_menu1%" , loadtemplate( "menu_loggedin.html" ) , $tml_index );
else 
	$tml_index = str_replace( "%index_menu1%" , loadtemplate( "menu_notloggedin.html" ) , $tml_index );


// Replaces %url_login% in the document with login URL
$tml_index = str_replace( "%url_login%" , $url_current , $tml_index ); // Login URL �ndern (f�r xb.php.mysqluserdb plugin)

// Replace %site_title% Tag with $site_title
if( !empty( $page_title ) )
	$site_title .= " - " . $page_title;
if( $user_loggedin )
	$site_title .= " - " . $_SESSION['userID'];
$tml_index = str_replace( "%site_title%" , $site_title , $tml_index );




// Output
echo $tml_index;


// Close MySQL Connection
$db_close = @mysql_close( $db );

?>
