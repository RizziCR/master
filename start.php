<?php

  // $use_lib = ?; // MSG_ADMINISTRATION
  $add_steps = null;

  require_once("msgs.php");
  require_once("database.php");
  require_once("constants.php");
  require_once("functions.php");
  require_once ('include/class_Party.php');
  require_once("do_loop.php");
  include("tutorial.php");


  // define phptal template
  require_once("PHPTAL.php");
  require_once("include/PHPTAL_EtsTranslator.php");
  //$template = new PHPTAL('city_general.html');
  $template = new PHPTAL('standard.html');
  $template->setTranslator(new PHPTAL_EtsTranslator());
  $template->setEncoding('ISO-8859-1');

  // set page title
  $template->set('pageTitle', 'Stadt - Übersicht');  
  
 // insert specific page logic here
  $MSG = array();

//  $get_buildings = sql_query("SELECT b_shield,c_active_shields,points,c_shield_timer FROM city WHERE city='".$_SESSION['city']."'");
  $get_buildings = sql_query("SELECT city,points FROM city WHERE ID='".$_SESSION['city']."'");
  $buildings = sql_fetch_array($get_buildings);
  
  $get_jobs_build = sql_query("SELECT current_build,end_time,level,msg FROM jobs_build WHERE city='$_SESSION[city]' ORDER BY end_time ASC");
  $get_jobs_tech = sql_query("SELECT current_build,end_time,level,msg FROM jobs_tech WHERE user='$_SESSION[user]' ORDER BY end_time ASC");
  
  $get_techs = sql_query("SELECT * FROM usarios WHERE ID='$_SESSION[user]'");
  $user_techs = sql_fetch_array($get_techs);

  $get_defense = sql_query("SELECT d_". implode(",d_",$d_db_name) ." FROM city WHERE ID='$_SESSION[city]'");
  $d_city = sql_fetch_array($get_defense);

  $get_multi_user = sql_query("SELECT 1 FROM multi_angemeldete WHERE user='$_SESSION[sitter]'");


  $main_query = sql_query("SELECT p_". implode(",p_",$p_db_name_wus) ." FROM city WHERE ID='$_SESSION[city]'");
  $p_count = sql_fetch_array($main_query);

  while( $jobs_build = sql_fetch_array($get_jobs_build) ) {
	  if ($jobs_build['end_time'] > time())
	  {
		$MSG[sizeof($MSG)][0] = $jobs_build['end_time'];
		$MSG[sizeof($MSG)-1][1] = "<font color=\"#44CCEE\">bis Fertigstellung ". translate_buildings($jobs_build['current_build']) ." Ausbaustufe ". ($jobs_build['level']) ." auf $buildings[city]</font>";
	  }
	  if ($jobs_build['end_time'] <= time() && $jobs_build['end_time']!=0 && $jobs_build['current_build'] != "0815")
	  {
		$MSG[sizeof($MSG)][0] = $jobs_build['end_time'];
		$MSG[sizeof($MSG)-1][1] = "<font color=\"#DDDDDD\">". translate_buildings($jobs_build['current_build']) ." Ausbaustufe ". ($jobs_build['level']) ." auf $buildings[city] wird er&ouml;ffnet</font>";
	  }
  }
