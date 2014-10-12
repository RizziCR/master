<?php
  $use_lib = 11; // MSG_SEARCH

  require_once("msgs.php");
  require_once("database.php");
  require_once("constants.php");
  require_once("functions.php");
  require_once("do_loop.php");
  include("tutorial.php");

  // define phptal template
  require_once("PHPTAL.php");
  require_once("include/PHPTAL_EtsTranslator.php");
  //$template = new PHPTAL('search.html');
  $template = new PHPTAL('standard.html');
  $template->setTranslator(new PHPTAL_EtsTranslator());
  $template->setEncoding('ISO-8859-1');

  // set page title
  $template->set('pageTitle', 'Übersichten - Suche');

  $pfuschOutput = "";


 // insert specific page logic here

  $pfuschOutput .= "  <h1>Suche</h1>";
  if($_SESSION['user'] == "514") {
  	$pfuschOutput .= "BRANCH ETS13";
  }
  $pfuschOutput .= "

      <table border=0 cellpadding=2 cellspacing=2>
      <tr>
        <td colspan=2 align=center class=table_head>
          Liste aller Allianzen
        </td>
      </tr>
      <tr>
        <td colspan=2 align=center>
          <a href=./alliancelist.php>Liste aller Allianzen anzeigen</a>
        </td>
      </tr>
      <tr>
        <td colspan=2 align=center>
          <br><br>
        </td>
      </tr>
      <tr>
        <td colspan=2 align=center class=table_head>
          User / Allianzen / St&auml;dte
        </td>
      </tr>
      <form action={$_SERVER['PHP_SELF']} method=post>
      <tr>
        <td>
          Suchen nach:<br>
          (Platzhalter %)
        </td>
        <td>
          <input class=button type=text name=search_arg>&nbsp;<input class=button type=submit value=\"Suchen\"><br>
        </td>
      </tr>
      </form>";

  if ($_POST[search_arg])
  {
    $search_arg = trim(addslashes($_POST[search_arg]));

    $pfuschOutput .= "  <tr>
          <td colspan=2>
            <br><br>
          </td>
        </tr>
        <tr>
          <td colspan=2 align=center class=table_head>
            Suchergebnis
          </td>
        </tr>";

    if (preg_match("/^(_|%| )+$/",$search_arg) || !strlen($search_arg))
    {
      ErrorMessage(MSG_SEARCH,e000);
      // Bitte geben Sie einen gültigen Suchbegriff ein
    }

    if (ErrorMessage(0))
    {
      $errorMessage .= "  <tr><td colspan=2 align=center>";
      $errorMessage .= ErrorMessage();
      $errorMessage .= "  </td></tr>";

      // add error output
      $template->set('errorMessage', $errorMessage);

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

    $get_search_u = sql_query("SELECT usarios.ID, usarios.user, userdata.name_affix FROM usarios INNER JOIN userdata ON usarios.user = userdata.user WHERE userdata.user <> 'Tutorial' AND userdata.user LIKE '%". htmlspecialchars($search_arg,ENT_QUOTES) ."%' OR userdata.name_affix LIKE '%". htmlspecialchars($search_arg,ENT_QUOTES) ."%'");
    $get_search_c = sql_query("SELECT city.ID, city.city,city.city_name FROM city INNER JOIN userdata on city.user = userdata.ID WHERE userdata.user <> 'Tutorial' AND city.city_name LIKE '%". htmlspecialchars($search_arg,ENT_QUOTES) ."%'");
    $get_search_a = sql_query("SELECT ID, tag FROM alliances WHERE tag LIKE '%". htmlspecialchars($search_arg,ENT_QUOTES) ."%'");


    $pfuschOutput .= "  <tr valign=top>
          <td>
            User
          </td>
          <td>";

    if (sql_num_rows($get_search_u))
      while ($show = sql_fetch_array($get_search_u))
        $pfuschOutput .= "<a href=\"./information.php?type=u&name=$show[user]\">$show[user] $show[name_affix]</a><br>";
    else
      $pfuschOutput .= "Keine Eintr&auml;ge gefunden";

    $pfuschOutput .= "    </td>
        </tr>
        <tr valign=top>
          <td>
            Allianzen
          </td>
          <td>";

    if (sql_num_rows($get_search_a))
      while ($show = sql_fetch_array($get_search_a))
        $pfuschOutput .= "<a href=\"./information.php?type=a&name=$show[tag]\">$show[tag]</a><br>";
    else
      $pfuschOutput .= "Keine Eintr&auml;ge gefunden";

    $pfuschOutput .= "    </td>
        </tr>
        <tr valign=top>
          <td>
            St&auml;dte
          </td>
          <td>";

    if (sql_num_rows($get_search_c))
      while ($show = sql_fetch_array($get_search_c))
        $pfuschOutput .= "<a href=\"./information.php?type=c&name=$show[city_name]\">$show[city_name]</a><br>";
    else
      $pfuschOutput .= "Keine Eintr&auml;ge gefunden";

    $pfuschOutput .= "    </td>
        </tr>";
  }

  $pfuschOutput .= "  </table>";

 // end specific page logic


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
