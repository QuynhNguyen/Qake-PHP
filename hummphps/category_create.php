<?php

$page_title = "Create Category";
$errors = "";

include("./includes/category_form_helper.php");

if($_SERVER['REQUEST_METHOD'] == 'POST'){
		
	//Assign global post value to local variable	
	$category_name = stop_injection($_POST['category_name']);
	$category_description = stop_injection($_POST['category_description']);
	
	$errors = categoryFormValidation($category_name, $category_description);
	
	if(empty($errors)){
		//MySql Query
		$query = "INSERT INTO category (name, description)";
		$query .= " VALUES('$category_name', '$category_description')";
		
		//execute query
		$result = mysql_query($query);
		if(!$result)
			die("unable to execute query");
		
		//Result Page
		$content = loadtemplate( "category_create.html");
		$content = str_replace("%success%", "<div class=\"info\">Succesfully Created <p> <a href='?pagename=category_management'>Go back to Management Page</a></div>", $content);
		$content = str_replace("%error%", "", $content);	
		
		create_log( "category_created" , "User created new category $category_name");
	}else{
		//Result Page
		$content = loadtemplate( "category_create.html");
		$content = str_replace("%success%", "", $content);
		$content = str_replace("%error%", "<div class=\"error\">".$errors."</div>", $content);	
	}
	
}
else {
	$content = loadtemplate( "category_create.html");
	$content = str_replace("%success%", "", $content);
	$content = str_replace("%error%", "", $content);
}





?>