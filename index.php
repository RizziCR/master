<?php
  require_once("config_general.php");
  require_once("database.php");

// define phptal template
  require_once("PHPTAL.php");
  require_once("include/PHPTAL_EtsTranslator.php");
  $template = new PHPTAL('guest/theme_blue_line_guest.html');
  $template->setTranslator(new PHPTAL_EtsTranslator());
  $template->setEncoding('ISO-8859-1');
  $template->set('contentMacroName','guest/index.html/content');

  require_once("include/TemplateSettingsCommonGuest.php");

  // set page title
 $template->set('pageTitle',           'Kostenloses Strategie- und Onlinespiel');
//  $template->set('descriptionContent',  'ETS ist ein kostenloses Browserspiel, in welchem der Spieler alleine oder zusammen in einer Allianz um die Herrschaft auf Erde II kämpft. Sei es mit Kampfschiffen oder Strategien.');
//  $template->set('keywordsContent',     'ETS, kostenlos, werbefrei, Browser, Game, Spiel, Browserspiel, Browsergame, Internet, Gemeinschaft, Strategie, Escape, Space, Allianz, Herrschaft, Flugzeuge');

  // create html page
  try {
    echo $template->execute();
  }
  catch (Exception $e) { echo $e->getMessage(); }
?>
