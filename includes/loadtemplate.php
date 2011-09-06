<?php

# VERSION 1.0

function loadtemplate ( $tml_filename , $tml_path = "templates/" )
{
	// The following if-else statement is for advanced use only (skins, languages)
    if( $_SESSION['lang'] != $site_default_lang ) // If another than default language was selected
    {
    	if( !empty( $_SESSION['skin'] ) ) // If user has selected a different skin than default
    	{
    		if( file_exists( $php_root_path . $tml_path.$_SESSION['skin']."/".$_SESSION['lang']."_$tml_filename" ) ) // If template exists in selected language
    			$tml_filename = $_SESSION['skin']."/" . $_SESSION['lang']."_$tml_filename";
    	}
    	else // If default skin should be used
      		if( file_exists( $php_root_path . $tml_path.$_SESSION['lang']."_$tml_filename" ) ) // If template exists in selected language
        		$tml_filename = $_SESSION['lang']."_$tml_filename";
    }
    else // If default language was selected
	{
		if( !empty( $_SESSION['skin'] ) ) // If user has selected a different skin
	    	if( file_exists( $php_root_path . $tml_path.$_SESSION['skin']."/$tml_filename" ) ) // If template exists in other skin
	    		$tml_filename = $_SESSION['skin']."/$tml_filename";
	}
	
	if( file_exists( $php_root_path . $tml_path . $tml_filename ) ) // If template is present
	{
      $tml_content = @join( " " , file( $php_root_path . $tml_path . $tml_filename ) );
      if( $tml_content === 0 )
      {
		$tml_content = "Error loading required template!" ;
      }
    }
    else
    {
      $tml_content = "Required template is not present!";
    }

	return $tml_content;
}


?>
