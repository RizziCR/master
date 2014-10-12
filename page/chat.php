<?php
  require_once("database.php");

  // define phptal template
  require_once("PHPTAL.php");
  require_once("include/PHPTAL_EtsTranslator.php");
  $template = new PHPTAL('guest/chat.html');
  $template->setTranslator(new PHPTAL_EtsTranslator());
  $template->setEncoding('ISO-8859-1');

  require_once("include/TemplateSettingsCommonGuest.php");

  if(!empty($_SESSION[user])) {
  	$user = sql_fetch_array( sql_query ( "SELECT user FROM userdata WHERE ID = '$_SESSION[user]'"));
      $template->set('username', $user['user']);
  }else{
      $template->set('username', 'Anonymous');
  }
  
  // set page title
  $template->set('pageTitle', 'Kommunikation - Chat');

  // create html page
  try {
    echo $template->execute();
  }
  catch (Exception $e) { echo $e->getMessage(); }
?>