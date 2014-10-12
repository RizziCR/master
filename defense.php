<?php
  $use_lib = 5; // MSG_DEF_CENTER

  $add_ir = 0;
  $add_hz = 0;

  require_once("msgs.php");
  require_once("database.php");
  require_once("constants.php");
  require_once("functions.php");
  require_once("do_loop.php");

  // define phptal template
  require_once("PHPTAL.php");
  require_once("include/PHPTAL_EtsTranslator.php");
  //$template = new PHPTAL('defense.html');
  $template = new PHPTAL('standard.html');
  $template->setTranslator(new PHPTAL_EtsTranslator());
  $template->setEncoding('ISO-8859-1');

  // set page title
  $template->set('pageTitle', 'Stadt - Verteidigungszentrum');

  $pfuschOutput = "";

  include("tutorial.php");
  
 // insert specific page logic here
  $get_buildings = sql_query("SELECT city,b_defense_center FROM city WHERE ID='$_SESSION[city]'");

  $buildings = sql_fetch_array($get_buildings);

  if ($buildings[b_defense_center] <= 0)
  {
    ErrorMessage(MSG_DEF_CENTER,e000);
    // Sie m¸ssen erst ein Verteidigungszentrum bauen, um diese Funktion nutzen zu kˆnnen
  }

  if (ErrorMessage(0))
  {

    $errorMessage .= "  <h1>{$MESSAGES[MSG_DEF_CENTER][m000]}</h1>";
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

  $main_query = sql_query("SELECT d_". implode(",d_",$d_db_name) .",d_". implode("+d_",$d_db_name) ." AS d_gesamt_defensive FROM city WHERE ID='$_SESSION[city]'");
  $d_city = sql_fetch_array($main_query);

  $main_actions = sql_query("SELECT id,end_time,current_build FROM jobs_defense WHERE city='$_SESSION[city]' ORDER BY end_time");

  $get_techs = sql_query("SELECT t_{$t_db_name[O_DRIVE]},t_{$t_db_name[H_DRIVE]},t_{$t_db_name[A_DRIVE]},t_{$t_db_name[E_WEAPONS]},t_{$t_db_name[P_WEAPONS]},t_{$t_db_name[N_WEAPONS]},t_{$t_db_name[CONSUMPTION]},t_{$t_db_name[COMP_MANAGEMENT]} FROM usarios WHERE ID='$_SESSION[user]'");
  $user_techs = sql_fetch_array($get_techs);


  $qry_chk = 0;

  $d_count = $_POST[d_count];
  if ($_POST[action] == $MESSAGES[MSG_DEF_CENTER][m003]) // Abreiﬂen
  {
    for ($i=0;$i<ANZAHL_DEFENSIVE;$i++)
    {
      if ((int)$d_count[$i] > 0 && (int)$d_count[$i] <= $d_city[$i])
      {
        $d_gesamt += (int)$d_count[$i];
        $abriss_query[] = "d_$d_db_name[$i]=d_$d_db_name[$i]-". (int)$d_count[$i];
        $qry_chk++;
        $d_city[$i] -= (int)$d_count[$i];
        $d_city[d_gesamt_defensive] -= (int)$d_count[$i];;
      }
    }

    if (count($abriss_query) != $qry_chk)
    {
      $moepp = serialize($abriss_query);
      mail($securityEmail,"bugusing","user:$_SESSION[user]\n\n". addslashes($moepp));
    }

    if (!count($abriss_query))
    {
      ErrorMessage(MSG_DEF_CENTER,e005);
      // Ung&uuml;ltige Menge
    }

    if (ErrorMessage(0))
    {
      $errorMessage .= "  <h1>{$MESSAGES[MSG_DEF_CENTER][m000]}</h1>";
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

    sql_query("UPDATE city SET ". implode(",",$abriss_query) ." WHERE ID='$_SESSION[city]'");
    sql_query("INSERT INTO news_er (city,topic,time) VALUES ('$_SESSION[city]','Sie haben $d_gesamt Verteidigungsanlage(n) eliminiert','". time() ."')");
  }

  for ($i=0;$i<ANZAHL_DEFENSIVE;$i++)
    $duration[$i] = $d_duration[$i]/$buildings["b_{$b_db_name[DEF_CENTER]}"];
  
  if($tut['tutorial'] == 10)
  	$duration[0] = 1;

  if ($_POST[action] == $MESSAGES[MSG_DEF_CENTER][m001]) // Bauen
  {
    if (sql_num_rows($main_actions))
    {
      $get_last_job = sql_query("SELECT Max(end_time) FROM jobs_defense WHERE city='$_SESSION[city]'");
      $last_job = sql_fetch_array($get_last_job);
      $end_time = $last_job[0];
    }
    else
      $end_time = time();

    $gesamt_defs = $d_city[d_gesamt_defensive];

    for ($i=0;$i<ANZAHL_DEFENSIVE && !ErrorMessage(0);$i++)
    {
      if ($d_count[$i] > 0)
      {
        $allowed = true;
        if($tut['tutorial'] != 10) {
	        for ($x=0;$x<=N_WEAPONS;$x++)
	        {
	          if ($user_techs[$x] < $d_need_techs[$i][$x])
	          {
	            $allowed = false;
	
	            ErrorMessage(MSG_DEF_CENTER,e001);
	            // Sie besitzen nicht einen ausreichenden Forschungsstand, um diese Verteidigungsanlage(n) bauen zu kˆnnen
	
	            break 2;
	          }
	        }
	
	        if ($buildings[b_defense_center] < $d_need_builds[$i][DEF_CENTER])
	        {
	          $allowed = false;
	
	          ErrorMessage(MSG_DEF_CENTER,e004);
	          // Sie besitzen kein ausreichend ausgebautes Verteidigungszentrum, um diese Verteidigungsanlage(n) bauen zu k&ouml;nnen
	
	          break;
	        }
        }
      }

      for ($y=0;$y<$d_count[$i] && !ErrorMessage(0);$y++)
      {
        if ($gesamt_defs + sql_num_rows($main_actions) >= $buildings["b_{$b_db_name[DEF_CENTER]}"]*TURRETS_PER_LEVEL)
        {
          ErrorMessage(MSG_DEF_CENTER,e002);
          // Ihr Verteidigungszentrum bietet nicht genug Platz f¸r soviele Verteidigungsanlagen
          break 2;
        }

        if ($timefixed_depot->getIridium() < $d_iridium[$i] || $timefixed_depot->getHolzium() < $d_holzium[$i])
        {
          ErrorMessage(MSG_DEF_CENTER,e003);
          // Sie haben nicht gen¸gend Rohstoffe
          break 2;
        }

        $timefixed_depot->removeIridium($d_iridium[$i]);
        $timefixed_depot->removeHolzium($d_holzium[$i]);

        $end_time += $duration[$i];
        $gesamt_defs++;

        sql_query("REPLACE INTO jobs_defense (city,user,end_time,current_build,msg) VALUES ('$_SESSION[city]','$_SESSION[user]','". round($end_time) ."','d_$d_db_name[$i]','Ein $d_name[$i] wurde auf $buildings[city] fertiggestellt')");
      }
    }
    $main_actions = sql_query("SELECT id,end_time,current_build FROM jobs_defense WHERE city='$_SESSION[city]' ORDER BY end_time");
  }

  if ($_POST["action"] == $MESSAGES[MSG_DEF_CENTER][m002]) // Abbrechen
  {
    $firstrun = true;
    while ($job = sql_fetch_array($main_actions))
    {
      if ($job[id] == $_POST["id"])
      {
        for($i=0;$i<ANZAHL_DEFENSIVE;$i++)
        {
          if ("d_$d_db_name[$i]" == $job[current_build])
          {
            $reduce_factor = ($job[end_time] - time()) / ($duration[$i]);

            $reduce_factor = min($reduce_factor, 0.8);

            $timefixed_depot->addIridium($d_iridium[$i] * $reduce_factor);
            $timefixed_depot->addHolzium($d_holzium[$i] * $reduce_factor);

            if ($firstrun)
              $sub_time = $job[end_time] - time();
            else
              $sub_time = $duration[$i];

            // Bezug <> ID immer direkt?!
            sql_query("UPDATE jobs_defense SET end_time=end_time-$sub_time WHERE city='$_SESSION[city]' && end_time>'$job[end_time]'"); // id>'$_POST[id]'
            sql_query("DELETE FROM jobs_defense WHERE city='$_SESSION[city]' && id='$_POST[id]'");

            break;
          }
        }
      }
      $firstrun = false;
    }
    $main_actions = sql_query("SELECT id,end_time,current_build FROM jobs_defense WHERE city='$_SESSION[city]' ORDER BY end_time");
  }

  if ($_POST["action"] == $MESSAGES[MSG_DEF_CENTER][m018]) // Alle abbrechen
  {
      while ($job = sql_fetch_array($main_actions))
    {
      for($i=0;$i<ANZAHL_DEFENSIVE;$i++)
      {
        if ("d_$d_db_name[$i]" == $job[current_build])
        {
          $reduce_factor = ($job[end_time] - time()) / ($duration[$i]);
          $reduce_factor = min($reduce_factor, 0.8);

          $timefixed_depot->addIridium($d_iridium[$i] * $reduce_factor);
          $timefixed_depot->addHolzium($d_holzium[$i] * $reduce_factor);
        }
      }
    }
    sql_query("DELETE FROM jobs_defense WHERE city='$_SESSION[city]'");

    $main_actions = sql_query("SELECT id,end_time,current_build FROM jobs_defense WHERE city='$_SESSION[city]' ORDER BY end_time");
  }

  if (ErrorMessage(0))
  {

    $errorMessage .= "  <h1>{$MESSAGES[MSG_DEF_CENTER][m000]}</h1>";
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
    
  $free = ($buildings["b_{$b_db_name[DEF_CENTER]}"]*TURRETS_PER_LEVEL)-($d_city[d_gesamt_defensive]+sql_num_rows($main_actions));
  $pfuschOutput .= "  <h1>{$MESSAGES[MSG_DEF_CENTER][m000]}</h1>";
  $pfuschOutput .= "
      <table border=0 cellpadding=3 cellspacing=3>
      <tr>
        <td colspan=2 align=center class=table_head>
          {$MESSAGES[MSG_DEF_CENTER][m004]}
        </td>
      </tr>
      <tr>
        <td>
          {$MESSAGES[MSG_DEF_CENTER][m005]} $buildings[city]
        </td>
        <td>
          $d_city[d_gesamt_defensive]
        </td>
      </tr>
      <tr>
        <td>
          {$MESSAGES[MSG_DEF_CENTER][m015]}
        </td>
        <td>
          ". sql_num_rows($main_actions) ."
        </td>
      </tr>
      <tr>
        <td colspan=2>
          <hr>
        </td>
      </tr>
      <tr>
        <td>
          {$MESSAGES[MSG_DEF_CENTER][m016]}
        </td>
        <td>
          ". ($d_city[d_gesamt_defensive]+sql_num_rows($main_actions)) ."
        </td>
      </tr>
      <tr>
        <td>
          {$MESSAGES[MSG_DEF_CENTER][m006]}
        </td>
        <td>
          ". ($buildings["b_{$b_db_name[DEF_CENTER]}"]*TURRETS_PER_LEVEL) ."
        </td>
      </tr>
      <tr>
        <td>
          Noch freier Platz
        </td>
        <td>
          ". $free ."
        </td>
      </tr>
      <tr>
        <td colspan=2>
          <br><br>
        </td>
      </tr>";

  if (sql_num_rows($main_actions))
  {
    $pfuschOutput .= "  <tr>
          <td colspan=2 align=center class=table_head>
            {$MESSAGES[MSG_DEF_CENTER][m007]}
          </td>
        </tr>
        <tr>
          <td colspan=2>
            <table width=100% border=0 cellpadding=0 cellspacing=0>
            <tr>
              <td>
                <b>{$MESSAGES[MSG_DEF_CENTER][m008]}</b>
              </td>
              <td>
                <b>{$MESSAGES[MSG_DEF_CENTER][m009]}</b>
              </td>
              <td align=right>
                <b>{$MESSAGES[MSG_DEF_CENTER][m002]}</b>
              </td>
            </tr>";

    while ($jobs = sql_fetch_array($main_actions))
    {
      $pfuschOutput .= "  <tr>
            <td>".
              translate_defense($jobs[current_build])
            ."</td>
            <td>".
              maketime($jobs[end_time] - time())
            ."</td>
            <td align=right>
            <form action={$_SERVER['PHP_SELF']} method=post>
              <input type=hidden name=action value=\"{$MESSAGES[MSG_DEF_CENTER][m002]}\">
              <input type=hidden name=id value=$jobs[id]>
              <input ondblclick=\"this.disabled=true\" class=button type=submit value=\"{$MESSAGES[MSG_DEF_CENTER][m010]}\">
            </form>
            </td>
          </tr>";
    }

    $pfuschOutput .= "    </table>
          </td>
        </tr>
        <tr>
          <td colspan=2 align=right>
            <form action=\"{$_SERVER['PHP_SELF']}\" method=post>
            <input onclick=\"if (!confirm('{$MESSAGES[MSG_DEF_CENTER][m019]}')) return false;\" class=button type=submit name=action value=\"{$MESSAGES[MSG_DEF_CENTER][m018]}\">
            </form>
          </td>
        </tr>
        <tr>
          <td colspan=2>
            <br><br>
          </td>
        </tr>";
  }

  $pfuschOutput .= "  <form action=\"{$_SERVER['PHP_SELF']}\" method=post>
      <tr>
        <td colspan=2 align=center class=table_head>
          {$MESSAGES[MSG_DEF_CENTER][m011]}
        </td>
      </tr>";


  for ($i=0;$i<ANZAHL_DEFENSIVE;$i++)
  {
    $allowed = true;
    if ($user_techs[$d_tech[$i][T_POWER]] < $d_need_techs[$i][$d_tech[$i][T_POWER]])
      $allowed = false;

//    if ($buildings[$d_tech[$i][T_BUILD1]] < $d_need_builds[$i][$d_tech[$i][T_BUILD1]])
      if ($buildings[b_defense_center] < $d_need_builds[$i][$d_tech[$i][T_BUILD1]])
      $allowed = false;

    if ($i == E_SEQUENZER && $user_techs[E_WEAPONS] > 0 && $buildings["b_{$b_db_name[DEF_CENTER]}"] >= 3)
    {
      $pfuschOutput .= "  <tr>
            <td colspan=2>
              <br><br>
            </td>
          </tr>
          <tr>
            <td colspan=2 align=center class=table_head>
              {$MESSAGES[MSG_DEF_CENTER][m012]}
            </td>
          </tr>";
    }

    if ($allowed || $d_city[$i] > 0)
    {
     if($tut['tutorial'] == 10 && $i == 1) {
     	break;
     }else{ 
      $pfuschOutput .= "  <tr>
            <td>
              <a href=description.php?show=$i&t=d>$d_name[$i]</a> ($d_city[$i])<br>
              {$MESSAGES[MSG_DEF_CENTER][m013]}: $d_iridium[$i] {$MESSAGES[MSG_GENERAL][m000]}, $d_holzium[$i] {$MESSAGES[MSG_GENERAL][m001]}<br>
              ".($allowed ? "{$MESSAGES[MSG_DEF_CENTER][m014]}: ". maketime($duration[$i]):'<i>Herstellung nicht m&ouml;glich!</i>') ."
            </td>
            <td>
              <input class=button type=text name=d_count[$i]>
            </td>
          </tr>
          <tr>
            <td colspan=2 class=border height=1>
            </td>
          </tr>";
      }
    }
  }


  $too_much_class = "button";

  for ($i=0;$i<ANZAHL_DEFENSIVE;$i++)
    $job_max += $d_city[$i];

  if ($job_max >= $buildings["b_{$b_db_name[DEF_CENTER]}"]*TURRETS_PER_LEVEL)
  {
    $too_much = "disabled";
    $too_much_class = "button_disabled";
  }

  $pfuschOutput .= "  <tr>
        <td colspan=2 align=right>
          <input class=$too_much_class $too_much type=submit name=action value=\"{$MESSAGES[MSG_DEF_CENTER][m001]}\" ondblclick=\"this.disabled=true\"><br><br>
          <input onclick=\"if (!confirm('{$MESSAGES[MSG_DEF_CENTER][m017]}')) return false;\" class=button type=submit name=action value=\"{$MESSAGES[MSG_DEF_CENTER][m003]}\" ondblclick=\"this.disabled=true\">
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