<?php
  $use_lib = 14; // MSG_WORK_BOARD
  $add_steps = null;

  require_once("msgs.php");
  require_once("database.php");
  require_once("constants.php");
  require_once("functions.php");
  require_once("do_loop.php");

    // define phptal template
  require_once("PHPTAL.php");
  require_once("include/PHPTAL_EtsTranslator.php");
  //$template = new PHPTAL('build.html');
  $template = new PHPTAL('theme_blue_line.html');
  $template->setTranslator(new PHPTAL_EtsTranslator());
  $template->setEncoding('ISO-8859-1');
  $template->set('contentMacroName','build.html/content');

  // set page title
  $template->set('pageTitle', 'Stadt - Bauzentrum');
  
  $tut_build = 1;
  include("tutorial.php");
  $template->set('pfuschOutput', $pfuschOutput);

 // insert specific page logic here
  
  if ($_POST['action'] && $_POST['bc'] != "2fkp8fnc63")
    sql_query("INSERT INTO _bot_user (user,time) VALUES ('$_SESSION[user]',UNIX_TIMESTAMP())");

  $get_home = sql_query("SELECT city,home FROM city WHERE ID='$_SESSION[city]'");
  $home = sql_fetch_array($get_home);

  for ($i=0;$i<ANZAHL_GEBAEUDE;$i++)
  {
    $disable[$i] = "";
    $disable_next[$i] = "disabled";
    $button[$i] = "button";
    $button_next[$i] = "button_disabled";
    $value[$i] = $MESSAGES[MSG_WORK_BOARD]['m010']; // Bauen
    $value_next[$i] = $MESSAGES[MSG_WORK_BOARD]['m011']; // Vormerken
    $work_time[$i] = "";
    $show_countdown[$i] = "";
  }
	
