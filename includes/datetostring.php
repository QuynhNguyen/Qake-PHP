<?php

// Outputs how much time has passed since a certain timestamp relative to now, or how much time left to date in timestamp
function datetostring( $datetime , $lang="en")
{
  $string['second']['en'] = "second";
  $string['second']['de'] = "Sekunde";
  $string['seconds']['en'] = "seconds";
  $string['seconds']['de'] = "Sekunden";
  $string['minute']['en'] = "minute";
  $string['minute']['de'] = "Minute";
  $string['minutes']['en'] = "minutes";
  $string['minutes']['de'] = "Minuten";
  $string['hour']['en'] = "hour";
  $string['hour']['de'] = "Stunde";
  $string['hours']['en'] = "hours";
  $string['hours']['de'] = "Stunden";
  $string['day']['en'] = "day";
  $string['day']['de'] = "Tag";
  $string['days']['en'] = "days";
  $string['days']['de'] = "Tagen";
  $string['month']['en'] = "month";
  $string['month']['de'] = "Monat";
  $string['months']['en'] = "months";
  $string['months']['de'] = "Monaten";
  $string['year']['en'] = "year";
  $string['year']['de'] = "Jahr";
  $string['years']['en'] = "years";
  $string['years']['de'] = "Jahren";
  $string['never']['en'] = "never";
  $string['never']['de'] = "Niemals";

  $flag = 0; // 0 = empty string or 0000, 1 = past, 2 = future
  
  if( $datetime=="0000-00-00 00:00:00" || empty( $datetime ) )
  {
  	$string = $string['never'][$lang];
  }
  else // if not empty
  {
  		$flag = 1;
	  	$seconds = time() - strtotime( $datetime ); //Find out how many seconds have passed since the specified date
	  	
	  	if( $seconds < 0 ) // If seconds has a negative value --> if the date is in the future
	  	{
	  		$flag = 2;
	  		$seconds = $seconds * -1;
	  	}
	  	
	  	if( $seconds < 60 )
	  	{
	  		if( $seconds == 1 )
	  		$string = "1 ".$string['second'][$lang];
	  		else
	  		$string = "$seconds " . $string['seconds'][$lang];
	  	}
	  	else
	  	{
	  		if( $seconds < 60 * 60 )
	  		{
	  			$minutes = round( $seconds / 60 );
	  			if( $minutes == 1 )
	  			$string = "1 ".$string['minute'][$lang];
	  			else
	  			$string = "$minutes ".$string['minutes'][$lang];
	  		}
	  		else if( $seconds < 60 * 60 * 48 )
	  		{
	  			$hours = round( $seconds / ( 60 * 60 ) );
	  			if( $hours == 1 )
	  			$string = "1 ".$string['hour'][$lang];
	  			else
	  			$string = "$hours ".$string['hours'][$lang];
	  		}
	  		else if( $seconds < 60 * 60 * 24 * 90 )
	  		{
	  			$days = round( $seconds / (60 * 60 * 24 ) );
	  			if( $days == 1)
	  			$string = "1 ".$string['day'][$lang];
	  			else
	  			$string = "$days ".$string['days'][$lang];
	  		}
	  		else if( $seconds < 60 * 60 * 24 * 365 )
	  		{
	  			$months = round( $seconds / ( 60 * 60 * 24 * 30 ) );
	  			if( $days == 1)
	  			$string = "1 ".$string['month'][$lang];
	  			else
	  			$string = "$months ".$string['months'][$lang];
	  		}
	  		else
	  		{
	  			$years = round( $seconds / ( 60 * 60 * 24 * 365 ) );
	  			if( $years == 1 )
	  			$string = "1 ".$string['year'][$lang];
	  			else
	  			$string = "$years ".$string['years'][$lang];
	  		}
	  	
	  	}
  }

  $return[0] = $string;
  $return[1] = $flag;
  return $return;
}
	
	
?>
