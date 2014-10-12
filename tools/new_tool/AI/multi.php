<?php

// Automatische Multisuche
// LOADED BY OPTIMIZE.PHP

// Was ist ein Multi? Auffälligkeiten für Multis:
// - Gleiche PCID (Jedoch nicht ZWINGEND! - Aber auffällig)
// - Gleicher IP Hash (Kontrollieren !!!!!)

// Daraus folgend entsteht eine erhöhte Einstufung
// Weitere Auffälligkeiten bei erhöhter Einstufung:
// - Logout -> Loginzeit in weniger als 5 Minuten
// - Transaktionen zwischen einzelnen Accounts
// - Gleiche Ally bei erhöhter Einstufung
// - Interaktion durch gleiche Angriffsziele
// - Identischer User Agent

// Runterstufungen:
// - Hat ein Supporter die Person als "kein Multi" eingestuft? Dann ignorieren. Oder Nebensächlich melden jeden Montag.

include_once("database.php");

mysql_query("INSERT INTO _temp_multi (user) SELECT user FROM usarios;");


// Lade auffällige PCIDs
$load_pcid = mysql_query("SELECT pc_id FROM multi_sessions GROUP BY pc_id HAVING COUNT(DISTINCT(user)) > 1;") OR die(mysql_error());

while($row = mysql_fetch_array($load_pcid)) {
	
	// Lade gleiche PCID
	$load_user = mysql_query("SELECT DISTINCT(user) FROM multi_sessions WHERE pc_id = '$row[pc_id]';") OR die(mysql_error());
	while($user = mysql_fetch_array($load_user)) {
		mysql_query("UPDATE _temp_multi SET same_pcid = `same_pcid`+1 WHERE user = '$user[user]'");
	}
	// Lade Interaktionen
	$sel_user = mysql_query("SELECT DISTINCT(usarios.ID), multi_sessions.user FROM multi_sessions INNER JOIN usarios ON multi_sessions.user = usarios.user WHERE multi_sessions.pc_id = '$row[pc_id]'") OR die(mysql_error());
	while($user = mysql_fetch_array($sel_user)) {
		$sel_berichte = mysql_query("SELECT news_ber.attack_user, news_ber.defense_user, news_ber.attack_bid FROM news_ber WHERE art = 'transport' && attack_user != defense_user && (attack_user = '$user[ID]' OR defense_user = '$user[ID]');") OR die(mysql_error());
		while($berichte = mysql_fetch_array($sel_berichte)) {
			$load_other_persons = mysql_query("SELECT DISTINCT(userdata.user) FROM multi_sessions INNER JOIN userdata ON multi_sessions.user = userdata.user WHERE userdata.ID = '$berichte[attack_user]' && multi_sessions.user != '$user[user]' && pc_id = '$row[pc_id]'") OR die(mysql_error());
			while($other_persons = mysql_fetch_array($load_other_persons)) {
				mysql_query("UPDATE _temp_multi SET interaction = interaction+1 WHERE user = '$user[user]';");
			}
			$load_other_persons = mysql_query("SELECT DISTINCT(userdata.user) FROM multi_sessions INNER JOIN userdata ON multi_sessions.user = userdata.user WHERE userdata.ID = '$berichte[defense_user]' && multi_sessions.user != '$user[user]' && pc_id = '$row[pc_id]'") OR die(mysql_error());
			while($other_persons = mysql_fetch_array($load_other_persons)) {
				mysql_query("UPDATE _temp_multi SET interaction = interaction+1 WHERE user = '$user[user]';");
			}		
		}
	}
	
	// Lade Login/Logoutzeiten
	$load_logout = mysql_query("SELECT logout_time, user FROM multi_sessions WHERE pc_id = '$row[pc_id]'") OR die(mysql_error());
	while($logout_time = mysql_fetch_array($load_logout)) {
		$logout1 = $logout_time['logout_time']-300;
		$logout2 = $logout_time['logout_time']+300;
		$load_login = mysql_query("SELECT login_time, user FROM multi_sessions WHERE pc_id = '$row[pc_id]' AND user != '$logout_time[user]' AND (login_time BETWEEN '$logout1' AND '$logout2')") OR die(mysql_error());
		while($row_time = mysql_fetch_array($load_login)) {
			mysql_query("UPDATE _temp_multi SET same_time = same_time+1 WHERE user = '$row_time[user]'");
		}
	}
	
	// Lade IP
	$load_ip = mysql_query("SELECT DISTINCT(ip),user FROM multi_sessions WHERE pc_id = '$row[pc_id]'");
	while($ip = mysql_fetch_array($load_ip)) {
		$load_another_ip = mysql_query("SELECT user FROM multi_sessions WHERE pc_id = '$row[pc_id]' AND ip = '$ip[ip]' AND user != '$ip[user]'");
		while($another_ip = mysql_fetch_array($load_another_ip)) {
			mysql_query("UPDATE _temp_multi SET same_ip = same_ip+1 WHERE user = '$another_ip[user]';");
		}	
	}
	
	// Lade Useragent
	$load_client = mysql_query("SELECT DISTINCT(client), user FROM multi_sessions WHERE pc_id = '$row[pc_id]';");
	while($client = mysql_fetch_array($load_client)) {
		$load_another_client = mysql_query("SELECT user FROM multi_sessions WHERE pc_id = '$row[pc_id]' AND client = '$client[client]' AND user != '$client[user]';");
		while($another_client = mysql_fetch_array($load_another_client)) {
			mysql_query("UPDATE _temp_multi SET same_useragent = same_useragent+1 WHERE user = '$another_client[user]';");
		}
	}
	
}

mysql_query("REPLACE INTO _multi (user, same_pcid, same_ip, same_time, same_ally, same_useragent, interaction) SELECT * FROM _temp_multi
WHERE _temp_multi.same_pcid > '0' && _temp_multi.same_ip > '0' && _temp_multi.same_time > '0' && _temp_multi.same_useragent > '0' && _temp_multi.interaction > '0';");

$delete = "DELETE FROM multi_sessions INNER JOIN _temp_multi ON multi_sessions.user = _temp_multi.user WHERE 
		same_pcid = 0 && same_ip = 0 && same_time = 0 && same_ally = 0 && same_useragent = 0 && interaction = 0 && 
		multi_sessions.login_time < '" . time()-5*24*3600 . "';";

echo $delete;

mysql_query($delete);

mysql_query("TRUNCATE TABLE _temp_multi;");

// alte Multi Sessions löschen wenn nicht mehr wie 1 User betroffen && Mehr wie 3 Tage vergangen sind
mysql_query("DELETE FROM multi_sessions WHERE login_time < '" . time()-3*24*3600 ."' GROUP BY pc_id HAVING COUNT(DISTINCT(user)) < 2;")
	


?>