//  $get_buildings = sql_query("SELECT b_". implode(",b_",$b_db_name) .",b_current_build,b_end_time,b_next_build,b_end_time_next FROM city WHERE city='$_SESSION[city]'");
  $get_buildings = sql_query("SELECT b_". implode(",b_",$b_db_name) ." FROM city WHERE ID='$_SESSION[city]'");
  $buildings = sql_fetch_array($get_buildings);

  $get_jobs_build = sql_query("SELECT current_build,end_time,level,msg FROM jobs_build WHERE city='$_SESSION[city]' ORDER BY end_time ASC");
  $num_jobs = sql_num_rows($get_jobs_build);

  $jobs_build = array();
  while ( $row = sql_fetch_row($get_jobs_build) ) {
  	$jobs_build[] = $row;
  }
  
  $buildings_new = array();
  foreach ($b_db_name AS $index => $name ) {
  	$buildings_new[$index] = 0;
  	$name_invers["b_".$name] = $index;
  }


  foreach ($jobs_build AS $job) {
  	$buildings_new[$name_invers[$job[0]]]++;
  }
  $buildings_total = array();
  foreach ($b_db_name AS $index => $name ) {
  	$buildings_total[$index] = $buildings[$index] + $buildings_new[$index]; 
  }
  
 for ($i=0;$i<ANZAHL_GEBAEUDE;$i++)
  {
    $pay_iridium[$i] = price($b_iridium[$i],$buildings_total[$i],$b_pricing_iridium[$i]);
    $pay_holzium[$i] = price($b_holzium[$i],$buildings_total[$i],$b_pricing_holzium[$i]);
    $duration[$i] = duration($b_duration[$i],$buildings_total[$i],$buildings[WORK_BOARD]+$buildings_new[WORK_BOARD]);

//    $pay_iridium_next[$i] = price($b_iridium[$i],$buildings[$i]+1,$b_pricing_iridium[$i]);
//    $pay_holzium_next[$i] = price($b_holzium[$i],$buildings[$i]+1,$b_pricing_holzium[$i]);
  }
  
  
  ///////// ÄNDERUNG FÜR TUTORIAL !!!!!!! //////////
  if($tut['tutorial'] == 7) 
  		$duration[0] = 1;
  if($tut['tutorial'] == 8) 
  		$duration[9] = 1;
  //////////////////////////////////////////////////
  
  $duration[WORK_BOARD] = Duration_Work_Board($buildings_total[WORK_BOARD]);
  
  switch ($_POST['action'])
  {
    case $MESSAGES[MSG_WORK_BOARD]['m010'] : // Bauen
    {
      if ($jobs_build[0])
        ErrorMessage(MSG_WORK_BOARD,e000);  // Es wird zur Zeit gebaut

      if ($timefixed_depot->getIridium() < $pay_iridium[$_POST['building']] || $timefixed_depot->getHolzium() < $pay_holzium[$_POST['building']])
        ErrorMessage(MSG_WORK_BOARD,e001);  // Sie haben nicht gen&uuml;gend Rohstoffe

      if ($buildings_total[$b_premise[$_POST['building']]] < $b_need[$_POST['building']][$b_premise[$_POST['building']]])
        ErrorMessage(MSG_WORK_BOARD,e006);  // Sie erfüllen nicht die nötigen Voraussetzungen zum Bau des Gebäudes

//      if ($_POST['building'] == TECH_CENTER && $home['home'] != 'YES')
//        ErrorMessage(MSG_WORK_BOARD,e006);  // Sie erfüllen nicht die nötigen Voraussetzungen zum Bau des Gebäudes

      if (ErrorMessage(0))
      {
        $errorMessage .= "  <h1>{$MESSAGES[MSG_WORK_BOARD][m000]}</h1>";
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

      $timefixed_depot->removeIridium($pay_iridium[$_POST['building']]);
      $timefixed_depot->removeHolzium($pay_holzium[$_POST['building']]);

      if (time() >= PAUSE_END || time() < PAUSE_BEGIN) {
          $reset_time = time();
      }
      else {
          $reset_time = PAUSE_END;
      }
      //sql_query("UPDATE city SET b_end_time='". ($duration[$_POST["building"]]+$reset_time) ."',b_current_build='b_{$b_db_name[$_POST[building]]}',".
      //    "msg=CONCAT('{$b_name[$_POST[building]]} Ausbaustufe ',b_{$b_db_name[$_POST[building]]}+1,".
      //    "' wurde auf $_SESSION[city] fertiggestellt') WHERE city='$_SESSION[city]'");
      sql_query("INSERT INTO jobs_build (city,current_build,end_time,level,msg) VALUES ('$_SESSION[city]','b_{$b_db_name[$_POST[building]]}','".
      	 ($duration[$_POST[building]]+$reset_time) ."',(SELECT b_{$b_db_name[$_POST[building]]}+1 FROM city WHERE ID='$_SESSION[city]'),
      	 CONCAT('{$b_name[$_POST[building]]} Ausbaustufe ',(SELECT b_{$b_db_name[$_POST[building]]}+1 FROM city WHERE ID='$_SESSION[city]'),' wurde auf $home[city] fertiggestellt'))");
      break;
    }

    case $MESSAGES[MSG_WORK_BOARD]['m011'] : // Vormerken
    {
      if ($jobs_build[MAX_MARKS_BUILD])
        ErrorMessage(MSG_WORK_BOARD,e002);  // Es ist bereits ein Gebäude vorgemerkt

      if (!$jobs_build[0])
        ErrorMessage(MSG_WORK_BOARD,e005);  // Es befindet sich kein Gebäude im Bau

      if ($buildings_total[$b_premise[$_POST['building']]] < $b_need[$_POST['building']][$b_premise[$_POST['building']]])
        ErrorMessage(MSG_WORK_BOARD,e006);  // Sie erfüllen nicht die nötigen Voraussetzungen zum Bau des Gebäudes

//      if ($_POST['building'] == TECH_CENTER && $home['home'] != 'YES')
//        ErrorMessage(MSG_WORK_BOARD,e006);  // Sie erfüllen nicht die nötigen Voraussetzungen zum Bau des Gebäudes
  
        if ($timefixed_depot->getIridium() < $pay_iridium[$_POST['building']] || $timefixed_depot->getHolzium() < $pay_holzium[$_POST['building']])
          ErrorMessage(MSG_WORK_BOARD,e001);  // Sie haben nicht gen&uuml;gend Rohstoffe

      if (ErrorMessage(0))
      {

        $errorMessage .= "  <h1>{$MESSAGES[MSG_WORK_BOARD][m000]}</h1>";
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
      $timefixed_depot->removeIridium($pay_iridium[$_POST[building]]);
      $timefixed_depot->removeHolzium($pay_holzium[$_POST[building]]);

      //sql_query("UPDATE city SET b_next_build='b_{$b_db_name[$_POST[building]]}',b_end_time_next=b_end_time+{$duration[$_POST[building]]},msg_next=CONCAT('{$b_name[$_POST[building]]} Ausbaustufe ',
      // b_{$b_db_name[$_POST[building]]}+1+{$buildings_new[$_POST[building]]},' wurde auf $_SESSION[city] fertiggestellt') WHERE city='$_SESSION[city]'");

      $end_time_all = sql_query("SELECT MAX(end_time) FROM jobs_build WHERE city='$_SESSION[city]'");
      $end_time = sql_fetch_array($end_time_all);

      sql_query("INSERT INTO jobs_build (city,current_build,end_time,level,msg) VALUES ('$_SESSION[city]','b_{$b_db_name[$_POST[building]]}',
      {$duration[$_POST[building]]} + $end_time[0] ,(SELECT b_{$b_db_name[$_POST[building]]}+1+{$buildings_new[$_POST[building]]} FROM city WHERE ID='$_SESSION[city]'),
      	 CONCAT('{$b_name[$_POST[building]]} Ausbaustufe ',(SELECT b_{$b_db_name[$_POST[building]]}+1+{$buildings_new[$_POST[building]]} FROM city WHERE ID='$_SESSION[city]'),' wurde auf $home[city] fertiggestellt'))");

      break;
    }  

    case $MESSAGES[MSG_WORK_BOARD]['m012'] : // Abbrechen
    {
      if (!$jobs_build[0])
        ErrorMessage(MSG_WORK_BOARD,e003);  // Zur Zeit wird nichts gebaut

      if (ErrorMessage(0))
      {
        $errorMessage .= "  <h1>{$MESSAGES[MSG_WORK_BOARD][m000]}</h1>";
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

      $cancel_building = $name_invers[$jobs_build[0][0]];
      if ( $cancel_building == WORK_BOARD ) {
      	$duration[WORK_BOARD] = Duration_Work_Board($buildings[WORK_BOARD]);
      } else {
	    $duration[$cancel_building] = duration($b_duration[$cancel_building],$buildings[$cancel_building],$buildings[WORK_BOARD]);
      }

      $pay_iridium[$cancel_building] = price($b_iridium[$cancel_building],$buildings[$cancel_building],$b_pricing_iridium[$cancel_building]);
      $pay_holzium[$cancel_building] = price($b_holzium[$cancel_building],$buildings[$cancel_building],$b_pricing_holzium[$cancel_building]);
      if ( $jobs_build[0] ) { $reduce_factor = ($jobs_build[0][1] - time()) / ($duration[$cancel_building]); } else $reduce_factor = 1;
      $reduce_factor = min($reduce_factor, 0.8);

      
      $timefixed_depot->addIridium($pay_iridium[$cancel_building] * $reduce_factor);
      $timefixed_depot->addHolzium($pay_holzium[$cancel_building] * $reduce_factor);

      //sql_query("UPDATE city SET b_end_time='',b_current_build='',msg='',b_end_time_next='',b_next_build='',msg_next='' WHERE city='$_SESSION[city]'");
	  sql_query("DELETE FROM jobs_build WHERE city='$_SESSION[city]'");
/*      $buildings['b_end_time'] = "";
      $buildings['b_current_build'] = "";
*/
      break;
    }

    case $MESSAGES[MSG_WORK_BOARD]['m013'] : // Vormerk. entf.
    {
      if ( ( $num_jobs < 1 ) && $_POST['action'] == $MESSAGES[MSG_WORK_BOARD]['m013']) // Vormerk. entf.
        ErrorMessage(MSG_WORK_BOARD,e004);  // Zur Zeit ist nichts vorgemerkt

      if (ErrorMessage(0))
      {

        $errorMessage .= "  <h1>{$MESSAGES[MSG_WORK_BOARD][m000]}</h1>";
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


      //sql_query("UPDATE city SET b_next_build='',b_end_time_next='0',msg_next='' WHERE city='$_SESSION[city]'");
      
      $first_time = sql_query("SELECT MIN(end_time) FROM jobs_build WHERE city='$_SESSION[city]'"); // Diese Zeit steckt im entsprechenden Aufheben-Knopf
      $first_end_time = sql_fetch_array($first_time);
		$first_end_time = max($first_end_time[0]+1,$_POST['building']);
      sql_query("DELETE FROM jobs_build WHERE city='$_SESSION[city]' AND end_time >= $first_end_time");
      break;
    }
  }  
  
  

  $get_buildings = sql_query("SELECT b_". implode(",b_",$b_db_name) ." FROM city WHERE ID='$_SESSION[city]'");
  $buildings = sql_fetch_array($get_buildings);

  $get_jobs_build = sql_query("SELECT current_build,end_time,level,msg FROM jobs_build WHERE city='$_SESSION[city]' ORDER BY end_time ASC");
  $num_jobs = sql_num_rows($get_jobs_build);

  $jobs_build = array();
  $buildings_jobs = array();
  while ( $row = sql_fetch_row($get_jobs_build) ) {
  	$jobs_build[] = $row;
  }

  $buildings_new = array();
  foreach ($b_db_name AS $index => $name ) {
  	$buildings_new[$index] = 0;
  	$name_invers["b_".$name] = $index;
  }

  foreach ($jobs_build AS $job) {
  	$buildings_new[$name_invers[$job[0]]]++;
  }
  
  foreach ($b_db_name AS $index => $name ) {
  	$buildings_total[$index] = $buildings[$index] + $buildings_new[$index];
  }
  
 for ($i=0;$i<ANZAHL_GEBAEUDE;$i++)
  {
    $pay_iridium[$i] = price($b_iridium[$i],$buildings_total[$i],$b_pricing_iridium[$i]);
    $pay_holzium[$i] = price($b_holzium[$i],$buildings_total[$i],$b_pricing_holzium[$i]);
    $duration[$i] = duration($b_duration[$i],$buildings_total[$i],$buildings[WORK_BOARD]);

  }

  ///////// ÄNDERUNG FÜR TUTORIAL !!!!!!! //////////
  if($tut['tutorial'] == 7)
  	$duration[0] = 1;
  if($tut['tutorial'] == 8)
  	$duration[9] = 1;
  //////////////////////////////////////////////////
  
  $duration[WORK_BOARD] = Duration_Work_Board($buildings_total[WORK_BOARD]);
  
 


// start template
  $get_buildings = sql_query("SELECT b_". implode(",b_",$b_db_name) .",points FROM city WHERE ID='$_SESSION[city]'");
  $buildings = sql_fetch_array($get_buildings);


/*  $temp_workboard = $buildings["b_$b_db_name[8]"];
  if( $buildings['b_current_build'] == "b_". $b_db_name[WORK_BOARD] ) // schon eins in Bau
  {
    $temp_workboard += 1;
  }
  if( $buildings['b_next_build'] == "b_". $b_db_name[WORK_BOARD] ) // schon eins vorgemerkt
  {
    $temp_workboard += 1;
  }
  */
  $temp_workboard = $buildings_total[WORK_BOARD];

  $t_buildings = array();
  for( $i=0 ; $i<ANZAHL_GEBAEUDE ; $i++ )
  {
    /*$temp_level = $buildings["b_$b_db_name[$i]"];
    $temp_time = 0;
    $temp_time_next = 0;
    if( $buildings['b_current_build'] == "b_". $b_db_name[$i] ) // schon eins in Bau
    {
      $temp_level += 1;
      $temp_time = maketime($buildings['b_end_time']-time());
    }
    if( $buildings['b_next_build'] == "b_". $b_db_name[$i] ) // schon eins vorgemerkt
    {
      $temp_level += 1;
      $temp_time_next = maketime($buildings['b_end_time_next']-time());
    }
    */
    $temp_level = $buildings_total[$i];
    $t_buildings[$i]['title'] = '';
    $t_buildings[$i]['onclick'] = '';
    if($temp_level == 0)
    {
      switch( $i )
      {
        case '5': // Tank
          $t_buildings[$i]['title'] = 'Du musst zuerst '. $b_name[OX_REACTOR] .' (Stufe '. $b_need[OX_DEPOT][OX_REACTOR] .') bauen, um dieses Gebäude freizuschalten.';
          break;
        case '6': // Hangar
          $t_buildings[$i]['title'] = 'Du musst zuerst '. $b_name[WORK_BOARD] .' (Stufe '. $b_need[HANGAR][WORK_BOARD] .') bauen, um dieses Gebäude freizuschalten.';
          break;
        case '7': // Flughafen
          $t_buildings[$i]['title'] = 'Du musst zuerst '. $b_name[HANGAR] .' (Stufe '. $b_need[AIRPORT][HANGAR] .') bauen, um dieses Gebäude freizuschalten.';
          break;
        case '11': // Handelszentrum
          $t_buildings[$i]['title'] = 'Du musst zuerst '. $b_name[HANGAR] .' (Stufe '. $b_need[TRADE_CENTER][HANGAR] .') bauen, um dieses Gebäude freizuschalten.';
          break;
//        case '13': // Schutzschild
//          $t_buildings[$i]['title'] = 'Du musst zuerst '. $b_name[DEF_CENTER] .' (Stufe '. $b_need[SHIELD][DEF_CENTER] .') bauen, um dieses Gebäude freizuschalten.';
//          break;
      }
    }

    $t_buildings[$i]['index'] = $i;
    $t_buildings[$i]['name'] = $b_name[$i];
    $t_buildings[$i]['level'] = $buildings[$i];
    $t_buildings[$i]['level_new'] = $buildings_new[$i];
//    if ( $buildings_new[$i] > 0 ) $t_buildings[$i]['level'] .= "+".$buildings_new[$i];

//    $temp_costs_iri = Price( $b_iridium[$i] , $temp_level , $b_pricing_iridium[$i] );
//    $temp_costs_holz = Price( $b_holzium[$i] , $temp_level , $b_pricing_holzium[$i] );
//    $t_buildings[$i]['cost_iri'] = $temp_costs_iri;
//    $t_buildings[$i]['cost_holz'] = $temp_costs_holz;
	$t_buildings[$i]['cost_iri'] = number_format($pay_iridium[$i],0,',','.');
	$t_buildings[$i]['cost_holz'] = number_format($pay_holzium[$i],0,',','.');
    
    $t_buildings[$i]['time'] = maketime(Duration( $b_duration[$i] , $buildings_total[$i] , $temp_workboard));
    if( $i==8 ) // Bauzentrum
    {
      $t_buildings[$i]['time'] = maketime(Duration_Work_Board( $temp_workboard ));
    }

    $t_buildings[$i]['isBuilding'] = ( $jobs_build[0][0] == "b_$b_db_name[$i]" ) ? 1 : 0 ;
    $t_buildings[$i]['isMarked'] = ( $jobs_build[1][0] == "b_$b_db_name[$i]" ) ? 1 : 0 ;
    $t_buildings[$i]['time_left'] = ( $jobs_build[0][0] == "b_$b_db_name[$i]" ) ? $jobs_build[0][1] : 0 ;
    $t_buildings[$i]['time_left_marked'] = ( $jobs_build[1][0] == "b_$b_db_name[$i]" ) ? $jobs_build[1][1] : 0 ;

    $temp_premises = ( $buildings_total[$b_premise[$i]] < $b_need[$i][$b_premise[$i]] ) ? 1 : 0 ;
    $t_buildings[$i]['isDisabled'] = $temp_premises;
    $t_buildings[$i]['isAffordable'] = ( $timefixed_depot->getIridium() >= $pay_iridium[$i] && $timefixed_depot->getHolzium() >= $pay_holzium[$i] ) ? 1 : 0 ;
    
  }
  

  ///////// ÄNDERUNG FÜR TUTORIAL !!!!!!! //////////
  
  	if($tut['tutorial'] == 7) {
  		if($t_buildings[0]['level_new'] < 5) 
	  			$t_buildings[0]['time'] = maketime(1);
  		
  		for( $i=1 ; $i<ANZAHL_GEBAEUDE ; $i++ ) {
	  		$t_buildings[$i]['isDisabled'] = "1";
	  		$t_buildings[$i]['title'] = "Bau zuerst 5 Stufen Iridium-Mine um mit dem Tutorial fortzufahren.";
	  	}
  	}
  	if($tut['tutorial'] == 8) {
  		// Technologiezentrum bauen
  		for($i=0;$i<ANZAHL_GEBAEUDE;$i++) {
  			if($i == 9 && $t_buildings[9]['level_new'] < 1) {
  				$t_buildings[$i]['time'] = maketime(1);
  			}else{
  				$t_buildings[$i]['isDisabled'] = "1";
  				$t_buildings[$i]['title'] = "Bau zuerst Technologiezentrum um mit dem Tutorial fortzufahren.";
  			}
  		}
  	}
  	
  ////////////////////////////////////////////////////	
  	

  $t_categories = array(
    0 => array(
      'heading' => $MESSAGES[MSG_WORK_BOARD]['m003'], // Ressourcen-Gebäude
      'buildings' => array_slice($t_buildings, 0, 4)
    ),
    1 => array(
      'heading' => $MESSAGES[MSG_WORK_BOARD]['m004'], // Depots
      'buildings' => array_slice($t_buildings, 4, 2)
    ),
    2 => array(
      'heading' => $MESSAGES[MSG_WORK_BOARD]['m005'], // Flugzeug-Gebäude
      'buildings' => array_slice($t_buildings, 6, 2)
    ),
    3 => array(
      'heading' => $MESSAGES[MSG_WORK_BOARD]['m006'], // Zentren
      'buildings' => array_slice($t_buildings, 8, 4)
    ),
    4 => array(
      'heading' => $MESSAGES[MSG_WORK_BOARD]['m007'], // Schutzschild
      'buildings' => array_slice($t_buildings, 12, 2)
    )
  );
  if($jobs_build)
  {	// Liste der bauaufträge erstellen
	for($i=0;$jobs_build[$i];$i++)	{
		$build_jobs[$i]['name'] = translate_buildings($jobs_build[$i][0]);
		$build_jobs[$i]['level'] = $jobs_build[$i][2];
		$build_jobs[$i]['time'] = $jobs_build[$i][1];
		$build_jobs[$i]['index'] = array_search($build_jobs[$i]['name'],$b_name);
		$build_jobs[$i]['time_left'] = $jobs_build[$i][1]-time();
		if($i==0) {
			$build_jobs[$i]['first'] = true;
		}
		else {
			$build_jobs[$i]['first'] = False;
		}
		$build_jobs[$i]['countdown'] = "countdown".$i;
	}
	$template->set('jobs',$build_jobs);
  }
  
  $template->set('categories', $t_categories);
  $jobs_build[0] ? $template->set('sthBuilding', 1 ) : $template->set('sthBuilding', 0 );
  $jobs_build[MAX_MARKS_BUILD] ? $template->set('sthMarked', 1 ) : $template->set('sthMarked', 0 );
  $home['home'] == 'YES' ? $template->set('isCapital', 1 ) : $template->set('isCapital', 0 );
  $template->set('showClue', ($buildings['points'] < 40) ? 1 : 0);
	if($tut['tutorial'] < 999) {
		$template->set('showClue', 0);
	}
  
  $js_code = "function ask(mode, hasMarking) {
    if(mode) // 0 = Vormerkung aufheben, 1 = Bau abbrechen
    {
      var text = 'Beim Abbrechen eines Gebäudebaus bekommst du maximal 80% der investierten Rohstoffe zurückerstattet (entsprechend der Zeit, die das Gebäude bereits gebaut hat).';
      if(hasMarking) { text += '\\nACHTUNG: Die Vormerkung wird automatisch aufgehoben, und davon werden KEINE Rohstoffe gutgeschrieben.'; }
      text += '\\n\\nBist du sicher, dass du den Gebäudebau abbrechen willst?';
    }
    else
    {
      var text = 'Achtung: Beim Aufheben einer Vormerkung werden auch alle folgenden Vormerkungen aufgehoben und keine Rohstoffe erstattet!\\n\\nBist du sicher, dass du die Vormerkung aufheben willst?';
    }
    return window.confirm(text);
  }
  ";

  for($i=0;$jobs_build[$i];$i++)
  {
    $diff = $jobs_build[$i][1]-time();
      $js_code .= "_initTimer('countdown".$i."',$diff,'fertig',false,'',true);";
  }

  $template->set('specificJS', $js_code);
  // end specific page logic

  // include common template settings
  require_once("include/JavaScriptCommon.php");
  require_once("include/TemplateSettingsCommon.php");

  // create html page
  try {
    echo $template->execute();
  }
  catch (Exception $e) { echo $e->getMessage(); }

  // save resource changes (ToDo: Is this necessary on every page?)
  $timefixed_depot->save();
?>
