<?php

// CREATE FIRST USER
if( !mysql_num_rows( mysql_query("SELECT * FROM hb_users WHERE username='admin'" ) ) )
{
	$content = "";
	$md5_pass = md5("password");
	$db_create = mysql_query( "INSERT INTO hb_users ( username , password_md5 , date_created , date_next_pw_reset , created_by_username )
			VALUES ( 'admin' , '$md5_pass' , NOW() , NOW() , 'admin' )");
	
	if( $db_create )
		$content .= "Initial user (admin:password) created.";
	else
		$content .= "Error creating initial user";
}
else
{
	$content .= "Initial user already exists";
}

// CREATE PERMISSION do_everything
if( !mysql_num_rows( mysql_query( "SELECT * FROM hb_permissions WHERE permission_name='do_everything'")))
{
	$db_create = mysql_query( "INSERT INTO hb_permissions (permission_name, permission_description)
	VALUES ('do_everything', 'This permission grants rights to everything') ");

	if( $db_create )
	$content .= "<br>Permission do_everything created.";
	else
	$content .= "<br>Error while creating do_everything permission.";
}
else
$content .= "<br>Permission do_everything already existed";

// CREATE ROLE superuser
if( !mysql_num_rows( mysql_query( "SELECT * FROM hb_roles WHERE role_name='superuser'")))
{
	$db_create = mysql_query( "INSERT INTO hb_roles (role_name, role_description) 
	VALUES ('superuser', 'Role for superuser, will be assigned with do_everything rights') ");
	
	if( $db_create )
		$content .= "<br>Role superuser created.";
	else
		$content .= "<br>Error while creating superuser role.";
}
else
	$content .= "<br>Superuser role already existed";

// ASSIGN ROLE superuser TO USER admin
if( !mysql_num_rows( mysql_query( "SELECT * FROM hb_users_roles WHERE role_name='superuser' AND username='admin'")))
{
	$db_create = mysql_query( "INSERT INTO hb_users_roles (role_name, username, date_assigned,assigned_by_username)
	VALUES ('superuser', 'admin', NOW(), 'admin') ");

	if( $db_create )
	$content .= "<br>Role superuser assigned to admin.";
	else
	$content .= "<br>Error while assigning superuser role to admin.";
}
else
$content .= "<br>Superuser role already assigned to admin";


// ADD PERMISSION do_everything TO ROLE superuser
if( !mysql_num_rows( mysql_query( "SELECT * FROM hb_roles_permissions WHERE role_name='superuser' AND permission_name='do_everything'")))
{
	$db_create = mysql_query( "INSERT INTO hb_roles_permissions (role_name, permission_name)
	VALUES ('superuser', 'do_everything') ");

	if( $db_create )
	$content .= "<br>Role superuser has been granted right do_everything.";
	else
	$content .= "<br>Error while granting do_everything right to superuser.";
}
else
$content .= "<br>Superuser role already has right do_everything";
?>