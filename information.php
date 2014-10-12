<?php
  $use_lib = 15; // MSG_INFORMATION

  require_once("msgs.php");
  require_once("database.php");
  require_once("constants.php");
  require_once("functions.php");
  require_once("do_loop.php");

  // define phptal template
  require_once("PHPTAL.php");
  require_once("include/PHPTAL_EtsTranslator.php");
  //$template = new PHPTAL('information.html');
  $template = new PHPTAL('theme_blue_line.html');
  $template->setTranslator(new PHPTAL_EtsTranslator());
  $template->setEncoding('ISO-8859-1');
  $template->set('contentMacroName', 'information.html/content');

  // set page title
  $template->set('pageTitle', 'Übersichten - Städte');

  $pfuschOutput = "";

  $t_medals = "";
  $medal_size = "62";
  
 // insert specific page logic here

  $type = $_GET['type'];
  $name = $_GET['name'];

  $t_ally = 0;
  $t_city = 0;
  $t_user = 0;

  switch($type)
  {
    // Allianz-Infoseite
    case "a":
    {
      require_once 'include/class_Krieg.php';

      $template->set('pageTitle', 'Übersichten - Allianzinfo');
      $get_information = sql_query("SELECT tag,name,pic,link,members,points,military_alliances,trade_alliances,naps,enemies,text,power,fame FROM alliances WHERE tag='". htmlspecialchars($name,ENT_QUOTES) ."'");

      if (!sql_num_rows($get_information))
        ErrorMessage(MSG_WORK_BOARD,e002);  // Diese Stadt gibt es nicht
      if (ErrorMessage(0))
      {
        $errorMessage .= "  <h1>{$MESSAGES[MSG_WORK_BOARD][m000]}</h1>";
        $errorMessage .= ErrorMessage();

        // add error output
        $template->set('errorMessage', $errorMessage);

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
        die();
      }

      $information = sql_fetch_array($get_information);
      $krieg = new Krieg($information['tag']);

      $t_ally = array();
      $get_keeper = sql_query("SELECT user FROM donations WHERE user='". $information['tag'] ."'");
      $t_ally['isKeeper'] = (sql_num_rows($get_keeper) > 0) ? 1 : 0 ;
      $t_ally['name'] = $information['name'];
      $t_ally['tag'] = $information['tag'];
      $t_ally['link'] = strpos($information['link'],'http://')===0 ? $information['link'] : 'http://'.$information['link'];
      $t_ally['image'] = $information['pic'];
      $t_ally['points'] = $information['points'];
      $t_ally['power'] = $information['power'];
      $t_ally['fame'] = $information['fame'];
      $t_ally['members'] = $information['members'];
      $t_ally['military_alliances'] = BBCode($information['military_alliances']);
      $t_ally['trade_alliances'] = BBCode($information['trade_alliances']);
      $t_ally['naps'] = BBCode($information['naps']);
      $t_ally['enemies'] = BBCode($information['enemies']);
      $t_ally['text'] = BBCode($information['text']);
      $t_ally['open_wars'] = $krieg->getWars(Krieg::TYPE_OPEN);
      $t_ally['won_wars'] = $krieg->getWars(Krieg::TYPE_WON);
      $t_ally['lost_wars'] = $krieg->getWars(Krieg::TYPE_LOST);
      break;
    }

    // Stadt-Infoseite
    case "c":
    {
      $template->set('pageTitle', 'Übersichten - Stadtinfo');
      $get_information = sql_query("SELECT user,city,x_pos,y_pos,city_name,pic,points,alliance,text FROM city WHERE user <> 'Tutorial' AND city='". htmlspecialchars($name,ENT_QUOTES) ."'");

      if (!sql_num_rows($get_information))
        ErrorMessage(MSG_WORK_BOARD,e001);  // Diese Stadt gibt es nicht
      if (ErrorMessage(0))
      {
        $errorMessage .= "  <h1>{$MESSAGES[MSG_WORK_BOARD][m000]}</h1>";
        $errorMessage .= ErrorMessage();

        // add error output
        $template->set('errorMessage', $errorMessage);

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
        die();
      }

      $information = sql_fetch_array($get_information);
      $alliance = sql_fetch_array(sql_query("SELECT tag FROM alliances WHERE ID='$information[alliance]'"));

      $get_history = sql_query("SELECT city,owner,time,user FROM city_history WHERE owner <> 'Tutorial' AND city='". htmlspecialchars($name,ENT_QUOTES) ."' ORDER BY time ASC");
      $t_history = array();
      $i = 0;
      while($history = sql_fetch_array($get_history))
      {
        $t_history[$i]['date'] = strftime('%d.%m.%Y', $history['time']);
        $username=sql_fetch_array(sql_query("SELECT user FROM usarios WHERE ID='".$history['user']."'"));
        if ($_SESSION[user] == "89") {
		echo "<pre>";
		print_r($username);
		print_r($history);
		echo "</pre>";
        }
        $t_history[$i]['owner'] = $username['user'];
        ++$i;
      }

      $tUser = new User($information['user']);
      $t_city = array();
      $t_city['name'] = $information['city_name'];
      $t_city['owner'] = $tUser->getName();
      $t_city['owner_affix'] = $tUser->getAffix();
      $t_city['image'] = $information['pic'];
      $t_city['coords'] = $information['city'];
      $t_city['continent'] = $information['x_pos'];
      $t_city['country'] = $information['y_pos'];
      $t_city['points'] = $information['points'];
      $t_city['alliance'] = $alliance['tag'];
      $t_city['text'] = BBCode($information['text']);
      $t_city['history'] = $t_history;

      break;
    }

    // User-Infoseite
    case "u":
    {
      $template->set('pageTitle', 'Übersichten - Siedlerinfo');
      $get_information = sql_query("SELECT usarios.ID,userdata.user,usarios.points,usarios.alliance,usarios.text,usarios.power,usarios.fame,usarios.flightstats,usarios.medals FROM usarios INNER JOIN userdata ON usarios.ID = userdata.ID WHERE userdata.user <> 'Tutorial' AND userdata.user='". addslashes($name) ."'");

      if (!sql_num_rows($get_information))
        ErrorMessage(MSG_WORK_BOARD,e000);  // Diesen User gibt es nicht
      if (ErrorMessage(0))
      {
        $errorMessage .= "  <h1>{$MESSAGES[MSG_WORK_BOARD][m000]}</h1>";
        $errorMessage .= ErrorMessage();

        // add error output
        $template->set('errorMessage', $errorMessage);

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
        die();
      }

      $information = sql_fetch_array($get_information);
      $alliance = sql_fetch_array(sql_query("SELECT tag FROM alliances WHERE ID='$information[alliance]'"));

      $get_cities = sql_query("SELECT city.city,city.city_name,city.home,city.x_pos,city.y_pos FROM city INNER JOIN userdata ON city.user = userdata.ID WHERE userdata.user <> 'Tutorial' AND userdata.user='$information[user]' ORDER BY city.foundation");
      $t_cities = array();
      $i = 0;
      while($city = sql_fetch_array($get_cities))
      {
        $t_cities[$i]['isCapital'] = ($city['home'] == 'YES') ? 1 : 0 ;
        $t_cities[$i]['coords'] = $city['city'];
        $t_cities[$i]['city_name'] = $city['city_name'];
        $t_cities[$i]['continent'] = $city['x_pos'];
        $t_cities[$i]['country'] = $city['y_pos'];
        $t_cities[$i]['city_name'] = $city['city_name'];
        ++$i;
      }

      $tUser = new User($information['ID']);
      $t_user = array();
      $get_keeper = sql_query("SELECT user FROM donations WHERE user='". $information['user'] ."'");
      $t_user['isKeeper'] = (sql_num_rows($get_keeper) > 0) ? 1 : 0 ;
      $t_user['name'] = $tUser->getName();
      $t_user['name_affix'] = $tUser->getAffix();
      $t_user['points'] = $information['points'];
      $t_user['power'] = $information['power'];
      $t_user['fame'] = $information['fame'];
      $t_user['alliance'] = $alliance['tag'];
      $t_user['text'] = BBCode($information['text']);
      $t_user['cities'] = $t_cities;

      $t_medals = "<br>";
      $player_alliance = sql_fetch_array(sql_query("SELECT alliance FROM usarios WHERE ID='$_SESSION[user]'"));
      // $information['medals'] == 1 =====> Medaillen für alle Sichtbar
      // $information['medals'] == 2 =====> Medaillen nur für die eigenen Allianzmitglieder sichtbar
      // Ansonsten nur für sich selbst sichbar
      // Informationstext zur Sicherbarkeit hinzugefügt 19.06.2014
      if (($information['ID'] == $_SESSION['user']) ||
      	  (intval($information['medals']) == 1) || 
         ((intval($information['medals']) == 2) && 
         ($information['alliance'] == $player_alliance['alliance']) && 
         $information['alliance'] != "")) {
      		$user_medals = sql_fetch_array(sql_query("SELECT * FROM medals WHERE user='$information[ID]'"));
       		$medal_color = "economy";
       		if($information['medals'] == 1 && $information['ID'] == $_SESSION['user'])  
       				$t_medals .= "Deine Medaillen sind für ALLE Sichtbar<br><br>";
       		elseif($information['medals'] == 2 && $information['ID'] == $_SESSION['user']) 
       				$t_medals .= "Deine Medaillen sind nur für deine Allianz Sichtbar<br><br>";
       		elseif($information['ID'] == $_SESSION['user'])
       				$t_medals .= "Deine Medaillen sind nur für DICH Sichtbar<br><br>";
       		$t_medals .= "<b>Wirtschaftsmedaillen</b><br>";
       		$medaille_vorhanden = 0;
       		foreach ($medaillen as $medal) {
				//Neue Zeile, da nun Kriegsmedaillen kommen.
				if ($medal == MEDAL_WAR_1) {
					if ($medaille_vorhanden == 0) {
						$t_medals .= "In dieser Kategorie sind noch keine Medaillen vorhanden.";
					} else { $medaille_vorhanden = 0; }
					$t_medals .= "<br><b>Kriegsmedaillen</b><br>";
					$medal_color = "war";
				} else if ($medal == MEDAL_ALLIANCE_1) {
					if ($medaille_vorhanden == 0) {
						$t_medals .= "In dieser Kategorie sind noch keine Medaillen vorhanden.";
					} else { $medaille_vorhanden = 0; }
					$t_medals .= "<br><b>Allianzmedaillen</b><br>";
					$medal_color = "alliance";
				}
       
				if ($medal == $medaillen[TUTORIAL]) {
					$get_tutorial = sql_fetch_array(sql_query("SELECT tutorial FROM new_tutorial WHERE user='". $information['ID'] . "'"));
					// echo "<pre>User ".$information['ID']. " mit dem Wert: " . $get_tutorial['tutorial'] . "</pre>";
					if (intval($get_tutorial['tutorial']) >= TUTORIAL_MAX) {
						$t_medals .= "<img src='pics/medals.php?medal=".$medal_color."&symbol=" . $medal . "&size=" . $medal_size . "' alt='". $medal_text[$medal] . "' title='" . $medal_text[$medal] ."'>";
						$medaille_vorhanden = 1;
					}
				}else{
					if (intval($user_medals["m_$medal"]) >= 1) {
						$t_medals .= "<img src='pics/medals.php?medal=".$medal_color."&symbol=" . $medal ."&nr=".$user_medals["m_$medal"]."&size=" . $medal_size . "' alt='". $medal_text[$medal] .$user_medals["m_$medal"]. "' title='" . $medal_text[$medal] .$user_medals["m_$medal"]."'>";
						$medaille_vorhanden = 1;
					}
				}
       		}
       		if ($medaille_vorhanden == 0) {
			$t_medals .= "In dieser Kategorie sind noch keine Medaillen vorhanden.";
		}
      }else{
			$t_medals .= "<br>Die Medaillen dieses Spielers sind für dich nicht sichtbar.";
      }
      
      break;
    }

    default:
    {
      ErrorMessage(MSG_WORK_BOARD,e003);  // Falsche oder fehlende Parameter

      if (ErrorMessage(0))
      {
        $errorMessage .= "  <h1>{$MESSAGES[MSG_WORK_BOARD][m000]}</h1>";
        $errorMessage .= ErrorMessage();

        // add error output
        $template->set('errorMessage', $errorMessage);

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
        die();
      }
      break;
    }
  }

  // Flugstatistik
  $sel = sql_query("SELECT usarios.ID,usarios.flightstats,usarios.alliance FROM usarios INNER JOIN userdata ON usarios.ID = userdata.ID WHERE userdata.user='" . $information['user'] . "'");
  $player_alliance = sql_fetch_array(sql_query("SELECT alliance FROM usarios WHERE ID='$_SESSION[user]'"));
  $sel = sql_fetch_array($sel);
  if((intval($sel['flightstats']) == 1) || ((intval($sel['flightstats']) == 2) && ($sel['alliance'] == $player_alliance['alliance']) && ($sel['alliance'] != ""))) {
  $x=0;	
  $select = sql_query("SELECT type_plane.name, flightstats.ad, flightstats.1, flightstats.2, flightstats.3, flightstats.4, flightstats.5, flightstats.6 FROM flightstats INNER JOIN type_plane ON flightstats.type = type_plane.type WHERE flightstats.user='$sel[ID]' AND (flightstats.ad='plane' OR flightstats.ad='defense');");
  while($row = sql_fetch_array($select)) { 
  if($x==0) {
  	$template->set("flightstats2", $sel['flightstats']);
  	  $flight .= "<table border=0>
  					<tr>
  						<td>
  							&nbsp;
  						</td>
  						<td colspan=2 align='center'>
  							<b>Angriff</b>
  						</td>
  						<td colspan=2 align='center'>
  							<b>Verteidigung</b>
  						</td>
  						<td colspan=2 align='center'>
  							<b>Handel</b>
  						</td>
  					</tr>
  					<tr>
  						<td>
  							&nbsp;
  						</td>
  						<td>
  							Zerstört
  						</td>
  						<td>
  							Verloren
  						</td>
  						<td>
  							Zerstört
  						</td>
  						<td>
  							Verloren
  						</td>
  						<td>
  							Eingang
  						</td>
  						<td>
  							Ausgang
  						</td>
  					</tr>";
  	  $x++;
  } 
	$flight .= "<tr><td>$row[name]</td><td>". number_format($row[1],0, "", ".")."</td><td>". number_format($row[2],0, "", ".")."</td><td>". number_format($row[3],0, "", ".")."</td><td>". number_format($row[4],0, "", ".")."</td><td>". number_format($row[5],0, "", ".")."</td><td>". number_format($row[6],0, "", ".")."</td></tr>";
  }
  if($x==1) $flight .= "</table><br><br>";
  
  $select = sql_query("SELECT `1`,`2`,`3`,`4` FROM flightstats WHERE user='$sel[ID]' AND ad='raid_out'");
  while($select = sql_fetch_array($select)) {
  	  $flight .= "<table border=0>
  					<tr>
  						<td colspan=2>
  							Plünderung
  						</td>
  					</tr>
  					<tr>
  						<td width=50>
  							Iridium
  						</td>
  						<td>
  							". number_format($select[0],0, "", ".")."
  						</td>
  					</tr><tr>
  						<td width=50>
  							Holzium
  						</td>
  						<td>
  							". number_format($select[1],0, "", ".")."
  						</td>
  					</tr><tr>
  						<td width=50>
  							Wasser
  						</td>
  						<td>
  							". number_format($select[2],0, "", ".")."
  						</td>
  					</tr><tr>
  						<td width=50>
  							Sauerstoff
  						</td>
  						<td>
  							". number_format($select[3],0, "", ".")."
  						</td>
  					</tr></table><br><br>";
  }
  $select2 = sql_query("SELECT `1`,`2`,`3`,`4` FROM flightstats WHERE user='$sel[ID]' AND ad='raid_in'");
  while($select2 = sql_fetch_array($select2)) {
  			$flight .= "<table border=0>
  				<tr>
  					<td colspan=2>
  						Verlorene Rohstoffe
  					</td>
  				</tr><tr>
  						<td width=50>
  							Iridium
  						</td>
  						<td>
  							". number_format($select2[0],0, " ", ".")."
  						</td>
  					</tr><tr>
  						<td width=50>
  							Holzium
  						</td>
  						<td>
  							". number_format($select2[1],0, " ", ".")."
  						</td>
  					</tr><tr>
  						<td width=50>
  							Wasser
  						</td>
  						<td>
  							". number_format($select2[2],0, " ", ".")."
  						</td>
  					</tr><tr>
  						<td width=50>
  							Sauerstoff
  						</td>
  						<td>
  							". number_format($select2[3],0, " ", ".")."
  						</td>
  					</tr></table>";
  }
  } else {
	$flight .= "<br> Die Flugstatistik dieses Users ist für dich nicht sichtbar.";
  }
  
  $template->set('flightstats', $flight);
  $template->set('alliance', $t_ally);
  $template->set('city', $t_city);
  $template->set('user', $t_user);
  $template->set('medals', $t_medals);
  $template->set('war_status_file', "$etsAddress/war_status.php");

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

// <a href=\"javascript:window.open('$etsAddress/msgctr.php?action=write&puser=$informations[user]&msgtype=txt','msgctr','fullscreen=no,channelmode=no,toolbar=yes,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=810,height=700,top=5,left=5')\" onclick=\"window.open('$etsAddress/msgctr.php?action=write&puser=$informations[user]','msgctr','fullscreen=no,channelmode=no,toolbar=yes,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=810,height=700,top=5,left=5')\" target=msgctr>Nachricht schreiben</a>

?>