/*
  if ($user_techs['t_end_time'] > time())
  {
    $MSG[sizeof($MSG)][0] = $user_techs['t_end_time'];
    $MSG[sizeof($MSG)-1][1] = "<font color=\"#AACCEE\">bis Fertigstellung ". translate_technologies($user_techs['t_current_build']) ." Ausbaustufe ". ($user_techs[$user_techs['t_current_build']]+1) ."</font>";
  }
  */
  while( $jobs_tech = sql_fetch_array($get_jobs_tech) ) {
	  if ($jobs_tech['end_time'] > time())
	  {
		$MSG[sizeof($MSG)][0] = $jobs_tech['end_time'];
		$MSG[sizeof($MSG)-1][1] = "<font color=\"#AACCEE\">bis Fertigstellung ". translate_technologies($jobs_tech['current_build']) ." Ausbaustufe ". ($jobs_tech['level']) ."</font>";
	  }
	  if ($jobs_tech['end_time'] <= time() && $jobs_tech['end_time']!=0)
	  {
		$MSG[sizeof($MSG)][0] = $jobs_tech['end_time'];
		$MSG[sizeof($MSG)-1][1] = "<font color=\"#DDDDDD\">". translate_technologies($jobs_tech['current_build']) ." Ausbaustufe ". ($jobs_tech['level']) ." wird fertiggestellt</font>";
	  }
  }

  $main_actions = sql_query("SELECT Max(end_time) AS end_time,Min(end_time) AS next,current_build,Count(*) AS anzahl FROM jobs_defense WHERE city='$_SESSION[city]' && end_time>". time() ." GROUP BY current_build");
  while ($actions = sql_fetch_array($main_actions))
  {
    if ($actions['end_time'] > time() || $actions['anzahl']>1)
    {
      $MSG[sizeof($MSG)][0] = $actions['end_time'];
      $MSG[sizeof($MSG)-1][1] = "<font color=\"#EEEE66\">bis Fertigstellung ". translate_defense($actions['current_build']) ." (Anzahl: $actions[anzahl] - n&auml;chster ". maketime($actions['next'] - time()) .") auf $buildings[city]</font>";
    }
  }

  $main_actions = sql_query("SELECT end_time,current_build FROM jobs_defense WHERE city='$_SESSION[city]' && end_time<=". time());
  while ($actions = sql_fetch_array($main_actions))
  {
    if ($actions['end_time'] != 0 && $actions['end_time'] <= time())
    {
      $MSG[sizeof($MSG)][0] = $actions['end_time'];
      $MSG[sizeof($MSG)-1][1] = "<font color=\"#DDDDDD\">Ein ". translate_defense($actions['current_build']) ." wird auf $buildings[city] einsatzbereit gemacht</font>";
    }
  }


  $main_actions = sql_query("SELECT Max(end_time) AS end_time,Min(end_time) AS next,current_build,Count(*) AS anzahl FROM jobs_planes WHERE city='$_SESSION[city]' && end_time>". time() ." GROUP BY current_build");
  while ($actions = sql_fetch_array($main_actions))
  {
    if ($actions['end_time'] > time())
    {
      $MSG[sizeof($MSG)][0] = $actions['end_time'];
      $MSG[sizeof($MSG)-1][1] = "<font color=\"#EEEEAA\">bis Fertigstellung ". translate_planes($actions['current_build']) ." (Anzahl: $actions[anzahl] - n&auml;chster ". maketime($actions['next'] - time()) .") auf $buildings[city]</font>";
    }
  }

  $main_actions = sql_query("SELECT end_time,current_build FROM jobs_planes WHERE city='$_SESSION[city]' && end_time<=". time());
  while ($actions = sql_fetch_array($main_actions))
  {
    if ($actions['end_time'] != 0 && $actions['end_time'] <= time())
    {
      $MSG[sizeof($MSG)][0] = $actions['end_time'];
      $MSG[sizeof($MSG)-1][1] = "<font color=\"#DDDDDD\">Ein ". translate_planes($actions['current_build']) ." wird auf $buildings[city] einsatzbereit gemacht</font>";
    }
  }

  $main_actions = sql_query("SELECT id,f_action,f_arrival,f_target,f_target_user,f_name FROM actions WHERE city='$_SESSION[city]' && f_arrival!=0");
  while ($actions = sql_fetch_array($main_actions))
  {
  	$select = sql_query("SELECT city FROM city WHERE ID = '$actions[f_target]'");
  	$select = sql_fetch_array($select);
  	$new_koords = split(":",$actions[f_target]);
  	if($new_koords[1] == "") {
  		$actions[f_target] = $select['city'];
  	}
  	$actions['f_name'] = stripslashes($actions['f_name']);
    $inhalt = strip_tags ($actions['f_name']);
  	$inhalt = htmlentities ($inhalt);
  	
  	if($_SESSION['user'] == "514") {
  		$pfuschOutput .= "New_Koords: $new_koords[0] - $new_koords[1] - $new_koords[2] - $fleet[f_target] - $select[city] - $actions[f_target]";
  	}
  	
  	$fUser = new User($actions['f_target_user']);
    if ($actions['f_action'] != "" && $actions['f_arrival'] > time())
    {
      switch ($actions['f_action'])
      {
        case "sell_to_depot" :
        {
          $MSG[sizeof($MSG)][0] = $actions['f_arrival'];
          $MSG[sizeof($MSG)-1][1] = "<font color=\"#FFFF00\">bis Ankunft einer <a href=\"fleets.php?action=detail&id=$actions[id]\" style=\"color:#FFFF00\">Rohstoffhandel-Flotte</a> von $buildings[city] im Hauptlager</font>";
          break;
        }
        case "sell_from_depot" :
        {
          $MSG[sizeof($MSG)][0] = $actions['f_arrival'];
          $MSG[sizeof($MSG)-1][1] = "<font color=\"#FFFF00\">bis Rückkehr einer <a href=\"fleets.php?action=detail&id=$actions[id]\" style=\"color:#FFFF00\">Rohstoffhandel-Flotte</a> vom Hauptlager in $buildings[city]</font>";
          break;
        }
        case "attack" :
        {
          $MSG[sizeof($MSG)][0] = $actions['f_arrival'];
          $MSG[sizeof($MSG)-1][1] = "<font color=\"#FF9000\">bis Ankunft einer <a href=\"fleets.php?action=detail&id=$actions[id]\" style=\"color:#FF9000\">Flotte</a> ". (($actions['f_name']) ?  "»". $inhalt ."«" : "") ." von $buildings[city] in $actions[f_target] ". (($fUser->getScreenName()) ? ("(". $fUser->getScreenName() .")") : "") ."</font>";
          break;
        }
        case "attack_back" :
        {
          $MSG[sizeof($MSG)][0] = $actions['f_arrival'];
          $MSG[sizeof($MSG)-1][1] = "<font color=\"#FF9000\">bis R&uuml;ckkehr einer <a href=\"fleets.php?action=detail&id=$actions[id]\" style=\"color:#FF9000\">Flotte</a> ". (($actions['f_name']) ?  "»". $inhalt ."«" : "") ." nach $buildings[city] von $actions[f_target] ". (($fUser->getScreenName()) ? ("(". $fUser->getScreenName() .")") : "") ."</font>";
          break;
        }
        case "transport" :
        {
          $MSG[sizeof($MSG)][0] = $actions['f_arrival'];
          $MSG[sizeof($MSG)-1][1] = "<font color=\"#FFFF00\">bis Ankunft einer <a href=\"fleets.php?action=detail&id=$actions[id]\" style=\"color:#FFFF00\">Flotte</a> ". (($actions['f_name']) ?  "»". $inhalt ."«" : "") ." von $buildings[city] in $actions[f_target] ". (($fUser->getScreenName()) ? ("(". $fUser->getScreenName() .")") : "") ."</font>";
          break;
        }
        case "transport_back" :
        {
          $MSG[sizeof($MSG)][0] = $actions['f_arrival'];
          $MSG[sizeof($MSG)-1][1] = "<font color=\"#FFFF00\">bis R&uuml;ckkehr einer <a href=\"fleets.php?action=detail&id=$actions[id]\" style=\"color:#FFFF00\">Flotte</a> ". (($actions['f_name']) ?  "»". $inhalt ."«" : "") ." nach $buildings[city] von $actions[f_target] ". (($fUser->getScreenName()) ? ("(". $fUser->getScreenName() .")") : "") ."</font>";
          break;
        }
        case "plane_sell" :
        {
          $MSG[sizeof($MSG)][0] = $actions['f_arrival'];
          $MSG[sizeof($MSG)-1][1] = "<font color=\"#FFFF00\">bis Ankunft einer <a href=\"fleets.php?action=detail&id=$actions[id]\" style=\"color:#FFFF00\">Flugzeughandel-Flotte</a> von $buildings[city] im Hauptlager</font>";
          break;
        }
        case "plane_buy" :
        {
          $MSG[sizeof($MSG)][0] = $actions['f_arrival'];
          $MSG[sizeof($MSG)-1][1] = "<font color=\"#FFFF00\">bis Ankunft einer <a href=\"fleets.php?action=detail&id=$actions[id]\" style=\"color:#FFFF00\">Flugzeughandel-Flotte</a> vom Hauptlager in $buildings[city]</font>";
          break;
        }
      }
    }
    
    if ($actions['f_arrival'] <= time() && $actions['f_arrival']!=0)
    {
      $fUser = new User($actions['f_target_user']);
      switch ($actions['f_action'])
      {
        case "sell_to_depot" :
        {
          $MSG[sizeof($MSG)][0] = $actions['f_arrival'];
          $MSG[sizeof($MSG)-1][1] = "<font color=\"#DDDDDD\">bis Ankunft einer <a href=\"fleets.php?action=detail&id=$actions[id]\" style=\"color:#DDDDDD\">Flotte</a> ($buildings[city]) im Hauptlager</font>";
          break;
        }
        case "sell_from_depot" :
        case "attack_back" :
        case "transport_back" :
        case "plane_buy" :
        {
          $MSG[sizeof($MSG)][0] = $actions['f_arrival'];
          $MSG[sizeof($MSG)-1][1] = "<font color=\"#DDDDDD\">Eine <a href=\"fleets.php?action=detail&id=$actions[id]\" style=\"color:#DDDDDD\">Flotte</a>". (($actions['f_name'] != "") ?  " (". $inhalt .")" : "") ." ($buildings[city]) landete und ist auf dem Weg zur&uuml;ck in den Hangar</font>";
          break;
        }
        case "attack" :
        {
          $MSG[sizeof($MSG)][0] = $actions['f_arrival'];
          $MSG[sizeof($MSG)-1][1] = "<font color=\"#DDDDDD\">Eine <a href=\"fleets.php?action=detail&id=$actions[id]\" style=\"color:#DDDDDD\">Flotte</a>". (($actions['f_name'] != "") ?  " (". $inhalt .")" : "") ." k&auml;mpft gerade in $actions[f_target] ". (($fUser->getScreenName()) ? ("(". $fUser->getScreenName() .")") : "") ."</font>";
          break;
        }
        case "transport" :
        {
          $MSG[sizeof($MSG)][0] = $actions['f_arrival'];
          $MSG[sizeof($MSG)-1][1] = "<font color=\"#DDDDDD\">Eine <a href=\"fleets.php?action=detail&id=$actions[id]\" style=\"color:#DDDDDD\">Flotte</a>". (($actions['f_name'] != "") ?  " (". $inhalt .")" : "") ." &uuml;berbringt gerade Rohstoffe an ". (($fUser->getScreenName()) ? ("»". $fUser->getScreenName() ."«") : "") ." ($actions[f_target])</font>";
          break;
        }
        case "plane_sell" :
        {
          $MSG[sizeof($MSG)][0] = $actions['f_arrival'];
          $MSG[sizeof($MSG)-1][1] = "<font color=\"#DDDDDD\">Eine <a href=\"fleets.php?action=detail&id=$actions[id]\" style=\"color:#DDDDDD\">Flotte</a> liefert gerade Flugzeuge an das Hauptlager</font>";
          break;
        }
      }
    }
  }

  $main_actions = sql_query("SELECT holiday.time FROM holiday INNER JOIN city ON holiday.user = city.user WHERE city.ID='$_SESSION[city]'");
  while ($actions = sql_fetch_array($main_actions))
  {
    $MSG[sizeof($MSG)][0] = $actions['time'];
    $MSG[sizeof($MSG)-1][1] = "<font color=\"#0000FF\">bis Aktivierung des Urlaubsmodus</font>";
  }

  $main_actions = sql_query("SELECT actions.user,city.city,actions.f_action,actions.f_arrival,actions.f_name,actions.f_name_show FROM actions INNER JOIN city ON actions.city = city.ID WHERE actions.f_target='$_SESSION[city]'");
  while ($actions = sql_fetch_array($main_actions))
  {
    $actions['f_name'] = stripslashes($actions['f_name']);
    $inhalt = strip_tags ($actions['f_name']);
  	$inhalt = htmlentities ($inhalt);
  	
    $fUser = new User($actions['user']);
    if ($actions['f_action'] != "" && $actions['f_arrival'] > time())
    {
      switch ($actions['f_action'])
      {
        case "attack" :
        {
          $MSG[sizeof($MSG)][0] = $actions['f_arrival'];
          $MSG[sizeof($MSG)-1][1] = "<font color=\"#FF0000\">bis Ankunft einer feindlichen Flotte ".
          (($actions['f_name_show'] == "YES") ? "(". $inhalt .")" : "") ." von »". $fUser->getScreenName() ."« ($actions[city]) in $buildings[city]</font>";
          break;
        }
        case "transport" :
        {
          $MSG[sizeof($MSG)][0] = $actions['f_arrival'];
          $MSG[sizeof($MSG)-1][1] = "<font color=\"#00FF00\">bis Ankunft einer friedlichen Flotte ".
          (($actions['f_name_show'] == "YES") ? "(". $inhalt .")" : "") ." von »". $fUser->getScreenName() ."« ($actions[city]) in $buildings[city]</font>";
          break;
        }
      }
    }

    if (substr_count($actions['f_action'],"_back") == 0 && substr_count($actions['f_action'],"plane_") == 0 && substr_count($actions['f_action'],"_from_depot") == 0 && $actions['f_arrival'] <= time() && $actions['f_arrival']!=0)
    {
      $MSG[sizeof($MSG)][0] = $actions['f_arrival'];
      $MSG[sizeof($MSG)-1][1] = "<font color=\"#DDDDDD\">Eine ankommende Flotte erreichte gerade $buildings[city]</font>";
    }
  }

  $get_techs2 = sql_query("SELECT t_". implode(",t_",$t_db_name) ." FROM usarios WHERE ID='$_SESSION[user]'");
  $user_techs2 = sql_fetch_array($get_techs2);

  for ($i=0;$i<ANZAHL_DEFENSIVE;$i++)
    $defense_strength_d += $d_city[$i] * ($d_power[$i] + $t_increase[$d_tech[$i][T_POWER]] * $user_techs2[$d_tech[$i][T_POWER]]);

  for ($i=0;$i<ANZAHL_FLUGZEUGE;$i++)
    $defense_strength_p += $p_count[$i] * Party::getPlaneKW($p_tech[$i][T_POWER], $p_power[$i], $t_increase[$p_tech[$i][T_POWER]], $user_techs["t_{$t_db_name[$p_tech[$i][T_POWER]]}"]);

