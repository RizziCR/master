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
  $template->set('pageTitle', 'Impressum');

  $pfuschOutput = "";

  $pfuschOutput .=  "  <h1>Impressum</h1>

      <table align=center border=0 cellpadding=3 cellspacing=3>
      <tr valign=top>
        <td align=left width=\"30%\">
          Betreiber
        </td>
        <td align=left width=\"70%\">
          Jens Bollmann<br>
          Brauenkamper Straße 47<br>
		  27753 Delmenhorst
        </td>
      </tr>
      <tr valign=top>
        <td align=left>
          Kontakt
        </td>
        <td align=left>
          E-Mail ".getEmailLink($etsEmail)."
        </td>
      </tr>
      <tr valign=top>
        <td align=left>
          Spielbetreuung
        </td>
        <td align=left>
          siehe <a href=\"./support.php\">Informationsseite</a> der Spielbetreuung
        </td>
      </tr>

      <tr valign=top>
        <td align=left>
          Copyright-Information
        </td>
        <td align=left>
          Die Rechte an der Spielidee und dem Namen \"Escape-To-Space\" liegen bei Thomas Weichert.
		  Im Spiel verwendete Bilder sind von <a href='http://commons.wikimedia.org'>http://commons.wikimedia.org</a> und sind unter der <a href='http://creativecommons.org/licenses/by/2.0/deed.de'>Creative Commons-Lizenz Namensnennung 2.0</a> lizenziert..<br>
		  Vielen Dank folgenden Wikimedia Nutzern:<br>
		  <div align='center'>
		  Thmsfrst - Ance - Markus G. Kl&ouml;tzer - Idaho National Laboratory - Erwin Lindemann - 
		  Felix K&ouml;nig - USN - Cecil - Frédéric VINEE - Reise Reise - mailer_diablo -
		  Christoph F. Siekermann - Funkdoctor - Radafaz - High Contrast - Telemaque MySon -
		  Lucas Taylor - Arpad Horvath - NASA - Anagoria - Unites States Department of Transportation -
		  Karl-Heinz Wolf - Stadtentw&auml;sserung Dresden GmbH - Hammelmann Oelde -
		  CPT Jeff A. Satterfield U.S. Marine Corps - Edward L. Cooper - Ex13 - U.S. Navy photo -
		  U.S. Department of Defense - Michael Sandberg - USAF/Judson Brohmer - Tana R. Hamilton -
		  Exif-data states - U.S. Air Force - DrPete - Paulae - Lance Cpl. Samantha H. Arrington -
		  Ralf Manteufel - Staff Sgt. Brian Ferguson - Tony Gray - U.S. Navy photo</div>
        </td>
      </tr>
      <tr valign=top>
        <td align=left>
          Dank an
        </td>
        <td align=left>
          Thomas Weichert (ErdeII)<br>
          Mitarbeiter und Berater der ETS-Verwaltung<br>
          alle Moderatoren im Forum
        </td>
      </tr>
      <tr valign=top>
        <td align=left>
          Thomas Weicherts Dank geht an
        </td>
        <td align=left>
          Jens Eberhardt (einfach alles ;))<br>
          J&uuml;rgen Scholz (Texte, Bilder, Ideen)<br>
          Marius Brade (Bilder, Ideen)<br>
          Patrick Quellmalz (Texte, Ideen)<br>
          Georg B&uuml;chner (Ideen)<br>
          Patrick Karschunke (Ideen)<br>
          <a href=\"javascript:linkTo_UnCryptMailto('".encryptEmail('admin@free4games.de')."');\">Joachim Pfaffl</a> (Foren-Logo)<br>
          Clemens Forman (Design)<br><br>
        </td>
      </tr>
      <!-- <tr valign=top>
        <td align=left>
          Der Dank des Verwaltungsrates geht an
        </td>
        <td align=left>
          Giovanni Lorenzo Sparta (Texte)<br>
          Jabber (Texte)
        </td>
      </tr> -->
      </table>";

  // add pfusch output
  $template->set('pfuschOutput', $pfuschOutput);

  // create html page
  try {
    echo $template->execute();
  }
  catch (Exception $e) { echo $e->getMessage(); }
?>
