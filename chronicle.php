<?php
  $use_lib = 21; // MSG_CHRONICLE

  require_once("msgs.php");
  require_once("database.php");
  require_once("constants.php");
  require_once("functions.php");
  require_once("do_loop.php");
  require_once("include/class_Krieg.php");
  include("tutorial.php");

  require_once("PHPTAL.php");
  require_once("include/PHPTAL_EtsTranslator.php");
  $template = new PHPTAL( 'theme_blue_line.html' );
  $template->setTranslator(new PHPTAL_EtsTranslator());
  $template->setEncoding('ISO-8859-1');
  $template->set('contentMacroName','chronicle.html/content');
  // set page title
  $template->set('pageTitle', 'Übersichten - Chronik');

 // end specific page logic

  // include common template settings
  require_once("include/JavaScriptCommon.php");
  require_once("include/TemplateSettingsCommon.php");

  // save resource changes (ToDo: Is this necessary on every page?)
  $timefixed_depot->save();

  $show = (int)addslashes($_GET[show]);
  $_GET[show] = (int)addslashes($_GET[show]);

  $showLimit = 30;

  $_msg = array(
      'declare' => 'Kriegserklärung von %s',
      'decline' => 'Krieg abgelehnt von %s',
      'withdraw' => 'Kriegserklärung zurückgezogen von %s',
      'start' => 'Kriegsbeginn',
      'accept' => 'Kriegsbedingungen von beiden Seiten angenommen',
      'end' => 'Kriegsende',
      'xdeclare' => '%s fordert %s zum Krieg heraus.',
      'xdecline' => '%s lehnt Herausforderung von %s ab.',
      'xwithdraw' => '%s zieht Herausforderung von %s zurück.',
      'xstart' => 'Der Krieg zwischen %s und %s beginnt.',
      'xaccept' => '%s nimmt die Herausforderung von %s an.',
      'xend' => 'Der Krieg zwischen %s und %s ist beendet.',
      'surrender' => array('Kriegsende durch Kapitulation', 'Die Allianz %s hat euren Krieg durch Kapitulation beendet.'),
      'application' => array('Kriegsende durch Regelverstoß', 'Die Allianz %s hat gegen die Kriegsvereinbarung verstossen. Damit ist der Krieg beendet.'),
      'vacation' => array('Kriegsende durch Regelverstoß', 'Die Allianz %s hat gegen die Kriegsvereinbarung verstossen. Damit ist der Krieg beendet.'),
      'deletion' => array('Kriegsende durch Allianzlöschung', 'Die Allianz %s hat gegen die Kriegsvereinbarung verstossen. Damit ist der Krieg beendet.'),
      'rename' => array('Kriegsende durch Allianzumbenennung', 'Die Allianz %s hat gegen die Kriegsvereinbarung verstossen. Damit ist der Krieg beendet.'),
      'finish' => array('Der Krieg ist beendet', 'Der aktuelle Krieg ist beendet.'),
      'finish_colo' => array('Der Krieg ist beendet', 'Der aktuelle Krieg ist beendet. Es wurden mehr Kolonien verloren, als die Kriegsbedingungen zulassen.'),
      'finish_memb' => array('Der Krieg ist beendet', 'Der aktuelle Krieg ist beendet. Es wurden mehr Mitglieder verloren, als die Kriegsbedingungen zulassen.'),
  	  'asteroid' => 'Retter von Erde II bei Asteroidenbedrohung',
  	  'artefakt' => 'Erster Pionier auf Erde II nach Entdeckung eines Artefakts:',
  	  'koth' => 'Mächtigster Eroberer auf Erde II nach erster Einnahme des Artefakts:',
  );


  $chronicle_scroll = array();
  if (!$show)
    $show = 0;
  $get_chronicle_entries = sql_query("SELECT *, from_unixtime(time, '%d.%m.%Y %H:%i:%s') AS date FROM chronicle WHERE approved='Y' ORDER BY time DESC LIMIT $show,$showLimit");
  $j = -1;
  // array of loaded wars
  $wars = array();
  while ($chronicle_entry = sql_fetch_array($get_chronicle_entries))
  {
    $j++;
    // show only if approved by censor
//    if ($chronicle_entry['approved'] != 'Y')
//      continue;
    $event_time = $chronicle_entry['date'] . " Uhr";
    if ($chronicle_entry['occasion'] == 'text')
      $chronicle_scroll[$j] = array('event_text'=>$chronicle_entry['arbitrary_text'], 'event_add_text'=>'', 'event_time'=>$event_time, 'marker'=>'');
    else
    {
      $war_id = $chronicle_entry['war_id'];
      // only load if not already loaded; store loaded wars
      if (!array_key_exists("$war_id", $wars)) {
        $wars["$war_id"] = new Krieg($chronicle_entry['causer']);
        //XXX is that necessary? or loaded already implicitly at creation?
        $wars["$war_id"]->load($war_id);
      }
      $i = 0;
      $defenders_text = '';
      foreach ($wars["$war_id"]->getDefenders() as $defender)
        $defenders_text .= ($i++ > 0 ? ', ' : '') . $defender;
      $i = 0;
      $attackers_text = '';
      foreach ($wars["$war_id"]->getAttackers() as $attacker)
        $attackers_text .= ($i++ > 0 ? ', ' : '') . $attacker;
      $marker = ((($chronicle_entry['occasion'] == 'start') ? 'x' : (($chronicle_entry['occasion'] == 'end') ? '-' : '')));
      $event_text = sprintf($_msg[$chronicle_entry[occasion]], $chronicle_entry['causer']);
      $event_add_text = "<a href=\"war_status.php?id=$war_id\" >" . $attackers_text . ' gegen ' . $defenders_text . "</a>";
      if($chronicle_entry[occasion] == "asteroid") 
      		$event_add_text = "Spieler $chronicle_entry[causer] hat den Asteroideneinschlag verhindert.";
      if($chronicle_entry[occasion] == "artefakt")
      		$event_add_text = "Spieler $chronicle_entry[causer] hat als erster Pionier das Artefakt eingenommen.";
      if($chronicle_entry[occasion] == "koth")
      		$event_add_text = "Spieler $chronicle_entry[causer] hat als längster das Artefakt verteidigen können.";
      $chronicle_scroll[$j] = array('event_text'=>$event_text, 'event_add_text'=>$event_add_text, 'event_time'=>$event_time, 'marker'=>$marker);
    }
  }

  $get_num_entries = sql_query("SELECT count(*) AS number FROM chronicle WHERE approved='Y'");
  list( $num_entries ) = sql_fetch_row($get_num_entries);
  // show next if not first page - number-show>showlimit?
  if ($num_entries - $_GET[show] > $showLimit)
  {
    $template->set('show_previous', 'true');
    $template->set('previous_entry', $_GET[show] + $showLimit);
  }
  if ($_GET[show] + $showLimit < $num_entries)
  {
    $template->set('show_first', 'true');
    $template->set('first_entry', ((int)(($num_entries - 1) / $showLimit)) * $showLimit);
  }

  $template->set('self', $_SERVER['PHP_SELF']);
  $template->set('chronicle_scroll', $chronicle_scroll);

  // create html page
  try {
    echo $template->execute();
  }
  catch (Exception $e) { echo $e->getMessage(); }

?>
