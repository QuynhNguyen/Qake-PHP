<?php

	function categoryFormValidation($category_name, $category_description){
		
		$errors = "";
		
		if(empty($category_name))
			$errors .= "<p>Your category name is empty</p>";
		if(empty($category_description) || $category_description == "Enter Description")
			$errors .= "<p>Please enter a description for your category</p>";
		
		return $errors;
	}

?>