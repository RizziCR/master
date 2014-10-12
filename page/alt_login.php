<?php
  require_once("database.php");
  
 // define phptal template
  require_once("PHPTAL.php");
  require_once("include/PHPTAL_EtsTranslator.php");
  //$template = new PHPTAL('guest/alt_login.html');
  $template = new PHPTAL('guest/standardGuest.html');
  $template->setTranslator(new PHPTAL_EtsTranslator());
  $template->setEncoding('ISO-8859-1');
  
require_once("include/TemplateSettingsCommonGuest.php");
  
  // set page title
  $template->set('pageTitle', 'Alternatives Login');  

  $pfuschOutput = "";  
  


	if ($_GET[user]) {
	    $sql = "UPDATE userdata SET noipchk='Y' WHERE user='".addslashes($_GET[user])."'";
		sql_query($sql);
	}
	  $pfuschOutput .=  "
<h1>Alternativer Zugang</h1>

<p>Der alternative Zugang wurde für dich freigegeben.<br />
Versuche nun <a href=\"./login.php\">Erde II zu betreten</a>.<br />
<br />
Solltest du Schwierigkeiten haben, wende dich bitte an unsere Spielbetreuer.</p>";

  // add pfusch output
  $template->set('pfuschOutput', $pfuschOutput);  
  
  // create html page
  try {
    echo $template->execute();
  }
  catch (Exception $e) { echo $e->getMessage(); }
?>