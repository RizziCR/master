<?php
  $use_lib = 21; // MSG_CHRONICLE

require_once("msgs.php");
require_once("database.php");
require_once("constants.php");
require_once("functions.php");
require_once("do_loop.php");
  require_once("include/class_Krieg.php");

// define phptal template
require_once("PHPTAL.php");
require_once("include/PHPTAL_EtsTranslator.php");
  $template = new PHPTAL( 'theme_blue_line.html' );
$template->setTranslator(new PHPTAL_EtsTranslator());
$template->setEncoding('ISO-8859-1');
  $template->set('contentMacroName','tools/censoring.html/content');
// set page title
$template->set('pageTitle', 'Administration - Zensur');

// include common template settings
require_once("include/JavaScriptCommon.php");
require_once("include/TemplateSettingsCommon.php");


if(($acl == 'ADMIN') || ($acl == 'SUPPORT') || ($acl == 'CENSOR')) {
  $show_refused = false;
  $show_open = true;
  $show_approved = false;
  $censoring_condition = "where approved = 'X' ";
  if($_POST[show]) {
    $show_refused = $_POST[refused];
    $show_open = $_POST[open];
    $show_approved = $_POST[approved];
    if ($show_refused && $show_open && $show_approved)
      $censoring_condition = "";
    else {
      $censoring_condition = "where ";
      $first = true;
      if ($show_refused) {
        $censoring_condition .= ($first ? "" : " OR ") . "approved = 'N'";
        $first = false;
      }
      if ($show_open) {
        $censoring_condition .= ($first ? "" : " OR ") . "approved = 'X'";
        $first = false;
      }
      if ($show_approved) {
        $censoring_condition .= ($first ? "" : " OR ") . "approved = 'Y'";
        $first = false;
      }
    }
  }
  else if($_POST[action]) {
    // read values of radios and update sql
    foreach(array(array('approved', 'Y'), array('open', 'X'), array('refused', 'N')) as $state) {
      $entry_ids = array_keys($_POST['censored'], $state[0]);
      if (count($entry_ids) > 0) {
        $ids = "";
        $first = true;
        foreach ($entry_ids as $id) {
          if ($first)
            $first = false;
          else
            $ids .= " OR ";
          $ids .= "id='$id'";
        }
        sql_query("UPDATE chronicle SET approved='" . $state[1] . "' where $ids");
      }
    }
  }
  $template->set('admin', true);
  $show = (int)addslashes($_GET[show]);
  $_GET[show] = (int)addslashes($_GET[show]);

  $template->set('show_refused', $show_refused);
  $template->set('show_open', $show_open);
  $template->set('show_approved', $show_approved);

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
      'finish_colo' => array('Der Krieg ist beendet', 'Der aktuelle Krieg ist beendet. Es wurden mehr Kolonien verloren, als die Kriegsbdingungen zulassen.'),
      'finish_memb' => array('Der Krieg ist beendet', 'Der aktuelle Krieg ist beendet. Es wurden mehr Mitglieder verloren, als die Kriegsbdingungen zulassen.'),
  );


  $chronicle_scroll = array();
  if (!$show)
    $show = 0;
  $get_chronicle_entries = sql_query("SELECT *, from_unixtime(time, '%d.%m.%Y %H:%i:%s') AS date FROM chronicle $censoring_condition ORDER BY time DESC LIMIT $show,$showLimit");
  $j = -1;
  // array of loaded wars
  $wars = array();
  while ($chronicle_entry = sql_fetch_array($get_chronicle_entries))
  {
    $j++;
    //XXX show only if approved by censor
    //if ($chronicle_entry['approved'] != 'Y')
    //  continue;
    $event_time = $chronicle_entry['date'] . " Uhr";
    if ($chronicle_entry['occasion'] == 'text')
      $chronicle_scroll[$j] = array('event_text'=>$chronicle_entry['arbitrary_text'], 'event_add_text'=>'', 'event_time'=>$event_time, 'marker'=>'', 'censored'=>$chronicle_entry['approved'], 'id'=>$chronicle_entry['id']);
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
      $event_add_text = "<a href=\"$etsAddress/war_status.php?id=$war_id\" >" . $attackers_text . ' gegen ' . $defenders_text . "</a>";
      $chronicle_scroll[$j] = array('event_text'=>$event_text, 'event_add_text'=>$event_add_text, 'event_time'=>$event_time, 'marker'=>$marker, 'censored'=>$chronicle_entry['approved'], 'id'=>$chronicle_entry['id']);
    }
  }

  $get_num_entries = sql_query("SELECT count(*) AS number FROM chronicle $censoring_condition");
  list( $num_entries ) = sql_fetch_row($get_num_entries);
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

}

try {
    echo $template->execute();
}
catch (Exception $e) { echo $e->getMessage(); }

?>
