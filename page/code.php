<?php
  require_once("database.php");
  
 // define phptal template
  require_once("PHPTAL.php");
  require_once("include/PHPTAL_EtsTranslator.php");
  //$template = new PHPTAL('guest/code.html');
  $template = new PHPTAL('guest/standardGuest.html');  
  $template->setTranslator(new PHPTAL_EtsTranslator());
  $template->setEncoding('ISO-8859-1');
  
require_once("include/TemplateSettingsCommonGuest.php");
  
  // set page title
  $template->set('pageTitle', 'Freischaltcode nicht erhalten?');  

  $pfuschOutput = "";  
  
  $pfuschOutput .= "	<h1>Freischaltcode nicht erhalten?</h1>

			$SUC_MSG<font class=error>$ERR_MSG</font><br><br>

			<table align=center border=0 cellpadding=3 cellspacing=3>
			<tr valign=top>
				<td colspan=2>
					Du hast keinen Freischaltcode erhalten? Dies ist in der Regel auf Tippfehler in der E-Mail-Adresse zur&uuml;ckzuf&uuml;hren. Aufgrund des Missbrauchs der Code-Anforderungs-Funktion, wurde diese abgeschalten. Nicht freigeschaltete Benutzerkonten werden nach 48 Stunden gel&ouml;scht, somit kannst du entweder abwarten oder einen anderen Spielernamen w&auml;hlen. Alternativ kannst du auch eine Anfrage an unsere Spielbetreuer senden.
				</td>
			</tr>
			</table>";

  // add pfusch output
  $template->set('pfuschOutput', $pfuschOutput);  
  
  // create html page
  try {
    echo $template->execute();
  }
  catch (Exception $e) { echo $e->getMessage(); }
?>