//  $defense_strength_s = round(Shield($buildings['b_shield'],$user_techs['t_shield_tech'],$buildings['c_active_shields']));
//  $defense_strength_s_std = round(Shield($buildings['b_shield'],$user_techs['t_shield_tech'],$buildings['b_shield']));

/** Limesurvey survey code
  if (!$_SESSION[sitt_login]) {
  $survey = sql_query("SELECT token FROM limesurvey.lime_tokens_22127 WHERE firstname='".addslashes($_SESSION[user])."' AND completed='N'");
  if (sql_num_rows($survey))
  {
    list($token) = sql_fetch_row($survey);
    $output .= "
      <tr>
        <td align='center' colspan='2' class='table_head'>
          ETS-Umfrage
        </td>
      </tr>
      <tr>
        <td align='center' colspan='2'>
        Die Verwaltung plant die n&auml;chste Revision von Erde II. Daf&uuml;r brauchen wir deine Unterst&uuml;tzung, Siedler ".$_SESSION[user]."!
        Bitte f&uuml;lle folgendes Formblatt 37A nach bestem Wissen und Gewissen aus. Vielen Dank!<br />
          <a href='http://umfrage.escape-to-space.de/index.php?lang=de-informal&sid=22127&token=".$token."'>Hier gehts zum Formblatt.</a>
        </td>
      </tr>
      <tr>
        <td align='center' colspan='2'>
          <br /><br />
        </td>
      </tr>";
  }
  }
*/

  /* if (!sql_num_rows($get_multi_user))
  {
    $output .= "
      <tr>
        <td align=center colspan=2 class=table_head>
          Multi-Schutz-Hinweis
        </td>
      </tr>
      <tr>
        <td align=center colspan=2>
          Sollten Sie Ihren Computer mit einem anderen ETS-Spieler teilen oder über eine gemeinsame Internetverbindung ETS spielen (z.B. über Router), so sollten Sie sich gemäß AGB §12 in diesem <a href=\"./ipregister.php\">Formular</a> anmelden.
        </td>
      </tr>
      <tr>
        <td align=center colspan=2>
          <br><br>
        </td>
      </tr>";
  }

  if (!$_SESSION[user_path])
  {
    $output .= "
        <tr>
          <td align=center colspan=2 class=table_head>
            Wichtig
          </td>
        </tr>
        <tr>
          <td align=center colspan=2>
            Laden Sie bitte die Grafiken von ETS auf Ihre Festplatte, damit Ihnen der schnellstm&ouml;gliche Seitenaufbau garantiert ist und der Server zugleich entlastet wird. <a href=$dir/preferences.php#download>Zum Download</a> (Diese Nachricht verschwindet, sobald die Grafiken von Ihrer Festplatte geladen werden)
          </td>
        </tr>
        <tr>
          <td align=center colspan=2>
            <br><br>
          </td>
        </tr>";
  } */

  /* summer camp attendance reward */
