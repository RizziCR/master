<?php
  require_once("database.php");
  require_once("functions.php");

 // define phptal template
  require_once("PHPTAL.php");
  require_once("include/PHPTAL_EtsTranslator.php");
  //$template = new PHPTAL('guest/emailchange.html');
  $template = new PHPTAL('guest/standardGuest.html');
  $template->setTranslator(new PHPTAL_EtsTranslator());
  $template->setEncoding('ISO-8859-1');

require_once("include/TemplateSettingsCommonGuest.php");

  // set page title
  $template->set('pageTitle', 'Adressänderung');

  $pfuschOutput = "";

  $u = $_GET['u'];
  $e = $_GET['e'];
  $code = $_GET['code'];

  if ($u && $e && $code)
  {
    $get_change_status = sql_query("SELECT email_confirm,email_new,confirm_code FROM userdata WHERE user='".addslashes($u)."' && email='".addslashes($e)."'");

    if (sql_num_rows($get_change_status))
    {
      $change_status = sql_fetch_assoc($get_change_status);

      if ($change_status[email_confirm] == "N")
      {
        if ($code == $change_status['confirm_code'])
        {
          $newConfirmCode = getConfirmCode();
          $res = sql_query("UPDATE userdata SET email_confirm='Y',confirm_code='$newConfirmCode' WHERE user='".addslashes($u)."' && email='".addslashes($e)."'");
          if (!$res)
            $ERR_MSG = "Es ist ein Fehler aufgetreten. Bitte wende dich an <a href=\"mailto:$supportEmail\">$supportEmail</a>";
          else
          {
            $change_link = $etsAddress."/page/emailchange.php?u=$u&e=$change_status[email_new]&code=". $newConfirmCode;
            smtp_mail($change_status[email_new],"E-Mail-Änderungswunsch Bestätigung",
                "<html><head></head><body>Hallo,<br><br>du möchtest die E-Mail-Adresse für dein ETS-Benutzerkonto ändern. ".
                "Um die Änderung abzuschließen, klicke bitte auf diesen Link: ".
                "<a href=\"".$change_link."\">". $change_link ." </a>".
                "<br><br>$goodbye <br>$liable<br>[ <a href=\"$etsAddress\">$etsName</a> ]</body></html>");
            $SUC_MSG = "Änderung akzeptiert. Bitte best&auml;tige die andere E-Mail-Adresse.";
          }
        }
        else
          $ERR_MSG = "Die E-Mail-Adresse und der Code passen nicht zusammen";
      }
      else
        $ERR_MSG = "Ung&uuml;ltige Anfrage";
    }
    else
    {
      $res = sql_query("SELECT * FROM userdata WHERE confirm_code ='".addslashes($code)."'");
      if(!$res) {
          $ERR_MSG = '';
      } else {
          $row = sql_fetch_assoc($res);
          if ($code == $row['confirm_code'] && $u ==$row['user'] && $e == $row['email_new'])
          {
            $res = sql_query("UPDATE userdata SET email=email_new,email_new='',email_confirm='N' WHERE user='$u' && email_new='$e'");
            if (!$res) {
              $ERR_MSG = "Es ist ein Fehler aufgetreten. Bitte wende dich an <a href=\"mailto:$supportEmail\">$supportEmail</a>";
            } else {
              $SUC_MSG = "&Auml;nderung akzeptiert. Die E-Mail-Adresse wurde ge&auml;ndert.";
            }
          } else {
            $ERR_MSG = "Die E-Mail-Adresse und der Code passen nicht zusammen";
          }
      }
    }
  }
  else
    $ERR_MSG = "Bitten rufe den vollst&auml;ndigen Link auf, den du per E-Mail erhalten hast";

  $pfuschOutput .= "  <h1>E-Mail-Adresse &auml;ndern</h1>

      $SUC_MSG<font class=error>$ERR_MSG</font>";

  // add pfusch output
  $template->set('pfuschOutput', $pfuschOutput);

  // create html page
  try {
    echo $template->execute();
  }
  catch (Exception $e) { echo $e->getMessage(); }
?>
