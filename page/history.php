<?php
    include("database.php");
    $unset_all_cookies = "YES";
    include("do_loop.php");

 // define phptal template
  require_once("PHPTAL.php");
  require_once("include/PHPTAL_EtsTranslator.php");
  //$template = new PHPTAL('guest/history.html');
  $template = new PHPTAL('guest/standardGuest.html');
  $template->setTranslator(new PHPTAL_EtsTranslator());
  $template->setEncoding('ISO-8859-1');

require_once("include/TemplateSettingsCommonGuest.php");

  // set page title
  $template->set('pageTitle', 'Nachrichtenarchiv');

  $pfuschOutput = "";

    $pfuschOutput .=  "	<h1>Nachrichtenarchiv</h1>

            <table align=center border=0 cellpadding=2 cellspacing=2>
            <tr>
                <td colspan=2 bgcolor=\"#444444\" align=center>
                    Nachrichten in zeitlicher Ordnung
                </td>
            </tr>";

    $get_admin_login_msgs = sql_query("SELECT time,color,text FROM admin_login_msgs ORDER BY time DESC");
    while ($admin_login_msgs =sql_fetch_array($get_admin_login_msgs))
        $pfuschOutput .=  "
            <tr valign=top>
                <td align=left style=\"color:$admin_login_msgs[color]\">". date("d.m.Y",$admin_login_msgs[time]) ."</td>
                <td align=left style=\"color:$admin_login_msgs[color]\">$admin_login_msgs[text]</td>
            </tr>";

    $pfuschOutput .=  "	</table>";

  // add pfusch output
  $template->set('pfuschOutput', $pfuschOutput);

  // create html page
  try {
    echo $template->execute();
  }
  catch (Exception $e) { echo $e->getMessage(); }
?>