//  $get_campers = sql_query("SELECT 1 FROM camper2011 WHERE ID='$_SESSION[user]' AND available=0x01");
//  if (sql_num_rows($get_campers)) {
//    if (isset($_POST['reward'])) {
//        $get_planes = sql_query("SELECT p_gesamt_flugzeuge FROM city WHERE city='$_SESSION[city]'");
//        $city_planes = sql_fetch_array($get_planes);
//        $get_buildings = sql_query("SELECT b_hangar FROM city WHERE city='$_SESSION[city]' && ID='$_SESSION[user]'");
//        $buildings = sql_fetch_array($get_buildings);
//        if ($city_planes[p_gesamt_flugzeuge] + 10 > $buildings["b_hangar"]*8)
//        {
//            $errorMessage = "In deinem Hangar ist nicht genug Platz.";
//            $template->set('errorMessage', $errorMessage);
//        } else {
//        sql_query("UPDATE city SET p_gesamt_flugzeuge=p_gesamt_flugzeuge+10,p_bomber=p_bomber+10 WHERE city='$_SESSION[city]'");
//        sql_query("UPDATE camper2011 SET available=0x00 WHERE ID='$_SESSION[user]'");
//        $output .= '
//            <tr>
//              <td colspan="2" class="table_head">
//                Sommercamp-Speziallieferung
//              </td>
//            </tr>';
//            $output .= '
//                <tr>
//                  <td colspan="2">
//                  Deine Hesse-Bomber sind im Hangar eingetroffen.
//                  </td>
//                </tr>
//                <tr>
//                  <td colspan="2">
//                    <br /><br />
//                  </td>
//                </tr>';
//        }
//    } else {
//        $output .= '
//            <tr>
//              <td colspan="2" class="table_head">
//                Sommercamp-Speziallieferung
//              </td>
//            </tr>';
//            $output .= '
//                <tr>
//                  <td colspan="2">
//		  Glückwunsch! Du warst dies Jahr auf dem Sommercamp. Als kleine Erinnerung erhältst
//                  du 10 handgefertigte Spezialflugzeuge vom Typ Hesse-Bomber. Sobald du Platz im Hangar hast, drücke diesen Knopf und sie werden dir zugestellt.<br /><br />
//                    <form action="' . $_SERVER['PHP_SELF'] . '" method="post">
//                    <input class="button" type="submit" name="reward" value="10 Flieger einparken" />
//                    </form><br />
//                  </td>
//                </tr>
//                <tr>
//                  <td colspan="2">
//                    <br /><br />
//                  </td>
//                </tr>';
//    }
//  }


  /* start preregistration */
  /*
  if (!$_SESSION[sitt_login])
  {
      $get_preregister = sql_query("SELECT 1 FROM new_user WHERE ID='$_SESSION[user]'");

      if (isset($_POST['preregister']) && !sql_num_rows($get_preregister)) {
        if( preg_match('/^([0-9A-Za-z])+$/', $_SESSION[user]) )
            sql_query("INSERT INTO new_user (user,email) SELECT user,email FROM userdata WHERE ID='$_SESSION[user]'");
      }

      $get_preregister = sql_query("SELECT 1 FROM new_user WHERE ID='$_SESSION[user]'");

      if (!sql_num_rows($get_preregister)) {
       if (time() < mktime(20,0,0,16,9,2009) && $user_techs[points] > 30 && !sql_num_rows($get_preregister)) {
        $output .= '
            <tr>
              <td colspan="2" class="table_head">
                Namensreservierung für ETS 9.5
              </td>
            </tr>';
            $output .= '
                <tr>
                  <td colspan="2">
		  Ich möchte in der neuen Runde ETS 9.5 wieder unter meinem jetzigen Namen mitspielen.<br />
                    <form action="' . $_SERVER['PHP_SELF'] . '" method="post">
                    <input class="button" type="submit" name="preregister" value="Namen reservieren" />
                    </form><br />
                    Dieser Knopf steht bis zum 16.09. zur Verfügung. Kurz vor dem Neustart wird dir eine E-Mail mit der Anmeldebestätigung geschickt.
                  </td>
                </tr>
                <tr>
                  <td colspan="2">
                    <br /><br />
                  </td>
                </tr>';
      }
    }
  }
   */
  /* end preregistration */

  if (!$_SESSION['sitt_login'])
  {
  	$check_news_ereignisse = sql_query("SELECT Max(time) AS time,topic,Count(*) AS anzahl FROM news_er WHERE city='$_SESSION[city]' && seen='N' GROUP BY topic ORDER BY time DESC");
    $check_news_berichte = sql_query("SELECT attack_user, defense_user, attack_bid, defense_bid, f_name, f_name_show, time, art, attack_city, defense_city, colonize, error FROM news_ber WHERE (attack_user='$_SESSION[user]' AND attack_city='$_SESSION[city]' AND attack_seen='N') OR (defense_user='$_SESSION[user]' AND defense_city='$_SESSION[city]' AND defense_seen='N' AND defense_bid <> '') OR (defense_user='$_SESSION[user]' AND colonize='Y' AND defense_seen='N') ORDER BY time DESC");
  }
  else
  {
  	$check_news_ereignisse = sql_query("SELECT Max(time) AS time,topic,Count(*) AS anzahl FROM news_er WHERE city='$_SESSION[city]' && seen_sitter='N' && seen='N' GROUP BY topic ORDER BY time DESC");
    $check_news_berichte = "SELECT attack_user, defense_user, attack_bid, defense_bid, f_name, f_name_show, time, art, attack_city, defense_city, colonize, error FROM news_ber WHERE (attack_user='$_SESSION[user]' AND attack_city='$_SESSION[city]' AND attack_seen_sitter='N' AND attack_seen='N') OR (defense_user='$_SESSION[user]' AND defense_city='$_SESSION[city]' AND defense_seen_sitter='N' AND defense_seen='N' AND defense_bid <> '') OR (defense_user='$_SESSION[user]' AND colonize='Y' AND defense_seen='N' AND defense_seen_sitter='N') ORDER BY time DESC";
  	$check_news_berichte = sql_query($check_news_berichte);
  }

  if (sql_num_rows($check_news_ereignisse) || sql_num_rows($check_news_berichte))
  {
    $output .= "<tr>
            <td align=center colspan=2 class=table_head>
              Nachrichten
            </td>
          </tr>";

    while ($show_news_ereignisse = sql_fetch_array($check_news_ereignisse))
    {
      $output .= "<tr>
              <td colspan=2>
                ". ETSZeit($show_news_ereignisse['time']) ."
                <br>
                ". (($show_news_ereignisse['anzahl'] > 1) ? "$show_news_ereignisse[anzahl]x " : "") ."". $show_news_ereignisse['topic'] ."
              </td>
            </tr>";
    }

    while ($show_news_berichte = sql_fetch_array($check_news_berichte))
    {
    	$fUser = sql_fetch_array ( sql_query ( "SELECT user,name_affix FROM userdata WHERE ID='$show_news_berichte[defense_user]'"));
    	$aUser = sql_fetch_array ( sql_query ( "SELECT user,name_affix FROM userdata WHERE ID='$show_news_berichte[attack_user]'"));
    	$def = sql_fetch_array ( sql_query ( "SELECT city FROM city WHERE ID = '$show_news_berichte[defense_city]';"));
    	$att = sql_fetch_array ( sql_query ( "SELECT city FROM city WHERE ID = '$show_news_berichte[attack_city]';"));
    	
    	if($show_news_berichte[art] == "attack" && $show_news_berichte[attack_city] == $_SESSION[city]) $topic = "Eine Flotte nach $def[city] ($fUser[user] $fUser[name_affix]) erreichte Ihr Ziel"; 
	    if($show_news_berichte[art] == "attack" && $show_news_berichte[defense_city] == $_SESSION[city]) $topic = "Eine Flotte von $att[city] ($aUser[user] $aUser[name_affix]) erreichte Ihre Stadt";
	    if($show_news_berichte[art] == "attack_back") $topic = "Eine Flotte ($att[city]) kehrte von $def[city] ($fUser[user] $fUser[name_affix]) zurück";
	    if($show_news_berichte[art] == "plane_buy") $topic = "Eine Flugzeughandel-Flotte vom Hauptlager erreichte $att[city]";
	    if($show_news_berichte[art] == "plane_sell") $topic = "Eine Flugzeughandel-Flotte von $att[city] erreichte das Hauptlager";
	    if($show_news_berichte[art] == "sell_to_depot") $topic = "Eine Rohstoffhandel-Flotte ($att[city]) erreichte das Hauptlager";
	    if($show_news_berichte[art] == "sell_from_depot") $topic = "Eine Rohstoffhandel-Flotte vom Hauptlager erreichte $att[city]";
	    if($show_news_berichte[art] == "transport" && $show_news_berichte[attack_city] == $_SESSION[city]) $topic = "Eine Flotte von $att[city] ($aUser[user] $aUser[name_affix]) überbrachte an $def[city] ($fUser[user] $fUser[name_affix])";
	    if($show_news_berichte[art] == "transport" && $show_news_berichte[defense_city] == $_SESSION[city]) $topic = "Eine Flotte von $att[city] ($aUser[user] $aUser[name_affix]) lieferte Ihnen auf $def[city] ($fUser[user] $fUser[name_affix])";
	    if($show_news_berichte[art] == "transport_back") $topic = "Eine Flotte ($att[city]) kehrte von $def[city] ($fUser[user] $fUser[name_affix]) zurück";
      	if($show_news_berichte[art] == "scan" && $show_news_berichte[attack_user] == $_SESSION[user]) $topic = "Eine Flotte nach $def[city] ($fUser[user] $fUser[name_affix]) erreichte Ihr Ziel"; 
	    if($show_news_berichte[art] == "scan" && $show_news_berichte[defense_user] == $_SESSION[user]) $topic = "Eine Flotte von $att[city] ($aUser[user] $aUser[name_affix]) erreichte Ihre Stadt";
	    if($show_news_berichte[art] == "attack" && $show_news_berichte[attack_user] == $_SESSION[user] && $show_news_berichte[colonize] == "Y") $topic = "$def[city] wurde erfolgreich erobert"; 
	    if($show_news_berichte[art] == "attack" && $show_news_berichte[defense_user] == $_SESSION[user] && $show_news_berichte[colonize] == "Y") $topic = "Sie haben Ihre Stadt $def[city] an $aUser[user] $aUser[name_affix] verloren";
	    if($show_news_berichte[art] == "attack" && $show_news_berichte[attack_user] == $_SESSION[user] && $show_news_berichte[colonize] == "Y" && $show_news_berichte[error] == "Settler") $topic = "Sie haben erfolgreich eine neue Stadt gegründet";
	   
	    if($show_news_berichte[defense_bid] != "" || $show_news_berichte[attack_user] == $_SESSION[user]) {
	    $output .= "<tr>
              <td colspan=2>
                ". ETSZeit($show_news_berichte['time']) ."
                <br>";
      		if($show_news_berichte[attack_user] == $_SESSION[user]) 
      		{
      			$output .= "<a href=\"messages_berichte.php?bid=$show_news_berichte[attack_bid]\" target=bericht onclick=\"window.open('messages_berichte.php?bid=$show_news_berichte[attack_bid]','bericht','width=700,height=580,location=yes,resizable=yes,scrollbars=yes');return false\">
      			$topic</a>";
      		}
			else 
			{
				if($show_news_berichte[defense_bid] != "") {
					$output .= "<a href=\"messages_berichte.php?bid=$show_news_berichte[defense_bid]\" target=bericht onclick=\"window.open('messages_berichte.php?bid=$show_news_berichte[defense_bid]','bericht','width=700,height=580,location=yes,resizable=yes,scrollbars=yes');return false\">
					$topic</a>";
				}
			}
			$output .= "</td>
            </tr>";
	    }
    }

    if (!$_SESSION['sitt_login'])
    {
   	  sql_query("UPDATE news_er SET seen='Y' WHERE city='$_SESSION[city]' && seen='N'");
      $up1 = "UPDATE news_ber SET attack_seen='Y' WHERE attack_user='$_SESSION[user]' && attack_city='$_SESSION[city]' && attack_seen='N'";
      $up2 = "UPDATE news_ber SET defense_seen='Y' WHERE (defense_user='$_SESSION[user]' && defense_city='$_SESSION[city]' && defense_seen='N') OR (defense_user='$_SESSION[user]' AND defense_seen='N' AND colonize='Y')";
      sql_query($up1);
      sql_query($up2);
    }
    else
    {
      sql_query("UPDATE news_er SET seen_sitter='Y' WHERE city='$_SESSION[city]' && seen_sitter='N' && seen='N'");
      $up1 = "UPDATE news_ber SET attack_seen_sitter='Y' WHERE attack_user='$_SESSION[user]' && attack_city='$_SESSION[city]' && attack_seen_sitter='N'";
      $up2 = "UPDATE news_ber SET defense_seen_sitter='Y' WHERE (defense_user='$_SESSION[user]' && defense_city='$_SESSION[city]' && defense_seen_sitter='N') OR (defense_user='$_SESSION[user]' AND defense_seen_sitter='N' AND colonize='Y')";
      sql_query($up1);
      sql_query($up2);
    }

    $output .= "
        </td>
      </tr>
      <tr>
        <td colspan=2>
          <br><br>
        </td>
      </tr>";
  }

  $output .= "
      <tr class=table_head>
        <td align=center colspan=2>
        	Auftr&auml;ge (Serverzeit: ". date("H:i",time()) ."<font class=seconds>:". date("s",time()) ."</font> ". date("d.m.Y",time()) .")
        </td>
      </tr>";

  sort($MSG);
  for ($i=0;$i<sizeof($MSG);$i++)
  {
    $output .= "
        <tr>
          <td colspan=2>";

    if ($MSG[$i][0] > time())
      $output .= "Noch ". maketime($MSG[$i][0] - time()) ." (". ETSZeit($MSG[$i][0]) .")<br>";
    else
      $output .= "<i>". ETSZeit($MSG[$i][0]) ."<br>";

    $output .=    $MSG[$i][1] ."</i>
          </td>
        </tr>";
  }
  if ($i == 0)
  {
    $output .= "
        <tr>
          <td colspan=2>
            Zur Zeit keine Auftr&auml;ge
          </td>
        </tr>";
  }

  $get_admin_details = sql_query("SELECT city_name,pic FROM city WHERE ID='$_SESSION[city]'");
  $admin_details = sql_fetch_array($get_admin_details);

  // Display failed login attempts in last 24 hours.
  if(isset($_POST['email'])) {
    list($lge) = sql_fetch_row( sql_query('SELECT count(*) AS c FROM `logs_login` WHERE identity="'.addslashes($_POST['email']).'" AND time>subdate(now(), interval 24 hour)') );
    if($lge > 0) {
        $loginerror = '
        <table border=0 cellpadding=3 cellspacing=3>
          <tr>
            <td colspan=2 align=center class=table_head>
              ACHTUNG!
            </td>
          </tr>
          <tr>
            <td colspan=2>
              <font color="#FF0000">Es gab '.$lge.' erfolglose Login-Versuche in den letzten 24 Stunden!</font>
            </td>
        </table>';
    }
  }

  // Display list of attacked cities.
  $attacks = sql_query('SELECT DISTINCT city.city FROM actions INNER JOIN city ON actions.f_target = city.ID WHERE actions.f_action="attack" AND actions.f_target_user="'.$_SESSION['user'].'"');
  if(sql_num_rows($attacks) && $_SESSION['show_attacks']) {
      $attack = '
      <table border=0 cellpadding=3 cellspacing=3>
        <tr>
          <td colspan=2 align=center class=table_head>
            Zur Beachtung!
          </td>
        </tr>
        <tr>
          <td colspan=2>';
      while($a = sql_fetch_row($attacks))
        $attack .= '    <font color="#FF0000">Deine Stadt »'.$a[0].'« wird angegriffen!</font><br />';
      $attack .= '
          </td>
      </table>';
  }
  // Views per login per day
  $views = "";
  $time_views = time();
  $time_views = $time_views - 24*60*60;
  if(!$_SESSION["sitt_login"]) {
		$updated = "no";
		  if($user_techs["last_views"] < $time_views && $user_techs["alliance"]<>'') {
		  	$user_techs["following_logins"]++;
		  	if(VIEWS_MAX_DAY < $user_techs['following_logins']) 
		  			$logins = VIEWS_MAX_DAY;
		  	else
		  			$logins = $user_techs['following_logins'];
		  			
		  	$view = $logins*VIEWS;
		  			
		  	sql_query("UPDATE alliances SET ads_credit = ads_credit+$view WHERE ID='$user_techs[alliance]';");
		  	sql_query("UPDATE usarios SET last_views='" . time() . "', following_logins=following_logins+1 WHERE ID='$_SESSION[user]';");
		  	
		  	$views = "<br><font color='#32CD32'><div align='center'>Du hast dich $user_techs[following_logins] Tage hintereinander eingeloggt!<br>
		  			Dafür hat deine Allianz <b>$view</b> Views bekommen.</div></font><br>";
		  	$updated = "yes";
		  }
		  if($user_techs["last_views"] < $time_views && $user_techs["alliance"] == "") {
		  	$user_techs["following_logins"]++;
		  	sql_query("UPDATE usarios SET last_views='" . time() . "', following_logins=following_logins+1 WHERE ID='$_SESSION[user]';");
		  	$views = "<br><font color='#32CD32'><div align='center'>Du hast dich $user_techs[following_logins] Tage hintereinander eingeloggt!<br>
		  			Als Mitglied einer Allianz hättest du deiner Allianz heute $view Views beschert.</div></font><br>";
		  	$updated = "yes";
		  }
		  if ($updated == "yes") {
			$user_medals = sql_fetch_array(sql_query("SELECT m_login FROM medals WHERE user='$_SESSION[user]'"));
			if ($user_medals[m_login] < count($medal_values[$medaillen[LOGIN]])) {
				if ($user_techs["following_logins"] >= $medal_values[$medaillen[LOGIN]][$user_medals[m_login]]) {
					sql_query("UPDATE medals SET m_login=m_login+1,d_login='".time()."' WHERE user='$_SESSION[user]'");
					$username = sql_fetch_array(sql_query("SELECT user FROM usarios WHERE ID='$_SESSION[user]'"));
					$user_medals[m_login]++;
					sql_query("INSERT INTO news_igm_umid (sender,recipient,owner,time,topic,text,dir) VALUES ('ETS','$_SESSION[user]','$_SESSION[user]','".time()."','".MEDAL_TOPIC."','".MEDAL_HALLO.$username[user].MEDAL_TEXT.$medal_text[$medaillen[LOGIN]].$user_medals[m_login]."','0')" );
				}
			}
		  }
  }
  
  $votes = sql_query("SELECT votes FROM usarios WHERE ID='$_SESSION[user]'");
  $votes = sql_fetch_array($votes);
  $pfuschOutput .= "      <h1>Hauptansicht</h1>";
  $pfuschOutput .= $loginerror;
  $pfuschOutput .= $attack;
  $pfuschOutput .= $views;
  $pfuschOutput .= "      <table border=0 cellpadding=3 cellspacing=3>";
  $pfuschOutput .= $output;
  if($votes['votes'] == 1 && !$_SESSION[sitt_login]) {
  		$pfuschOutput .= 	"<br><br><div style='background: rgb(255, 0, 0);background: rgba(255, 0, 0, 0.5);border: 1px solid red;-webkit-border-radius: 5px;-khtml-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;padding: 5px;'><b>";
  		$pfuschOutput .= "<a href='votes.php'><font color='#FFFFFF'>Aktuell findet eine globale Umfrage statt. Hilf auch du ETS zu verbessern und nimm teil!</font></a>";
  		$pfuschOutput .= "</b></div>"; 
  }  
  
  // Asteroids
  $select = "SELECT `start`, `duration` FROM asteroids WHERE `started` = 'started' ORDER BY `start` DESC LIMIT 1";
  $select = sql_query($select);
  $select = sql_fetch_array($select);
  if($select['start']) {
	  $pfuschOutput .= "<br><br><div style='background: rgb(255, 0, 0);background: rgba(255, 0, 0, 0.5);border: 1px solid red;-webkit-border-radius: 5px;-khtml-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;padding: 5px;'><b>";
	  $pfuschOutput .= "Ein Asteroid ist im Anflug Richtung Erde II. Der Einschlag könnte verheerende Folgen für alle Erdenbewohner haben! Hilf bei der Abwehr und starte nun auf die Koordinaten 0:0:0! Einer für Alle, Alle für Einen!";
	  $pfuschOutput .= "</b></div>";  	
  }
  
  // Artefakte
  $select = "SELECT `start`, `duration` FROM artefakte WHERE `started` = 'started' ORDER BY `start` DESC LIMIT 1";
  $select = sql_query($select);
  $select = sql_fetch_array($select);
  if($select['start']) {
	  $pfuschOutput .= "<br><br><div style='background: rgb(255, 0, 0);background: rgba(255, 0, 0, 0.5);border: 1px solid red;-webkit-border-radius: 5px;-khtml-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;padding: 5px;'><b>";
	  $pfuschOutput .= " Forscher haben auf Erde II eine riesige bisher unentdeckt gebliebene Stadt aufgespürt. Dieses Artefakt könnte von unschätzbarem Wert für die Menschheit sein. Hilf auch Du mit bei der Eroberung des Artefakts und starte nun auf die Koordinaten 0:0:0! Einer für Alle, Alle für Einen!";
	  $pfuschOutput .= "</b></div>";  	
  }
  
  
  
  $pfuschOutput .= "
      <tr>
        <td colspan=2>
          <br><br>
        </td>
      </tr>
      <tr>
        <td colspan=2 align=center class=table_head>
          Stadt $buildings[city]
        </td>
      </tr>";

  if ($admin_details['pic'] != "")
  {
    $pfuschOutput .= "
      <tr>
        <td colspan=2 align=center>
          <img src=\"$admin_details[pic]\">
        </td>
      </tr>
      <tr>
        <td colspan=2>
          <br>
        </td>
      </tr>";
  }
    $pfuschOutput .= "  <tr>
        <td>
          <b>Stadt-Name</b>
        </td>
        <td align=right>
          <b>$admin_details[city_name]</b>
        </td>
      </tr>
      <tr>
        <td>
          Kontinent:Land:Stadt
        </td>
        <td align=right>
          $buildings[city]
        </td>
      </tr>
      <tr>
        <td>
          Punkte
        </td>
        <td align=right>
          $buildings[points]
        </td>
      </tr>
      <tr>
        <td colspan=2>
          <br>
        </td>
      </tr>
      <tr>
        <td>
          Kampfwert Defensivanlagen
        </td>
        <td align=right>".
          number_format($defense_strength_d,0,',','.')
        ."</td>
      </tr>
      <tr>
        <td>
          Kampfwert Flugzeuge
        </td>
        <td align=right>".
          number_format($defense_strength_p,0,',','.')
        ."</td>
      </tr>";
      // no shield
