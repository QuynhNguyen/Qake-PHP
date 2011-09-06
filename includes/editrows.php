<?php

# VERSION 0.1

/* SAMPLE CODE
$content = "This is a row test <!--row-1-start-->ROW 1 <!--row-1-end-->";

$tmp_row = getRow($content,1);

$content = putRow($content,$tmp_row,1);
$content = putRow($content,$tmp_row,1);


echo $content;
*/

// This function will return a string containing the row and its placeholders
function getRow( $content , $rowname = 1 )
{
	$tmp = substr( $content, strpos( $content, '<!--row-'.$rowname.'-start-->') + strlen( '<!--row-'.$rowname.'-start-->' ) );
	$tmp = strstr( $tmp, '<!--row-'.$rowname.'-end-->' , 1);
	
	if( $tmp )
		return $tmp;
	else
		return "Row was not found or is empty";
}

// This function replaces the sample rows with a marker at which actual rows will be inserted
// Make sure to call getRow first to get the row template - it will be deleted after calling markRow
function markRow( $content, $rowname = 1 )
{
	if( strpos( $content , '<!--row-'.$rowname.'-start-->' ) && strpos( $content , '<!--row-'.$rowname.'-start-->' ) )
		return substr_replace( $content, '<!--row-'.$rowname.'-->', strpos( $content , '<!--row-'.$rowname.'-start-->' ) , strpos( $content , '<!--row-'.$rowname.'-end-->' ) - strpos( $content , '<!--row-'.$rowname.'-start-->' ) + strlen('<!--row-'.$rowname.'-end-->')  );
	else
		return "Couldn't find row $rowname to mark (already marked?)";
}

// This function will add rows at the given position - must call markRow first
// Usage: putrow( $content , $row , $position );
// $content = the html output where the row will be placed 
// $row = the html of the row
// $rowname = the position of the row -- equal to the rowname (when using zebra striping always use the name of the first row here)
function putRow( $content, $row , $rowname = 1 )
{
	if( strpos( $content , '<!--row-'.$rowname.'-->' ) ) // Check if marker is there
	{
		$content = str_replace( '<!--row-'.$rowname.'-->' , $row . '<!--row-'.$rowname.'-->' , $content );
		return  $content;
	}
	else // If marker wasnt found
	{
		$content = markRow( $content , $rowname ); // Try to set marker
		if( strpos( $content , '<!--row-'.$rowname.'-->' ) ) // Look for marker again
		{
			$content = str_replace( '<!--row-'.$rowname.'-->' , $row . '<!--row-'.$rowname.'-->' , $content );
			return  $content;
		}
		else // If marker still cant be found
			return "Marker for row $rowname not found.";
	}
}



?>
