<?php
include_once("../session.php");
require_once("database.php");
echo 	"<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\">
		<html>
		<head>
		<title>Benutzer umbenennen</title>
		<link rel=\"stylesheet\" type=\"text/css\" href=\"../css.css\">
		</head>
		<body>
		<h2>Benutzername &auml;ndern"; if ($_GET[oldname]) echo " - Benutzer: ".$_GET[oldname]; ;echo "</h2>
		<table>
		<tr>
			<td>Hier kann ein Benutzer umbenannt werden, falls wirklich ein triftiger Grund vorliegt. Solche Gr&uuml;nde w&auml;ren z.B. bei der Anmeldung verschrieben, Verwechslungsgefahr, Name war bei der Anmeldung nicht verf&uuml;gbar, Name ist beleidigend, etc. Insbesondere z&auml;hlt es nicht als hinreichend, wenn ein Spieler einfach nicht mehr zufrieden ist mit seinem Namen. Namenswechsel sollen eine Ausnahme bleiben.<p/>
			W&auml;hle einen Benutzer, der umbenannt werden soll.
			<form action=\"rename_user.php\" method=post>
			<input type=\"text\" class=\"textinput\" name=\"oldname\">
			<input type=submit value=Ok name=show_name>
			</form>
			</td>
		</tr>";
if(empty($_POST['oldname']) && empty($_GET['oldname'])) {
echo "<tr><td><b>Bitte einen Benutzernamen eingeben!</b></td></tr>";
exit;
} ELSE {
if(!empty($_POST[oldname])) {
	echo
	"<tr><td><table align=\"left\" cellspacing=\"0\" cellpadding=\"0\">
	<tr>
	<td><b>Benutzer mit der Buchstabenfolge \"".$_POST[oldname]."\"</b>:</td>
	</tr>";
	$names = sql_query("SELECT user FROM usarios WHERE user LIKE '%$_POST[oldname]%'");
	while($name = sql_fetch_array($names)) {
	echo "<tr>
	<td><a href=\"rename_user.php?oldname=".$name[user]."\">".$name[user]."</a></td>
	</tr>";
	}
	echo "</table></td></tr>";
}
$oldname = $_GET[oldname];
			
			
			
if ($oldname)
{
  //echo "<hl />";
  $get_user_data = sql_query("SELECT user,email,register FROM userdata WHERE user='$oldname'");
  $user_data = sql_fetch_array($get_user_data);
  if ($user_data)
  {
    echo "
	<tr>
	<td>
		<table>
		<tr>
			<th align=\"left\">Name:</th>
			<th align=\"left\">$user_data[user]</th>
		</tr>
		<tr>
			<td>E-Mail:</td>
			<td>$user_data[email]</td>
		</tr>
		<tr>
			<td>Angemeldet am:</td>
			<td>". date("d.m.Y",$user_data[register]) . "</td>
		</tr>
		<form action=\"{$_SERVER[PHP_SELF]}?oldname=$oldname\" method=post>
		<tr>
			<td>Neuer Benutzername:</td>
			<td><input type=\"text\" class=\"textinput\" name=\"newname\" maxlength=\"15\" value=\"".($_POST[newname]?$_POST[newname]:"")."\"></td>
		</tr>
		<tr>
			<td>Neue E-Mail Adresse:</td>
			<td><input type=\"text\" class=\"textinput\" name=\"newemail\" maxlength=\"255\" value=\"".($_POST[newemail]?$_POST[newemail]:"")."\"></td>
		</tr>
		<tr>
			<td colspan=\"2\" align=\"center\"><input type=submit value=&Auml;ndern name=rename></td>
		</tr>
		</form>
		</table>
	</td>
	</tr>";
  }
  else
    echo "Einen Benutzer mit dem Namen '$oldname' gibt es nicht.";
}
if ($_POST[rename])
{
  $get_name_exists = sql_query("SELECT 1 FROM userdata WHERE user='".addslashes($_POST[newname])."'");
  if (sql_num_rows($get_name_exists))
  {
	echo "<tr><td colspan=\"2\">Es gibt bereits einen Benutzer mit dem Namen ".$_POST[newname]."</td></tr>";
  }
	else if (!preg_match('/^([0-9A-Za-z])+$/', $_POST[newname]))
  {
	echo "<tr><td colspan=\"2\" class=\"important\">Der Name darf nur Buchstaben und Zahlen enthalten. Name nicht ge&auml;ndert.</td></tr>";
  }
  else
  {
    $newname = $_POST[newname];
    sql_query("UPDATE usarios SET user='$newname' WHERE user='$oldname'");
    sql_query("UPDATE userdata SET user='$newname' WHERE user='$oldname'");
	// Eintrag in Support-Log
	sql_query("INSERT INTO logs_support (supporter, action, action_value, target_user, timestamp) VALUES ('$_SESSION[supporter]', 'Datenkorrektur', 'Name: ". mysql_real_escape_string($oldname) ." -> ". mysql_real_escape_string($newname) ."', '". mysql_real_escape_string($oldname) ."', '". time() ."')");
	// Ende Eintrag in Support-Log
	
    echo "<tr><td colspan=\"2\" class=\"important_green\">Nutzer ".$oldname." wurde umbenannt in ".$newname." (Spendenquittungen bleiben auf den alten Namen ausgestellt).</td></tr>";
	}
	
	if(!empty($_POST[newemail])){
		switch(true){
			case $newname == "":
				$chg_mail_user = $oldname;
				break;
			case $newname !== "":
				$chg_mail_user = $_POST[newname];
				break;
		}
		$get_email_exists = sql_query("SELECT 1 FROM userdata WHERE email='".addslashes($_POST[newemail])."'");
		if(sql_num_rows($get_email_exists)){
			echo "<tr><td colspan=\"2\" class=\"important\">Die neue Mailadresse ".$_POST[newemail]." ist bereits vergeben</td></tr>";
		} else {
		$get_old_mail = sql_query("SELECT email FROM userdata WHERE user = '". mysql_real_escape_string($chg_mail_user) ."'");
		$old_mail_array = sql_fetch_array($get_old_mail);
		$oldemail = $old_mail_array[email];
		sql_query("UPDATE userdata SET email='".addslashes($_POST[newemail])."' WHERE user='" . mysql_real_escape_string($chg_mail_user) . "'");
		if($newname) $msg = "(jetzt: ".$newname.")";
			echo "<tr><td colspan=\"2\" class=\"important_green\">Die E-Mail Adresse von ".$oldname." ".$msg." wurde ge&auml;ndert in '$_POST[newemail]'.</td></tr>";
		// Eintrag in Support-Log
		sql_query("INSERT INTO logs_support (supporter, action, action_value, target_user, timestamp) VALUES ('$_SESSION[supporter]', 'Datenkorrektur', 'Email: ". mysql_real_escape_string($oldemail) ." -> ". mysql_real_escape_string($_POST[newemail]) ."', '". mysql_real_escape_string($chg_mail_user) ."', '". time() ."')");
		// Ende Eintrag in Support-Log
		}
	}
}
}
echo
"</table>
</body>
</html>";
?>