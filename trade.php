<?php
  $use_lib = 2; // MSG_TRADE_CENTER

  require_once("msgs.php");
  require_once("database.php");
  require_once("constants.php");
  require_once("functions.php");
  require_once("do_loop.php");

  // define phptal template
  require_once("PHPTAL.php");
  require_once("include/PHPTAL_EtsTranslator.php");
  $template = new PHPTAL( 'theme_blue_line.html' );
  $template->setTranslator(new PHPTAL_EtsTranslator());
  $template->setEncoding('ISO-8859-1');
  $template->set('contentMacroName','trade.html/content');
  // set page title
  $template->set('pageTitle', 'Stadt - Handelszentrum');
  // slim trade center wanted?
  $template->set('slimTrade', isset($_GET['slimTrade']) ? 1 : 0);

  /**
   * Normalize the given real cost of a plane according to the given cost factor (considering the 
   * ratio of the resources in the central warehouse). The cost factor already contains a tax part.
   */
  function compute_base_cost(/*$p_id*/$cost_factor, $iridium_cost, $holzium_cost)
  {
    global $global;
    return $cost_factor * (($iridium_cost*($global[oxygen]+TC_MIN_STOCK)/($global[iridium]+TC_MIN_STOCK)) + $holzium_cost*($global[oxygen]+TC_MIN_STOCK)/($global[holzium]+TC_MIN_STOCK));
  }

  /**
   * Compute the ratio of the two given resource amounts. Add a tax if requested.
   * @param $actualTrade true if tax part is requested in the price
   */
  function compute_resource_price($amount, $exchange, &$x, &$y, $actualTrade)
  {
    $amount += TC_MIN_STOCK;
    // if one resource is to be traded for an other then devaluate it
    // (same amount as for planes, 10%)
    if ($actualTrade)
	$amount += floor($amount * TC_TAX);
    $exchange += TC_MIN_STOCK;
    //echo "XXX: $amount - $exchange<br />";
    if ($amount >= $exchange)
    {
      $x = $amount * 100 / $exchange;
      $y = 100;
    }
    else
    {
      $x = 100;
      $y = $exchange * 100 / $amount;
    }
  }

  /**
   * Compute the ratio of the two given (normalized) plane costs and store it in the two output 
   * parameters. For example, the values 2000 and 1000 result in a ratio of 1 to 2, that is the 
   * first plane is worth only the half of the second.
   */
  function compute_planes_price($valuex, $valuey, &$x, &$y)
  {
    if ($valuex >= $valuey)
    {
      $x = 1;
      $y = $valuex / $valuey;
    }
    else
    {
      $x = $valuey / $valuex;
      $y = 1;
    }
  }

  /**
   * Determine the greatest common divisor of the two given ratio values.
   */
  function gcd($x, $y)
  {
    return gmp_intval(gmp_gcd((int)$x, (int)$y));
  }

  /**
   * Compute the final price for a resource - round
   * (to the benefit of the trading center) and reduce (mathematically) the given ratio.
   * @param $tc_sale true if the trading center sells the resource given as first parameter
   */
  function even_price($x, $y, &$even_x, &$even_y, $tc_sale)
  {
    // round to the benefit of the trading center
    $even_x = $tc_sale ? floor($x) : ceil($x);
    $even_y = $tc_sale ? ceil($y) : floor($y);
    //echo "even_price: $x : $y - $even_x : $even_y <br />";
    $the_gcd = gcd($even_x, $even_y);
    //echo "gcd: $the_gcd <br />";
    if ($the_gcd)
    {
      $even_x = $even_x / $the_gcd;
      $even_y = $even_y / $the_gcd;
    }
  }

  /**
   * Compute the final price for a plane - round
   * (to the benefit of the trading center) the given value (which is the non-1 part of the ratio).
   * @param $tc_sale true if the trading center sells the plane
   */
  function get_plane_price($value, $tc_sale)
  {
    if ($tc_sale)
      return ceil($value / 10) * 10;
    else
      return floor($value / 10) * 10;
  }

  /**
   * Convert the given (normalized) base cost into the requested currency (considering the
   * available amounts of the currency and of the internal oxygen currency) and tax it if requested.
   * For example, if the base cost was 2000, the currency stored in the central warehouse is 5
   * times that of oxygen then the plane costs 10000 units of the requested currency.
   */
  function compute_plane_price($base_cost, $currency_amount, $oxygen_amount)
  {
    $rx = 0;
    $ry = 0;
    compute_resource_price($currency_amount, $oxygen_amount, $rx, $ry, false);
    return $base_cost * $rx / $ry;
  }

  /**
   * Compute the value ratio between the offered ware and the requested one.
   * @param $give the offered ware (internal index for planes and internal name for resources)
   * @param $get the requested ware (internal index for planes and internal name for resources)
   * @param $plane_value_buy array of normalized plane costs for buying planes from trading center
   * @param $plane_value_sell array of normalized plane costs for selling planes to trading center
   * @param $global array of the resources in the central warehouse
   * @param $ratio_x first ratio part - output parameter
   * @param $ratio_y second ratio part - output parameter
   */
  function get_ratio($give, $get, $plane_value_buy, $plane_value_sell, $global, &$ratio_x, &$ratio_y)
  {
    global $p_db_name_wus;

    $ratio_x = 1;
    $ratio_y = 1;
    if (is_numeric($give))
    {
      if (is_numeric($get))
      {
        compute_planes_price($plane_value_sell[$give], $plane_value_buy[$get], $ratio_x, $ratio_y);
        even_price($ratio_x, $ratio_y, $ratio_x, $ratio_y, false);
      }
      else
      {
        $plane = compute_plane_price($plane_value_sell[$give], $global[$get], $global[oxygen]);
        $ratio_y = get_plane_price($plane, false);
      }
    }
    else if (is_numeric($get))
    {
      $plane = compute_plane_price($plane_value_buy[$get], $global[$give], $global[oxygen]);
      $ratio_x = get_plane_price($plane, true);
    }
    else
    {
      compute_resource_price($global[$give], $global[$get], $x, $y, true);
      even_price($x, $y, $ratio_x, $ratio_y, false);
    }
  }

  function compute_counterweight($amout, $ratio_x, $ratio_y, $giving)
  {
    $rawVal = $amout * $ratio_y / $ratio_x;
    return $giving ? floor($rawVal) : ceil($rawVal);
  }

  function compute_consumption($distance, $number, $id)
  {
    global $p_consumption, $t_increase, $user_techs;
    return round($distance * $p_consumption[$id] * pow($t_increase[CONSUMPTION], $user_techs[CONSUMPTION]) * $number / 1000 / 2);
  }

  function compute_capacity($number, $id)
  {
    global $p_capacity, $t_increase, $user_techs;
    return floor($number * $p_capacity[$id] * pow($t_increase[PLANE_SIZE], $user_techs[PLANE_SIZE]));
  }

  function compute_flying_time($distance, $id)
  {
    global $p_speed, $t_increase, $user_techs, $p_tech;
    return round($distance / ($p_speed[$id] + $t_increase[$p_tech[$id][T_SPEED]] *
      $user_techs[$p_tech[$id][T_SPEED]]) * 3600);
  }

  function readOnly()
  {
    global $buildings;
    return $buildings[b_trade_center] <= 0;
  }

 // insert specific page logic here

  $flugzeuge_anzahl = 0;

  if ($_POST[action] && $_POST[bc] != "3fhig35z")
    sql_query("INSERT INTO _bot_user (user,time) VALUES ('$_SESSION[user]',UNIX_TIMESTAMP())");

  $get_buildings = sql_query("SELECT city,b_trade_center,b_hangar FROM city WHERE ID='$_SESSION[city]' && user='$_SESSION[user]'");
  $buildings = sql_fetch_array($get_buildings);

  try
  {

    $get_global = sql_query("SELECT * FROM global");
    $global = sql_fetch_array($get_global);

    if (!readOnly())
    {
      $get_resources = sql_query("SELECT SUM(f_volume) FROM actions WHERE city='$_SESSION[city]' && (f_action='sell_to_depot' or f_action='plane_sell' or f_action='plane_buy')");
      $resources = sql_fetch_array($get_resources);
      $resources_in_fleets = $resources[0];
    }

    $get_planes = sql_query("SELECT p_". implode(",p_",$p_db_name_wus) .",p_gesamt_flugzeuge FROM city WHERE ID='$_SESSION[city]'");
    $city_planes = sql_fetch_array($get_planes);

    // requesting 9 values - important for access;
    // beware - the resulting array seems to be of size 18
    $get_techs = sql_query("SELECT t_{$t_db_name[O_DRIVE]},t_{$t_db_name[H_DRIVE]},t_{$t_db_name[A_DRIVE]},t_{$t_db_name[E_WEAPONS]},t_{$t_db_name[P_WEAPONS]},t_{$t_db_name[N_WEAPONS]},t_{$t_db_name[CONSUMPTION]},t_plane_size,t_{$t_db_name[COMP_MANAGEMENT]} FROM usarios WHERE ID='$_SESSION[user]'");
    $user_techs = sql_fetch_array($get_techs);

    $query_string = "";
    for ($i = 0; $i < ANZAHL_FLUGZEUGE; $i++)
    {
      if ($i > 0)
        $query_string .= ', ';
      $query_string .= "sum(p$p_db_name[$i]_gesamt)";
    }
    $get_plane_trade_data = sql_query("SELECT * from plane_trade order by plane_type ASC");
    $plane_trade_data = array();
    $i = 0;
    while ($row = sql_fetch_array($get_plane_trade_data))
    {
      //echo $row['plane_type']." ". $row['cost_factor']." <br />";
      $plane_trade_data[$i] = $row;
      //echo $plane_trade_data[$i][1]." ". $plane_trade_data[$i]['cost_factor']." <br />";
      $i++;
    }

    $plane_value_buy = array();
    $plane_value_sell = array();
    for ($i = 0; $i < ANZAHL_FLUGZEUGE; $i++)
    {
      $plane_value_buy[$i] = compute_base_cost($plane_trade_data[$i][cost_factor], $p_iridium[$i], $p_holzium[$i]);
      $plane_value_sell[$i] = compute_base_cost($plane_trade_data[$i][gain_factor], $p_iridium[$i], $p_holzium[$i]) * (1 - TC_TAX);
    }

    if ($_POST[action])
    {
      // this could only happen through BOTs or a change from a trading city to a non-trading one
      if (readOnly())
        ErrorMessageException(MSG_TRADE_CENTER, e000);  //Du hast in dieser Stadt kein Handelszentrum gebaut

      $sending = intval($_POST[sends]);
      $receiving = intval($_POST[gets]);
      $ware_up = addslashes($_POST[up]);
      $ware_down = addslashes($_POST[down]);
      $value_source = addslashes($_POST[value_source]);

      if ($ware_up == $ware_down)
        ErrorMessageException(MSG_TRADE_CENTER, e003);  // Bitte 2 verschiedene Waren wählen

      // this is a correction line for non-JS user
      if ($value_source == "give" && (!$sending || $sending < 1) && $receiving > 0)
        $value_source = "get";
      // the sending amount (respectively receiving) is zero or empty
      if ($value_source == "get" && (!$receiving || $receiving < 0) ||
          $value_source == "give" && (!$sending || $sending < 0))
        ErrorMessageException(MSG_TRADE_CENTER, e002);

      if (is_numeric($ware_up))
        $plane_traded_away = true;
      if (is_numeric($ware_down))
        $plane_traded_in = true;

      $valid_ressis = array("iridium","holzium","water","oxygen");
      if (!$plane_traded_away && $ware_up && !in_array($ware_up, $valid_ressis)) die();
      if (!$plane_traded_in && $ware_down && !in_array($ware_down, $valid_ressis)) die();

      //compute ratio
      $ratio_x = 1;
      $ratio_y = 1;
      get_ratio($ware_up, $ware_down, $plane_value_buy, $plane_value_sell, $global, $ratio_x, $ratio_y);
      if ($value_source == "get")
      {
        $receiving = round($receiving);
        $sending = compute_counterweight($receiving, $ratio_y, $ratio_x, false);
      }
      else
      {
        $sending = round($sending);
        $receiving = compute_counterweight($sending, $ratio_x, $ratio_y, true);
      }
      // the amount of the traded-in/away ware would be less than 1
      if ($sending < 1 || $receiving < 1)
        ErrorMessageException(MSG_TRADE_CENTER, e014);

      $trading_limit =
        TradeCenterCapacity($buildings["b_{$b_db_name[TRADE_CENTER]}"]) - $resources_in_fleets;
      if ($sending > $trading_limit || $receiving > $trading_limit)
        ErrorMessageException(MSG_TRADE_CENTER, e005);  // Du kannst nicht mit so vielen Waren handeln

      $consumption = 0;
      $flying_time = 0;
      $flying_time_back = 0;
      $plane_number = 0;
      if ($plane_traded_away)
      {
        $consumption += compute_consumption(TC_DISTANCE, $sending, $ware_up);
        $flying_time = compute_flying_time(TC_DISTANCE, $ware_up);
      }
      if ($plane_traded_in)
      {
        $consumption += compute_consumption(TC_DISTANCE, $receiving, $ware_down);
        $flying_time_back = compute_flying_time(TC_DISTANCE, $ware_down);
      }
      if (!$plane_traded_away && !$plane_traded_in)
        for ($i=SMALL_TRANSPORTER;$i<=BIG_TRANSPORTER;$i++)
          if ($_POST[p_fleet][$i])
          {
            if (!is_numeric($_POST[p_fleet][$i]) || $_POST[p_fleet][$i] < 1)
              ErrorMessageException(MSG_TRADE_CENTER,invalidTransports);
            $consumption += compute_consumption(TC_DISTANCE, $_POST[p_fleet][$i], $i) * 2;
            $flying_time = max($flying_time, compute_flying_time(TC_DISTANCE, $i,
              $i));
            $plane_number += $_POST[p_fleet][$i];
          }
      if ($consumption > $timefixed_depot->getOxygen())
        ErrorMessageException(MSG_TRADE_CENTER,e008);  // Du hast nicht genügend Treibstoff

      $res_in_city[iridium] = $timefixed_depot->getIridium();
      $res_in_city[holzium] = $timefixed_depot->getHolzium();
      $res_in_city[water] = $timefixed_depot->getWater();
      $res_in_city[oxygen] = $timefixed_depot->getOxygen();

      if ($plane_traded_away)
      {
        if ($city_planes[$ware_up] < $sending)
          ErrorMessageException(MSG_TRADE_CENTER, e001);  // Du hast nicht genügend Flugzeuge
      }
      // take the fuel into account when giving oxygen
      else if ($res_in_city[$ware_up] < $sending + ($ware_up == "oxygen" ? $consumption : 0))
        ErrorMessageException(MSG_TRADE_CENTER, e007);  // Du hast nicht genügend Rohstoffe
      if (!$plane_traded_away && !$plane_traded_in)
      {
        for ($i=SMALL_TRANSPORTER;$i<=BIG_TRANSPORTER;$i++)
          if ($_POST[p_fleet][$i] > $city_planes[$i])
            ErrorMessageException(MSG_TRADE_CENTER, e001);  // Du hast nicht genügend Flugzeuge
        $capacity = 0;
        for ($i=SMALL_TRANSPORTER;$i<=BIG_TRANSPORTER;$i++)
          $capacity += compute_capacity($_POST[p_fleet][$i], $i);
        if (max($receiving, $sending) > $capacity)
          ErrorMessageException(MSG_TRADE_CENTER, e004);  // In Deinen Flugzeugen ist nicht genug Platz
      }
      if ($plane_traded_in)
      {
        if ($plane_trade_data[$ware_down]['stock'] < $receiving)
          ErrorMessageException(MSG_TRADE_CENTER, e010);  // Im Hauptlager sind nicht genügend Flugzeuge
        //XXX (if plane_traded_away that is slower and not enough space then slow down, if
        //away+space-in>0 ) - fill up immediately or let planes return to hz if
        //full?
        if ($city_planes[p_gesamt_flugzeuge] + $receiving > $buildings["b_{$b_db_name[HANGAR]}"]*PLANES_PER_LEVEL)
          ErrorMessageException(MSG_TRADE_CENTER, e011);  // In Deinem Hangar ist nicht genügend Platz
      }
      else
      {
        if ($global[$ware_down] < $receiving)
          ErrorMessageException(MSG_TRADE_CENTER, e006);  // Im Hauptlager sind nicht genügend Rohstoffe
        if ($ware_down == "oxygen")
        {
          if ($receiving + $timefixed_depot->fillLevelOxygen() > $timefixed_depot->getCapacityOxygen())
            ErrorMessageException(MSG_TRADE_CENTER, e013);  // Dein Tank ist nicht gross genug
        }
        else if ($receiving + $timefixed_depot->fillLevel() > $timefixed_depot->getCapacity())
          ErrorMessageException(MSG_TRADE_CENTER, e009);  // Dein Lager ist nicht gross genug
      }

      $get_now = split(" ",microtime());
      $now = $get_now[1] + $get_now[0];
      $timefixed_depot->removeOxygen($consumption);

      if (!$plane_traded_away && !$plane_traded_in)
      {
        sql_query("UPDATE global SET $ware_down=$ware_down-$receiving");

        $small = intval($_POST[p_fleet][SMALL_TRANSPORTER] ? $_POST[p_fleet][SMALL_TRANSPORTER] : 0);
        $medium = intval($_POST[p_fleet][MEDIUM_TRANSPORTER] ? $_POST[p_fleet][MEDIUM_TRANSPORTER] : 0);
        $big = intval($_POST[p_fleet][BIG_TRANSPORTER] ? $_POST[p_fleet][BIG_TRANSPORTER] : 0);
        sql_query("INSERT INTO actions (city,user,session_id,f_id,f_action,f_start,f_arrival,f_volume,f_$ware_up,f_small_transporter,f_medium_transporter,f_big_transporter,f_flugzeuge_anzahl,msg,msg_text) SELECT '$_SESSION[city]','$_SESSION[user]','". session_id() ."',Max(id)+1,'sell_to_depot',$now,$now + $flying_time,".max($sending,$receiving).",$sending,$small,$medium,$big,$plane_number,'Eine Rohstoffhandel-Flotte von $buildings[city] erreichte das Hauptlager','headline|Eine Rohstoffhandel-Flotte ($buildings[city]) erreichte das Hauptlager<br><br>Sie &uuml;berbrachte ". round($sending) ." ". translate("$ware_up") ."|-' FROM actions");

        $get_f_id = sql_query("SELECT Max(f_id) AS f_id FROM actions WHERE session_id='". session_id() ."'");
        $f_id = sql_fetch_array($get_f_id);
        sql_query("INSERT INTO actions (city,user,f_id,f_action,f_start,f_arrival,f_volume,f_$ware_down,f_small_transporter,f_medium_transporter,f_big_transporter,f_flugzeuge_anzahl,msg,msg_text) VALUES ('$_SESSION[city]','$_SESSION[user]','$f_id[0]','sell_from_depot',$now + $flying_time,$now + 2*$flying_time,".max($sending,$receiving).",$receiving,$small,$medium,$big,$plane_number,'Eine Rohstoffhandel-Flotte vom Hauptlager erreichte $buildings[city]','headline|Eine Rohstoffhandel-Flotte vom Hauptlager erreichte $buildings[city] (Rückflug).<br><br>Sie brachte ". $receiving ." ". translate("$ware_down") ." mit|-')");

        sql_query("UPDATE city SET p_small_transporter=p_small_transporter-$small,p_medium_transporter=p_medium_transporter-$medium,p_big_transporter=p_big_transporter-$big WHERE ID='$_SESSION[city]'");

        switch ($ware_up)
        {
          case "iridium" :  $timefixed_depot->removeIridium($sending); break;
          case "holzium" :  $timefixed_depot->removeHolzium($sending); break;
          case "water" :    $timefixed_depot->removeWater($sending); break;
          case "oxygen" :   $timefixed_depot->removeOxygen($sending); break;
        }
      }
      if ($plane_traded_away)
      {
        sql_query("INSERT INTO plane_transactions SET plane='$ware_up', user='$_SESSION[user]', type='sell', number=$sending, time=UNIX_TIMESTAMP()");
        if (!$plane_traded_in)
        {
          switch ($ware_down)
          {
            case "iridium" :  $timefixed_depot->addIridium($receiving); break;
            case "holzium" :  $timefixed_depot->addHolzium($receiving); break;
            case "water" :    $timefixed_depot->addWater($receiving); break;
            case "oxygen" :   $timefixed_depot->addOxygen($receiving); break;
          }
          sql_query("UPDATE global SET $ware_down=$ware_down-$receiving");
        }

        sql_query("INSERT INTO actions (city,user,f_id,f_action,f_start,f_arrival,f_volume,f$p_db_name[$ware_up], f_flugzeuge_anzahl,msg,msg_text) SELECT '$_SESSION[city]','$_SESSION[user]',Max(id)+1,'plane_sell',$now,$now + $flying_time,$receiving,$sending, $sending,'Eine Flugzeughandel-Flotte von $buildings[city] erreichte das Hauptlager','headline|Eine Flugzeughandel-Flotte von $buildings[city] erreichte das Hauptlager|-\nbreak|-|-\ninfo|$p_name[$ware_up]|$sending\n' FROM actions");
        sql_query("UPDATE city SET p$p_db_name[$ware_up]=p$p_db_name[$ware_up]-$sending WHERE ID='$_SESSION[city]'");

        $get_city_planes = sql_query("SELECT p_". implode(",p_",$p_db_name_wus) .",p_gesamt_flugzeuge FROM city WHERE ID='$_SESSION[city]' && user='$_SESSION[user]'");
        $city_planes = sql_fetch_array($get_city_planes);
        // stock is increased at arrival
        sql_query("UPDATE plane_trade SET sales=sales+$sending where plane_type='$ware_up'");

      }
      if ($plane_traded_in)
      {
        $update_tc = sql_query("UPDATE plane_trade SET stock=stock-$receiving, acquisitions=acquisitions+$receiving WHERE plane_type='$ware_down' && stock >= $receiving");
        if (!$update_tc)
          ErrorMessageException(MSG_TRADE_CENTER,e012);  // Es ist ein Fehler aufgetreten

      if(mysql_affected_rows())
      {
        sql_query("INSERT INTO plane_transactions SET plane='$ware_down', user='$_SESSION[user]', type='buy', number=$receiving, time=UNIX_TIMESTAMP()");
        if (!$plane_traded_away)
        {
          switch ($ware_up)
          {
            case "iridium" :  $timefixed_depot->removeIridium($sending); break;
            case "holzium" :  $timefixed_depot->removeHolzium($sending); break;
            case "water" :    $timefixed_depot->removeWater($sending); break;
            case "oxygen" :   $timefixed_depot->removeOxygen($sending); break;
          }
          sql_query("UPDATE global SET $ware_up=$ware_up+$sending");
        }
        sql_query("INSERT INTO actions (city,user,f_id,f_action,f_start,f_arrival,f_volume,f$p_db_name[$ware_down], f_flugzeuge_anzahl,msg,msg_text) SELECT '$_SESSION[city]','$_SESSION[user]',Max(id)+1,'plane_buy',$now,$now + $flying_time_back,$sending,$receiving, $receiving,'Eine Flugzeughandel-Flotte vom Hauptlager erreichte $buildings[city]','headline|Eine Flugzeughandel-Flotte vom Hauptlager erreichte $buildings[city]|-\nbreak|-|-\ninfo|$p_name[$ware_down]|$receiving\n' FROM actions");
        sql_query("UPDATE city SET p$p_db_name[$ware_down]_gesamt=p$p_db_name[$ware_down]_gesamt+$receiving, p_gesamt_flugzeuge=p_gesamt_flugzeuge+$receiving WHERE ID='$_SESSION[city]'");
        sql_query("UPDATE city SET blubb=blubb+$receiving WHERE ID='$_SESSION[city]'");
      }
      else
     {
        ErrorMessageException(MSG_TRADE_CENTER, e010);  // Im Hauptlager sind nicht genügend Flugzeuge
     }

      }

      $get_resources = sql_query("SELECT SUM(f_volume) FROM actions WHERE city='$_SESSION[city]' && (f_action='sell_to_depot' or f_action='plane_sell' or f_action='plane_buy')");
      $resources = sql_fetch_array($get_resources);
      $resources_in_fleets = $resources[0];

      if (!$plane_traded_away || !$plane_traded_in)
      {
        $get_global = sql_query("SELECT * FROM global");
        $global = sql_fetch_array($get_global);
      }
      if ($plane_traded_away || $plane_traded_in)
      {
        $get_plane_trade_data = sql_query("SELECT * from plane_trade order by plane_type ASC");
        $plane_trade_data = array();
        $i = 0;
        while ($row = sql_fetch_array($get_plane_trade_data))
        {
          //echo $row['plane_type']." ". $row['cost_factor']." <br />";
          $plane_trade_data[$i] = $row;
          //echo $plane_trade_data[$i][1]." ". $plane_trade_data[$i]['cost_factor']." <br />";
          $i++;
        }

        $plane_value_buy = array();
        $plane_value_sell = array();
        for ($i = 0; $i < ANZAHL_FLUGZEUGE; $i++)
        {
	    $plane_value_buy[$i] = compute_base_cost($plane_trade_data[$i][cost_factor], $p_iridium[$i], $p_holzium[$i]);
	    $plane_value_sell[$i] = compute_base_cost($plane_trade_data[$i][gain_factor], $p_iridium[$i], $p_holzium[$i]) * (1 - TC_TAX);
        }

      }

      $get_city_planes = sql_query("SELECT p_". implode(",p_",$p_db_name_wus) .",p_gesamt_flugzeuge FROM city WHERE ID='$_SESSION[city]' && user='$_SESSION[user]'");
      $city_planes = sql_fetch_array($get_city_planes);
    }
  }
  catch(Exception $e)
  {
    $errorMessage =
      "  <h1>{$MESSAGES[MSG_TRADE_CENTER][m000]}</h1>" .
      "<ul>\n    <li>" . $e->getMessage() . "</li>\n</ul>";

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

  $template->set('iridium', number_format($global[iridium],0,",","."));
  $template->set('holzium', number_format($global[holzium],0,",","."));
  $template->set('water', number_format($global[water],0,",","."));
  $template->set('oxygen', number_format($global[oxygen],0,",","."));
  if (readOnly())
  {
    $template->set('tc_readonly', 'true');
    $template->set('remainder', 0);
  }
  else
    $template->set('remainder',
      TradeCenterCapacity($buildings["b_{$b_db_name[TRADE_CENTER]}"])-$resources_in_fleets);

  $in_store = array();
  for ($i = 0; $i < ANZAHL_FLUGZEUGE; $i++)
  {
    if ($plane_trade_data[$i]['stock'])
      $in_store[$i] = array('id'=>$i, 'name'=>$p_name[$i], 'number'=>$plane_trade_data[$i]['stock']);
  }
  $template->set('planes_in_store', $in_store);


  $plane_values = array();
  for ($i = 0; $i < ANZAHL_FLUGZEUGE; $i++)
    $plane_values[$i] = array('speed'=>$p_speed[$i]/*'duration'=>compute_flying_time(TC_DISTANCE,$i)*/, 'tech'=>$p_tech[$i][T_SPEED],
      'consumption'=>$p_consumption[$i], 'capacity'=>$p_capacity[$i]/*,
      'costs'=>$plane_value[$i]*/);

  $xratio = 0;
  $yratio = 0;
  $evened_x = 0;
  $evened_y = 0;
  $evened_xs = 0;
  $evened_ys = 0;
  $prices = array();
  for ($i = 0; $i < ANZAHL_ROHSTOFFE; $i++)
  {
    for ($j = 0; $j < ANZAHL_ROHSTOFFE; $j++)
    {
      // index 0 of global contains id
      compute_resource_price($global[$i+1], $global[$j+1], $x, $y, true);
      even_price($x, $y, $evened_x, $evened_y, false);
      //echo "ratio $i ($global[$i]) : $j ($global[$j]) - $x : $y - evened: $evened_x : $evened_y<br />";
      $row = array();
      $row['warex'] = $MESSAGES[MSG_GENERAL]["m00".($i)];
      $row['warey'] = $MESSAGES[MSG_GENERAL]["m00".($j)];
      $row['x'] = $evened_x;
      $row['y'] = $evened_y;
      $row['idx'] = $i;
      $row['idy'] = $j;
      //echo (($i-1)*(ANZAHL_ROHSTOFFE+ANZAHL_FLUGZEUGE)+($j-1))."<br />";
      $prices[$i*(ANZAHL_ROHSTOFFE+ANZAHL_FLUGZEUGE)+$j] = $row;
    }
    $off = ANZAHL_ROHSTOFFE;
    for ($j = 0; $j < ANZAHL_FLUGZEUGE; $j++)
    {
      $plane = compute_plane_price($plane_value_buy[$j], $global[$i+1], $global[OXYGEN+1]);
      $plane_e = get_plane_price($plane, true);
      //echo "ratio $i ($global[$i]) : $j (".$plane_trade_data[$j][build_costs]." - $plane_value[$j]) - $plane : 1 - evened: ".number_format($plane_e,0,",",".")." : 1 <br />";
      $row = array();
      $row['warex'] = $MESSAGES[MSG_GENERAL]["m00".($i)];
      $row['warey'] = $p_name[$j];
      $row['x'] = $plane_e;
      $row['y'] = 1;
      $row['idx'] = $i;
      $row['idy'] = $j + $off;
      //echo (($i-1)*(ANZAHL_ROHSTOFFE+ANZAHL_FLUGZEUGE)+$j+$off)."<br />";
      $prices[$i*(ANZAHL_ROHSTOFFE+ANZAHL_FLUGZEUGE)+$j+$off] = $row;
    }
  }
  $offa = ANZAHL_ROHSTOFFE * (ANZAHL_ROHSTOFFE + ANZAHL_FLUGZEUGE);
  for ($i = 0; $i < ANZAHL_FLUGZEUGE; $i++)
  {
    for ($j = 0; $j < ANZAHL_ROHSTOFFE; $j++)
    {
      $plane = compute_plane_price($plane_value_sell[$i], $global[$j+1], $global[OXYGEN+1]);
      $plane_e = get_plane_price($plane, false);
      //echo "ratio $i (".$plane_trade_data[$i][build_costs].") : $j ($global[$j]) - 1 : $plane - evened: 1 : ".number_format($plane_e,0,",",".")."<br />";
      $row = array();
      $row['warex'] = $p_name[$i];
      $row['warey'] = $MESSAGES[MSG_GENERAL]["m00".($j)];
      $row['x'] = 1;
      $row['y'] = $plane_e;
      $row['idx'] = $i + ANZAHL_ROHSTOFFE;
      $row['idy'] = $j;
      //echo ($offa + ($i)*(ANZAHL_ROHSTOFFE+ANZAHL_FLUGZEUGE)+($j-1))."<br />";
      $prices[$offa + ($i)*(ANZAHL_ROHSTOFFE+ANZAHL_FLUGZEUGE)+$j] = $row;
    }
    $off = ANZAHL_ROHSTOFFE;
    for ($j = 0; $j < ANZAHL_FLUGZEUGE; $j++)
    {
      compute_planes_price($plane_value_sell[$i], $plane_value_buy[$j], $x, $y);
      even_price($x, $y, $evened_x, $evened_y, false);
      //echo "ratio $i (".$plane_trade_data[$i][build_costs].") : $j (".$plane_trade_data[$j][build_costs].") - $x : $y - evened: ".number_format($evened_x,0,",",".")." : ".number_format($evened_y,0,",",".")."<br />";
      $row = array();
      $row['warex'] = $p_name[$i];
      $row['warey'] = $p_name[$j];
      $row['x'] = $evened_x;
      $row['y'] = $evened_y;
      $row['idx'] = $i + ANZAHL_ROHSTOFFE;
      $row['idy'] = $j + $off;
      //echo ($offa + ($i)*(ANZAHL_ROHSTOFFE+ANZAHL_FLUGZEUGE)+($j+$off))."<br />";
      $prices[$offa + ($i)*(ANZAHL_ROHSTOFFE+ANZAHL_FLUGZEUGE)+($j+$off)] = $row;
    }
  }

  // the $user_techs array seems to be doubled if given to TAL (caused by string mapping?), so we
  // copy it before passing on; caused by sizeof($user_techs) yielding 18 instead of 9
  $template_user_techs = array();
  for ($i = 0; $i < 9; $i++)
    $template_user_techs[$i] = (int) $user_techs[$i];

  $type_temp = array_chunk ( $prices, 18, true );

  $template->set('plane_prices', $prices);
  $template->set('all_types', $type_temp[0]);
  $template->set('plane_prices_x', json_encode(array_map(create_function('$e','return $e[x];'),$prices)));
  $template->set('plane_prices_y', json_encode(array_map(create_function('$e','return $e[y];'),$prices)));
  $template->set('plane_values', $plane_values);
  $template->set('plane_values_speed', json_encode(array_map(create_function('$e','return $e[speed];'),$plane_values)));
  $template->set('plane_values_tech', json_encode(array_map(create_function('$e','return $e[tech];'),$plane_values)));
  $template->set('plane_values_cons', json_encode(array_map(create_function('$e','return $e[consumption];'),$plane_values)));
  $template->set('plane_values_capa', json_encode(array_map(create_function('$e','return $e[capacity];'),$plane_values)));
  $template->set('tech_increments', $t_increase);
  $template->set('user_techs', json_encode($template_user_techs));
  $template->set('small_trans_index', SMALL_TRANSPORTER);
  $template->set('big_trans_index', BIG_TRANSPORTER);
  $template->set('plane_size', PLANE_SIZE);
  $template->set('consumption', CONSUMPTION);
  $template->set('plane_number', ANZAHL_FLUGZEUGE);
  $template->set('array_size', ANZAHL_ROHSTOFFE+ANZAHL_FLUGZEUGE);
  $template->set('tc_distance', TC_DISTANCE);

  $iridium_line = array();
  for ($i = 0; $i < ANZAHL_FLUGZEUGE; $i++)
    $iridium_line[$i] = $p_iridium[$i];

  $holzium_line = array();
  for ($i = 0; $i < ANZAHL_FLUGZEUGE; $i++)
    $holzium_line[$i] = $p_holzium[$i];

  $template->set('self', $_SERVER['PHP_SELF']);

  $in_hangar = array();
  for ($i = 0; $i < ANZAHL_FLUGZEUGE; $i++)
  {
    if ($city_planes[$i])
      $in_hangar[$i] = array('id'=>$i, 'name'=>$p_name[$i], 'number'=>$city_planes[$i]);
  }
  $template->set('planes_in_hangar', $in_hangar);

  $trans_in_hangar = array();
  for ($i=SMALL_TRANSPORTER;$i<=BIG_TRANSPORTER;$i++)
  {
    $trans_in_hangar[$i] = array('id'=>$i, 'name'=>$p_name[$i], 'number'=>$city_planes[$i]);
    $allTransporters += $city_planes[$i];
  }
  $template->set('transporters', $trans_in_hangar);
  if (!$allTransporters)
    $template->set('no_transports', 'true');


  // Uebergebe freie Hangarplaetze
  $calc_hangar_free = sql_query("SELECT IF( b_hangar*".PLANES_PER_LEVEL." > p_gesamt_flugzeuge,
    b_hangar*".PLANES_PER_LEVEL." - p_gesamt_flugzeuge, 0 ) AS hangar_free FROM city WHERE ID='$_SESSION[city]'"); 
  $calc = sql_fetch_array($calc_hangar_free);
  $plane_hangar_free = $calc[hangar_free];
  $template->set('plane_hangar_free', $plane_hangar_free);
  
  
 // end specific page logic

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
