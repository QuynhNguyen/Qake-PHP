<?php

# VERSION 0.1
# This function will be used to determine whether a user has a specific right to perform some task

function user_hasright( $right_name = "default" , $right_user = "" )
{
	if( $_SESSION['loggedin'] )
	{
		foreach( $_SESSION['permissions'] as $permission )
		{
			if( $permission == $right_name || $permission == 'do_everything' )
			{
				return true;
			}
		}
		return false;
	}
	else
	{
		return false;
	}
	

}


?>
