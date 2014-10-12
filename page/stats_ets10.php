<?php

    include("database.php");

    // define phptal template
    require_once("PHPTAL.php");
    require_once("include/PHPTAL_EtsTranslator.php");

    $db = 'ETS10';
    $revision = '10';
    $revisionString = '10';
    $revisionName = 'Eine Welt am Scheideweg';

    $template = new PHPTAL('guest/theme_blue_line_guest.html');
    $template->setTranslator(new PHPTAL_EtsTranslator());
    $template->setEncoding('ISO-8859-1');
    //$template->setForceReparse(true); // disable template cache
    $template->set('contentMacroName','stats_ets'.$revision.'.html/content');
    $template->set('pageTitle', 'Statistik von Runde '.$revisionString.' - '.$revisionName);
    $template->set('revNo', $revisionString);
    $template->set('revName', $revisionName);

    // insert specific page logic here
    require_once("include/TemplateSettingsCommonGuest.php");

    function extractXMLData($template, $action, $xpath, $db, $revision) {
        $fileName = '../stats/xml_export/ets'.$revision.'_'.$action.'.xml';
        if (file_exists($fileName)) {
            $xml = simplexml_load_file($fileName);
            $template->set($action.'_data', $xml->xpath('/'.$db.'/'.$xpath));
            $template->set($action, 'true');

        } else {
            $template->set('errorMessage', 'Konnte Datei nicht laden: '.$fileName);
        }
    }

    $action = $_GET[action];
    switch ($action)
    {
      case '':
        $action = 'donations';
      case 'donations' :
        extractXMLData($template, $action, 'donations/user', $db, $revision);
        break;
      case "cities" :
        extractXMLData($template, $action, 'city', $db, $revision);
        break;
      case "statistics" :
        extractXMLData($template, $action, 'statistics/stat', $db, $revision);
        break;
      case "upgradings" :
        extractXMLData($template, $action, 'expansions/expansion', $db, $revision);
        break;
      case "users_score" :
        extractXMLData($template, $action, 'usarios', $db, $revision);
        break;
      case "users_power" :
        extractXMLData($template, $action, 'usarios', $db, $revision);
        break;
      case "users_fame" :
        extractXMLData($template, $action, 'usarios', $db, $revision);
        break;
      case "alliances_score" :
        extractXMLData($template, $action, 'alliances', $db, $revision);
        break;
      case "alliances_power" :
        extractXMLData($template, $action, 'alliances', $db, $revision);
        break;
      case "alliances_fame" :
        extractXMLData($template, $action, 'alliances', $db, $revision);
        break;
    }


  // create html page
  try {
    echo $template->execute();
  }
  catch (Exception $e) { echo $e->getMessage(); }
?>
