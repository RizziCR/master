<?php
  $use_lib = 4; // MSG_HANGAR

  $add_ir = 0;
  $add_hz = 0;
  $p_gesamt = 0;

  require_once("msgs.php");
  require_once("database.php");
  require_once("constants.php");
  require_once("functions.php");
  require_once("do_loop.php");

  // define phptal template
  require_once("PHPTAL.php");
  require_once("include/PHPTAL_EtsTranslator.php");
  //$template = new PHPTAL('hangar.html');
  $template = new PHPTAL('standard.html');
  $template->setTranslator(new PHPTAL_EtsTranslator());
  $template->setEncoding('ISO-8859-1');

  // set page title
  $template->set('pageTitle', 'Stadt - Hangar');

  $pfuschOutput = "";
  include("tutorial.php");
  

 // insert specific page logic here

  $p_gesamt = 0;

  $action = $_REQUEST[action];
  $id = $_REQUEST[id];
  $p_count = $_REQUEST[p_count];

  $get_buildings = sql_query("SELECT city,b_{$b_db_name[HANGAR]} FROM city WHERE ID='$_SESSION[city]' && user='$_SESSION[user]'");
  $buildings = sql_fetch_array($get_buildings);

  if ($buildings["b_{$b_db_name[HANGAR]}"] <= 0)
    ErrorMessage(MSG_HANGAR,e000);    // Sie müssen erst einen Hangar bauen, um diese Funktion nutzen zu können

  if (ErrorMessage(0))
  {
    $errorMessage .=  "  <h1>{$MESSAGES[MSG_HANGAR][m000]}</h1>";
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

  $main_query = sql_query("SELECT p_". implode(",p_",$p_db_name_wus) .",p_gesamt_flugzeuge,city FROM city WHERE ID='$_SESSION[city]'");
  $p_hangar_fleet = sql_fetch_array($main_query);

  $main_actions = sql_query("SELECT id,end_time,current_build FROM jobs_planes WHERE city='$_SESSION[city]' ORDER BY end_time");

  $get_techs = sql_query("SELECT t_". implode(",t_",$t_db_name) ." FROM usarios WHERE ID='$_SESSION[user]'");
  $user_techs = sql_fetch_array($get_techs);

  if ($_POST[action] == $MESSAGES[MSG_HANGAR][m003]) // Abreißen
  {
    $p_gesamt = 0;
    for ($i=0;$i<ANZAHL_FLUGZEUGE;$i++)
    {
      if ((int)$p_count[$i] > 0 && (int)$p_count[$i] <= $p_hangar_fleet[$i])
      {
        $p_gesamt += (int)$p_count[$i];
        $abriss_query[] = "p_$p_db_name_wus[$i]=p_$p_db_name_wus[$i]-". (int)$p_count[$i];
        $abriss_query[] = "p_$p_db_name_wus[$i]_gesamt=p_$p_db_name_wus[$i]_gesamt-". (int)$p_count[$i];
        $p_hangar_fleet[$i] -= (int)$p_count[$i];
      }
    }

    if (!count($abriss_query))
      ErrorMessage(MSG_HANGAR,e004);  // Ung&uuml;ltige Menge

    if (ErrorMessage(0))
    {
      $errorMessage .=  "  <h1>{$MESSAGES[MSG_HANGAR][m000]}</h1>";
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

    sql_query("LOCK TABLES city WRITE");
    sql_query("UPDATE city SET blubb=blubb-$p_gesamt, ". implode(",",$abriss_query) .",p_gesamt_flugzeuge=p_gesamt_flugzeuge-$p_gesamt WHERE ID='$_SESSION[city]'");
    #sql_query("UPDATE city SET blubb=blubb-$p_gesamt WHERE city='$_SESSION[city]'");
    sql_query("UNLOCK TABLES");
    sql_query("INSERT INTO news_er (city,topic,time) VALUES ('$_SESSION[city]','Sie haben $p_gesamt Flugzeug(e) eliminiert','". time() ."')");

    $p_hangar_fleet[p_gesamt_flugzeuge] -= $p_gesamt;
  }

  $duration = null;
  for ($i=0;$i<ANZAHL_FLUGZEUGE;$i++)
    $duration[$i] = round(($p_duration[$i] - $p_duration_min[$i]) * pow(2,-$buildings["b_{$b_db_name[HANGAR]}"]/$p_duration_half_hangar) + $p_duration_min[$i]);
// also old:	$duration[$i] = $p_duration[$i];
// old:    $duration[$i] = $p_duration[$i]/$buildings["b_{$b_db_name[HANGAR]}"];

  if ($_POST[action] == $MESSAGES[MSG_HANGAR][m001]) // Bauen
  {
    if (sql_num_rows($main_actions))
    {
      $get_last_job = sql_query("SELECT Max(end_time) FROM jobs_planes WHERE city='$_SESSION[city]'");
      $last_job = sql_fetch_array($get_last_job);
      $end_time = $last_job[0];
    }
    else
      $end_time = time();

    for ($i=0;$i<ANZAHL_FLUGZEUGE && !ErrorMessage(0);$i++)
    {
      if ($p_count[$i] > 0)
      {
        $allowed = true;
        for ($x=0;$x<=ANZAHL_TECHNOLOGIEN;$x++)
        {
          if ($user_techs[$x] < $p_need[$i][$x])
          {
          	if($tut['tutorial'] != 11) {
            	$allowed = false;

            	ErrorMessage(MSG_HANGAR,e001);  // Sie besitzen nicht einen ausreichenden Forschungsstand, um diese(n) Flugzeugtype(n) bauen zu können

          	}
            break 2;
          }
        }
      }

      $gesamt = 0;
      $query = "INSERT INTO jobs_planes (city,user,end_time,current_build,msg) VALUES ";
      for ($y=0;$y<$p_count[$i] && !ErrorMessage(0);$y++)
      {
      	if($tut['tutorial'] == 11 && $i == 9) {
      		$p_iridium[$i] = 100;
      		$p_holzium[$i] = 200;
      		$duration[$i] = 60;
      	}
        if ($p_hangar_fleet[p_gesamt_flugzeuge] >=
        $buildings["b_{$b_db_name[HANGAR]}"]*PLANES_PER_LEVEL)
        {
          ErrorMessage(MSG_HANGAR,e002);    // Ihr Hangar bietet nicht genug Platz für soviele Flugzeuge
          break 2;
        }

        if ($timefixed_depot->getIridium() < $p_iridium[$i] || $timefixed_depot->getHolzium() < $p_holzium[$i])
        {
          ErrorMessage(MSG_HANGAR,e003);    // Sie haben nicht gen&uuml;gend Rohstoffe
          break 2;
        }

        
        $timefixed_depot->removeIridium($p_iridium[$i]);
        $timefixed_depot->removeHolzium($p_holzium[$i]);
        
        $end_time += $duration[$i];
        
        $up = sql_query("UPDATE city SET blubb=blubb+1, p_gesamt_flugzeuge=p_gesamt_flugzeuge+1,p$p_db_name[$i]_gesamt=p$p_db_name[$i]_gesamt+1 WHERE ID='$_SESSION[city]'");
        $p_hangar_fleet[p_gesamt_flugzeuge]++;
        
        sql_query("INSERT INTO jobs_planes (city,user,end_time,current_build,msg) VALUES ('$_SESSION[city]','$_SESSION[user]','". round($end_time) ."','p$p_db_name[$i]','Ein(e) $p_name[$i] wurde auf $buildings[city] fertiggestellt')");
        
      }
    }
    
    $main_actions = sql_query("SELECT id,end_time,current_build FROM jobs_planes WHERE city='$_SESSION[city]' ORDER BY end_time");
  }

  if ($_POST[action] == $MESSAGES[MSG_HANGAR][m002]) // Abbrechen
  {
    $firstrun = true;

    sql_query("LOCK TABLES city WRITE, jobs_planes WRITE");

    $main_actions = sql_query("SELECT id,end_time,current_build FROM jobs_planes WHERE city='$_SESSION[city]' ORDER BY end_time");
    while ($job = sql_fetch_array($main_actions))
    {

      if ($job[id] == $_POST[id])
      {
        for($i=0;$i<ANZAHL_FLUGZEUGE;$i++)
        {
          if ("p_". $p_db_name_wus[$i] == $job[current_build])
          {
            $reduce_factor = ($job[end_time] - time()) / ($duration[$i]);
            $reduce_factor = min($reduce_factor, 0.8);

            $timefixed_depot->addIridium($p_iridium[$i] * $reduce_factor);
            $timefixed_depot->addHolzium($p_holzium[$i] * $reduce_factor);

            sql_query("UPDATE city SET blubb=blubb-1, p_gesamt_flugzeuge=p_gesamt_flugzeuge-1,p_$p_db_name_wus[$i]_gesamt=p_$p_db_name_wus[$i]_gesamt-1 WHERE ID='$_SESSION[city]'");
            #sql_query("UPDATE city SET blubb=blubb-1 WHERE city='$_SESSION[city]'");
            $p_hangar_fleet[p_gesamt_flugzeuge]--;

            if ($firstrun)
              $sub_time = $job[end_time] - time();
            else
              $sub_time = $duration[$i];

            // Bezug <> ID immer direkt?!
            sql_query("DELETE FROM jobs_planes WHERE city='$_SESSION[city]' && id='$_POST[id]'");
            sql_query("UPDATE jobs_planes SET end_time=end_time-$sub_time WHERE city='$_SESSION[city]' && end_time>'$job[end_time]'"); // id>'$_POST[id]'

            break;
          }
        }
      }
      $firstrun = false;
    }
    sql_query("UNLOCK TABLES");

    $main_actions = sql_query("SELECT id,end_time,current_build FROM jobs_planes WHERE city='$_SESSION[city]' ORDER BY end_time");
  }

  if ($_POST[action] == $MESSAGES[MSG_HANGAR][m020]) // Alle Abbrechen
  {
    sql_query("LOCK TABLES city WRITE, jobs_planes WRITE");

    while ($job = sql_fetch_array($main_actions))
    {
      for($i=0;$i<ANZAHL_FLUGZEUGE;$i++)
      {
        if ("p_$p_db_name_wus[$i]" == $job[current_build])
        {
          $reduce_factor = ($job[end_time] - time()) / ($duration[$i]);
          $reduce_factor = min($reduce_factor, 0.8);

          $timefixed_depot->addIridium($p_iridium[$i] * $reduce_factor);
          $timefixed_depot->addHolzium($p_holzium[$i] * $reduce_factor);

          sql_query("UPDATE city SET blubb=blubb-1, p_gesamt_flugzeuge=p_gesamt_flugzeuge-1,p_$p_db_name_wus[$i]_gesamt=p_$p_db_name_wus[$i]_gesamt-1 WHERE ID='$_SESSION[city]'");
          #sql_query("UPDATE city SET blubb=blubb-1 WHERE city='$_SESSION[city]'");
          $p_hangar_fleet[p_gesamt_flugzeuge]--;
        }
      }
    }
    sql_query("DELETE FROM jobs_planes WHERE city='$_SESSION[city]'");
    sql_query("UNLOCK TABLES");

    $main_actions = sql_query("SELECT id,end_time,current_build FROM jobs_planes WHERE city='$_SESSION[city]' ORDER BY end_time");
  }

  for ($i=0;$i<ANZAHL_FLUGZEUGE;$i++)
    $p_gesamt_in_city += $p_hangar_fleet[$i];

  if (ErrorMessage(0))
  {
    $errorMessage .=  "  <h1>{$MESSAGES[MSG_HANGAR][m000]}</h1>";
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
 $free = $buildings["b_{$b_db_name[HANGAR]}"]*PLANES_PER_LEVEL - $p_hangar_fleet[p_gesamt_flugzeuge];

  $pfuschOutput .=  "  <h1>{$MESSAGES[MSG_HANGAR][m000]}</h1>";
  $pfuschOutput .= "
      <table border=0 cellpadding=3 cellspacing=3>
      <tr>
        <td colspan=2 align=center class=table_head>
          {$MESSAGES[MSG_HANGAR][m004]}
        </td>
      </tr>
      <tr>
        <td>
          {$MESSAGES[MSG_HANGAR][m005]} $p_hangar_fleet[city]
        </td>
        <td>
          $p_gesamt_in_city
        </td>
      </tr>
      <tr>
        <td>
          {$MESSAGES[MSG_HANGAR][m006]}
        </td>
        <td>
          ". sql_num_rows($main_actions) ."
        </td>
      </tr>
      <tr>
        <td>
          {$MESSAGES[MSG_HANGAR][m007]}
        </td>
        <td>
          ". ($p_hangar_fleet[p_gesamt_flugzeuge]-$p_gesamt_in_city-sql_num_rows($main_actions))."
        </td>
      </tr>
      <tr>
        <td colspan=2>
          <hr>
        </td>
      </tr>
      <tr>
        <td>
          {$MESSAGES[MSG_HANGAR][m008]}
        </td>
        <td>
          $p_hangar_fleet[p_gesamt_flugzeuge]
        </td>
      </tr>
      <tr>
        <td>
          {$MESSAGES[MSG_HANGAR][m009]}
        </td>
        <td>
          ". ($buildings["b_{$b_db_name[HANGAR]}"]*PLANES_PER_LEVEL) ."
        </td>
      </tr>
      <tr>
        <td>
          Freier Hangarplatz
        </td>
        <td>
          ". $free ." 
        </td>
      </tr>";

  if (sql_num_rows($main_actions))
  {
    $pfuschOutput .=  "  <tr>
          <td colspan=2>
            <br><br>
          </td>
        </tr>
        <tr>
          <td colspan=2 align=center class=table_head>
            {$MESSAGES[MSG_HANGAR][m010]}
          </td>
        </tr>
        <tr>
          <td colspan=2>
            <table width=100% border=0 cellpadding=0 cellspacing=0>
            <tr>
              <td>
                <b>{$MESSAGES[MSG_HANGAR][m011]}</b>
              </td>
              <td>
                <b>{$MESSAGES[MSG_HANGAR][m012]}</b>
              </td>
              <td align=right>
                <b>{$MESSAGES[MSG_HANGAR][m002]}</b>
              </td>
            </tr>";

    while ($jobs = sql_fetch_array($main_actions))
    {
      $pfuschOutput .=  "  <tr>
            <td>".
              translate_planes($jobs[current_build])
            ."</td>
            <td>".
              maketime($jobs[end_time]-time())
            ."</td>
            <td align=right>
            <form action=\"{$_SERVER['PHP_SELF']}\" method=post>
              <input type=hidden name=action value=\"{$MESSAGES[MSG_HANGAR][m002]}\">
              <input type=hidden name=id value=$jobs[id]>
              <input ondblclick=\"this.disabled=true\" class=button type=submit value=\"{$MESSAGES[MSG_HANGAR][m013]}\">
            </form>
              </td>
          </tr>";
    }

    $pfuschOutput .=  "    </table>
          </td>
        </tr>
        <tr>
          <td colspan=2 align=right>
            <form action=\"{$_SERVER['PHP_SELF']}\" method=post>
            <input onclick=\"if (!confirm('{$MESSAGES[MSG_HANGAR][m021]}')) return false;\" class=button type=submit name=action value=\"{$MESSAGES[MSG_HANGAR][m020]}\">
            </form>
          </td>
        </tr>
        <tr>";
  }

  $pfuschOutput .=  "  <form action=\"{$_SERVER['PHP_SELF']}\" method=post>";


  for ($i=0;$i<ANZAHL_FLUGZEUGE;$i++)
  {
    $allowed = true;
    for ($x=0;$x<=ANZAHL_TECHNOLOGIEN;$x++)
      if ($user_techs[$x] < $p_need[$i][$x])
        $allowed = false;

    if ($catname != $p_category[$i])
    {
      $pfuschOutput .=  "    <tr>
              <td colspan=4>
                <br><br>
              </td>
            </tr>
            <tr>
              <td colspan=4 align=center class=table_head>
                $p_category[$i]
              </td>
            </tr>";
    }

    $catname = $p_category[$i];

	if($tut['tutorial'] == 11 && $i == 9) {
		$allowed = true;
	}elseif($tut['tutorial'] == 11) {
		$allowed = false;
	}
    
    
    if ($allowed || $p_hangar_fleet[$i] > 0)
    {
      if($tut['tutorial'] == 11 && $i == 9) {
      	
      	$duration[$i] = 60;
      	$p_iridium[$i] = 100;
      	$p_holzium[$i] = 200;
      	
      }
      	
      	  $pfuschOutput .=  "  <tr>
            <td>
              <a href=description.php?show=$i&t=p>$p_name[$i]</a> ($p_hangar_fleet[$i])<br>
              {$MESSAGES[MSG_HANGAR][m016]}: $p_iridium[$i] {$MESSAGES[MSG_GENERAL][m000]}, $p_holzium[$i] {$MESSAGES[MSG_GENERAL][m001]}<br>
              ".($allowed ? "{$MESSAGES[MSG_HANGAR][m017]}: ". maketime($duration[$i]):'<i>Herstellung nicht möglich!</i>') ."
            </td>
            <td>
              <input class=button type=text name=p_count[$i]>
            </td>
          </tr>
          <tr>
            <td colspan=2 class=border height=1>
            </td>
          </tr>";
    }
  }

  $too_much_class = "button";

  if ($p_hangar_fleet[p_gesamt_flugzeuge] >= $buildings["b_{$b_db_name[HANGAR]}"]*PLANES_PER_LEVEL)
  {
    $too_much = "disabled";
    $too_much_class = "button_disabled";
  }

  $pfuschOutput .=  "  <tr>
        <td colspan=2 align=right>
          <input ondblclick=\"this.disabled=true\" class=$too_much_class $too_much type=submit name=action value=\"{$MESSAGES[MSG_HANGAR][m001]}\"><br><br>
          <input onclick=\"if (!confirm('{$MESSAGES[MSG_HANGAR][m018]}')) return false;\" class=button type=submit name=action value=\"{$MESSAGES[MSG_HANGAR][m003]}\" ondblclick=\"this.disabled=true\">
        </td>
      </tr>
      </table>
      </form>";

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
