<?php

    include("database.php");

    // define phptal template
    require_once("PHPTAL.php");
    require_once("include/PHPTAL_EtsTranslator.php");
    $template = new PHPTAL('guest/theme_blue_line_guest.html');
    $template->setTranslator(new PHPTAL_EtsTranslator());
    $template->setEncoding('ISO-8859-1');
    //$template->setForceReparse(true); // disable template cache
    $template->set('contentMacroName','stats_ets97.html/content');
    $template->set('pageTitle', 'Statistik von Runde 9.7 - Für eine Handvoll Iridium');

    // insert specific page logic here
    require_once("include/TemplateSettingsCommonGuest.php");

    $action = $_GET[action];
    switch ($action)
    {
      case "":

      case "users_donations" :
        if (file_exists('../stats/xml_export/ets97_all_donations.xml')) {
            $xml = simplexml_load_file('../stats/xml_export/ets97_all_donations.xml');
            $template->set('users_donations', $xml->xpath('/ETS9/donations_sort/user'));
            $template->set('donations', 'true');

        } else {
            $template->set('errorMessage', 'Konnte Datei nicht laden.');
        }
        break;

      case "cities" :
        if (file_exists('../stats/xml_export/ets97_top50_cities_points.xml')) {
            $xml = simplexml_load_file('../stats/xml_export/ets97_top50_cities_points.xml');
            $template->set('top50_city_points', $xml->xpath('/ETS9/city'));
            $template->set('cities', 'true');

        } else {
            $template->set('errorMessage', 'Konnte Datei nicht laden.');
        }
        break;

      case "statistics" :
        if (file_exists('../stats/xml_export/ets97_all_statistics.xml')) {
            $xml = simplexml_load_file('../stats/xml_export/ets97_all_statistics.xml');
            $template->set('common_statistics', $xml->xpath('/ETS9/statistics/stat'));
            $template->set('statistics', 'true');

        } else {
            $template->set('errorMessage', 'Konnte Datei nicht laden.');
        }
        break;

      case "expansions" :
        if (file_exists('../stats/xml_export/ets97_top10_expansion.xml')) {
            $xml = simplexml_load_file('../stats/xml_export/ets97_top10_expansion.xml');
            $template->set('top10_expansions', $xml->xpath('/ETS9/expansions/expansion'));
            $template->set('expansions', 'true');

        } else {
            $template->set('errorMessage', 'Konnte Datei nicht laden.');
        }
        break;

      case "users" :
        if (file_exists('../stats/xml_export/ets97_top50_users_points.xml')) {
            $xml = simplexml_load_file('../stats/xml_export/ets97_top50_users_points.xml');
            $template->set('top50_users_points', $xml->xpath('/ETS9/usarios'));
            $template->set('users', 'true');

        } else {
            $template->set('errorMessage', 'Konnte Datei nicht laden.');
        }
        break;

      case "users_power" :
        if (file_exists('../stats/xml_export/ets97_top50_users_power.xml')) {
            $xml = simplexml_load_file('../stats/xml_export/ets97_top50_users_power.xml');
            $template->set('top50_users_power', $xml->xpath('/ETS9/usarios'));
            $template->set('users_power', 'true');

        } else {
            $template->set('errorMessage', 'Konnte Datei nicht laden.');
        }
        break;

      case "users_fame" :
        if (file_exists('../stats/xml_export/ets97_top50_users_fame.xml')) {
            $xml = simplexml_load_file('../stats/xml_export/ets97_top50_users_fame.xml');
            $template->set('top50_users_fame', $xml->xpath('/ETS9/usarios'));
            $template->set('users_fame', 'true');

        } else {
            $template->set('errorMessage', 'Konnte Datei nicht laden.');
        }
        break;

      case "alliances" :
        if (file_exists('../stats/xml_export/ets97_top50_alliances_points.xml')) {
            $xml = simplexml_load_file('../stats/xml_export/ets97_top50_alliances_points.xml');
            $template->set('top50_alliance_points', $xml->xpath('/ETS9/alliances'));
            $template->set('alliances', 'true');

        } else {
            $template->set('errorMessage', 'Konnte Datei nicht laden.');
        }
        break;

      case "alliances_power" :
        if (file_exists('../stats/xml_export/ets97_top50_alliances_power.xml')) {
            $xml = simplexml_load_file('../stats/xml_export/ets97_top50_alliances_power.xml');
            $template->set('top50_alliance_power', $xml->xpath('/ETS9/alliances'));
            $template->set('alliances_power', 'true');

        } else {
            $template->set('errorMessage', 'Konnte Datei nicht laden.');
        }
        break;

      case "alliances_fame" :
        if (file_exists('../stats/xml_export/ets97_top50_alliances_fame.xml')) {
            $xml = simplexml_load_file('../stats/xml_export/ets97_top50_alliances_fame.xml');
            $template->set('top50_alliance_fame', $xml->xpath('/ETS9/alliances'));
            $template->set('alliances_fame', 'true');

        } else {
            $template->set('errorMessage', 'Konnte Datei nicht laden.');
        }
        break;
    }





  // create html page
  try {
    echo $template->execute();
  }
  catch (Exception $e) { echo $e->getMessage(); }
?>
