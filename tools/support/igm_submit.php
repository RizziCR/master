<?php
include_once("../session.php");
include_once("../../database.php");
echo
"<html>
<head>
<title>Testseite</title>
<link rel=\"stylesheet\" type=\"text/css\" href=\"../css.css\">
</head>
<body>
<h2>Globale IGMs versenden</h2>
<table width=\"80%\">";
	
if($_POST[submit] == "Suchen") {
$names = sql_query("SELECT * FROM usarios WHERE user LIKE '%". addslashes($_POST[recipient]) ."%'");
while($name = sql_fetch_array($names)) {
$list .=	"<tr>
				<td><a href=\"igm_submit.php?recipient=".$name[user]."\">".$name[user]."</a></td>
			</tr>";
}
$usernames =
"<tr>
	<td>
		<table align=\"left\" cellspacing=\"0\" cellpadding=\"0\">
			<tr>
				<td><b>Benutzer mit der Buchstabenfolge \"".$_POST[recipient]."\"</b>:</td>
			</tr>"
			.$list.
		"</table>
	</td>
</tr>";
}


// Eintragen, wenn Formular abgesendet wird
if($_POST[submit] == "Versenden"){
	$timestamp = time();
		// Unterscheidung: Globale Mail oder IGM an einen spezifischen User
		if($_POST[global_mail] == TRUE){
			$result_user = sql_query("SELECT ID,user FROM userdata WHERE delacc = '0'");
			while($user = sql_fetch_array($result_user)){
			$recipient = $user[ID];
			sql_query("INSERT INTO news_igm_umid (dir, owner, sender, recipient, time, seen, confirm, topic, text) VALUES ('0', '". addslashes($recipient) ."', '". addslashes($_POST[sender]) ."', '$result_user[ID]', '$timestamp', 'N', 'N', '". addslashes($_POST[topic]) ."', '". addslashes($_POST[text]) ."' )");
			}
			// Eintrage in Supporterlog
			$mail_id = "id_". mysql_insert_id();
			sql_query("INSERT INTO logs_support (supporter, action, target_user, action_value, timestamp) VALUES ('$_SESSION[supporter]', 'Globale IGM', 'Alle User', '". addslashes($mail_id) ."', '$timestamp')");
			$sent = "<tr>
				<td><span style=\"color: red; font-weight: bold;\">Nachricht an alle aktiven User gesendet.</td>
			</tr>";
		}
		if(!isset($_POST[global_mail])){
			$result_user = sql_fetch_array ( sql_query("SELECT ID FROM userdata WHERE user='". addslashes($_POST[recipient]) ."'") );
			sql_query("INSERT INTO news_igm_umid (dir, owner, sender, recipient, time, seen, confirm, topic, text) VALUES ('0', '$result_user[ID]', '". addslashes($_POST[sender]) ."', '$result_user[ID]', '$timestamp', 'N', 'N', '". addslashes($_POST[topic]) ."', '". addslashes($_POST[text]) ."' )");
			$mail_id = "id_". mysql_insert_id();
			sql_query("INSERT INTO logs_support (supporter, action, target_user, action_value, timestamp) VALUES ('$_SESSION[supporter]', 'IGM', '". addslashes($_POST[recipient]) ."', '". addslashes($mail_id) ."', '$timestamp')");
			$sent = "<tr>
				<td><span style=\"color: red; font-weight: bold;\">Nachricht an User ".$_POST[recipient]." gesendet.</td>
			</tr>";
		}
}
	// Formular definieren
	echo
"<tr>
	<td>
		<table>
		<form action=\"igm_submit.php\" method=\"post\">"
			.$sent.
			"<tr>
			<td>Versenden als: 
				<select name=\"sender\">
					<option value=\"KlausKleber\">KlausKleber (Erdbeben)</option>
					<option value=\"ETS-Support\">ETS-Support</option>
					<option value=\"Verwaltungsrat\">Verwaltungsrat</option>";
					if($_SESSION[access] >= 90) echo "<option value=\"Administration\">Administration</option>";
				echo
				"</select>
			</td>
			</tr>
			<tr>
			<td>Versenden an: <input type=\"text\" name=\"recipient\" size=\"15\" value=\"".$_GET[recipient]."\"><input type=\"submit\" name=\"submit\" value=\"Suchen\"> <b>oder</b> Globale Rundmail: <input type=\"checkbox\" name=\"global_mail\"> Ja ?</td>
			</tr>"
			.$usernames.
			"<tr>
				<td>Betreff: <input type=\"text\" name=\"topic\" size=\"80\">
			</tr>
			<tr>
				<td><textarea name=\"text\" cols=\"80\" rows=\"10\"></textarea>
			</tr>
			<tr>
				<td><input type=\"submit\" name=\"submit\" value=\"Versenden\">".$mail_id."</td>
			</tr>
		</form>
		</table>
	</td>
</tr>
</table>
</body>
</html>";
?>