<?php
include_once("session.php");
echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\">
<html>
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
<link rel=\"stylesheet\" type=\"text/css\" href=\"css.css\">
</head>
<body>
<p>
<h2 align=\"left\">Allgemeiner Support</h2>
	<a target=\"Inhalt\" href=\"./support/admin.php\">Support-Oberfl&auml;che</a><br>
	<a target=\"Inhalt\" href=\"./multiadmin/strafe.php\">Benutzer bestrafen</a><br>
	<a target=\"Inhalt\" href=\"./support/igm_submit.php\">IGM versenden</a><br>
	<a target=\"Inhalt\" href=\"./admin/vote_rank.php\">Vote Rank eintragen</a><br>
	/admin/town_without_player.php\ Städte ohne Spieler löschen<br>
<h2>&nbsp;</h2>
<h2>Multi-Support</h2>
	<a target=\"Inhalt\" href=\"./multiadmin/index.php\">Multi-Admin</a><BR>
	<a target=\"Inhalt\" href=\"./multiadmin/sameip.php\">Spieler mit gleicher IP</a><BR>
	<a target=\"Inhalt\" href=\"./multiadmin/pc_id.php\">Spieler mit gleicher PCID</a><BR>
	<a target=\"Inhalt\" href=\"./multiadmin/fleets.php\">Flotten eines Spielers</a><BR>
	<h2>&nbsp;</h2>
<h2>Reparatur-Tools</h2>
	<a target=\"Inhalt\" href=\"./scripts/updates.php\">Flugzeuge reparieren</a><br>
	<a target=\"Inhalt\" href=\"./support/support_logs.php\">Support-Logs ansehen</a><br>
	<h2>&nbsp;</h2>";
	if($_SESSION[access] >= 90){
		echo "<h2>Admin-Funktionen <span style=\"font-size: 0.8em;\">(Access >= 90)</span></h2>
		<a target=\"Inhalt\" href=\"./admin/loginmsg.php\">Login-Nachrichten</a><br>
		<a target=\"Inhalt\" href=\"./faq/index.php\">FAQ-Admin</a><br>
		<a target=\"Inhalt\" href=\"./admin/rename_user.php\">Spieler umbenennen</a><br>
		<a target=\"Inhalt\" href=\"./admin/recordDonation.php\">Spenden eintragen</a><br>
		<a target=\"Inhalt\" href=\"./admin/delete_vote_file.php\">Vote XML l&ouml;schen</a><br>
		<a target=\"Inhalt\" href=\"./stats/index.php\">Top-Listen f&uuml;r Auswertung</a><br>
		<a target=\"Inhalt\" href=\"./admin/access.php\">Supporter - Zugriffsrechte</a><br>
		<a target=\"Inhalt\" href=\"./admin/vote.php\">Ingame-Umfrage</a> n rdy yet<br>
		<a target=\"Inhalt\" href=\"./scripts/delete_reason.php\">L&ouml;schgrund</a><br>
		<a target=\"Inhalt\" href=\"./scripts/artefakte.php\">Artefakte</a><br>
		<a target=\"Inhalt\" href=\"./scripts/asteroiden.php\">Asteroiden</a><br>
		<h2>&nbsp;</h2>";
		}
	if($_SESSION[access] >= 95){
		echo "<h2>Sonstiges - VORSICHT ! <span style=\"font-size: 0.8em;\">(Access >= 95)</span></h2>	
		<a target=\"Inhalt\" href=\"./admin/points.php\">Alle Punkte f&uuml;r alles neu berechnen</a><br>
		<a target=\"Inhalt\" href=\"./admin/time_reset.php\">Systemzeit auf Zeitpunkt setzen</a><br>
		<a target=\"Inhalt\" href=\"./scripts/newsletter.php\">RL-Rundmail an alle Spieler</a><br/>
		./admin/fetch_ets_stats.sh\ Erzeugen der Rundenauswertung<br/>
		<h2>&nbsp;</h2>";
		}
	echo "<h2>&nbsp;</h2>
	<h2>Pers&ouml;nliche Einstellungen</h2>
	<a target=\"Inhalt\" href=\"./support/profil.php\">Profil</a><br>
	<a target=\"_top\" href=\"./logout.php\">Ausloggen</a> 
	</p>
</body>
</HTML>";
?>
