<?php
  require_once("database.php");
 // define phptal template
  require_once("PHPTAL.php");
  require_once("include/PHPTAL_EtsTranslator.php");

  //$template = new PHPTAL('guest/tour.html');
  $template = new PHPTAL('guest/standardGuest.html');
  $template->setTranslator(new PHPTAL_EtsTranslator());
  $template->setEncoding('ISO-8859-1');

require_once("include/TemplateSettingsCommonGuest.php");

  // set page title
  $template->set('pageTitle', 'Warum dieses Onlinespiel?');

  $pfuschOutput = "";

  $pfuschOutput .= " <h1>Warum dieses Spiel?</h1>

      <table align=center border=0 cellspacing=0 cellpadding=0>
      <tr>
        <td align=center>
          <b>Surfst du gern durchs Web?<br><br>
          Spielst du mit Begeisterung Spiele?<br><br>
          Unternimmst du gern etwas mit deinen Freunden?<br><br>
          Suchst du manchmal Ablenkung vom grauen Alltag?<br><br>
          Stellst du dich gern neuen Herausforderungen?</b><br><br><br>
        </td>
      </tr>
      <tr>
        <td align=left>
          Wenn du mindestens eine Frage mit Ja beantworten kannst, bist
          du hier <b>genau richtig</b>. Escape To Space bietet im Gegensatz
          zu den herkömmlichen Spielen ein völlig neues Spielgefühl. Baller
          nicht mehr nur stupide Computergegner ab oder baue allein
          vor dich hin ...
        </td>
      </tr>
      <tr>
        <td align=right>
          <i>... langweile dich nicht mehr nach wenigen Stunden!</i><br><br><br>
        </td>
      </tr>
      <tr>
        <td align=left>
          Escape To Space gibt dir die einmalige Möglichkeit dich mit
          Tausenden anderen Begeisterten zu messen. Da keinerlei Computer
          mitspielen, sondern nur gleichgesinnte Menschen, ist eine
          ungeahnte Spieldynamik garantiert...
        </td>
      </tr>
      <tr>
        <td align=right>
          <i>...eine riesige Community wartet auf dich!</i><br><br><br>
        </td>
      </tr>
      <tr>
        <td align=left>
          Und das Beste: Du entscheidest, ob du in die Rolle des friedliebenden
          Händlers schlüpfst oder in die, des kriegerischen Eroberers. Egal,
          ob du nur 10 Minuten am Tag investieren willst oder mit vollem
          Einsatz dabei bist...
        </td>
      </tr>
      <tr>
        <td align=right>
          <i>...ETS bietet für jeden genau die richtige Mischung an Spiel,
          Spaß und Spannung!</i><br><br><br>
        </td>
      </tr>
      <tr>
        <td align=left>
          Noch mehr Freude macht es, wenn all deine Freunde mitspielen - der
          gemeinsame Weg durch das Spiel verbindet, denn bei ETS werden
          Begriffe, wie Teamplay, Kommunikation und Interaktion groß
          geschrieben ...
        </td>
      </tr>
      <tr>
        <td align=right>
          <i>... lerne außerdem viele neue Freunde kennen!</i><br><br><br>
        </td>
      </tr>
      <tr>
        <td align=left>
          ETS ist kostenfrei und via Internet überall, 24 Stunden am Tag
          erreichbar. Es stellt keine hohen Anforderungen an den eigenen
          Computer ...
        </td>
      </tr>
      <tr>
        <td align=right>
          <i>... es wird lediglich ein Browser benötigt!</i><br><br><br>
        </td>
      </tr>
      <tr>
        <td align=left>
          Und wo ist der Haken? Es gibt keinen!
        </td>
      </tr>
      <tr>
        <td align=right>
          <i>... keine lästigen Popups, Werbemails, Zwangsklicks oder sonstiger
          Werbemüll!</i><br><br><br><br>
        </td>
      </tr>
      <tr>
        <td align=center>
          <b>Überzeugt?</b><br>
          <a href=\"./register.php\">Dann melde dich schnell und unkompliziert hier an.</a><br><br>
        </td>
      </tr>
      <tr>
        <td align=center>
          <b>Lies auch, wie alles begann</b><br>
          <a href=\"./ets.php\">Die einleitende Spielgeschichte.</a><br><br>
        </td>
      </tr>
      <tr>
        <td align=center>
          <b>Immernoch offene Fragen?</b><br>
          Hilfe findest du in den <a href=\"./faqs.php\">Fragen zu ETS</a>, dem <a target=_blank href=\"$forumAddress/\">Forum</a> oder per E-Mail an ".getEmailLink($supportEmail).".
        </td>
      </tr>
      </table>";

 // end specific page logic


  // add pfusch output
  $template->set('pfuschOutput', $pfuschOutput);

  // create html page
  try {
    echo $template->execute();
  }
  catch (Exception $e) { echo $e->getMessage(); }
?>
