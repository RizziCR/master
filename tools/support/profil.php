<?php
include_once("../session.php");
include_once("../../database.php");

if($_POST[change] == " Aktualisieren "){
	// Altes Passwort -> md5
	$old_pass_hash = md5("kenny".$_POST[old_pass]."istdoof");
	$old_pass_hash = md5($old_pass_hash);
	// Altes Passwort mit DB-Passwort abgleichen
	if($_POST[old_pass_db] != $old_pass_hash){
		echo
		"<html>
		<head>
		<title>Testseite</title>
		<link rel=\"stylesheet\" type=\"text/css\" href=\"../css.css\">
		<meta http-equiv=\"refresh\" content=\"2; URL=http://wumets.dyndns.org/ets10/tool2/support/profil.php\"> 
		</head>
		<body>
		<table align=\"center\" width=\"50%\" border=\"0\">
			<tr>
				<td align=\"center\"><span style=\"font-size: 0.9em\";>Altes Passwort f&uuml;r ".$_SESSION[supporter]." nicht korrekt !</span></td>
			</tr>
		</table>
		</body>
		</html>";
	}
	if($_POST[old_pass_db] == $old_pass_hash){
		if($_POST[new_pass] != $_POST[new_pass_2]){
			echo
			"<html>
			<head>
			<title>Testseite</title>
			<link rel=\"stylesheet\" type=\"text/css\" href=\"../css.css\">
			<meta http-equiv=\"refresh\" content=\"2; URL=http://wumets.dyndns.org/ets10/tool2/support/profil.php\"> 
			</head>
			<body>
			<table align=\"center\" width=\"50%\" border=\"0\">
				<tr>
					<td align=\"center\"><span style=\"font-size: 0.9em\";>Passwort und Passwortwiederholung stimmen nicht &uuml;berein !</span></td>
				</tr>
			</table>
			</body>
			</html>";
		}
		if($_POST[new_pass] == $_POST[new_pass_2]){
			$new_pass_hash = md5("kenny".$_POST[new_pass]."istdoof");
			$new_pass_hash = md5($new_pass_hash);
				mysql_query("UPDATE supporterdata SET password = '$new_pass_hash' WHERE supporter = '$_SESSION[supporter]'");
				echo
				"<html>
				<head>
				<title>Testseite</title>
				<link rel=\"stylesheet\" type=\"text/css\" href=\"../css.css\">
				<meta http-equiv=\"refresh\" content=\"2; URL=http://wumets.dyndns.org/ets10/tool2/support/profil.php\"> 
				</head>
				<body>
				<table align=\"center\" width=\"50%\" border=\"0\">
					<tr>
						<td align=\"center\"><span style=\"font-size: 0.9em\";>Daten f&uuml;r ".$_SESSION[supporter]." aktualisiert</span></td>
					</tr>
				</table>
				</body>
				</html>";
		}
	}
}
if(!isset($_POST[change])){
$p_qry = sql_query("SELECT * FROM supporterdata WHERE supporter = '$_SESSION[supporter]'");
$profil = sql_fetch_array($p_qry);
echo
"<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\">
<html>
<head>
<title>Flotten eines Users</title>
<link rel=\"stylesheet\" type=\"text/css\" href=\"../../css/css.css\">
</head>
<body>
<table width=\"40%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" align=\"center\">
	<tr>
	<td colspan=\"2\"><span style=\"font-size: 0.5em\";>&nbsp;</span></td>
	</tr>
	<tr>
	<td align=\"center\" colspan=\"2\"><span style=\"font-weight: bold\">Eigene Daten</span></td>
	</tr>
	<tr>
	<td colspan=\"2\"><span style=\"font-size: 0.5em\";>&nbsp;</span></td>
	</tr>
	<tr bgcolor=\"444444\">
		<th width=\"50%\" align=\"center\"></th>
		<th width=\"50%\" align=\"center\"></th>
	</tr>
	<form method=\"post\" action=\"profil.php\">
	<tr>
		<td align=\"left\" class=\"liste\">Dein Name:</td>
		<td align=\"right\" class=\"liste\">".$profil[supporter]."</td>
	</tr>
	<tr>
		<td colspan=\"2\" align=\"center\" class=\"liste\">Passwort &auml;ndern:</td>
	</tr>
	<tr>
		<td align=\"left\" class=\"liste\">Altes Passwort:</td>
		<td align=\"right\" class=\"liste\"><input type=\"hidden\" name=\"old_pass_db\" value=\"".$profil[password]."\"><input type=\"password\" name=\"old_pass\" size=\"15\"></td>
	</tr>
	<tr>
		<td align=\"left\" class=\"liste\">Neues Passwort:</td>
		<td align=\"right\" class=\"liste\"><input type=\"password\" name=\"new_pass\" size=\"15\"></td>
	</tr>
	<tr>
		<td align=\"left\" class=\"liste\">Neues Passwort (Wiederholung):</td>
		<td align=\"right\" class=\"liste\"><input type=\"password\" name=\"new_pass_2\" size=\"15\"></td>
	</tr>
	<tr>
		<td colspan=\"2\" align=\"center\"><input type=\"submit\" name=\"change\" class=\"listebutton\" value=\" Aktualisieren \"></td>
	</tr>
	</form>
</table>
</body>
</html>";
}
?>