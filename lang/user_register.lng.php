<?


switch( $_SESSION['lang'] )
{
  case 'de': // GERMAN
  
    $lang_ur_too_long = " ist zu lang, max Zeichen: ";
    $lang_ur_too_short = " ist zu kurz, min Zeichen: ";
	$lang_ur_invalid_characters = "ist ungültig oder enthält ungültige Zeichen";
	$lang_ur_already_in_use = " wird bereits von einem anderen User verwendet!";
	$lang_ur_email_sent = "Eine Bestätigungsemail wurde an Ihre Emailadresse gesandt.";
	$lang_ur_reg_success_email_fail =  "Registrierung erfolgreich. <br><font color='Red'><b>Fehler beim zusenden der Aktivierungsmail - Admin wurde informiert!</b></font>";
	$lang_ur_reg_success =  "Registrierung erfolgreich!";
	$lang_ur_error_storing_data = "Fehler beim Speichern der Daten!";
	$lang_ur_password_mismatch = "Passwörter stimmen nicht überein";
	
  break;

  default: // DEFAULT (ENGLISH)
  
    $lang_ur_too_long = " is too long, max characters: ";
    $lang_ur_too_short = " is too short, min characters: ";
	$lang_ur_invalid_characters = "is invalid or contains invalid characters";
	$lang_ur_already_in_use = " is being used by another user!";
	$lang_ur_email_sent = "An Email has been sent to you to verify your adress";
	$lang_ur_reg_success_email_fail =  "Registration successful. <br><font color='Red'><b>Error sending confirmation email - Admin has been informed!</b></font>";
	$lang_ur_reg_success = "Registration successful!";
	$lang_ur_error_storing_data = "Error: Storing data failed!";
	$lang_ur_password_mismatch = "passwords don't match";
  break;
}
