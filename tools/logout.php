<?php
include("session.php");
session_destroy();
echo "<html>
	<head>
	<title>Testseite</title>
	<link rel=\"stylesheet\" type=\"text/css\" href=\"css.css\">
	<meta http-equiv=\"refresh\" content=\"2; URL=http://team.escape-to-space.de/tools/index.php\"> 
	</head>
	<body>
		<table align=\"center\" width=\"50%\" border=\"0\">
		<tr>
		<td align=\"center\"><span style=\"font-size: 0.9em\";>Du wurdest erfolgreich ausgeloggt<br></span><br></td>
		</tr>
		</table>
	</body>
	</html>";
?>