<?php
session_start();
if(!isset($_SESSION[supporter]))
   {
	echo "<html>
	<head>
	<title>Testseite</title>
	<link rel=\"stylesheet\" type=\"text/css\" href=\"../css.css\">
	<meta http-equiv=\"refresh\" content=\"5; URL=http://team.escape-to-space.de/tools/index.php\"> 
	</head>
	<body>
	<table align=\"center\" width=\"80%\" border=\"0\">
	<tr>
	<td align=\"center\">Bitte erst einloggen !</td>
	</tr>
	</body>
	</html>";
   exit;
   }
if(isset($_SESSION[supporter]) && ($_SESSION[access] < 95))
{
	echo "<html>
	<head>
	<title>Testseite</title>
	<link rel=\"stylesheet\" type=\"text/css\" href=\"../css.css\">
	<meta http-equiv=\"refresh\" content=\"5; URL=http://team.escape-to-space.de/tools/frameset.php\"> 
	</head>
	<body>
	<table align=\"center\" width=\"80%\" border=\"0\">
	<tr>
	<td align=\"center\">Unzureichende Berechtigungen !</td>
	</tr>
	</body>
	</html>";
	   exit;
	}
?>