//      <tr>
//        <td>
//          Kampfwert Schutzschild (max.)
//        </td>
//        <td align=right>".
//          number_format($defense_strength_s,0,',','.')." (".number_format($defense_strength_s_std,0,',','.').")
//        </td>
//      </tr>";

  $defense_strength_n = NewbieDef($buildings['points']);
  if($defense_strength_n > 0) 
  {
    $pfuschOutput .= "  <tr>
        <td>
          Grundwert (Kampfwert)
        </td>
        <td align=right>".
          number_format($defense_strength_n,0,',','.')
        ."</td>
      </tr>";
  }
    $pfuschOutput .= "
      <tr>
        <td>
          Kampfwert gesamt
        </td>
        <td align=right>
          ". number_format($defense_strength_d + $defense_strength_p + $defense_strength_n,0,',','.') ."
        </td>
      </tr>
      <tr>
        <td colspan=2>
          <br><br>
        </td>
      </tr>
      <tr>
        <td colspan=2 align=center class=table_head>
          Siedler »".$thisUser->getScreenName()."«
        </td>
      </tr>
      <tr>
        <td>
          <b>Punkte</b>
        </td>
        <td align=right>
          <b>$user_techs[points]</b>
        </td>
      </tr>
      <tr>
        <td>
          &nbsp;&nbsp;&nbsp;Geb&auml;ude
        </td>
        <td align=right>
          ". ($user_techs['points'] - $user_techs['tech_points']) ."
        </td>
      </tr>
      <tr>
        <td>
          &nbsp;&nbsp;&nbsp;Technologien
        </td>
        <td align=right>
          $user_techs[tech_points]
        </td>
      </tr>";

    $pfuschOutput .= "    <tr>
          <td colspan=2>
            <br><br>
          </td>
        </tr>
        <tr>
          <td colspan=2 align=center class=table_head>
            Wichtige Information
          </td>
        </tr>
        <tr>
          <td colspan=2 align=center>
            Die Berechnung der Flotten und der Bau von Verteidigungsanlagen und Flugzeugen geschieht im 1-Minuten-Takt vom Server ausgehend. Dadurch können Kampfberichte etc. zeitversetzt zum eigentlichen Ankunftszeitpunkt ankommen.
          </td>
        </tr>
        </table>";


 // end specific page logic


  // add pfusch output
  $template->set('pfuschOutput', $pfuschOutput);

  // include common template settings
  require_once("include/JavaScriptCommon.php");
  require_once("include/TemplateSettingsCommon.php");

  // save resource changes (ToDo: Is this necessary on every page?)
  $timefixed_depot->save();

  // create html page
  try {
    echo $template->execute();
  }
  catch (Exception $e) { echo $e->getMessage(); }

?>
