<?php
  // $use_lib = ?; // MSG_ADMINISTRATION
  $add_steps = null;
  require_once("msgs.php");
  require_once("database.php");
  require_once("constants.php");
  require_once("functions.php");
  require_once("do_loop.php");
  include("tutorial.php");

  // define phptal template
  require_once("PHPTAL.php");
  require_once("include/PHPTAL_EtsTranslator.php");
  //$template = new PHPTAL('overview.html');
  $template = new PHPTAL('standard.html');
  $template->setTranslator(new PHPTAL_EtsTranslator());
  $template->setEncoding('ISO-8859-1');

  // set page title
  $template->set('pageTitle', 'Übersichten - Aufträge');

  $pfuschOutput = "";


 // insert specific page logic here


  $MSG = array();

  $sort = $_REQUEST[sort];
  $sh_b = $_REQUEST[sh_b];
  $sh_t = $_REQUEST[sh_t];
  $sh_p = $_REQUEST[sh_p];
  $sh_d = $_REQUEST[sh_d];
  $sh_o = $_REQUEST[sh_o];
  $sh_f = $_REQUEST[sh_f];

  if (!$sort)
  {
    $sort = "time";
    $sh_b = 1;
    $sh_t = 1;
    $sh_p = 1;
    $sh_d = 1;
    $sh_o = 1;
    $sh_f = 1;
  }
  
  switch ($sort)
  {
    default:
    case "time" :
      $to_sort[0] = 0;
      $to_sort[1] = 1;
      break;
    case "city" :
      $to_sort[0] = 1;
      $to_sort[1] = 0;
      break;
  }

  $pfuschOutput .= "  <h1>Auftrags-&Uuml;bersicht aller St&auml;dte</h1>";
  $pfuschOutput .= "

      <table border=0 cellpadding=0 cellspacing=0>
      <form action=\"" . $_SERVER['PHP_SELF'] . "\">
      <tr>
        <td colspan=2 align=center>
          <table border=0 cellpadding=0 cellspacing=0>
          <tr valign=top>
            <td>
              <b>Anzeige:</b><br>
              <input type=checkbox value=checked name=sh_b $sh_b> Geb&auml;ude<br>
              <input type=checkbox value=checked name=sh_t $sh_t> Technologien<br>
              <input type=checkbox value=checked name=sh_p $sh_p> Flugzeuge<br>
              <input type=checkbox value=checked name=sh_d $sh_d> Verteidigungsanlagen<br>
              <input type=checkbox value=checked name=sh_o $sh_o> Eigene Flotten<br>
              <input type=checkbox value=checked name=sh_f $sh_f> Fremde Flotten
            </td>
            <td>
              <b>Sortierung:</b><br>
              <input type=radio value=time name=sort ". (($sort == "time") ? "checked" : "") ."> nach Zeit<br>
              <input type=radio value=city name=sort ". (($sort == "city") ? "checked" : "") ."> nach St&auml;dten<br>
            </td>
          </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td colspan=2 align=center>
          <input type=submit value=Anzeigen class=button>
        </td>
      </tr>
      </form>
      <tr align=center>
        <td colspan=2>
          <br>
        </td>
      </tr>
      <tr class=table_head>
        <td>
          Stadt
        </td>
        <td>
          Zeit / Aktion
        </td>
      </tr>";

  if ($sh_b)
  {
    $get_jobs_build = sql_query("SELECT city.city,jobs_build.current_build,jobs_build.end_time,jobs_build.level,jobs_build.msg FROM jobs_build INNER JOIN city ON jobs_build.city = city.ID WHERE city.user='$_SESSION[user]';");
    while ($jobs_build = sql_fetch_array($get_jobs_build))
    {
      if ($jobs_build[end_time] > time())
      {
        $content[0] = $jobs_build[end_time];
        $content[1] = $jobs_build[city];

        $MSG[$content[$to_sort[0]]][] = $content[$to_sort[1]];
        $MSG[$content[$to_sort[0]]][] = "<font color=\"#44CCEE\">bis Fertigstellung ". translate_buildings($jobs_build[current_build]) ." Ausbaustufe ". ($jobs_build[level]) ." auf $jobs_build[city]</font>";
      }
      if ($jobs_build[end_time] <= time() && $jobs_build[end_time]!=0)
      {
        $content[0] = $jobs_build[end_time];
        $content[1] = $jobs_build[city];

        $MSG[$content[$to_sort[0]]][] = $content[$to_sort[1]];
        $MSG[$content[$to_sort[0]]][] = "<font color=\"#DDDDDD\">". translate_buildings($jobs_build[current_build]) ." Ausbaustufe ". ($jobs_build[level]) ." auf $jobs_build[city] wird er&ouml;ffnet</font>";
      }
    }
  }

  if ($sh_t)
  {
    $get_jobs_tech = sql_query("SELECT current_build,end_time,level,msg FROM jobs_tech WHERE user = '$_SESSION[user]'");
    while ($jobs_tech = sql_fetch_array($get_jobs_tech))
    {
      if ($jobs_tech[end_time] > time())
      {
        $content[0] = $jobs_tech[end_time];
        $content[1] = null;

        $MSG[$content[$to_sort[0]]][] = $content[$to_sort[1]];
        $MSG[$content[$to_sort[0]]][] = "<font color=\"#AACCEE\">bis Fertigstellung ". translate_technologies($jobs_tech[current_build]) ." Ausbaustufe ". ($jobs_tech[level]) ." </font>";
      }
      if ($jobs_tech[end_time] <= time() && $jobs_tech[end_time]!=0)
      {
        $content[0] = $jobs_tech[end_time];
        $content[1] = null;

        $MSG[$content[$to_sort[0]]][] = $content[$to_sort[1]];
        $MSG[$content[$to_sort[0]]][] = "<font color=\"#DDDDDD\">". translate_technologies($jobs_tech[current_build]) ." Ausbaustufe ". ($jobs_tech[level]) ." wird fertiggestellt</font>";
      }
    }
  }

  if ($sh_d)
  {
    $main_actions = sql_query("SELECT city.city,jobs_defense.end_time,jobs_defense.current_build FROM jobs_defense INNER JOIN city ON jobs_defense.city = city.ID WHERE city.user='$_SESSION[user]'");
    while ($actions = sql_fetch_array($main_actions))
    {
      if ($actions[end_time] > time())
      {
        $content[0] = $actions[end_time];
        $content[1] = $actions[city];

        $MSG[$content[$to_sort[0]]][] = $content[$to_sort[1]];
        $MSG[$content[$to_sort[0]]][] = "<font color=\"#EEEE66\">bis Fertigstellung ". translate_defense($actions[current_build]) ." auf $actions[city]</font>";
      }

      if ($actions[end_time] != 0 && $actions[end_time] <= time())
      {
        $content[0] = $actions[end_time];
        $content[1] = $actions[city];

        $MSG[$content[$to_sort[0]]][] = $content[$to_sort[1]];
        $MSG[$content[$to_sort[0]]][] = "<font color=\"#DDDDDD\">Ein ". translate_defense($actions[current_build]) ." wird auf $actions[city] einsatzbereit gemacht</font>";
      }
    }
  }

  if ($sh_p)
  {
    $main_actions = sql_query("SELECT city.city,jobs_planes.end_time,jobs_planes.current_build FROM jobs_planes INNER JOIN city ON jobs_planes.city = city.ID WHERE city.user='$_SESSION[user]'");
    while ($actions = sql_fetch_array($main_actions))
    {
      if ($actions[end_time] > time())
      {
        $content[0] = $actions[end_time];
        $content[1] = $actions[city];

        $MSG[$content[$to_sort[0]]][] = $content[$to_sort[1]];
        $MSG[$content[$to_sort[0]]][] = "<font color=\"#EEEEAA\">bis Fertigstellung ". translate_planes($actions[current_build]) ." auf $actions[city]</font>";
      }

      if ($actions[end_time] != 0 && $actions[end_time] <= time())
      {
        $content[0] = $actions[end_time];
        $content[1] = $actions[city];

        $MSG[$content[$to_sort[0]]][] = $content[$to_sort[1]];
        $MSG[$content[$to_sort[0]]][] = "<font color=\"#DDDDDD\">Ein ". translate_planes($actions[current_build]) ." wird auf $actions[city] einsatzbereit gemacht</font>";
      }
    }
  }

  if ($sh_o)
  {
    $main_actions = sql_query("SELECT actions.id,city.city,actions.f_action,actions.f_arrival,actions.f_target,actions.f_name FROM actions INNER JOIN city ON actions.city = city.ID WHERE actions.user='$_SESSION[user]'");
    while ($actions = sql_fetch_array($main_actions))
    {
      $select = sql_query("SELECT city FROM city WHERE ID = '$actions[f_target]'");
      $select = sql_fetch_array($select);
      $actions[f_target] = $select['city'];
      $actions[f_name] = stripslashes($actions[f_name]);
      if ($actions[f_arrival] > time())
      {
        switch ($actions[f_action])
        {
          case "buy_to_depot" :
          case "sell_to_depot" :
          {
            $content[0] = $actions[f_arrival];
            $content[1] = $actions[city];

            $MSG[$content[$to_sort[0]]][] = $content[$to_sort[1]];
            $MSG[$content[$to_sort[0]]][] = "<font color=\"#FFFF00\">bis Ankunft einer <a href=\"fleets.php?action=detail&id=$actions[id]\" style=\"color:#FFFF00\">Flotte</a> ($actions[city]) im Hauptlager</font>";
            break;
          }
          case "buy_from_depot" :
          case "sell_from_depot" :
          {
            $content[0] = $actions[f_arrival];
            $content[1] = $actions[city];

            $MSG[$content[$to_sort[0]]][] = $content[$to_sort[1]];
            $MSG[$content[$to_sort[0]]][] = "<font color=\"#FFFF00\">bis R&uuml;ckkehr einer <a href=\"fleets.php?action=detail&id=$actions[id]\" style=\"color:#FFFF00\">Flotte</a> ($actions[city]) vom Hauptlager</font>";
            break;
          }
          case "attack" :
          {
            $content[0] = $actions[f_arrival];
            $content[1] = $actions[city];

            $MSG[$content[$to_sort[0]]][] = $content[$to_sort[1]];
            $MSG[$content[$to_sort[0]]][] = "<font color=\"#FFFF00\">bis Ankunft einer <a href=\"fleets.php?action=detail&id=$actions[id]\" style=\"color:#FFFF00\">Flotte</a> ". (($actions[f_name]) ?  "(". sonderz($actions[f_name]) .")" : "") ." von $actions[city] in $actions[f_target] ". (($actions[f_target_user]) ? "($actions[f_target_user])" : "") ."</font>";
            break;
          }
          case "attack_back" :
          {
            $content[0] = $actions[f_arrival];
            $content[1] = $actions[city];

            $MSG[$content[$to_sort[0]]][] = $content[$to_sort[1]];
            $MSG[$content[$to_sort[0]]][] = "<font color=\"#FFFF00\">bis R&uuml;ckkehr einer <a href=\"fleets.php?action=detail&id=$actions[id]\" style=\"color:#FFFF00\">Flotte</a> ". (($actions[f_name]) ?  "(". sonderz($actions[f_name]) .")" : "") ." nach $actions[city] von $actions[f_target] ". (($actions[f_target_user]) ? "($actions[f_target_user])" : "") ."</font>";
            break;
          }
          case "transport" :
          {
            $content[0] = $actions[f_arrival];
            $content[1] = $actions[city];

            $MSG[$content[$to_sort[0]]][] = $content[$to_sort[1]];
            $MSG[$content[$to_sort[0]]][] = "<font color=\"#FFFF00\">bis Ankunft einer <a href=\"fleets.php?action=detail&id=$actions[id]\" style=\"color:#FFFF00\">Flotte</a> ". (($actions[f_name]) ?  "(". sonderz($actions[f_name]) .")" : "") ." von $actions[city] in $actions[f_target] ". (($actions[f_target_user]) ? "($actions[f_target_user])" : "") ."</font>";
            break;
          }
          case "transport_back" :
          {
            $content[0] = $actions[f_arrival];
            $content[1] = $actions[city];

            $MSG[$content[$to_sort[0]]][] = $content[$to_sort[1]];
            $MSG[$content[$to_sort[0]]][] = "<font color=\"#FFFF00\">bis R&uuml;ckkehr einer <a href=\"fleets.php?action=detail&id=$actions[id]\" style=\"color:#FFFF00\">Flotte</a> ". (($actions[f_name]) ?  "(". sonderz($actions[f_name]) .")" : "") ." nach $actions[city] von $actions[f_target] ". (($actions[f_target_user]) ? "($actions[f_target_user])" : "") ."</font>";
            break;
          }
          case "plane_sell" :
          {
            $content[0] = $actions[f_arrival];
            $content[1] = $actions[city];

            $MSG[$content[$to_sort[0]]][] = $content[$to_sort[1]];
            $MSG[$content[$to_sort[0]]][] = "<font color=\"#FFFF00\">bis Ankunft einer <a href=\"fleets.php?action=detail&id=$actions[id]\" style=\"color:#FFFF00\">Flotte</a> im Hauptlager</font>";
            break;
          }
          case "plane_buy" :
          {
            $content[0] = $actions[f_arrival];
            $content[1] = $actions[city];

            $MSG[$content[$to_sort[0]]][] = $content[$to_sort[1]];
            $MSG[$content[$to_sort[0]]][] = "<font color=\"#FFFF00\">bis Ankunft einer <a href=\"fleets.php?action=detail&id=$actions[id]\" style=\"color:#FFFF00\">Flotte</a> vom Hauptlager</font>";
            break;
          }
        }
      }

      if ($actions[f_arrival] <= time())
      {
        switch ($actions[f_action])
        {
          case "buy_to_depot" :
          case "sell_to_depot" :
          {
            $content[0] = $actions[f_arrival];
            $content[1] = $actions[city];

            $MSG[$content[$to_sort[0]]][] = $content[$to_sort[1]];
            $MSG[$content[$to_sort[0]]][] = "<font color=\"#FFFF00\">bis Ankunft einer <a href=\"fleets.php?action=detail&id=$actions[id]\" style=\"color:#FFFF00\">Flotte</a> ($actions[city]) im Hauptlager</font>";
            break;
          }
          case "buy_from_depot" :
          case "sell_from_depot" :
          case "attack_back" :
          case "transport_back" :
          case "plane_buy" :
          {
            $content[0] = $actions[f_arrival];
            $content[1] = $actions[city];

            $MSG[$content[$to_sort[0]]][] = $content[$to_sort[1]];
            $MSG[$content[$to_sort[0]]][] = "<font color=\"#DDDDDD\">Eine <a href=\"fleets.php?action=detail&id=$actions[id]\" style=\"color:#DDDDDD\">Flotte</a>". (($actions[f_name] != "") ?  " (". sonderz($actions[f_name]) .")" : "") ." ($actions[city]) landete und ist auf dem Weg zur&uuml;ck in den Hangar</font>";
            break;
          }
          case "attack" :
          {
            $content[0] = $actions[f_arrival];
            $content[1] = $actions[city];

            $MSG[$content[$to_sort[0]]][] = $content[$to_sort[1]];
            $MSG[$content[$to_sort[0]]][] = "<font color=\"#DDDDDD\">Eine <a href=\"fleets.php?action=detail&id=$actions[id]\" style=\"color:#DDDDDD\">Flotte</a>". (($actions[f_name] != "") ?  " (". sonderz($actions[f_name]) .")" : "") ." k&auml;mpft gerade in $actions[f_target] ". (($actions[f_target_user]) ? "($actions[f_target_user])" : "") ."</font>";
            break;
          }
          case "transport" :
          {
            $content[0] = $actions[f_arrival];
            $content[1] = $actions[city];

            $MSG[$content[$to_sort[0]]][] = $content[$to_sort[1]];
            $MSG[$content[$to_sort[0]]][] = "<font color=\"#DDDDDD\">Eine <a href=\"fleets.php?action=detail&id=$actions[id]\" style=\"color:#DDDDDD\">Flotte</a>". (($actions[f_name] != "") ?  " (". sonderz($actions[f_name]) .")" : "") ." &uuml;berbringt gerade Rohstoffe an $actions[f_target] ". (($actions[f_target_user]) ? "($actions[f_target_user])" : "") ."</font>";
            break;
          }
          case "plane_sell" :
          {
            $content[0] = $actions[f_arrival];
            $content[1] = $actions[city];

            $MSG[$content[$to_sort[0]]][] = $content[$to_sort[1]];
            $MSG[$content[$to_sort[0]]][] = "<font color=\"#DDDDDD\">Eine <a href=\"fleets.php?action=detail&id=$actions[id]\" style=\"color:#DDDDDD\">Flotte</a> verkauft gerade Flugzeuge an das Hauptlager</font>";
            break;
          }
        }
      }
    }
  }

  if ($sh_f)
  {
    $main_actions = sql_query("SELECT city.city,actions.f_target,actions.f_action,actions.f_arrival,actions.f_name,actions.f_name_show FROM actions INNER JOIN city ON actions.city = city.ID WHERE actions.f_target_user='$_SESSION[user]'");
    while ($actions = sql_fetch_array($main_actions))
    {
      $select = sql_query("SELECT city FROM city WHERE ID = '$actions[f_target]'");
      $select = sql_fetch_array($select);
      $actions[f_target] = $select['city'];
      $actions[f_name] = stripslashes($actions[f_name]);
      if ($actions[f_action] != "" && $actions[f_arrival] > time())
      {
        switch ($actions[f_action])
        {
          case "attack" :
          {
            $content[0] = $actions[f_arrival];
            $content[1] = $actions[f_target];

            $MSG[$content[$to_sort[0]]][] = $content[$to_sort[1]];
            $MSG[$content[$to_sort[0]]][] = "<font color=\"#FF0000\">bis Ankunft einer feindlichen Flotte ". (($actions[f_name_show] == "YES") ? (sonderz($actions[f_name])) : "") ." von $actions[city] in $actions[f_target]</font>";
            break;
          }
          case "transport" :
          {
            $content[0] = $actions[f_arrival];
            $content[1] = $actions[f_target];

            $MSG[$content[$to_sort[0]]][] = $content[$to_sort[1]];
            $MSG[$content[$to_sort[0]]][] = "<font color=\"#00FF00\">bis Ankunft einer friedlichen Flotte ". (($actions[f_name_show] == "YES") ? (sonderz($actions[f_name])) : "") ." von $actions[city] in $actions[f_target]</font>";
            break;
          }
        }
      }

      if (substr_count($actions[f_action],"_back") == 0 && substr_count($actions[f_action],"plane_") == 0 && substr_count($actions[f_action],"_from_depot") == 0 && $actions[f_arrival] <= time() && $actions[f_arrival]!=0)
      {
        $content[0] = $actions[f_arrival];
        $content[1] = $actions[f_target];

        $MSG[$content[$to_sort[0]]][] = $content[$to_sort[1]];
        $MSG[$content[$to_sort[0]]][] = "<font color=\"#DDDDDD\">Eine ankommende Flotte erreichte gerade $actions[f_target]</font>";
      }
    }
  }

  if($sort == 'city') {
    function sortByCity($city1,$city2) {
        preg_match('/([0-9]{1}):([0-9]{1,3}):([0-9]{1,2})/imU',$city1,$cd1);
        preg_match('/([0-9]{1}):([0-9]{1,3}):([0-9]{1,2})/imU',$city2,$cd2);
        foreach($cd1 as $k=>$v) {
            if(intval($v) == intval($cd2[$k])) {
                continue;
            } else if(intval($v) < intval($cd2[$k])) {
                return -1;
            } else {
                return 1;
            }
        }
        return 0;
    }
    uksort($MSG,'sortByCity');
  } else {
    ksort($MSG);
  }


  $line = 0;
  foreach ($MSG as $sh_index => $sh_value)
  {
    if (preg_match("/^\d{10}(\.|)(\d)*?$/",$sh_index))
    {
      for ($i=0;$i<count($sh_value);$i+=2)
      {
        $zeit = $sh_index;
        $stadt = $sh_value[$i];
        $nachricht = $sh_value[$i + 1];

        if ($line%2)
          $color = "#222222";
        else
          $color = "#000000";

        $pfuschOutput .= "  <tr valign=top bgcolor=$color>
              <td>
                $stadt
              </td>";

        if ($zeit > time())
        {
          $pfuschOutput .= "  <td>
                Noch ". maketime($zeit - time()) ." (". date("H:i",$zeit) ."<font class=seconds>:". date("s",$zeit) ."</font> ". date("d.m.Y",$zeit) .")<br>
                $nachricht
              </td>";
        }
        else
        {
          $pfuschOutput .= "  <td>
                <i>". ETSZeit($zeit) ."<br>
                $nachricht</i>
              </td>";
        }

        $pfuschOutput .= "  </tr>";
        $line++;
      }
    }
    else
    {
      $stadt = $sh_index;

      $sort_by = null;
      $event_msg = null;
      for ($i=0;$i<count($sh_value);$i+=2)
      {
        $sort_by[] = $sh_value[$i];
        $event_msg[] = $sh_value[$i+1];
      }
      array_multisort($sort_by, $event_msg);
      $elements = count($sort_by);

      for ($i = 0; $i <  $elements; $i++)
      {
        $output_index = $sort_by[$i];
        $output_value = $event_msg[$i];
        if ($i%2)
          $color = "#222222";
        else
          $color = "#000000";

        $pfuschOutput .= "  <tr valign=top bgcolor=$color>
              <td>
                $stadt
              </td>";

        if ($output_index > time())
        {
          $pfuschOutput .= "  <td>
                Noch ". maketime($output_index - time()) ." (". date("H:i",$output_index) ."<font class=seconds>:". date("s",$output_index) ."</font> ". date("d.m.Y",$output_index) .")<br>
                $output_value
              </td>";
        }
        else
        {
          $pfuschOutput .= "  <td>
                <i>". ETSZeit($output_index) ."<br>
                $output_value</i>
              </td>";
        }

        $pfuschOutput .= "  </tr>";
      }

      $pfuschOutput .= "    <tr>
              <td colspan=2>
                <br>
              </td>
            </tr>";
    }

  }

  if (!count($MSG))
  {
    $pfuschOutput .= "  <tr>
          <td colspan=2 align=center>
            Zur Zeit keine Auftr&auml;ge
          </td>
        </tr>";
  }

  $pfuschOutput .= "  </table>";

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
