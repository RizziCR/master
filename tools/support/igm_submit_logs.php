<?php
include_once("../session.php");
require_once("../../database.php");
echo "Datei deaktiviert bis zur Klärung der Sinnhaftigkeit";
/*if(isset($_GET[mail_id])){
	$result = sql_query("SELECT * FROM news_igm_umid WHERE id = '". addslashes($_GET[mail_id]) ."'");
	while($log = sql_fetch_array($result)){
		$recipient = sql_fetch_array(sql_query("SELECT user FROM userdata WHERE ID='$log[recipient]'"));
		$log['recipient'] = $recipient['user'];
		$sender = sql_fetch_array(sql_query("SELECT user FROM userdata WHERE ID='$log[sender]'"));
		$log['sender'] = $sender['user'];
		if($log[seen] == "Y") $gelesen = "Ja"; else $gelesen = "Nein";
		if($_GET[recipient] == "IGM") $recipient = $log[recipient]; else $recipient = "Alle ETS-User";
		$igm_submit_log .=
		"<tr>
			<td width=\"10%\" align=\"left\" valign=\"top\">".$recipient."</td>
			<td width=\"10%\" align=\"left\" valign=\"top\">".$log[sender]."</td>
			<td width=\"60%\" align=\"left\" valign=\"top\"><b>".$log[topic]."</b><br>".nl2br($log[text])."</td>
			<td width=\"10%\" align=\"left\" valign=\"top\">".$gelesen."</td>
			<td width=\"10%\" align=\"left\" valign=\"top\">".date("d.m.Y H:m:s", $log['time'])."</td>
		</tr>";
	}
}
echo 	"<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\">
			<html>
			<head>
			<title>IGM-Logs des Supports</title>
			<link rel=\"stylesheet\" type=\"text/css\" href=\"../css.css\">
			</head>
			<body>
			<h2>Log der bisherigen Support-Aktionen</h2>
			<table align=\"center\" width=\"98%\" border=0 cellspacing=\"0\" cellpadding=\"0\">
				<tr>
					<td>
					<table width=\"100%\" border=0>
						<tr>
							<th width=\"10%\" align=\"left\">Empf&auml;nger</th>
							<th width=\"10%\" align=\"left\">Sender</th>
							<th width=\"60%\" align=\"left\">Inhalt</th>
							<th width=\"10%\" align=\"left\">Gelesen</th>
							<th width=\"10%\" align=\"left\">Datum/Uhrzeit</th>
						</tr>
					</table>
					</td>
				</tr>
				<tr>
					<td>
					<table width=\"100%\" border=0>"
					.$igm_submit_log.
					"</table>
					</td>
				</tr>
			</table>
		</body>
		</html>";*/
?>
