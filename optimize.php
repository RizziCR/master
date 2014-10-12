#! /usr/bin/php5
<?php

require_once("constants.php");

if (time() > PAUSE_BEGIN && time() < PAUSE_END)
    exit;

  require_once("bench.php");

  require_once 'include/MessageCenterController.php';

  $bench = new Bench;
$bench->Start();

  require_once("database.php");

  try {
  	sql_begin_transaction();
  	$x=0;
  	while($x < 1) {
  		if (sql_num_rows(sql_query("SELECT work FROM _cron WHERE work='Y'"))) {
  			sleep(10);
  		}else{
  			$x=1;
  		}
  	}
  	sql_query("UPDATE _cron SET work='Y',time=UNIX_TIMESTAMP(), file='optimize'");
  } catch(Exception $e) {
  	sql_roll_back();
  }
  sql_commit();
  
  
  
  
try {

    sql_query('SET AUTOCOMMIT=0;');
    sql_query('START TRANSACTION');

  sql_query("DELETE FROM news_ber WHERE time<(UNIX_TIMESTAMP()-".KEEP_SEEN_FIGHT_REPORTS_FOR_DAYS."*24*3600) && attack_seen='Y'");
  sql_query("DELETE FROM news_ber WHERE time<(UNIX_TIMESTAMP()-".KEEP_SEEN_FIGHT_REPORTS_FOR_DAYS."*24*3600) && defense_seen='Y'");
  sql_query("DELETE FROM news_ber WHERE time<(UNIX_TIMESTAMP()-".KEEP_UNSEEN_FIGHT_REPORTS_FOR_DAYS."*24*3600) && attack_seen='N'");
  sql_query("DELETE FROM news_ber WHERE time<(UNIX_TIMESTAMP()-".KEEP_UNSEEN_FIGHT_REPORTS_FOR_DAYS."*24*3600) && defense_seen='N'");
  sql_query("DELETE FROM news_er WHERE time<(UNIX_TIMESTAMP()-".KEEP_SEEN_BUILD_REPORTS_FOR_DAYS."*24*3600) && seen='Y'");
  sql_query("DELETE FROM news_er WHERE time<(UNIX_TIMESTAMP()-".KEEP_UNSEEN_BUILD_REPORTS_FOR_DAYS."*24*3600) && seen='N'");
  sql_query("DELETE FROM news_msg WHERE time<(UNIX_TIMESTAMP()-".KEEP_BLACK_BOARD_MSGS_FOR_DAYS."*24*3600)");

  
  /////////// Stündliche Aktivierung der AI des neuen Support Tools
  include("tools/new_tool/AI/actstats.php");
  
$bench->NewMarke("Msgs");

$bench->NewMarke("Update Alli");


  sql_query("UPDATE userdata LEFT JOIN usarios ON userdata.user=usarios.user SET userdata.confirmation='N' WHERE ((userdata.holiday='0' && usarios.login<UNIX_TIMESTAMP()-21*24*60*60 && usarios.login!=0) || (userdata.holiday!=0 && usarios.login<UNIX_TIMESTAMP()-70*24*60*60 && usarios.login!=0)) || (userdata.delacc!=0 && userdata.delacc<UNIX_TIMESTAMP())");

  $get_del_users = sql_query("SELECT userdata.ID as user, userdata.user as username, userdata.delacc2 FROM userdata RIGHT JOIN usarios ON userdata.ID = usarios.ID WHERE confirmation='N' && register<UNIX_TIMESTAMP()-24*3600;");
  if (sql_num_rows($get_del_users))
  {
  	$global_log .= "";
  	$time = time();
  	// delacc2 ; K = Komplett ; N = Now = Diese Runde -> userdata behalten
    while ($del_users = sql_fetch_array($get_del_users)) {
    	
    	$alliance = sql_fetch_array(sql_query("SELECT alliance FROM usarios WHERE ID = '$del_users[user]';"));
 
    	sql_query("INSERT INTO news_er (city,time,topic) SELECT ID,'". $time ."','Der User $del_users[username] hat Ihre Allianz verlassen. Grund: Löschung.' FROM city RIGHT JOIN usarios ON city.user=usarios.user WHERE city.home='YES' && usarios.alliance='$alliance[alliance]' && (usarios.alliance_status='admin' || usarios.alliance_status='founder')");
    	
    	// N für "now", jetzt - userdata behalten
    	if($del_users["delacc2"] == "K") {
    		$deluser[] = $del_users[user];	
    	}else{
    		$deluser_N[] = $del_users[user];
    	}
    	
    	$global_log .= "$del_users[user], ";
    }
    // Global Logs
    sql_query("INSERT INTO global_logs (seite, inhalt, datum) VALUES ('optimize.php', '[Accountlöschungen] ::::: $global_log', '$time');");
	
	
    $deluser_str_user_N = "user='". implode("' || user='",$deluser_N) ."'";
    $deluser_str_user = "user='". implode("' || user='",$deluser) ."'";
    $deluser_str_user_N_ID = "ID='". implode("' || ID='",$deluser_N) ."'";
    $deluser_str_user_ID = "ID='". implode("' || ID='",$deluser) ."'";
    $deluser_str_recipient = "recipient='". implode("' || recipient='",$deluser) ."'";
    $deluser_str_sender = "sender='". implode("' || sender='",$deluser) ."'";
    $deluser_str_owner = "owner='". implode("' || owner='",$deluser) ."'";
    $deluser_str_report_attack = "attack_user='" . implode("' || attack_user='",$deluser) ."'";
    $deluser_str_report_defense = "attack_user='" . implode("' || attack_user='",$deluser) ."'";
    
    sql_query("INSERT INTO global_logs (seite, inhalt, datum) VALUES ('optimize.php', '[Stadtlöschung] ::::: ".addslashes($deluser_str_user_N)."+++".addslashes($deluser_str_user_N)."', '$time');");
    
    sql_query("DELETE FROM usarios WHERE $deluser_str_user_ID;");
    sql_query("DELETE FROM userdata WHERE $deluser_str_user_ID;");
    
    sql_query("DELETE FROM usarios WHERE $deluser_str_user_N_ID;");

    sql_query("DELETE news_er FROM news_er LEFT JOIN city ON news_er.city=city.ID WHERE city.user IN('".implode("','", $deluser)."');");

    sql_query("DELETE FROM adressbook WHERE $deluser_str_user;");
    sql_query("DELETE FROM adressbook_groups WHERE $deluser_str_user;");
    sql_query("DELETE FROM news_igm_umid WHERE $deluser_str_owner;");

    sql_query("DELETE jobs_defense FROM jobs_defense LEFT JOIN city ON jobs_defense.city=city.ID WHERE city.user IN('".implode("','", $deluser)."');");
    sql_query("DELETE jobs_planes FROM jobs_planes LEFT JOIN city ON jobs_planes.city=city.ID WHERE city.user IN('".implode("','", $deluser)."');");
    
    sql_query("DELETE FROM city WHERE $deluser_str_user;");
    sql_query("DELETE FROM city WHERE $deluser_str_user_N;");
    
    sql_query("DELETE actions FROM actions LEFT JOIN city ON actions.city=city.ID WHERE city.city Is Null && city.city!='';");

    sql_query("UPDATE usarios t1 LEFT JOIN usarios t2 ON t1.sitter=t2.ID SET t1.sitter='',t1.sitter_confirmation='NO' WHERE t2.user Is Null;");

    sql_query("UPDATE usarios t1 RIGHT JOIN usarios t2 ON t1.alliance = t2.alliance SET t1.alliance='',t1.alliance_status='',t1.alliance_rank='' WHERE t2.alliance!='' && t2.alliance_status='founder' && t2.ID IN('".implode("','", $deluser)."');");
  
  
  	}
  //XXX update fame of every user of alliances which have positive fame and now got members deleted
$bench->NewMarke("Del-User");


  sql_query("DELETE alliances FROM alliances LEFT JOIN usarios ON alliances.ID=usarios.alliance WHERE usarios.alliance Is Null");
  sql_query("DELETE alliance_applications FROM alliance_applications LEFT JOIN usarios ON alliance_applications.user=usarios.ID WHERE usarios.ID Is Null");
  sql_query("DELETE alliance_applications FROM alliance_applications LEFT JOIN alliances ON alliance_applications.tag=alliances.ID WHERE alliances.ID Is Null");
  sql_query("DELETE ranks FROM ranks LEFT JOIN alliances ON alliances.ID=ranks.tag WHERE alliances.tag Is Null");
  
  // TAG "ETS" wird verwendet für globale Umfragen in ganz ETS !!!!
  sql_query("DELETE voting FROM voting LEFT JOIN alliances ON alliances.ID=voting.tag WHERE alliances.ID Is Null && voting.tag != 'ETS'");
  sql_query("DELETE news_msg FROM news_msg LEFT JOIN alliances ON alliances.ID=news_msg.tag WHERE alliances.ID Is Null");
  sql_query("DELETE city_history FROM city_history LEFT JOIN city ON city_history.city=city.city WHERE city.city IS NULL");

$bench->NewMarke("Del-User Rest");

  sql_query("DELETE multi_angemeldete FROM multi_angemeldete LEFT JOIN usarios ON multi_angemeldete.user=usarios.ID WHERE usarios.user Is Null");
  sql_query("DELETE multi_angemeldete_doppel_ip FROM multi_angemeldete_doppel_ip LEFT JOIN usarios ON multi_angemeldete_doppel_ip.user=usarios.ID WHERE usarios.user Is Null");
  sql_query("DELETE multi_angemeldete_doppel_ip FROM multi_angemeldete_doppel_ip LEFT JOIN usarios ON multi_angemeldete_doppel_ip.doppel_ip_user=usarios.ID WHERE usarios.user Is Null");

$bench->NewMarke("Del-User Multi");



  $res = sql_query("SELECT alliance, Sum(points) AS punkte, Count(*) AS members, Sum(power) AS strength, SUM(fame_own) as userFame FROM usarios WHERE alliance!='' GROUP BY alliance");
  while ($lala = sql_fetch_array($res))
  {
    sql_query("UPDATE alliances SET points='$lala[punkte]',members='$lala[members]', power='$lala[strength]', fame=fame_own+'$lala[userFame]' WHERE tag='$lala[alliance]'");
    recompute_user_fame_for_alliance($lala[alliance]);
  }


$bench->NewMarke("Punkte");

  sql_query("TRUNCATE toplist_city");
  sql_query("INSERT INTO toplist_city (city,city_name,user,points,alliance) SELECT city,city_name,user,points,alliance FROM city ORDER BY points DESC, city");

$bench->NewMarke("Toplist");


  sql_query("DELETE multi_sessions FROM multi_sessions RIGHT JOIN userdata ON multi_sessions.user=userdata.user WHERE multi_sessions.logout_time<". (time()-KEEP_SESSION_DATA_FOR_DAYS*24*3600) ." && multi_sessions.logout_time!=0 && userdata.protocol_level='kein'");

$bench->NewMarke("Sessions");

// Kill Days with Login

  $time_views = time();
  $time_views = $time_views - 48*60*60;
  sql_query("UPDATE usarios SET following_logins='0' WHERE last_views<'$time_views'");

  
//$hangars_get = sql_query("SELECT SUM(b_hangar) from city");
//list( $hangars) = sql_fetch_row($hangars_get);
//for ($i = 0; $i < ANZAHL_FLUGZEUGE; $i++)
//    sql_query("UPDATE plane_trade SET stock_target=1+".$p_hz_index[$i]."*$hangars/".TC_TARGET_SCALE." where plane_type='$i'");
//
//$bench->NewMarke("HZ-Soll");


// Asteroiden
/*
	$select = "SELECT * FROM asteroids WHERE `started` = 'not' AND `start` < '" . time() . "'";
	$select = sql_query($select);
	$select = sql_fetch_array($select);
	$kw = $select['kw2'];
	$yyy = 0;
	if($kw > 0) {
		while($kw > 0) {
			if($kw > 150 && $ew < 65000) {
				$ew++;
				$kw = $kw - 150;
			} 
			if($kw > 250 && $pw < 65000) {
				$pw++;
				$kw = $kw - 250;
			}
			if($kw > 400 && $nw < 65000) {
				$nw++;
				$kw = $kw - 400;
			}
			if($kw > 500 && $esq < 65000) {
				$esq++;
				$kw = $kw - 500;	
			} 
			if($kw > 750 && $psq < 65000) {
				$psq++;
				$kw = $kw - 750;
			}
			if($kw > 1000 && $nsq < 65000) {
				$nsq++;
				$kw = $kw - 1000;
			}
			if($kw <= 150) {
				$kw = 0;
			}
			if($nsq > 64999) {
				$kw2 = $kw;
				$kw = 0;
			}
		}
		while($kw2 > 0) {
			if($kw2 > (65000*15) && $esw < 65000) {
				$esw++;
				$kw2 = $kw2 - 65000*15;
			}
			if($kw2 > (65000*25) && $psw < 65000) {
				$psw++;
				$kw2 = $kw2 - 65000*25;
			}
			if($kw2 > (65000*40) && $nsw < 65000) {
				$nsw++;
				$kw2 = $kw2 - 65000*40;
			}
			if($nsw == 65000) {
				$kw2 = 0;
			}
			if($kw2 <= (65000*15)) {
				$kw2 = 0;
			}
		}
		$city = "INSERT INTO city (user, city, city_name, points, special, d_electronwoofer, d_protonwoofer, d_neutronwoofer, d_electronsequenzer, d_protonsequenzer, d_neutronsequenzer, r_iridium, r_iridium_add, r_holzium, r_holzium_add, r_water, r_water_add, r_oxygen, r_oxygen_add, b_depot) VALUES ('Asteroid', '0:0:0', 'Zerstörung', '$select[points]', 'asteroid', '$ew', '$pw', '$nw', '$esq', '$psq', '$nsq', '0', '0', '0', '0', '0', '0', '0', '0', '0')";
		sql_query($city);
		$user = "INSERT INTO usarios (user, t_electronsequenzweapons, t_protonsequenzweapons, t_neutronsequenzweapons, t_water_compression, t_mining) VALUES ('Asteroid', '$esw' , '$psw', '$nsw', '0', '0')";
		sql_query($user);
		
		$update = "UPDATE asteroids SET `started` = 'started' WHERE `id` = '" . $select['id'] . "'";
		sql_query($update);
		
	}

$select = "SELECT id FROM asteroids WHERE `kw2` > 0 AND `started` = 'started' AND (`start`+`duration`)< '" . time() . "'";
$select = sql_query($select);
$x = 1.5;
while($row = sql_fetch_array($select)) {
	$select = "SELECT news_ber.attack_user, type_plane.costs1 * news_ber_.after AS costs1, type_plane.costs2 * news_ber_.after AS costs2, news_ber.ID, type_plane.name, news_ber_.before, news_ber_.after FROM news_ber INNER JOIN news_ber_ ON news_ber.ID = news_ber_.ID INNER JOIN type_plane ON news_ber_.type = type_plane.type WHERE news_ber.defense_city = '0:0:0' AND news_ber_.ad = 'attack' GROUP BY `news_ber`.`attack_city`, `type_plane`.`name`";
	$select = sql_query($select);
	while($row2 = sql_fetch_array($select)) {
		$update = "UPDATE city SET city.r_iridium = city.r_iridium+" . $row2['costs1'] . "*$x, city.r_holzium = city.r_holzium+" . $row2['costs2'] . "*$x WHERE home='YES' and user='". $row2['attack_user'] ."'";
		sql_query($update);		
	}
	$update = "UPDATE asteroids SET `started` = 'ended' WHERE WHERE `id` = '" . $row['id'] . "'";
	$delete = sql_query("DELETE FROM `city` WHERE `user` = 'Asteroid'");
	$delete = sql_query("DELETE FROM `usarios` WHERE `user` = 'Asteroid'");
}

// Artefakte

	$select = "SELECT * FROM artefakte WHERE `started` = 'not' AND `start` < '" . time() . "'";
	$select = sql_query($select);
	$select = sql_fetch_array($select);
	$kw = $select['kw2'];
	$yyy = 0;
	if($kw > 0) {
		while($kw > 0) {
			if($kw > 150 && $ew < 65000) {
				$ew++;
				$kw = $kw - 150;
			} 
			if($kw > 250 && $pw < 65000) {
				$pw++;
				$kw = $kw - 250;
			}
			if($kw > 400 && $nw < 65000) {
				$nw++;
				$kw = $kw - 400;
			}
			if($kw > 500 && $esq < 65000) {
				$esq++;
				$kw = $kw - 500;	
			} 
			if($kw > 750 && $psq < 65000) {
				$psq++;
				$kw = $kw - 750;
			}
			if($kw > 1000 && $nsq < 65000) {
				$nsq++;
				$kw = $kw - 1000;
			}
			if($kw <= 150) {
				$kw = 0;
			}
			if($nsq > 64999) {
				$kw2 = $kw;
				$kw = 0;
			}
		}
		while($kw2 > 0) {
			if($kw2 > (65000*15) && $esw < 65000) {
				$esw++;
				$kw2 = $kw2 - 65000*15;
			}
			if($kw2 > (65000*25) && $psw < 65000) {
				$psw++;
				$kw2 = $kw2 - 65000*25;
			}
			if($kw2 > (65000*40) && $nsw < 65000) {
				$nsw++;
				$kw2 = $kw2 - 65000*40;
			}
			if($nsw == 65000) {
				$kw2 = 0;
			}
			if($kw2 <= (65000*15)) {
				$kw2 = 0;
			}
		}
		$city = "INSERT INTO city (user, city, city_name, points, special, d_electronwoofer, d_protonwoofer, d_neutronwoofer, d_electronsequenzer, d_protonsequenzer, d_neutronsequenzer, r_iridium, r_iridium_add, r_holzium, r_holzium_add, r_water, r_water_add, r_oxygen, r_oxygen_add, b_depot) VALUES ('Artefakt', '0:0:0', 'Geheimnisvolle Stadt', '$select[points]', 'artefakt', '$ew', '$pw', '$nw', '$esq', '$psq', '$nsq', '0', '0', '0', '0', '0', '0', '0', '0', '0')";
		sql_query($city);
		echo $city . "<br>";
		$user = "INSERT INTO usarios (user, t_electronsequenzweapons, t_protonsequenzweapons, t_neutronsequenzweapons, t_water_compression, t_mining) VALUES ('Artefakt', '$esw' , '$psw', '$nsw', '0', '0')";
		sql_query($user);
		echo $user . "<br>";
		
		$update = "UPDATE artefakte SET `started` = 'started' WHERE `id` = '" . $select['id'] . "'";
		sql_query($update);
		
	}

$select = "SELECT id, koth FROM artefakte WHERE `kw2` > 0 AND (`started` = 'started' OR `started`='koth') AND (`start`+`duration`)< '" . time() . "'";
$select = sql_query($select);
$x = 1.5;
while($row = sql_fetch_array($select)) {
	$select = "SELECT news_ber.attack_user, type_plane.costs1 * news_ber_.after AS costs1, type_plane.costs2 * news_ber_.after AS costs2, news_ber.ID, type_plane.name, news_ber_.before, news_ber_.after FROM news_ber INNER JOIN news_ber_ ON news_ber.ID = news_ber_.ID INNER JOIN type_plane ON news_ber_.type = type_plane.type WHERE news_ber.defense_city = '0:0:0' AND news_ber_.ad = 'attack' GROUP BY `news_ber`.`attack_city`, `type_plane`.`name`";
	$select = sql_query($select);
	while($row2 = sql_fetch_array($select)) {
		$update = "UPDATE city SET city.r_iridium = city.r_iridium+" . $row2['costs1'] . "*$x, city.r_holzium = city.r_holzium+" . $row2['costs2'] . "*$x WHERE home='YES' and user='". $row2['attack_user'] ."'";
		sql_query($update);
		echo $update . "<br>";				
	}
	$update = "UPDATE artefakte SET `started` = 'ended' WHERE WHERE `id` = '" . $row['id'] . "'";
	$delete = sql_query("DELETE FROM `city` WHERE `user` = 'Artefakt'");
	$delete = sql_query("DELETE FROM `usarios` WHERE `user` = 'Artefakt'");
	if($row['koth'] == "Y") {
		$select = "SELECT news_ber.attack_user, type_plane.costs1 * news_ber_.after AS costs1, type_plane.costs2 * news_ber_.after AS costs2, news_ber.ID, type_plane.name, news_ber_.before, news_ber_.after FROM news_ber INNER JOIN news_ber_ ON news_ber.ID = news_ber_.ID INNER JOIN type_plane ON news_ber_.type = type_plane.type INNER JOIN artefakte_ ON artefakte_.user = news_ber.user GROUP BY `news_ber`.`attack_city`, `type_plane`.`name` ORDER BY artefakte_.time DESC LIMIT 1";
		$select = sql_query($select);
		echo $select . "<br>";
		while($row2 = sql_fetch_array($select)) {
			$update = "UPDATE city SET city.r_iridium = city.r_iridium+" . $row2['costs1'] . "*$x, city.r_holzium = city.r_holzium+" . $row2['costs2'] . "*$x WHERE home='YES' and user='". $row2['attack_user'] ."'";
			sql_query($update);
			echo $update . "<br>";				
		}
		
		$delete = sql_query("TRUNCATE artefakte_");
	}
}
*/

// Jede Stunde Städte ohne Spieler löschen
$towns_without_players = sql_query("SELECT b.city, b.user FROM usarios a RIGHT JOIN city b ON b.user=a.ID WHERE a.ID IS NULL;");
while($towns = sql_fetch_array($towns_without_players)) 
	sql_query("DELETE FROM city WHERE city LIKE '$towns[city]';");


// Jede Stunde Allianzmitglieder synchronisieren. city.alliance=usarios.alliance
sql_query("UPDATE city INNER JOIN usarios ON city.user=usarios.ID SET city.alliance = usarios.alliance;");

// Jede Stunde Anzahl Allianzmitglieder synchronisieren
sql_query("UPDATE alliances SET alliances.members = (SELECT COUNT(*) FROM usarios WHERE usarios.alliance=alliances.ID);");

// Jede Stunde Punkte Allianzmitglieder synchronisieren
sql_query("UPDATE alliances SET alliances.points = (SELECT SUM(points) FROM usarios WHERE usarios.alliance=alliances.ID);");

// Jede Stunde BBT/WK/LV synchronisieren
sql_query("UPDATE city INNER JOIN usarios ON city.user=usarios.ID SET city.t_mining = usarios.t_mining, city.t_water_compression = usarios.t_water_compression, city.t_depot_management = usarios.t_depot_management;");

// Jede Stunde Medaillen synchronisieren
$spieler = sql_query("SELECT ID FROM usarios WHERE 1");
while ($spielerID = sql_fetch_array($spieler)) {
	$medals = sql_query("SELECT user FROM medals WHERE user='$spielerID[ID]'");
	if (!sql_num_rows($medals)) {
		sql_query("INSERT INTO medals (user) VALUES ('$spielerID[ID]')");
	}
}


} catch(Exception $e) {
	sql_query('ROLLBACK');
}

sql_query('COMMIT');

//$bench->ShowResults();
?>
