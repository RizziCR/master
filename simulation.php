<?php
  $use_lib = 6; // MSG_SIMULATION

  require_once("msgs.php");
  require_once("database.php");
  require_once("constants.php");
  require_once("functions.php");
  require_once("include/class_Party.php");
  require_once("do_loop.php");

// ?def_newbie=1009
// &def_shield=23
// &p_def[0]=0&p_def[1]=0&p_def[2]=0&p_def[3]=48&p_def[4]=0&p_def[5]=896
// &p_def[6]=0&p_def[7]=0&p_def[8]=0&p_def[9]=0&p_def[10]=0&p_def[11]=0
// &d_def[0]=0&d_def[1]=0&d_def[2]=0&d_def[3]=0&d_def[4]=1730&d_def[5]=0

  if ($_POST[action] == "calculate")
  {
    for ($i=0;$i<ANZAHL_KAMPF_FLUGZEUGE;$i++)
    {
      $offense_strength += $_POST[p_off][$i] * Party::getPlaneKW($p_tech[$i][T_POWER], $p_power[$i], $t_increase[$p_tech[$i][T_POWER]], $_POST[off_user_techs][$p_tech[$i][T_POWER]]);
      $defense_strength += $_POST[p_def][$i] * Party::getPlaneKW($p_tech[$i][T_POWER], $p_power[$i], $t_increase[$p_tech[$i][T_POWER]], $_POST[def_user_techs][$p_tech[$i][T_POWER]]);
    }

    $_POST[def_user_techs][$d_tech[0][T_POWER]] = $_POST[def_user_techs][$d_tech[3][T_POWER]];
    $_POST[def_user_techs][$d_tech[1][T_POWER]] = $_POST[def_user_techs][$d_tech[4][T_POWER]];
    $_POST[def_user_techs][$d_tech[2][T_POWER]] = $_POST[def_user_techs][$d_tech[5][T_POWER]];

    for ($i=0;$i<ANZAHL_DEFENSIVE;$i++)
      $defense_strength += $_POST[d_def][$i] * ($d_power[$i] + $t_increase[$d_tech[$i][T_POWER]] * $_POST[def_user_techs][$d_tech[$i][T_POWER]]);

    $defense_strength_of_newbie = NewbieDef($_POST[def_newbie]);
    if ($defense_strength_of_newbie < 0)
      $defense_strength_of_newbie = 0;

    $defense_strength += $defense_strength_of_newbie;
//    $defense_strength_shield = Shield($_POST[def_shield],$_POST[def_sst], $_POST[def_shield]);

//    $probability = attacker_victory_probability($offense_strength, $defense_strength);
      $p_A = 1 / ( 1 + pow($defense_strength/($offense_strength), 1.5));
      $p_B = 1 / ( 1 + pow($offense_strength/$defense_strength, 1.5));
  }

  
  // define phptal template
  require_once("PHPTAL.php");
  require_once("include/PHPTAL_EtsTranslator.php");
  //$template = new PHPTAL('accstat.html');
  $template = new PHPTAL('standard.html');
  $template->setTranslator(new PHPTAL_EtsTranslator());
  $template->setEncoding('ISO-8859-1');

  // include common template settings
  require_once("include/JavaScriptCommon.php");
  require_once("include/TemplateSettingsCommon.php");

  // set page title
  $template->set('pageTitle', 'Kampfsimulator');

  $pfuschOutput = "";


 // insert specific page logic here

  $pfuschOutput .= "  <h1>{$MESSAGES[MSG_SIMULATION][m000]}</h1>

      <form action=\"{$_SERVER['PHP_SELF']}\" method=post>
      <table width=750 border=0 cellpadding=0 cellspacing=0>";

  if ($_POST[action] == "calculate")
  {
    $pfuschOutput .= "  <tr class=table_head>
          <td>
            <b>{$MESSAGES[MSG_SIMULATION][m003]}</b>
          </td>
          <td align=right>
            <b>{$MESSAGES[MSG_SIMULATION][m001]}</b>
          </td>
          <td align=right>
            <b>{$MESSAGES[MSG_SIMULATION][m002]}</b>
          </td>
        </tr>
        <tr>
          <td>
            {$MESSAGES[MSG_SIMULATION][m004]}
          </td>
          <td align=right>
            ".round($offense_strength)."
          </td>
          <td align=right>
            ".round($defense_strength)."
          </td>
        </tr>
        <tr bgcolor=#222222>
          <td>
            {$MESSAGES[MSG_SIMULATION][m005]}
          </td>
          <td align=right>
            ". round($p_A*100,2) ."%
          </td>
          <td align=right>
            ". round($p_B*100,2) ."%
          </td>
        </tr>
        <tr>
          <td colspan=3>
            <br>
          </td>
        </tr>";
  }

  $pfuschOutput .= "  <tr class=table_head>
        <td>
          <b>{$MESSAGES[MSG_SIMULATION][m006]}</b>
        </td>
        <td align=right>
          <b>{$MESSAGES[MSG_SIMULATION][m001]}</b>
        </td>
        <td align=right>
          <b>{$MESSAGES[MSG_SIMULATION][m002]}</b>
        </td>
      </tr>";

  if($_GET['bid']) {
  	$select = sql_query("SELECT `id`, `points` FROM `news_ber` WHERE `attack_bid` = '" . htmlspecialchars($_GET['bid'],ENT_QUOTES) . "' OR `defense_bid` = '" . htmlspecialchars($_GET['bid'],ENT_QUOTES) . "';");
  	$select = sql_fetch_array($select);
  }
  
  for ($i=0;$i<ANZAHL_KAMPF_FLUGZEUGE;$i++)
  {
    if ($i%2)
      $color = "#222222";
    else
      $color = "#000000";
  	
  	if($_GET['bid']) {
  		$sel = sql_query("SELECT `news_ber_`.`before` FROM `news_ber_` INNER JOIN `type_plane` ON `type_plane`.`type`=`news_ber_`.`type` WHERE `news_ber_`.`ID` = '$select[id]' AND `news_ber_`.`ad` = 'defense' AND `type_plane`.`name` = '$p_name[$i]'");
  		$sel = sql_fetch_array($sel);
  		$pfuschOutput .= "  <tr bgcolor=$color>
          <td>
            <a href=\"$dir/description.php?show=$i&t=p\">$p_name[$i]</a>
          </td>
          <td align=right>
            <input type=text class=button style=\"width:50\" name=\"p_off[$i]\" value=\"{$_REQUEST[p_off][$i]}\">
          </td>
          <td align=right>
            <input type=text class=button style=\"width:50\" name=\"p_def[$i]\" value=\"$sel[before]\">
          </td>
        </tr>";
  		
  	}else{
    $pfuschOutput .= "  <tr bgcolor=$color>
          <td>
            <a href=\"$dir/description.php?show=$i&t=p\">$p_name[$i]</a>
          </td>
          <td align=right>
            <input type=text class=button style=\"width:50\" name=\"p_off[$i]\" value=\"{$_REQUEST[p_off][$i]}\">
          </td>
          <td align=right>
            <input type=text class=button style=\"width:50\" name=\"p_def[$i]\" value=\"{$_REQUEST[p_def][$i]}\">
          </td>
        </tr>";
  	}
  }

  $pfuschOutput .= "  <tr>
        <td colspan=3>
          <br>
        </td>
      </tr>
      <tr class=table_head>
        <td>
          <b>{$MESSAGES[MSG_SIMULATION][m007]}</b>
        </td>
        <td>
        </td>
        <td align=right>
          <b>{$MESSAGES[MSG_SIMULATION][m002]}</b>
        </td>
      </tr>";

  for ($i=0;$i<ANZAHL_DEFENSIVE;$i++)
  {
    if ($i%2)
      $color = "#222222";
    else
      $color = "#000000";
    
    
    if($_GET['bid']) {
    	
    	$sel = sql_query("SELECT `news_ber_`.`before` FROM `news_ber_` INNER JOIN `type_plane` ON `type_plane`.`type`=`news_ber_`.`type` WHERE `news_ber_`.`ID` = '$select[id]' AND `news_ber_`.`ad` = 'defense' AND `type_plane`.`name` = '$d_name[$i]'");
    	$sel = sql_fetch_array($sel);
    	$pfuschOutput .= "  <tr bgcolor=$color>
    	<td>
    	<a href=\"$dir/description.php?show=$i&t=d\">$d_name[$i]</a>
    	</td>
    	<td>
    	</td>
    	<td align=right>
    	<input type=text class=button style=\"width:50\" name=\"d_def[$i]\" value=\"$sel[before]\">
    	</td>
    	</tr>";
    
    }else{
    
    	$pfuschOutput .= "  <tr bgcolor=$color>
          <td>
            <a href=description.php?show=$i&t=d>$d_name[$i]</a>
          </td>
          <td>
          </td>
          <td align=right>
            <input type=text class=button style=\"width:50\" name=\"d_def[$i]\" value=\"{$_REQUEST[d_def][$i]}\">
          </td>
        </tr>";
    }
  }

  $pfuschOutput .= "  <tr>
        <td colspan=3>
          <br>
        </td>
      </tr>
      <tr class=table_head>
        <td>
          <b>{$MESSAGES[MSG_SIMULATION][m008]}</b>
        </td>
        <td align=right>
          <b>{$MESSAGES[MSG_SIMULATION][m001]}</b>
        </td>
        <td align=right>
          <b>{$MESSAGES[MSG_SIMULATION][m002]}</b>
        </td>
      </tr>";

  for ($i=E_WEAPONS;$i<=N_WEAPONS;$i++)
  {
    if ($i%2)
      $color = "#000000";
    else
      $color = "#222222";

    $pfuschOutput .= "  <tr bgcolor=$color>
          <td>
            $t_name[$i]
          </td>
          <td align=right>
            <input type=text class=button style=\"width:50\" name=\"off_user_techs[$i]\" value=\"{$_REQUEST[off_user_techs][$i]}\">
          </td>
          <td align=right>
            <input type=text class=button style=\"width:50\" name=\"def_user_techs[$i]\" value=\"{$_REQUEST[def_user_techs][$i]}\">
          </td>
        </tr>";
  }

  $pfuschOutput .= "  <tr>
        <td colspan=3>
          <br>
        </td>
      </tr>
      <tr class=table_head>
        <td>
          <b>{$MESSAGES[MSG_SIMULATION][m009]}</b>
        </td>
        <td>
        </td>
        <td align=right>
          <b>{$MESSAGES[MSG_SIMULATION][m002]}</b>
        </td>
      </tr>
      <tr>
        <td>
          {$MESSAGES[MSG_SIMULATION][m010]}
        </td>
        <td>
        </td>
        <td align=right>";
   if($_GET['bid']) {
   		$pfuschOutput .= "<input type=text class=button style=\"width:50\" name=def_newbie value=\"$select[points]\">";
   }else{
        $pfuschOutput .= "<input type=text class=button style=\"width:50\" name=def_newbie value=\"{$_REQUEST[def_newbie]}\">";
   }
   $pfuschOutput .= "
        </td>
      </tr>
      <tr>
        <td colspan=3>
          <br>
        </td>
      </tr>
      <tr>
        <td colspan=3 align=center>
          <input type=hidden name=\"def_sst\" value=\"0\">
          <input type=hidden name=action value=calculate>
          <input type=submit class=button value=\"{$MESSAGES[MSG_SIMULATION][m012]}\">
          <input type=reset  class=button value=\"{$MESSAGES[MSG_SIMULATION][m013]}\">
          <input type=button class=button value=\"{$MESSAGES[MSG_SIMULATION][m014]}\" onclick=\"window.location.href='airport.php'\">
        </td>
      </tr>
      </form>
      </table>";

  // add pfusch output
  $template->set('pfuschOutput', $pfuschOutput);


  // save resource changes (ToDo: Is this necessary on every page?)
  $timefixed_depot->save();

  // create html page
  try {
    echo $template->execute();
  }
  catch (Exception $e) { echo $e->getMessage(); }
?>
