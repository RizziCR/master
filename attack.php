<?php
  $use_lib = 1; // MSG_AIRPORT

  require_once("msgs.php");
  require_once("database.php");
  require_once("constants.php");
  require_once("functions.php");
  require_once("do_loop.php");

  require_once("class_Krieg.php");

  // define phptal template
  require_once("PHPTAL.php");
  require_once("include/PHPTAL_EtsTranslator.php");
  //$template = new PHPTAL('accstat.html');
  $template = new PHPTAL('standard.html');
  $template->setTranslator(new PHPTAL_EtsTranslator());
  $template->setEncoding('ISO-8859-1');

  // set page title
  $template->set('pageTitle', 'Übersichten - Städte');

  $pfuschOutput = "";


  // Überprüfen, ob Werte in der URL übergeben wurden
  if(isset($_GET['continent'], $_GET['country'], $_GET['city'], $_GET['id'])){

      // Spy_ID mit der ID in der Session vergleichen
      if($_GET['id'] != $_SESSION[spy_id] || $_GET['id'] == ''){
          ErrorMessage(MSG_AIRPORT,e007);
          $go_back = 1;
      }

      $_POST[continent] = mysql_real_escape_string($_GET['continent']);
      $_POST[country] = mysql_real_escape_string($_GET['country']);
      $_POST[aimed_city] = mysql_real_escape_string($_GET['city']);
      $_POST[p_fleet][8] = 1;
      $_POST[what] = "attack";
      $_POST[spy] = "yes";
      $_POST[plunder_first] = "iridium";
      $_POST[plunder_second] = "holzium";
      $_POST[plunder_third] = "water";
      $_POST[plunder_fourth] = "oxygen";
  }

 // insert specific page logic here

  if($_POST[colonize] == "YES") {
  	
  }else{
  	$_POST[colonize] = "NO";
  }
  
  if ($query)
    sql_query("INSERT INTO _attack (time,user,query) VALUES (UNIX_TIMESTAMP(),'$_SESSION[user]','". addslashes($query) ."')");

  $get_buildings = sql_query("SELECT b_airport,b_communication_center,city FROM city WHERE ID='$_SESSION[city]' && user='$_SESSION[user]'");

  $buildings = sql_fetch_array($get_buildings);

  if ($buildings[b_airport] <= 0)
    ErrorMessage(MSG_AIRPORT,e000);    // Sie müssen erst einen Flughafen bauen, um diese Funktion nutzen zu können

  if ($_POST[what] != "attack" && $_POST[what] != "transport")
    ErrorMessage(MSG_AIRPORT,e001);    // Bitte wähle eine Aktion aus


  $continent = (int)round(trim($_POST[continent]));
  $country = (int)round(trim($_POST[country]));
  $aimed_city = (int)round(trim($_POST[aimed_city]));

  $attack_city = $continent . ":" . $country . ":" . $aimed_city;
  if($attack_city == $buildings['city']) 
  	ErrorMessage(MSG_AIRPORT,e002);
  
  $p_fleet = $_POST[p_fleet];
  if(($continent == 0 && $country == 11 && $aimed_city == 5) || ($continent == 0 && $country == 0 && $aimed_city == 0)) $pfuschOutput .= "";
  elseif ($continent < 1 || $continent > MAX_CONTINENT || $country < 1 || $aimed_city < 1 || $_SESSION[city] == "$continent:$country:$aimed_city" || $aimed_city > countrySize($continent,$country) || $country > MAX_COUNTRY || $country < 1)
    ErrorMessage(MSG_AIRPORT,e002);    // Bitte wählen Sie ein gültiges Ziel aus
  if ($_POST[what] == "attack" && sql_num_rows(sql_query("SELECT 1 FROM city LEFT JOIN userdata ON city.user=userdata.ID WHERE city.city='$continent:$country:$aimed_city' && (userdata.holiday!=0 || userdata.multi='Y' || (userdata.time_block+24*3600) >= ". time() .")")) > 0)
    ErrorMessage(MSG_AIRPORT,e003);    // Der Zieluser befindet sich im Urlaub

  if ($_POST[what] == "attack" && ($_POST[plunder_first] == $_POST[plunder_second] || $_POST[plunder_first] == $_POST[plunder_third] || $_POST[plunder_first] == $_POST[plunder_fourth] || $_POST[plunder_second] == $_POST[plunder_third] || $_POST[plunder_second] == $_POST[plunder_fourth] || $_POST[plunder_third] == $_POST[plunder_fourth]))
    ErrorMessage(MSG_AIRPORT,e004);    // Bitte geben Sie eine eindeutige Plünderreihenfolge an

  if ($_POST[what] == "attack" && $_POST[colonize] == "YES" && $p_fleet[SETTLER])
  {
    $get_anzahl_user_colonies = sql_query("SELECT Count(*) FROM city WHERE user='$_SESSION[user]' && home!='YES'");
    $anzahl_user_colonies = sql_fetch_array($get_anzahl_user_colonies);

    $get_colonizations = sql_query("SELECT Count(*) FROM actions WHERE user='$_SESSION[user]' && f_colonize='YES' && f_settler>0 && f_scarecrow=0");
    $colonizations = sql_fetch_array($get_colonizations);

    if ($anzahl_user_colonies[0] + $colonizations[0] >= numberOfColonies($buildings["b_{$b_db_name[COMM_CENTER]}"]))
      ErrorMessage(MSG_AIRPORT,e005);  // Ihr Kommunikationszentrum kann nicht so viele Kolonien verwalten
  }

  if (ErrorMessage(0))
  {

    $pfuschOutput .= "  <h1>{$MESSAGES[MSG_AIRPORT][m000]}</h1>";

    $pfuschOutput .= ErrorMessage();

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
    die();
  }



  $position = split(":",$buildings[city]);
  if ($continent != $position[0])
  {
//    $dist = abs($continent - $position[0]);
//    $dist = min($dist, MAX_CONTINENT - $dist);
//    $distance = round(10000 * $dist);
    $distance = CONTINENT_DISTANCE;
  }
  else
    if ($country != $position[1])
    {
      if ($country > $position[1])
        $ndist = (MAX_COUNTRY - $country) + $position[1];
      else
        $ndist = (MAX_COUNTRY - $position[1]) + $country;

      if (abs($country - $position[1]) < $ndist)
        $distance = COUNTRY_BASE_DISTANCE + abs($country - $position[1])*COUNTRY_DISTANCE;
      else
        $distance = COUNTRY_BASE_DISTANCE + abs($ndist)*COUNTRY_DISTANCE;
    }
    else
      if ($aimed_city - $position[2] != 0)
        $distance = CITY_BASE_DISTANCE + abs($aimed_city - $position[2])*CITY_DISTANCE;

	if ($_POST[colonize] == "YES" && $buildings["b_{$b_db_name[COMM_CENTER]}"] < 50 && $buildings["b_{$b_db_name[COMM_CENTER]}"] < ($distance-COUNTRY_BASE_DISTANCE)/COUNTRY_DISTANCE
		&& ($p_fleet[SETTLER] || $p_fleet[SCARECROW]))
		ErrorMessage(MSG_AIRPORT,e022);
		
  $get_techs = sql_query("SELECT t_{$t_db_name[O_DRIVE]},t_{$t_db_name[H_DRIVE]},t_{$t_db_name[A_DRIVE]},null,null,null,t_{$t_db_name[CONSUMPTION]},t_{$t_db_name[PLANE_SIZE]},t_{$t_db_name[COMP_MANAGEMENT]} FROM usarios WHERE ID='$_SESSION[user]'");
  $user_techs = sql_fetch_array($get_techs);

  $get_planes = sql_query("SELECT p_". implode(",p_",$p_db_name_wus) ." FROM city WHERE ID='$_SESSION[city]'");
  $city_planes = sql_fetch_array($get_planes);


  $displayWarningTransporters = false;
  if ($_POST[what] == "attack")
  {
    /* warnhinweis das mit transportern nicht angegriffen werden kann. */
    if($p_fleet[SMALL_TRANSPORTER] > 0 ||$p_fleet[MEDIUM_TRANSPORTER] > 0 ||$p_fleet[BIG_TRANSPORTER] > 0) {

          $displayWarningTransporters = true;
    }
    $p_fleet[SMALL_TRANSPORTER] = 0;
    $p_fleet[MEDIUM_TRANSPORTER] = 0;
    $p_fleet[BIG_TRANSPORTER] = 0;
  }


  $speed = 0;
  $consumption = 0;
  $capacity = 0;
  $flugzeuge_anzahl = 0;
  $speed_start_flag = true;

  for ($i=0;$i<ANZAHL_FLUGZEUGE;$i++)
  {
    $p_fleet[$i] = round($p_fleet[$i]);

    if ($p_fleet[$i] > $city_planes[$i])
    {
      ErrorMessage(MSG_AIRPORT,e006);    // Sie haben nicht genügend Flugzeuge
      break;
    }

    if ($p_fleet[$i] > 0)
    {
      $curr_speed = $p_speed[$i] + $t_increase[$p_tech[$i][T_SPEED]] * $user_techs[$p_tech[$i][T_SPEED]];
      if (($curr_speed < $speed) || $speed_start_flag)
      {
        $speed_start_flag = false;
        $speed = $curr_speed;
      }
    }

    if ($p_fleet[$i] < 0)
      ErrorMessage(MSG_AIRPORT,e007);    // Cheat-Versuche werden bestraft!

    $consumption += $distance / 1000 * $p_consumption[$i] * pow($t_increase[CONSUMPTION],$user_techs[CONSUMPTION]) * $p_fleet[$i];
    $capacity += floor($p_fleet[$i] * $p_capacity[$i] * pow($t_increase[PLANE_SIZE],$user_techs[PLANE_SIZE]));

    $flugzeuge_anzahl += $p_fleet[$i];
  }

  $consumption = ceil($consumption);

  if (!$flugzeuge_anzahl) {
      if($displayWarningTransporters) {
          ErrorMessage(MSG_AIRPORT,e020);
      }
      ErrorMessage(MSG_AIRPORT,e008);   // Sie haben nicht genügend Flugzeuge
  }

  if ($_POST[what] == transport && $_POST[give])
    $consumption /= 2;

  if (ErrorMessage(0))
  {
    $pfuschOutput .= "  <h1>{$MESSAGES[MSG_AIRPORT][m000]}</h1>";

    $pfuschOutput .= ErrorMessage();

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
    die();
  }

  if ($flugzeuge_anzahl > $buildings["b_{$b_db_name[AIRPORT]}"]*5 + $user_techs[COMP_MANAGEMENT]*$t_increase[COMP_MANAGEMENT])
    ErrorMessage(MSG_AIRPORT,e009);    // Sie können nicht soviele Flugzeuge verschicken

  if ($_POST[what] == "transport")
  {
    if ($_POST[transport_iridium] <= 0)  { $transport_iridium = 0; } else { $transport_iridium = round($_POST[transport_iridium]);}
    if ($_POST[transport_holzium] <= 0)  { $transport_holzium = 0; } else { $transport_holzium = round($_POST[transport_holzium]);}
    if ($_POST[transport_water] <= 0)    { $transport_water = 0; }  else { $transport_water = round($_POST[transport_water]);}
    if ($_POST[transport_oxygen] <= 0)    { $transport_oxygen = 0; }  else { $transport_oxygen = round($_POST[transport_oxygen]);}
  }
  else
  {
    $transport_iridium = 0;
    $transport_holzium = 0;
    $transport_water = 0;
    $transport_oxygen = 0;
  }

  if ($_POST[what] == "transport" &&
          ($timefixed_depot->getIridium() < $transport_iridium || $timefixed_depot->getHolzium() < $transport_holzium ||
            $timefixed_depot->getWater() < $transport_water || $timefixed_depot->getOxygen() < $consumption + $transport_oxygen)
       )
    ErrorMessage(MSG_AIRPORT,e010);    // Sie haben nicht genügend Rohstoffe

  if ($_POST[what] == "transport" && ($transport_iridium + $transport_holzium + $transport_water + $transport_oxygen > $capacity))
    ErrorMessage(MSG_AIRPORT,e011);    // Ihre Flugzeuge haben nicht genügend Kapazität


  if ($consumption + $transport_oxygen > $timefixed_depot->getOxygen())
    ErrorMessage(MSG_AIRPORT,e012);    // Sie haben nicht genügend Treibstoff

  $fly_time = round($distance/$speed * 3600);

  $get_now = split(" ",microtime());
  $now = $get_now[1] + $get_now[0];

  /* no hangar protection in ETS9
  $target_r = sql_query("SELECT b_hangar,b_technologie_center,home FROM city WHERE city LIKE '$continent:$country:$aimed_city'");
  $target = sql_fetch_array($target_r);

  if ($_POST[what] == "attack" && sql_num_rows($target_r) && (
      ( $target[home] == 'YES' && !$target[b_hangar] ) ||
      ( $target[home] != 'YES' && !$target[b_hangar] && !$target[b_technologie_center] )
  ))
    ErrorMessage(MSG_AIRPORT,e019);    // Die Zielstadt hat noch keinen Hangar und ist somit unangreifbar
  */

  $get_denies = sql_query("SELECT time FROM attack_denies WHERE user='$_SESSION[user]' && city LIKE '$continent:$country:$aimed_city' ORDER BY time DESC");
  $denies = sql_fetch_array($get_denies);
  
  if ($_POST[what] == "attack" && ($denies[time]-$fly_time) > time() && $flugzeuge_anzahl > $p_fleet[ESPIONAGE_PROBE])
  {
    $MESSAGES[MSG_AIRPORT][e013] = "Du darfst diese Stadt erst wieder ab ". date("H:i",$denies[time]) ."<font class=seconds style=\"color:#FF0000\">". date(":s",$denies[time]) ."</font> am ". date("d.m.Y",$denies[time]) ." angreifen<br>";
    ErrorMessage(MSG_AIRPORT,e013);
  }

  $get_target_user = sql_query("SELECT ID,user FROM city WHERE city='$continent:$country:$aimed_city'");
  $target_user = sql_fetch_array($get_target_user);

  if ($_SESSION[sitt_login] && $target_user[user] != $_SESSION[user] && $_POST[what] != "attack" && $target_user[user])
    ErrorMessage(MSG_AIRPORT,e014);    // Sitter dürfen Nichtangriffsflotten nur zu Städten des zu sittenden Accounts schicken

  if ($_SESSION[sitt_login] && $target_user[user] == $_SESSION[sitter])
    ErrorMessage(MSG_AIRPORT,e015);    // Sitter dürfen nicht mit Ihrem eigenen Account interagieren

  $get_acc_to_sit = sql_query("SELECT ID FROM usarios WHERE sitter='$_SESSION[sitter]' && sitter_confirmation='YES'");
  $acc_to_sit = sql_fetch_array($get_acc_to_sit);

  if (!$_SESSION[sitt_login] && sql_num_rows($get_acc_to_sit) && $target_user[user] == $acc_to_sit[ID] && $target_user[user])
    ErrorMessage(MSG_AIRPORT,e016);    // Sitter dürfen nicht mit dem zu sittenden Account interagieren

  if ($_SESSION[sitt_login] && $_POST[spy])
    ErrorMessage(MSG_AIRPORT,e017);    // Sitter dürfen keine Sonden verschicken

  $get_own_points = sql_query("SELECT points,home FROM city WHERE ID='$_SESSION[city]'");
  $own_points = sql_fetch_array($get_own_points);

  if ($_POST[what] == "transport" && $own_points[points] < 100 && $own_points[home] == "YES" && $target_user[user]!='' && $target_user[user]!=$_SESSION[user] )
    ErrorMessage(MSG_AIRPORT,e018);    // Hauptstädte unter 100 Punkten dürfen keine Transportflotten starten

  if ($_POST[what] == "attack" && $own_points[points] < 50 && $target_user[user]!='' && $target_user[user]!=$_SESSION[user] )
    ErrorMessage(MSG_AIRPORT,e021);    // Angriffe sind erst ab 50 Stadtpunkten möglich

  if($continent == "0" && $country == "0" && $aimed_city == "0") 
  {
  	$select = "SELECT kw2 FROM asteroid WHERE `started` = 'started'";
  	$select = sql_query($select);
  	$select = sql_fetch_array($select);
  	if($select['kw2'] == "0")
  	{
  		$MESSAGES[MSG_AIRPORT][e999] = "Es findet momentan kein Event statt.";
  		ErrorMessage(MSG_AIRPORT,e999);
  	} 
  }    
    
  if($_SESSION['user'] == "514") {
  	echo "WHAT: $_POST[what] <br>";
  	echo "SPY: $_POST[spy] <br>";
  	echo "Flugzeug_Anzahl: $flugzeuge_anzahl <br>";
  	echo "<pre>";
  	print_r($p_fleet);
  	echo "</pre>";
  }
  
  if (ErrorMessage(0))
  {
    $pfuschOutput .= "  <h1>{$MESSAGES[MSG_AIRPORT][m000]}</h1>";

    $pfuschOutput .= ErrorMessage();

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
    die();
  }

  $f_name = addslashes(wordwrap(trim($_POST[f_name]),60,"\n",true));
  // remove linebreaks
  $f_name = str_replace("<br>", " ", $f_name);
  $f_name = implode('#', explode('|', $f_name));

  
  
  $koordinaten = sql_fetch_array ( sql_query("SELECT city FROM city WHERE city='$continent:$country:$aimed_city';") );
  
  if($koordinaten['city'] == "") {
  	$target_user['ID'] = "$continent:$country:$aimed_city";
  }
   
  
  
  if ($_POST[what] == "attack")
  {
    $timefixed_depot->removeOxygen($consumption);

    for ($i=0;$i<ANZAHL_KAMPF_FLUGZEUGE;$i++)
    {
      $query[0] .= ",f$p_db_name[$i]";
      $query[1] .= ",'$p_fleet[$i]'";
      $query[2] .= "p_$p_db_name_wus[$i]=p_$p_db_name_wus[$i]-$p_fleet[$i],";
    }

    if (!$_POST[colonize_jobs])
      $_POST[colonize_jobs] = NO;
    if (!$_POST[colonize_fleet])
      $_POST[colonize_fleet] = NO;
    if (!$_POST[colonize_hangar])
      $_POST[colonize_hangar] = NO;
	
    // Plünderreihenfolge speichern R11
    if($_POST[plunder] == "YES")
    {
    	if($_POST[plunder_first] == "iridium") $plunder_iri = 1;
    	if($_POST[plunder_second] == "iridium") $plunder_iri = 2;
    	if($_POST[plunder_third] == "iridium") $plunder_iri = 3;
    	if($_POST[plunder_fourth] == "iridium") $plunder_iri = 4;
    	
    	if($_POST[plunder_first] == "holzium") $plunder_holz = 1;
    	if($_POST[plunder_second] == "holzium") $plunder_holz = 2;
    	if($_POST[plunder_third] == "holzium") $plunder_holz = 3;
    	if($_POST[plunder_fourth] == "holzium") $plunder_holz = 4;
    	
    	if($_POST[plunder_first] == "water") $plunder_wat = 1;
    	if($_POST[plunder_second] == "water") $plunder_wat = 2;
    	if($_POST[plunder_third] == "water") $plunder_wat = 3;
    	if($_POST[plunder_fourth] == "water") $plunder_wat = 4;
    	
    	if($_POST[plunder_first] == "oxygen") $plunder_sauer = 1;
    	if($_POST[plunder_second] == "oxygen") $plunder_sauer = 2;
    	if($_POST[plunder_third] == "oxygen") $plunder_sauer = 3;
    	if($_POST[plunder_fourth] == "oxygen") $plunder_sauer = 4;
    	
    	
    	sql_query("UPDATE userdata SET plunder_iridium='$plunder_iri', plunder_holzium='$plunder_holz', plunder_water='$plunder_wat', plunder_oxygen='$plunder_sauer' WHERE ID='$_SESSION[user]'");
    }
    
    sql_query("INSERT INTO actions (city,user,session_id,f_id,f_action,f_plunder,f_iridium,f_holzium,f_water,f_oxygen,f_spy,f_colonize,f_colonize_jobs,f_colonize_fleets,f_colonize_hangar,f_target,f_target_user,f_name,f_name_show,f_start,f_arrival$query[0],f_flugzeuge_anzahl,code) SELECT '$_SESSION[city]','$_SESSION[user]','". session_id() ."',Max(id)+1,'attack','$_POST[plunder]','$_POST[plunder_first]','$_POST[plunder_second]','$_POST[plunder_third]','$_POST[plunder_fourth]','$_POST[spy]','$_POST[colonize]','$_POST[colonize_jobs]','$_POST[colonize_fleet]','$_POST[colonize_hangar]','$target_user[ID]','$target_user[user]','$f_name','$_POST[f_name_show]',$now,$now + $fly_time". $query[1] .",'$flugzeuge_anzahl','".rand(10000, 99999)."' FROM actions");

    $get_f_id = sql_query("SELECT Max(f_id) AS f_id FROM actions WHERE session_id='". session_id() ."'");
    $f_id = sql_fetch_array($get_f_id);

    sql_query("INSERT INTO actions (city,user,f_id,f_action,f_target,f_target_user,f_name,f_start,f_arrival$query[0],f_flugzeuge_anzahl) VALUES ('$_SESSION[city]','$_SESSION[user]','$f_id[0]','attack_back','$target_user[ID]','$target_user[user]','$f_name',$now + $fly_time,$now + 2*$fly_time" . $query[1] .",'$flugzeuge_anzahl')");

    sql_query("UPDATE city SET ".rtrim($query[2],',')." WHERE ID='$_SESSION[city]'");

    if ($flugzeuge_anzahl > $p_fleet[ESPIONAGE_PROBE] && $target_user[user])
    {
      sql_query("INSERT INTO attack_denies (user,city,time) VALUES ('$_SESSION[user]','$continent:$country:$aimed_city',UNIX_TIMESTAMP(NOW()) + ". ATTACKDENYHOURS ."*3600 + $fly_time)");
      $get_max_deny_id = sql_query("SELECT Max(id) as max_id FROM attack_denies WHERE user='$_SESSION[user]' && city='$continent:$country:$aimed_city'");
      $max_deny_id = sql_fetch_array($get_max_deny_id);
      sql_query("UPDATE actions SET attack_deny_id=$max_deny_id[max_id] WHERE f_id='$f_id[f_id]'");
    }
  }

  $give = $_POST[give];

  if ($_POST[what] == "transport")
  {
    for ($i=0;$i<ANZAHL_FLUGZEUGE;$i++)
    {
      $query[0] .= ",f$p_db_name[$i]";
      $query[1] .= ",'$p_fleet[$i]'";
      $query[2] .= "p$p_db_name[$i]=p$p_db_name[$i]-$p_fleet[$i],";
    }

    sql_query("INSERT INTO actions (city,user,session_id,f_id,f_action,f_iridium,f_holzium,f_water,f_oxygen,f_give,f_target,f_target_user,f_name,f_name_show,f_start,f_arrival$query[0],f_flugzeuge_anzahl,code) SELECT '$_SESSION[city]','$_SESSION[user]','". session_id() ."',Max(id)+1,'transport','$transport_iridium','$transport_holzium','$transport_water','$transport_oxygen','$give','$target_user[ID]','$target_user[user]','$f_name','$_POST[f_name_show]',$now,$now + $fly_time". $query[1] .",'$flugzeuge_anzahl','".rand(10000, 99999)."' FROM actions");
    $get_f_id = sql_query("SELECT Max(f_id) AS f_id FROM actions WHERE session_id='". session_id() ."'");
    $f_id = sql_fetch_array($get_f_id);

    if (!$give)
      sql_query("INSERT INTO actions (city,user,f_id,f_action,f_target,f_target_user,f_name,f_start,f_arrival$query[0],f_flugzeuge_anzahl,msg) VALUES ('$_SESSION[city]','$_SESSION[user]','$f_id[0]','transport_back','$target_user[ID]','$target_user[user]','$f_name',$now + $fly_time,$now + 2*$fly_time". $query[1] .",'$flugzeuge_anzahl','Eine Flotte ($_SESSION[city]) kehrte von $continent:$country:$aimed_city ". (($target_user[user]) ? "($target_user[user])" : "") ." zurück')");

    sql_query("UPDATE city SET ".rtrim($query[2],',')." WHERE ID='$_SESSION[city]'");

    $timefixed_depot->removeIridium($transport_iridium);
    $timefixed_depot->removeHolzium($transport_holzium);
    $timefixed_depot->removeWater($transport_water);
    $timefixed_depot->removeOxygen($transport_oxygen + $consumption);
  }

  $hours = floor($fly_time/3600);
  $m = ($fly_time/3600 - $hours) * 60;
  $minutes = floor($m);
  $s = ($m - $minutes) * 60;
  $seconds = floor($s);

  if ($minutes < 10)
    $minutes = "0$minutes";
  if ($seconds < 10)
    $seconds = "0$seconds";
  if($displayWarningTransporters) {
      ErrorMessage(MSG_AIRPORT,e020); //Mit Transportflugzeugen kann nicht angegriffen werden
      $pfuschOutput .= ErrorMessage();
  }
  $fUser = new User($target_user[user]);

  list( $alliance ) = sql_fetch_row(sql_query("SELECT alliance FROM city WHERE city='".addslashes("$continent:$country:$aimed_city")."'"));
  list( $myalliance ) = sql_fetch_row(sql_query("SELECT alliance FROM usarios WHERE user='".$_SESSION[user]."'"));

  $krieg = new Krieg($alliance);
  $krieg->load();
  if($krieg->inWar() && !$krieg->isAlly($myalliance) && !$krieg->isOpponent($myalliance)) {
      $war_warning = "<span style='color:#f00'>Achtung: Diese Stadt ist Kriegsgebiet!</span>";
  }

  $pfuschOutput .= "  <h1>{$MESSAGES[MSG_AIRPORT][m000]}</h1>

      {$MESSAGES[MSG_AIRPORT][m025]}<br><br>

      <table width=750 border=0 cellpadding=3 cellspacing=3>

      <tr>
        <td colspan=2 align=center class=table_head>
          {$MESSAGES[MSG_AIRPORT][m026]}
        </td>
      </tr>
      <tr>
        <td>
          {$MESSAGES[MSG_AIRPORT][m008]}
        </td>
        <td>
          $continent:$country:$aimed_city (". (($target_user[user]) ? $fUser->getScreenName() : $MESSAGES[MSG_AIRPORT][m038]) .")
        </td>
      </tr>
      <tr>
        <td></td>
        <td>".$war_warning."</td>
      </tr>
      <tr>
        <td>
          {$MESSAGES[MSG_AIRPORT][m009]}
        </td>
        <td>
          $hours:$minutes:<font class=seconds>$seconds</font>
        </td>
      </tr>
      <tr>
        <td>
          {$MESSAGES[MSG_AIRPORT][m010]}
        </td>
        <td>
          $distance km
        </td>
      </tr>
      <tr>
        <td>
          {$MESSAGES[MSG_AIRPORT][m011]}
        </td>
        <td>
          ". round($consumption) ." {$MESSAGES[MSG_GENERAL][m003]}
        </td>
      </tr>
      <tr>
        <td>
          {$MESSAGES[MSG_AIRPORT][m012]}
        </td>
        <td>
          $speed km/h
        </td>
      </tr>
      <tr>
        <td>
          {$MESSAGES[MSG_AIRPORT][m013]}
        </td>
        <td>
          $capacity {$MESSAGES[MSG_AIRPORT][m014]}
        </td>
      </tr>
      <tr>
        <td>
          {$MESSAGES[MSG_AIRPORT][m027]}
        </td>
        <td>
          ". ($transport_iridium + $transport_holzium + $transport_water + $transport_oxygen) ." {$MESSAGES[MSG_AIRPORT][m014]}
        </td>
      </tr>

      <tr>
        <td colspan=2>
          <br>
        </td>
      </tr>";

  switch ($_POST[what])
  {
    case "attack" :
      $pfuschOutput .= "  <tr>
            <td>
              {$MESSAGES[MSG_AIRPORT][m028]}
            </td>
            <td>
              {$MESSAGES[MSG_AIRPORT][m029]}
            </td>
          </tr>";

      if ($_POST[plunder] == "YES")
        $pfuschOutput .= "  <tr>
              <td>
                &nbsp;&nbsp;&nbsp;&nbsp;{$MESSAGES[MSG_AIRPORT][m030]}
              </td>
              <td>
                (1. ". translate($_POST[plunder_first]) . ", 2. ". translate($_POST[plunder_second]) . ", 3. ". translate($_POST[plunder_third]) . ", 4. ". translate($_POST[plunder_fourth]) . ")
              </td>
            </tr>";

      if ($_POST[spy] == "YES")
        $pfuschOutput .= "  <tr>
              <td colspan=2>
                &nbsp;&nbsp;&nbsp;&nbsp;{$MESSAGES[MSG_AIRPORT][m031]}
              </td>
            </tr>";

      if ($_POST[colonize] == "YES")
      {
        $pfuschOutput .= "  <tr>
              <td colspan=2>
                &nbsp;&nbsp;&nbsp;&nbsp;{$MESSAGES[MSG_AIRPORT][m032]}<br>";

        if ($_POST[colonize_jobs] == "YES")
          $pfuschOutput .= "    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$MESSAGES[MSG_AIRPORT][m034]}<br>";

        if ($_POST[colonize_fleet] == "YES")
          $pfuschOutput .= "    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$MESSAGES[MSG_AIRPORT][m035]}<br>";

        if ($_POST[colonize_hangar] == "YES")
          $pfuschOutput .= "    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$MESSAGES[MSG_AIRPORT][m039]}<br>";

        $pfuschOutput .= "    </td>
            </tr>";
      }
      break;

    case "transport" :
      $pfuschOutput .= "  <tr>
            <td>
              {$MESSAGES[MSG_AIRPORT][m028]}
            </td>
            <td>
              {$MESSAGES[MSG_AIRPORT][m033]}
            </td>
          </tr>";

      if ($give)
        $pfuschOutput .= "  <tr>
              <td>
              </td>
              <td>
                {$MESSAGES[MSG_AIRPORT][m037]}
              </td>
            </tr>";

      if ($transport_iridium > 0)
        $pfuschOutput .= "  <tr>
              <td>
                {$MESSAGES[MSG_GENERAL][m000]}
              </td>
              <td>
                $transport_iridium {$MESSAGES[MSG_AIRPORT][m014]}
              </td>
            </tr>";

      if ($transport_holzium > 0)
        $pfuschOutput .= "  <tr>
              <td>
                {$MESSAGES[MSG_GENERAL][m001]}
              </td>
              <td>
                $transport_holzium {$MESSAGES[MSG_AIRPORT][m014]}
              </td>
            </tr>";

      if ($transport_water > 0)
        $pfuschOutput .= "  <tr>
              <td>
                {$MESSAGES[MSG_GENERAL][m002]}
              </td>
              <td>
                $transport_water {$MESSAGES[MSG_AIRPORT][m014]}
              </td>
            </tr>";

      if ($transport_oxygen > 0)
        $pfuschOutput .= "  <tr>
              <td>
                {$MESSAGES[MSG_GENERAL][m003]}
              </td>
              <td>
                $transport_oxygen {$MESSAGES[MSG_AIRPORT][m014]}
              </td>
            </tr>";

      break;
  }

  $pfuschOutput .= "  <tr>
        <td colspan=2>
          <br>
        </td>
      </tr>";

  for ($i=0;$i<ANZAHL_FLUGZEUGE;$i++)
    if ($p_fleet[$i] > 0)
      $pfuschOutput .= "  <tr>
            <td>
              $p_name[$i]
            </td>
            <td>
              $p_fleet[$i]
            </td>
          </tr>";

  $pfuschOutput .= "  </table>";

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
