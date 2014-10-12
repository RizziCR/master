<?php
  // $use_lib = ?; // MSG_ADMINISTRATION

  require_once("msgs.php");
  require_once("database.php");
  require_once("constants.php");
  require_once("functions.php");
  require_once("do_loop.php");

  // define phptal template
  require_once("PHPTAL.php");
  require_once("include/PHPTAL_EtsTranslator.php");
  $template = new PHPTAL('toplist.html');
  //$template = new PHPTAL('standard.html');
  $template->setTranslator(new PHPTAL_EtsTranslator());
  $template->setEncoding('ISO-8859-1');

  // set page title
  $template->set('pageTitle', 'Übersichten - Ranglisten');

  $pfuschOutput = "";


 // insert specific page logic here

  $show = null;
  $show = (int)addslashes($_GET[show]);
  $_GET[show] = (int)addslashes($_GET[show]);
  $action = $_GET[action];
  $tag = $_GET[tag];

  $showLimit = 50;

  $pfuschOutput .= "  <h1>Ranglisten</h1>
      <table id=\"ranklists\">
      <tr>
        <td> Städte </td>
        <td>
         <a href=\"{$_SERVER['PHP_SELF']}?action=cities\">Grösse</a>
        </td>
      </tr>
      <tr>
        <td> Siedler </td>
        <td>
        <a href=\"{$_SERVER['PHP_SELF']}?action=user_size\">Grösse</a> - <a href=\"{$_SERVER['PHP_SELF']}?action=user_power\">Stärke</a> - <a href=\"{$_SERVER['PHP_SELF']}?action=user_fame\">Ruhm</a> - <a href=\"{$_SERVER['PHP_SELF']}?action=user_donations\">Spenden</a>
        </td>
      </tr>
      <tr>
        <td> Allianzen </td>
        <td>
        <a href=\"{$_SERVER['PHP_SELF']}?action=alliance_size\">Grösse</a> - <a href=\"{$_SERVER['PHP_SELF']}?action=alliance_power\">Stärke</a> - <a href=\"{$_SERVER['PHP_SELF']}?action=alliance_fame\">Ruhm</a>
        </td>
      </tr>
      <tr>
        <td></td>
        <td>
        <a href=\"{$_SERVER['PHP_SELF']}?action=own_positions\">Eigene Ränge</a>
        </td>
      </tr>
    </table>";

  switch ($action)
  {
    case "" :
    case "cities" :
    {
      $pfuschOutput .= "
      <h2>Städte</h2>
      <table id=\"ranks\">
          <tr>
            <th>
              Rang
            </th>
            <th>
              Stadt
            </th>
            <th>
              Siedler (Allianz)
            </th>
            <th>
              Grösse
            </th>
          </tr>";

      if (!$show)
        $show = 0;

      $get_cities = sql_query("SELECT userdata.ID as ID2,city.ID,city.city,city.city_name,userdata.user,city.points,city.alliance FROM city INNER JOIN userdata ON city.user=userdata.ID WHERE userdata.user <> 'Tutorial' ORDER BY city.points DESC, city.city LIMIT $show,$showLimit");
      while ($top = sql_fetch_array($get_cities))
      {
      	// Bestimmen, ob Bewahrer
        $isSpecialClass = "";
        if ($_SESSION['user'] == $top[ID2]) {
		$isSpecialClass = "player";
	} else {
		$getKeeper = sql_query("SELECT userdata.user FROM donations INNER JOIN userdata ON donations.user=userdata.user WHERE donations.user='". $top[ID2] ."' AND type='u'");
		if (sql_num_rows($getKeeper) > 0)
			$isSpecialClass = "keeper";
	}

        $getAlliance = sql_query("SELECT tag FROM alliances WHERE ID = '$top[alliance]'");
        $getAlliance = sql_fetch_array($getAlliance);
        
        if ($show%2)
          $bgcol = "#000000";
        else
          $bgcol = "#222222";

        $show++;

        $sUser = new User($top[ID2]);

        $pfuschOutput .= "  <tr align=center bgcolor=$bgcol>
              <td class=\"rank $isSpecialClass\">
                $show
              </td>
              <td class=\"name $isSpecialClass\">
                <a href=\"information.php?type=c&name=$top[city]\">$top[city_name]</a>
              </td>
              <td class=\"name $isSpecialClass\">
                <a href=\"information.php?type=u&name=$top[user]\">".$sUser->getScreenName()."</a> ". (($getAlliance[tag] !="") ? "(<a href=\"information.php?type=a&name=$getAlliance[tag]\">$getAlliance[tag]</a>)" : "") ."
              </td>
              <td class=\"points $isSpecialClass\">
                $top[points]
              </td>
            </tr>";
      }

      $pfuschOutput .= "  <tr>
            <td colspan=4 align=center>
              <br>";

      if ($_GET[show] - $showLimit >= 0)
      {
        $pfuschOutput .= "<a href=\"{$_SERVER['PHP_SELF']}?action=cities&show=". ($_GET[show] - $showLimit) ."\">Zurück</a>&nbsp;&nbsp;&nbsp;";
      }

      $get_anzahl_entries = sql_query("SELECT count(*) AS anzahl FROM city");
      $showLimit_entries = sql_fetch_array($get_anzahl_entries);

      if ($_GET[show] + $showLimit < $showLimit_entries[anzahl])
      {
        $pfuschOutput .= "<a href=\"{$_SERVER['PHP_SELF']}?action=cities&show=". ($_GET[show] + $showLimit) ."\">Weiter</a>&nbsp;&nbsp;&nbsp;";
        $pfuschOutput .= "<a href=\"{$_SERVER['PHP_SELF']}?action=cities&show=". (((int)(($showLimit_entries[anzahl] - 1) / $showLimit)) * $showLimit) ."\">Ende</a>";
      }


      $pfuschOutput .= "    </td>
          </tr>";
      $pfuschOutput .= "</table>";

      break;
    }
    case "user_size" :
    {
      $pfuschOutput .= "
      <h2>Siedler Grösse</h2>
      <table id=\"ranks\">
          <tr>
            <th>
              Rang
            </th>
            <th>
              Siedler (Allianz)
            </th>
            <th>
              Grösse
            </th>
          </tr>";

      if (!$show)
        $show = 0;

      $get_users = sql_query("SELECT usarios.ID, userdata.user,usarios.points,usarios.alliance FROM usarios INNER JOIN userdata ON usarios.ID=userdata.ID WHERE userdata.user <> 'Tutorial' ORDER BY usarios.points DESC, userdata.user LIMIT $show,$showLimit",$db);
      while ($top = sql_fetch_array($get_users))
      {
        // Bestimmen, ob Bewahrer
                $isSpecialClass = "";
        if ($_SESSION['user'] == $top[ID]) {
		$isSpecialClass = "player";
	} else {
		$getKeeper = sql_query("SELECT user FROM donations WHERE user='". $top[user] ."' AND type='u'");
		if (sql_num_rows($getKeeper) > 0)
			$isSpecialClass = "keeper";
	}
        
        $getAlliance = sql_query("SELECT tag FROM alliances WHERE ID = '$top[alliance]'");
        $getAlliance = sql_fetch_array($getAlliance);
        
        if ($show%2)
          $bgcol = "#000000";
        else
          $bgcol = "#222222";

        $show++;

        $sUser = new User($top[ID]);

        $pfuschOutput .= "  <tr bgcolor=$bgcol>
              <td class=\"rank $isSpecialClass\">
                $show
              </td>
              <td class=\"name $isSpecialClass\">
                <a href=\"information.php?type=u&name=$top[user]\">".$sUser->getScreenName()."</a> ". (($getAlliance[tag] !="") ? "(<a href=\"information.php?type=a&name=$getAlliance[tag]\">$getAlliance[tag]</a>)" : "") ."
              </td>
              <td class=\"points $isSpecialClass\">
                ".number_format($top[points],0,",",".")."
              </td>
            </tr>";
      }

      $pfuschOutput .= "  <tr>
            <td colspan=4 align=center>
              <br>";

      if ($_GET[show] - $showLimit >= 0)
      {
        $pfuschOutput .= "<a href=\"{$_SERVER['PHP_SELF']}?action=user_size&show=". ($_GET[show] - $showLimit) ."\">Zurück</a>&nbsp;&nbsp;&nbsp;";
      }

      $get_anzahl_entries = sql_query("SELECT count(*) AS anzahl FROM usarios");
      $showLimit_entries = sql_fetch_array($get_anzahl_entries);

      if ($_GET[show] + $showLimit < $showLimit_entries[anzahl])
      {
        $pfuschOutput .= "<a href=\"{$_SERVER['PHP_SELF']}?action=user_size&show=". ($_GET[show] + $showLimit) ."\">Weiter</a>&nbsp;&nbsp;&nbsp;";
        $pfuschOutput .= "<a href=\"{$_SERVER['PHP_SELF']}?action=user_size&show=". (((int)(($showLimit_entries[anzahl] - 1) / $showLimit)) * $showLimit) ."\">Ende</a>";
      }



      $pfuschOutput .= "    </td>
          </tr>";
      $pfuschOutput .= "</table>";

      break;
    }
  case "user_power":
      {
      $pfuschOutput .= "
      <h2>Siedler Stärke</h2>
      <table id=\"ranks\">
          <tr>
            <th>
              Rang
            </th>
            <th>
              Siedler (Allianz)
            </th>
            <th>
              Stärke
            </th>
          </tr>";

      if (!$show)
        $show = 0;

      $get_users = sql_query("SELECT usarios.ID,userdata.user,usarios.power,usarios.alliance FROM usarios INNER JOIN userdata ON usarios.ID=userdata.ID WHERE userdata.user <> 'Tutorial' ORDER BY usarios.power DESC, userdata.user LIMIT $show,$showLimit",$db);
      while ($top = sql_fetch_array($get_users))
      {
        // Bestimmen, ob Bewahrer
                $isSpecialClass = "";
        if ($_SESSION['user'] == $top[ID]) {
		$isSpecialClass = "player";
	} else {
		$getKeeper = sql_query("SELECT user FROM donations WHERE user='". $top[user] ."' AND type='u'");
		if (sql_num_rows($getKeeper) > 0)
			$isSpecialClass = "keeper";
	}

        $getAlliance = sql_query("SELECT tag FROM alliances WHERE ID = '$top[alliance]'");
        $getAlliance = sql_fetch_array($getAlliance);
        
        if ($show%2)
          $bgcol = "#000000";
        else
          $bgcol = "#222222";

        $show++;

        $sUser = new User($top[ID]);

        $pfuschOutput .= "  <tr bgcolor=$bgcol>
              <td class=\"rank $isSpecialClass\">
                $show
              </td>
              <td class=\"name $isSpecialClass\">
                <a href=\"information.php?type=u&name=$top[user]\">".$sUser->getScreenName()."</a> ". (($getAlliance[tag] !="") ? "(<a href=\"information.php?type=a&name=$getAlliance[tag]\">$getAlliance[tag]</a>)" : "") ."
              </td>
              <td class=\"points $isSpecialClass\">
                ".number_format($top[power],0,",",".")."
              </td>
            </tr>";
      }

      $pfuschOutput .= "  <tr>
            <td colspan=4 align=center>
              <br>";

      if ($_GET[show] - $showLimit >= 0)
      {
        $pfuschOutput .= "<a href=\"{$_SERVER['PHP_SELF']}?action=user_power&show=". ($_GET[show] - $showLimit) ."\">Zurück</a>&nbsp;&nbsp;&nbsp;";
      }

      $get_anzahl_entries = sql_query("SELECT count(*) AS anzahl FROM usarios");
      $showLimit_entries = sql_fetch_array($get_anzahl_entries);

      if ($_GET[show] + $showLimit < $showLimit_entries[anzahl])
      {
        $pfuschOutput .= "<a href=\"{$_SERVER['PHP_SELF']}?action=user_power&show=". ($_GET[show] + $showLimit) ."\">Weiter</a>&nbsp;&nbsp;&nbsp;";
        $pfuschOutput .= "<a href=\"{$_SERVER['PHP_SELF']}?action=user_power&show=". (((int)(($showLimit_entries[anzahl] - 1) / $showLimit)) * $showLimit) ."\">Ende</a>";
      }


      $pfuschOutput .= "    </td>
          </tr>";
      $pfuschOutput .= "</table>";

        break;
      }
  case "user_fame":
      {
      $pfuschOutput .= "
      <h2>Siedler Ruhm</h2>
      <table id=\"ranks\">
          <tr>
            <th>
              Rang
            </th>
            <th>
              Siedler (Allianz)
            </th>
            <th>
              Ruhm
            </th>
          </tr>";

      if (!$show)
        $show = 0;

      $get_users = sql_query("SELECT usarios.ID, userdata.user,usarios.fame,usarios.alliance FROM usarios INNER JOIN userdata ON usarios.user=userdata.ID WHERE userdata.user <> 'Tutorial' ORDER BY usarios.fame DESC, userdata.user LIMIT $show,$showLimit",$db);
      while ($top = sql_fetch_array($get_users))
      {
        // Bestimmen, ob Bewahrer
                $isSpecialClass = "";
        if ($_SESSION['user'] == $top[ID]) {
		$isSpecialClass = "player";
	} else {
		$getKeeper = sql_query("SELECT user FROM donations WHERE user='". $top[user] ."' AND type='u'");
		if (sql_num_rows($getKeeper) > 0)
			$isSpecialClass = "keeper";
	}

        $getAlliance = sql_query("SELECT tag FROM alliances WHERE ID = '$top[alliance]'");
        $getAlliance = sql_fetch_array($getAlliance);
        
        if ($show%2)
          $bgcol = "#000000";
        else
          $bgcol = "#222222";

        $show++;

        $sUser = new User($top[ID]);

        $pfuschOutput .= "  <tr bgcolor=$bgcol>
              <td class=\"rank $isSpecialClass\">
                $show
              </td>
              <td class=\"name $isSpecialClass\">
                <a href=\"information.php?type=u&name=$top[user]\">".$sUser->getScreenName()."</a> ". (($getAlliance[tag] !="") ? "(<a href=\"information.php?type=a&name=$getAlliance[tag]\">$getAlliance[tag]</a>)" : "") ."
              </td>
              <td class=\"points $isSpecialClass\">
                ".number_format($top[fame],0,",",".")."
              </td>
            </tr>";
      }

      $pfuschOutput .= "  <tr>
            <td colspan=4 align=center>
              <br>";

      if ($_GET[show] - $showLimit >= 0)
      {
        $pfuschOutput .= "<a href=\"{$_SERVER['PHP_SELF']}?action=user_fame&show=". ($_GET[show] - $showLimit) ."\">Zurück</a>&nbsp;&nbsp;&nbsp;";
      }

      $get_anzahl_entries = sql_query("SELECT count(*) AS anzahl FROM usarios");
      $showLimit_entries = sql_fetch_array($get_anzahl_entries);

      if ($_GET[show] + $showLimit < $showLimit_entries[anzahl])
      {
        $pfuschOutput .= "<a href=\"{$_SERVER['PHP_SELF']}?action=user_fame&show=". ($_GET[show] + $showLimit) ."\">Weiter</a>&nbsp;&nbsp;&nbsp;";
        $pfuschOutput .= "<a href=\"{$_SERVER['PHP_SELF']}?action=user_fame&show=". (((int)(($showLimit_entries[anzahl] - 1) / $showLimit)) * $showLimit) ."\">Ende</a>";
      }


      $pfuschOutput .= "    </td>
          </tr>";
      $pfuschOutput .= "</table>";

        break;
      }
  case "user_donations":
      {
    // Liste öffentlichen Spender
    $rank = array();
    $j = 1;
    $ranks_res = sql_query("SELECT user, SUM(amount) AS amount, type, date FROM donations WHERE user != 'Ano Nymous' AND rip = 'FALSE' GROUP BY user ORDER BY SUM(amount) DESC");
    while($row = sql_fetch_assoc($ranks_res)) {
        $currentUser = new User($row[ID]);
        $rank[] = array_merge($row, array('rank'=>$j++, 'fullName'=>$currentUser->getScreenName()));
        // extract dd.mm.yy to table, to sort for last incoming
        $rank[$j-2]['date_short'] = '2021-' . substr($rank[$j-2]['date'], 5, 2) . '-' . substr($rank[$j-2]['date'], 8, 2);
        // donate ist no older then 24 hours
        if ( $rank[$j-2]['date'] > date("Y-m-d",(time()-1*60*60*24*2)))
        {
            $rank[$j-2]['isnew'] = true;
        } else {
            $rank[$j-2]['isnew'] = false;
        }
    }
      $pfuschOutput .= "
        <script type=\"text/javascript\" src=\"javascript/jquery.tablesorter.pack.js\"></script>
<script>
              $(document).ready(function(){
                var flip = 0;
                $(\"button\").click(function () {
                  $(\"p.toggleIdent\").toggle( flip++ % 2 == 0 );
                });
                $(\"#show_list\").tablesorter();
              });
              </script>

                    <table id=\"show_list\" class=\"tablesorter\">
                        <thead>
                            <tr class=\"table_head\">
                                <th class=\"position info\" title=\"|Position in der Bewahrerliste\">Rang</th>
                                <th class=\"type info\" title=\"|Siedler oder Allianz\">Art</th>
                                <th class=\"donator info\" title=\"|Name des Bewahrers\">Bewahrer</th>
                                <th class=\"date info\" name=\"date\" title=\"|Datum der letzten Spende\">Datum</th>
                                <th class=\"points info\" title=\"|Spende in Cent\">Punkte</th>
                            </tr>
                        </thead>
                        <tbody>";
    foreach ($rank as $s) {
      $pfuschOutput .= "
                                <tr>
				<td class=\"keeper\">" . $s['rank'] . "</td>";
      if ($s['type'] == 'u')
	$pfuschOutput .= "<td class=\"position keeper\"><img src=\"${etsAddress}/pics/donator_type_u.gif\" alt=\"S\" /></td>";
      else if ($s['type'] == 'a')
	$pfuschOutput .= "<td class=\"type keeper\"><img src=\"${etsAddress}/pics/donator_type_a.gif\" alt=\"A\" /></td>";
      $pfuschOutput .= "
                                    <td class=\"donator keeper\">
                                        <a href=\"information.php?type=".$s['type']."&name=".$s['user']."\"> ".$s['fullName']."</a>
                                    </td>";
      if ($s['isnew'])
	  $pfuschOutput .= " <td class=\"aktuell info\" title=\"|Neueingang\">".$s['date_short']."</td>";
      else
	  $pfuschOutput .= " <td class=\"keeper\" title=\"|Neueingang\">".$s['date_short']."</td>";
      $pfuschOutput .= "
                                    <td class=\"type keeper\">". ($s['amount'] * 100) . "</td>

                                </tr>";
    }
      $pfuschOutput .= "
                        </tbody>
                    </table>
		    ";
        break;
      }
    case "alliance_size" :
    {
      $pfuschOutput .= "
      <h2>Allianzen Grösse</h2>
      <table id=\"ranks\">
          <tr>
            <th>
              Rang
            </th>
            <th>
              Allianz
            </th>
            <th>
              Mitglieder
            </th>
            <th>
                  Grösse
                </th>
                <th>
                  Durchschnitt
                </th>
          </tr>";

      if (!$show)
        $show = 0;

      $get_user_infos2 = sql_query("SELECT alliances.ID,alliances.tag FROM usarios INNER JOIN alliances ON usarios.alliance = alliances.ID WHERE usarios.ID='$_SESSION[user]'");
      $user_infos2 = sql_fetch_array( $get_user_infos2 );
        
      $get_alliances = sql_query("SELECT ID,tag,members,points FROM alliances ORDER BY points DESC, tag LIMIT $show,$showLimit");
      while ($top = sql_fetch_array($get_alliances))
      {
        // Bestimmen, ob Bewahrer
        $isSpecialClass = "";

        if ($user_infos2[ID] == $top[ID]) {
		$isSpecialClass = "player";
	} else {
		$getKeeper = sql_query("SELECT user FROM donations WHERE user='". $top[tag] ."' AND type='a'");
		if (sql_num_rows($getKeeper) > 0)
			$isSpecialClass = "keeper";
	}

        if ($show%2)
          $bgcol = "#000000";
        else
          $bgcol = "#222222";

        $show++;

        $pfuschOutput .= "  <tr bgcolor=$bgcol>
              <td class=\"rank $isSpecialClass\">
                $show
              </td>
              <td class=\"name $isSpecialClass\">
                <a href=\"information.php?type=a&name=$top[tag]\">$top[tag]</a>
              </td>
              <td class=\"users $isSpecialClass\">
                <a href=\"./alliancelist.php?action=list&tag=$top[tag]\">$top[members]</a>
              </td>
              <td class=\"points $isSpecialClass\">
                    ".number_format($top[points],0,",",".")."
                  </td>
                  <td class=\"avarage $isSpecialClass\">
                    ".number_format(round($top[points]/$top[members]),0,",",".")."
                  </td>
            </tr>";
      }

      $pfuschOutput .= "  <tr>
            <td colspan=4 align=center>
              <br>";

      if ($_GET[show] - $showLimit >= 0)
      {
        $pfuschOutput .= "<a href=\"{$_SERVER['PHP_SELF']}?action=alliance_size&show=". ($_GET[show] - $showLimit) ."\">Zurück</a>&nbsp;&nbsp;&nbsp;";
      }

      $get_anzahl_entries = sql_query("SELECT count(*) AS anzahl FROM alliances");
      $showLimit_entries = sql_fetch_array($get_anzahl_entries);

      if ($_GET[show] + $showLimit < $showLimit_entries[anzahl])
      {
        $pfuschOutput .= "<a href=\"{$_SERVER['PHP_SELF']}?action=alliance_size&show=". ($_GET[show] + $showLimit) ."\">Weiter</a>&nbsp;&nbsp;&nbsp;";
        $pfuschOutput .= "<a href=\"{$_SERVER['PHP_SELF']}?action=alliance_size&show=". (((int)(($showLimit_entries[anzahl] - 1) / $showLimit)) * $showLimit) ."\">Ende</a>";
      }


      $pfuschOutput .= "    </td>
          </tr>";
      $pfuschOutput .= "</table>";

      break;
    }
    case "alliance_power" :
    {
      $pfuschOutput .= "
      <h2>Allianzen Stärke</h2>
      <table id=\"ranks\">
          <tr>
            <th>
              Rang
            </th>
            <th>
              Allianz
            </th>
            <th>
              Mitglieder
            </th>
            <th>
                  Stärke
                </th>
                <th>
                  Durchschnitt
                </th>
          </tr>";

      if (!$show)
        $show = 0;

      $get_user_infos2 = sql_query("SELECT alliances.ID,alliances.tag FROM usarios INNER JOIN alliances ON usarios.alliance = alliances.ID WHERE usarios.ID='$_SESSION[user]'");
      $user_infos2 = sql_fetch_array( $get_user_infos2 );
        
      $get_alliances = sql_query("SELECT ID,tag,members,power FROM alliances ORDER BY power DESC, tag LIMIT $show,$showLimit");
      while ($top = sql_fetch_array($get_alliances))
      {
        // Bestimmen, ob Bewahrer
                $isSpecialClass = "";
        if ($user_infos2[ID] == $top[ID]) {
		$isSpecialClass = "player";
	} else {
		$getKeeper = sql_query("SELECT user FROM donations WHERE user='". $top[tag] ."' AND type='a'");
		if (sql_num_rows($getKeeper) > 0)
			$isSpecialClass = "keeper";
	}

        if ($show%2)
          $bgcol = "#000000";
        else
          $bgcol = "#222222";

        $show++;

        $pfuschOutput .= "  <tr bgcolor=$bgcol>
              <td class=\"rank $isSpecialClass\">
                $show
              </td>
              <td class=\"name $isSpecialClass\">
                <a href=\"information.php?type=a&name=$top[tag]\">$top[tag]</a>
              </td>
              <td class=\"users $isSpecialClass\">
                <a href=\"./alliancelist.php?action=list&tag=$top[tag]\">$top[members]</a>
              </td>
              <td class=\"points $isSpecialClass\">
                    ".number_format($top[power],0,",",".")."
                  </td>
                  <td class=\"avarage $isSpecialClass\">
                    ". number_format(round($top[power]/$top[members]),0,",",".") ."
                  </td>
            </tr>";
      }

      $pfuschOutput .= "  <tr>
            <td colspan=4 align=center>
              <br>";

      if ($_GET[show] - $showLimit >= 0)
      {
        $pfuschOutput .= "<a href=\"{$_SERVER['PHP_SELF']}?action=alliance_power&show=". ($_GET[show] - $showLimit) ."\">Zurück</a>&nbsp;&nbsp;&nbsp;";
      }

      $get_anzahl_entries = sql_query("SELECT count(*) AS anzahl FROM alliances");
      $showLimit_entries = sql_fetch_array($get_anzahl_entries);

      if ($_GET[show] + $showLimit < $showLimit_entries[anzahl])
      {
        $pfuschOutput .= "<a href=\"{$_SERVER['PHP_SELF']}?action=alliance_power&show=". ($_GET[show] + $showLimit) ."\">Weiter</a>&nbsp;&nbsp;&nbsp;";
        $pfuschOutput .= "<a href=\"{$_SERVER['PHP_SELF']}?action=alliance_power&show=". (((int)(($showLimit_entries[anzahl] - 1) / $showLimit)) * $showLimit) ."\">Ende</a>";
      }


      $pfuschOutput .= "    </td>
          </tr>";
      $pfuschOutput .= "</table>";

      break;
    }
    case "alliance_fame" :
    {
      $pfuschOutput .= "
      <h2>Allianzen Ruhm</h2>
      <table id=\"ranks\">
          <tr>
            <th>
              Rang
            </th>
            <th>
              Allianz
            </th>
            <th>
              Mitglieder
            </th>
            <th>
                  Ruhm
                </th>
                <th>
                  Durchschnitt
                </th>
          </tr>";

      if (!$show)
        $show = 0;

      $get_user_infos2 = sql_query("SELECT alliances.ID,alliances.tag FROM usarios INNER JOIN alliances ON usarios.alliance = alliances.ID WHERE usarios.ID='$_SESSION[user]'");
      $user_infos2 = sql_fetch_array( $get_user_infos2 );

      $get_alliances = sql_query("SELECT ID,tag,members,fame FROM alliances ORDER BY fame DESC, tag LIMIT $show,$showLimit");
      while ($top = sql_fetch_array($get_alliances))
      {
        // Bestimmen, ob Bewahrer
                $isSpecialClass = "";
        if ($user_infos2[ID] == $top[ID]) {
		$isSpecialClass = "player";
	} else {
		$getKeeper = sql_query("SELECT user FROM donations WHERE user='". $top[tag] ."' AND type='a'");
		if (sql_num_rows($getKeeper) > 0)
			$isSpecialClass = "keeper";
	}

        if ($show%2)
          $bgcol = "#000000";
        else
          $bgcol = "#222222";

        $show++;

        $pfuschOutput .= "  <tr bgcolor=$bgcol>
              <td class=\"rank $isSpecialClass\">
                $show
              </td>
              <td class=\"name $isSpecialClass\">
                <a href=\"information.php?type=a&name=$top[tag]\">$top[tag]</a>
              </td>
              <td class=\"users $isSpecialClass\">
                <a href=\"./alliancelist.php?action=list&tag=$top[tag]\">$top[members]</a>
              </td>
              <td class=\"points $isSpecialClass\">
                    ".number_format($top[fame],0,",",".")."
                  </td>
                  <td class=\"avarage $isSpecialClass\">
                    ". number_format(round($top[fame]/$top[members]),0,",",".") ."
                  </td>
            </tr>";
      }

      $pfuschOutput .= "  <tr>
            <td colspan=4 align=center>
              <br>";

      if ($_GET[show] - $showLimit >= 0)
      {
        $pfuschOutput .= "<a href=\"{$_SERVER['PHP_SELF']}?action=alliance_fame&show=". ($_GET[show] - $showLimit) ."\">Zurück</a>&nbsp;&nbsp;&nbsp;";
      }

      $get_anzahl_entries = sql_query("SELECT count(*) AS anzahl FROM alliances");
      $showLimit_entries = sql_fetch_array($get_anzahl_entries);

      if ($_GET[show] + $showLimit < $showLimit_entries[anzahl])
      {
        $pfuschOutput .= "<a href=\"{$_SERVER['PHP_SELF']}?action=alliance_fame&show=". ($_GET[show] + $showLimit) ."\">Weiter</a>&nbsp;&nbsp;&nbsp;";
        $pfuschOutput .= "<a href=\"{$_SERVER['PHP_SELF']}?action=alliance_fame&show=". (((int)(($showLimit_entries[anzahl] - 1) / $showLimit)) * $showLimit) ."\">Ende</a>";
      }


      $pfuschOutput .= "    </td>
          </tr>";
      $pfuschOutput .= "</table>";

      break;
    }
    case "own_positions" :
    {
      $pfuschOutput .= "
      <h2>Eigene Ränge</h2>
      <table id=\"ranks\">
          <tr>
            <th>
              Rang
            </th>
            <th>
              Stadt
            </th>
            <th>
              Grösse
            </th>
          </tr>";

      if (!$show)
        $show = 0;

//      $get_cities = sql_query("SELECT pos,city,city_name,points,alliance FROM city WHERE user='$_SESSION[user]' ORDER BY points DESC, city");
      $get_cities = sql_query("SELECT ID,city,city_name,points,alliance FROM city WHERE user='$_SESSION[user]' ORDER BY points DESC, city");
      while ($top = sql_fetch_array($get_cities))
      {
      	$count = sql_query("SELECT count(*)+1 as point FROM city WHERE points>$top[points]");
      	$count = sql_fetch_array($count);
      	
        if ($show%2)
          $bgcol = "#000000";
        else
          $bgcol = "#222222";

        $show++;

        $pfuschOutput .= "  <tr bgcolor=$bgcol>
              <td class=\"rank\">
                <a href=\"{$_SERVER['PHP_SELF']}?action=cities&show=". (((int)(($count[point] - -1) / $showLimit)) * $showLimit) ."\">$count[point]</a>
              </td>
              <td class=\"name\">
                <a href=\"information.php?type=c&name=$top[city]\">$top[city]</a> ($top[city_name])
              </td>
              <td class=\"points\">
                ".number_format($top[points],0,",",".")."
              </td>
            </tr>";
      }
      $pfuschOutput .= "</table>";

      $pfuschOutput .= "<br /><br />";

      $get_user_infos = sql_query("SELECT alliances.tag as alliance FROM usarios INNER JOIN alliances ON usarios.alliance = alliances.ID WHERE usarios.ID='$_SESSION[user]'");
      $user_infos = sql_fetch_array($get_user_infos);

      $pfuschOutput .= "<table id=\"own_ranks\" >";
      $pfuschOutput .= "
        <tr>
          <th>
            Rang
          </th>
          <th>
          Kategorie
          </th>
          <th>
          Punkte
          </th>
        </tr>
            ";
      $top = "";
      $get_points = sql_query("SELECT points FROM usarios WHERE ID='$_SESSION[user]'");
      list( $points ) = sql_fetch_row($get_points);
      $get_user = sql_query("SELECT count(*)+1 as pos FROM usarios WHERE points>'$points' OR (points='$points' AND ID<'$_SESSION[user]')");
      list( $top ) = sql_fetch_row($get_user);

      $pfuschOutput .= "
        <tr bgcolor=\"#222222\" >
          <td class=\"rank\" >
                <a href=\"{$_SERVER['PHP_SELF']}?action=user_size&show=". (((int)(($top - 1) / $showLimit)) * $showLimit) ."\">$top</a>
          </td>
          <td class=\"category\">
            Grösse
          </td>
          <td class=\"points\" >
            ".number_format($points,0,",",".")."
          </td>
        </tr>
             ";

      $top = "";
      $get_points = sql_query("SELECT power FROM usarios WHERE ID='$_SESSION[user]'");
      list( $points ) = sql_fetch_row($get_points);
      $get_user = sql_query("SELECT count(*)+1 as pos FROM usarios WHERE power>'$points' OR (power='$points' AND ID<'$_SESSION[user]')");
      list( $top ) = sql_fetch_row($get_user);

      $pfuschOutput .= "
        <tr bgcolor=\"#000000\" >
          <td class=\"rank\" >
                <a href=\"{$_SERVER['PHP_SELF']}?action=user_power&show=". (((int)(($top - 1) / $showLimit)) * $showLimit) ."\">$top</a>
          </td>
          <td class=\"category\">
            Stärke
          </td>
          <td class=\"points\" >
            ".number_format($points,0,",",".")."
          </td>
        </tr>
             ";

      $top = "";
      $get_points = sql_query("SELECT fame FROM usarios WHERE ID='$_SESSION[user]'");
      list( $points ) = sql_fetch_row($get_points);
      $points = number_format($points,0,",",".");
      $get_user = sql_query("SELECT count(*)+1 as pos FROM usarios WHERE fame>'$points' OR (fame='$points' AND ID<'$_SESSION[user]')");
      list( $top ) = sql_fetch_row($get_user);

      $pfuschOutput .= "
        <tr bgcolor=\"#222222\" >
          <td class=\"rank\" >
                <a href=\"{$_SERVER['PHP_SELF']}?action=user_fame&show=". (((int)(($top - 1) / $showLimit)) * $showLimit) ."\">$top</a>
          </td>
          <td class=\"category\">
            Ruhm
          </td>
          <td class=\"points\" >
            ".number_format($points,0,",",".")."
          </td>
        </tr>
             ";
      $pfuschOutput .= "  </table>";

      if ($user_infos[alliance])
      {
        $pfuschOutput .= "<br /><br />
        <h2>Ränge deiner Allianz</h2>
        <table id=\"own_alliance_ranks\" >
        <tr>
          <th>
            Rang
          </th>
          <th>
          Kategorie
          </th>
          <th>
          Punkte
          </th>
        </tr>
        ";
        $top = "";
        $get_points = sql_query("SELECT points FROM alliances WHERE tag='$user_infos[alliance]'");
        list( $points ) = sql_fetch_row($get_points);
          $get_alliance = sql_query("SELECT count(*)+1 as pos FROM alliances WHERE points>'$points' OR (points='$points' AND tag<'$user_infos[alliance]')");
        list( $top ) = sql_fetch_row($get_alliance);

        $pfuschOutput .= "
          <tr bgcolor=\"#222222\" >
            <td class=\"rank\" >
                <a href=\"{$_SERVER['PHP_SELF']}?action=alliance_size&show=". (((int)(($top - 1) / $showLimit)) * $showLimit) ."\">$top</a>
            </td>
            <td class=\"category\">
              Grösse
            </td>
            <td class=\"points\" >
              ".number_format($points,0,",",".")."
            </td>
          </tr>
               ";

        $top = "";
        $get_points = sql_query("SELECT power FROM alliances WHERE tag='$user_infos[alliance]'");
        list( $points ) = sql_fetch_row($get_points);
          $get_alliance = sql_query("SELECT count(*)+1 as pos FROM alliances WHERE power>'$points' OR (power='$points' AND tag<'$user_infos[alliance]')");
        list( $top ) = sql_fetch_row($get_alliance);

        $pfuschOutput .= "
          <tr bgcolor=\"#000000\" >
            <td class=\"rank\" >
                <a href=\"{$_SERVER['PHP_SELF']}?action=alliance_power&show=". (((int)(($top - 1) / $showLimit)) * $showLimit) ."\">$top</a>
            </td>
            <td class=\"category\">
              Stärke
            </td>
            <td class=\"points\" >
              ".number_format($points,0,",",".")."
            </td>
          </tr>
               ";

        $top = "";
        $get_points = sql_query("SELECT fame FROM alliances WHERE tag='$user_infos[alliance]'");
        list( $points ) = sql_fetch_row($get_points);
          $get_alliance = sql_query("SELECT count(*)+1 as pos FROM alliances WHERE fame>$points OR (fame=$points AND tag<'$user_infos[alliance]')");
        list( $top ) = sql_fetch_row($get_alliance);

        $pfuschOutput .= "
          <tr bgcolor=\"#222222\" >
            <td class=\"rank\" >
                <a href=\"{$_SERVER['PHP_SELF']}?action=alliance_fame&show=". (((int)(($top - 1) / $showLimit)) * $showLimit) ."\">$top</a>
            </td>
            <td class=\"category\">
              Ruhm
            </td>
            <td class=\"points\" >
              ".number_format($points,0,",",".")."
            </td>
          </tr>
               ";

        $pfuschOutput .= "  </table>";
      }

//      $pfuschOutput .= "
//          <tr>
//            <td class=\"name\" >
//            Spenden
//            </td>";
//
//      $top = "";
//      $get_user = sql_query("SELECT count(*)+1 as pos FROM usarios WHERE donations>(select donations FROM usarios WHERE user='$_SESSION[user]') OR (donations=(select donations FROM usarios WHERE user='$_SESSION[user]') AND user<'$_SESSION[user]')");
//      list( $top ) = sql_fetch_row($get_user);
//
//      $pfuschOutput .= "
//            <td class=\"rank\" >
//              $top
//            </td>";
//
//      $top = "";
//      $pfuschOutput .= "
//            <td class=\"rank\" >
//              $top
//            </td>
//          </tr>";

      break;
    }
  }


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
