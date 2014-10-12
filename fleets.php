<?php
  $use_lib = 1; // MSG_ADMINISTRATION

  require_once("msgs.php");
  require_once("database.php");
  require_once("constants.php");
  require_once("functions.php");
  require_once("do_loop.php");

// define phptal template
  require_once("PHPTAL.php");
  require_once("include/PHPTAL_EtsTranslator.php");
  //$template = new PHPTAL('fleets.html');
  $template = new PHPTAL('standard.html');
  $template->setTranslator(new PHPTAL_EtsTranslator());
  $template->setEncoding('ISO-8859-1');

  // set page title
  $template->set('pageTitle', 'Übersichten - Flotten');

  $pfuschOutput = "";
  $pfuschOutput .= "<script language='JavaScript'>
					function toggle(source) {
  						checkboxes = document.getElementsByName('del_fleet[]');
  						for(var i in checkboxes)
    					checkboxes[i].checked = source.checked;
					}
  					</script>";


 // insert specific page logic here

  $get_buildings = sql_query("SELECT b_communication_center FROM city WHERE ID='$_SESSION[city]' && user='$_SESSION[user]'");
  $buildings = sql_fetch_array($get_buildings);


  $pfuschOutput .= "  <h1>Flotten</h1>

      <table border=0 cellpadding=3 cellspacing=3>";

  $action = $_REQUEST[action];
  $id = $_REQUEST[id];
  $back = $_REQUEST[back];
  $code = $_REQUEST[code];

  switch ($action)
  {
    case "" :
    {
      $main_query = sql_query("SELECT p_". implode(",p_",$p_db_name_wus) ." FROM city WHERE ID='$_SESSION[city]'");
      $p_count = sql_fetch_array($main_query);

      $main_query = sql_query("SELECT p_". implode("_gesamt,p_",$p_db_name_wus) ."_gesamt FROM city WHERE ID='$_SESSION[city]'");
      $p_count_gesamt = sql_fetch_array($main_query);

      $main_actions = sql_query("SELECT id,f_id,city,f_action,f_arrival,f_target,f_target_user,f_iridium,f_holzium,f_water,f_oxygen,f_name FROM actions WHERE city='$_SESSION[city]' && f_action!='' ORDER BY f_arrival ASC");

      if (array_sum($p_count_gesamt))
      {
        $pfuschOutput .= "  <tr>
              <td colspan=3 align=center class=table_head>
                Flugzeuge
              </td>
            </tr>
            <tr valign=top>
              <td>
                <b>Flugzeug</b>
              </td>
              <td>
                <b>verfügbar</b>
              </td>
              <td>
                <b>gesamt</b>
              </td>
            </tr>";

        for ($i=0;$i<ANZAHL_FLUGZEUGE;$i++)
        {
          if ($p_count_gesamt[$i] > 0)
          {
            $pfuschOutput .= "  <tr valign=top>
                  <td>
                    $p_name[$i]
                  </td>
                  <td>
                    $p_count[$i]
                  </td>
                  <td>
                    $p_count_gesamt[$i]
                  </td>
                </tr>";
          }
        }
      }
      else
      {
        $pfuschOutput .= "  <tr>
              <td colspan=3 align=center>
                <b>Keine Flottenbewegungen vorhanden</b>
              </td>
            </tr>";
      }

      if (sql_num_rows($main_actions))
      {
        $pfuschOutput .= "<form action='".$_SERVER['PHP_SELF']."?action=del' method='post'>
        	<tr>
              <td colspan=4>
                <br><br>
              </td>
            </tr>
            <tr>
              <td colspan=4 align=center class=table_head>
                Flotten
              </td>
            </tr>
            <tr valign=top>
              <td>
                <b>Löschen</b>
              </td>
              <td colspan=2>
                <b>Flotte</b>
              </td>
              <td>
                <b>Ankunft</b>
              </td>
            </tr>
            <tr>
              <td>
                <input type='checkbox' onClick='toggle(this)' /> Alle wählen
              </td>
              <td colspan=3>
                <input type='submit' value='Löschen'>
              </td>
            </tr>";

        while ($fleet = sql_fetch_array($main_actions))
        {
          $pfuschOutput .= "
            <tr valign=top>
                <td>";
          if($fleet[f_action] == "attack" || $fleet[f_action] == "transport") 
                   $pfuschOutput .= "<input type='checkbox' name='del_fleet[]' value='$fleet[id]'>";
          $pfuschOutput .= "</td>
                <td colspan=2>";

          // fleet name given by user; trading center fleets do not have names
          if ($fleet[f_name] != "")
            $name = "(Name: ".stripslashes($fleet[f_name]).")";
          else
            $name = "";

          // Stadt+User durch Koordinaten und Usernamen ersetzt
          
          $target_city = sql_fetch_array(sql_query("SELECT city FROM city WHERE ID='$fleet[f_target]';"));
          $target_user = sql_fetch_array(sql_query("SELECT user FROM userdata WHERE ID='$target_user'"));
          $city = sql_fetch_array(sql_query("SELECT city FROM city WHERE ID='$city[city]'"));
          
          if ($target_user[user] != "")
            $target_user = "($target_user[user])";
          else
            $target_user = "";

          switch ($fleet[f_action])
          {
            case "attack" :
            case "transport" :
              $pfuschOutput .= "<a href=\"{$_SERVER['PHP_SELF']}?action=detail&id=$fleet[id]\">Flug nach $target_city[city] $target_user $name</a>";
              break;

            case "attack_back" :
            case "transport_back" :
              $pfuschOutput .= "<a href=\"{$_SERVER['PHP_SELF']}?action=detail&id=$fleet[id]\">Rückflug von $target_city[city] $target_user $name</a>";
              break;

            case "sell_to_depot" :
              $pfuschOutput .= "<a href=\"{$_SERVER['PHP_SELF']}?action=detail&id=$fleet[id]\">Rohstoffhandel (zum Hauptlager)</a>";
              break;

            case "sell_from_depot" :
              $pfuschOutput .= "<a href=\"{$_SERVER['PHP_SELF']}?action=detail&id=$fleet[id]\">Rohstoffhandel (vom Hauptlager - Rückflug)</a>";
              break;

            case "plane_sell" :
              $pfuschOutput .= "<a href=\"{$_SERVER['PHP_SELF']}?action=detail&id=$fleet[id]\">Flugzeughandel (zum Hauptlager)</a>";
              break;

            case "plane_buy" :
              $pfuschOutput .= "<a href=\"{$_SERVER['PHP_SELF']}?action=detail&id=$fleet[id]\">Flugzeughandel (vom Hauptlager)</a>";
              break;
          }

          $name = "";

          $pfuschOutput .= "    </td>
                <td>".
                  date("H:i",$fleet[f_arrival]) ."<font class=seconds>:". date("s",$fleet[f_arrival]) ."</font> ". date("d.m.Y",$fleet[f_arrival])
                ."</td>";
        }
      }
      break;
    }
    case "del" :
    {
    	$select = sql_query("SELECT b_communication_center FROM city WHERE ID='$_SESSION[city]'");
    	$select = sql_fetch_array($select);
    	if($select['b_communication_center'] > 4) {
    		$i = count($_POST['del_fleet']);
	    	foreach($_POST as $temp) {
	    	}
	    	for($x=0;$x<$i;$x++) {
	    		$fleet = sql_query("SELECT f_id,f_action,f_start,f_give,f_arrival,f_espionage_probe,f_flugzeuge_anzahl,f_iridium,f_holzium,f_water,f_oxygen FROM actions WHERE user='$_SESSION[user]' && id='$temp[$x]'");
	      		$fleet = sql_fetch_array($fleet);
	      		switch ($fleet[f_action])
	          	{
	            	case "attack" :
		              sql_query("UPDATE actions SET f_start='". time() ."',f_arrival=f_arrival-2*'". ($fleet[f_arrival]-time()) ."' WHERE f_action='attack_back' && f_id='$fleet[f_id]'");
		              sql_query("DELETE FROM actions WHERE f_action='attack' && f_id='$fleet[f_id]'");
		
		              if ($fleet[f_flugzeuge_anzahl] != $fleet[f_espionage_probe])
		                sql_query("DELETE attack_denies FROM attack_denies RIGHT JOIN actions ON attack_denies.id=actions.attack_deny_id WHERE actions.f_id='$fleet[f_id]'");
		              break;
		
		            case "transport" :
		              if (!$fleet[f_give])
		              {
		              	sql_query("UPDATE actions SET f_start='". time() ."',f_arrival=f_arrival-2*'". ($fleet[f_arrival]-time()) ."',f_iridium='$fleet[f_iridium]',f_holzium='$fleet[f_holzium]',f_water='$fleet[f_water]',f_oxygen='$fleet[f_oxygen]' WHERE f_action='transport_back' && f_id='$fleet[f_id]'");
		                sql_query("DELETE FROM actions WHERE f_action='transport' && f_id='$fleet[f_id]'");
		              }
		              else
		              {
		                sql_query("UPDATE actions SET f_action='transport_back',f_start='". time() ."',f_arrival=f_start+'". (time()-$fleet[f_start]) ."',msg='Eine Flotte ($city[city]) kehrte von $fleet[f_target] zurück' WHERE id='$temp[$x]'");
		              }
		              break;
		       }
	      	}
		    $pfuschOutput .= "<center>Die Flotte wurde erfolgreich zurückbeordert.</center>";
    	}else{
    		$pfuschOutput .= "<center>Du benötigst mindestens Kommunikationszentrum Stufe 5 um Flotten zurückrufen zu können.</center>";
    	}

    }
    case "detail" :
    {
      $fleets = sql_query("SELECT id,city,f_id,f_action,f_give,f_start,f_arrival,f_target,f_name,f_name_show,f_iridium,f_holzium,f_water,f_oxygen,f_espionage_probe,f_flugzeuge_anzahl,code FROM actions WHERE city='$_SESSION[city]' && id='$id'");
      while ($fleet = sql_fetch_array($fleets))
      {
        $get_target_user = sql_query("SELECT city,user FROM city WHERE ID='$fleet[f_target]'");
        $target_user = sql_fetch_array($get_target_user);

        if ($back == "YES" && $buildings[b_communication_center] >= 5 && $fleet[f_arrival] > time() && $code == $fleet[code])
        {
          switch ($fleet[f_action])
          {
            case "attack" :
              sql_query("UPDATE actions SET f_start='". time() ."',f_arrival=f_arrival-2*'". ($fleet[f_arrival]-time()) ."' WHERE f_action='attack_back' && f_id='$fleet[f_id]'");
              sql_query("DELETE FROM actions WHERE f_action='attack' && f_id='$fleet[f_id]'");

              if ($fleet[f_flugzeuge_anzahl] != $fleet[f_espionage_probe])
                sql_query("DELETE attack_denies FROM attack_denies RIGHT JOIN actions ON attack_denies.id=actions.attack_deny_id WHERE actions.f_id='$fleet[f_id]'");

              $pfuschOutput .= "<center>Die Flotte wurde erfolgreich zurückbeordert</center>";
              break;

            case "transport" :
              if (!$fleet[f_give])
              {
                sql_query("UPDATE actions SET f_start='". time() ."',f_arrival=f_arrival-2*'". ($fleet[f_arrival]-time()) ."',f_iridium='$fleet[f_iridium]',f_holzium='$fleet[f_holzium]',f_water='$fleet[f_water]',f_oxygen='$fleet[f_oxygen]' WHERE f_action='transport_back' && f_id='$fleet[f_id]'");
                sql_query("DELETE FROM actions WHERE f_action='transport' && f_id='$fleet[f_id]'");
                $pfuschOutput .= "<center>Die Flotte wurde erfolgreich zurückbeordert</center>";
              }
              else
              {
                sql_query("UPDATE actions SET f_action='transport_back',f_start='". time() ."',f_arrival=f_start+'". (time()-$fleet[f_start]) ."',msg='Eine Flotte ($city[city]) kehrte von $fleet[f_target] zurück' WHERE id='$id'");
              }
              break;
          }
        }
      }

      $fleets = sql_query("SELECT * FROM actions WHERE city='$_SESSION[city]' && id='$id'");
      while ($fleet = sql_fetch_array($fleets))
      {
        switch ($fleet[f_action])
        {
          case "attack" :
          case "transport" :
            $pfuschOutput .= "  <tr valign=top>
                  <td>
                    <b>Ziel</b>
                  </td>
                  <td>
                    $target_user[city]
                  </td>
                </tr>";

            if ($fleet[f_name] != "")
            {
              $pfuschOutput .= "
                <tr valign=top>
                  <td>
                    <b>Flottenname</b>
                  </td>
                  <td>
                    ".stripslashes($fleet[f_name])."
                  </td>
                </tr>";
            }
            break;

          case "attack_back" :
          case "transport_back" :
            $pfuschOutput .= "  <tr valign=top>
                  <td>
                    <b>Ziel</b>
                  </td>
                  <td>
                    $target_user[city]
                  </td>
                </tr>";

            /*if ($fleet[f_name] != "")
            {
              $pfuschOutput .= "
                <tr valign=top>
                  <td>
                    <b>Flottenname</b>
                  </td>
                  <td>
                    ".stripslashes($fleet[f_name])."
                  </td>
                </tr>";
            }*/
            break;

          case "buy_to_depot" :
          case "sell_to_depot" :
          case "plane_sell" :
            $pfuschOutput .= "  <tr valign=top>
                  <td>
                    <b>Ziel</b>
                  </td>
                  <td>
                    Hauptlager
                  </td>
                </tr>";
            break;

          case "plane_buy" :
            $pfuschOutput .= "  <tr valign=top>
                  <td>
                    <b>Ziel</b>
                  </td>
                  <td>
                    $target_user[city]
                  </td>
                </tr>";
            break;
        }

        $pfuschOutput .= "  <tr valign=top>
              <td>
                <b>Ankunft</b>
              </td>
              <td>".
                date("H:i",$fleet[f_arrival]) ."<font class=seconds>:". date("s",$fleet[f_arrival]) ."</font> ". date("d.m.Y",$fleet[f_arrival])
              ."</td>
            </tr>";

        if ($buildings[b_communication_center] >= 5 && $fleet[f_arrival] > time() && ($fleet[f_action] == "attack" || $fleet[f_action] == "transport") && $back != "YES")
        {
          $pfuschOutput .= "  <tr valign=top colspan=2>
                <td colspan=2>
                  <br>
                </td>
              </tr>
              <tr>
                <td colspan=2>
                  <a href=\"{$_SERVER['PHP_SELF']}?back=YES&id=$fleet[id]&action=detail&code=$fleet[code]\">Flotte zurückbeordern (nicht verbrauchter Sauerstoff geht verloren)</a>
                </td>
              </tr>";
        }

        $pfuschOutput .= "  <tr valign=top colspan=2>
              <td>
                <br>
              </td>
            </tr>";

        switch ($fleet[f_action])
        {
          case "sell_to_depot" :
            $mission = "Rohstoffhandel (zum Hauptlager)";
            break;

          case "sell_from_depot" :
            $mission = "Rohstoffhandel (vom Hauptlager - Rückflug)";
            break;

          case "plane_sell" :
            $mission = "Flugzeughandel (zum Hauptlager)";
            break;

          case "plane_buy" :
            $mission = "Flugzeughandel (vom Hauptlager)";
            break;

          case "transport" :
            if ($fleet[f_give] == "YES")
              $add_plane_give = "[Flugzeuge verschenken]";
            $mission = "Rohstoffe transportieren $add_plane_give";
            break;

          case "transport_back" :
            $mission = "Rohstoffe transportieren (Rückflug)";
            break;

          case "attack" :
            if ($fleet[f_plunder] == "YES")
              $fleet_prefs[] = "Plündern (". translate($fleet[f_iridium]) .", ". translate($fleet[f_holzium]) .", ". translate($fleet[f_water]) .", ". translate($fleet[f_oxygen]) .")";

            if ($fleet[f_spy] == "YES")
              $fleet_prefs[] = "Spionieren";

            if ($fleet[f_colonize] == "YES")
              $fleet_prefs[] = "Kolonisieren";

            $mission = "Angreifen [" . ((count($fleet_prefs)) ? implode("; ",$fleet_prefs) : "keine Optionen gewählt") ."]";
            if($fleet[f_colonize] == "YES")
            {
              $mission .= "<br>"; 
              if($fleet[f_colonize_jobs] == "YES")
                $mission = $mission . "<br>- {$MESSAGES[MSG_AIRPORT]['m034']}";
              if($fleet[f_colonize_fleets] == "YES")
                $mission = $mission . "<br>- {$MESSAGES[MSG_AIRPORT]['m035']}";
              if($fleet[f_colonize_hangar] == "YES")
                $mission = $mission . "<br>- {$MESSAGES[MSG_AIRPORT]['m039']}";
              }
              
            break;

          case "attack_back" :
            $mission = "Angreifen (Rückflug)";
            break;
        }

        $pfuschOutput .= "  <tr valign=top>
              <td>
                <b>Auftrag</b>
              </td>
              <td>
                $mission
              </td>
            </tr>
            <tr valign=top colspan=2>
              <td>
                <br>
              </td>
            </tr>";

        if ($fleet[f_iridium] + $fleet[f_holzium] + $fleet[f_water] + $fleet[f_oxygen])
        {
          $pfuschOutput .= "<tr valign=top colspan=2>
              <td>
                <b>Ladung</b>
              </td>
            </tr>";

          if ($fleet[f_iridium])
            $pfuschOutput .= "<tr valign=top>
                <td>
                  Iridium
                </td>
                <td>
                  ". round($fleet[f_iridium]) ."
                </td>
              </tr>";

          if ($fleet[f_holzium])
            $pfuschOutput .= "<tr valign=top>
                <td>
                  Holzium
                </td>
                <td>
                  ". round($fleet[f_holzium]) ."
                </td>
              </tr>";

          if ($fleet[f_water])
            $pfuschOutput .= "<tr valign=top>
                <td>
                  Wasser
                </td>
                <td>
                  ". round($fleet[f_water]) ."
                </td>
              </tr>";

          if ($fleet[f_oxygen])
            $pfuschOutput .= "<tr valign=top>
                <td>
                  Sauerstoff
                </td>
                <td>
                  ". round($fleet[f_oxygen]) ."
                </td>
              </tr>";

          $pfuschOutput .= "  <tr colspan=2>
                <td>
                  <br>
                </td>
              </tr>";
        }
        $pfuschOutput .= "  <tr colspan=2>
              <td>
                <b>Flugzeuge</b>
              </td>
            </tr>";

        switch ($fleet[f_action])
        {
          case "attack" :
          case "attack_back" :
          case "transport" :
          case "transport_back" :
          case "plane_sell" :
          case "plane_buy" :
          case "sell_to_depot" :
          case "sell_from_depot" :
            for ($i=0;$i<ANZAHL_FLUGZEUGE;$i++)
              if ($fleet["f_{$p_db_name_wus[$i]}"])
                $pfuschOutput .= "  <tr valign=top>
                      <td>
                        $p_name[$i]
                      </td>
                      <td>
                        ". $fleet["f_{$p_db_name_wus[$i]}"] ."
                      </td>
                    </tr>";
            break;
        }
  		if($fleet[f_name_show] == "YES")
  		{
  			$pfuschOutput .= "
            <tr valign=top colspan=2>
              <td>
                <br>
              </td>
            </tr>
            <tr>
  				<td>
  					<b>Flottenname</b>
  				</td>
     			<td>
       				$fleet[f_name]
     			</td> 
     		</tr>";
  		}     
      }
  
  
      $pfuschOutput .= "   <form f_action=\"{$_SERVER['PHP_SELF']}\">
          <tr align=center>
            <td colspan=2>
              <br>
              <input type=hidden name=f_action>
              <input type=submit value=\"Zurück\" class=button>
            </td>
          </tr>
          </form>";

      break;
    }
  }
  $pfuschOutput .= "</table>";

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
