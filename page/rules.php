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
  $template->set('pageTitle', 'Spielregeln');

  $pfuschOutput = "";

  $pfuschOutput .= "  <h1>Spielregeln</h1>
          <p>Jedes Spiel hat Regeln. Noch sind die Regeln für Escapte To Space
          lediglich unseren <a href=\"$etsAddress/page/agb.php\">Allgemeinen
          Geschäftsbedingungen (AGB)</a> entnehmbar. Jedoch planen wir eine
          vom Rechtsdeutsch befreite Variante der AGB. In dieser wollen wir
          an Beispielen deutlich machen, was nicht gern gesehen wird und was
          dir als Spieler sogar verboten ist, um möglichst allen den
          Spielspass zu erhalten.</p>";

  // add pfusch output
  $template->set('pfuschOutput', $pfuschOutput);

  // create html page
  try {
    echo $template->execute();
  }
  catch (Exception $e) { echo $e->getMessage(); }
?>
