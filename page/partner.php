<?php
  require_once("database.php");

 // define phptal template
  require_once("PHPTAL.php");
  require_once("include/PHPTAL_EtsTranslator.php");
  //$template = new PHPTAL('guest/impressum.html');
  $template = new PHPTAL('guest/standardGuest.html');
  $template->setTranslator(new PHPTAL_EtsTranslator());
  $template->setEncoding('ISO-8859-1');

require_once("include/TemplateSettingsCommonGuest.php");

  // set page title
  $template->set('pageTitle', 'Partner und Presse');

  $pfuschOutput = "";

  $pfuschOutput .=  "  <h1>Partner und Presse</h1>
<br><br>
<b>Interne Neuigkeiten</b>
<br><br>
<table border=0>
<tr><td>
	<a href='http://ets-blog.de.vu/' target='_blank'>ETS Blog</a>
</td><td>
	<a href='http://forum.escape-to-space.de/wiki' target='_blank'>ETS Wiki</a>
</td></tr>
</table>
<br><br>
<b>Artikel &uuml;ber Escape-to-Space</b>
<br><br>
<table border=0>
<tr><td>
		<a href='http://www.gamesbasis.com/blog/browsergame-klassiker-escape-to-space.html' target='_blank'>Browsergame-Klassiker Escape-to-Space</a>
</td><td>

</td></tr>
</table>
<b>Partner von Escape-to-Space</b>
<br><br>
<table border=0>
<tr><td>
		<a href='http://www.arnayo.de/' target='_blank'>http://www.arnayo.de</a>
</td><td>
		<a href='http://www.suchefix.de' target='_blank'>http://www.suchefix.de</a>
</td></tr>
<tr><td>
		<a href='http://www.browsergame-magazin.de/escape-to-space/' target='_blank'>http://www.browsergame-magazin.de</a>
</td><td>
		<a href='http://spiele.seekxl.de/escape-to-space/' target='_blank'>http://spiele.seekxl.de</a>
</td></tr>
<tr><td>
		<a href='http://onlinestreet.de/336326-escape-to-space' target='_blank'>http://onlinestreet.de</a>
</td><td>
		<a href='http://www.de-linkliste.de/webkatalog/webkatalog.php?id=36846&key=Browsergame-kostenlos-Strategiespiel' target='_blank'>http://www.de-linkliste.de</a>
</td></tr>
<tr><td>
		<a href='http://www.browsergamemag.de/viewpage.php?page_id=8&gameID=386' target='_blank'>http://www.browsergamemag.de</a>
</td><td>
		<a href='http://kostenlose-browsergames-liste.de/' target='_blank'><img src='http://kostenlose-browsergames-liste.de/button.php?u=Daxl' alt='Kostenlose Browsergames Liste' border='0'></a>
		<a href='http://www.bgliste.de/' target='_blank'><img src='http://www.bgliste.de/button.php?u=Daxl' alt='BGListe -Die Browsergames Liste' border='0'></a>
</td></tr>
</table>";

  // add pfusch output
  $template->set('pfuschOutput', $pfuschOutput);

  // create html page
  try {
    echo $template->execute();
  }
  catch (Exception $e) { echo $e->getMessage(); }

?>