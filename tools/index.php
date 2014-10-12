<?php
session_start();
include_once("../database.php");
echo 	"<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\">
		<html>
		<head>
		<title>Login-Seite</title>
		<link rel=\"stylesheet\" type=\"text/css\" href=\"css.css\">
		</head>
		<body>";
		
if(!isset($_SESSION[supporter])){
	if(!isset($_POST[login])){
		echo "<table width=\"800\" align=\"center\" cellspacing=\"0\" cellpadding=\"0\">
		<tr>
		<td>
		<table width=\"400\" align=\"center\" cellspacing=\"0\" cellpadding=\"0\">
		<form action=\"index.php\" method=\"post\" name=\"form1\">
		<tr>
		<td colspan=\"2\" align=\"center\">Willkommen im Supporttool</td>
		</tr>
		<tr>
		<td colspan=\"2\" align=\"center\">&nbsp;<p></td>
		</tr>
		<tr>
		<td align=\"left\">Dein User-Name:</td>
		<td align=\"right\"><input type=\"text\" size=\"20\" maxlength=\"20\" name=\"supporter\"></td>
		</tr>
		<tr>
		<td align=\"left\">Dein Passwort:</td>
		<td align=\"right\"><input type=\"password\" size=\"20\" maxlength=\"50\" name=\"password\"></td>
		</tr>
		<tr>
		<td colspan=\"2\" align=\"center\">&nbsp;<p><p></td>
		</tr>
		<tr>
		<td colspan=\"2\" align=\"center\"><span style=\"font-size: 0.7em\";><a href=\"reg.htm\">Registrieren</a></td>
		</tr>
		<tr>
		<td colspan=\"2\" align=\"center\">&nbsp;</td>
		</tr>
		<tr>
		<td colspan=\"2\" align=\"center\"><input type=\"submit\" name=\"login\" value=\"Login\"></td>
		</tr>
		</form>
		</table>
		</td>
		</tr>
		</table>";
	}
	
	if($_POST[login] == "Login"){
		$supporter = $_POST[supporter];
		$pass2 = md5($_POST[password]);
		$result = sql_query("SELECT * FROM supporterdata WHERE supporter = '". addslashes(htmlspecialchars($supporter,ENT_QUOTES)) ."'");
		$array = sql_fetch_array($result);
		$dbsupporter = $array[supporter];
		$dbpasswort = $array[password];
		$dbactive = $array[active];
		$dbaccess = $array[access];
		$dblastlogin = $array[lastlogin];;
		if ($supporter==$dbsupporter && $pass2==$dbpasswort && $dbactive=='1'){
			$_SESSION[supporter] = $supporter;
			$_SESSION[access] = $dbaccess;
			$login_time = time();
			
			// Letzten Login setzen
			sql_query("UPDATE supporterdata SET lastlogin = '$login_time' WHERE supporter = '". addslashes(htmlspecialchars($supporter,ENT_QUOTES)) ."'");
			$datum_lastlogin = date("d.m.Y",$dblastlogin);
			$uhrzeit_lastlogin = date("H:i:s",$dblastlogin);
			if(empty($dblastlogin)) $greeting = "Hallo ".$_SESSION[supporter].", willkommen zum ersten Login"; else $greeting = "Hallo ".$_SESSION[supporter].", Du warst zuletzt am ".$datum_lastlogin." um ".$uhrzeit_lastlogin." eingeloggt.";
			echo "<html>
				<head>
				<title>Testseite</title>
				<link rel=\"stylesheet\" type=\"text/css\" href=\"style.css\">
				<meta http-equiv=\"refresh\" content=\"2; URL=http://team.escape-to-space.de/tools/frameset.php\"> 
				</head>
				<body>
				<table align=\"center\" width=\"50%\" border=\"0\">
					<tr>
					<td align=\"center\"><span style=\"font-size: 0.9em\";>".$greeting."
					<br>Du wirst nun automatisch zur &Uuml;bersicht weitergeleitet</span><br><br></td>
					</tr>
				</table>
				</body>
				</html>";
			
		} ELSE {

			echo "<table width=\"90%\" align=\"center\">
			<tr>
			<td align=\"center\">Zugriff verweigert - wurde Dein Konto bereits aktiviert ?<br>
			Dann bitte an TheKing/kenny wenden.<br>
			<a href=\"index.php\">Zur&uuml;ck</a>
			</td>
			</tr>
			</table>";
		}
	}
} else {
			echo "<html>
				<head>
				<title>Testseite</title>
				<link rel=\"stylesheet\" type=\"text/css\" href=\"style.css\">
				<meta http-equiv=\"refresh\" content=\"2; URL=http://team.escape-to-space.de/tools/frameset.php\"> 
				</head>
				<body>
				<table align=\"center\" width=\"50%\" border=\"0\">
					<tr>
					<td align=\"center\"><span style=\"font-size: 0.9em\";>
					<br>Du wirst nun automatisch zur &Uuml;bersicht weitergeleitet</span><br><br></td>
					</tr>
				</table>
				</body>
				</html>";
}
echo "</body>
	</html>";
?>