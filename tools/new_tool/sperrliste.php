<?php
// Diverse Sperrlisten für verschiedene Spieler
/////////////////
// Tabelle ----- Zweck der Tabelle
// ---------------------------------
// sperrliste_igm ----- Spieler denken sie schreiben IGMs an andere Spieler, diese landen auch im eigenen POSTAUSGANG, kommen jedoch nicht an!
// sperrliste_username ----- Sperrliste für bestimmte Usernamen
// sperrliste_email_domain ----- Sperrliste für bestimmte E-Mail Domains
// sperrliste_emails ----- Sperrliste für bestimmte E-Mail Adressen


session_start();

// Include des DB Zugriffs (config.php) und des HTML Begins (head, start body -> html_head.php);
include("config.php");
include("html_head.php");

echo "*Über dieses Script lassen sich verschiedene Sperrlisten ansprechen.<br>
		** Die IGM Sperrliste täuscht einem Spammer vor IGMs zu versenden, diese erscheinen in seinem Postausgang, landen jedoch bei keinem Empfänger!<br>
		** Die Username Sperrliste blockiert bei Registrierung und Umbenennung von Spielern bestimmte Usernamen<br>
		** Die Domain Sperrliste blockiert bestimmte E-Mail Domains (z.B. komplett 'web.de' oder private, Trashmails etc.)<br>
		** Die E-Mail Sperrliste blockiert bestimmte E-Mailadressen (z.B. abc@web.de)<br><br>
		
		
		<a href='sperrliste.php?what=igm'>IGM</a> | 
		<a href='sperrliste.php?what=user'>User</a> | 
		<a href='sperrliste.php?what=domain'>Domain</a> | 
		<a href='sperrliste.php?what=email'>E-Mail</a><br><br>";



if($_POST['unlock_user']) {

	foreach($_POST['unlock_user'] AS $tmp_unlock_user) {
		$load_user_list = mysql_fetch_array(mysql_query("SELECT username FROM sperrliste_username WHERE username = '" . mysql_real_escape_string($tmp_unlock_user) . "';"));
		if($load_user_list['username']) {
			mysql_query("INSERT INTO logs_support (supporter, action, action_value, target_user, timestamp) VALUES ('$_SESSION[tool_user]', 'Username Sperrliste', 'User entfernt', '$load_user_list[username]', '" . time() . "');");
			mysql_query("DELETE FROM sperrliste_username WHERE username = '$load_user_list[username]';");
		}
	}
	
}

if($_POST['unlock_igm']) {

	foreach($_POST['unlock_igm'] AS $tmp_unlock_igm) {
		$load_igm_list = mysql_fetch_array(mysql_query("SELECT sperrliste_igm.user, userdata.user AS username FROM sperrliste_igm INNER JOIN userdata ON sperrliste_igm.user = userdata.ID WHERE userdata.user = '" . mysql_real_escape_string($tmp_unlock_igm) . "';"));
		if($load_igm_list['user']) {
			mysql_query("INSERT INTO logs_support (supporter, action, action_value, target_user, timestamp) VALUES ('$_SESSION[tool_user]', 'IGM Sperrliste', 'User entfernt', '$load_igm_list[user] ($load_igm_list[username])', '" . time() . "');");
			mysql_query("DELETE FROM sperrliste_igm WHERE user = '$load_igm_list[user]';");
		}
	}

}

if($_POST['unlock_domain']) {

	foreach($_POST['unlock_domain'] AS $tmp_unlock_domain) {
		$load_domain_list = mysql_fetch_array(mysql_query("SELECT domain FROM sperrliste_email_domain WHERE domain = '" . mysql_real_escape_string($tmp_unlock_domain) . "';"));
		if($load_domain_list['domain']) {
			mysql_query("INSERT INTO logs_support (supporter, action, action_value, target_user, timestamp) VALUES ('$_SESSION[tool_user]', 'Domain Sperrliste', 'Domain entfernt', '$load_domain_list[domain]', '" . time() . "');");
			mysql_query("DELETE FROM sperrliste_email_domain WHERE domain = '$load_domain_list[domain]';");
		}
	}

}

if($_POST['unlock_email']) {

	foreach($_POST['unlock_email'] AS $tmp_unlock_email) {
		$load_email_list = mysql_fetch_array(mysql_query("SELECT email FROM sperrliste_email WHERE email = '" . mysql_real_escape_string($tmp_unlock_email) . "';"));
		if($load_email_list['email']) {
			mysql_query("INSERT INTO logs_support (supporter, action, action_value, target_user, timestamp) VALUES ('$_SESSION[tool_user]', 'Email Sperrliste', 'Email entfernt', '$load_email_list[email]', '" . time() . "');");
			mysql_query("DELETE FROM sperrliste_email WHERE email = '$load_email_list[email]';");
		}
	}

}


if($_POST['user_sperr']) {

	mysql_query("INSERT INTO logs_support (supporter, action, action_value, target_user, timestamp) VALUES ('$_SESSION[tool_user]', 'User Sperrliste', 'Usernamen hinzugefügt', '". mysql_real_escape_string($_POST['user_sperr']) ."', '" . time() . "');");
	mysql_query("INSERT INTO sperrliste_username (username) VALUES ('" . mysql_real_escape_string($_POST['user_sperr']) . "');");

}


