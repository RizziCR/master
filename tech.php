<?php
  $use_lib = 16; // MSG_TECH_CENTER
  $add_steps = null;

  require_once("msgs.php");
  require_once("database.php");
  require_once("constants.php");
  require_once("functions.php");
  require_once("do_loop.php");

// define phptal template
  require_once("PHPTAL.php");
  require_once("include/PHPTAL_EtsTranslator.php");
  //$template = new PHPTAL('tech.html');
  $template = new PHPTAL('theme_blue_line.html');
  $template->setTranslator(new PHPTAL_EtsTranslator());
  $template->setEncoding('ISO-8859-1');
  $template->set('contentMacroName','tech.html/content');

  // set page title
  $template->set('pageTitle', 'Stadt - Technologiezentrum');
  
  $tut_build = 2;
  include("tutorial.php");
  $template->set('pfuschOutput', $pfuschOutput);
  
 // insert specific page logic here

  $get_buildings = sql_query("SELECT b_". implode(",b_",$b_db_name) ." FROM city WHERE ID='$_SESSION[city]' && user='$_SESSION[user]'");
  $buildings = sql_fetch_array($get_buildings);


  if (!$buildings['b_technologie_center'])
    ErrorMessage(MSG_TECH_CENTER,e000);  // Sie müssen erst ein Technologie-Zentrum bauen, um diese Funktion nutzen zu können

  if (ErrorMessage(0))
  {
    $errorMessage .= "  <h1>{$MESSAGES[MSG_TECH_CENTER][m000]}</h1>";
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


  for ($i=0;$i<ANZAHL_TECHNOLOGIEN;$i++)
  {
    $disable[$i] = "";
    $disable_next[$i] = "disabled";
    $button[$i] = "button";
    $button_next[$i] = "button_disabled";
    $value[$i] = $MESSAGES[MSG_TECH_CENTER]['m001']; // Erforschen
    $value_next[$i] = $MESSAGES[MSG_TECH_CENTER]['m009']; // Einbauen!
    $work_time[$i] = "";
    $show_countdown[$i] = "";
  }

  $get_technologies = sql_query("SELECT t_". implode(",t_",$t_db_name) ." FROM usarios WHERE ID='$_SESSION[user]'");
  $techs = sql_fetch_array($get_technologies);

  
  $get_jobs_tech = sql_query("SELECT current_build,start_city,end_time,level,msg FROM jobs_tech WHERE user='$_SESSION[user]' ORDER BY end_time ASC");
  $num_jobs = sql_num_rows($get_jobs_tech);
  
  $jobs_tech = array();
  while ( $row = sql_fetch_row($get_jobs_tech) ) {
  	$jobs_tech[] = $row;
  }
  
  $techs_new = array();
  foreach ($t_db_name AS $index => $name ) {
  	$techs_new[$index] = 0;
  	$name_invers["t_".$name] = $index;
  }


  foreach ($jobs_tech AS $job) {
  	$techs_new[$name_invers[$job[0]]]++;
  }
  $techs_total = array();
  foreach ($t_db_name AS $index => $name ) {
  	$techs_total[$index] = $techs[$index] + $techs_new[$index]; 
  }

  for ($i=0;$i<ANZAHL_TECHNOLOGIEN;$i++)
  {
    $pay_holzium[$i] = price($t_holzium[$i],$techs_total[$i],$t_pricing_holzium[$i]);
    $pay_oxygen[$i] = price($t_oxygen[$i],$techs_total[$i],$t_pricing_oxygen[$i]);
    $duration[$i] = duration($t_duration[$i],$techs_total[$i],$buildings[TECH_CENTER],$i, $techs_total);
  }


  ///////// ÄNDERUNG FÜR TUTORIAL !!!!!!! //////////
  
  if($tut['tutorial'] == 9) {
  	$duration[10] = 1;
  	$duration[11] = 1;
  }
  
  //////////////////////////////////////////////////

  switch ($_POST['action'])
  {
    case $MESSAGES[MSG_TECH_CENTER]['m001'] : // Erforschen
    {
      if ($jobs_tech[0])
        ErrorMessage(MSG_TECH_CENTER,e001);  // Es wird zur Zeit gebaut

      if ($timefixed_depot->getHolzium() < $pay_holzium[$_POST['technologie']] || $timefixed_depot->getOxygen() < $pay_oxygen[$_POST['technologie']])
        ErrorMessage(MSG_TECH_CENTER,e002);  // Sie haben nicht genügend Rohstoffe

      
   if($tut['tutorial'] != 9) {   
      for ($y=T_TECH1;$y<=T_TECH2;$y++)
        if ($techs_total[$t_tech[$_POST['technologie']][$y]] < $t_need_techs[$_POST['technologie']][$t_tech[$_POST['technologie']][$y]])
          ErrorMessage(MSG_TECH_CENTER,e005);  // Sie erfüllen nicht die nötigen Voraussetzungen zum Erforschen der Technologie

      for ($y=T_BUILD1;$y<=T_BUILD2;$y++)
        if ($buildings[$t_tech[$_POST['technologie']][$y]] < $t_need_builds[$_POST['technologie']][$t_tech[$_POST['technologie']][$y]])
          ErrorMessage(MSG_TECH_CENTER,e005);  // Sie erfüllen nicht die nötigen Voraussetzungen zum Erforschen der Technologie

   }
      if (ErrorMessage(0))
      {
        $errorMessage .= "  <h1>{$MESSAGES[MSG_TECH_CENTER][m000]}</h1>";
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

      $reset_time = time();
      $timefixed_depot->removeHolzium($pay_holzium[$_POST['technologie']]);
      $timefixed_depot->removeOxygen($pay_oxygen[$_POST['technologie']]);
      sql_query("INSERT INTO jobs_tech (user,start_city,current_build,end_time,level,msg) VALUES ('$_SESSION[user]','$_SESSION[city]','t_{$t_db_name[$_POST[technologie]]}','".
      	 ($duration[$_POST[technologie]]+$reset_time) ."',(SELECT t_{$t_db_name[$_POST[technologie]]}+1 FROM usarios WHERE ID='$_SESSION[user]'),
      	 CONCAT('{$t_name[$_POST[technologie]]} Ausbaustufe ',(SELECT t_{$t_db_name[$_POST[technologie]]}+1 FROM usarios WHERE ID='$_SESSION[user]'),' wurde fertiggestellt'))");

      break;
    }
    case $MESSAGES[MSG_TECH_CENTER]['m009'] : // Vormerken
    {
      if ($jobs_tech[MAX_MARKS_TECH])
        ErrorMessage(MSG_TECH_CENTER,e007);  // Es ist bereits eine Technologie vorgemerkt

      if (!$jobs_tech[0])
        ErrorMessage(MSG_TECH_CENTER,e003);  // Es befindet sich keine Technologie im Bau
        
      if ($timefixed_depot->getHolzium() < $pay_holzium[$_POST['technologie']] || $timefixed_depot->getOxygen() < $pay_oxygen[$_POST['technologie']])
        ErrorMessage(MSG_TECH_CENTER,e002);  // Sie haben nicht genügend Rohstoffe

    if($tut['tutorial'] != 9) {
      for ($y=T_TECH1;$y<=T_TECH2;$y++)
        if ($techs_total[$t_tech[$_POST['technologie']][$y]] < $t_need_techs[$_POST['technologie']][$t_tech[$_POST['technologie']][$y]])
          ErrorMessage(MSG_TECH_CENTER,e005);  // Sie erfüllen nicht die nötigen Voraussetzungen zum Erforschen der Technologie

      for ($y=T_BUILD1;$y<=T_BUILD2;$y++)
        if ($buildings[$t_tech[$_POST['technologie']][$y]] < $t_need_builds[$_POST['technologie']][$t_tech[$_POST['technologie']][$y]])
          ErrorMessage(MSG_TECH_CENTER,e005);  // Sie erfüllen nicht die nötigen Voraussetzungen zum Erforschen der Technologie
    
    }
      if (ErrorMessage(0))
      {

        $errorMessage .= "  <h1>{$MESSAGES[MSG_TECH_CENTER][m000]}</h1>";
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

      $timefixed_depot->removeHolzium($pay_holzium[$_POST[technologie]]);
      $timefixed_depot->removeOxygen($pay_oxygen[$_POST[technologie]]);
      
      //sql_query("UPDATE city SET b_next_build='b_{$b_db_name[$_POST[building]]}',b_end_time_next=b_end_time+{$duration[$_POST[building]]},msg_next=CONCAT('{$b_name[$_POST[building]]} Ausbaustufe ',
      // b_{$b_db_name[$_POST[building]]}+1+{$buildings_new[$_POST[building]]},' wurde auf $_SESSION[city] fertiggestellt') WHERE city='$_SESSION[city]'");

      $end_time_all = sql_query("SELECT MAX(end_time) FROM jobs_tech WHERE user='$_SESSION[user]'");
      $end_time = sql_fetch_array($end_time_all);

      sql_query("INSERT INTO jobs_tech (user,start_city,current_build,end_time,level,msg) VALUES ('$_SESSION[user]','$_SESSION[city]','t_{$t_db_name[$_POST[technologie]]}',
      {$duration[$_POST[technologie]]} + $end_time[0] ,(SELECT t_{$t_db_name[$_POST[technologie]]}+1+{$techs_new[$_POST[technologie]]} FROM usarios WHERE ID='$_SESSION[user]'),
      	 CONCAT('{$t_name[$_POST[technologie]]} Ausbaustufe ',(SELECT t_{$t_db_name[$_POST[technologie]]}+1+{$techs_new[$_POST[technologie]]} FROM usarios WHERE ID='$_SESSION[user]'),' wurde fertiggestellt'))");

      break;
    }
    
    case $MESSAGES[MSG_TECH_CENTER]['m002'] : // Abbrechen
    {
      if (!$jobs_tech[0])
        ErrorMessage(MSG_TECH_CENTER,e003);  // Zur Zeit wird nichts gebaut

      if ($jobs_tech[0][1] != $_SESSION['city'])
        ErrorMessage(MSG_TECH_CENTER,e004);  // Sie können Forschungen nur in der Stadt abbrechen, in der Sie den Auftrag gegeben haben

      if (ErrorMessage(0))
      {
        $errorMessage .= "  <h1>{$MESSAGES[MSG_TECH_CENTER][m000]}</h1>";
        $errorMessage .= ErrorMessage();

        // add error output
        $template->set('errorMessage', $errorMessage);

        // include common template settings
        require_once("include/JavaScriptCommon.php");
        require_once("include/TemplateSettingsCommon.php");

        // save resource changes (ToDo: Is this necessary on every page?)
        $timefixed_depot->save();

        // create html page  $get_technologies = sql_query("SELECT t_". implode(",t_",$t_db_name) .",t_current_build,t_end_time,t_start_city FROM usarios WHERE user='$_SESSION[user]'");
        try {
          echo $template->execute();
        }
        catch (Exception $e) { echo $e->getMessage(); }
        die();
      }

      $cancel_tech = $name_invers[$jobs_tech[0][0]];
      $duration[$cancel_tech] = duration($t_duration[$cancel_tech],$techs[$cancel_tech],$buildings[TECH_CENTER],$cancel_tech, $user_techs);

      $pay_holzium[$cancel_tech] = price($t_holzium[$cancel_tech],$techs[$cancel_tech],$t_pricing_holzium[$cancel_tech]);
      $pay_oxygen[$cancel_tech] = price($t_oxygen[$cancel_tech],$techs[$cancel_tech],$t_pricing_oxygen[$cancel_tech]);
      if ( $jobs_tech[0] ) { $reduce_factor = ($jobs_tech[0][2] - time()) / ($duration[$cancel_tech]); } else $reduce_factor = 1;
      $reduce_factor = min($reduce_factor, 0.8);

      
      $timefixed_depot->addHolzium($pay_holzium[$cancel_tech] * $reduce_factor);
      $timefixed_depot->addOxygen($pay_oxygen[$cancel_tech] * $reduce_factor);
      
	  sql_query("DELETE FROM jobs_tech where user='$_SESSION[user]'");
      break;
    }  
    
   	case $MESSAGES[MSG_TECH_CENTER]['m010'] : // Vormerk. entf.
    {
      if ( ( $num_jobs < 1 ) && ( $_POST['action'] == $MESSAGES[MSG_TECH_CENTER]['m010']) ) // Vormerk. entf.
        ErrorMessage(MSG_TECH_CENTER,e006);  // Zur Zeit ist nichts vorgemerkt

      if (ErrorMessage(0))
      {
        $errorMessage .= "  <h1>{$MESSAGES[MSG_TECH_CENTER][m000]}</h1>";
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

      $first_time = sql_query("SELECT MIN(end_time) FROM jobs_tech WHERE user='$_SESSION[user]'"); // Diese Zeit steckt im entsprechenden Aufheben-Knopf
      $first_end_time = sql_fetch_array($first_time);
		$first_end_time = max($first_end_time[0]+1,$_POST['technologie']);
      sql_query("DELETE FROM jobs_tech WHERE user='$_SESSION[user]' AND end_time >= $first_end_time");
      break;
    }
  }

  $get_buildings = sql_query("SELECT b_". implode(",b_",$b_db_name) ." FROM city WHERE ID='$_SESSION[city]'");
  $buildings = sql_fetch_array($get_buildings);

  $get_techs = sql_query("SELECT t_". implode(",t_",$t_db_name) ." FROM usarios WHERE ID = '$_SESSION[user]'");
  $techs = sql_fetch_array($get_techs);
  
  $get_jobs_tech = sql_query("SELECT current_build,start_city,end_time,level,msg FROM jobs_tech WHERE user='$_SESSION[user]' ORDER BY end_time ASC");
  $num_jobs = sql_num_rows($get_jobs_tech);

  $jobs_tech = array();
  while ( $row = sql_fetch_row($get_jobs_tech) ) {
  	$jobs_tech[] = $row;
  }

  $techs_new = array();
  foreach ($t_db_name AS $index => $name ) {
  	$techs_new[$index] = 0;
  	$name_invers["t_".$name] = $index;
  }

  foreach ($jobs_tech AS $job) {
  	$techs_new[$name_invers[$job[0]]]++;
  }
  
  foreach ($t_db_name AS $index => $name ) {
  	$techs_total[$index] = $techs[$index] + $techs_new[$index];
  }
  
 for ($i=0;$i<ANZAHL_TECHNOLOGIEN;$i++)
  {
    $pay_holzium[$i] = price($t_holzium[$i],$techs_total[$i],$t_pricing_holzium[$i]);
    $pay_oxygen[$i] = price($t_oxygen[$i],$techs_total[$i],$t_pricing_oxygen[$i]);
    $duration[$i] = duration($t_duration[$i],$techs_total[$i],$buildings[TECH_CENTER]);
  }
  

  ///////// ÄNDERUNG FÜR TUTORIAL !!!!!!! //////////
  
  if($tut['tutorial'] == 9) {
  	$duration[10] = 1;
  	$duration[11] = 1;
  }
  
  //////////////////////////////////////////////////
  

// start template

  $t_level_tz = $buildings['b_technologie_center'];
  $t_techs = array();
  // level = aktuelle Stufe (inkl. in Forschung)
  // => Dauer( ... , ... , stufe[bz] ) ergibt Forschzeit von Forschung mit "aktueller Stufe"
  for( $i=0 ; $i<ANZAHL_TECHNOLOGIEN ; $i++ )
  {
/*    $temp_level = $techs["t_$t_db_name[$i]"];
    $temp_time = 0;
    $temp_time_next = 0;
    if( $techs['t_current_build'] == "t_". $t_db_name[$i] ) // schon eine am forschen
    {
      $temp_level += 1;
      $temp_time = maketime($techs['t_end_time']-time());
    }
   if( $techs['t_next_build'] == "t_". $t_db_name[$i] ) // schon eine am vorforschen
    {
      $temp_level += 1;
      $temp_time_next = maketime($techs['t_end_time_next']-time());
    } */

  	$t_techs[$i]['title'] = '';
    $t_techs[$i]['onclick'] = '';
    switch( $i )
    {
      case '1': // Hoverantrieb
        $t_techs[$i]['title'] = 'Du musst zuerst '. $t_name[O_DRIVE] .' (Stufe '. $t_need_techs[H_DRIVE][O_DRIVE] .') erforschen, um diese Forschung freizuschalten.';
        break;
      case '2': // Antigravitationsantrieb
        $t_techs[$i]['title'] = 'Du musst zuerst '. $t_name[O_DRIVE] .' (Stufe '. $t_need_techs[A_DRIVE][O_DRIVE] .') und '. $t_name[H_DRIVE] .' (Stufe '. $t_need_techs[A_DRIVE][H_DRIVE] .') erforschen, um diese Forschung freizuschalten.';
        break;
      case '4': // Protonensequenzwaffen
        $t_techs[$i]['title'] = 'Du musst zuerst '. $t_name[E_WEAPONS] .' (Stufe '. $t_need_techs[P_WEAPONS][E_WEAPONS] .') erforschen und '. $b_name[TECH_CENTER] .' (Stufe '. $t_need_builds[P_WEAPONS][TECH_CENTER] .') bauen, um diese Forschung freizuschalten.';
        break;
      case '5': // Neutronensequenzwaffen
        $t_techs[$i]['title'] = 'Du musst zuerst '. $t_name[P_WEAPONS] .' (Stufe '. $t_need_techs[N_WEAPONS][P_WEAPONS] .') erforschen und '. $b_name[TECH_CENTER] .' (Stufe '. $t_need_builds[N_WEAPONS][TECH_CENTER] .') bauen, um diese Forschung freizuschalten.';
        break;
      case '7': // Flugzeugkapazitätsverwaltung
        $t_techs[$i]['title'] = 'Du musst zuerst '. $t_name[DEPOT_MANAGEMENT] .' (Stufe '. $t_need_techs[PLANE_SIZE][DEPOT_MANAGEMENT] .') erforschen, um diese Forschung freizuschalten.';
        break;
      case '8': // Computermanagement
        $t_techs[$i]['title'] = 'Du musst zuerst '. $b_name[HANGAR] .' (Stufe '. $t_need_builds[COMP_MANAGEMENT][HANGAR] .') bauen, um diese Forschung freizuschalten.';
        break;
      case '10': // Wasserkompression
        $t_techs[$i]['title'] = 'Du musst zuerst '. $b_name[OXYGEN] .' (Stufe '. $t_need_builds[COMPRESSION][OXYGEN] .') bauen, um diese Forschung freizuschalten.';
        break;
      case '11': // Bergbautechnik
        $t_techs[$i]['title'] = 'Du musst zuerst '. $b_name[IRIDIUM] .' (Stufe '. $t_need_builds[MINING][IRIDIUM] .') und '. $b_name[HOLZIUM] .' (Stufe '. $t_need_builds[MINING][HOLZIUM] .') bauen, um diese Forschung freizuschalten.';
        break;
//      case '12': // Schutzschildtechnologie
//        $t_techs[$i]['title'] = 'Du musst zuerst '. $b_name[SHIELD] .' (Stufe '. $t_need_builds[SHIELD_TECH][SHIELD] .') bauen, um diese Forschung freizuschalten.';
//        break;
    }

    $t_techs[$i]['index'] = $i;
    $t_techs[$i]['name'] = $t_name[$i];
    $t_techs[$i]['level'] = $techs[$i];
    $t_techs[$i]['level_new'] = $techs_new[$i];
    $t_techs[$i]['level_total'] = $techs_total[$i];
    
    $t_techs[$i]['cost_holz'] = number_format($pay_holzium[$i],0,',','.');
    $t_techs[$i]['cost_o2'] = number_format($pay_oxygen[$i],0,',','.');
    $t_techs[$i]['time'] = maketime(Duration( $t_duration[$i] , $techs_total[$i] , $t_level_tz, $i, $techs));
    $t_techs[$i]['isSearching'] = ( $techs['t_current_build'] == "t_$t_db_name[$i]" ) ? 1 : 0 ;
    $t_techs[$i]['isMarked'] = ( $techs['t_next_build'] == "t_$t_db_name[$i]" ) ? 1 : 0 ;
    $t_techs[$i]['time_left'] = $temp_time;
    $t_techs[$i]['time_left_next'] = $temp_time_next;


    $temp_premises = 0;
    for ($y=T_TECH1;$y<=T_TECH2;$y++)
      if( ($techs_total[$t_tech[$i][$y]] < $t_need_techs[$i][$t_tech[$i][$y]]) )
        $temp_premises = 1;
    for ($y=T_BUILD1;$y<=T_BUILD2;$y++)
      if( ($buildings[$t_tech[$i][$y]] < $t_need_builds[$i][$t_tech[$i][$y]]) )
        $temp_premises = 1;
    $t_techs[$i]['isDisabled'] = $temp_premises;
    $t_techs[$i]['isAffordable'] = ( $timefixed_depot->getHolzium() >= $pay_holzium[$i] && $timefixed_depot->getOxygen() >= $pay_oxygen[$i] ) ? 1 : 0 ;
  }

  
  ///////// ÄNDERUNG FÜR TUTORIAL !!!!!!! //////////
  
  	if($tut['tutorial'] == 9) {
  		// BBT / WK Forschen
  		for($i=0;$i<ANZAHL_TECHNOLOGIEN;$i++) {
  			if($i == 10 && $t_techs[10]['level_total'] < 1) {
  				$t_techs[$i]['time'] = maketime(1);
  				$t_techs[$i]['isDisabled'] = "0";
  			}elseif($i == 11 && $t_techs[11]['level_total'] < 2) {
  				$t_techs[$i]['time'] = maketime(1);
  				$t_techs[$i]['isDisabled'] = "0";
  			}else{
  				$t_techs[$i]['isDisabled'] = "1";
  				$t_techs[$i]['title'] = "Forsch als erstes Wasserkompression 1 und Bergbautechnik 2 um Fortzufahren.";
  			}
  		}
  	}
   
  ////////////////////////////////////////////////////
  
  
  
  $t_categories = array(
    0 => array(
      'heading' => $MESSAGES[MSG_TECH_CENTER]['m003'], // Antriebstechnologien
      'techs' => array_slice($t_techs, 0, 3)
    ),
    1 => array(
      'heading' => $MESSAGES[MSG_TECH_CENTER]['m004'], // Waffentechnologien
      'techs' => array_slice($t_techs, 3, 3)
    ),
    2 => array(
      'heading' => $MESSAGES[MSG_TECH_CENTER]['m005'], // Flugzeug-Forschungen
      'techs' => array_slice($t_techs, 6, 3)
    ),
    3 => array(
      'heading' => $MESSAGES[MSG_TECH_CENTER]['m006'], // Gebäude-Technologien
      'techs' => array_slice($t_techs, 9, 4)
    )
  );

  
  if($jobs_tech)
  {	// Liste der bauaufträge erstellen
	for($i=0;$jobs_tech[$i];$i++)	{
		$tech_jobs[$i]['name'] = translate_technologies($jobs_tech[$i][0]);
		$tech_jobs[$i]['level'] = $jobs_tech[$i][3];
		$tech_jobs[$i]['time'] = $jobs_tech[$i][2];
		$tech_jobs[$i]['index'] = array_search($tech_jobs[$i]['name'],$t_name);
		$tech_jobs[$i]['time_left'] = $jobs_tech[$i][2]-time();
		if($i==0) {
			$tech_jobs[$i]['first'] = true;
		}
		else {
			$tech_jobs[$i]['first'] = False;
		}
		$tech_jobs[$i]['countdown'] = "countdown".$i;
	}
	$template->set('jobs',$tech_jobs);
  }

  $template->set('categories', $t_categories);
  $jobs_tech[0] ? $template->set('sthSearching', 1 ) : $template->set('sthSearching', 0 );
  $jobs_tech[MAX_MARKS_TECH] ? $template->set('sthMarked', 1 ) : $template->set('sthMarked', 0 );
  $jobs_tech[0][1] == $_SESSION['city'] ? $template->set('isStarterCity', 1 ) : $template->set('isStarterCity', 0 );
  

  $js_code = "function ask(mode, hasMarking) {
    if(mode) // 0 = Vormerkung aufheben, 1 = Bau abbrechen
    {
      var text = 'Beim Abbrechen einer Forschung bekommst du maximal 80% der investierten Rohstoffe zurückerstattet (entsprechend der Zeit, die bereits in die Erforschung gesteckt wurde).\\n\\nBist du sicher, dass du die Forschung abbrechen willst?';
    }
    else
    {
      var text = 'Achtung: Beim Aufheben einer Vormerkung gehen ALLE für die vorgemerkte Forschung investierten Rohstoffe verloren!\\n\\nBist du sicher, dass du die Vormerkung aufheben willst?';
    }
    return window.confirm(text);
  }
  ";
/*  $js_code = "function ask() {
    var text = 'Beim Abbrechen einer Forschung bekommst du maximal 80% der investierten Rohstoffe zurückerstattet (entsprechend der Zeit, die bereits in die Erforschung gesteckt wurde).\\n\\nBist du sicher, dass du die Forschung abbrechen willst?';
    return window.confirm(text);
  }
  "; */
  
  for($i=0;$jobs_tech[$i];$i++)
  {
    $diff = $jobs_tech[$i][2]-time();
      $js_code .= "_initTimer('countdown".$i."',$diff,'fertig',false,'',true);";
  }
  $template->set('specificJS', $js_code);

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
