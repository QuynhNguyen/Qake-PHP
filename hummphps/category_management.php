<?php

/*DONT FORGET TO DO STOP_INJECTION!!!@#@$@#%#@%@*/

if($_GET["edit"] && $_POST["edit"] == false){
	
	$page_title = "Category Edit";
	
	$category_id = stop_injection($_GET["edit"]);
	
	//MySql query
	$query = "SELECT * FROM category WHERE id = '$category_id'";
	$result = mysql_query($query);
	$num_rows = mysql_num_rows($result);
	if($num_rows == 0){
		$content = loadtemplate("category_management.html");
		$content = str_replace("%content%", "Request Category Doesn't Exist", $content);
		$content = str_replace("%header%", "Edit Category", $content);
	}else{
		while($row = mysql_fetch_assoc($result)){
			$category_name = $row['name'];
			$category_description = $row['description'];
		}
	
		$content = loadtemplate( "edit_category_form.html");
		$content = str_replace("%header%", "Edit Category", $content);
		$content = str_replace("%category_name%", $category_name, $content);
		$content = str_replace("%category_description%", $category_description, $content);
		$content = str_replace("%id%", $category_id, $content);
		$content = str_replace("%error%", "", $content);
		$content = str_replace("%success%", "", $content);
	}
}elseif($_POST["edit"]){
	
	@include("./includes/category_form_helper.php");
	$page_title = "Category Edit";
	$category_name = stop_injection($_POST['category_name']);
	$category_id = stop_injection($_GET['edit']);
	$category_description = stop_injection($_POST['category_description']);
	$content = loadtemplate( "edit_category_form.html");
	$content = str_replace("%header%", "Edit Category", $content);
	$content = str_replace("%debug%", $category_id, $content);
	
	$errors = categoryFormValidation($category_name, $category_description);
	
	if(empty($errors)){
		$query = "UPDATE category SET name = '$category_name', description = '$category_description' WHERE id = '$category_id'";
		$result = mysql_query($query);
		if(!$result)
			$content = str_replace("%error%", "Category Doesn't existed", $content);
		else
			$content = str_replace("%error%", "", $content);
		
		$content = str_replace("%success%", "<div class='success'>Succesfully Update Your Category <p> <a href='?pagename=category_management'>Go back to Management Page</a></div>", $content);
		
		create_log( "category_update" , "Category $category_name has been updated");
	}
	
	//MySql query
	$query = "SELECT * FROM category WHERE id = '$category_id'";
	$result = mysql_query($query);
	$num_rows = mysql_num_rows($result);
	
		while($row = mysql_fetch_assoc($result)){
			$category_name = $row['name'];
			$category_description = $row['description'];
		}
	
	$content = str_replace("%category_name%", $category_name, $content);
	$content = str_replace("%category_description%", $category_description, $content);
	$content = str_replace("%error%", "<div class=\"error\">$errors</div>", $content);
	$content = str_replace("%success%", "", $content);
	
	
}elseif($_GET['delete'] && $_GET['category_name'] && !isset($_POST['delete'])){
	$page_title = "Delete A Category";
	$category_id = stop_injection($_GET['delete']);
	$category_name = stop_injection($_GET['category_name']);
	
	$output = "
				<form method='POST'>
					Are you sure you want to delete <em>$category_name</em> ? <br/><br/>
					<input type='hidden' name='delete' value='$category_id' />
					<input type='button' value='Cancel' onclick='window.location=\"?pagename=category_management\"' />  <input type='submit' value='Delete Now!' />  
				</form>
			  ";
	
	$content = loadtemplate("category_management.html");
	$content = str_replace("%header%", "Delete A Category", $content);
	$content = str_replace("%content%", $output, $content);
}elseif($_POST['delete']){
	$page_title = "Delete A Category";
	$content = loadtemplate("en_url_redirect.html");
	$content = str_replace("%redirectdelay%", "3", $content);
	$content = str_replace("%redirecturl%", "?pagename=category_management", $content);
	$category_id = stop_injection($_POST['delete']);
	
	$query = "DELETE FROM category WHERE id = '$category_id'";
	$result = mysql_query($query);
	if(!$result)
		$content = str_replace("%redirectmessage%", "Failed to delete your category! ", $content);
	else
		$content = str_replace("%redirectmessage%", '<div class="success">Succesfully Deleted!</div>', $content);
		
}else{
	$page_title = "Category Management";

	//MySql query
	$query = "SELECT * FROM category";
	$result = mysql_query($query);
	
	while($row = mysql_fetch_assoc($result)){
		$output .= '<p><b>Name: </b>'.$row["name"].'</p> <p>'.$row["description"].'</p>';
		$output .= '<a href="index.php?pagename=category_management&edit='.$row["id"].'">Edit</a> | ';
		$output .= '<a href="index.php?pagename=category_management&delete='.$row["id"].'&category_name='.urlencode($row["name"]).'">Delete</a><br/><br/>';
		
	}
	
	$content = loadtemplate("category_management.html");
	$content = str_replace("%content%", $output . "<p><a href='?pagename=category_create' class='button'>Create A New Category!</a></p>", $content);
	$content = str_replace("%header%", "Category Management", $content);
}


?>