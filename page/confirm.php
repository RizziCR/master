<?php
    $use_lib = 12; // MSG_REGISTER
    require_once("database.php");
      require_once("msgs.php");
 // define phptal template
  require_once("PHPTAL.php");
  require_once("include/PHPTAL_EtsTranslator.php");
  //$template = new PHPTAL('guest/confirm.html');
  $template = new PHPTAL('guest/standardGuest.html');
  $template->setTranslator(new PHPTAL_EtsTranslator());
  $template->setEncoding('ISO-8859-1');

require_once("include/TemplateSettingsCommonGuest.php");

  // set page title
  $template->set('pageTitle', 'Bestätigung');

  $pfuschOutput = "";
  $pfuschOutput .= "<h1>Bestätigung</h1>";
  $pfuschOutput .= "<p>";

    if (!empty($_GET['u']) && !empty($_GET['code']))
    {
        $success = false;
        $sql = "SELECT *
                   FROM
                       userdata
                   WHERE
                       confirm_code = '".addslashes($_GET['code'])."' AND
                       user = '".addslashes($_GET['u'])."'
                   ";
        $res = sql_query($sql);
        if($res) {
            $row = sql_fetch_assoc($res);
            if($_GET['u'] == $row['user']) {
                $success = sql_query("UPDATE userdata SET confirmation='Y' WHERE user='".addslashes($_GET['u'])."'");
            }
        }

        if ($success)
        {
            $SUC_MSG = $MESSAGES[MSG_REGISTER][confirmSuccess];// Erfolg
        }
        else
            $ERR_MSG = $MESSAGES[MSG_REGISTER][confirmFalse];// Code passt nicht zum User
    }
    else
        $ERR_MSG = $MESSAGES[MSG_REGISTER][confirmError];// Link nicht vollstaendig?

    $pfuschOutput .= $SUC_MSG;
    $pfuschOutput .= $ERR_MSG;

  $pfuschOutput .= "</p>";

  // add pfusch output
  $template->set('pfuschOutput', $pfuschOutput);

  // create html page
  try {
    echo $template->execute();
  }
  catch (Exception $e) { echo $e->getMessage(); }
?>