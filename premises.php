<?php
  $use_lib = 17; // MSG_PREMISES;

  require('msgs.php');
  require_once('database.php');
  require('constants.php');
  require_once('functions.php');
  require_once('do_loop.php');
  include("tutorial.php");

  // define phptal template
  require_once("PHPTAL.php");
  require_once("include/PHPTAL_EtsTranslator.php");
  $template = new PHPTAL('premises.html');
  $template->setTranslator(new PHPTAL_EtsTranslator());
  $template->setEncoding('ISO-8859-1');

  // set page title
  $template->set('pageTitle', 'Übersichten - Gebäude/Technologien');

 // insert specific page logic here
  $get_buildings = sql_query("SELECT b_". implode(",b_",$b_db_name) .", home FROM city WHERE ID='$_SESSION[city]' && user='$_SESSION[user]'");
  $buildings = sql_fetch_array($get_buildings);

  $get_user_tech = sql_query("SELECT t_". implode(",t_",$t_db_name) ." FROM usarios WHERE ID='$_SESSION[user]'");
  $user_techs = sql_fetch_array($get_user_tech);

  $use_lib = MSG_WORK_BOARD;
  require('msgs.php');
  require('constants.php');

  $cats = array();
  $catname = '';
  for ($i=0,$j=-1;$i<ANZAHL_GEBAEUDE;$i++) {
    if ($catname != $b_category[$i]) {
        $catname = $b_category[$i];
        $cats[++$j] = array('name'=>$catname, 'items'=>array());
    }

    $item = array('premise'=>'');
    $item[id] = $i;
    $item[name] = $b_name[$i];
    $item[stufe] = $buildings[$i];

    if ($buildings[$b_premise[$i]] >= $b_need[$i][$b_premise[$i]])
      if ($buildings[$i])
        $item[color] = '#00FF00';
      else
        $item[color] = '#FFFF00';
    else
      $item[color] = '#FF0000';

    if ($b_premise[$i] > 0)
      $item[premise] = $b_name[$b_premise[$i]] .' ('. $b_need[$i][$b_premise[$i]] .')';

    $cats[$j][items][] = $item;
  }

  $template->b_categories = $cats;

  $use_lib = MSG_TECH_CENTER;
  require('msgs.php');
  require('constants.php');

  $has_tech_center = $buildings[TECH_CENTER] != 0 || $buildings['home'] == 'NO';
  $cats = array();
  for ($i=0;$i<ANZAHL_TECHNOLOGIEN;$i++)
  {
    $allowed = $has_tech_center;
    if ($has_tech_center) {
        for ($y=T_TECH1;$y<=T_TECH2;$y++)
          if ($user_techs[$t_tech[$i][$y]] < $t_need_techs[$i][$t_tech[$i][$y]])
            $allowed = false;
    
        for ($y=T_BUILD1;$y<=T_BUILD2;$y++)
          if ($buildings[$t_tech[$i][$y]] < $t_need_builds[$i][$t_tech[$i][$y]])
            $allowed = false;
    
    }

    if ($catname != $t_category[$i]) {
        $catname = $t_category[$i];
        $cats[++$j] = array('name'=>$catname, 'items'=>array());
    }

    $item = array('premise'=>'');
    $item[id] = $i;
    $item[name] = $t_name[$i];
    $item[stufe] = $user_techs[$i];

    if ($allowed)
      if ($user_techs[$i])
        $item[color] = '#00FF00';
      else
        $item[color] = '#FFFF00';
    else
      $item[color] = '#FF0000';

    for ($y=T_TECH1;$y<=T_TECH2;$y++)
      if ($t_tech[$i][$y] > NOTECH)
          $item[premise] .= $t_name[$t_tech[$i][$y]] .' ('. $t_need_techs[$i][$t_tech[$i][$y]] .')<br />';

    for ($y=T_BUILD1;$y<=T_BUILD2;$y++)
      if ($t_tech[$i][$y] > NOBUILD)
          $item[premise] .= $b_name[$t_tech[$i][$y]] .' ('. $t_need_builds[$i][$t_tech[$i][$y]] .')<br />';

    $cats[$j][items][] = $item;
  }

  $template->t_categories = $cats;

  $has_def_center = $buildings[DEF_CENTER] != 0;

  $cats = array();
  for ($i=0;$i<ANZAHL_DEFENSIVE;$i++)
  {
    $allowed = $has_def_center;
    if ($has_def_center) {
        if ($user_techs[$d_tech[$i][T_POWER]] < $d_need_techs[$i][$d_tech[$i][T_POWER]])
          $allowed = false;
    
        if ($buildings[$d_tech[$i][T_BUILD1]] < $d_need_builds[$i][$d_tech[$i][T_BUILD1]])
          $allowed = false;
    }

    $item = array('premise'=>'');
    $item[id] = $i;
    $item[name] = $d_name[$i];

    if ($allowed)
      $item[color] = '#00FF00';
    else
      $item[color] = '#FF0000';

    if ($d_tech[$i][T_POWER] > NOTECH && $d_tech[$i][T_POWER] < EW_WEAPONS)
          $item[premise] .= $t_name[$d_tech[$i][T_POWER]] .' ('. $d_need_techs[$i][$d_tech[$i][T_POWER]] .')<br />';

    if ($d_tech[$i][T_BUILD1] > NOBUILD)
          $item[premise] .= $b_name[$d_tech[$i][T_BUILD1]] .' ('. $d_need_builds[$i][$d_tech[$i][T_BUILD1]] .')<br />';

    $cats[] = $item;
  }

  $template->defense = $cats;

  $has_hangar = $buildings[HANGAR] != 0;

  $cats = array(array('name'=>'Kampf-Flugzeuge', 'items'=>array()));
  for ($i=0,$j=0;$i<ANZAHL_FLUGZEUGE;$i++)
  {
      $allowed = $has_hangar;
    if ($has_hangar) {
        for ($y=T_SPEED;$y<=T_POWER;$y++)
          if ($user_techs[$p_tech[$i][$y]] < $p_need[$i][$p_tech[$i][$y]])
            $allowed = false;
        
        //CM für Settler
        if ($i == SETTLER)
          if ($user_techs[COMP_MANAGEMENT] < $p_need[$i][COMP_MANAGEMENT])
            $allowed = false;
    }
    

    if ($i == ESPIONAGE_PROBE) {
        $cats[++$j] = array('name'=>'Spezialflugzeuge', 'items'=>array());
    } else if ($i == SMALL_TRANSPORTER) {
        $cats[++$j] = array('name'=>'Handelsflugzeuge', 'items'=>array());
    }

    $item = array('premise'=>'');
    $item[id] = $i;
    $item[name] = $p_name[$i];

    if ($allowed)
      $item[color] = '#00FF00';
    else
      $item[color] = '#FF0000';


    for ($y=T_SPEED;$y<=T_POWER;$y++)
      if ($p_need[$i][$p_tech[$i][$y]] > 0)
        $item[premise] .= $t_name[$p_tech[$i][$y]] .' ('. $p_need[$i][$p_tech[$i][$y]] .')<br />';
        
    //CM für Settler
    if ($i == SETTLER)
	$item[premise] .= $t_name[COMP_MANAGEMENT] .' ('. $p_need[$i][COMP_MANAGEMENT] .')<br />';

    $cats[$j][items][] = $item;
  }

  $template->planes = $cats;

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
