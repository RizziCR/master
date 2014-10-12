<?php
  require("database.php");
 // define phptal template
  require_once("PHPTAL.php");
  require_once("include/PHPTAL_EtsTranslator.php");
  //$template = new PHPTAL('guest/support.html');
  $template = new PHPTAL('guest/standardGuest.html');
  $template->setTranslator(new PHPTAL_EtsTranslator());
  $template->setEncoding('ISO-8859-1');

require_once("include/TemplateSettingsCommonGuest.php");

  // set page title
  $template->set('pageTitle', 'Spielbetreuung');

  $pfuschOutput = "";

  $pfuschOutput .= "  <h1>Spielbetreuung</h1>
          Spielbetreuung erhältst du:
          <ol>
            <li>Im Bereich <a href=\"$etsAddress/page/faqs.php\">Fragen zu ETS</a> findest du Informationen zu allem was im Spiel
            wichtig ist. Wenn Fragen offen bleiben, findest du sicher unter einem der anderen hier aufgeführten Punkte eine Antwort.</li>

            <li>Im <a href=\"$forumAddress\" target=_blank>Welt-Forum</a> findest du umfangreiche Infos zu den meisten Dingen die
            das Spiel betreffen. Es gibt die Möglichkeit <a href=\"$forumAddress/viewforum.php?f=24\" target=_blank>Fragen</a> zum
            Spiel zu stellen. Außerdem gibt es auch einen Bereich in dem du <a href=\"$forumAddress/viewforum.php?f=26\" target=_blank
            >Fehler</a> melden kannst. Kritische Fehler, die man in deinen Augen zu seinem eigenen Vorteil ausnutzen kann, melde bitte
            direkt an die Spielbetreuer (Siehe 4.) damit sich diese nicht verbreiten. Einfach mal die Suchfunktion benutzen.</li>

            <li>Im <a href=\"$etsAddress/page/chat.php\">Hilfe-Chat</a> findest du kompetente Helfer. Diese geben neuen Spielern
            Hilfestellungen für einen guten Start in unser Spiel, beantworten aber auch Fragen zum Spiel. Manchmal verraten sie
            auch ganz gute Strategien, die dich im Spiel voranbringen.</li>

            <li>Die Spielbetreuer sind über ".getEmailLink($supportEmail)." zu erreichen. Die Mitarbeiter stehen für die übrigen Anfragen,
            die durch die obigen Punkte nicht nicht abgedeckt werden können, zur Verfügung.</li>

            <li>Solltest du den Verdacht auf eine Mehrfachregistrierung (Multi-Account) haben, so schildere deine Vermutungen bitte
            möglichst genau an folgende Mail-Adresse ".getEmailLink($multiEmail).". Diese werden dann vorrangig von unseren Spielbetreuern
            bearbeitet um die Chancengleichheit im Spiel zu wahren.</li>
          </ol>";

  // add pfusch output
  $template->set('pfuschOutput', $pfuschOutput);

  // create html page
  try {
    echo $template->execute();
  }
  catch (Exception $e) { echo $e->getMessage(); }
?>
