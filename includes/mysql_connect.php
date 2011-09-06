<?

// Include Variables
$inc_var = @include_once "settings/variables.php";
if ( empty( $inc_var ) )
	die( "Error: Couldn't load variables!" );

// Connect to MySQL DB
$db = @mysql_connect( $db_server , $db_user , $db_pass ) or die( "MySQL Connection Error" );
$db_select = @mysql_select_db( $db_name );

?>