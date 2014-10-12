<?php
  $use_lib = 9; // MSG_MESSAGES

  require_once("msgs.php");
  require_once("database.php");
  require_once("constants.php");
  require_once("functions.php");
  require_once("do_loop.php");

 // define phptal template
  require_once("PHPTAL.php");
  require_once("include/PHPTAL_EtsTranslator.php");
  //$template = new PHPTAL('messages.html');
  $template = new PHPTAL('standard.html');
  $template->setTranslator(new PHPTAL_EtsTranslator());
  $template->setEncoding('ISO-8859-1');

  // set page title
  $template->set('pageTitle', 'Kommunikation - Nachrichten');

  $pfuschOutput = "";

  $select_username = sql_query("SELECT user FROM userdata WHERE ID = '$_SESSION[user]'");
  $select_username = sql_fetch_array($select_username);
  
  // insert specific page logic here



  $action = $_REQUEST[action];
  switch ($action)
  {
    case "news_del" :
    case "news_ber" :
      $title = $MESSAGES[MSG_MESSAGES][m004]; break;
    case "news_er" :
      $title = $MESSAGES[MSG_MESSAGES][m005]; break;
    default :
      $title = $MESSAGES[MSG_MESSAGES][m001]; break;
  }

  $pfuschOutput .= "  <h1>$title</h1>

      <table border=0 cellpadding=2 cellspacing=0>
      <tr>
        <td colspan=3 align=center>
          {$MESSAGES[MSG_MESSAGES][m013]}
        </td>
      </tr>
      <tr>
        <td align=center>";

  if (!$_SESSION['sitt_login'])
    $pfuschOutput .= "     <a href=\"#\" onclick=\"window.open('$etsAddress/msgctr.php','msgctr','fullscreen=no,channelmode=no,toolbar=yes,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=810,height=700,top=5,left=5')\">{$MESSAGES[MSG_MESSAGES][m000]}</a><br>
          [<a href=\"#\" onclick=\"window.open('$etsAddress/msgctr.php?action=write','msgctr','fullscreen=no,channelmode=no,toolbar=yes,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=810,height=700,top=5,left=5')\">{$MESSAGES[MSG_MESSAGES][m002]}</a>]";


  $pfuschOutput .= "    </td>
        <td>
          <a href=\"".$_SERVER['PHP_SELF']."?action=news_ber\">{$MESSAGES[MSG_MESSAGES][m004]}</a>
        </td>
        <td>
          <a href=\"".$_SERVER['PHP_SELF']."?action=news_er\">{$MESSAGES[MSG_MESSAGES][m005]}</a>
        </td>
      </tr>";

  if (ErrorMessage(0))
  {
    $errorMessage .= "  <tr>
          <td colspan=3 align=center>";
    $errorMessage .= ErrorMessage();
    $errorMessage .= "    </td>
        </tr>";

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
  $pfuschOutput .= "</table>";
  
  switch ($action)
  {
    case "del_ber":
    { 
    	
      $i = count($_POST['del_ber']);
      foreach($_POST as $temp) {
      }
      for($x=0;$x<$i;$x++) {
      	if(!$_SESSION['sitt_login']) {
      		sql_query("UPDATE news_ber SET attack_delete = 'Y' WHERE attack_user='$_SESSION[user]' AND attack_bid='". mysql_real_escape_string($temp[$x]) . "'");
      		sql_query("UPDATE news_ber SET defense_delete = 'Y'  WHERE defense_user='$_SESSION[user]' AND defense_bid='". mysql_real_escape_string($temp[$x]) . "'");
      	}else{
      		sql_query("UPDATE news_ber SET attack_delete_sitter = 'Y' WHERE attack_user='$_SESSION[user]' AND attack_bid='". mysql_real_escape_string($temp[$x]) . "'");
      		sql_query("UPDATE news_ber SET defense_delete_sitter = 'Y'  WHERE defense_user='$_SESSION[user]' AND defense_bid='". mysql_real_escape_string($temp[$x]) . "'");
      	}
      }      
    }
    case "news_ber" :
    {
      $ori = ( !empty($_GET[ori]) ? $_GET[ori] : $_SESSION[city] );
      $ori = mysql_real_escape_string($ori);
	  $art = ( !empty($_GET[art]) ? $_GET[art] : "all");
	  
	  $sel = sql_query("SELECT city FROM city WHERE ID='$ori'");
	  $sel = sql_fetch_array($sel);
	  if($sel['city'] == $ori)
	  		$ori = $_SESSION[city];
      
	  if($_SESSION[user] == "514") {
	  	print_r($ori);
	  }
	  
	  $get_origins_attack = sql_query("SELECT attack_city as city FROM news_ber WHERE attack_user='$_SESSION[user]' AND attack_delete='N' GROUP BY city");
	  $get_origins_defense = sql_query("SELECT defense_city as city FROM news_ber WHERE defense_user='$_SESSION[user]' AND defense_delete='N' GROUP BY city");
	  $user_cities = sql_query("SELECT ID,city FROM city WHERE user='$_SESSION[user]'");
	  
      switch($art) {
      	case "all":
      		if(!$_SESSION['sitt_login']) 
      			$art2 = array("attack_user, defense_user, attack_bid as bid, defense_bid as bid2, attack_city, defense_city", "(defense_city = '$ori' AND defense_delete='N' AND art!='transport_back') OR (attack_city = '$ori' AND attack_delete='N')");
      		else
      			$art2 = array("attack_user, defense_user, attack_bid as bid, defense_bid as bid2, attack_city, defense_city", "(defense_city = '$ori' AND defense_delete_sitter='N' AND art!='transport_back') OR (attack_city = '$ori' AND attack_delete_sitter='N')");
      		break;
      		
      	case "defense":
      		if(!$_SESSION['sitt_login'])
      			$art2 = array("attack_user, defense_bid as bid2, attack_city, defense_city, defense_user", "(defense_city = '$ori' AND defense_delete='N')", "attack");
      		else
      			$art2 = array("attack_user, defense_bid as bid2, attack_city, defense_city, defense_user", "(defense_city = '$ori' AND defense_delete_sitter='N')", "attack");
      		break;
      		
      	case "transport_income":
      		if(!$_SESSION['sitt_login'])
      			$art2 = array("attack_user, defense_user, defense_bid as bid2, attack_city, defense_city", "(defense_city = '$ori' AND defense_delete='N')", "transport");
      		else
      			$art2 = array("attack_user, defense_user, defense_bid as bid2, attack_city, defense_city", "(defense_city = '$ori' AND defense_delete_sitter='N')", "transport");
      		break;
      		
      	case "transport_outgoing":
      		if(!$_SESSION['sitt_login'])
      			$art2 = array("defense_user, attack_bid as bid, defense_city", "(attack_city = '$ori' AND attack_delete='N')", "transport' OR art = 'transport_back");
      		else
      			$art2 = array("defense_user, attack_bid as bid, defense_city", "(attack_city = '$ori' AND attack_delete_sitter='N')", "transport' OR art = 'transport_back");
      		break;
      		
      	case "attack":
      		if(!$_SESSION['sitt_login'])
      			$art2 = array("defense_user, attack_bid as bid, defense_city", "(attack_city = '$ori' AND attack_delete='N')", "attack' OR art = 'attack_back");
      		else
      			$art2 = array("defense_user, attack_bid as bid, defense_city", "(attack_city = '$ori' AND attack_delete_sitter='N')", "attack' OR art = 'attack_back");
      		break; 
      		
      	case "scan":
      		if(!$_SESSION['sitt_login'])
      			$art2 = array("attack_user, defense_user, attack_bid as bid, defense_bid as bid2, defense_city, attack_city", "(attack_city = '$ori' AND attack_delete='N') OR (defense_city = '$ori' AND defense_delete='N')", "scan");
      		else
      			$art2 = array("attack_user, defense_user, attack_bid as bid, defense_bid as bid2, defense_city, attack_city", "(attack_city = '$ori' AND attack_delete_sitter='N') OR (defense_city = '$ori' AND defense_delete_sitter='N')", "scan");
      		break; 
      		
      	case "hz":
      		if(!$_SESSION['sitt_login'])
      			$art2 = array("attack_user, attack_bid as bid, attack_city", "(attack_city = '$ori' AND attack_delete='N')", "sell_to_depot' OR art = 'sell_from_depot' OR art = 'plane_sell' OR art = 'plane_buy");
      		else
      			$art2 = array("attack_user, attack_bid as bid, attack_city", "(attack_city = '$ori' AND attack_delete_sitter='N')", "sell_to_depot' OR art = 'sell_from_depot' OR art = 'plane_sell' OR art = 'plane_buy");
      		break;
      	
      }
      
      if($art == "all") 
      	$get_msg = "SELECT ". $art2[0] . ", time, f_name_show, f_name, art, colonize, error FROM news_ber WHERE (attack_user='$_SESSION[user]' OR defense_user='$_SESSION[user]') AND ((". $art2[1] . ") OR (defense_user='$_SESSION[user]' AND colonize='Y')) ORDER BY time DESC";
	  else
	  	$get_msg = "SELECT ". $art2[0] . ", time, f_name_show, f_name, art, colonize, error FROM news_ber WHERE (attack_user='$_SESSION[user]' OR defense_user='$_SESSION[user]') AND ". $art2[1] . " AND (art = '$art2[2]') ORDER BY time DESC";
	  
	  
	  if($_SESSION['user'] == "514") {
	  	$pfuschOutput .= "GET_MSG: $get_msg<br>";
	  }
	  $get_msgs = sql_query($get_msg);
      
	  $pfuschOutput .= "<table border=0 cellpadding=2 cellspacing=0>
           <tr>
              <td colspan=3>
                {$MESSAGES[MSG_MESSAGES][m031]} ";

      /*if (sql_num_rows($get_origins_attack) || sql_num_rows($get_origins_defense)) {
      	$x=0;
         while ($origins = sql_fetch_array($get_origins_attack)) {
         	$city[$x] = $origins[city];
         	$x++;
         }
         while ($origins = sql_fetch_array($get_origins_defense)) {
         	$city[$x] = $origins[city];
         	$x++;
         }
         sort($city);
         $city = array_unique($city);
         foreach($city as $origins) 
         	$pfuschOutput .= "  <a href=\"".$_SERVER['PHP_SELF']."?action=$_GET[action]&ori=$origins\">$origins</a> |"; 
      	
	  }else
          $pfuschOutput .= "{$MESSAGES[MSG_MESSAGES][m033]}";
        */  
          while( $city = sql_fetch_array( $user_cities ) ) {
                 $pfuschOutput .= "  <a href=\"".$_SERVER['PHP_SELF']."?action=$_GET[action]&ori=$city[ID]\">$city[city]</a> |";
          }


      $pfuschOutput .= "    </td>
            </tr>";
	  $pfuschOutput .= "<tr>
	  <td colspan=3>
		  <table border=0 cellpadding=2 cellspacing=0>
		  <tr><td>
		  <a href=\"".$_SERVER['PHP_SELF']."?action=news_ber&ori=$ori&art=attack\">Angriff</a>
		  </td><td>
		  <a href=\"".$_SERVER['PHP_SELF']."?action=news_ber&ori=$ori&art=scan\">Scan</a>
		  </td><td>
		  <a href=\"".$_SERVER['PHP_SELF']."?action=news_ber&ori=$ori&art=defense\">Verteidigung</a>
		  </td><td>
		  <a href=\"".$_SERVER['PHP_SELF']."?action=news_ber&ori=$ori&art=transport_income\">Handel Eingang</a>
		  </td><td>
		  <a href=\"".$_SERVER['PHP_SELF']."?action=news_ber&ori=$ori&art=transport_outgoing\">Handel Ausgang</a>
		  </td><td>
		  <a href=\"".$_SERVER['PHP_SELF']."?action=news_ber&ori=$ori&art=hz\">Handelszentrum</a>
		  </td></tr>
		  </table>
	  </td></tr>";
      if (sql_num_rows($get_msgs))
      {
        $pfuschOutput .= "<form action='".$_SERVER['PHP_SELF']."?action=del_ber' method='post'>
            <tr>
             <td colspan=3 valign='left'>
             <input type='submit' value='{$MESSAGES[MSG_MESSAGES][m023]}'>
             </td>
            </tr>";
      	$pfuschOutput .= "
            <tr>
              <td colspan=3>
                <tableborder=0 cellspacing=0 cellpadding=2>
                <tr>
                  <td>
      				{$MESSAGES[MSG_MESSAGES][m023]}
                  </td>
                  <td>
                    {$MESSAGES[MSG_MESSAGES][m024]}
                  </td>
                  <td>
                    {$MESSAGES[MSG_MESSAGES][m004]}
                  </td>
                </tr>
                <tr>";
            
            while ($msgs = sql_fetch_array($get_msgs))
            {
            	$fUser = sql_fetch_array ( sql_query ( "SELECT user,name_affix FROM userdata WHERE ID='$msgs[defense_user]'"));
            	$aUser = sql_fetch_array ( sql_query ( "SELECT user,name_affix FROM userdata WHERE ID='$msgs[attack_user]'"));
            	$def = sql_fetch_array ( sql_query ( "SELECT city FROM city WHERE ID = '$msgs[defense_city]';"));
            	$att = sql_fetch_array ( sql_query ( "SELECT city FROM city WHERE ID = '$msgs[attack_city]';"));
            	 
            	$msgs['attack_user'] = $aUser['user'];
            	$msgs['defense_user'] = $fUser['user'];
            	$msgs['attack_city2'] = $att['city'];
            	$msgs['defense_city2'] = $def['city'];
            	
            switch($msgs[art]) {
            	case "attack":
            		if($msgs[defense_city] == $ori)
            		{
	            		if($msgs[f_name_show] == "Y")
	            		{
	            			$msgs[topic] = "Eine Flotte " . (($msgs['f_name']) ?  "»". $msgs['f_name'] ."«" : "") . " von ".$msgs[attack_city2]." (".$msgs[attack_user].") erreichte Ihre Stadt";
	            		}
	            		else 
	            		{
	            			$msgs[topic] = "Eine Flotte von ".$msgs[attack_city2]." (".$msgs[attack_user].") erreichte Ihre Stadt";
	            		}
	            		break;
            		}else{
            			$msgs[topic] = "Eine Flotte " . (($msgs['f_name']) ?  "»". $msgs['f_name'] ."«" : "") . " nach ".$msgs[defense_cit2y]." (".$msgs[defense_user].") erreichte Ihr Ziel";
	            		if($msgs[colonize] == "Y" && $msgs[attack_user] == $select_username[user]) 
	            			$msgs[topic] = "$msgs[defense_city2] wurde erfolgreich erobert";
	            		if($msgs[colonize] == "Y" && $msgs[defense_user] == $select_username[user]) 
	            			$msgs[topic] = "Sie haben Ihre Kolonie $msgs[defense_city2] an $msgs[attack_user] verloren";
	            		if($msgs[colonize] == "N" && $msgs[attack_user] == $select_username[user] && $msgs[error] == "Settler") 
	            			$msgs[topic] = "Sie haben erfolgreich eine neue Stadt gegründet";
	            		break;
            		}
            		
            	case "attack_back":
            		$msgs[topic] = "Eine Flotte " . (($msgs['f_name']) ?  "»". $msgs['f_name'] ."«" : "") . " kehrte von $msgs[defense_city2] ($msgs[defense_user]) zurück";
            		break;
            
            	case "transport":
            		if($msgs[attack_city] == $ori)
            		{
            			if($msgs[f_name_show] == "Y")
            				$msgs[topic] = "Eine Flotte " . (($msgs['f_name']) ?  "»". $msgs['f_name'] ."«" : "") . " von ".$msgs[attack_city2]." (".$msgs[attack_user].") lieferte auf ".$msgs[defense_city2]." (".$msgs[defense_user].")";
            			else
            				$msgs[topic] = "Eine Flotte von ".$msgs[attack_city2]." (".$msgs[attack_user].") lieferte auf ".$msgs[defense_city2]." (".$msgs[defense_user].")";
            			break;
            		}
            		else
            		{
            			$msgs[topic] = "Eine Flotte " . (($msgs['f_name']) ?  "»". $msgs['f_name'] ."«" : "") . " von ".$msgs[attack_city2]." (".$msgs[attack_user].") lieferte Ihnen auf ".$msgs[defense_city2]." (".$msgs[defense_user].")";
            			break;
            		}
            	
            	case "transport_back":
            		$msgs[topic] = "Eine Flotte " . (($msgs['f_name']) ?  "»". $msgs['f_name'] ."«" : "") . " kehrte von $msgs[defense_city2] ($msgs[defense_user]) zurück";
            		break;
            		
            	case "scan":
            		if($ori == $msgs[defense_city])
            		{
	            		if($msgs[f_name_show] == "Y")
	            			$msgs[topic] = "Eine Flotte " . (($msgs['f_name']) ?  "»". $msgs['f_name'] ."«" : "") . " von ".$msgs[attack_city2]." (".$msgs[attack_user].") erreichte Ihre Stadt";
	            		else
	            			$msgs[topic] = "Eine Flotte von ".$msgs[attack_city2]." (".$msgs[attack_user].")  erreichte Ihre Stadt";
	            		break;
            		}else{
	            		$msgs[topic] = "Eine Flotte " . (($msgs['f_name']) ?  "»". $msgs['f_name'] ."«" : "") . " nach ".$msgs[defense_city2]." (".$msgs[defense_user].") erreichte Ihr Ziel";
	            		break;
            		}
            		
            	case "sell_from_depot":
            		$msgs[topic] = "Eine Rohstoffhandel-Flotte vom Hauptlager erreichte ".$msgs[attack_city2];
            		break;
            			
            	case "sell_to_depot":
            		$msgs[topic] = "Eine Rohstoffhandel-Flotte von ".$msgs[attack_city2]." erreichte das Hauptlager";
            		break;
            		
            	case "plane_buy":
            		$msgs[topic] = "Eine Flugzeughandel-Flotte vom Hauptlager erreichte $msgs[attack_city2]";
            		break;
            		
            	case "plane_sell":
            		$msgs[topic] = "Eine Flugzeughandel-Flotte von $msgs[attack_city2] erreichte das Hauptlager";
            		break;
            }
            # TODO: Farben via CSS definieren
              if ($i%2)
                $color = "#222222";
              else
                $color = "#000000";
			if($msgs[bid2] != "" || $msgs[attack_user] == $select_username[user]) {
               if($msgs[defense_user] == $select_username[user] && $msgs[bid2] != "") 
               	$msgs[bid] = $msgs[bid2];
              $pfuschOutput .= "<tr valign=top bgcolor=$color>
              <td><input type='checkbox' name='del_ber[]' value='$msgs[bid]'></td>
              	<td>".
              	   ETSZeit($msgs[time])
              	."</td>
              	<td>
                   <a href=\"messages_berichte.php?bid=$msgs[bid]\" target=bericht onclick=\"window.open('messages_berichte.php?bid=$msgs[bid]','bericht','width=700,height=580,location=yes,resizable=yes,scrollbars=yes');return false\">". sonderz($msgs[topic]) ."</a>
                </td>";

				$i++;
            }
            }
            $pfuschOutput .= "
              </td>
            </tr>
            </form>";
      }
      else
      {
        $pfuschOutput .= "  <tr>
              <td colspan=3 align=center>
                {$MESSAGES[MSG_MESSAGES][m019]}
              </td>
            </tr>";
      }
      break;
    }
    case "news_er" :
    {
      $get_msgs = sql_query("SELECT id,time,topic FROM news_er WHERE city='$_SESSION[city]' ORDER BY time DESC");

      if (sql_num_rows($get_msgs) >= 1)
      {
        $pfuschOutput .= "  <tr>
              <td colspan=3>
                <table border=0 cellspacing=0 cellpadding=2>
                <tr>
                  <td>
                    {$MESSAGES[MSG_MESSAGES][m024]}
                  </td>
                  <td>
                    {$MESSAGES[MSG_MESSAGES][m005]}
                  </td>
                </tr>";

            while ($msgs = sql_fetch_array($get_msgs))
            {
            # TODO: Farben via CSS definieren
              if ($i%2)
                $color = "#222222";
              else
                $color = "#000000";

              $msgs[topic] = sonderz($msgs[topic]);

              $pfuschOutput .= "  <tr valign=top bgcolor=$color>
                    <td>".
                      ETSZeit($msgs[time])
                    ."</td>
                    <td>
                      ". sonderz($msgs[topic]) ."
                    </td>";

              $i++;
            }

            $pfuschOutput .= "
              </td>
            </tr>";
      }
      else
      {
        $pfuschOutput .= "  <tr>
              <td colspan=3 align=center>
                {$MESSAGES[MSG_MESSAGES][m019]}
              </td>
            </tr>";
      }
      break;
    }
  }

  $pfuschOutput .= "</table></table>";

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