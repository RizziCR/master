#! /usr/bin/php5
<?php

$tore = 1;
$query = "";

require_once("bench.php");

$bench = new Bench;
$bench->Start();

@set_time_limit(590);

require_once("database.php");
require_once("constants.php");
require_once("functions.php");
require_once 'include/class_Party.php';
require_once 'include/class_Kampf.php';
require_once 'include/class_Krieg.php';
include("tutorial.php");

$email = "";

if (time() > PAUSE_BEGIN && time() < PAUSE_END)
    exit;
$unix_timestamp = time();
try {

    // Cron Job
    // Wichtig: php.ini => execution_time = 300


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
        sql_query("UPDATE _cron SET work='Y',time=UNIX_TIMESTAMP(), file='cr_calc'");
    } catch(Exception $e) {
        sql_roll_back();
    }
    sql_commit();

    $bench->NewMarke("Inits");

    // Flieger durchzaehlen
    try {
        sql_begin_transaction();
        sql_query("UPDATE city SET
            p_sparrow_gesamt=p_sparrow,
            p_blackbird_gesamt=p_blackbird,
            p_raven_gesamt=p_raven,
            p_eagle_gesamt=p_eagle,
            p_falcon_gesamt=p_falcon,
            p_nightingale_gesamt=p_nightingale,
            p_settler_gesamt=p_settler,
            p_scarecrow_gesamt=p_scarecrow,
            p_ravager_gesamt=p_ravager,
            p_destroyer_gesamt=p_destroyer,
            p_bomber_gesamt=p_bomber,
            p_espionage_probe_gesamt=p_espionage_probe,
            p_small_transporter_gesamt=p_small_transporter,
            p_medium_transporter_gesamt=p_medium_transporter,
            p_big_transporter_gesamt=p_big_transporter");

        $f_db_name = array();
        $f_db_name[] = "f_sparrow";
        $f_db_name[] = "f_blackbird";
        $f_db_name[] = "f_raven";
        $f_db_name[] = "f_eagle";
        $f_db_name[] = "f_falcon";
        $f_db_name[] = "f_nightingale";
        $f_db_name[] = "f_settler";
        $f_db_name[] = "f_scarecrow";
        $f_db_name[] = "f_ravager";
        $f_db_name[] = "f_destroyer";
        $f_db_name[] = "f_espionage_probe";
        $f_db_name[] = "f_small_transporter";
        $f_db_name[] = "f_medium_transporter";
        $f_db_name[] = "f_big_transporter";
        $f_db_name[] = "f_bomber";
        
        
        // count only back flights of trading planes or resources, and of transportations;
        // in addition count planes given away
        for ($i=0;$i<count($p_db_name);$i++)
        {
            $load_fleets = sql_query("SELECT city,
						SUM(f_sparrow) AS f_sparrow,
						SUM(f_blackbird) AS f_blackbird,
						SUM(f_raven) AS f_raven,
						SUM(f_eagle) AS f_eagle,
						SUM(f_falcon) AS f_falcon,
						SUM(f_nightingale) AS f_nightingale,
						SUM(f_settler) AS f_settler,
						SUM(f_scarecrow) AS f_scarecrow,
						SUM(f_ravager) AS f_ravager,
						SUM(f_destroyer) AS f_destroyer,
						SUM(f_espionage_probe) AS f_espionage_probe,
						SUM(f_small_transporter) AS f_small_transporter,
						SUM(f_medium_transporter) AS f_medium_transporter,
						SUM(f_big_transporter) AS f_big_transporter,
						SUM(f_bomber) AS f_bomber
						FROM actions WHERE ((f_action LIKE '%_back' || 
            								 f_action LIKE '%_from_depot' || 
            								 f_action LIKE 'plane_%') || 
            								(f_give='YES')) 
            			GROUP by `city`");

            while ($update = sql_fetch_array($load_fleets)) {

            	$query = array();
            	for ($i=0;$i<ANZAHL_FLUGZEUGE;$i++)
            		$query[] = "p_$p_db_name_wus[$i]_gesamt=p_$p_db_name_wus[$i]_gesamt+". $update["f_". $p_db_name_wus[$i]];
            	
            	sql_query("UPDATE city SET ". implode(",",$query) ." WHERE ID='$update[city]'");
            	unset($query);
            }
            
        }
	
        // add planes under production
        $res = sql_query("SELECT city,current_build,Count(*) AS anzahl FROM jobs_planes GROUP BY city,current_build");
 
        while ($lala = sql_fetch_array($res))
            sql_query("UPDATE city SET $lala[current_build]_gesamt=$lala[current_build]_gesamt+$lala[anzahl] WHERE ID='$lala[city]'");

        // sum up all plane types of the city
          sql_query("UPDATE city SET p_gesamt_flugzeuge=p_sparrow_gesamt+p_blackbird_gesamt+p_raven_gesamt+p_eagle_gesamt+p_falcon_gesamt+p_nightingale_gesamt+p_settler_gesamt+p_scarecrow_gesamt+p_ravager_gesamt+p_destroyer_gesamt+p_espionage_probe_gesamt+p_small_transporter_gesamt+p_medium_transporter_gesamt+p_big_transporter_gesamt+p_bomber_gesamt");
    	
    } catch(Exception $e) {
        sql_roll_back();
    }
    sql_commit();

    $bench->NewMarke("Flieger durchgezählt"); // . sql_affected_rows()
    
	// Nachricht über fertige Technologien
    sql_query("INSERT INTO news_er (city,time,topic)
        SELECT jobs_tech.start_city, jobs_tech.end_time, jobs_tech.msg FROM jobs_tech, city WHERE jobs_tech.user=city.user
    && city.home = 'YES' && jobs_tech.end_time<$unix_timestamp ");

    $bench->NewMarke("Nachricht über fertige Technologien");
    
    // Technologien fertig forschen, PunkteUpdate
    try {
        sql_begin_transaction();
		$x=0;
		$main_actions = sql_query("SELECT jobs_tech.user AS user,usarios.alliance AS alliance,jobs_tech.current_build AS current_build, jobs_tech.end_time AS end_time FROM jobs_tech, usarios WHERE jobs_tech.end_time<$unix_timestamp && usarios.ID=jobs_tech.user ORDER BY end_time ASC");
		while ($actions = sql_fetch_array($main_actions))
        {
            sql_query("UPDATE usarios SET $actions[current_build]=$actions[current_build]+1,
            tech_points=tech_points+1,points=points+1 WHERE ID='$actions[user]'");
            sql_query("UPDATE alliances SET points=points+1 WHERE ID='$actions[alliance]'");

            if ($actions[current_build] == "t_mining" || $actions[current_build] == "t_water_compression" || $actions[current_build] == "t_depot_management")
                sql_query("UPDATE city SET $actions[current_build]=$actions[current_build]+1 WHERE user='$actions[user]'");
            sql_query("DELETE FROM jobs_tech WHERE jobs_tech.end_time='$actions[end_time]' AND jobs_tech.user='$actions[user]'");
            $x++;
            
            //Medaillenabschnitt
            if ($actions[current_build] == "t_mining") {
		$bbt=sql_fetch_array(sql_query("SELECT t_mining,user FROM usarios WHERE ID='$actions[user]'"));
		if (in_array($bbt[t_mining], $medal_values[$medaillen[BBT]])) {
			$stufe = array_search($bbt[t_mining], $medal_values[$medaillen[BBT]])+1;
			sql_query("UPDATE medals SET m_bbt='".$stufe."',d_bbt='$actions[end_time]' WHERE user='$actions[user]'");
			sql_query("INSERT INTO news_igm_umid (sender,recipient,owner,time,topic,text,dir) VALUES ('ETS','$actions[user]','$actions[user]','$actions[end_time]','".MEDAL_TOPIC."','".MEDAL_HALLO.$bbt[user].MEDAL_TEXT.$medal_text[$medaillen[BBT]].$stufe."','0')" );
			sql_query("INSERT INTO global_logs (seite, inhalt, datum) VALUES ('cr_calc.php', '[BBTmedaille erhalten] ::::: Spieler $actions[user] hat eine Medaille für Bergbautechnik erhalten', '$actions[end_time]');");
		}
            } else if ($actions[current_build] == "t_water_compression") {
		$wk=sql_fetch_array(sql_query("SELECT t_water_compression,user FROM usarios WHERE ID='$actions[user]'"));
		if (in_array($wk[t_water_compression], $medal_values[$medaillen[WK]])) {
			$stufe = array_search($wk[t_water_compression], $medal_values[$medaillen[WK]])+1;
			sql_query("UPDATE medals SET m_wk='".$stufe."',d_wk='$actions[end_time]' WHERE user='$actions[user]'");
			sql_query("INSERT INTO news_igm_umid (sender,recipient,owner,time,topic,text,dir) VALUES ('ETS','$actions[user]','$actions[user]','$actions[end_time]','".MEDAL_TOPIC."','".MEDAL_HALLO.$wk[user].MEDAL_TEXT.$medal_text[$medaillen[WK]].$stufe."','0')" );
			sql_query("INSERT INTO global_logs (seite, inhalt, datum) VALUES ('cr_calc.php', '[WKmedaille erhalten] ::::: Spieler $actions[user] hat eine Medaille für Wasserkompression erhalten', '$actions[end_time]');");
		}
            } else if ($actions[current_build] == "t_oxidationsdrive" ||  $actions[current_build] == "t_hoverdrive" || $actions[current_build] == "t_antigravitydrive") {
		$techs = sql_fetch_array(sql_query("SELECT t_oxidationsdrive,t_hoverdrive,t_antigravitydrive,user FROM usarios WHERE ID='$actions[user]'"));
		$antrieb = $techs[t_oxidationsdrive] + 1.0*$techs[t_hoverdrive]/$t_increase[O_DRIVE]*$t_increase[H_DRIVE] + 1.0*$techs[t_antigravitydrive]/$t_increase[O_DRIVE]*$t_increase[A_DRIVE];
		$m_aktuell=sql_fetch_array(sql_query("SELECT m_gear FROM medals WHERE user='$actions[user]'"));
		if ($m_aktuell[m_gear] < count($medal_values[$medaillen[GEAR]])) { //Schon Endstufe erreicht?
			if($antrieb >= $medal_values[$medaillen[GEAR]][$m_aktuell[m_gear]]) {
				$m_aktuell[m_gear]++;
				sql_query("UPDATE medals SET m_gear=m_gear+1,d_gear='$actions[end_time]' WHERE user='$actions[user]'");
				sql_query("INSERT INTO news_igm_umid (sender,recipient,owner,time,topic,text,dir) VALUES ('ETS','$actions[user]','$actions[user]','$actions[end_time]','".MEDAL_TOPIC."','".MEDAL_HALLO.$techs[user].MEDAL_TEXT.$medal_text[$medaillen[GEAR]].$m_aktuell[m_gear]."','0')" );
				sql_query("INSERT INTO global_logs (seite, inhalt, datum) VALUES ('cr_calc.php', '[GEARmedaille erhalten] ::::: Spieler $actions[user] hat eine Medaille für Antiebstechnologien erhalten', '$actions[end_time]');");
			}
		}
            } else if ($actions[current_build] == "t_electronsequenzweapons" ||  $actions[current_build] == "t_protonsequenzweapons" || $actions[current_build] == "t_neutronsequenzweapons") {
		$techs = sql_fetch_array(sql_query("SELECT t_electronsequenzweapons,t_protonsequenzweapons,t_neutronsequenzweapons,user FROM usarios WHERE ID='$actions[user]'"));
		$waffen = $techs[t_electronsequenzweapons] + 1.0*$techs[t_protonsequenzweapons]/$t_increase[E_WEAPONS]*$t_increase[P_WEAPONS] + 1.0*$techs[t_neutronsequenzweapons]/$t_increase[E_WEAPONS]*$t_increase[N_WEAPONS];
		$m_aktuell=sql_fetch_array(sql_query("SELECT m_weapon FROM medals WHERE user='$actions[user]'"));
		if ($m_aktuell[m_weapon] < count($medal_values[$medaillen[WEAPON]])) { //Schon Endstufe erreicht?
			if($waffen >= $medal_values[$medaillen[WEAPON]][$m_aktuell[m_weapon]]) {
				$m_aktuell[m_weapon]++;
				sql_query("UPDATE medals SET m_weapon=m_weapon+1,d_gear='$actions[end_time]' WHERE user='$actions[user]'");
				sql_query("INSERT INTO news_igm_umid (sender,recipient,owner,time,topic,text,dir) VALUES ('ETS','$actions[user]','$actions[user]','$actions[end_time]','".MEDAL_TOPIC."','".MEDAL_HALLO.$techs[user].MEDAL_TEXT.$medal_text[$medaillen[WEAPON]].$m_aktuell[m_weapon]."','0')" );
				sql_query("INSERT INTO global_logs (seite, inhalt, datum) VALUES ('cr_calc.php', '[WEAPONmedaille erhalten] ::::: Spieler $actions[user] hat eine Medaille für Waffentechnologie erhalten', '$actions[end_time]');");
			}
		}
            } else if ($actions[current_build] == "t_computer_management") {
		$m_aktuell=sql_fetch_array(sql_query("SELECT m_fleet FROM medals WHERE user='$actions[user]'"));
		if ($m_aktuell[m_fleet] < count($medal_values[$medaillen[FLEET]])) { //Schon Endstufe erreicht?
			$techs = sql_fetch_array(sql_query("SELECT t_computer_management, user FROM usarios WHERE ID='$actions[user]'"));
			$builds = sql_query("SELECT b_airport FROM city WHERE user='$actions[user]'");
			$airport = 0;
			while ($build = sql_fetch_array($builds)) {
				if ($build[b_airport] > $airport) {
					$airport = $build[b_airport];
				}
			}
			$maxfleet = $airport*5 + $techs[t_computer_management]*3;
			if($maxfleet >= $medal_values[$medaillen[FLEET]][$m_aktuell[m_fleet]]) {
				$m_aktuell[m_fleet]++;
				sql_query("UPDATE medals SET m_fleet=m_fleet+1,d_fleet='$actions[end_time]' WHERE user='$actions[user]'");
				sql_query("INSERT INTO news_igm_umid (sender,recipient,owner,time,topic,text,dir) VALUES ('ETS','$actions[user]','$actions[user]','$actions[end_time]','".MEDAL_TOPIC."','".MEDAL_HALLO.$techs[user].MEDAL_TEXT.$medal_text[$medaillen[FLEET]].$m_aktuell[m_fleet]."','0')" );
				sql_query("INSERT INTO global_logs (seite, inhalt, datum) VALUES ('cr_calc.php', '[Settlermedaille erhalten] ::::: Spieler $actions[user] hat eine Medaille für Maxfleet erhalten', '$actions[end_time]');");
			}
		}
            }
            //Techpunkte
            
           // Punktemedaille
           $m_aktuell=sql_fetch_array(sql_query("SELECT m_tech FROM medals WHERE user='$actions[user]'"));
           if ($m_aktuell[m_tech] < count($medal_values[$medaillen[TECH]])) {
		$m_werte=sql_fetch_array(sql_query("SELECT tech_points,user FROM usarios WHERE ID='$actions[user]'"));
		if ($m_werte[tech_points] >= $medal_values[$medaillen[TECH]][$m_aktuell[m_tech]]) {
			$stufe = $m_aktuell[m_tech] +1;
			sql_query("UPDATE medals SET m_tech=m_tech+1,d_tech='$actions[end_time]' WHERE user='$actions[user]'");
			sql_query("INSERT INTO news_igm_umid (sender,recipient,owner,time,topic,text,dir) VALUES ('ETS','$actions[user]','$actions[user]','$actions[end_time]','".MEDAL_TOPIC."','".MEDAL_HALLO.$m_werte[user].MEDAL_TEXT.$medal_text[$medaillen[TECH]].$stufe."','0')" );
			sql_query("INSERT INTO global_logs (seite, inhalt, datum) VALUES ('cr_calc.php', '[Techmedaille erhalten] ::::: Spieler $actions[user] hat eine Medaille für Techpunkte erhalten', '$actions[end_time]');");
		}
           }
           
        }
        
        ///////// ÄNDERUNG FÜR TUTORIAL !!!!!!! //////////
        
        if($actions['current_build'] == "t_mining" && $actions['level'] == 2) {

        	$wk = sql_fetch_array ( sql_query ( "SELECT t_water_compression AS wk FROM usarios WHERE user='$actions[user]'") );
        	if($wk['wk'] > 0) 
        		sql_query("UPDATE new_tutorial SET tutorial = 10 WHERE user = '$actions[user]'");	
        	
        	sql_query("UPDATE city SET b_defense_center=2, points=points+2 WHERE user='$actions[user]'");
        	sql_query("UPDATE usarios SET points=points+2 WHERE ID='$actions[user]'");
        	
        }	
        if($actions['current_build'] == "t_water_compression" && $actions['level'] == 1) {
        	
        	$bbt = sql_fetch_array ( sql_query ( "SELECT t_mining AS bbt FROM usarios WHERE user='$actions[user]'") );
        	if($bbt['bbt'] > 1)
        		sql_query("UPDATE new_tutorial SET tutorial = 10 WHERE user = '$actions[user]'");
        	
        	sql_query("UPDATE city SET b_defense_center=2, points=points+2 WHERE user='$actions[user]'");
        	sql_query("UPDATE usarios SET points=points+2 WHERE ID='$actions[user]'");
        }
        
        ////////////////////////////////////////////////////
        
        
    } catch(Exception $e) {
        sql_roll_back();
    }
    sql_commit();
    $bench->NewMarke("Technologien");

	// Gebaeudebau
    try {
        sql_begin_transaction();

        // Gebäude fertigstelle, PunkteUPDATE
        $x=0;
	    $main_actions = "SELECT ID,city.user,jobs_build.city,city.alliance,jobs_build.current_build,jobs_build.end_time,jobs_build.msg,jobs_build.level FROM city,jobs_build WHERE jobs_build.end_time<$unix_timestamp && jobs_build.city = city.ID ORDER BY jobs_build.end_time ASC";
        $main_actions = sql_query($main_actions);
        while ($actions = sql_fetch_array($main_actions))
        {
        // Nachricht über fertige Gebäude
        	sql_query("INSERT INTO news_er (city,time,topic) VALUES ('$actions[ID]',$actions[end_time],'$actions[msg]')");
        	// Fertigstellen
            sql_query("UPDATE city SET $actions[current_build]=$actions[current_build]+1,msg='',b_current_build='',b_end_time=0,points=points+1 WHERE ID='$actions[ID]'");
            sql_query("UPDATE usarios SET points=points+1 WHERE ID='$actions[user]'");
            sql_query("UPDATE alliances SET points=points+1 WHERE ID='$actions[alliance]'");
			sql_query("DELETE FROM jobs_build WHERE jobs_build.end_time='$actions[end_time]' AND jobs_build.city='$actions[ID]'");
            
            switch ($actions[current_build])
            {
                case "b_iridium_mine" :
                    sql_query("UPDATE city SET r_iridium_add = ((15*POW(b_iridium_mine,1.8)+2000)/3600) WHERE ID='$actions[ID]'");
                    break;
                case "b_holzium_plantage" :
                    sql_query("UPDATE city SET r_holzium_add = ((15*POW(b_holzium_plantage,1.7)+2000)/3600) WHERE ID='$actions[ID]'");
                    break;
                case "b_water_derrick" :
                    sql_query("UPDATE city SET r_water_add = ((10*POW(b_water_derrick,2)+10)/3600) WHERE ID='$actions[ID]'");
                    break;
                case "b_oxygen_reactor" :
                    sql_query("UPDATE city SET r_oxygen_add = (((20/7)*POW(b_oxygen_reactor,2)+200)/3600) WHERE ID='$actions[ID]'");
                    break;
                case "b_shield" :
                    sql_query("UPDATE city SET c_active_shields = LEAST(c_active_shields+1, b_shield) WHERE ID='$actions[ID]'");
                    break;
            }
           if($actions['current_build'] == "b_iridium_mine" && $actions['level'] == 5) {
           	sql_query("UPDATE new_tutorial SET tutorial = 8 WHERE user = '$actions[user]'");
           }
           if($actions['current_build'] == "b_technologie_center" && $actions['level'] == 1) {
           	sql_query("UPDATE new_tutorial SET tutorial = 9 WHERE user = '$actions[user]'");
           }
           $x++;
           
           // Medaillen
           if($actions['current_build'] == "b_work_board") {
		$m_aktuell=sql_fetch_array(sql_query("SELECT m_bz FROM medals WHERE user='$actions[user]'"));
		if ($m_aktuell[m_bz] < count($medal_values[$medaillen[BZ]])) {
			$m_werte=sql_fetch_array(sql_query("SELECT b_work_board,user FROM city WHERE ID='$actions[ID]'"));
			if ($m_werte[b_work_board] >= $medal_values[$medaillen[BZ]][$m_aktuell[m_bz]]) {
				$stufe = $m_aktuell[m_bz]+1;
				sql_query("UPDATE medals SET m_bz=m_bz+1,d_bz='$actions[end_time]' WHERE user='$m_werte[user]'");
				$username = sql_fetch_array(sql_query("SELECT user FROM usarios WHERE ID='$m_werte[user]'"));
				sql_query("INSERT INTO news_igm_umid (sender,recipient,owner,time,topic,text,dir) VALUES ('ETS','$m_werte[user]','$m_werte[user]','$actions[end_time]','".MEDAL_TOPIC."','".MEDAL_HALLO.$username[user].MEDAL_TEXT.$medal_text[$medaillen[BZ]].$stufe."','0')" );
				sql_query("INSERT INTO global_logs (seite, inhalt, datum) VALUES ('cr_calc.php', '[BZmedaille erhalten] ::::: Spieler $actions[user] hat eine Medaille für Bauzentrum erhalten', '$actions[end_time]');");
			}
		}
           } else if ($actions['current_build'] == "b_defense_center") {
		$m_aktuell=sql_fetch_array(sql_query("SELECT m_defence FROM medals WHERE user='$actions[user]'"));
		if ($m_aktuell[m_defence] < count($medal_values[$medaillen[DEFENCE]])) {
			$m_werte=sql_fetch_array(sql_query("SELECT b_defense_center,user FROM city WHERE ID='$actions[ID]'"));
			if ($m_werte[b_defense_center] >= $medal_values[$medaillen[DEFENCE]][$m_aktuell[m_defence]]) {
				$stufe = $m_aktuell[m_defence]+1;
				sql_query("UPDATE medals SET m_defence=m_defence+1,d_defence='$actions[end_time]' WHERE user='$m_werte[user]'");
				$username = sql_fetch_array(sql_query("SELECT user FROM usarios WHERE ID='$m_werte[user]'"));
				sql_query("INSERT INTO news_igm_umid (sender,recipient,owner,time,topic,text,dir) VALUES ('ETS','$m_werte[user]','$m_werte[user]','$actions[end_time]','".MEDAL_TOPIC."','".MEDAL_HALLO.$username[user].MEDAL_TEXT.$medal_text[$medaillen[DEFENCE]].$stufe."','0')" );
				sql_query("INSERT INTO global_logs (seite, inhalt, datum) VALUES ('cr_calc.php', '[VZmedaille erhalten] ::::: Spieler $actions[user] hat eine Medaille für Verteidigungszentrum erhalten', '$actions[end_time]');");
			}
		}
           } else if ($actions['current_build'] == "b_trade_center") {
		$m_aktuell=sql_fetch_array(sql_query("SELECT m_trade FROM medals WHERE user='$actions[user]'"));
		if ($m_aktuell[m_trade] < count($medal_values[$medaillen[TRADE]])) {
			$m_werte=sql_fetch_array(sql_query("SELECT b_trade_center,user FROM city WHERE ID='$actions[ID]'"));
			if ($m_werte[b_trade_center] >= $medal_values[$medaillen[TRADE]][$m_aktuell[m_trade]]) {
				$stufe = $m_aktuell[m_trade]+1;
				sql_query("UPDATE medals SET m_trade=m_trade+1,d_trade='$actions[end_time]' WHERE user='$m_werte[user]'");
				$username = sql_fetch_array(sql_query("SELECT user FROM usarios WHERE ID='$m_werte[user]'"));
				sql_query("INSERT INTO news_igm_umid (sender,recipient,owner,time,topic,text,dir) VALUES ('ETS','$m_werte[user]','$m_werte[user]','$actions[end_time]','".MEDAL_TOPIC."','".MEDAL_HALLO.$username[user].MEDAL_TEXT.$medal_text[$medaillen[TRADE]].$stufe."','0')" );
				sql_query("INSERT INTO global_logs (seite, inhalt, datum) VALUES ('cr_calc.php', '[Trademedaille erhalten] ::::: Spieler $actions[user] hat eine Medaille für Handelszentrum erhalten', '$actions[end_time]');");
			}
		}
           } else if ($actions['current_build'] == "b_airport") {
		$m_aktuell=sql_fetch_array(sql_query("SELECT m_fleet FROM medals WHERE user='$actions[user]'"));
		if ($m_aktuell[m_fleet] < count($medal_values[$medaillen[FLEET]])) { //Schon Endstufe erreicht?
			$techs = sql_fetch_array(sql_query("SELECT t_computer_management, user FROM usarios WHERE ID='$actions[user]'"));
			$build = sql_fetch_array(sql_query("SELECT b_airport FROM city WHERE ID='$actions[ID]'"));
			$maxfleet = $build[b_airport]*5 + $techs[t_computer_management]*3;
			if($maxfleet >= $medal_values[$medaillen[FLEET]][$m_aktuell[m_fleet]]) {
				$m_aktuell[m_fleet]++;
				sql_query("UPDATE medals SET m_fleet=m_fleet+1,d_fleet='$actions[end_time]' WHERE user='$actions[user]'");
				sql_query("INSERT INTO news_igm_umid (sender,recipient,owner,time,topic,text,dir) VALUES ('ETS','$actions[user]','$actions[user]','$actions[end_time]','".MEDAL_TOPIC."','".MEDAL_HALLO.$techs[user].MEDAL_TEXT.$medal_text[$medaillen[FLEET]].$m_aktuell[m_fleet]."','0')" );
				sql_query("INSERT INTO global_logs (seite, inhalt, datum) VALUES ('cr_calc.php', '[Fleetmedaille erhalten] ::::: Spieler $actions[user] hat eine Medaille für Maxfleet erhalten', '$actions[end_time]');");
			}
		}
            }
           // Punktemedaille
           $m_aktuell=sql_fetch_array(sql_query("SELECT m_points FROM medals WHERE user='$actions[user]'"));
           if ($m_aktuell[m_points] < count($medal_values[$medaillen[POINTS]])) {
		$m_werte=sql_fetch_array(sql_query("SELECT points,user FROM usarios WHERE ID='$actions[user]'"));
		if ($m_werte[points] >= $medal_values[$medaillen[POINTS]][$m_aktuell[m_points]]) {
			$stufe = $m_aktuell[m_points] +1;
			sql_query("UPDATE medals SET m_points=m_points+1,d_points='$actions[end_time]' WHERE user='$actions[user]'");
			sql_query("INSERT INTO news_igm_umid (sender,recipient,owner,time,topic,text,dir) VALUES ('ETS','$actions[user]','$actions[user]','$actions[end_time]','".MEDAL_TOPIC."','".MEDAL_HALLO.$m_werte[user].MEDAL_TEXT.$medal_text[$medaillen[POINTS]].$stufe."','0')" );
			sql_query("INSERT INTO global_logs (seite, inhalt, datum) VALUES ('cr_calc.php', '[Punktemedaille erhalten] ::::: Spieler $actions[user] hat eine Medaille für Punktebau erhalten', '$actions[end_time]');");
		}
           }
        }
    } catch(Exception $e) {
        sql_roll_back();
    }
    sql_commit();
    $bench->NewMarke("Gebäude");
    
	
	// U-Mod & Sperrung, keine Rohstoffförderung
    sql_query("UPDATE city RIGHT JOIN userdata ON city.user=userdata.ID SET city.r_time='$unix_timestamp',city.r_time_oxygen='$unix_timestamp'
    WHERE userdata.holiday!=0 || userdata.multi='Y' || (userdata.time_block+24*3600) >= $unix_timestamp");

    $bench->NewMarke("U-Mod & Sperrung, keine Rohstoffförderung");

    sql_query("LOCK TABLES city WRITE");
    try {
        sql_begin_transaction();
        // Falls Lager voll, keine Förderung mehr
        sql_query("UPDATE city SET r_time='$unix_timestamp' WHERE r_iridium+r_holzium+r_water >= 2 * (5000 * POW(b_depot,2) + 200000) * POW(1.05,t_depot_management)");
        $bench->NewMarke("Falls Lager voll, keine Förderung mehr");

        // Förderungen berechnen
    // it's a bad idea to use the oxygen extraction to compute the consumed water - reversion
    // of an implicit dependency
        sql_query("UPDATE city SET  r_iridium = r_iridium + r_iridium_add * POW(1.05,t_mining) * $tore * ($unix_timestamp - r_time),
            r_holzium = r_holzium + r_holzium_add * POW(1.05,t_mining) * $tore * ($unix_timestamp - r_time),

            r_water   = GREATEST(0, (r_water   + (r_water_add - (r_oxygen_add - (2/36)) * 3.5) * ($unix_timestamp - r_time))),
        r_time='$unix_timestamp'");
        $bench->NewMarke("Förderungen berechnen");

        // O2-Förderung & H2O-Verbrauch, wenn zu wenig Wasser vorhanden
        sql_query("UPDATE city SET  r_oxygen = r_oxygen + (r_water_add/3.5 + 2/36) * POW(1.05,t_water_compression) * $tore * ($unix_timestamp - r_time_oxygen),
        r_time_oxygen='$unix_timestamp' WHERE r_water=0");
        $bench->NewMarke("O2-Förderung & H2O-Verbrauch, wenn zu wenig Wasser vorhanden");

        // O2-Förderung & H2O-Verbrauch, wenn genug Wasser vorhanden
        sql_query("UPDATE city SET  r_oxygen = r_oxygen + r_oxygen_add * POW(1.05,t_water_compression )* $tore * ($unix_timestamp - r_time_oxygen),
        r_time_oxygen='$unix_timestamp' WHERE r_water>0");
        $bench->NewMarke("O2-Förderung & H2O-Verbrauch, wenn genug Wasser vorhanden");


        // O2-Lager auf 100%, falls übervoll
        sql_query("UPDATE city SET r_oxygen = 4 * (4000 * POW(b_oxygen_depot,2) + 80000) * POW(1.05,t_depot_management)
        WHERE r_oxygen > 4 * (4000 * POW(b_oxygen_depot,2) + 80000) * POW(1.05,t_depot_management)");

    } catch(Exception $e) {
        sql_roll_back();
    }
    sql_commit();
    sql_query("UNLOCK TABLES");

    $bench->NewMarke("O2-Lager auf 100%, falls übervoll");

    // Schutzschild -> nicht aktiv fuer Speed
/*    sql_query("LOCK TABLES city WRITE");
    try {
        sql_begin_transaction();

        $ss_res = sql_query("SELECT city,b_shield,c_active_shields,c_shield_timer FROM city WHERE c_active_shields<b_shield AND 0<c_shield_timer AND c_shield_timer<".time());
        while ($ss_city = sql_fetch_array($ss_res))
        {
            $active_shields = min($ss_city['c_active_shields']+1, $ss_city['b_shield']);
            if($active_shields == $ss_city['b_shield']) {
                $timer = 0;
            }
            else {
                $timer = time() + ShieldRegenTime($ss_city['b_shield'], $active_shields);
            }
            sql_query("UPDATE city SET c_active_shields=".$active_shields.", c_shield_timer=".$timer." WHERE city='$ss_city[city]'");
        }
    } catch(Exception $e) {
        sql_roll_back();
    }
    sql_commit();
    sql_query("UNLOCK TABLES");
    $bench->NewMarke("Schutzschild");
*/

	// Gebaeude, Flieger, Nachrichten
    try {
        sql_begin_transaction();
        sql_query("LOCK TABLES city WRITE, jobs_defense WRITE, jobs_planes WRITE, news_er WRITE, attack_denies WRITE");

        $main_actions = sql_query("SELECT city,current_build FROM jobs_defense WHERE end_time<$unix_timestamp");
        while ($actions = sql_fetch_array($main_actions))
            sql_query("UPDATE city SET $actions[current_build]=$actions[current_build]+1 WHERE ID='$actions[city]'");

        // ! we need to query the number of all airplains here to protect against race conditions on cron vs. job cancelation
        $main_actions = sql_query("SELECT jobs_planes.city,current_build,city.p_gesamt_flugzeuge FROM jobs_planes JOIN city ON(jobs_planes.city=city.ID) WHERE end_time<$unix_timestamp");
        while ($actions = sql_fetch_array($main_actions))
            sql_query("UPDATE city SET $actions[current_build]=$actions[current_build]+1 WHERE ID='$actions[city]' AND p_gesamt_flugzeuge=".intval($actions['p_gesamt_flugzeuge']));

        $bench->NewMarke("Def&Fluggis");

        sql_query("INSERT INTO news_er (city,topic,time) SELECT city.ID,jobs_defense.msg,jobs_defense.end_time FROM jobs_defense INNER JOIN city ON jobs_defense.city=city.ID WHERE jobs_defense.end_time<$unix_timestamp");
        sql_query("INSERT INTO news_er (city,topic,time) SELECT city.ID,jobs_planes.msg,jobs_planes.end_time FROM jobs_planes INNER JOIN city ON jobs_planes.city=city.ID WHERE jobs_planes.end_time<$unix_timestamp");
        sql_query("DELETE FROM jobs_defense WHERE end_time<$unix_timestamp");
        sql_query("DELETE FROM jobs_planes WHERE end_time<$unix_timestamp");
        sql_query("DELETE FROM attack_denies WHERE time<$unix_timestamp-". (ATTACKDENYHOURS*3600));

        sql_query("UNLOCK TABLES");
    } catch(Exception $e) {
        sql_roll_back();
    }
    sql_commit();
    $bench->NewMarke("Nachrichten");

    try {
        sql_begin_transaction();
        sql_query("LOCK TABLES city WRITE, actions WRITE");
        $count_x=0;
        $get_fleets = sql_query("SELECT * FROM actions WHERE f_arrival<$unix_timestamp && f_arrival!=0 ORDER BY f_arrival");
        while ($fleets = sql_fetch_array($get_fleets))
        {
        	$count_x++;
        	switch ($fleets[f_action])
            {
                case "sell_to_depot" :
                    $xmlid = MD5(uniqid());
                        
                    sql_query("UPDATE global SET iridium=iridium+$fleets[f_iridium],holzium=holzium+$fleets[f_holzium],water=water+$fleets[f_water],oxygen=oxygen+$fleets[f_oxygen]");
                    
                    sql_query("INSERT INTO news_ber (attack_user, attack_bid, attack_city, time, attack_seen, attack_seen_sitter, attack_delete, attack_xmlid, f_name_show, iridium, holzium, water, oxygen, points, shield, art) VALUES 
    				('$fleets[user]', MD5(CONCAT('$fleets[f_target]$fleets[f_arrival]',RAND())), '$fleets[city]', '$fleets[f_arrival]', 'N', 'N', 'N', '$xmlid', 'N', '$fleets[f_iridium]', '$fleets[f_holzium]', '$fleets[f_water]', '$fleets[f_oxygen]', '0', '0', '$fleets[f_action]')");
                      	
                    sql_query("DELETE FROM actions WHERE id='$fleets[id]'");
                    
                break;

                case "sell_from_depot" :
                	
					$query = array();
                    for ($i=ANZAHL_KAMPF_FLUGZEUGE;$i<ANZAHL_FLUGZEUGE;$i++)
                        if ($fleets["f_". $p_db_name_wus[$i]])
                            $query[] = "p_$p_db_name_wus[$i]=p_$p_db_name_wus[$i]+". $fleets["f_". $p_db_name_wus[$i]];

                    $xmlid = MD5(uniqid());
                    sql_query("UPDATE city SET r_iridium=r_iridium+$fleets[f_iridium], r_holzium=r_holzium+$fleets[f_holzium], r_water=r_water+$fleets[f_water], r_oxygen=r_oxygen+$fleets[f_oxygen], 
                    ". implode(",",$query) ." WHERE ID='$fleets[city]'");
                    
                    sql_query("INSERT INTO news_ber (attack_user, attack_bid, attack_city, time, attack_seen, attack_seen_sitter, attack_delete, attack_xmlid, f_name_show, iridium, holzium, water, oxygen, points, shield, art) VALUES 
    				('$fleets[user]', MD5(CONCAT('$fleets[f_target]$fleets[f_arrival]',RAND())), '$fleets[city]', '$fleets[f_arrival]', 'N', 'N', 'N', '$xmlid', 'N', '$fleets[f_iridium]', '$fleets[f_holzium]', '$fleets[f_water]', '$fleets[f_oxygen]', '0', '0', '$fleets[f_action]')");

                    sql_query("DELETE FROM actions WHERE id='$fleets[id]'");
                    
                break;

                case "attack" :
                    $id = $fleets[id];
                    include("fight.php");
                    sql_query("DELETE FROM actions WHERE id='$fleets[id]'");
                    break;

                case "attack_back" :
                	
					$query = array();
                    for ($i=0;$i<ANZAHL_KAMPF_FLUGZEUGE;$i++)
                        if ($fleets["f_". $p_db_name_wus[$i]])
                           $query[] = "p_$p_db_name_wus[$i]=p_$p_db_name_wus[$i]+". $fleets["f_". $p_db_name_wus[$i]];
						
                    $attack_alliance = sql_query("SELECT alliance FROM usarios WHERE ID = '$fleets[user]'");
                    $attack_alliance = sql_fetch_array($attack_alliance);
                    $defenders_alliance = sql_query("SELECT alliance FROM usarios WHERE ID = '$fleets[f_target]'");
                    $defenders_alliance = sql_fetch_array($defenders_alliance);
                    $xmlid = MD5(uniqid());
                                
                    sql_query("UPDATE city SET r_iridium=r_iridium+$fleets[f_iridium], r_holzium=r_holzium+$fleets[f_holzium], r_water=r_water+$fleets[f_water], r_oxygen=r_oxygen+$fleets[f_oxygen],
                    ". implode(",",$query) ." WHERE ID='$fleets[city]'");
                    
                    sql_query("INSERT INTO news_ber (attack_user, defense_user, attack_bid, attack_city, defense_city, time, attack_seen, attack_seen_sitter, attack_delete, attackers_alliance, defenders_alliance, attack_xmlid, f_name_show, f_name, iridium, holzium, water, oxygen, points, shield, art) VALUES 
    				('$fleets[user]', '$fleets[f_target_user]', MD5(CONCAT('$fleets[f_target]$fleets[f_arrival]',RAND())),'$fleets[city]', '$fleets[f_target]', '$fleets[f_arrival]', 'N', 'N', 'N', '$attack_alliance[alliance]', '$defenders_alliance[alliance]', '$xmlid', 'Y', '".mysql_real_escape_string($fleets[f_name])."', '$fleets[f_iridium]', '$fleets[f_holzium]', '$fleets[f_water]', '$fleets[f_oxygen]', '0', '0', '$fleets[f_action]')");
                    sql_query("DELETE FROM actions WHERE id='$fleets[id]'");

                break;

                case "transport" :
                    $query = array();
                    
                    $new_koords = split(":",$fleets[f_target]);
                    if($new_koords[1] != "") {
						$old_koords = $fleets['f_target'];
                    	$get_koords = sql_fetch_array(sql_query("SELECT ID FROM city WHERE city LIKE '$fleets[f_target]';"));
                    	$fleets['f_target'] = $get_koords['ID'];
                    	$date = time();
                    	sql_query("INSERT INTO global_logs (seite, inhalt, datum) VALUES ('cr_calc.php', '[TRANSPORT] ::::: Koordinaten $old_koords ersetzt durch ID $fleets[f_target]', '$date');");
                    }
                    
                    
                    // check with if() to prevent unsigned integer overflow:
                    $ressis = sql_query("SELECT IF( b_hangar*".PLANES_PER_LEVEL." > p_gesamt_flugzeuge,
                    b_hangar*".PLANES_PER_LEVEL." - p_gesamt_flugzeuge, 0 ) AS hangar_free FROM city WHERE ID='$fleets[f_target]'"); 
                    $calc = sql_fetch_array($ressis);
                    $l = new Lager($fleets[f_target]);
                    $l->addIridium($fleets[f_iridium]);
                    $l->addHolzium($fleets[f_holzium]);
                    $l->addWater($fleets[f_water]);
                    $l->addOxygen($fleets[f_oxygen]);
                    $l->save();
                    unset($l);
                    
                    if($fleet[f_name_show] == "YES") $f_name_show = "Y";
    				else $f_name_show = "N";    
    				
    				$attack_xmlid = md5(uniqid());
    				$defense_xmlid = md5(uniqid());
    				
    				sql_query("INSERT INTO news_ber (attack_user, defense_user, attack_bid, defense_bid, attack_city, defense_city, time, attack_seen, defense_seen, attack_seen_sitter, defense_seen_sitter, attack_delete, defense_delete, attackers_alliance, defenders_alliance, attack_xmlid, defense_xmlid, f_name_show, f_name, iridium, holzium, water, oxygen, lost, error, points, shield, art) VALUES 
				    ('$fleets[user]', '$fleets[f_target_user]', MD5(CONCAT('$fleets[f_target]$fleets[f_arrival]',RAND())), MD5(CONCAT('$fleets[f_target]$fleets[f_arrival]',RAND())), '$fleets[city]', '$fleets[f_target]', '$fleets[f_arrival]', 'N', 'N', 'N', 'N', 'N', 'N', '$fleets[attackers_alliance]', '$fleets[defenders_alliance]', '$attack_xmlid', '$defense_xmlid', '$f_name_show', '".mysql_real_escape_string($fleets[f_name])."', '$fleets[f_iridium]', '$fleets[f_holzium]', '$fleets[f_water]', '$fleets[f_oxygen]', '0', 'empty', '$target_city_points', '0', '$fleets[f_action]')");
    				
				    $bid = sql_query("SELECT ID FROM news_ber WHERE attack_xmlid = '$attack_xmlid' AND defense_xmlid = '$defense_xmlid'");
				    $bid = sql_fetch_array($bid);
				    
				    if ($fleets[f_give] == "YES") // Flieger verschenken?
                    {
                        if ($calc[hangar_free] < $fleets[f_flugzeuge_anzahl]) // Hangar zu klein
                        {
                            sql_query("UPDATE actions SET f_iridium=0,f_holzium=0,f_water=0,f_oxygen=0,f_action='transport_back',f_start='". $fleets[f_arrival] ."',f_arrival=f_start+'". ($fleets[f_arrival]-$fleets[f_start]) ."' WHERE id='$fleets[id]'");

                            sql_query("UPDATE news_ber SET error = 'Hangar' WHERE attack_xmlid = '$attack_xmlid' AND defense_xmlid = '$defense_xmlid'");
                        	// Hangar voll
                        }
                        else // Hangar ok
                        {
                        	$insert = "";
                        	$numeric = 0;
                        	for ($i=0;$i<ANZAHL_FLUGZEUGE;$i++)
                            {
                                $query[0][] = "p_$p_db_name_wus[$i]=p_$p_db_name_wus[$i]+". $fleets["f_$p_db_name_wus[$i]"];
                                $query[1][] = "p_$p_db_name_wus[$i]_gesamt=p_$p_db_name_wus[$i]_gesamt+". $fleets["f_$p_db_name_wus[$i]"];
                                $query[2][] = "p_$p_db_name_wus[$i]_gesamt=p_$p_db_name_wus[$i]_gesamt-". $fleets["f_$p_db_name_wus[$i]"];
                                $fleet_id = sql_query("SELECT type FROM type_plane WHERE name = '$p_name[$i]'");
        						$fleet_id = sql_fetch_array($fleet_id);
        						if($fleets["f_$p_db_name_wus[$i]"] > 0) {
        							if($numeric > 0) 
        								$insert .= ", ";
        							
        							$insert .= "('$bid[ID]', '$fleet_id[0]', 'attack', '" . $fleets["f_$p_db_name_wus[$i]"] . "', '0')";
        							$numeric++;
        						}
        						
        						if($fleets[user] == $fleets[f_target_user]) {
        						}else{
        							if($fleets["f_$p_db_name_wus[$i]"] > 0) {
        								// Fliegerstatistik
								        // Angreifer
								        sql_query("INSERT INTO flightstats (`user`, `type`, `ad`, `1`, `2`, `3`, `4`, `5`, `6`) VALUES ('$fleets[user]', '$fleet_id[0]', 'plane', '0', '0', '0', '0', '0', '" . $fleets["f_$p_db_name_wus[$i]"] . "') ON DUPLICATE KEY UPDATE `6` = `6`+'" . $fleets["f_$p_db_name_wus[$i]"] . "';");
								        
								        // Verteidiger
								        sql_query("INSERT INTO flightstats (`user`, `type`, `ad`, `1`, `2`, `3`, `4`, `5`, `6`) VALUES ('$fleets[f_target_user]', '$fleet_id[0]', 'plane', '0', '0', '0', '0', '" . $fleets["f_$p_db_name_wus[$i]"] . "', '0') ON DUPLICATE KEY UPDATE `5` = `5`+'" . $fleets["f_$p_db_name_wus[$i]"] . "';");
        							}
        						}
						    }
						    sql_query("INSERT INTO news_ber_ (`id`, `type`, `ad`, `before`, `after`) VALUES $insert");
                            sql_query("UPDATE city SET blubb=blubb+$fleets[f_flugzeuge_anzahl], ". implode(",",$query[0]) .",". implode(",",$query[1]) .",p_gesamt_flugzeuge=p_gesamt_flugzeuge+$fleets[f_flugzeuge_anzahl] WHERE ID='$fleets[f_target]'");
                            sql_query("UPDATE city SET blubb=blubb-$fleets[f_flugzeuge_anzahl], p_gesamt_flugzeuge=p_gesamt_flugzeuge-$fleets[f_flugzeuge_anzahl],". implode(",",$query[2]) .
                            " WHERE ID='$fleets[city]'");
                        }
                    }
                    if($fleets[f_give] == "YES" && $calc[hangar_free] < $fleets[f_flugzeuge_anzahl]) {
                    	
                    }else{
                    	sql_query("DELETE FROM actions WHERE id='$fleets[id]'");
                    }
                    break;

                case "transport_back" :

                	$query = array();
                    for ($i=0;$i<ANZAHL_FLUGZEUGE;$i++)
                        $query[] = "p_$p_db_name_wus[$i]=p_$p_db_name_wus[$i]+". $fleets["f_$p_db_name_wus[$i]"];

                    sql_query("UPDATE city SET r_iridium=r_iridium+$fleets[f_iridium], r_holzium=r_holzium+$fleets[f_holzium], r_water=r_water+$fleets[f_water], r_oxygen=r_oxygen+$fleets[f_oxygen],
                    ". implode(",",$query) ." WHERE ID='$fleets[city]'");
                        
                    sql_query("INSERT INTO news_ber (attack_user, defense_user, attack_bid, attack_city, defense_city, time, attack_seen, attack_seen_sitter, attack_delete, attackers_alliance, defenders_alliance, attack_xmlid, f_name_show, f_name, points, shield, art) VALUES 
    				('$fleets[user]', '$fleets[f_target_user]', MD5(CONCAT('$fleets[f_target]$fleets[f_arrival]',RAND())),'$fleets[city]', '$fleets[f_target]', '$fleets[f_arrival]', 'N', 'N', 'N', '$attack_alliance[alliance]', '$defenders_alliance[alliance]', '$xmlid', 'Y', '".mysql_real_escape_string($fleets[f_name])."', '0', '0', '$fleets[f_action]')");
	                sql_query("DELETE FROM actions WHERE id='$fleets[id]'");

				break;

                case "plane_sell" :
                	
                    $xmlid = md5(uniqid());
                    sql_query("INSERT INTO news_ber (attack_user, attack_bid, attack_city, time, attack_seen, attack_seen_sitter, attack_delete, attack_xmlid, f_name_show, points, shield, art) VALUES 
    				('$fleets[user]', MD5(CONCAT('$fleets[f_target]$fleets[f_arrival]',RAND())), '$fleets[city]', '$fleets[f_arrival]', 'N', 'N', 'N', '$xmlid', 'N', '0', '0', '$fleets[f_action]')");
                    
					$query = array();
                    for ($i=0;$i<ANZAHL_FLUGZEUGE;$i++)
                    {
                        if ($fleets["f_$p_db_name_wus[$i]"])
                        {
                    	    sql_query("UPDATE plane_trade SET stock=stock+". $fleets["f_$p_db_name_wus[$i]"]." where plane_type='$i'");
                            $query[0][] = "p_$p_db_name_wus[$i]_gesamt=p_$p_db_name_wus[$i]_gesamt-". $fleets["f_$p_db_name_wus[$i]"];
                        }
                    }
      					
                    sql_query("UPDATE city SET blubb=blubb-$fleets[f_flugzeuge_anzahl], p_gesamt_flugzeuge=p_gesamt_flugzeuge-$fleets[f_flugzeuge_anzahl],". implode(",",$query[0]) .
                    " WHERE ID='$fleets[city]'");
                    sql_query("DELETE FROM actions WHERE id='$fleets[id]'");
                 
                break;

                case "plane_buy" :
                    
                	$xmlid = md5(uniqid());
                        
                    $query = array();
                    for ($i=0;$i<ANZAHL_FLUGZEUGE;$i++)
                        $query[] = "p_$p_db_name_wus[$i]=p_$p_db_name_wus[$i]+". $fleets["f_$p_db_name_wus[$i]"];

                    sql_query("UPDATE city SET ". implode(",",$query) ." WHERE ID='$fleets[city]'");
                    sql_query("INSERT INTO news_ber (attack_user, attack_bid, attack_city, time, attack_seen, attack_seen_sitter, attack_delete, attack_xmlid, f_name_show, points, shield, art) VALUES 
    				('$fleets[user]', MD5(CONCAT('$fleets[f_target]$fleets[f_arrival]',RAND())), '$fleets[city]', '$fleets[f_arrival]', 'N', 'N', 'N', '$xmlid', 'N', '0', '0', '$fleets[f_action]')");
                    sql_query("DELETE FROM actions WHERE id='$fleets[id]'");
                    
				break;
            }
        }
        sql_query("UNLOCK TABLES");
    } catch(Exception $e) {
        sql_roll_back();
    }
    sql_commit();

    // Urlaub
    try {
        sql_begin_transaction();
        sql_query("UPDATE userdata RIGHT JOIN holiday ON userdata.ID=holiday.user SET userdata.holiday2='1', userdata.holiday=holiday.time+72*3600 WHERE holiday.art = 1 AND holiday.time<=$unix_timestamp");
		sql_query("UPDATE userdata RIGHT JOIN holiday ON userdata.ID=holiday.user SET userdata.holiday2='2', userdata.holiday=holiday.time WHERE holiday.art=2 AND holiday.time<=$unix_timestamp");
        sql_query("DELETE FROM holiday WHERE time<=$unix_timestamp");
    } catch(Exception $e) {
        sql_roll_back();
    }
    sql_commit();

    // Sessions
    try {
        sql_begin_transaction();
        sql_query("UPDATE multi_sessions RIGHT JOIN usarios ON multi_sessions.user=usarios.ID SET multi_sessions.logout_time=usarios.last_action
            WHERE usarios.last_action<". ($unix_timestamp-30*60) ." && multi_sessions.logout_time=0");
        sql_query("UPDATE usarios SET logged_in='NO' WHERE last_action<". ($unix_timestamp-30*60)." AND logged_in='YES'");
    } catch(Exception $e) {
        sql_roll_back();
    }
    sql_commit();
    $bench->NewMarke("Sessions");

    // Kriege beginnen
    try {
        sql_begin_transaction();
        $war_res = sql_query("SELECT id FROM wars WHERE approved='Y' AND open='N' AND begin<$unix_timestamp");
        while($k = sql_fetch_assoc($war_res)) {
            list( $_alliance ) = sql_fetch_row( sql_query('SELECT tag FROM war_party WHERE war_id='.$k[id].' AND side="A" LIMIT 1') );
            $krieg = new Krieg($_alliance);
            $krieg->load($k[id]);
            $krieg->beginWar();
            sql_query("INSERT INTO chronicle SET time=$unix_timestamp, war_id='".$k[id]."', causer='$_alliance', occasion='start'");
        }
    } catch(Exception $e) {
        sql_roll_back();
    }
    sql_commit();
    $bench->NewMarke("Kriege beginnen");

    // Kriege beenden 1
    try {
        sql_begin_transaction();
        $war_res = sql_query("SELECT id FROM wars WHERE approved='Y' AND open='Y' AND winner IS NULL AND end<$unix_timestamp");
        while($k = sql_fetch_assoc($war_res)) {
            list( $_alliance ) = sql_fetch_row( sql_query('SELECT tag FROM war_party WHERE war_id='.$k[id].' AND side="A" LIMIT 1') );
            $krieg = new Krieg($_alliance);
            $krieg->load($k[id]);
            sql_query("INSERT INTO chronicle SET time=$unix_timestamp, war_id='".$k[id]."', causer='$_alliance', occasion='end', victory='timeout'");
            $krieg->endWar(NULL, NULL, 'finish', NULL);
        }
    } catch(Exception $e) {
        sql_roll_back();
    }
    sql_commit();
    $bench->NewMarke("Kriege beenden 1");

    sql_query("UPDATE _cron SET work='N'");

} catch(Exception $e) {
    $email .= $e;
}

$bench->NewMarke("Ende");
//$bench->ShowResults();
?>
