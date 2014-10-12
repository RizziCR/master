<?php
    require("database.php");

 // define phptal template
  require_once("PHPTAL.php");
  require_once("include/PHPTAL_EtsTranslator.php");
  //$template = new PHPTAL('guest/recover.html');
  $template = new PHPTAL('guest/standardGuest.html');
  $template->setTranslator(new PHPTAL_EtsTranslator());
  $template->setEncoding('ISO-8859-1');

require_once("include/TemplateSettingsCommonGuest.php");

  // set page title
  $template->set('pageTitle', 'Benutzerkonto freischalten');

  $pfuschOutput = "";

  //XXX info message should be showed if user was not marked as deleted or code was wrong
    if (isset($_GET[code])) {
        $sql = "SELECT * FROM userdata WHERE confirm_code ='".addslashes($_GET['code'])."' AND user = '".addslashes($_GET['user'])."'";
        $res = sql_query($sql);
        if($res && sql_num_rows($res)) {
           sql_query("UPDATE userdata SET delacc=0, delacc2='A' WHERE user='" . addslashes($_GET['user']) . "'");
        } else {
            //nothing :p
        }

    }

    $pfuschOutput .= "<h1>Benutzerkonto freischalten</h1>

            <table align=center border=0 cellpadding=3 cellspacing=3>
            <tr>
                <td align=center>
                    Dein Benutzerkonto wurde erfolgreich wiederhergestellt. <a href=\"$etsAddress/page/login.php\">Erde II betreten</a>
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
