<?php

  require_once("database.php");
  require_once("functions.php");

 // define phptal template
  require_once("PHPTAL.php");
  require_once("include/PHPTAL_EtsTranslator.php");
  //$template = new PHPTAL('guest/password.html');
  $template = new PHPTAL('guest/standardGuest.html');
  $template->setTranslator(new PHPTAL_EtsTranslator());
  $template->setEncoding('ISO-8859-1');

  require_once("include/TemplateSettingsCommonGuest.php");

  // set page title
  $template->set('pageTitle', 'Kennwort-Erinnerung');

  $pfuschOutput = "";

  switch ($_REQUEST[action])
  {
    case "" :
      $output = "  <form action=\"{$_SERVER['PHP_SELF']}\" method=post>
            <table align=center border=0 cellpadding=3 cellspacing=3>
            <tr valign=top>
              <td colspan=2>
                Bitte geben deine E-Mail-Adresse an, mit der du dich bei ETS angemeldet hast. Wir senden dir dann ein neues Kennwort zu.<br><br>
              </td>
            </tr>
            <tr valign=top>
              <td align=right>
                E-Mail-Adresse:
              </td>
              <td align=left>
                <input type=text name=email class=button>
              </td>
            </tr>
            <tr valign=top>
              <td colspan=2 align=center>
                <input type=hidden name=action value=request>
                <input type=submit name=submit value=\"Neues, <sicheres> Kennwort anfordern\" class=button>
              </td>
            </tr>
            <tr valign=top>
              <td colspan=2 align=center>
                <input type=submit name=submit value=\"Neues Kennwort anfordern\" class=button>
              </td>
            </tr>
            </table>
            </form>";
      break;

    case "request" :
      if ($_POST[submit] == "Neues <sicheres> Kennwort anfordern")
        $t = "s";

      $get_datas = sql_query("SELECT user,name FROM userdata WHERE email='".addslashes($_POST[email])."'");

      if (sql_num_rows($get_datas) == 1)
      {
        $datas = sql_fetch_array($get_datas);
        $cl = $etsAddress."/page/password.php?action=confirm&t=$t&user=$datas[user]&code=". md5($datas[user]."erdoithre");
        smtp_mail("$_POST[email]","Kennwort-Erinnerung","<html><head></head><body>Hallo $datas[name], <br><br>".
            "Du oder jemand anderes hat ein neues Kennwort f&uuml;r dein Benutzerkonto angefordert. Um ein neues Kennwort generieren zu lassen, ".
            "klicke bitte auf folgenden Link: <br><br>".
            "<a href=\"" .$cl. "\">" . $cl . "</a> ".
            "<br><br>$goodbye <br>$liable<br>[ <a href=\"$etsAddress\">$etsName</a> ]</body></html>");
        $SUC_MSG = "Ein neues Kennwort wurde angefordert<br><br>";
      }
      else
        $ERR_MSG = "Die E-Mail-Adresse konnte nicht in der Datenbank gefunden werden";

      break;

    case "confirm" :
      if ($_GET[code] == md5($_GET[user]."erdoithre"))
      {
        $get_datas = sql_query("SELECT email FROM userdata WHERE user='".addslashes($_GET[user])."'");
        $datas = sql_fetch_array($get_datas);

        if ($_GET[t] == "s")
          for ($i=0;$i<10;$i++)
            $npwd .= substr("<>@$%&()=?+*#-_.:,;1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ",rand(0,87),1);
        else
          for ($i=0;$i<10;$i++)
            $npwd .= substr("1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ",rand(0,61),1);

        $md5_password = $npwd . $datas[email] . "B3stBr0ws3rg4m33v3r";
        $md5_password = md5($md5_password);
        $md5_password = md5($md5_password);
        $md5_password = md5($md5_password);
         
        
        
        smtp_mail($datas[email],"Kennwort-Erinnerung","<html><head></head><body>Hallo,<br><br>hier nochmals deine Benutzerdaten:<br><br>Spielername: ".
            "$_GET[user]<br>Kennwort: $npwd<br><br>Bitte hebe diese Daten sorgf&auml;ltig auf.<br><br>$goodbye<br>$liable<br>[ ".
            "<a href=\"$etsAddress\">$etsName</a> ]</body></html>");
        sql_query("UPDATE userdata SET password='$md5_password' WHERE user='".addslashes($_GET[user])."'");
        $SUC_MSG = "Das neue Kennwort wurde erfolgreich versandt<br><br>";
      }
      else
        $ERR_MSG = "Der Code ist falsch oder unvollständig. Bitte kopiere ggf. den kompletten Link in deinen Browser. Klappt auch dies nicht melde dich bitte beim Support.";
  
  
 		break; 
  }

  $pfuschOutput .= "  <h1>Neues Kennwort anfordern</h1>

      $SUC_MSG<font class=error>$ERR_MSG</font>

      $output";

  // add pfusch output
  $template->set('pfuschOutput', $pfuschOutput);

  // create html page
  try {
    echo $template->execute();
  }
  catch (Exception $e) { echo $e->getMessage(); }
?>
