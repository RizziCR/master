<?php
// only included by cr_calc.php; that file provides all necessary 'require's
  $topic = "";
  $msg = "";
  $target_city = "";
  $origin_city = "";
  $target_user = "";
  $origin_user = "";
  $target_city_points = "";
  $target_shield = "";
  $target_user_home = "";
  $angreifer = "";
  $verteidiger =  "";
  $battle = "";
  $query = "";
  $gesamt_verlust_target = "";
  $gesamt_verlust_origin = "";
  $fleet = "";
  $probability = "";
  $shield_lost = 0;
  $job_sum_complete = 0;

  $query = array();

  
/****/
  
require_once("database.php");
require_once("constants.php");
require_once("functions.php");
require_once 'include/class_Party.php';
require_once 'include/class_Kampf.php';
require_once 'include/class_Krieg.php';

  $get_fleet = sql_query("SELECT f_". implode(",f_",$p_db_name_wus) .",user,city,f_id,f_arrival,f_plunder,f_spy,f_colonize,f_colonize_jobs,f_colonize_fleets,f_colonize_hangar,f_colonize_nobonus,f_target,f_iridium,f_holzium,f_water,f_oxygen,f_name,f_name_show FROM actions WHERE id='$id'");
  $fleet = sql_fetch_array($get_fleet);
  $target_city = $fleet[f_target];
  $origin_city = $fleet[city];
  
  $new_koords = split(":",$fleet[f_target]);
   
  if($new_koords[1] == "") {
  		$get_homes = sql_query("SELECT ID,user,city,alliance,points FROM city WHERE ID='$target_city' || ID='$origin_city'");
  }else{
  		$colonize_empty = "YES";
  }
  
  while ($home = sql_fetch_array($get_homes))
  {
  	if ($home[ID] == $target_city)
    {
      $target_user = $home[user];
      $target_city_points = $home[points];
      $target_user_alliance = $home[alliance];
    }
    if ($home[ID] == $origin_city)
    {
      $origin_user = $home[user];
      $origin_user_alliance = $home[alliance];
    }
  }
   
  if($target_city == "0:0:0" && $target_user == "") 
  		$fleet[f_colonize] = "NO";

  /* full fleet of attacker before shield battle */
  $angreifer2 = new Party();
  $angreifer2->Init($fleet);


  $get_user_techs_origin = sql_query("SELECT t_electronsequenzweapons,t_protonsequenzweapons,t_neutronsequenzweapons,t_plane_size,points,tech_points FROM usarios WHERE ID='$origin_user'");
  $user_techs_origin = sql_fetch_array($get_user_techs_origin);

  $angreifer2->StrengthCalc($user_techs_origin[t_electronsequenzweapons],$user_techs_origin[t_protonsequenzweapons],$user_techs_origin[t_neutronsequenzweapons],0,-1,0, 0);

/****/

  if($new_koords[1] == "") {
  	  $get_target_p_city_infos = sql_query("SELECT p_". implode(",p_",$p_db_name_wus) ." FROM city WHERE ID='$target_city'");
	  $get_target_d_city_infos = sql_query("SELECT d_". implode(",d_",$d_db_name) .",b_shield,home,c_active_shields FROM city WHERE ID='$target_city'");
	
	  $target_city_p_infos = sql_fetch_array($get_target_p_city_infos);
	  $target_city_d_infos = sql_fetch_array($get_target_d_city_infos);
  }else{
  	  $get_target_p_city_infos = sql_query("SELECT p_". implode(",p_",$p_db_name_wus) ." FROM city WHERE city='$target_city'");
	  $target_city_p_infos = sql_fetch_array($get_target_p_city_infos);
  }
  
  $verteidiger = new Party();
  $verteidiger->Init($target_city_p_infos);
  $verteidiger->InitDefense($target_city_d_infos);

  $target_shield = $target_city_d_infos[b_shield];
  $target_shield_timer = $target_city_d_infos[c_active_shields];
  $target_user_home = $target_city_d_infos[home];

  $get_user_techs_target = sql_query("SELECT t_electronsequenzweapons,t_protonsequenzweapons,t_neutronsequenzweapons,t_shield_tech,points,tech_points FROM usarios WHERE ID='$target_user'");
  $user_techs_target = sql_fetch_array($get_user_techs_target);

  $verteidiger->StrengthCalc($user_techs_target[t_electronsequenzweapons],$user_techs_target[t_protonsequenzweapons],$user_techs_target[t_neutronsequenzweapons],$user_techs_target[t_shield_tech],$target_city_points,$target_shield,$target_shield_timer);
  #$verteidiger->StrengthCalcShield($user_techs_target[t_shield_tech],$target_shield_timer);
 
/****/

  $krieg = new Krieg($origin_user_alliance);
  $krieg->handleCeasefire($target_user_alliance, $fleet[f_arrival]);
  unset($krieg);

