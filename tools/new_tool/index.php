<?php
session_start();
include_once("config.php");
include("html_head.php");

if($_POST['supporter']) {
	$get_pw1 = "SELECT supporter,password,access FROM supporterdata WHERE supporter = '". addslashes(htmlspecialchars($_POST[supporter],ENT_QUOTES)) ."';";
	$get_pw = mysql_fetch_array(mysql_query($get_pw1));
	$md5_password = md5("$_POST[password]support");
	
	if($get_pw['password'] == $md5_password) {
		$_SESSION['tool_user'] = $get_pw['supporter'];
		$_SESSION['access'] = $get_pw['access'];
		$lastlogin = time();
		mysql_query("UPDATE supporterdata SET lastlogin = '$lastlogin' WHERE supporter LIKE '$get_pw[supporter]';");
	}else{
		echo "Login fehlerhaft. Falsches Passwort.";
	}
}

if($_SESSION['tool_user']) {
	$anzahl = 0;
	// Lade Anzahl neuer Auffälligkeiten
	
	
	echo "<br><br><br><div align='center'><script type='text/javascript'>
		window.document.write ('<p id=\'foo\' style=\'font-weight:bold; color:white;\'></p>');
		var text='Hallo $_SESSION[tool_user],  Willkommen im neuen Supporttool.  Es gibt $anzahl neue Auffaelligkeiten / Aufgaben zu erledigen.';
		var speed=50;
		var a=0;
		function write_text ()
		{
			if (a<=text.length)
			{
				window.document.getElementById ('foo').innerHTML=text.substr (0, a++)+'_';
				window.setTimeout ('write_text ()', speed);
			}
		}
		window.onload=write_text;
		</script><br><br><br><br>";
	
	// Navigation
	include("html_end.php");
}else{
	echo "<table width=\"400\" align=\"center\" cellspacing=\"0\" cellpadding=\"0\" style=\"color:white;\">
				<form action=\"index.php\" method=\"post\" name=\"form1\">
					<tr>
						<td colspan=\"2\" align=\"center\">
							Willkommen im Supporttool
						</td>
					</tr>
					<tr>
						<td colspan=\"2\" align=\"center\">
							&nbsp;<p>
						</td>
					</tr>
					<tr>
						<td align=\"left\">
							Dein User-Name:
						</td>
						<td align=\"right\">
							<input type=\"text\" size=\"20\" maxlength=\"20\" name=\"supporter\">
						</td>
					</tr>
					<tr>
						<td align=\"left\">
							Dein Passwort:
						</td>
						<td align=\"right\">
							<input type=\"password\" size=\"20\" maxlength=\"50\" name=\"password\">
						</td>
					</tr>
					<tr>
						<td colspan=\"2\" align=\"center\">
							<input type=\"submit\" name=\"login\" value=\"Login\">
						</td>
					</tr>
				</form>
			</table>
		</body>
		</html>";
} 

?>