<?php
  $use_lib = 8; // MSG_ALLIANCELIST

  require_once("msgs.php");
  require_once("database.php");
  require_once("constants.php");
  require_once("functions.php");
  require_once("do_loop.php");

  // define phptal template
  require_once("PHPTAL.php");
  require_once("include/PHPTAL_EtsTranslator.php");
  //$template = new PHPTAL('accstat.html');
  $template = new PHPTAL('standard.html');
  $template->setTranslator(new PHPTAL_EtsTranslator());
  $template->setEncoding('ISO-8859-1');

  // set page title
  $template->set('pageTitle', 'Übersichten - Städte');

  $pfuschOutput = "";


 // insert specific page logic here

  $pfuschOutput .= "  <div  id=\"contentToplist\"><h1>{$MESSAGES[MSG_ALLIANCELIST][m000]}</h1>
      <table  id=\"ranks\">";

  $action = $_GET[action];
  $tag = $_GET[tag];
  $order = $_GET[order];

  switch ($action)
  {
    case "" :
    {
      $pfuschOutput .= "  <tr>
            <th>Rang
            </th>
            <th>
              <a href=\"".$_SERVER['PHP_SELF']."?order=tag\"><b>{$MESSAGES[MSG_ALLIANCELIST][m002]}</b></a>
            </th>
            <th>
              <a href=\"".$_SERVER['PHP_SELF']."?order=pts\"><b>{$MESSAGES[MSG_ALLIANCELIST][m003]}</b></a>
            </th>
            <th>
              <a href=\"".$_SERVER['PHP_SELF']."?order=avg\"><b>{$MESSAGES[MSG_ALLIANCELIST][m001]}</b></a>
            </th>
            <th>
              <a href=\"".$_SERVER['PHP_SELF']."?order=mem\"><b>{$MESSAGES[MSG_ALLIANCELIST][m004]}</b></a>
            </th>
            <th>
              <b>{$MESSAGES[MSG_ALLIANCELIST][m005]}</b>
            </th>
          </tr>";

      switch ($order)
      {
        case "" :
        case "pts" : $order = "points DESC"; break;
        case "avg" : $order = "avg DESC"; break;
        case "mem" : $order = "members DESC"; break;
        case "tag" : $order = "tag"; break;
        default : $order = "points"; break;
      }

      $i = 0;
      $get_alliances = sql_query("SELECT tag,points,members,points/members AS avg FROM alliances ORDER BY ". htmlspecialchars($order,ENT_QUOTES) ."",$db);
      while ($top = sql_fetch_array($get_alliances))
      {
        // Bestimmen, ob Bewahrer
        $isKeeperClass = "";
        $getKeeper = sql_query("SELECT user FROM donations WHERE user='". $top[tag] ."' AND type='a'");
        if (sql_num_rows($getKeeper) > 0)
          $isKeeperClass = "keeper";

        $i++;

        if ($i%2)
          $color = "#000000";
        else
          $color = "#222222";

        $pfuschOutput .= "  <tr bgcolor=$color>
              <td class=\"rank $isKeeperClass\">
                $i
              </td>
              <td class=\"name $isKeeperClass\">
                <a href=\"$dir/information.php?type=a&name=$top[tag]\">$top[tag]</a>
              </td>
              <td class=\"points $isKeeperClass\">
                ".number_format($top[points],0,",",".")."
              </td>
              <td class=\"avarage $isKeeperClass\">
                ".number_format(round($top[avg]),0,",",".")."
              </td>
              <td class=\"users $isKeeperClass\">
                $top[members]
              </td>
              <td class=\"users $isKeeperClass\">
                <a href=\"".$_SERVER['PHP_SELF']."?action=list&tag=$top[tag]\">{$MESSAGES[MSG_ALLIANCELIST][m006]}</a>
              </td>
            </tr>";

      }
      break;
    }
    case "list" :
    {
      if (!$tag)
      {
        ErrorMessage(MSG_ALLIANCELIST,e000);
        // Bitte wählen Sie eine Allianz
      }

      if (ErrorMessage(0))
      {
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

      $pfuschOutput .= "  <tr>
            <th>
              <a href=\"".$_SERVER['PHP_SELF']."?action=list&tag=$tag&order=1\">{$MESSAGES[MSG_ALLIANCELIST][m007]}</a>
            </th>
            <th>
              <a href=\"".$_SERVER['PHP_SELF']."?action=list&tag=$tag&order=2\">{$MESSAGES[MSG_ALLIANCELIST][m009]}</a>
            </th>
            <th>
              <a href=\"".$_SERVER['PHP_SELF']."?action=list&tag=$tag&order=0\">{$MESSAGES[MSG_ALLIANCELIST][m008]}</a>
            </th>
          </tr>";

      if ($order == "")
        $order = 1;

      switch ($order)
      {
        case "0" : $setorder = ""; break;
        case "1" : $setorder = "user,"; break;
        case "2" : $setorder = "points,"; break;
        default  : $setorder = "user,"; break;
      }
	  if($setorder == "") {
	  	$get_alliances = sql_query("SELECT city.user,city.points,city.city FROM city INNER JOIN alliances ON city.alliance = alliances.ID WHERE alliances.tag='". htmlspecialchars($tag,ENT_QUOTES) ."' ORDER BY city.x_pos,city.y_pos,city.z_pos",$db);
	  }else{
      	$get_alliances = sql_query("SELECT city.user,city.points,city.city FROM city INNER JOIN alliances ON city.alliance = alliances.ID WHERE alliances.tag='". htmlspecialchars($tag,ENT_QUOTES) ."' ORDER BY city.$setorder city.x_pos,city.y_pos,city.z_pos",$db);
	  }
      while ($show_user = sql_fetch_array($get_alliances))
      {
      	
      	$select = sql_query("SELECT user FROM userdata WHERE ID = '$show_user[user]'");
      	$select = sql_fetch_array($select);
      	
        // Bestimmen, ob Bewahrer
        $isKeeperClass = "";
        $getKeeper = sql_query("SELECT user FROM donations WHERE user='". $select[user] ."' AND type='u'");
        if (sql_num_rows($getKeeper) > 0)
          $isKeeperClass = "keeper";

        if ($i%2)
          $color = "#000000";
        else
          $color = "#222222";

        $pfuschOutput .= "  <tr bgcolor=$color>
              <td class=\"name $isKeeperClass\">
                <a href=\"$dir/information.php?type=u&name=$select[user]\">$select[user]</a>
              </td>
              <td class=\"points $isKeeperClass\">
                ".number_format($show_user[points],0,",",".")."
              </td>
              <td class=\"name $isKeeperClass\">
                <a href=\"$dir/information.php?type=c&name=$show_user[city]\">$show_user[city]</a>
              </td>
            </tr>";
      }
      break;
    }
  }

  $pfuschOutput .= "  </table></div>";

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
