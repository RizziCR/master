<?php
include_once("../session.php");
include_once("../htmlheader.php");
require_once("database.php");
require_once("functions.php");
	
	// Seitenummer definieren
	if($_GET[page]) $page = $_GET[page]; else $page=0;
	// Link zur vorherigen Seite
	if($page-1 >= 0) {
		if($_GET[sort]) $linkback .= "sort=".$_GET[sort]."&";
		if($_GET[value]) $linkback .= "value=".$_GET[value]."&";
		$last = $page-1;
		$lastpage = "<a href=\"support_logs.php?".$linkback."page=".$last."\">Vorherige Seite</a>";
	}
	
	// Vorbereitende Variablen
	$limit = 15;
	$from = addslashes($page) * $limit;
	
echo		"<h2>Log der letzten <b>".$limit."</b> Support-Aktionen</h2>
			<table align=\"center\" width=\"98%\" border=0 cellspacing=\"0\" cellpadding=\"0\">
				<tr>
					<td colspan=\"5\">
					<table width=\"95%\" border=0>
						<tr>
							<td width=\"10%\"><a href=\"support_logs.php?sort=all\">Alle</a></td>
							<td width=\"18%\"><a href=\"support_logs.php?sort=action\">Aktionen</a></td>
							<td width=\"18%\"><a href=\"support_logs.php?sort=user\">Spieler</a></td>
							<td width=\"18%\"><a href=\"support_logs.php?sort=city\">Stadt</a></td>
							<td width=\"18%\"><a href=\"support_logs.php?sort=supporter\">Supporter</a></td>
							<td width=\"18%\"><a href=\"support_logs.php?sort=date&order=asc\">&uarr;</a> Datum <a href=\"support_logs.php?sort=date&order=asc\">&darr;</a></td>
						</tr>
					</table>
					</td>
				</tr>";
		switch($_GET[sort]){
			case all:
			$result = sql_query("SELECT * FROM logs_support ORDER BY timestamp DESC LIMIT ". $from .",". $limit."");	
			echo "<tr>
				<td colspan=\"5\">
				</td>
				</tr>";
				break;
			
			case action:
				if($_GET[value]){
					$result = sql_query("SELECT * FROM logs_support WHERE action = '" .addslashes($_GET[value]) ."' ORDER BY timestamp DESC LIMIT ". $from .",". $limit."");	
				}
				$sup_query = sql_query("SELECT DISTINCT action FROM logs_support WHERE action IS NOT NULL");
				echo "<tr>
					<td colspan=\"5\">";
					while ($sup_result = sql_fetch_array($sup_query)){
					echo "<a href=\"support_logs.php?sort=action&value=".$sup_result[action]."&order=timestamp\">|  ".$sup_result[action]."  |</a>";
					}
					echo "</td>
					</tr>";
				break;
			case user:
				if(!empty($_GET[value])){
					$result = sql_query("SELECT * FROM logs_support WHERE target_user = '" .addslashes($_GET[value]) ."' ORDER BY timestamp DESC LIMIT ". $from .",". $limit."");	
				}
				$usr_query = sql_query("SELECT DISTINCT target_user FROM logs_support WHERE target_user IS NOT NULL");
				echo "<tr>
					<td colspan=\"5\">
					<form method=\"get\" action=\"support_logs.php\">
					<input type=\"hidden\" name=\"sort\" value=\"user\">
						<select name=\"value\" style=\"background-color:#000000;\">";
						while ($usr_result = sql_fetch_array($usr_query)){
						if($usr_result[target_user] == $_GET[value]) $selected = "selected"; else $selected = "";
						echo "<option value=\"".$usr_result[target_user]."\" ".$selected.">".$usr_result[target_user]."</option>";
						}
					echo "</select>
					<input type=submit value=\"Los\">
					</form>
					</td>
					</tr>";
				break;
			case city:
				if(!empty($_GET[value])){
					$result = sql_query("SELECT * FROM logs_support WHERE target_city = '" .addslashes($_GET[value]) ."' ORDER BY timestamp DESC LIMIT ". $from .",". $limit."");	
				}
				$cty_query = sql_query("SELECT DISTINCT target_city FROM logs_support WHERE target_city IS NOT NULL");
				echo "<tr>
					<td colspan=\"5\">
					<form method=\"get\" action=\"support_logs.php\">
					<input type=\"hidden\" name=\"sort\" value=\"city\">
						<select name=\"value\" style=\"background-color:#000000;\">";
						while ($cty_result = sql_fetch_array($cty_query)){
						if($cty_result[target_city] == $_GET[value]) $selected = "selected"; else $selected = "";
						echo "<option value=\"".$cty_result[target_city]."\" ".$selected.">".$cty_result[target_city]."</option>";
						}
					echo "</select>
					<input type=submit value=\"Los\">
					</form>
					</td>
					</tr>";
				break;
			case supporter:
				if(!empty($_GET[value])){
					$result = sql_query("SELECT * FROM logs_support WHERE supporter = '" .addslashes($_GET[value]) ."' ORDER BY timestamp DESC LIMIT ". $from .",". $limit."");	
				}
				$spt_query = sql_query("SELECT DISTINCT supporter FROM logs_support WHERE supporter IS NOT NULL");
				echo "<tr>
					<td colspan=\"5\">
					<form method=\"get\" action=\"support_logs.php\">
					<input type=\"hidden\" name=\"sort\" value=\"supporter\">
						<select name=\"value\" style=\"background-color:#000000;\">";
						while ($spt_result = sql_fetch_array($spt_query)){
						if($spt_result[supporter] == $_GET[value]) $selected = "selected"; else $selected = "";
						echo "<option value=\"".$spt_result[supporter]."\" ".$selected.">".$spt_result[supporter]."</option>";
						}
					echo "</select>
					<input type=submit value=\"Los\">
					</form>
					</td>
					</tr>";
				break;		
			case date:
			echo "<tr>
				<td colspan=\"5\">date</td>
				</tr>";
			default:
			$result = sql_query("SELECT * FROM logs_support ORDER BY timestamp DESC LIMIT ". $from .",". $limit."");	
		}
		echo	"<tr>
					<th align=\"left\" width=\"15%\">Spieler</th>
					<th align=\"left\" width=\"10%\">Aktion</th>
					<th align=\"left\" width=\"15%\">Stadt / User</th>
					<th align=\"center\" width=\"35%\">Strafe</th>
					<th align=\"center\" width=\"15%\">Datum</th>
					<th align=\"right\" width=\"10%\">durch (Supporter)</th>
				</tr>";
			$i = 0;
			while($log = sql_fetch_array($result)){
			// Tabellenfarben definieren (class)
			$support_gutschriften = array("Gutschrift absolut");
			$support_strafen = array("Abzug absolut", "Abzug Account (%)", "Benutzerstrafe", "Sperre -> 24h", "Sperre -> 48h", "Sperre -> 7d" );
			$support_gemischt = array("Abzug & Gutschrift");
			$support_sonstiges = array("Datenkorrektur", "Freischaltung", "System", "Supporter", "Voranmeldung");
			$class = $i % 2;
			if(in_array($log[action], $support_gutschriften)) $class = "class=\"row_".$class."_gutschrift\"";
			if(in_array($log[action], $support_strafen)) $class = "class=\"row_".$class."_abzug\"";
			if(in_array($log[action], $support_gemischt)) $class = "class=\"row_".$class."_gemischt\"";
			if(in_array($log[action], $support_sonstiges)){
				$class1 = "class=\"row_".$class."_abzug\"";
				$class2 = "class=\"row_".$class."_gutschrift\"";
				$class = "class=\"row_".$class."_neutral\"";
			}
			//if(!empty($log[target_city])) $target = "Stadt -> ".$log[target_city].""; else $target = "User -> ".$log[target_user]."";
			if(!empty($log[target_city])) $target = "Stadt -> ".$log[target_city].""; else $target = $log[target_user];
			// Prozentualer Abzug
			if($log[r_account_prozent] != 0){
				$strafe = "<b>Account (%): ".number_format($log[r_account_prozent],0,',','.')." %</b>";
			}
			// Umbenennung eines Siedlers bzw Änderung der Mailadresse:
			else if($log[action] == "Datenkorrektur"){
				$data_change = explode(" ",$log[action_value]);
				switch($data_change[0]){
					case "Name:":
						$strafe = "<span ".$class1.">".$data_change[1]."</span><span ".$class.">  hei&szlig;t nun </span><span ".$class2.">".$data_change[3]."</span>";
						break;
					case "Email:":
						$strafe = "<span ".$class1.">".$data_change[1]."</span><span ".$class."> ge&auml;ndert in </span><span ".$class2.">".$data_change[3]."</span>";
						break;
				}
			}
			else if($log[action] == "Freischaltung"){
				$strafe = $log[action_value];
			}
			else if(substr($log[action_value],0,21)  == "Benutzer gesperrt bis"){
				$strafe = $log[action_value];
			}
			// Änderungen am System (vote.xml löschen o.ä.)
			else if($log[action] == "System"){
				$strafe = "<span ".$class.">".$log[action_value]."</span>";
			}
			// Änderungen der Supporter
			else if($log[action] == "Supporter"){
				if(substr($log[action_value], -5) == "aktiv") $class = $class2;
				if(substr($log[action_value], -11) == "nicht aktiv") $class = $class1;
				$strafe = "<span ".$class.">".$log[action_value]."</span>";
			}
			// Änderungen am System (vote.xml löschen o.ä.)
			else if($log[action] == "Voranmeldung"){
				$strafe = "<span ".$class.">".$log[action_value]."</span>";
			}
			ELSE {
			// Strafen Rohstoffe
			if($log[r_iridium] != 0)	$rohstoffe .= "<tr><td ".$class.">Iri:</td><td ".$class.">".number_format($log[r_iridium],0,',','.')."</td></tr>";
			if($log[r_holzium] != 0)	$rohstoffe .= "<tr><td ".$class.">Holz:</td><td ".$class.">".number_format($log[r_holzium],0,',','.')."</td></tr>";
			if($log[r_water] != 0) $rohstoffe .= "<tr><td ".$class.">H20:</td><td ".$class.">".number_format($log[r_water],0,',','.')."</td></tr>";
			if($log[r_oxygen] != 0) $rohstoffe .= "<tr><td ".$class.">O2:</td><td ".$class.">".number_format($log[r_oxygen],0,',','.')."</td></tr>";
			// Strafen Antriebstechnologien
			if($log[r_oxidationsdrive] != 0)	$atechnologien .= "<tr><td ".$class.">Oxi:</td><td ".$class.">".$log[r_oxidationsdrive]."</td></tr>";
			if($log[r_hoverdrive] != 0)	$atechnologien .= "<tr><td ".$class.">Hover:</td><td ".$class.">".$log[r_hoverdrive]."</td></tr>";
			if($log[r_antigravitydrive] != 0)	$atechnologien .= "<tr><td ".$class.">Anti:</td><td ".$class.">".$log[r_antigravitydrive]."</td></tr>";
			// Strafen Waffentechnologien
			if($log[r_electronsequenzweapons] != 0)	$wtechnologien .= "<tr><td ".$class.">ESW:</td><td ".$class.">".$log[r_electronsequenzweapons]."</td></tr>";
			if($log[r_protonsequenzweapons] != 0)	$wtechnologien .= "<tr><td ".$class.">PSW:</td><td ".$class.">".$log[r_protonsequenzweapons]."</td></tr>";
			if($log[r_neutronsequenzweapons] != 0)	$wtechnologien .= "<tr><td ".$class.">NSW:</td><td ".$class.">".$log[r_neutronsequenzweapons]."</td></tr>";
			// Strafen Account
			if($log[r_res_buildings] != 0)	$account .= "<tr><td ".$class.">Ress-Geb.:</td><td ".$class.">".$log[r_res_buildings]."</td></tr>";
			if($log[r_work_board] != 0)	$account .= "<tr><td ".$class.">BZ:</td><td ".$class.">".$log[r_work_board]."</td></tr>";
			if($log[r_water_compression] != 0)	$account .= "<tr><td ".$class.">WK:</td><td ".$class.">".$log[r_water_compression]."</td></tr>";
			if($log[r_mining] != 0)	$account .= "<tr><td ".$class.">BBT:</td><td ".$class.">".$log[r_mining]."</td></tr>";
				
				$strafen = "<table width=\"95%\" align=\"center\" cellspacing=\"0\" cellpadding=\"0\">
							<tr>
								<td width=\"40%\" valign=\"top\">
									<table width=\"100%\" align=\"center\" cellspacing=\"0\" cellpadding=\"0\">
									".$rohstoffe."
									</table>
								</td>
								<td width=\"5%\">&nbsp;</td>
								<td width=\"15%\" valign=\"top\">
									<table width=\"100%\" align=\"center\" cellspacing=\"0\" cellpadding=\"0\">
									".$account."
									</table>
								</td>
								<td width=\"5%\">&nbsp;</td>
								<td width=\"15%\" valign=\"top\">
									<table width=\"100%\" align=\"center\" cellspacing=\"0\" cellpadding=\"0\">
									".$atechnologien."
									</table>
								</td>
								<td width=\"5%\">&nbsp;</td>
								<td width=\"15%\" valign=\"top\">
									<table width=\"100%\" align=\"center\" cellspacing=\"0\" cellpadding=\"0\">
									".$wtechnologien."
									</table>
								</td>
							</tr>
						</table>";
			$strafe = "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
			<tr>
			<td valign=\"top\" width=\"100%\">".$strafen."</td>
			</tr>
			</table>";
			}
			
			// Ausgabe
			echo "<tr>
				<td ".$class." valign=\"top\" align=\"left\">".$log[target_user]."</td>";
				if($log[action] == "IGM" || $log[action] == "Globale IGM"){
					$mail_ids = explode("_",$log[action_value]);
					$mail_id = $mail_ids[1];
					echo "<td ".$class." valign=\"top\" align=\"left\"><a href=\"igm_submit_logs.php?mail_id=".$mail_id."&recipient=".$log[action]."\">".$log[action]."</a></td>";
				}
				/*else if($log[action] == "Name ge&auml;ndert" || $log[action] == "eMail ge&auml;ndert")
				{
					$data_change = explode("->",$log[action_value]);
					echo "<td valign=\"top\" align=\"left\">".$data_change[0]." hei&szlig; nun ".$data_change[1]."</td>";
				}*/
				ELSE
				{
					echo "<td ".$class." valign=\"top\" align=\"left\">".$log[action]."</td>";
				}
				echo
				"<td ".$class." valign=\"top\" align=\"left\">".$target."</td>
				<td ".$class." valign=\"top\" align=\"left\">".$strafe."</td>
				<td ".$class." valign=\"top\" align=\"center\">".date("d.m.Y - H:i:s", $log[timestamp])."</td>
				<td ".$class." valign=\"top\" align=\"right\">".$log[supporter]."</td>
				</tr>";
			unset($rohstoffe);
			unset($account);
			unset($atechnologien);
			unset($wtechnologien);
			unset($gebaeude);
			unset($strafen);
			$i++;
			}
			
		// Link zur nächsten Seite
		if($i >= $limit){
			if($_GET[sort]) $link .= "sort=".$_GET[sort]."&";
			if($_GET[value]) $link .= "value=".$_GET[value]."&";
			$next = $page+1;
			$nextpage .= "<a href=\"support_logs.php?".$link."page=".$next."\">N&auml;chste Seite</a>";
		}

    echo "
		<tr>
			<td colspan=\"6\">
				<table width=\"100%\">
					<tr>
						<td align=\"left\">".$lastpage."</td>
						<td align=\"right\">".$nextpage."</td>
					</tr>
				</table>
			</td>
		</tr>
		</table>
	</body>
	</html>";
?>
