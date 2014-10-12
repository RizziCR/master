<?php
  // $use_lib = ?; // MSG_ADMINISTRATION

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
  $template = new PHPTAL('theme_blue_line.html');
  $template->setTranslator(new PHPTAL_EtsTranslator());
  $template->setEncoding('ISO-8859-1');
  $template->set('contentMacroName','accstat.html/content');

  // set page title
  $template->set('pageTitle', 'Übersichten - Städte');

 // insert specific page logic here
  $get_cities = sql_query("SELECT ID,city,foundation,ROUND(r_iridium) AS r_iridium,ROUND(r_holzium) AS r_holzium,ROUND(r_water) AS r_water,ROUND(r_oxygen) AS r_oxygen,points,home FROM city WHERE user='$_SESSION[user]' ORDER BY pos ASC");
  $get_buildings = sql_query("SELECT b_". implode(",b_",$b_db_name) .",c_active_shields FROM city WHERE user='$_SESSION[user]' ORDER BY pos ASC");
  $get_planes = sql_query("SELECT p_". implode(",p_",$p_db_name_wus) ." FROM city WHERE user='$_SESSION[user]' ORDER BY pos ASC");
  $get_defense = sql_query("SELECT d_". implode(",d_",$d_db_name) ." FROM city WHERE user='$_SESSION[user]' ORDER BY pos ASC");
  $anzahl = sql_num_rows($get_cities);

  $get_techs = sql_query("SELECT t_". implode(",t_",$t_db_name) ." FROM usarios WHERE ID='$_SESSION[user]'");
    $techs = sql_fetch_assoc($get_techs);

  // Gebäudestatistiken zusammenstellen
  $t_cities = array();
  for($i=0; $i<$anzahl; $i++)
  {
    $cities = sql_fetch_array($get_cities);
    $buildings = sql_fetch_array($get_buildings);
    $planes = sql_fetch_array($get_planes);
    $defense = sql_fetch_array($get_defense);

    $t_depot = new Lager($cities['ID']);
    $t_depot->recalcCapacity();

    $t_cities[$i]['coords'] = $cities['city'];
    $t_cities[$i]['founded'] = date( 'd.m.Y', $cities['foundation'] );
    $t_cities[$i]['points'] = number_format($cities['points'],0,'','.');
    $t_cities[$i]['isCapital'] = ($cities['home'] == 'YES') ? 1 : 0;
    $t_cities[$i]['iridium'] = number_format($t_depot->getIridium(),0,',','.');
    $t_cities[$i]['holzium'] = number_format($t_depot->getHolzium(),0,',','.');
    $t_cities[$i]['water'] = number_format($t_depot->getWater(),0,',','.');
    $t_cities[$i]['oxygen'] = number_format($t_depot->getOxygen(),0,',','.');
    $t_cities[$i]['depot'] = number_format($t_depot->getCapacity(),0,',','.');
    $t_cities[$i]['depot_perc'] = round($t_depot->fillLevelPercent(), 2);
    $t_cities[$i]['depot_oxy'] = number_format($t_depot->getCapacityOxygen(),0,',','.');
    $t_cities[$i]['depot_oxy_perc'] = round($t_depot->fillLevelOxygenPercent(),2);

    // Totalwerte aktualisieren
    $t_totals['points'] += $cities['points'];
    $t_totals['iridium'] += $t_depot->getIridium();
    $t_totals['holzium'] += $t_depot->getHolzium();
    $t_totals['water'] += $t_depot->getWater();
    $t_totals['oxygen'] += $t_depot->getOxygen();
    $t_totals['depot'] += $t_depot->getCapacity();
    $t_totals['depot_oxy'] += $t_depot->getCapacityOxygen();

    $kw_plane = 0; $kw_defense = 0;
    for($j=0; $j<ANZAHL_GEBAEUDE; $j++)
    {
      $t_cities[$i]['buildings'][$j] = $buildings[$j];
      $t_totals['buildings'][$j] += $t_cities[$i]['buildings'][$j];
    }
    for($j=0; $j<ANZAHL_FLUGZEUGE; $j++)
    {
      $t_cities[$i]['planes'][$j] = $planes[$j];
      $t_totals['planes'][$j] += $t_cities[$i]['planes'][$j];
      $kw_plane += $planes[$j] * Party::getPlaneKW($p_tech[$j][T_POWER], $p_power[$j], $t_increase[$p_tech[$j][T_POWER]], $techs["t_{$t_db_name[$p_tech[$j][T_POWER]]}"]);
    }
    
    $get_techs2 = sql_query("SELECT t_". implode(",t_",$t_db_name) ." FROM usarios WHERE ID='$_SESSION[user]'");
    $user_techs2 = sql_fetch_array($get_techs2);
  
    for($j=0; $j<ANZAHL_DEFENSIVE; $j++)
    {
      $t_cities[$i]['defense'][$j] = $defense[$j];
      $t_totals['defense'][$j] += $t_cities[$i]['defense'][$j];
      $kw_defense += $defense[$j] * ($d_power[$j] + $t_increase[$d_tech[$j][T_POWER]] * $user_techs2[$d_tech[$j][T_POWER]]);

    }

    // Kampfwerte berechnen
//    $kw_shield = Shield($buildings['b_shield'], $techs['t_shield_tech'], $buildings['c_active_shields']);
    $kw_basic = NewbieDef($cities['points']);
//    $kw_total = $kw_defense + $kw_plane + $kw_shield + $kw_basic;
    $kw_total = $kw_defense + $kw_plane + $kw_basic;
    $t_cities[$i]['kw_plane'] = number_format($kw_plane,0,',','.');
    $t_cities[$i]['kw_defense'] = number_format($kw_defense,0,',','.');
//    $t_cities[$i]['kw_shield'] = number_format($kw_shield,0,',','.');
    $t_cities[$i]['kw_basic'] = number_format($kw_basic,0,',','.');
    $t_cities[$i]['kw_total'] = number_format($kw_total,0,',','.');
    $t_totals['kw_plane'] += $kw_plane;
    $t_totals['kw_defense'] += $kw_defense;
//    $t_totals['kw_shield'] += $kw_shield;
    $t_totals['kw_basic'] += $kw_basic;
    $t_totals['kw_total'] += $kw_total;


  }

  // Durchschnittswerte erzeugen
  $t_avgs['depot_perc'] = round( ($t_totals['iridium'] + $t_totals['holzium'] + $t_totals['water']) / $t_totals['depot'], 4 ) * 100;
  $t_avgs['depot_oxy_perc'] = round( $t_totals['oxygen'] / $t_totals['depot_oxy'], 4 ) * 100;

  foreach($t_totals as $key => &$value)
  {
    if( is_array($value) )
    {
      foreach($value as $k => $val)
      {
        $t_avgs[$key][$k] = number_format(Round( $val / $anzahl ),0,',','.');
        $val = number_format($val,0,',','.');
      }
    }
    else
    {
      $t_avgs[$key] = number_format(Round( $value / $anzahl ),0,',','.');
      $value = number_format($value,0,',','.');
    }
  }

  // Technologien zusammenstellen
  $get_techs = sql_query("SELECT t_". implode(",t_",$t_db_name) ." FROM usarios WHERE ID='$_SESSION[user]'");
    $techs = sql_fetch_row($get_techs);
  $t_techs = Array(
    'engine'   => array_slice($techs, 0, 3),
    'weapon'   => array_slice($techs, 3, 3),
    'fleet'    => array_slice($techs, 6, 3),
    'building' => array_slice($techs, 9, 4)
  );
 // end specific page logic

  // add pfusch output
  $template->set('nbrCities', $anzahl);
  $template->set('cities', $t_cities);
  $template->set('total', $t_totals);
  $template->set('avg', $t_avgs);
  $template->set('techs', $t_techs);

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
