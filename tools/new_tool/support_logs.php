<?php

session_start();

// Include des DB Zugriffs (config.php) und des HTML Begins (head, start body -> html_head.php);
include("config.php");
include("html_head.php");

	// Seitenummer definieren
	if($_GET[page]) $page = $_GET[page]; else $page=0;
	// Link zur vorherigen Seite
	if($page-1 >= 0) {
		if($_GET[sort]) $linkback .= "sort=".$_GET[sort]."&";
		if($_GET[value]) $linkback .= "value=".$_GET[value]."&";
		$last = $page-1;
		$lastpage = "<a href='support_logs.php?".$linkback."page=".$last."'>Vorherige Seite</a>";
	}
	
	// Vorbereitende Variablen
	$limit = 50;
	$from = mysql_real_escape_string($page) * $limit;
	
echo		"<h2>Log der letzten <b>".$limit."</b> Support-Aktionen</h2>
			<table align='center' width='98%' border=1 cellspacing='0' cellpadding='0'>
				<tr>
					<td colspan='5'>
					<table width='95%' border=0>
						<tr>
							<td width='10%'><a href='support_logs.php?sort=all'>Alle</a></td>
							<td width='18%'><a href='support_logs.php?sort=action'>Aktionen</a></td>
							<td width='18%'><a href='support_logs.php?sort=user'>Spieler</a></td>
							<td width='18%'><a href='support_logs.php?sort=city'>Stadt</a></td>
							<td width='18%'><a href='support_logs.php?sort=supporter'>Supporter</a></td>
							<td width='18%'><a href='support_logs.php?sort=date&order=asc'>&uarr;</a> Datum <a href='support_logs.php?sort=date&order=asc'>&darr;</a></td>
						</tr>
					</table>
					</td>
				</tr>";
		switch($_GET[sort]){
			case all:
			$result = mysql_query("SELECT * FROM logs_support ORDER BY timestamp DESC LIMIT ". $from .",". $limit."");	
			echo "<tr>
				<td colspan='5'>
				</td>
				</tr>";
				break;
			
			case action:
				if($_GET[value]){
					$result = mysql_query("SELECT * FROM logs_support WHERE action = '" .mysql_real_escape_string($_GET[value]) ."' ORDER BY timestamp DESC LIMIT ". $from .",". $limit."");	
				}
				$sup_query = mysql_query("SELECT DISTINCT action FROM logs_support WHERE action IS NOT NULL");
				echo "<tr>
					<td colspan='5'>";
					while ($sup_result = mysql_fetch_array($sup_query)){
					echo "<a href='support_logs.php?sort=action&value=".$sup_result[action]."&order=timestamp'>|  ".$sup_result[action]."  |</a>";
					}
					echo "</td>
					</tr>";
				break;
			case user:
				if(!empty($_GET[value])){
					$result = mysql_query("SELECT * FROM logs_support WHERE target_user = '" .mysql_real_escape_string($_GET[value]) ."' ORDER BY timestamp DESC LIMIT ". $from .",". $limit."");	
				}
				$usr_query = mysql_query("SELECT DISTINCT target_user FROM logs_support WHERE target_user IS NOT NULL");
				echo "<tr>
					<td colspan='5'>
					<form method='get' action='support_logs.php'>
					<input type='hidden' name='sort' value='user'>
						<select name='value' style='background-color:#000000;'>";
						while ($usr_result = mysql_fetch_array($usr_query)){
						if($usr_result[target_user] == $_GET[value]) $selected = "selected"; else $selected = "";
						echo "<option value='".$usr_result[target_user]."' ".$selected.">".$usr_result[target_user]."</option>";
						}
					echo "</select>
					<input type=submit value='Los'>
					</form>
					</td>
					</tr>";
				break;
			case city:
				if(!empty($_GET[value])){
					$result = mysql_query("SELECT * FROM logs_support WHERE target_city = '" .mysql_real_escape_string($_GET[value]) ."' ORDER BY timestamp DESC LIMIT ". $from .",". $limit."");	
				}
				$cty_query = mysql_query("SELECT DISTINCT target_city FROM logs_support WHERE target_city IS NOT NULL");
				echo "<tr>
					<td colspan='5'>
					<form method='get' action='support_logs.php'>
					<input type='hidden' name='sort' value='city'>
						<select name='value' style='background-color:#000000;'>";
						while ($cty_result = mysql_fetch_array($cty_query)){
						if($cty_result[target_city] == $_GET[value]) $selected = "selected"; else $selected = "";
						echo "<option value='".$cty_result[target_city]."' ".$selected.">".$cty_result[target_city]."</option>";
						}
					echo "</select>
					<input type=submit value='Los'>
					</form>
					</td>
					</tr>";
				break;
			case supporter:
				if(!empty($_GET[value])){
					$result = mysql_query("SELECT * FROM logs_support WHERE supporter = '" .mysql_real_escape_string($_GET[value]) ."' ORDER BY timestamp DESC LIMIT ". $from .",". $limit."");	
				}
				$spt_query = mysql_query("SELECT DISTINCT supporter FROM logs_support WHERE supporter IS NOT NULL");
				echo "<tr>
					<td colspan='5'>
					<form method='get' action='support_logs.php'>
					<input type='hidden' name='sort' value='supporter'>
						<select name='value' style='background-color:#000000;'>";
						while ($spt_result = mysql_fetch_array($spt_query)){
						if($spt_result[supporter] == $_GET[value]) $selected = "selected"; else $selected = "";
						echo "<option value='".$spt_result[supporter]."' ".$selected.">".$spt_result[supporter]."</option>";
						}
					echo "</select>
					<input type=submit value='Los'>
					</form>
					</td>
					</tr>";
				break;		
			case date:
			echo "<tr>
				<td colspan='5'>date</td>
				</tr>";
			default:
			$result = mysql_query("SELECT * FROM logs_support ORDER BY timestamp DESC LIMIT ". $from .",". $limit."");	
		}
		
		
		
		echo	"<tr>
					<th align='left' width='20%'>Spieler</th>
					<th align='left' width='10%'>Aktion</th>
					<th align='left' width='30%'>Details</th>
					<th align='center' width='10%'>Datum</th>
					<th align='right' width='10%'>durch (Supporter)</th>
				</tr>";
			
			$i = 0;
			while($log = mysql_fetch_array($result)){
			
				// Ausgabe
				echo "<tr>
							<td valign='top' align='left'>".$log[target_user]."</td>
							<td valign='top' align='left'>$log[action]</td>
							<td valign='top' align='left'>$log[action_value]</td>
							<td valign='top' align='center'>".date("d.m.Y - H:i:s", $log[timestamp])."</td>
							<td valign='top' align='right'>".$log[supporter]."</td>
					</tr>";
				$i++;
			}
			
		// Link zur nächsten Seite
		if($i >= $limit){
			if($_GET[sort]) $link .= "sort=".$_GET[sort]."&";
			if($_GET[value]) $link .= "value=".$_GET[value]."&";
			$next = $page+1;
			$nextpage .= "<a href='support_logs.php?".$link."page=".$next."'>N&auml;chste Seite</a>";
		}

    echo "
		<tr>
			<td colspan='6'>
				<table width='100%'>
					<tr>
						<td align='left'>".$lastpage."</td>
						<td align='right'>".$nextpage."</td>
					</tr>
				</table>
			</td>
		</tr>
		</table>";



include("html_end.php");

?>