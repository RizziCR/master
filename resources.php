<?php
  // $use_lib = ?; // MSG_ADMINISTRATION

  require_once("msgs.php");
  require_once("database.php");
  require_once("constants.php");
  require_once("functions.php");
  require_once("do_loop.php");
  include("tutorial.php");

// define phptal template
  require_once("PHPTAL.php");
  require_once("include/PHPTAL_EtsTranslator.php");
  //$template = new PHPTAL('resources.html');
  $template = new PHPTAL('standard.html');
  $template->setTranslator(new PHPTAL_EtsTranslator());
  $template->setEncoding('ISO-8859-1');

  // set page title
  $template->set('pageTitle', 'Stadt - Lager');

  $pfuschOutput = "";


 // insert specific page logic here


  $get_buildings = sql_query("SELECT b_iridium_mine,b_holzium_plantage,b_water_derrick,b_oxygen_reactor,b_depot,b_oxygen_depot FROM city WHERE ID='$_SESSION[city]' && user='$_SESSION[user]'");
  $buildings = sql_fetch_array($get_buildings);

  $get_techs = sql_query("SELECT t_mining,t_water_compression,t_depot_management FROM usarios WHERE ID='$_SESSION[user]'");
  $user_techs = sql_fetch_array($get_techs);

  $foerderung[IRIDIUM]    = round(Foerderung(IRIDIUM,$buildings[IR_MINE],$user_techs[t_mining]));
  $foerderung[HOLZIUM]    = round(Foerderung(HOLZIUM,$buildings[HZ_PLANTAGE],$user_techs[t_mining]));
  $foerderung[WATER]      = round(Foerderung(WATER,$buildings[WA_DERRICK],0));
  $foerderung[OXYGEN]     = round(Foerderung(OXYGEN,$buildings[OX_REACTOR],$user_techs[t_water_compression],1));
  $foerderung['oxygen_r'] = round(Foerderung(OXYGEN,$buildings[OX_REACTOR],$user_techs[t_water_compression],$timefixed_depot->getWater(),$buildings[WA_DERRICK]));
  $foerderung['oxygen_m'] = round(Foerderung(OXYGEN,$buildings[OX_REACTOR],$user_techs[t_water_compression],0,$buildings[WA_DERRICK]));

  $verbrauch[IRIDIUM]   = round(Verbrauch(IRIDIUM,0));
  $verbrauch[HOLZIUM]   = round(Verbrauch(HOLZIUM,0));
  $verbrauch[WATER]     = round(Verbrauch(WATER,$buildings[OX_REACTOR]));
  $verbrauch[OXYGEN]    = round(Verbrauch(OXYGEN,0));

  $depot_size[DEPOT]    = Lager::size($buildings[DEPOT], $user_techs[t_depot_management]);
  $depot_size[OX_DEPOT] = Lager::sizeOxygen($buildings[OX_DEPOT], $user_techs[t_depot_management]);

  $pfuschOutput .= "  <script language='javascript'>
  <!--
      var menge = Array();
      var foerderung = Array();

      menge['iridium']    = Math.round(".$timefixed_depot->getIridium().");
      menge['holzium']    = Math.round(".$timefixed_depot->getHolzium().");
      menge['water']      = Math.round(".$timefixed_depot->getWater().");
      menge['oxygen']     = Math.round(".$timefixed_depot->getOxygen().");

      foerderung['iridium']  = Array({$foerderung[IRIDIUM]}, {$foerderung[IRIDIUM]});
      foerderung['holzium']  = Array({$foerderung[HOLZIUM]}, {$foerderung[HOLZIUM]});
      foerderung['water']    = Array({$foerderung[WATER]}, {$foerderung[WATER]} - {$verbrauch[WATER]});
      foerderung['oxygen']   = Array({$foerderung[OXYGEN]}, {$foerderung[oxygen_r]}, {$foerderung[oxygen_m]});

      var depot_size_depot = {$depot_size[DEPOT]};
      var depot_size_ox = {$depot_size[OX_DEPOT]};
    // -->
    </script>
    <script type='text/javascript' src='".$etsAddress."/javascript/resources.js'></script>
    ";


  $pfuschOutput .= "  <h1>Rohstoff-&Uuml;bersicht</h1>";
  $pfuschOutput .= "
	<table cellpadding='2' cellspacing='0' border='0'>
      <tr>
        <td colspan='4' align='center' class='table_head'>
          &Uuml;bersicht
        </td>
      </tr>
      <tr>
        <td>
          <b>Rohstoff</b>
        </td>
        <td>
          <b>Ausbaustufe</b>
        </td>
        <td>
          <b>F&ouml;rdermenge/h</b>
        </td>
        <td>
          <b>Verbrauch/h</b>
        </td>
      </tr>";

  for ($i=0;$i<ANZAHL_ROHSTOFFE;$i++)
  {
    if ($i%2)
      $color = "#000000";
    else
      $color = "#222222";

    $pfuschOutput .= "  <tr bgcolor='$color'>
          <td>
            <a href='description.php?show=$i&t=b'>". $MESSAGES[MSG_GENERAL]["m00$i"] ."</a>
          </td>
          <td>
            $buildings[$i]
          </td>
          <td>
            " . number_format($foerderung[$i],0,',','.') . " (".
                ( ($foerderung[$i] < $verbrauch[$i]) ? '<font style="color:#FF0000">' : '<font style="color:#00FF00">' ) .
                    ( ($i != OXYGEN) ? (number_format($foerderung[$i] - $verbrauch[$i],0,',','.')) : number_format($foerderung['oxygen_r'],0,',','.') ).
                "</font>
            )
          </td>
          <td>
            ". (($i == WATER) ? number_format($verbrauch[$i],0,',','.') : "") ."
          </td>
        </tr>";
  }

  $pfuschOutput .= "  <tr>
        <td colspan='4'>
          <br><br>
        </td>
      </tr>
      <tr>
        <td colspan='4' align='center' class='table_head'>
          Lagerkapazit&auml;ten
        </td>
      </tr>
      <tr valign='top'>
        <td>
          <b>Rohstoff(e)</b>
        </td>
        <td>
          <b>Ausbaustufe</b>
        </td>
        <td>
          <b>Kapazit&auml;t<br>(enthaltene Rohstoffe / freier Lagerplatz)</b>
        </td>
        <td>
          <b>Auslastung</b>
        </td>
      </tr>
      <tr valign='top' bgcolor='#222222'>
        <td>
          <a href='description.php?show=". DEPOT ."&t=b'>Iridium, Holzium, Wasser</a>
        </td>
        <td>
          {$buildings[DEPOT]}
        </td>
        <td>
          ". number_format($depot_size[DEPOT],0,',','.') . " (". number_format($timefixed_depot->fillLevel(),0,',','.') ." / " . number_format($depot_size[DEPOT]-$timefixed_depot->fillLevel(),0,',','.') . ")
        </td>
        <td>
          ". round($timefixed_depot->fillLevelPercent()) ." %
        </td>
      </tr>
      <tr valign='top' bgcolor='#000000'>
        <td>
          <a href='description.php?show=". OX_DEPOT ."&t=b'>Sauerstoff</a>
        </td>
        <td>
          {$buildings[OX_DEPOT]}
        </td>
        <td>
          ". number_format($depot_size[OX_DEPOT],0,',','.') . " (". number_format($timefixed_depot->fillLevelOxygen(),0,',','.') ." / " . number_format($depot_size[OX_DEPOT]-$timefixed_depot->fillLevelOxygen(),0,',','.') . ")
        </td>
        <td>
          ". round($timefixed_depot->fillLevelOxygenPercent()) ." %
        </td>
      </tr>
      <tr>
        <td colspan='4'>
          <br><br>
        </td>
      </tr>
      <form action={$_SERVER['PHP_SELF']} method='post' name='formular'>
      <tr>
        <td colspan='4' align='center' class='table_head'>
          Berechnung
        </td>
      </tr>
      <tr>
      <td colspan='4'>
      <table width='100%'>
      <tr>
        <td>Wieviel X habe ich in Y Stunden?</td><td>Wie lange ben&ouml;tigt die F&ouml;rderung  von</td>
      </tr>
      <tr>
        <td width='50%'>
           <select name='resource' class='button' onchange='calc_hours()'>
                <option value='iridium'>Iridium</option>
                <option value='holzium'>Holzium</option>
                <option value='water'>Wasser</option>
                <option value='oxygen'>Sauerstoff</option>
                <option value='depot'>% Füllung Lager</option>
                <option value='oxygen_depot'>% Füllung Tank</option>
          </select>
          <input maxlength='7' type='text' size='7' class='button' name='hours' onkeyup='calc_hours()'><br />
          <p id='c_1'><input type='checkbox' name='c_res' value='1' onclick='calc_hours()'> vorhandene Rohstoffe einbeziehen</p>
          <p id='c_2'><input type='checkbox' name='c_water_ignore' value='1' onclick='calc_hours()'> H2O-Bestand/-förderung ignorieren</p>
          <br />
          Menge: <input type='text' class='readonly' readonly='readonly' name='result_hours'><br />
          Fertig: <input class='readonly' readonly='readonly' name='result_time1'>
        </td>
        <td>
          <input type='text' class='button' name='quantity' size='7' onkeyup='calc_quantity()'>
          <select name='q_resource' class='button' onchange='calc_quantity()'>
                <option value='iridium'>Iridium</option>
                <option value='holzium'>Holzium</option>
                <option value='water'>Wasser</option>
                <option value='oxygen'>Sauerstoff</option>
                <option value='depot'>% Füllung Lager</option>
                <option value='oxygen_depot'>% Füllung Tank</option>
          </select><br />
          <p id='q_c_1'><input type='checkbox' name='q_c_res' value='1' onclick='calc_quantity()'> vorhandene Rohstoffe einbeziehen</p>
          <p id='q_c_2'><input type='checkbox' name='q_c_water_ignore' value='1' onclick='calc_quantity()'> H2O-Bestand/-förderung ignorieren</p>
          <br />
          Dauer: <input type='text' class='readonly' readonly='readonly' size='5' name='quantity_hours'> :
                 <input type='text' class='readonly' readonly='readonly' size='2' name='quantity_minutes'> :
                 <input type='text' class='readonly' readonly='readonly' size='2' name='quantity_seconds'> h <br />
          Fertig: <input class='readonly' readonly='readonly' name='result_time2'>
        </td>
      </tr>
      </table>
      </td>
      </tr>
      </form>
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
