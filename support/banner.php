<?php

if(isset($_GET[image])) {
    echo '<html><body><img src="../'.trim($_GET[image],'./').'"></body></html>';
    exit;
}

require_once("msgs.php");
require_once("database.php");
require_once("constants.php");
require_once("functions.php");
require_once("do_loop.php");

// define phptal template
require_once("PHPTAL.php");
require_once("include/PHPTAL_EtsTranslator.php");
$template = new PHPTAL('standard.html');
$template->setTranslator(new PHPTAL_EtsTranslator());
$template->setEncoding('ISO-8859-1');

// include common template settings
require_once("include/JavaScriptCommon.php");
require_once("include/TemplateSettingsCommon.php");

// set page title
$template->set('pageTitle', 'Administration - Werbebanner');

if($acl['acl]'] == 'ADMIN' || $acl['acl'] == 'SUPPORT') {
    if(isset($_POST[accept]) || isset($_POST[deny])) {
        if(is_array($_POST[accept])) {
            list( $id ) = array_keys( $_POST[accept] );
            sql_query('UPDATE alliance_ads SET approved = "Y", link_to=CONCAT("/information.php?type=a&name=",tag) WHERE id='.intval($id));
        }
        else if(is_array($_POST[deny])) {
            list( $id ) = array_keys( $_POST[deny] );
            $cause = (!empty($_POST[cause][$id]) ? strip_magic_slashes($_POST[cause][$id]) : 'Ohne Gründe');
            sql_query('UPDATE alliance_ads SET denied = "'.addslashes($cause).'" WHERE id='.intval($id));
        }
    }

    $pfuschOutput = "
  <form action='./banner.php' method='post'>
      <table width='100%'>
  ";

    $banner = sql_query("SELECT * FROM alliance_ads WHERE approved='N' AND credit>0 AND denied IS NULL");
    while ( $row = sql_fetch_assoc($banner) ) {
        $pfuschOutput .= "<tr><td><img class='tooltip' src='../$row[thumb]' rel='banner.php?image=$row[filename]'/></td>
          <td>
	      Banner von $row[tag] mit $row[credit] Aufladungen.
              <input type='submit' name='accept[$row[id]]' value='Akzeptieren' /><br />
              <input type='submit' name='deny[$row[id]]' value='Ablehnen' /><br />
              Grund:<br />
              <input type='text' name='cause[$row[id]]' size='40' maxlength='64' value='' />
              </td>
          </tr>
      <tr><td colspan='2'><hr /></td></tr>";
    }

    $pfuschOutput .= "
          </table>
      </form>
      <script type='text/javascript'>
      <!--
          $('.tooltip').cluetip();
      // -->
      </script>
      ";
}
else
$pfuschOutput = 'Diese Funktion ist für Dich gesperrt.';

$template->set('pfuschOutput', $pfuschOutput);

try {
    echo $template->execute();
}
catch (Exception $e) { echo $e->getMessage(); }

?>