if($_POST['user_to_igm']) {
	
	mysql_query("INSERT INTO logs_support (supporter, action, action_value, target_user, timestamp) VALUES ('$_SESSION[tool_user]', 'IGM Sperrliste', 'Usernamen hinzugefügt', '". mysql_real_escape_string($_POST['user_to_igm']) ."', '" . time() . "');");
	$select_userID = mysql_fetch_array(mysql_query("SELECT ID FROM userdata WHERE user = '" . mysql_real_escape_string($_POST['user_to_igm']) . "';"));
	mysql_query("INSERT INTO sperrliste_igm (user) VALUES ('$select_userID[ID]');");
	
}


if($_POST['domain_sperr']) {
	
	mysql_query("INSERT INTO logs_support (supporter, action, action_value, target_user, timestamp) VALUES ('$_SESSION[tool_user]', 'Domain Sperrliste', 'Domain hinzugefügt', '". mysql_real_escape_string($_POST['domain_sperr']) ."', '" . time() . "');");
	mysql_query("INSERT INTO sperrliste_email_domain (domain) VALUES ('" . mysql_real_escape_string($_POST['domain_sperr']) . "');");
	
}


if($_POST['email_sperr']) {
	
	mysql_query("INSERT INTO logs_support (supporter, action, action_value, target_user, timestamp) VALUES ('$_SESSION[tool_user]', 'E-Mail Sperrliste', 'E-Mail Adresse hinzugefügt', '". mysql_real_escape_string($_POST['email_sperr']) ."', '" . time() . "');");
	mysql_query("INSERT INTO sperrliste_email (email) VALUES ('" . mysql_real_escape_string($_POST['email_sperr']) . "');");
	
}



if($_POST['what'])
	$_GET['what'] = $_POST['what'];



switch($_GET['what']) {
	
	
	default:
	case "user":
		echo "<form action='sperrliste.php' method='post'>
			Usernamen zur Usernamen Sperrliste hinzufügen: <input type='text' name='user_sperr'><br>
				*Bitte nur einen Usernamen zur Zeit eingeben<br><br>";
		$load_locked_user = mysql_query("SELECT username FROM sperrliste_username;");
		echo "Gesperrte Usernamen:<br>";
		while($locked_user = mysql_fetch_array($load_locked_user)) {
			echo "<input type='checkbox' name='unlock_user[]' value='$locked_user[username]'> $locked_user[username]<br>";
		}
		echo "<input type='hidden' name='what' value='user'>
				<input type='submit' value='Username Sperre entfernen'>";
		break;

		
	case "igm":
		echo "<form action='sperrliste.php' method='post'>
				User zur IGM Sperrliste hinzufügen: <input type='text' name='user_to_igm'><br>
				*Bitte nur einen Usernamen zur Zeit eingeben<br><br>";
		$load_locked_igm = mysql_query("SELECT userdata.user FROM sperrliste_igm INNER JOIN userdata ON sperrliste_igm.user = userdata.ID;");
		echo "Gesperrte User für IGM Versand:<br>";
		while($locked_igm = mysql_fetch_array($load_locked_igm)) {
			echo "<input type='checkbox' name='unlock_igm[]' value='$locked_igm[user]'> $locked_igm[user]<br>";
		}	
		echo "<input type='hidden' name='what' value='igm'>
				<input type='submit' value='IGM Sperre entfernen'>";
		break;

		
	case "domain":
		echo "<form action='sperrliste.php' method='post'>
				Domain zur Domain Sperrliste hinzufügen: <input type='text' name='domain_sperr'><br>
				*Bitte nur einen Domainnamen zur Zeit eingeben<br><br>";
		$load_locked_domain = mysql_query("SELECT domain FROM sperrliste_email_domain;");
		echo "Gesperrte Domains für Registrierung:<br>";
		while($locked_domain = mysql_fetch_array($load_locked_domain)) {
			echo "<input type='checkbox' name='unlock_domain[]' value='$locked_domain[domain]'> $locked_domain[domain]<br>";
		}
		echo "<input type='hidden' name='what' value='domain'>
				<input type='submit' value='Domain Sperre entfernen'>";
		break;
		
		
	case "email":	
		echo "<form action='sperrliste.php' method='post'>
				E-Mail Adresse zur E-Mail Sperrliste hinzufügen: <input type='text' name='email_sperr'><br>
				*Bitte nur eine E-Mail Adresse zur Zeit eingeben<br><br>";
		$load_locked_email = mysql_query("SELECT email FROM sperrliste_email;");
		echo "Gesperrte E-Mails für Registrierung:<br>";
		while($locked_email = mysql_fetch_array($load_locked_email)) {
			echo "<input type='checkbox' name='unlock_email[]' value='$locked_email[email]'> $locked_email[email]<br>";
		}
		echo "<input type='hidden' name='what' value='email'>
				<input type='submit' value='E-Mail Sperre entfernen'>";
		break;
				
		
}

// Navigation
include("html_end.php");

?>