<?php
  $use_lib = 25; // MSG_REPORT

  require_once("msgs.php");
  require_once("database.php");
  require_once("constants.php");
  require_once("functions.php");

  //add login check...
  require_once('do_loop.php');

  if($_GET['forum'] == "Y") $forum = "y";
  
  if (!$_SESSION[user_path])
    $_SESSION[user_path] = "$etsAddress";

  if (!$_SESSION[user_path_css])
    $_SESSION[user_path_css] = "$cssAddress";

  $output = "  <html>
        <head>
        <META HTTP-EQUIV=\"content-type\" CONTENT=\"text/html; charset=iso-8859-1\">
        <META HTTP-EQUIV=\"expires\" CONTENT=\"0\">
        <META HTTP-EQUIV=\"Cache-Control\" CONTENT=\"no-cache\">
        <META HTTP-EQUIV=\"Pragma\" CONTENT=\"no-cache\">

        <title>Escape To Space - Das Online-Strategie-Spiel</title>

        <link type=\"text/css\" rel=\"stylesheet\" href=\"". $oldcssAddress . "?ts=" . TIMESTAMP() ."\" />
        </head>
        <body>
        <div id='oldStyle'>
        <table width=100% align=center border=0 cellpadding=0 cellspacing=0>";
  
  if($_GET[bid] != "") {
  $collect_messages = "SELECT * FROM news_ber WHERE attack_bid='". addslashes(htmlspecialchars($_GET[bid],ENT_QUOTES)) ."' OR defense_bid='". addslashes(htmlspecialchars($_GET[bid],ENT_QUOTES)) ."'";
  $collect_messages = sql_query($collect_messages);
  if (sql_num_rows($collect_messages))
  {	
    $show_news = sql_fetch_array($collect_messages);
  	$output .= "<tr>
          <td align=center><br>";
  	if($forum != "y") 
  		$output .= "<h1>{$MESSAGES[MSG_REPORT]['m000']}</h1>";
  	
	$output .= "<table width=650 border=0 cellpadding=3 cellspacing=3>
    		<tr valign=top>
              <td width=150>";
	if($forum == "y") 
			$output .= "[quote]";
	
		$att_alliance = sql_fetch_array ( sql_query ( "SELECT tag FROM alliances WHERE ID='$show_news[attackers_alliance]'") );
		$def_alliance = sql_fetch_array ( sql_query ( "SELECT tag FROM alliances WHERE ID='$show_news[defenders_alliance]'") );
		
		$show_news['attackers_alliance'] = $att_alliance['tag'];
		$show_news['defenders_alliance'] = $def_alliance['tag'];
              
    	$fUser = sql_fetch_array ( sql_query ( "SELECT user,name_affix FROM userdata WHERE ID='$show_news[defense_user]'"));
    	$aUser = sql_fetch_array ( sql_query ( "SELECT user,name_affix FROM userdata WHERE ID='$show_news[attack_user]'"));
    	$def = sql_fetch_array ( sql_query ( "SELECT city FROM city WHERE ID = '$show_news[defense_city]';"));
    	$att = sql_fetch_array ( sql_query ( "SELECT city FROM city WHERE ID = '$show_news[attack_city]';"));
    	
    	if($show_news[art] == "attack" && $show_news[attack_bid] == $_GET[bid]) $topic = "Eine Flotte nach $def[city] ($fUser[user] $fUser[name_affix]) erreichte Ihr Ziel";
    	if($show_news[art] == "attack" && $show_news[attack_bid] == $_GET[bid] && $show_news[colonize] == "Y") $topic = "$def[city] wurde erfolgreich erobert";
    	if($show_news[art] == "attack" && $show_news[defense_bid] == $_GET[bid]) $topic = "Eine Flotte von $att[city] ($aUser[user] $aUser[name_affix]) erreichte Ihre Stadt";
    	if($show_news[art] == "attack" && $show_news[defense_bid] == $_GET[bid] && $show_news[colonize] == "Y") $topic = "Sie haben Ihre Kolonie $def[city] an $aUser[user] verloren";
    	if($show_news[art] == "attack" && $show_news[attack_bid] == $_GET[bid] && $show_news[colonize] == "Y" && $show_news[error] == "Settler") $topic = "Sie haben erfolgreich eine neue Stadt gegründet";
    	if($show_news[art] == "attack_back") $topic = "Eine Flotte ($att[city]) kehrte von $def[city] ($fUser[user] $fUser[name_affix]) zurück";
    	if($show_news[art] == "plane_buy") $topic = "Eine Flugzeughandel-Flotte vom Hauptlager erreichte $att[city]";
    	if($show_news[art] == "plane_sell") $topic = "Eine Flugzeughandel-Flotte von $att[city] erreichte das Hauptlager";
    	if($show_news[art] == "sell_to_depot") $topic = "Eine Rohstoffhandel-Flotte ($att[city]) erreichte das Hauptlager";
    	if($show_news[art] == "sell_from_depot") $topic = "Eine Rohstoffhandel-Flotte vom Hauptlager erreichte $att[city]";
    	if($show_news[art] == "transport" && $show_news[attack_bid] == $_GET[bid]) $topic = "Eine Flotte von $att[city] ($aUser[user]  $aUser[name_affix]) überbrachte an $def[city] ($fUser[user]  $fUser[name_affix])";
    	if($show_news[art] == "transport" && $show_news[defense_bid] == $_GET[bid]) $topic = "Eine Flotte von $def[city] ($fUser[user]  $fUser[name_affix]) lieferte Ihnen auf $att[city] ($aUser[user]  $aUser[name_affix])";
    	if($show_news[art] == "transport_back") $topic = "Eine Flotte ($att[city]) kehrte von $def[city] ($fUser[user] $fUser[name_affix]) zurück";
    	if($show_news[art] == "scan" && $show_news[attack_bid] == $_GET[bid]) $topic = "Eine Flotte nach $def[city] ($fUser[user] $fUser[name_affix]) erreichte Ihr Ziel";
    	if($show_news[art] == "scan" && $show_news[defense_bid] == $_GET[bid]) $topic = "Eine Flotte von $att[city] ($aUser[user] $aUser[name_affix]) erreichte Ihre Stadt";
    	
    	
	    $show_news['attack_user'] = $aUser['user'];
	    $show_news['defense_user'] = $fUser['user'];
	    $show_news['attack_city'] = $att['city'];
	    $show_news['defense_city'] = $def['city'];
	    
	if($forum != "y")
			$output .= "<tr>
							<td colspan=2 align=center class=table_head>
								$topic
							</td> 
	            		</tr>
	            		<tr valign=top>
				              <td width=150>";
	if($forum == "y") 
			$output .= "[b]{$MESSAGES[MSG_REPORT]['m001']}[/b]";
	else
			$output .= "<b>{$MESSAGES[MSG_REPORT]['m001']}</b>";
			
			$output .= "</td>
				              <td width=500>".
				                date("H:i",$show_news[time]) ."<font class=seconds>:". date("s",$show_news[time]) ."</font> ". date("d.m.Y",$show_news[time])
				              ."</td>
				            </tr>
				            <tr valign=top>";
				       
	if($forum != "y") {
		$output .= "<td>
				                <b>{$MESSAGES[MSG_REPORT]['m002']}</b>
				              </td>
				              <td>".
				                sonderz($topic)
				              ."</td>
				            </tr>";
	}			              
				            
			$output .= "
              <td>";
		if($forum != "y")
				$output .= "<b>{$MESSAGES[MSG_REPORT]['m003']}</b><br><br>";
				
		$output .= "</td>
              <td>";
              
    $reportToForum = false;

      if ($allowed)
        $color = "#00FF00";
      else
        $color = "#FF0000";

     
	switch($show_news[art])
	{
		case scan:
		case attack:
			
			$konti2 = explode(":", $show_news['attack_city']);
			$konti2 = "K". $konti2[0]; 
			
			$konti = explode(":", $show_news['defense_city']);
			$konti = "K". $konti[0];
			
			$collect_planes = sql_query("SELECT news_ber_.before, news_ber_.after, type_plane.name FROM news_ber_ INNER JOIN type_plane ON type_plane.type=news_ber_.type WHERE ID = '$show_news[id]' AND ad = 'attack'");
    		$collect_planes2 = sql_query("SELECT news_ber_.before, news_ber_.after, type_plane.name FROM news_ber_ INNER JOIN type_plane ON type_plane.type=news_ber_.type WHERE ID = '$show_news[id]' AND ad = 'defense'");
    		if($show_news[error] != "Settler") {
	    		$output .= "  <table width=100% border=0 cellpadding=0 cellspacing=0>
	                  			<tr>
	                    			<td colspan=3>";
	    		if($forum == "y") {
	    				$output .= "[b]{$MESSAGES[MSG_REPORT]['m004']}: $konti2";
	    				if($show_news[attackers_alliance] != '') { $output .= " (".$show_news[attackers_alliance].")"; }
	    				$output .= "[/b]";
	    		}else{
	    			$output .= "<b>{$MESSAGES[MSG_REPORT]['m004']}: $show_news[attack_city] | $show_news[attack_user]"; if($show_news[attackers_alliance] != '') { $output .= " (".$show_news[attackers_alliance].")"; } $output .= "</b>";
				}
	    			$output .= "</td>
	                 	 		</tr>";
	    		if($forum != "y") {
	                $output .= "<tr>
	                   				<td></td>
	                   				<td><u>{$MESSAGES[MSG_REPORT]['m005']}</u></td>
	                   				<td><u>{$MESSAGES[MSG_REPORT]['m006']}</u></td>
	               				</tr>";
	    		}
				while($fetch = sql_fetch_array($collect_planes))
				{ 
					if($fetch['after'] == 0 && $fetch['before'] != 0) {
						$percent = "(0%)";
					}else{
						$percent = $fetch['after'] / $fetch['before'] * 100;
						if($percent > 0) 
							$percent = "(" . round($percent) . "%)";
						else 
							$percent = "";
					}
					$output .= "<tr>
									<td>$fetch[name]</td>
									<td>$fetch[before]</td>
									<td>$fetch[after]</td>";
					if($forum == "y")
								$output .= "<td>$percent</td>";
								
					$output .= "</tr>";
				}
				$output .= "<tr>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td colspan=3>";
				if($forum == "y") {
						$output .= "[b]{$MESSAGES[MSG_REPORT]['m007']}: $konti";
						if($show_news[defenders_alliance] != '') { $output .= " (".$show_news[defenders_alliance].")"; }
						$output .= "[/b]";
				}else{
					$output .= "<b>{$MESSAGES[MSG_REPORT]['m007']}: $show_news[defense_city] | $show_news[defense_user]"; if($show_news[defenders_alliance] != '') { $output .= " (".$show_news[defenders_alliance].")"; } $output .= "</b>";			
				}
					$output .= "</td>
							</tr>";
	    			if($forum != "y") {
	                	$output .= "
							<tr>
								<td></td>
								<td><u>{$MESSAGES[MSG_REPORT]['m005']}</u></td>
 								<td><u>{$MESSAGES[MSG_REPORT]['m006']}</u></td>
							</tr>";
	    			}
				while($fetch = sql_fetch_array($collect_planes2))
				{
					if($fetch['after'] == 0 && $fetch['before'] != 0) {
						$percent2 = "(0%)";
					}else{
						$percent2 = $fetch['after'] / $fetch['before'] * 100;
						if($percent2 > 0) 
							$percent2 = "(" . round($percent2) . "%)";
						else 
							$percent2 = "";
					}
					$output .= "<tr>
									<td>$fetch[name]</td>
									<td>$fetch[before]</td>
									<td>$fetch[after]</td>";
					if($forum == "y")
								$output .= "<td>$percent2</td>";
								
					$output .= "</tr>";
				}
				if($forum == "y") 
					$show_news[points] = "ca. " . round($show_news[points], -2);
				
				$output .= "<tr>
								<td>{$MESSAGES[MSG_GENERAL][m007]}</td>
								<td>$show_news[points]</td>
								<td>-</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
							</tr>";
				if($show_news[plunder] == "Y") {
					if($show_news[art] == "attack") {
						if($forum == "y") {
							$output .= "<tr>
										<td>[b]{$MESSAGES[MSG_GENERAL][m006]}[/b]</td>
										</tr>";
						}else{
							$output .= "<tr>
									<td><u>{$MESSAGES[MSG_GENERAL][m006]}</u></td>
									</tr>";
						}
					}	
					
					$output .= "<tr>
								<td>{$MESSAGES[MSG_GENERAL][m000]}</td>
								<td>$show_news[iridium]</td>
							</tr>
							<tr>
								<td>{$MESSAGES[MSG_GENERAL][m001]}</td>
								<td>$show_news[holzium]</td>
							</tr>
							<tr>
								<td>{$MESSAGES[MSG_GENERAL][m002]}</td>
								<td>$show_news[water]</td>
							</tr>
							<tr>
								<td>{$MESSAGES[MSG_GENERAL][m003]}</td>
								<td>$show_news[oxygen]</td>
							</tr>";
					
				}
				if($show_news[f_name_show] == "Y") {
						$inhalt = strip_tags ($show_news['f_name']);
						$inhalt = htmlentities ($inhalt);
						$output .= "
								<tr>
									<td>&nbsp;</td>
								</tr>";
						if($forum == "y") {
							$output .= "<tr><td>[b]{$MESSAGES[MSG_REPORT]['m008']}[/b]</td></tr>";
						}
						$output .= "<tr>
									<td>" . $inhalt;
						
						if($forum == "y") {
								$output .= "[/quote]";
						}
						$output .= "</td>
								</tr>";
				}
    		}
			if($show_news[error] == "Settler")
				$output .= $topic;
				
			
			if($show_news[userprotection] == "Y") {
					$output .= "<tr>
									<td>
										{$MESSAGES[MSG_REPORT]['m013']}
									</td>
								</tr>";
			}
			break;
			
		case attack_back:
			$output .= "<table width=100% border=0 cellpadding=0 cellspacing=0>
                  			<tr>
                    			<td colspan=3>
                    				$topic. {$MESSAGES[MSG_REPORT]['m009']}:
                    			</td>
                    		</tr>
							<tr>
								<td>{$MESSAGES[MSG_GENERAL][m000]}</td>
								<td>$show_news[iridium]</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td>{$MESSAGES[MSG_GENERAL][m001]}</td>
								<td>$show_news[holzium]</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td>{$MESSAGES[MSG_GENERAL][m002]}</td>
								<td>$show_news[water]</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td>{$MESSAGES[MSG_GENERAL][m003]}</td>
								<td>$show_news[oxygen]</td>
								<td>&nbsp;</td>
							</tr>";
			
			break;
			
		case plane_buy:
		case plane_sell:
			$collect_planes = sql_query("SELECT news_ber_.before, type_plane.name FROM news_ber_ INNER JOIN type_plane ON type_plane.type=news_ber_.type WHERE ID = '$show_news[id]' AND ad = 'attack'");
    		$output .= "<table width=100% border=0 cellpadding=0 cellspacing=0>
							<tr>
								<td colspan=3>
									$topic
								</td>
							</tr>
							<tr>
								<td>
									&nbsp;
								</td>
							</tr>";
			while($fetch = sql_fetch_array($collect_planes))
			{
				$output .= "<tr>
								<td>$fetch[name]</td>
								<td>$fetch[before]</td>
							</tr>";
			}
		break;
		
		case transport:
			$collect_planes = sql_query("SELECT news_ber_.before, type_plane.name FROM news_ber_ INNER JOIN type_plane ON type_plane.type = news_ber_.type WHERE news_ber_.ID = '$show_news[id]'");
			$output .= "<table width=100% border=0 cellpadding=0 cellspacing=0>
                  			<tr>
							<td>$topic</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td><u>{$MESSAGES[MSG_GENERAL][m008]}</u></td>
						</tr>
						<tr>
							<td>{$MESSAGES[MSG_GENERAL][m000]}</td>
							<td>$show_news[iridium]</td>
						</tr>
						<tr>
							<td>{$MESSAGES[MSG_GENERAL][m001]}</td>
							<td>$show_news[holzium]</td>
						</tr>
						<tr>
							<td>{$MESSAGES[MSG_GENERAL][m002]}</td>
							<td>$show_news[water]</td>
						</tr>
						<tr>
							<td>{$MESSAGES[MSG_GENERAL][m003]}</td>
							<td>$show_news[oxygen]</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
						</tr>";
			if($show_news['error'] == "Hangar") {
					$output .= "<tr>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td>{$MESSAGES[MSG_GENERAL][m009]}</td>
								</tr>";
			}else{
					while($row = sql_fetch_array($collect_planes)) {
						if($row['before'] > 0) {
							$output .= "<tr>
											<td>
												$row[name]
											</td>
											<td>
												$row[before]
											</td>
										</tr>";
						}
					}
			}
			if($show_news[f_name_show] == "Y")
			{
				$inhalt = strip_tags ($show_news['f_name']);
				$inhalt = htmlentities ($inhalt);
				$output .= "
								<tr>
									<td>&nbsp;</td>
								</tr>";
				if($forum == "y") {
					$output .= "<tr><td colspan=3>[b]{$MESSAGES[MSG_REPORT]['m008']}[/b]</td></tr>";
				}
				$output .= "<tr>
									<td>" . $inhalt;
			
				if($forum == "y") {
					$output .= "[/quote]";
				}
				$output .= "</td>
								</tr>";
			}
		break;
			
		case transport_back:
			$output .="<table width=100% border=0 cellpadding=0 cellspacing=0>
                  			<tr>
							<td>$topic</td>
					   </tr>";
		break;
			
		case sell_to_depot:
			if($show_news[iridium] > 0) $bring = array($show_news[iridium], $MESSAGES[MSG_GENERAL]['m000']);
			if($show_news[holzium] > 0) $bring = array($show_news[holzium], $MESSAGES[MSG_GENERAL]['m001']);
			if($show_news[water] > 0) $bring = array($show_news[water], $MESSAGES[MSG_GENERAL]['m002']);
			if($show_news[oxygen] > 0) $bring = array($show_news[oxygen], $MESSAGES[MSG_GENERAL]['m003']);
			$output .="<table width=100% border=0 cellpadding=0 cellspacing=0>
                  			<tr>
							<td>$topic</td>
					   </tr>
					   <tr>
					   		<td>&nbsp;</td>
					   </tr>
					   <tr>
					   		<td>Sie überbrachte $bring[0] $bring[1]</td>
					   </tr>";
		break;
		
		case sell_from_depot:
			if($show_news[iridium] > 0) $bring = array($show_news[iridium], $MESSAGES[MSG_GENERAL]['m000']);
			if($show_news[holzium] > 0) $bring = array($show_news[holzium], $MESSAGES[MSG_GENERAL]['m001']);
			if($show_news[water] > 0) $bring = array($show_news[water], $MESSAGES[MSG_GENERAL]['m002']);
			if($show_news[oxygen] > 0) $bring = array($show_news[oxygen], $MESSAGES[MSG_GENERAL]['m003']);
			$output .="<table width=100% border=0 cellpadding=0 cellspacing=0>
                  			<tr>
							<td>$topic</td>
					   </tr>
					   <tr>
					   		<td>&nbsp;</td>
					   </tr>
					   <tr>
					   		<td>Sie brachte $bring[0] $bring[1] mit</td>
					   </tr>";
		break;
			
	}
        
        
    $mailtext = $output . "</table>";
    $output .= "</table>
              </td>
          </tr>";
  	if($forum != "y") {
  		$output .= "
          <tr>
                  <td colspan=2 align=center>
                    <br>";
               
		if($show_news[art] == "scan") { //In Simulator eintragen.
			$output .= "	<a href=\"$dir/simulation.php?bid=$_GET[bid]\" target=\"simulation\">{$MESSAGES[MSG_GENERAL][m014]}</a>
					</td>
				</tr>
				<tr align=center>
					<td colspan=2>";
		}
		
                $output .= "<br><br><form action=\"".$_SERVER['PHP_SELF']."?bid=".$_GET[bid]."\" method=POST>
                    <input class=button type=submit name=email value=\"{$MESSAGES[MSG_REPORT]['m010']}\">
                  </form>
                </td>
              </tr>
              <tr align=center>
                <td colspan=2>";
                
  		if($show_news['art'] == "attack" OR $show_news['art'] == "scan") {
  			if($show_news['attack_bid'] == $_GET['bid'])
  				$output .= "<a href=\"messages_berichte_xml.php?bid=".$_GET[bid]."&xmlid=".$show_news['attack_xmlid']."\">Als XML ansehen</a> | ";
            else 
            	$output .= "<a href=\"messages_berichte_xml.php?bid=".$_GET[bid]."&xmlid=".$show_news['defense_xmlid']."\">Als XML ansehen</a> | ";
  		}
  		    
             $output .= "<a href=\"messages_berichte.php?bid=".$_GET[bid]."&forum=Y\">{$MESSAGES[MSG_REPORT]['m011']}</a> | <a href=\"javascript:window.close()\">{$MESSAGES[MSG_REPORT]['m012']}</a>
                </td>
              </tr>";
  	}
  }
  else
    $output .= "      <tr>
                  <td align=center>
                    <font class=error>{$MESSAGES[MSG_REPORT]['e000']}</font>
                  </td>
                </tr>";

  $output .= "        </table>";
  if($forum == "y" && $show_news[f_name_show] != "Y") {
  	$output .= "[/quote]";
  }
  $output .= "</td>
            </tr>
            </table></div>
            </body>
            </html>";

  $mailtext .= "        </table>
              </td>
            </tr>
            </table>
            </div>
            </body>
            </html>";

  echo $output;

  if ($_POST[email])
  {
    $get_email_adress = sql_query("SELECT email FROM userdata WHERE user='$_SESSION[sitter]'");
    $email_adress = sql_fetch_array($get_email_adress);

    smtp_mail($email_adress[email],"ETS-{$MESSAGES[MSG_REPORT]['m000']}",$mailtext);
  }
  
  }
  else
  {
  	echo $MESSAGES[MSG_REPORT]['e001'];
  }

?>
