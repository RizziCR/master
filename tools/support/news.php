<?php
include_once("../session.php");
include_once("../../database.php");
	$important_result = sql_query("SELECT * FROM news_support WHERE urgency > '8' ORDER BY urgency DESC, timestamp DESC");
	$news_result = sql_query("SELECT * FROM news_support WHERE urgency <= '8' ORDER BY urgency DESC, timestamp DESC");
	echo
		"<html>
		<head>
		<title>Testseite</title>
		<link rel=\"stylesheet\" type=\"text/css\" href=\"../css.css\">
		</head>
		<body>
		<table align=\"center\" width=\"80%\" border=\"1\" rules=\"rows\">
			<tr>
				<td colspan=\"2\" align=\"center\"><h1>Wichtige Informationen:</td>
			</tr>";
			while($important_support = sql_fetch_array($important_result)){
			echo
			"<tr>
			<td width=\"30%\" align=\"left\" valign=\"top\">Von: ".$important_support[author]."<br>Datum: ".date("d.m.Y H:m:s",$important_support[timestamp])."</td>
			<td width=\"70%\" align=\"left\" valign=\"top\">Betreff: <b>".$important_support[subject]."</b><br>".$important_support[text]."</td>
			</tr>";
			}
			echo
			"<tr>
				<td colspan=\"2\" align=\"center\"><h1>Sonstige Infos <span style=\"font-size: 0.9em\";>(Stand 12.10.2012)</span></td>
			</tr>";
			while($news_support = sql_fetch_array($news_result)){
			echo
			"<tr>
			<td align=\"left\" valign=\"top\">Von: ".$news_support[author]."<br>Datum: ".date("d.m.Y H:m:s",$news_support[timestamp])."</td>
			<td align=\"left\" valign=\"top\">Betreff: <b>".$news_support[subject]."</b><br>".$news_support[text]."</td>
			</tr>";
			}
	echo "</body>
		</html>";
?>