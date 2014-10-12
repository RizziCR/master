<?php
  $use_lib = 24; // MSG_ADMINISTRATION

  require_once("msgs.php");
  require_once("database.php");
  require_once("constants.php");
  require_once("functions.php");
  require_once("include/class_Party.php");
  require_once("do_loop.php");

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


 // insert specific page logic here

  $name=stripslashes($_REQUEST[$name]);
  $_GET[t] = addslashes($_GET[t]);
  $show = addslashes($_GET[show]);
  $_GET[step] = addslashes($_GET[step]);
  $max = $_REQUEST[max];

  if ($max/10 != round($max/10))
  {
    $pfuschOutput .= "  <table width=750 border=0 cellpadding=0 cellspacing=0>";
    $pfuschOutput .= "  <tr>
          <td>
            <font class=error>{$MESSAGES[MSG_DESC]['m000']}</font><br><br>
          <td>
        </tr>
        </table>";

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

  $get_techs = sql_query("SELECT t_". implode(",t_",$t_db_name) ." FROM usarios WHERE ID='$_SESSION[user]'");
  $user_techs = sql_fetch_array($get_techs);

  switch ($_GET[t])
  {
    case "b" :
      $get_step = sql_query("SELECT b_{$b_db_name[$show]} AS stufe FROM city WHERE ID='$_SESSION[city]'");
      $res_step = sql_fetch_array($get_step);
      $_GET[step] = $res_step[stufe];
      $add_query_string = "&show=$show&t=$_GET[t]";

      if (!isset($max))
        $max = ceil($_GET[step]/10+0.01)*10;

      $quantitiy = "YES";

      switch ($show)
      {
        case IR_MINE :    $content = $MESSAGES[MSG_DESC]['m001']; break;
        case HZ_PLANTAGE :  $content = $MESSAGES[MSG_DESC]['m001']; break;
        case WA_DERRICK :  $content = $MESSAGES[MSG_DESC]['m001']; break;
        case OX_REACTOR :  $content = $MESSAGES[MSG_DESC]['m001']; break;
        case DEPOT :    $content = $MESSAGES[MSG_DESC]['m002']; break;
        case OX_DEPOT :    $content = $MESSAGES[MSG_DESC]['m002']; break;
        case HANGAR :    $content = $MESSAGES[MSG_DESC]['m003']; break;
        case AIRPORT :    $content = $MESSAGES[MSG_DESC]['m004'];
                    $get_techs = sql_query("SELECT t_{$t_db_name[COMP_MANAGEMENT]} FROM usarios WHERE ID='$_SESSION[user]'");
                    $user_techs = sql_fetch_array($get_techs);
                    break;
        case COMM_CENTER :  $content = $MESSAGES[MSG_DESC]['m005']; break;
        case TRADE_CENTER :  $content = $MESSAGES[MSG_DESC]['m006']; break;
        case DEF_CENTER :  $content = $MESSAGES[MSG_DESC]['m007']; break;
//        case SHIELD :    $content = $MESSAGES[MSG_DESC]['m008']; break;
      }

      $resource1 = $MESSAGES[MSG_GENERAL]['m000'];
      $resource2 = $MESSAGES[MSG_GENERAL]['m001'];
      $title = $b_name[$show];
      $description = "<img src=\"{$_SESSION[user_path]}/pics/$b_db_name[$show].jpg\"><br><br>$b_description[$show]";

      if ($max > 10)
      {
        $formel_res1[] = price($b_iridium[$show],$max-11,$b_pricing_iridium[$show]);
        $formel_res2[] = price($b_holzium[$show],$max-11,$b_pricing_holzium[$show]);
      }
      else
      {
        $formel_res1[] = 0;
        $formel_res2[] = 0;
      }

      for ($i=$max-10;$i<$max;$i++)
      {
        switch ($show)
        {
          case IR_MINE :    $formel[] = number_format(Foerderung(IRIDIUM,$i,0),0,',','.') ." + ". number_format((Foerderung(IRIDIUM,$i,$user_techs[t_mining])-Foerderung(IRIDIUM,$i,0)),0,',','.') ." = ". number_format(Foerderung(IRIDIUM,$i,$user_techs[t_mining]),0,',','.'); break;
          case HZ_PLANTAGE :  $formel[] = number_format(Foerderung(HOLZIUM,$i,0),0,',','.') ." + ". number_format((Foerderung(HOLZIUM,$i,$user_techs[t_mining])-Foerderung(HOLZIUM,$i,0)),0,',','.') ." = ". number_format(Foerderung(HOLZIUM,$i,$user_techs[t_mining]),0,',','.'); break;
          case WA_DERRICK :  $formel[] = number_format(Foerderung(WATER,$i,null),0,',','.'); break;
          case OX_REACTOR :  $formel[] = number_format(Foerderung(OXYGEN,$i,0,1),0,',','.') ." + ". number_format((Foerderung(OXYGEN,$i,$user_techs[t_water_compression],1)-Foerderung(OXYGEN,$i,0,1)),0,',','.') ." = ". number_format(Foerderung(OXYGEN,$i,$user_techs[t_water_compression],1),0,',','.'); break;
          case DEPOT :    $formel[] = number_format(Lager(DEPOT,$i,0),0,',','.') ." + ". number_format((Lager(DEPOT,$i,$user_techs[t_depot_management])-Lager(DEPOT,$i,0)),0,',','.') ." = ". number_format(Lager(DEPOT,$i,$user_techs[t_depot_management]),0,',','.'); break;
          case OX_DEPOT :    $formel[] = number_format(Lager(OX_DEPOT,$i,0),0,',','.') ." + ". number_format((Lager(OX_DEPOT,$i,$user_techs[t_depot_management])-Lager(OX_DEPOT,$i,0)),0,',','.') ." = ". number_format(Lager(OX_DEPOT,$i,$user_techs[t_depot_management]),0,',','.'); break;
          case HANGAR :    $formel[] = $i * PLANES_PER_LEVEL; break;
          case AIRPORT :    $formel[] = ($i * 5) ." + ". ($user_techs[t_computer_management] * 3); break;
          case COMM_CENTER :  $formel[] = numberOfColonies($i); break;
          case TRADE_CENTER :  $formel[] = TradeCenterCapacity($i); break;
          case DEF_CENTER :  $formel[] = $i * TURRETS_PER_LEVEL; break;
//          case SHIELD :    $formel[] = (int)round(Shield($i,0,$i)) ." + ". (int)round((Shield($i,$user_techs[t_shield_tech],$i) - Shield($i,0,$i))) ." = ". (int)round(Shield($i,$user_techs[t_shield_tech],$i)); break;
        }

        $stufe[] = $i;
        $formel_res1[] = price($b_iridium[$show],$i,$b_pricing_iridium[$show]);
        $formel_res2[] = price($b_holzium[$show],$i,$b_pricing_holzium[$show]);

        if ($_GET[step] == $i)
          $color[] = "#00FF00";
        else
          $color[] = "";
      }

      break;

    case "t" :
      $get_step = sql_query("SELECT t_{$t_db_name[$show]} AS stufe FROM usarios WHERE ID='$_SESSION[user]'");
      $tech_step = sql_fetch_array($get_step);
      $_GET[step] = $tech_step[stufe];
      $add_query_string = "&show=$show&t=$_GET[t]";
      if (!isset($max))
        $max = ceil($_GET[step]/10+0.01)*10;

      $quantitiy = "YES";

      $resource1 = $MESSAGES[MSG_GENERAL]['m001'];
      $resource2 = $MESSAGES[MSG_GENERAL]['m003'];
      $title = $t_name[$show];
      $description = "<img src=\"{$_SESSION[user_path]}/pics/$t_db_name[$show].jpg\"><br><br>$t_description[$show]";

      if ($max > 10)
      {
        $formel_res1[] = price($t_holzium[$show],$max-11,$t_pricing_holzium[$show]);
        $formel_res2[] = price($t_oxygen[$show],$max-11,$t_pricing_oxygen[$show]);
      }
      else
      {
        $formel_res1[] = 0;
        $formel_res2[] = 0;
      }

      for ($i=$max-10;$i<$max;$i++)
      {
        $stufe[] = $i;
        $formel_res1[] = price($t_holzium[$show],$i,$t_pricing_holzium[$show]);
        $formel_res2[] = price($t_oxygen[$show],$i,$t_pricing_oxygen[$show]);

        if ($_GET[step] == $i)
          $color[] = "#00FF00";
        else
          $color[] = "";
      }

      break;

    case "p" :
      $plane = "YES";

      $title = $p_name[$show];
      $description = $p_description[$show];
      $resource1 = $p_iridium[$show];
      $resource2 = $p_holzium[$show];
      if($p_power[$show] != 0)
      $strange = "$p_power[$show] + "
      .Party::getPlaneKW($p_tech[$show][T_POWER], 0, $t_increase[$p_tech[$show][T_POWER]],
          $user_techs[$p_tech[$show][T_POWER]])
          ." (<font color=#00FF00>". Party::getPlaneKW($p_tech[$show][T_POWER],
          $p_power[$show], $t_increase[$p_tech[$show][T_POWER]],
          $user_techs[$p_tech[$show][T_POWER]]) ."</font>)";
      else
        $strange = $MESSAGES[MSG_DESC]['m009'];
      $speed = "$p_speed[$show] + ". ($t_increase[$p_tech[$show][T_SPEED]] * $user_techs[$p_tech[$show][T_SPEED]]) ." (<font color=#00FF00>". ($p_speed[$show] + $t_increase[$p_tech[$show][T_SPEED]] * $user_techs[$p_tech[$show][T_SPEED]]) ."</font>)";
      $consumption = "$p_consumption[$show] - ". round($p_consumption[$show] - $p_consumption[$show] * pow($t_increase[CONSUMPTION],$user_techs[CONSUMPTION]),2) ." (<font color=#00FF00>". round($p_consumption[$show] * pow($t_increase[CONSUMPTION],$user_techs[CONSUMPTION]),2) ."</font>)";
      $capacity = "$p_capacity[$show] + ". round($p_capacity[$show] * pow($t_increase[PLANE_SIZE],$user_techs[PLANE_SIZE]) - $p_capacity[$show]) ." (<font color=#00FF00>". round($p_capacity[$show] * pow($t_increase[PLANE_SIZE],$user_techs[PLANE_SIZE])) ."</font>)";
      $duration = $p_duration[$show];

      break;

    case "d" :
      $defense = "YES";

      $title = $d_name[$show];
      $description = "<br><img src=\"{$_SESSION[user_path]}/pics/$d_db_name[$show].jpg\"><br><br>$d_description[$show]";
      $resource1 = $d_iridium[$show];
      $resource2 = $d_holzium[$show];
      $strange = "$d_power[$show] + ". ($t_increase[$d_tech[$show][T_POWER]] * $user_techs[$d_tech[$show][T_POWER]]) . " (<font color=#00FF00>". ($d_power[$show] + $t_increase[$d_tech[$show][T_POWER]] * $user_techs[$d_tech[$show][T_POWER]]) ."</font>)";

      break;

    default :
      $title = $MESSAGES[MSG_DESC]['e000'];
      $description = $MESSAGES[MSG_DESC]['e001'];
      break;
  }

  if ($plane == "YES")
    $colspan = 2;
  else
    $colspan = 4;
$pfuschOutput .= "  <table width=750 border=0 cellpadding=0 cellspacing=0>  
		<tr>
        <td align=center colspan=$colspan>
          <h1>$title</h1>
        <td>
      </tr>
      ";
      
  if($plane != "YES") 
  {
  	$pfuschOutput .= "<tr>
        <td align=center colspan=$colspan>
          $description<br><br><br><br>
        </td>
      </tr>";
  }
/* else {
 	$pfuschOutput .= "<tr>
 				<td align=center colspan=$colspan>
 					<br><img src=\"{$_SESSION[user_path]}/pics/$p_db_name_wus[$show].jpg\"><br><br><br>
 				</td>
 				</tr>";
 }*/
  

  if ($quantitiy == "YES")
  {
    $pfuschOutput .= "
      <tr>
        <td align=center>
          <b>{$MESSAGES[MSG_DESC]['m010']}</b>
        </td>
        <td align=center>
          <b>$content</b>
        </td>
        <td align=center>
          <b>$resource1</b>
        </td>
        <td align=center>
          <b>$resource2</b>
        </td>
      </tr>";

    for ($a=0;$a<10;$a++)
    {
      $pfuschOutput .= "
      <tr>
        <td align=center>
          <font style=\"color:$color[$a]\">$stufe[$a]</font>
        </td>
        <td align=center>
          <font style=\"color:$color[$a]\">$formel[$a]</font>
        </td>
        <td align=center>
          <font style=\"color:$color[$a]\">$formel_res1[$a]</font>
        </td>
        <td align=center>
          <font style=\"color:$color[$a]\">$formel_res2[$a]</font>
        </td>
      </tr>";
    }

    $pfuschOutput .= "
      <tr>
        <td align=center colspan=5>
          <br>
          <table border=0 cellpadding=0 cellspacing=0>
          <tr>";

    if ($max-10 > 0)
    {
      $pfuschOutput .= "    <td valign='right'>
              <form action=\"{$_SERVER['PHP_SELF']}\">
                <input type=hidden name=max value=". ($max-10) .">
                <input type=hidden name=show value=$show>
                <input type=hidden name=step value=$_GET[step]>
                <input type=hidden name=t value={$_GET[t]}>
                <input class=button type=submit value=\"Zur&uuml;ck\">
              </form>
            </td>";
    }else{
    	$pfuschOutput .= "<td>&nbsp;</td>";
    }
    $pfuschOutput .= "      <td valign='right'>
              <form action=\"{$_SERVER['PHP_SELF']}\">
                <input type=hidden name=max value=". ($max+10) .">
                <input type=hidden name=show value=$show>
                <input type=hidden name=step value=$_GET[step]>
                <input type=hidden name=t value={$_GET[t]}>
                <input class=button type=submit value=\"Weiter\">
              </form>
            </td>
          </tr>
          </table>
        <td>
      </tr>";
  }

  if ($plane == "YES" || $defense == "YES")
  {
    $pfuschOutput .= "
      <tr class=table_head>
        <td>
          <b>{$MESSAGES[MSG_DESC]['m011']}</b>
        </td>
        <td>
          <b>{$MESSAGES[MSG_DESC]['m012']}</b>
        </td>
      </tr>
      <tr>
        <td>
          {$MESSAGES[MSG_GENERAL]['m000']}
        </td>
        <td>
          $resource1
        </td>
      </tr>
      <tr bgcolor=#222222>
        <td>
          {$MESSAGES[MSG_GENERAL]['m001']}
        </td>
        <td>
          $resource2
        </td>
      </tr>
      <tr>
        <td>
          {$MESSAGES[MSG_DESC]['m008']}
        </td>
        <td>
          $strange
        </td>
      </tr>";
  }

  if ($plane == "YES")
  {
    $pfuschOutput .= "
      <tr bgcolor=#222222>
        <td>
          {$MESSAGES[MSG_DESC]['m013']}
        </td>
        <td>
          $speed
        </td>
      </tr>
      <tr>
        <td>
          {$MESSAGES[MSG_DESC]['m014']}
        </td>
        <td>
          $consumption
        </td>
      </tr>
      <tr bgcolor=#222222>
        <td>
          {$MESSAGES[MSG_DESC]['m015']}
        </td>
        <td>
          $capacity
        </td>
      </tr>
      <tr>
        <td>
          {$MESSAGES[MSG_DESC]['m016']}
        </td>
        <td>
          ".maketime($duration)."
        </td>
      </tr>
      <tr>
  <td align=center colspan=$colspan>
  <br><br><br><br><img src=\"{$_SESSION[user_path]}/pics/$p_db_name_wus[$show].jpg\"><br><br><br>
  </td>
  </tr>
      <tr>
        <td align=center colspan=$colspan>
          <br><br>$description
        </td>
      </tr>";
  }
  
  $pfuschOutput .= "    </table>";

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