/****/
  // For Tutorial special Koordsinteraction
  if($target_city == "0:11:5" && $fleet[f_espionage_probe] > 0)
  {
  		$select = sql_query("SELECT 1 FROM tutorial WHERE ID = '$origin_user' AND page = 'airport'");
		if(sql_num_rows($select) == 0) $insert = sql_query("INSERT INTO `tutorial` (`user` ,`page` ,`number`) VALUES ('$origin_user', 'airport', '1')");		
  }
  elseif($target_city == "0:11:5" && $fleet[f_sparrow] > 0)
  {
  		$select = sql_fetch_array(sql_query("SELECT `number` FROM tutorial WHERE ID = '$origin_user' AND page = 'airport'"));
  		if($select[number] == "2") $up = sql_query("UPDATE `tutorial` SET `number` = 3 WHERE ID = '$origin_user' AND page = 'airport'");
  }
  
  
  // Userprotect
  
  require_once 'include/userprotect.php';
  
  // Weiter gehts beim Kampf
  
  
  //XXX disable shield by define
  
  
  $shield_battle = new Kampf();
  $shield_battle->Init($angreifer2->p_fleet,$verteidiger->p_home,$verteidiger->d_home);

  if (sql_num_rows($get_target_p_city_infos) > 0)
  {
  	// shield probability
    $probability = attacker_victory_probability($angreifer2->strength, $verteidiger->strength_shield);

    // First: schield fight 
    $shield_battle->FightShield($probability);

    // calculate lost shields
    $shield_lost = 0;

    $s_res = sql_query("SELECT c_shield_timer,c_active_shields FROM city WHERE ID='$target_city'");
    list($shield_timer, $active_shields) = sql_fetch_row($s_res);

    $shield_battle->SumOffense();
    if ($shield_battle->SumOffBomber >= 1)
    {
      if ($target_shield > 0)
      {
		// 3^k bomber destroy k+1 shields
		$bomber = $shield_battle->SumOffBomber;
		$disabled_shields = floor(log($bomber,3))+1;
		$active_shields_old = $active_shields;
        $active_shields = max($active_shields-$disabled_shields, 0);
        $new_shield_timer = ShieldRegenTime($target_shield, $active_shields);
        if($shield_timer > 0) { // there are shields regenerating
            $tmp = $shield_timer - time(); // remaining recharge time
            if($new_shield_timer < $tmp) // the remaining time is greater than the new one
                $shield_timer = time() + $new_shield_timer;
        }
        else { // shield is full charged
            $shield_timer = time() + $new_shield_timer;
        }
        sql_query("UPDATE city SET c_shield_timer=".$shield_timer.", c_active_shields=".$active_shields.
            " WHERE ID='$target_city'");

        $shield_lost = min($disabled_shields,$active_shields_old);
      }
    }
	
	// normal fight probability
    $probability = attacker_victory_probability($angreifer2->strength+1, $verteidiger->strength);

    //check if users alliance is in war with target users alliance
    $in_war = FALSE;
    $krieg = new Krieg($origin_user_alliance);
    $in_war = $krieg->isOpponent($target_user_alliance);
    unset($krieg);

    $battle = new Kampf();
    $battle->Init($angreifer2->p_fleet,$verteidiger->p_home,$verteidiger->d_home);
    $battle->Fight($probability);

    // Revision 11:
    // Changed storage of reports - for details see old version
    
    // kind of report?
   	if ($fleet[f_spy] == "YES")
   		$art = "scan";
   	else
   		$art = "attack";
   	if($fleet[f_name_show] == "YES") $f_name_show = "Y";
    else $f_name_show = "N";    
    $attack_xmlid = md5(uniqid());
    $defense_xmlid = md5(uniqid());
    if($bed == 1)
    	$userprotect = "Y";
   	else 
   		$userprotect = "N";
    $test = "INSERT INTO news_ber (attack_user, defense_user, attack_bid, defense_bid, attack_city, defense_city, time, attack_seen, defense_seen, attack_seen_sitter, defense_seen_sitter, attack_delete, defense_delete, attackers_alliance, defenders_alliance, attack_xmlid, defense_xmlid, f_name_show, f_name, points, shield, art, userprotection) VALUES 
    ('$origin_user', '$target_user', MD5(CONCAT('$target_city$fleet[f_arrival]',RAND())), MD5(CONCAT('$target_city$fleet[f_arrival]',RAND())), '$origin_city', '$target_city', '$fleet[f_arrival]', 'N', 'N', 'N', 'N', 'N', 'N', '$origin_user_alliance', '$target_user_alliance', '$attack_xmlid', '$defense_xmlid', '$f_name_show', '".addslashes($fleet[f_name])."', '$target_city_points', '$target_shield', '$art', '$userprotect')";
    sql_query($test);
    $bid = sql_query("SELECT ID FROM news_ber WHERE attack_xmlid = '$attack_xmlid' AND defense_xmlid = '$defense_xmlid'");
    $bid = sql_fetch_array($bid);
    
    //////// $angreifer2 = Vorher
    //////// $battle = Kampf Flugzeuge/Defanlagen
    //////// $shield_battle = Kampf Schutzschild

    ##### Verluste Angreifer (KAMPF_FLUGZEUGE)
    for ($i=0;$i<ANZAHL_KAMPF_FLUGZEUGE;$i++)
    {
     $verlust = $battle->p_angr_lost[$i] + $shield_battle->p_angr_lost[$i];
     
     if ($angreifer2->p_home[$i]) {
        $gesamt_lost = $verlust; // Verlorene Flugzeuge gesamt (-> alle Sparrows)
        #$fleet_id = sql_query("SELECT type FROM type_plane WHERE name = '$p_name[$i]'");
        #$fleet_id = sql_fetch_array($fleet_id);
        $fleet_id = $p_id[$i];
        sql_query("INSERT INTO news_ber_ (`ID`, `type`, `ad`, `before`, `after`) VALUES ('$bid[ID]','$fleet_id', 'attack', '" . $battle->p_angr[$i] . "', '$gesamt_lost')");

        if($origin_user == $target_user) {
        }else{
        	if($gesamt_lost > 0) {
		        // Fliegerstatistik
		        // Angreifer
		        sql_query("INSERT INTO flightstats (`user`, `type`, `ad`, `1`, `2`, `3`, `4`, `5`, `6`) VALUES ('$origin_user', '$fleet_id', 'plane', '0', '$gesamt_lost', '0', '0', '0', '0') ON DUPLICATE KEY UPDATE `2` = `2`+'$gesamt_lost';");
		        
		        // Verteidiger
		        sql_query("INSERT INTO flightstats (`user`, `type`, `ad`, `1`, `2`, `3`, `4`, `5`, `6`) VALUES ('$target_user', '$fleet_id', 'plane', '0', '0', '$gesamt_lost', '0', '0', '0') ON DUPLICATE KEY UPDATE `3` = `3`+'$gesamt_lost';");
        	}
        }
     }
     
      $query[2] .= "p_$p_db_name_wus[$i]_gesamt=p_$p_db_name_wus[$i]_gesamt-$verlust,";
      $query[3] .= "f_$p_db_name_wus[$i]=f_$p_db_name_wus[$i]-$verlust,";

      $gesamt_verlust_origin += $verlust;
    }

	##### Verluste Verteidiger (KAMPF_FLUGZEUGE)
    $p_vert_lost = null;
    for ($i=0;$i<ANZAHL_KAMPF_FLUGZEUGE;$i++)
    {
      if($bed == 1) {
    		$ech = $battle->p_vert_lost[$i];
     		$verlust = ceil(  $battle->p_vert_lost[$i] * $bedingung / 100   );
      }else{
    		$verlust = $battle->p_vert_lost[$i];
   	  }
    	 
    	
    	
     $p_vert_lost[$i] = $verlust;
        
     if ($verteidiger->p_home[$i]) {
        #$fleet_id = sql_query("SELECT type FROM type_plane WHERE name = '$p_name[$i]'");
        #$fleet_id = sql_fetch_array($fleet_id);
        $fleet_id = $p_id[$i];
        sql_query("INSERT INTO news_ber_ (`ID`, `type`, `ad`, `before`, `after`) VALUES ('$bid[ID]','$fleet_id', 'defense', '" . $battle->p_vert[$i] . "', '$p_vert_lost[$i]')");

        if($origin_user == $target_user) {
        }else{
        	if($p_vert_lost[$i] > 0) {
        		// Fliegerstatistik
		        // Angreifer
		        sql_query("INSERT INTO flightstats (`user`, `type`, `ad`, `1`, `2`, `3`, `4`, `5`, `6`) VALUES ('$origin_user', '$fleet_id', 'plane', '$p_vert_lost[$i]', '0', '0', '0', '0', '0') ON DUPLICATE KEY UPDATE `1` = `1`+'$p_vert_lost[$i]';");
		        
		        // Verteidiger
		        sql_query("INSERT INTO flightstats (`user`, `type`, `ad`, `1`, `2`, `3`, `4`, `5`, `6`) VALUES ('$target_user', '$fleet_id', 'plane', '0', '0', '0', '$p_vert_lost[$i]', '0', '0') ON DUPLICATE KEY UPDATE `4` = `4`+'$p_vert_lost[$i]';");
		        
        	}
        }
     }
    
      $query[0][] = "p_$p_db_name_wus[$i]=p_$p_db_name_wus[$i]-{$p_vert_lost[$i]},p_$p_db_name_wus[$i]_gesamt=p_$p_db_name_wus[$i]_gesamt-{$p_vert_lost[$i]}";
      $query[4] .= "&p_def[$i]=". ($verteidiger->p_home[$i] - $p_vert_lost[$i]);

      $gesamt_verlust_target += $p_vert_lost[$i];
    }

    ##### Verluste Verteidiger (VERTEIDIGUNGSANLAGEN)
    $d_vert_lost = null;
    for ($i=0;$i<ANZAHL_DEFENSIVE;$i++)
    {
    echo "BEDINGUNG::::::: $bed<br><br>";
      if($bed == 1) {
    		$verlust = ceil(  $battle->d_vert_lost[$i] * $bedingung / 100   );
      }else{
    		$verlust = $battle->d_vert_lost[$i];
   	  }
        $d_vert_lost[$i] = $verlust;
        
      if ($verteidiger->d_home[$i]) {
      	#$fleet_id = sql_query("SELECT type FROM type_plane WHERE name = '$d_name[$i]'");
        #$fleet_id = sql_fetch_array($fleet_id);
        $fleet_id = $d_id[$i];
        sql_query("INSERT INTO news_ber_ (`ID`, `type`, `ad`, `before`, `after`) VALUES ('$bid[ID]','$fleet_id', 'defense', '" . $battle->d_vert[$i] . "', '$d_vert_lost[$i]')");
      }

      $query[1][] = "d_$d_db_name[$i]=d_$d_db_name[$i]-{$d_vert_lost[$i]}";
      $query[4] .= "&d_def[$i]=". ($verteidiger->d_home[$i] - $d_vert_lost[$i]);
      
      	if($origin_user != $target_user) {
      		if($d_vert_lost[$i] > 0) {
    			// Fliegerstatistik
		        // Angreifer
		        sql_query("INSERT INTO flightstats (`user`, `type`, `ad`, `1`, `2`, `3`, `4`, `5`, `6`) VALUES ('$origin_user', '$fleet_id', 'plane', '$d_vert_lost[$i]', '0', '0', '0', '0', '0') ON DUPLICATE KEY UPDATE `1` = `1`+'$d_vert_lost[$i]';");
		        
		        // Verteidiger
		        sql_query("INSERT INTO flightstats (`user`, `type`, `ad`, `1`, `2`, `3`, `4`, `5`, `6`) VALUES ('$target_user', '$fleet_id', 'plane', '0', '0', '0', '$d_vert_lost[$i]', '0', '0') ON DUPLICATE KEY UPDATE `4` = `4`+'$d_vert_lost[$i]';");
    		} 
      	}
    }
    
    sql_query("UPDATE city SET ". implode(",",$query[1]) .",". implode(",",$query[0]) .",p_gesamt_flugzeuge=p_gesamt_flugzeuge-$gesamt_verlust_target,blubb=blubb-$gesamt_verlust_target WHERE ID='$target_city'");

    sql_query("UPDATE city SET $query[2] p_gesamt_flugzeuge=p_gesamt_flugzeuge-$gesamt_verlust_origin,blubb=blubb-$gesamt_verlust_origin WHERE ID='$origin_city'");
    sql_query("UPDATE actions SET $query[3] f_flugzeuge_anzahl=f_flugzeuge_anzahl-$gesamt_verlust_origin WHERE f_id='$fleet[f_id]'");

    $target_depot = new Lager($target_city);

    $battle->SumOffense();
    $battle->SumDefense();

    if ($battle->SumOffPlanes == 0)
    {
      sql_query("DELETE FROM actions WHERE f_id='$fleet[f_id]'");
    }
    
    //Medaillen für Zerstörte Einheiten
    $m_aktuell = sql_fetch_array(sql_query("SELECT m_attack FROM medals WHERE user=$origin_user"));
    if ($m_aktuell[m_attack] < count($medal_values[$medaillen[ATTACK]])) { //Schon Endstufe erreicht?
      $destroyed = sql_query("SELECT flightstats.1 AS plane,type FROM flightstats WHERE user=$origin_user AND ad='plane'");
      $sum_destroyed = 0;
      while ($des = sql_fetch_array($destroyed)) {
        if($des[type] != ESPIONAGE_PROBE) {
          $sum_destroyed += $des[plane];
        }
      }
      if($sum_destroyed >= $medal_values[$medaillen[ATTACK]][$m_aktuell[m_attack]]) {
        $m_aktuell[m_attack]++;
        sql_query("UPDATE medals SET m_attack=m_attack+1,d_attack='$fleet[f_arrival]' WHERE user='$origin_user'");
        $username = sql_fetch_array(sql_query("SELECT user FROM usarios WHERE ID='$origin_user'"));
        sql_query("INSERT INTO news_igm_umid (sender,recipient,owner,time,topic,text,dir) VALUES ('ETS','$origin_user','$origin_user','$fleet[f_arrival]','".MEDAL_TOPIC."','".MEDAL_HALLO.$username[user].MEDAL_TEXT.$medal_text[$medaillen[ATTACK]].$m_aktuell[m_attack]."','0')" );
        $time_new = time();
        sql_query("INSERT INTO global_logs (seite, inhalt, datum) VALUES ('fight.php', '[Attackmedaille erhalten] ::::: Spieler $fleet[user] hat eine Medaille für Zerstörte Einheiten erhalten', '$time_new');");
      }
    }
    
    //Medaille für zerstörte Einheiten bei Verteidigung
    $m_aktuell = sql_fetch_array(sql_query("SELECT m_defence2 FROM medals WHERE user=$target_user"));
    if ($m_aktuell[m_defence2] < count($medal_values[$medaillen[DEFENCE2]])) { //Schon Endstufe erreicht?
      $destroyed = sql_query("SELECT flightstats.3 AS plane,type FROM flightstats WHERE user=$target_user AND ad='plane'");
      $sum_destroyed = 0;
      while ($des = sql_fetch_array($destroyed)) {
        if($des[type] != ESPIONAGE_PROBE) {
          $sum_destroyed += $des[plane];
        }
      }
      if($sum_destroyed >= $medal_values[$medaillen[DEFENCE2]][$m_aktuell[m_defence2]]) {
        $m_aktuell[m_defence2]++;
        sql_query("UPDATE medals SET m_defence2=m_defence2+1,d_defence2='$fleet[f_arrival]' WHERE user='$target_user'");
        $username = sql_fetch_array(sql_query("SELECT user FROM usarios WHERE ID='$target_user'"));
        sql_query("INSERT INTO news_igm_umid (sender,recipient,owner,time,topic,text,dir) VALUES ('ETS','$target_user','$target_user','$fleet[f_arrival]','".MEDAL_TOPIC."','".MEDAL_HALLO.$username[user].MEDAL_TEXT.$medal_text[$medaillen[DEFENCE2]].$m_aktuell[m_defence2]."','0')" );
        $time_new = time();
        sql_query("INSERT INTO global_logs (seite, inhalt, datum) VALUES ('fight.php', '[Verteidiger2medaille erhalten] ::::: Spieler $fleet[user] hat eine Medaille für Zerstörte Einheiten bei Verteidigung erhalten', '$time_new');");
      }
    }
    
    
    

// Urlaubsmodus bei längerer Abwesenheit // ETS 11
  $get_target_p_city_infos = sql_query("SELECT p_". implode(",p_",$p_db_name_wus) ." FROM city WHERE ID='$target_city'");
  $get_target_d_city_infos = sql_query("SELECT d_". implode(",d_",$d_db_name) .",b_shield,home,c_active_shields FROM city WHERE ID='$target_city'");

  $target_city_p_infos = sql_fetch_array($get_target_p_city_infos);
  $target_city_d_infos = sql_fetch_array($get_target_d_city_infos);
    
  $verteidiger2 = new Party();
  $verteidiger2->Init($target_city_p_infos);
  $verteidiger2->InitDefense($target_city_d_infos);
    
    $verteidiger2->StrengthCalc($user_techs_target[t_electronsequenzweapons],$user_techs_target[t_protonsequenzweapons],$user_techs_target[t_neutronsequenzweapons],$user_techs_target[t_shield_tech],$target_city_points,$target_shield,$target_shield_timer);
  
    if($verteidiger2->strength < ($verteidiger->strength/10) || $verteidiger->strength == 0)
    {
    	$select = sql_query("SELECT sitter FROM usarios WHERE ID = '$target_user'");
    	$selecting = sql_fetch_array($select);
    	$time = time() - 86400*2;
    	if($selecting['sitter'] == "") {
    		$select = "SELECT `login` FROM usarios WHERE `ID` = '$target_user' AND `login` < '$time'";	
    	}else{
    		$select = "SELECT `login` FROM usarios WHERE (`ID` = '$target_user' OR `sitter` = '$selecting[sitter]') AND `login` < '$time'";
    	}
    	$select = sql_query($select);
    	if(sql_affected_rows($select))
    	{
    		sql_query("INSERT INTO holiday (`user`, `time`, `art`) VALUES ('$target_user', '" . (time()+72*3600) . "', '2') ON DUPLICATE KEY UPDATE `user` = '$target_user';");
    	}
    }
    		
// ENDE
// Urlaubsmodus bei längerer Abesenheit // ETS 11
/*
	// Asteroids Recalc Defense
	if($target_city == "0:0:0") {
		
		$select = sql_query("SELECT `started` FROM asteroids WHERE `started`='started'");
		$select = sql_fetch_array($select);
		if($select['started'] == 'started') {
			$fleet[f_colonize] = "NO";
			$update = "UPDATE asteroids SET `real_fleets`=`real_fleets`+1, `kw2` = '" . $verteidiger2->strength . "' WHERE `started` = 'started'";
			sql_query($update);
			if($verteidiger2->strength == 0) {
				$select = "SELECT news_ber.attack_user, type_plane.costs1 * news_ber_.after AS costs1, type_plane.costs2 * news_ber_.after AS costs2, news_ber.ID, type_plane.name, news_ber_.before, news_ber_.after FROM news_ber INNER JOIN news_ber_ ON news_ber.ID = news_ber_.ID INNER JOIN type_plane ON news_ber_.type = type_plane.type WHERE news_ber.defense_city = '0:0:0' AND news_ber_.ad = 'attack' GROUP BY `news_ber`.`attack_city`, `type_plane`.`name`";
				$select = sql_query($select);
				while($row = sql_fetch_array($select)) {
					if($row['attack_user'] == $origin_user) {
						$x = 3;
						sql_query("UPDATE city SET b_work_board = b_work_board+2 WHERE user='$origin_user' AND home='YES'");
						$select = "SELECT jobs_build.level, jobs_build.city FROM jobs_build INNER JOIN city ON jobs_build.city = city.city WHERE city.user='$origin_user' AND home='YES'";
						$select = sql_query($select);
						while($dings = sql_fetch_array($select)) {
							$dings['level'] = $dings['level']+2;
							$text = "Bauzentrum Ausbaustufe " . $dings['level'] . " wurde auf $dings[city] fertiggestellt";
							sql_query("UPDATE jobs_build INNER JOIN city ON jobs_build.city = city.city SET jobs_build.level='" . $dings['level'] . "', jobs_build.msg='$text' WHERE city.user='$origin_user' AND home='YES' AND level='" . $level['dings'] . "'");	
						}
					}else{
						$x = 2;
					}
					if($row['name'] == "Scarecrow" || $row['name'] == "Settler") 
						$x = 1;
					
					$update = "UPDATE city SET city.r_iridium = city.r_iridium+" . $row['costs1'] . "*$x, city.r_holzium = city.r_holzium+" . $row['costs2'] . "*$x WHERE home='YES' and user='". $row['attack_user'] ."'";
					sql_query($update);					
				}
				$fame_user = "UPDATE usarios SET fame=fame+250 WHERE user='$origin_user'";
				sql_query($fame_user);
				
				$select = sql_query("SELECT alliance FROM usarions WHERE user='$origin_user'");
				$select = sql_fetch_array($select);
				$chronik = sql_query("INSERT INTO chronicle (occasion, causer, time) VALUES ('asteroid', '$origin_user ($select[alliance])', '" . time() . "'");
					
				$fame_alliance = "UPDATE alliances INNER JOIN usarios ON alliances.tag=usarios.alliance SET alliances.fame=alliances.fame+500 WHERE usarios.user = '$origin_user'";
				sql_query($fame_alliance);
					
				$end = "UPDATE asteroids SET started = 'ended' WHERE `started`= 'started' AND kw2='0'";
				sql_query($end);
					
				$delete = sql_query("DELETE FROM `city` WHERE `user` = 'Asteroid'");
				$delete = sql_query("DELETE FROM `usarios` WHERE `user` = 'Asteroid'");
			}
		}
		// Artefakte
		$select2 = sql_query("SELECT `started`, `koth`, `last_take` FROM artefakte WHERE `started`='started'");
		$select2 = sql_fetch_array($select);
		if($select2['started'] == 'started') {
			$update = "UPDATE artefakte SET `real_fleets`=`real_fleets`+1, `kw2` = '" . $verteidiger2->strength . "' WHERE `started` = 'started'";
			sql_query($update);
			if($verteidiger2->strength == 0) {
				if($select2['last_take'] == "") {
					$select = "SELECT news_ber.attack_user, type_plane.costs1 * news_ber_.after AS costs1, type_plane.costs2 * news_ber_.after AS costs2, news_ber.ID, type_plane.name, news_ber_.before, news_ber_.after FROM news_ber INNER JOIN news_ber_ ON news_ber.ID = news_ber_.ID INNER JOIN type_plane ON news_ber_.type = type_plane.type WHERE news_ber.defense_city = '0:0:0' AND news_ber_.ad = 'attack' GROUP BY `news_ber`.`attack_city`, `type_plane`.`name`";
					$select = sql_query($select);
					while($row = sql_fetch_array($select)) {
						if($row['attack_user'] == $origin_user) {
							$x = 5;
							sql_query("UPDATE city SET b_work_board = b_work_board+5 WHERE user='$origin_user' AND home='YES'");
							$select = "SELECT jobs_build.level, jobs_build.city FROM jobs_build INNER JOIN city ON jobs_build.city = city.city WHERE city.user='$origin_user' AND home='YES'";
							$select = sql_query($select);
							while($dings = sql_fetch_array($select)) {
								$dings['level'] = $dings['level']+5;
								$text = "Bauzentrum Ausbaustufe " . $dings['level'] . " wurde auf $dings[city] fertiggestellt";
								sql_query("UPDATE jobs_build INNER JOIN city ON jobs_build.city = city.city SET jobs_build.level='" . $dings['level'] . "', jobs_build.msg='$text' WHERE city.user='$origin_user' AND home='YES' AND level='" . $level['dings'] . "'");	
							}
						}else{
							$x = 2;
						}
						if($row['name'] == "Scarecrow" || $row['name'] == "Settler") 
							$x = 1;
						
						$update = "UPDATE city SET city.r_iridium = city.r_iridium+" . $row['costs1'] . "*$x, city.r_holzium = city.r_holzium+" . $row['costs2'] . "*$x WHERE home='YES' and user='". $row['attack_user'] ."'";
						sql_query($update);					
					}
					$fame_user = "UPDATE usarios SET fame=fame+250 WHERE user='$origin_user'";
					sql_query($fame_user);
					
					$fame_alliance = "UPDATE alliances INNER JOIN usarios ON alliances.tag=usarios.alliance SET alliances.fame=alliances.fame+500 WHERE usarios.user = '$origin_user'";
					sql_query($fame_alliance);
						
					if($sleect2['koth'] == "N") {	
							
						$select = sql_query("SELECT alliance FROM usarions WHERE user='$origin_user'");
						$select = sql_fetch_array($select);
						$chronik = sql_query("INSERT INTO chronicle (occasion, causer, time) VALUES ('artefakt', '$origin_user ($select[alliance])', '" . time() . "'");
				
						$end = "UPDATE artefakte SET started = 'ended' WHERE `started`= 'started' AND kw2='0'";
						sql_query($end);
						$delete = sql_query("DELETE FROM `city` WHERE `user` = 'Artefakt'");
						$delete = sql_query("DELETE FROM `usarios` WHERE `user` = 'Artefakt'");
					}else{
						$end = "UPDATE artefakte SET started = 'koth', last_take = '". time()."' WHERE `started`='started' AND kw2='0'";
						sql_query($end);
					}
				}else{
					$time = time() - $select2['last_take'];
					$last = "INSERT INTO artefakte_ (user, time) VALUES ('$origin_user', '$time') ON DUPLICATE KEY `time` = `time`+'$time'";
					sql_query($last);
					$end = "UPDATE artefakte SET last_take = '". time()."' WHERE `started`='koth'";
					sql_query($end);
				}
			}
		}
	}	
*/
    ##### SCAN
    if ($fleet[f_spy] == "YES" && $angreifer2->p_fleet[ESPIONAGE_PROBE] >= 1)
    	sql_query("UPDATE news_ber SET plunder='Y', iridium=". round($target_depot->getIridium()) .", holzium=". round($target_depot->getHolzium()) .", water=". round($target_depot->getWater()) .", oxygen=". round($target_depot->getOxygen()) . " WHERE ID='$bid[ID]'");
      
    $get_anzahl_user_colonies = sql_query("SELECT Count(*) FROM city WHERE user='$origin_user' && home!='YES'");
    $anzahl_user_colonies = sql_fetch_array($get_anzahl_user_colonies);

    $get_comm_center = sql_query("SELECT b_communication_center FROM city WHERE ID='$origin_city'");
    $comm_center = sql_fetch_array($get_comm_center);

    if ($fleet[f_colonize] == "YES" && $anzahl_user_colonies[0] < numberOfColonies($comm_center[b_communication_center]) && $battle->SumOffScarecrow >= 1 && $battle->SumDefPlanes == 0 && $battle->SumDefDef == 0 && $target_user_home != "YES")
    { // Kolo erobert !
        // Wechsel in Stadthistorie vermerken
      $target_coordinates = sql_fetch_array(sql_query("SELECT city FROM city WHERE ID='$target_city'"));
      sql_query("INSERT INTO city_history (city, owner, time, user) VALUES ('$target_coordinates[city]','$origin_user',".microtime(true).",'$origin_user')");

      // Neue Allianz setzen
      list( $origin_alliance ) = sql_fetch_row( sql_query("SELECT alliance FROM usarios WHERE ID='$origin_user'") );
      sql_query("UPDATE city SET user='$origin_user',alliance='$origin_alliance' WHERE ID='$target_city'");


      // Punkte updaten + Nutzer Logout
      sql_query("UPDATE usarios SET points=points-$target_city_points,login='". (time() - 86400) ."' WHERE ID='$target_user'");
      sql_query("UPDATE userdata SET ip='123',user_agent='123' WHERE ID='$target_user'");
      sql_query("UPDATE usarios SET points=points+$target_city_points WHERE ID='$origin_user'");

      // Sitter Logout
      $get_sitter = sql_query("SELECT sitter FROM usarios WHERE ID='$target_user'");
      $sitter = sql_fetch_array($get_sitter);
      sql_query("UPDATE userdata SET ip='123',user_agent='123' WHERE ID='$sitter[sitter]'");

      // Hangarblockade
      if ($fleet[f_colonize_jobs] == "YES") // Alle Hangar-Bauauftrï¿½ge bei <i>erfolgreicher</i> Eroberung abbrechen
      {
        $get_job_sum = sql_query("SELECT current_build,Count(*) AS anzahl FROM jobs_planes WHERE city='$target_city' GROUP BY current_build");
        while($job_sum = sql_fetch_array($get_job_sum))
        {
          $job_sum_array[] = "$job_sum[current_build]_gesamt=$job_sum[current_build]_gesamt-$job_sum[anzahl]";
          $job_sum_complete += $job_sum[anzahl];
        }
        if (count($job_sum_array))
        {
          sql_query("DELETE FROM jobs_planes WHERE city='$target_city'");
          sql_query("UPDATE city SET ". implode(",",$job_sum_array) .",p_gesamt_flugzeuge=p_gesamt_flugzeuge-$job_sum_complete WHERE ID='$target_city'");
          sql_query("UPDATE city SET blubb=blubb-$job_sum_complete WHERE ID='$target_city'");
        }
      }

      if ($fleet[f_colonize_fleets] == "YES") // Alle Flotten bei <i>erfolgreicher</i> Eroberung abstï¿½rzen lassen
      {
        $fleet_sum_query = "";

        for ($i=0;$i<ANZAHL_FLUGZEUGE;$i++)
          $query[5][] = "SUM(f_$p_db_name_wus[$i]) AS $p_db_name_wus[$i]";

        $get_fleet_sum = sql_query("SELECT ". implode(",",$query[5]) .",SUM(f_flugzeuge_anzahl) AS flugzeuge_anzahl FROM actions ".
                "WHERE ID='$target_city' && (f_action LIKE '%_back' || f_action LIKE '%_from_depot' || f_action LIKE 'plane_%')");
        $fleet_sum_array = sql_fetch_array($get_fleet_sum);

        for ($i=0;$i<ANZAHL_FLUGZEUGE;$i++)
          $fleet_sum_query[] = "p_$p_db_name_wus[$i]_gesamt=p_$p_db_name_wus[$i]_gesamt-". ((int)($fleet_sum_array[$p_db_name_wus[$i]]));

        sql_query("UPDATE city SET ". implode(",",$fleet_sum_query) .",p_gesamt_flugzeuge=p_gesamt_flugzeuge-". ((int)$fleet_sum_array[flugzeuge_anzahl]).
                " WHERE ID='$target_city'");
        sql_query("UPDATE city SET blubb=blubb-". ((int)$fleet_sum_array[flugzeuge_anzahl])." WHERE ID='$target_city'");
        sql_query("DELETE FROM actions WHERE city='$target_city'");
      }

      if ($fleet[f_colonize_hangar] == "YES") // Alle Transport-Flugzeuge im Hangar bei <i>erfolgreicher</i> Eroberung eliminieren
      {
        $fleet_sum_query = "";

        for ($i=SMALL_TRANSPORTER;$i<=BIG_TRANSPORTER;$i++)
        {
          $query[6][] = "p_$p_db_name_wus[$i]";
          $fleet_sum_query[] = "p_$p_db_name_wus[$i]_gesamt=p_$p_db_name_wus[$i]_gesamt-p_$p_db_name_wus[$i],p_$p_db_name_wus[$i]=0";
        }

        sql_query("UPDATE city SET p_gesamt_flugzeuge=p_gesamt_flugzeuge-(". implode("+",$query[6]) .") WHERE ID='$target_city'");
        sql_query("UPDATE city SET blubb=blubb-(". implode("+",$query[6]) .") WHERE ID='$target_city'");
        sql_query("UPDATE city SET ". implode(",",$fleet_sum_query) ." WHERE ID='$target_city'");
      }


      // Besitzer aktualisieren
      sql_query("UPDATE actions SET user='$origin_user' WHERE city='$target_city'");
      sql_query("UPDATE actions SET f_target_user='$origin_user' WHERE f_target='$target_city'");
      sql_query("UPDATE jobs_planes SET user='$origin_user' WHERE city='$target_city'");
      sql_query("UPDATE jobs_defense SET user='$origin_user' WHERE city='$target_city'");
// TODO: ï¿½berprï¿½fen, wo diese Queries hin mï¿½ssen.
//      sql_query("UPDATE news_er SET user='$origin_user' WHERE city='$target_city'");
//      sql_query("UPDATE news_ber SET user='$origin_user' WHERE city='$target_city'");

      // Die Position der Stadt in der Sortierliste neu setzen (ans Ende)
      $city_pos_res = sql_query("SELECT MAX(pos) AS mpos FROM city WHERE user='$origin_user'");
      $mpos = sql_fetch_array($city_pos_res);
      sql_query("UPDATE city SET pos=".($mpos[mpos]+1)." WHERE ID='$target_city'");

      // Technologien aktualisieren
      sql_query("UPDATE city RIGHT JOIN usarios ON city.user=usarios.ID SET city.t_depot_management=usarios.t_depot_management,city.t_water_compression=usarios.t_water_compression,city.t_mining=usarios.t_mining WHERE city.ID='$target_city'");
/*
      // Laufende Technologien auf dieser Kolo abbrechen
      $get_technologies = sql_query("SELECT t_". implode(",t_",$t_db_name) .",t_current_build,t_end_time FROM usarios WHERE user='$target_user' AND t_start_city='$target_city'");
      if(sql_num_rows($get_technologies)) {
          $user_techs = sql_fetch_array($get_technologies);
          $cancel_tech = array_search( substr($user_techs[t_current_build], 2), $t_db_name);
          $pay_holzium = price($t_holzium[$cancel_tech],$user_techs[$cancel_tech],$t_pricing_holzium[$cancel_tech]);
          $pay_oxygen = price($t_oxygen[$cancel_tech],$user_techs[$cancel_tech],$t_pricing_oxygen[$cancel_tech]);
          $duration = duration($t_duration[$cancel_tech],$user_techs[$cancel_tech],$buildings[TECH_CENTER],$cancel_tech,$user_techs);
          $reduce_factor = min( ($user_techs[t_end_time] - time()) / $duration, 0.8 );

          sql_query("UPDATE usarios SET t_end_time=0,t_current_build='',msg='',t_start_city='',t_end_time_next=0,t_next_build='',msg_next='',t_start_city_next='' WHERE user='$target_user'");
          sql_query("INSERT INTO news_er (city,time,topic) SELECT city.city, ".time().", 'Eine Technologieforschung wurde durch Kolonieverlust abgebrochen' FROM city RIGHT JOIN usarios ON usarios.user=city.user WHERE city.home = 'YES' && usarios.user='$target_user'");

          $target_depot->addHolzium($pay_holzium * $reduce_factor);
          $target_depot->addOxygen($pay_oxygen * $reduce_factor);
      }

      // Vorgemerkte Technologien auf dieser Kolo abbrechen
      sql_query("UPDATE usarios SET t_next_build='',t_end_time_next=0,msg_next='',t_start_city_next='' WHERE user='$target_user' AND t_start_city_next='$target_city'");
 */
	   $get_tech_jobs = sql_query("SELECT start_city, current_build, MIN(end_time) AS end_time FROM jobs_tech WHERE user ='$target_user' AND start_city='$target_city' HAVING (NOT MIN(end_time) IS NULL)");

      if(sql_num_rows($get_tech_jobs)) {
		$tech_jobs = sql_fetch_array($get_tech_jobs);
        //$user_techs = sql_fetch_array($get_technologies);
          //$cancel_tech = $tech_jobs[0][1];
          $end_time = $tech_jobs['end_time'];
        //$pay_holzium = price($t_holzium[$cancel_tech],$user_techs[$cancel_tech],$t_pricing_holzium[$cancel_tech]);
          //$pay_oxygen = price($t_oxygen[$cancel_tech],$user_techs[$cancel_tech],$t_pricing_oxygen[$cancel_tech]);
          //$duration = duration($t_duration[$cancel_tech],$user_techs[$cancel_tech],$buildings[TECH_CENTER],$cancel_tech,$user_techs);
         // $reduce_factor = min( ($end_time - time()) / $duration, 0.8 );
 // Es werden doch ka<<ÄÖÜPeine Ressourcen gutgeschrieben
          sql_query("INSERT INTO news_er (city,time,topic) SELECT city.ID, ".time().", 'Eine Technologieforschung wurde durch Kolonieverlust abgebrochen' FROM city RIGHT JOIN usarios ON usarios.user=city.user WHERE city.home = 'YES' && usarios.user='$target_user'");
	      sql_query("DELETE FROM jobs_tech WHERE end_time >= $end_time AND user='$target_user'");
		}


      // Lagerkapazität anpassen
      $new_tech = sql_query("SELECT t_depot_management FROM city WHERE ID='$target_city'");
      list($new_tech) = sql_fetch_row($new_tech);
      $target_depot->recalcCapacity(null, null, $new_tech);

      // Flotte
      if ($battle->SumOffPlanes > 1)
        sql_query("UPDATE actions SET f_scarecrow=f_scarecrow-1,f_flugzeuge_anzahl=f_flugzeuge_anzahl-1 WHERE f_id='$fleet[f_id]'");
      else
        sql_query("DELETE FROM actions WHERE f_id='$fleet[f_id]'");
      sql_query("UPDATE city SET p_scarecrow_gesamt=p_scarecrow_gesamt-1,p_gesamt_flugzeuge=p_gesamt_flugzeuge-1 WHERE ID='$origin_city'");
      sql_query("UPDATE city SET blubb=blubb-1 WHERE ID='$origin_city'");
      sql_query("DELETE FROM attack_denies WHERE user='$origin_user' && city='$target_city'");
      sql_query("UPDATE news_ber SET colonize='Y' WHERE ID='$bid[ID]'");
      
      //Medaille für eroberte Kolonien.
      if ($origin_user_alliance == "") {
	sql_query("UPDATE medals SET n_scare=n_scare+1 WHERE user='$fleet[user]'");
	$medals = sql_fetch_array(sql_query("SELECT n_scare FROM medals WHERE user='$fleet[user]'"));
	if (in_array($medals[n_scare], $medal_values[$medaillen[SCARE]])) {
		$time_new = time();
      		sql_query("INSERT INTO global_logs (seite, inhalt, datum) VALUES ('fight.php', '[Scaremedaille erhalten] ::::: Spieler $fleet[user] hat eine Medaille für Kologründung erhalten', '$time_new');");
		$stufe = array_search($medals[n_scare], $medal_values[$medaillen[SCARE]])+1;
		$username = sql_fetch_array(sql_query("SELECT user FROM usarios WHERE ID='$fleet[user]'"));
		sql_query("UPDATE medals SET m_kolo='".$stufe."',d_scare='$fleet[f_arrival]' WHERE user='$fleet[user]'");
		sql_query("INSERT INTO news_igm_umid (sender,recipient,owner,time,topic,text,dir) VALUES ('ETS','$fleet[user]','$fleet[user]','$fleet[f_arrival]','".MEDAL_TOPIC."','".MEDAL_HALLO.$username[user].MEDAL_TEXT.$medal_text[$medaillen[SCARE]].$stufe."','0')" );
	}
      } else if ($origin_user_alliance != $target_user_alliance) {
	$alliance_members = sql_query("SELECT ID FROM usarios WHERE alliance='$origin_user_alliance'");
	while ($member = sql_fetch_array($alliance_members)) {
		sql_query("UPDATE medals SET n_scare=n_scare+1 WHERE user='$member[ID]'");
		$medals = sql_fetch_array(sql_query("SELECT n_scare FROM medals WHERE user='$member[ID]'"));
		if (in_array($medals[n_scare], $medal_values[$medaillen[SCARE]])) {
			$time_new = time();
			sql_query("INSERT INTO global_logs (seite, inhalt, datum) VALUES ('fight.php', '[Scaremedaille erhalten] ::::: Spieler $member[ID] hat eine Medaille für Kologründung erhalten', '$time_new');");
			$stufe = array_search($medals[n_scare], $medal_values[$medaillen[SCARE]])+1;
			$username = sql_fetch_array(sql_query("SELECT user FROM usarios WHERE ID='$member[ID]'"));
			sql_query("UPDATE medals SET m_scare='".$stufe."',d_scare='$fleet[f_arrival]' WHERE user='$member[ID]'");
			sql_query("INSERT INTO news_igm_umid (sender,recipient,owner,time,topic,text,dir) VALUES ('ETS','$member[ID]','$member[ID]','$fleet[f_arrival]','".MEDAL_TOPIC."','".MEDAL_HALLO.$username[user].MEDAL_TEXT.$medal_text[$medaillen[SCARE]].$stufe."','0')" );
		}
	}
      }
      

    } // Ende Kolo-Eroberung

    
    // Plünderung
    if ($fleet[f_plunder] == "YES" && $battle->SumOffPlanes > $battle->SumOffEspionageProbe && $battle->SumOffPlanes != 0)
    {
    	// Nochmals Usertechs laden denn irgendwie ist die Variable hier nicht mehr gesetzt !!!!!! Warum ka.
    
    	$get_user_techs_origin = sql_query("SELECT t_electronsequenzweapons,t_protonsequenzweapons,t_neutronsequenzweapons,t_plane_size,points,tech_points FROM usarios WHERE ID='$origin_user'");
    	$user_techs_origin = sql_fetch_array($get_user_techs_origin);
    	
      $battle->CalcCapacity($user_techs_origin[t_plane_size]);

      $plunder = array($fleet[f_iridium], $fleet[f_holzium], $fleet[f_water], $fleet[f_oxygen]);
      $plunder1 = $plunder2 = $plunder3 = $plunder4 = 0;

      $get_depot = sql_query("SELECT b_depot FROM city WHERE ID='$target_city'");
      list($depot) = sql_fetch_row($get_depot);
      $next_depot_iri = (int) round( Price($b_iridium[DEPOT], $depot+1, $b_pricing_iridium[DEPOT]) );
      $next_depot_hol = (int) round( Price($b_holzium[DEPOT], $depot+1, $b_pricing_holzium[DEPOT]) );

      foreach($plunder as $ptmp) {
          switch($ptmp) {
              case 'iridium':
                  $plunder1 = round(min($battle->capacity, max($target_depot->getIridium()-$next_depot_iri,0)));
                  $target_depot->removeIridium($plunder1);
                  $battle->capacity -= $plunder1;
                  break;
              case 'holzium':
                  $plunder2 = round(min($battle->capacity, max($target_depot->getHolzium()-$next_depot_hol,0)));
                  $target_depot->removeHolzium($plunder2);
                  $battle->capacity -= $plunder2;
                  break;
              case 'water':
                  $plunder3 = round(min($battle->capacity, $target_depot->getWater()));
                  $target_depot->removeWater($plunder3);
                  $battle->capacity -= $plunder3;
                  break;
              case 'oxygen':
                  $plunder4 = round(min($battle->capacity, $target_depot->getOxygen()));
                  $target_depot->removeOxygen($plunder4);
                  $battle->capacity -= $plunder4;
                  break;
          }
      }

      sql_query("UPDATE actions SET f_iridium=$plunder1,f_holzium=$plunder2,f_water=$plunder3,f_oxygen=$plunder4 WHERE f_id='$fleet[f_id]'");
	  sql_query("UPDATE news_ber SET plunder='Y',iridium=$plunder1,holzium=$plunder2,water=$plunder3,oxygen=$plunder4 WHERE ID='$bid[ID]'");
	  $doit = "INSERT INTO flightstats (`user`, `type`, `ad`, `1`, `2`, `3`, `4`, `5`, `6`) VALUES ('$origin_user', '0', 'raid_out', '$plunder1', '$plunder2', '$plunder3', '$plunder4', '0', '0') ON DUPLICATE KEY UPDATE `1` = `1`+'$plunder1', `2` = `2`+'$plunder2', `3`=`3`+'$plunder3', `4`=`4`+'$plunder4';";
	  	sql_query($doit);
	  $doit = "INSERT INTO flightstats (`user`, `type`, `ad`, `1`, `2`, `3`, `4`, `5`, `6`) VALUES ('$target_user', '0', 'raid_in', '$plunder1', '$plunder2', '$plunder3', '$plunder4', '0', '0') ON DUPLICATE KEY UPDATE `1` = `1`+'$plunder1', `2` = `2`+'$plunder2', `3`=`3`+'$plunder3', `4`=`4`+'$plunder4';";
        sql_query($doit);
        
        //Medaillen
        $m_aktuell = sql_fetch_array(sql_query("SELECT m_plunder FROM medals WHERE user='$origin_user'"));
        if ($m_aktuell[m_plunder] < count($medal_values[$medaillen[PLUNDER]])) { //Schon Endstufe erreicht?
		//Hier sollte ja der User bereits in der DB drin sein.
		$plundern = sql_fetch_array(sql_query("SELECT  flightstats.1 as iri, flightstats.2 as holzi, flightstats.3 as water, flightstats.4 as oxygen FROM flightstats WHERE user='$origin_user' AND ad='raid_out'"));
		$sum_plunder = intval($plundern[iri])+intval($plundern[holzi])+intval($plundern[water])+intval($plundern[oxygen]);
			if($sum_plunder >= $medal_values[$medaillen[PLUNDER]][$m_aktuell[m_plunder]]) {
				$m_aktuell[m_plunder]++;
				sql_query("UPDATE medals SET m_plunder=m_plunder+1,d_plunder='$fleet[f_arrival]' WHERE user='$origin_user'");
				$username = sql_fetch_array(sql_query("SELECT user FROM usarios WHERE ID='$origin_user'"));
				sql_query("INSERT INTO news_igm_umid (sender,recipient,owner,time,topic,text,dir) VALUES ('ETS','$origin_user','$origin_user','$fleet[f_arrival]','".MEDAL_TOPIC."','".MEDAL_HALLO.$username[user].MEDAL_TEXT.$medal_text[$medaillen[PLUNDER]].$m_aktuell[m_plunder]."','0')" );
				$time_new = time();
				sql_query("INSERT INTO global_logs (seite, inhalt, datum) VALUES ('fight.php', '[Plundermedaille erhalten] ::::: Spieler $fleet[user] hat eine Medaille für Plünderungen erhalten', '$time_new');");
			}
	}

     }

    $target_depot->save();

    if ($fleet[f_name])
    {
      if ($fleet[f_name_show] == "YES")
      {
			sql_query("UPDATE news_ber SET f_name_show='Y', f_name='$fleet[f_name]' WHERE ID='$bid[ID]'");
      }
      else
      {
      		sql_query("UPDATE news_ber SET f_name='$fleet[f_name]' WHERE ID='$bid[ID]'");
      }
    }

    if ($fleet[f_colonize] == "YES" && $anzahl_user_colonies[0] >= numberOfColonies($comm_center[b_communication_center]) && $battle->SumOffScarecrow >= 1 && $battle->SumDefPlanes == 0 && $battle->SumDefDef == 0 && $target_user_home != "YES")
	    sql_query("UPDATE news_ber SET error='Die Kolonie konnte nicht erobert werden, da dein Kommunikationszentrum zu klein ist.' WHERE ID='$bid[ID]'");
    	
    if ($battle->SumOffPlanes == 0)
      sql_query("DELETE FROM actions WHERE f_id='$fleet[f_id]'");

  }
  else
  {
  	if ($fleet[f_colonize] == "YES" && $fleet[f_settler] > 0)
    {
      $shield_battle->SumOffense();
	  $new_koords = split(":",$fleet[f_target]);
	  
      sql_query("INSERT INTO city (user,city,x_pos,y_pos,z_pos,foundation,r_time,alliance,r_time_oxygen) VALUES ('$fleet[user]','$target_city','$new_koords[0]','$new_koords[1]','$new_koords[2]','$fleet[f_arrival]','$fleet[f_arrival]','$origin_user_alliance','$fleet[f_arrival]')");
      $sel_koords = sql_fetch_array ( sql_query("SELECT ID FROM city WHERE city = '$target_city'") );
      
	  //Gründung der ersten Kolonie suchen
	  $get_first_foundation = sql_query("SELECT foundation FROM city WHERE home = 'NO' ORDER BY foundation ASC");
	  $first_foundation = sql_fetch_array($get_first_foundation);

	  //wenn es bereits eine Kolonie gibt die 21 tage alt ist gibt es einen bonus
	  sql_query("UPDATE city SET b_work_board = 5 WHERE ID='$sel_koords[ID]'");
	  if(($first_foundation['foundation']+(86400*21)<time()) && ($fleet[f_colonize_nobonus] != "YES" ))
	  {
		$foundation_work_board = floor(pow((time()-($first_foundation['foundation']+(86400*14)))/8000,1/1.7));
		sql_query("UPDATE city SET b_work_board = $foundation_work_board, b_hangar = 1, b_airport = 1, b_defense_center = ".floor($foundation_work_board/2).", b_communication_center = 5 WHERE ID='$sel_koords[ID]'");
		// sql_query("UPDATE city SET b_work_board = $foundation_work_board, b_hangar = 1, b_airport = 1, b_defense_center = ".floor($foundation_work_board/2).", b_shield = ".floor($foundation_work_board/2).",c_active_shields = ".floor($foundation_work_board/2).", b_communication_center = 5 WHERE city='$target_city'"); round using shields
	  }

	  sql_query("UPDATE usarios SET points=points+5 WHERE ID='$fleet[user]'"); // +5 für die 5 Bauzentren per default
        // Wechsel in Stadthistorie vermerken
        //TODO Kommt man überhaupt in diese Stelle? Warum gibts dennoch EIntrag in Historie? Was läuft hier schief???
      sql_query("INSERT INTO city_history (city, owner, time, user) VALUES ('$target_city','$fleet[user]',".microtime(true).",'$fleet[user]')");

      sql_query("UPDATE actions SET f_settler=f_settler-1,f_flugzeuge_anzahl=f_flugzeuge_anzahl-1 WHERE f_id='$fleet[f_id]'");
      sql_query("UPDATE city SET p_settler_gesamt=p_settler_gesamt-1,p_gesamt_flugzeuge=p_gesamt_flugzeuge-1, blubb=blubb-1 WHERE ID='$origin_city'");
      sql_query("UPDATE city RIGHT JOIN usarios ON city.user=usarios.ID SET city.alliance=usarios.alliance, city.t_depot_management=usarios.t_depot_management,city.t_water_compression=usarios.t_water_compression,city.t_mining=usarios.t_mining WHERE city.ID='$sel_koords[ID]'");

	  //Punkte des Spielers neu berechnen
	  sql_query("UPDATE city SET points=b_iridium_mine+b_holzium_plantage+b_water_derrick+b_oxygen_reactor+b_depot+b_oxygen_depot+b_trade_center+b_hangar+".
	    "b_airport+b_defense_center+b_shield+b_technologie_center+b_communication_center+b_work_board WHERE ID = '$sel_koords[ID]'");
	  sql_query("UPDATE usarios SET points=tech_points + (SELECT sum(points) FROM city WHERE user= '$fleet[user]') WHERE ID= '$fleet[user]'");

      sql_query("INSERT INTO news_er (city,time,topic) VALUES ('$origin_city',". time() .",'Sie haben erfolgreich eine neue Stadt gegr&uuml;ndet')");

      $test = "INSERT INTO news_ber (attack_user, attack_bid,  attack_city, defense_city, time, attack_seen, defense_seen, attack_seen_sitter, defense_seen_sitter, attack_delete, defense_delete, attackers_alliance, attack_xmlid, f_name_show, f_name, shield, art, error) VALUES 
      ('$origin_user', MD5(CONCAT('$target_city$fleet[f_arrival]',RAND())), '$origin_city', '$sel_koords[ID]', '$fleet[f_arrival]', 'N', 'N', 'N', 'N', 'N', 'N', '$origin_user_alliance', '$attack_xmlid', '$f_name_show', '$fleet[f_name]', '0', 'attack', 'Settler')";
      sql_query($test);
      
      if ($shield_battle->SumOffPlanes == 1)
      {
        sql_query("DELETE FROM actions WHERE f_id='$fleet[f_id]'");
      }
      
      //Werte für Medaille einfügen
      sql_query("UPDATE medals SET n_kolo=n_kolo+1 WHERE user='$fleet[user]'");
      $medals = sql_fetch_array(sql_query("SELECT n_kolo FROM medals WHERE user='$fleet[user]'"));
      if (in_array($medals[n_kolo], $medal_values[$medaillen[KOLO]])) {
      		$time_new = time();
      		sql_query("INSERT INTO global_logs (seite, inhalt, datum) VALUES ('fight.php', '[Settlermedaille erhalten] ::::: Spieler $fleet[user] hat eine Medaille für Stadtgründungen erhalten', '$time_new');");
			$stufe = array_search($medals[n_kolo], $medal_values[$medaillen[KOLO]])+1;
			$username = sql_fetch_array(sql_query("SELECT user FROM usarios WHERE ID='$fleet[user]'"));
			sql_query("UPDATE medals SET m_kolo='".$stufe."',d_kolo='$fleet[f_arrival]' WHERE user='$fleet[user]'");
			sql_query("INSERT INTO news_igm_umid (sender,recipient,owner,time,topic,text,dir) VALUES ('ETS','$fleet[user]','$fleet[user]','$fleet[f_arrival]','".MEDAL_TOPIC."','".MEDAL_HALLO.$username[user].MEDAL_TEXT.$medal_text[$medaillen[KOLO]].$stufe."','0')" );
      }
   }
  }
  
  
?>
