<?php
    require_once("database.php");

    // define phptal template
    require_once("PHPTAL.php");
    require_once("include/PHPTAL_EtsTranslator.php");
    $template = new PHPTAL('guest/dataSecurity.html');
    $template->setTranslator(new PHPTAL_EtsTranslator());
    $template->setEncoding('ISO-8859-1');

    // set page title
    $template->set('pageTitle', 'Datenschutzbestimmungen');

    session_start();

    // include common template settings
    require_once("include/TemplateSettingsCommonGuest.php");

    // determine side area
    $template->set('showGuest','true');

    // create html page
    try {
        echo $template->execute();
    }
    catch (Exception $e) { echo $e->getMessage(); }
?>
