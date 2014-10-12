<?php
  $use_lib = 22; // MSG_WAR_STATUS

  require_once("msgs.php");
  require_once("database.php");
  require_once("constants.php");
  require_once("functions.php");
  require_once("do_loop.php");
  require_once("include/class_Krieg.php");

  require_once("PHPTAL.php");
  require_once("include/PHPTAL_EtsTranslator.php");
  $template = new PHPTAL( 'theme_blue_line.html' );
  $template->setTranslator(new PHPTAL_EtsTranslator());
  $template->setEncoding('ISO-8859-1');
  $template->set('contentMacroName','war_status.html/content');
  // set page title
  $template->set('pageTitle', 'Stand - Krieg');


 // end specific page logic

  // include common template settings
  require_once("include/JavaScriptCommon.php");
  require_once("include/TemplateSettingsCommon.php");

  // save resource changes (ToDo: Is this necessary on every page?)
  $timefixed_depot->save();

  try {

  if (!isset($_GET[id]))
        ErrorMessageException(MSG_WAR_STATUS, 'no_id');

  $war_id = (int)addslashes($_GET[id]);
  if ($war_id < 0)
        ErrorMessageException(MSG_WAR_STATUS, 'invalid_id');

  $war_events = array();
  $get_chronicle_entries = sql_query("SELECT *, from_unixtime(time, '%d.%m.%Y %H:%i') AS date FROM chronicle where war_id='$war_id'");
//  $log = fopen("/tmp/war_status.log", "w");
//    fwrite($log, "war: $war_id\n");
  while ($chronicle_entry = sql_fetch_array($get_chronicle_entries))
  {
//    fwrite($log, "occasion: " . $chronicle_entry['occasion'] . "\n");
    $war_events["" . $chronicle_entry['occasion']] = $chronicle_entry;
  }
//  fclose($log);

  if (!array_key_exists('declare', $war_events))
        ErrorMessageException(MSG_WAR_STATUS, 'no_such_war');

  // show only events approved by censor
  if ($war_events['declare']['approved'] != 'Y')
        ErrorMessageException(MSG_WAR_STATUS, 'censored');

  $war = new Krieg($war_events['declare']['causer']);
  $war->load($war_id);

  $i = 0;
  $defenders_text = '';
  foreach ($war->getDefenders() as $defender)
    $defenders_text .= ($i++ > 0 ? ', ' : '') . $defender;
  $i = 0;
  $attackers_text = '';
  foreach ($war->getAttackers() as $attacker)
    $attackers_text .= ($i++ > 0 ? ', ' : '') . $attacker;

  if (array_key_exists('end', $war_events))
  {
    $get_wars_entries = sql_query("SELECT winner FROM wars where id='$war_id'");
    list( $winner ) = sql_fetch_row($get_wars_entries);
    if ($winner != 'N') {
      switch ($war_events['end']['victory'])
      {
      case 'timeout':
        $victoryBy = "geringere Verluste nach Ablauf der Zeit";
        break;
      case 'colonies':
        $victoryBy = "Kolonieverlust";
        break;
      case 'loss':
        $victoryBy = "Ausfall von Mitgliedern";
        break;
      case 'join':
        $victoryBy = "Aufnahme von Mitgliedern";
        break;
      case 'vacation':
        $victoryBy = "Urlaub von Mitgliedern";
        break;
      case 'leave':
        $victoryBy = "Verlust von Mitgliedern";
        break;
      case 'breach':
        $victoryBy = "Bruch der Waffenruhe";
        break;
      case 'surrender':
        $victoryBy = "Kapitulation";
        break;
      case 'disband':
        $victoryBy = "Auflösung";
        break;
        // should never happen because of preceding checks
      default:
        $victoryBy = "Kein Sieg";
      }
    }
  }
  else
  {
    $get_wars_entries = sql_query("SELECT from_unixtime(end, '%d.%m.%Y %H:%i') AS date FROM wars where id='$war_id'");
    list( $expected_end ) = sql_fetch_row($get_wars_entries);
  }

  $template->set('adversaries', $attackers_text . ' gegen ' . $defenders_text);
  $template->set('war_causer', $war_events['declare']['causer']);
  $template->set('war_defenders', $defenders_text);
  $template->set('declare_date', $war_events['declare']['date']);
  $template->set('war_defender', $war_events['accept']['causer']);
  $template->set('accept_date', $war_events['accept']['date']);
  $template->set('start_date', $war_events['start']['date']);
  $template->set('end_date', $war_events['end']['date']);
  $template->set('expected_end_date', $expected_end);
  $template->set('victory_cause', $victoryBy);
  $template->set('winner', ($winner == 'A') ? $attackers_text : ($winner == 'B' ? $defenders_text : ''));
//  $template->set('', );
  $template->set('self', $_SERVER['PHP_SELF']);

  }
  catch(Exception $e) {
    $errorMessage =
      "  <h1>{$MESSAGES[MSG_WAR_STATUS][title]}</h1>" .
      "<ul>\n    <li>" . $e->getMessage() . "</li>\n</ul>";

    // add error output
    $template->set('errorMessage', $errorMessage);
  }
    
  // create html page
  try {
    echo $template->execute();
  }
  catch (Exception $e) { echo $e->getMessage(); }

?>
