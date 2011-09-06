<?php

	function isValidDate ($x) {
        if ($x === false || $x == -1) {
                return false;
        } else {
                return true;
        }
	}

	function tweetFormValidation($tweet_content, $cat_selected, $post_date, $using_date, $date_s, $time_s){
		
		$errors = "";
		
		if(empty($tweet_content)) {
			$errors .= "<p>Your tweet has no content.</p>";
		}
		if($cat_selected == false) {
			$errors .= "<p>You need to select at least one category</p>";
		}
		if($using_date&&(!isValidDate($post_date)||time() > $post_date || empty($date_s) || empty($time_s))) {
			$errors .= "<p>Invalid post date.</p>";
		} 
		return $errors;
	}
	
	

?>