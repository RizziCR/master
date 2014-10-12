<?php

session_start();

// Include des DB Zugriffs (config.php) und des HTML Begins (head, start body -> html_head.php);
include("config.php");
include("html_head.php");


echo"<h2>Globale IGMs versenden</h2>
		<table border=0>";

if($_POST[submit] == "Suchen") {
	$names = mysql_query("SELECT * FROM usarios WHERE user LIKE '%". mysql_real_escape_string($_POST[recipient]) ."%'");
	while($name = mysql_fetch_array($names)) {
		$list .=	"<tr>
				<td>
					<a href='igm_submit.php?recipient=".$name[user]."'>".$name[user]."</a>
				</td>
			</tr>";
	}
	$usernames =
	"<tr>
		<td>
			<table align='left' cellspacing='0' cellpadding='0'>
				<tr>
					<td>
						<b>Benutzer mit der Buchstabenfolge '".$_POST[recipient]."'</b>:
					</td>
				</tr>$list
			</table>
		</td>
	</tr>";
}


// Eintragen, wenn Formular abgesendet wird
if($_POST[submit] == "Versenden"){
	$timestamp = time();
	// Unterscheidung: Globale Mail oder IGM an einen spezifischen User
	if($_POST[global_mail] == TRUE){
		$result_user = mysql_query("SELECT ID,user FROM userdata WHERE delacc = '0'");
		while($user = mysql_fetch_array($result_user)){
			$recipient = $user[ID];
			mysql_query("INSERT INTO news_igm_umid (dir, owner, sender, recipient, time, seen, confirm, topic, text) VALUES ('0', '". mysql_real_escape_string($recipient) ."', '". mysql_real_escape_string($_POST[sender]) ."', '$result_user[ID]', '$timestamp', 'N', 'N', '". mysql_real_escape_string($_POST[topic]) ."', '". mysql_real_escape_string($_POST[text]) ."' )");
		}
		// Eintrage in Supporterlog
		$mail_id = "id_". mysql_insert_id();
		mysql_query("INSERT INTO logs_support (supporter, action, target_user, action_value, timestamp) VALUES ('$_SESSION[tool_user]', 'Globale IGM', 'Alle User', '". mysql_real_escape_string($mail_id) ."', '$timestamp')");
		$sent = "<tr>
				<td><span style='color: red; font-weight: bold;'>Nachricht an alle aktiven User gesendet.</td>
			</tr>";
	}
	if(!isset($_POST[global_mail])){
		$result_user = mysql_fetch_array ( mysql_query("SELECT ID FROM userdata WHERE user='". mysql_real_escape_string($_POST[recipient]) ."'") );
		mysql_query("INSERT INTO news_igm_umid (dir, owner, sender, recipient, time, seen, confirm, topic, text) VALUES ('0', '$result_user[ID]', '". mysql_real_escape_string($_POST[sender]) ."', '$result_user[ID]', '$timestamp', 'N', 'N', '". mysql_real_escape_string($_POST[topic]) ."', '". mysql_real_escape_string($_POST[text]) ."' )");
		$mail_id = "id_". mysql_insert_id();
		mysql_query("INSERT INTO logs_support (supporter, action, target_user, action_value, timestamp) VALUES ('$_SESSION[tool_user]', 'IGM', '". mysql_real_escape_string($_POST[recipient]) ."', '". mysql_real_escape_string($mail_id) ."', '$timestamp')");
		$sent = "<tr>
				<td><span style='color: red; font-weight: bold;'>Nachricht an User ".$_POST[recipient]." gesendet.</td>
			</tr>";
	}
}
// Formular definieren
echo
"<tr>
	<td>
		<table>
		<form action='igm.php' method='post'>"
		.$sent.
		"<tr>
			<td>Versenden als:
				<select name='sender'>
					<option value='KlausKleber'>KlausKleber (Erdbeben)</option>
					<option value='ETS-Support'>ETS-Support</option>
					<option value='Verwaltungsrat'>Verwaltungsrat</option>";
if($_SESSION[access] >= 90) echo "<option value='Administration'>Administration</option>";
echo
"</select>
			</td>
			</tr>
			<tr>
			<td>Versenden an: <input type='text' name='recipient' size='15' value='".$_GET[recipient]."'><input type='submit' name='submit' value='Suchen'> <b>oder</b> Globale Rundmail: <input type='checkbox' name='global_mail'> Ja ?</td>
			</tr><tr><td>"
					.$usernames.
					"</td></tr><tr>
				<td>Betreff: <input type='text' name='topic' size='80'></td>
			</tr>
			<tr>
				<td><textarea name='text' cols='80' rows='10'></textarea></td>
			</tr>
			<tr>
				<td><input type='submit' name='submit' value='Versenden'>$mail_id</td>
			</tr>
		</form>
		</table>
	</td>
</tr>
</table>
</body>
</html>";






// Navigation
include("html_end.php");

?>