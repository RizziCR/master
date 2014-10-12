<?php
  $use_lib = 13; // MSG_IP_REGISTER

  require_once("msgs.php");
  require_once("database.php");
  require_once("constants.php");
  require_once("functions.php");
  require_once("do_loop.php");

  // define phptal template
  require_once("PHPTAL.php");
  require_once("include/PHPTAL_EtsTranslator.php");
  //$template = new PHPTAL('ipregister.html');
  $template = new PHPTAL('standard.html');
  $template->setTranslator(new PHPTAL_EtsTranslator());
  $template->setEncoding('ISO-8859-1');

  // set page title
  $template->set('pageTitle', 'Multi-Schutz-Formular');

  $pfuschOutput = "";


 // insert specific page logic here

  switch ($_POST[action])
  {
    case "save_double_ip" :
      sql_query("REPLACE INTO multi_angemeldete (user,no_double_ip) VALUES ('$_SESSION[sitter]','".addslashes($_POST[no_double_ip])."')");
      break;

    case "save" :

      if (ErrorMessage(0))
      {
        $pfuschOutput .= "  <h1>Multi-Schutz-Formular</h1>";

        $pfuschOutput .= ErrorMessage();

        // add pfusch output
  $template->set('pfuschOutput', $pfuschOutput);

  // include common template settings
  require_once("include/JavaScriptCommon.php");
  require_once("include/TemplateSettingsCommon.php");

  // save resource changes (ToDo: Is this necessary on every page?)
  $timefixed_depot->save();

  // create html page
  try {
    echo $template->execute();
  }
  catch (Exception $e) { echo $e->getMessage(); }
        die();
      }

      sql_query("UPDATE multi_angemeldete SET
            vorname='".addslashes($_POST[vorname])."',
            name='".addslashes($_POST[name])."',
            strasse='".addslashes($_POST[strasse])."',
            plz='".addslashes($_POST[plz])."',
            ort='".addslashes($_POST[ort])."',
            land='".addslashes($_POST[land])."',
            tel='".addslashes($_POST[tel])."',
            kommentar='".addslashes($_POST[kommentar])."'
            WHERE user='$_SESSION[sitter]'");
      break;

    case "del" :
      sql_query("DELETE FROM multi_angemeldete_doppel_ip WHERE user='$_SESSION[sitter]'");
      sql_query("DELETE FROM multi_angemeldete WHERE user='$_SESSION[sitter]'");

      break;

    case "deluser" :
      sql_query("DELETE FROM multi_angemeldete_doppel_ip WHERE user='$_SESSION[sitter]' && doppel_ip_user='".addslashes($_POST[doppel_ip_user])."'");

      break;

    case "adduser" :
      if (!$_POST[doppel_ip_user] || !$_POST[reason])
        ErrorMessage(MSG_IP_REGISTER,e000);    // Bitte füllen Sie alle Felder aus

      $check_user = sql_query("SELECT 1 FROM userdata WHERE user='".addslashes($_POST[doppel_ip_user])."'");
      if (!sql_num_rows($check_user))
        ErrorMessage(MSG_IP_REGISTER,e001);    // Dieser User existiert nicht

      if (ErrorMessage(0))
      {
        $pfuschOutput .= "  <h1>Multi-Schutz-Formular</h1>";

        $pfuschOutput .= ErrorMessage();

        // add pfusch output
  $template->set('pfuschOutput', $pfuschOutput);

  // include common template settings
  require_once("include/JavaScriptCommon.php");
  require_once("include/TemplateSettingsCommon.php");

  // save resource changes (ToDo: Is this necessary on every page?)
  $timefixed_depot->save();

  // create html page
  try {
    echo $template->execute();
  }
  catch (Exception $e) { echo $e->getMessage(); }
        die();
      }

      sql_query("REPLACE INTO multi_angemeldete_doppel_ip (user,doppel_ip_user,reason)
        VALUES (
            '$_SESSION[sitter]',
            '".addslashes($_POST[doppel_ip_user])."',
            '".addslashes($_POST[reason])."'
        )");

      break;
  }


  $get_data = sql_query("SELECT kommentar,no_double_ip FROM multi_angemeldete WHERE user='$_SESSION[sitter]'");
  $data = sql_fetch_array($get_data);

  $pfuschOutput .= "  <h1>Multi-Schutz-Formular</h1>

      <font class=error>$ERR_MSG</font>$SUC_MSG<br><br>

      <table width=750 border=0 cellpadding=3 cellspacing=3>
       <tr>
         <td colspan=2 align=center>
           <font style=\"color:#FF0000;\">Die Angabe der Daten ist freiwillig. Um einer ungewollten Sperrung deines Benutzerkontos im Falle einer Überprüfung vorzubeugen, empfehlen wir jedoch, dieses Formular auszufüllen. Die angegebenen Daten werden im Verdachtsfall einer Mehrfachregistrierung zu deiner Entlastung herangezogen.</font><br><br>
         </td>
       </tr>
      <form action=\"{$_SERVER['PHP_SELF']}\" method=post>
       <tr>
         <td colspan=2 align=center class=table_head>
           Eigene Daten
         </td>
       </tr>
       <tr valign=top>
         <td>
           Ich teile mit anderen Benutzern meine Internetverbindung
         </td>
         <td>
          <input type=radio name=no_double_ip value=YES ". (($data[no_double_ip] == "YES") ? "checked" : "") ."> Ja<br>
          <input type=radio name=no_double_ip value=NO ". (($data[no_double_ip] == "NO") ? "checked" : "") ."> Nein
         </td>
       </tr>
       <tr valign=top>
         <td>
         </td>
         <td>
           <input type=hidden name=action value=save_double_ip>
           <input type=submit class=button value=\"Speichern\" name=submit>
         </td>
       </tr>
       </form>";


   if ($data[no_double_ip] == "YES")
   {
    $pfuschOutput .= "
       <tr>
         <td colspan=2>
           <hr size=1>
         </td>
       </tr>
      <form action=\"{$_SERVER['PHP_SELF']}\" method=post>
       <tr valign=top>
         <td>
           Anmerkungen
         </td>
         <td>
           <textarea class=button name=kommentar style=\"width:300;height:200;overflow:auto\">$data[kommentar]</textarea>
         </td>
       </tr>
       <tr valign=top>
         <td>
         </td>
         <td>
           <table border=0 cellpadding=2 cellspacing=0>
           <tr>
             <td>
               <input type=hidden name=action value=save>
               <input type=submit class=button value=\"Speichern\" name=submit>
             </td>
             </form>
             <form action=\"{$_SERVER['PHP_SELF']}\" method=post>
             <td>
               <input type=hidden name=action value=del>
               <input type=submit class=button value=\"Daten löschen\" name=submit>
             </td>
             </form>
           </tr>
           </table>
         </td>
       </tr>";
   }

   if ($data[no_double_ip] == "YES")
   {
    $pfuschOutput .= "
       <tr>
         <td colspan=2 align=center>
           <br><br>
         </td>
       </tr>
       <tr>
         <td colspan=2 align=center class=table_head>
           Andere User mit meiner IP
         </td>
       </tr>";

      $pfuschOutput .= "
       <tr>
         <td colspan=2>
           <table border=0 width=100% cellspacing=1 cellpadding=0>
           <tr>
             <td>
               <b>User mit meiner IP</b>
             </td>
             <td>
               <b>Grund</b>
             </td>
             <td>
               <b>Aktion</b>
             </td>
           </tr>";

    $get_doppel_ip_user = sql_query("SELECT doppel_ip_user,reason FROM multi_angemeldete_doppel_ip WHERE user='$_SESSION[sitter]'");
    while ($doppel_ip_user = sql_fetch_array($get_doppel_ip_user))
    {
      if ($i%2)
        $col = "#000000";
      else
        $col = "#222222";

      $i++;

      $pfuschOutput .= "  <form action=\"{$_SERVER['PHP_SELF']}\" method=post>
           <tr bgcolor=$col>
             <td>
               $doppel_ip_user[doppel_ip_user]
             </td>
             <td>
               $doppel_ip_user[reason]
             </td>
             <td>
               <input type=submit value=\"Löschen\" class=button>
               <input type=hidden name=action value=deluser>
               <input type=hidden name=doppel_ip_user value=\"$doppel_ip_user[doppel_ip_user]\">
             </td>
           </tr>
           </form>";
     }

    $pfuschOutput .= "    <form action=\"{$_SERVER['PHP_SELF']}\" method=post>
           <tr>
             <td>
               <input type=text class=button name=doppel_ip_user>
             </td>
             <td>
               <input type=text class=button name=reason>
             </td>
             <td>
               <input type=submit value=\"Hinzufügen\" class=button>
               <input type=hidden name=action value=adduser>
             </td>
           </tr>
           </table>
           </form>
         </td>
       </tr>";
     }

   $pfuschOutput .= "  <tr>
         <td colspan=2 align=center>
           <br><br>
         </td>
       </tr>
      <tr>
         <td colspan=2 align=center class=table_head>
           Datenschutzhinweis
         </td>
       </tr>
       <tr>
         <td colspan=2 align=center>
           Die hier angegebenen Daten dienen ausschließlich zum Ausschluss von unerlaubten Aktivitäten nach AGB §2 Abs.3. Sie werden gemäß der Datenschutzerklärung verwendet und nicht an Dritte weitergegeben.
         </td>
       </tr>
       </table>";

  // add pfusch output
  $template->set('pfuschOutput', $pfuschOutput);

  // include common template settings
  require_once("include/JavaScriptCommon.php");
  require_once("include/TemplateSettingsCommon.php");

  // save resource changes (ToDo: Is this necessary on every page?)
  $timefixed_depot->save();

  // create html page
  try {
    echo $template->execute();
  }
  catch (Exception $e) { echo $e->getMessage(); }
?>
