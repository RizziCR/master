<?php
// Anzeige Aktivitätsstatistik

session_start();

// Include des DB Zugriffs (config.php) und des HTML Begins (head, start body -> html_head.php);
include("config.php");
include("html_head.php");

$select_activity_last_24 = mysql_query("SELECT * FROM activity_stats ORDER BY `time` DESC LIMIT 0,24");

// Anzeige aktuelle Statistik und vor 24 Stunden
$x = 0;
while($row = mysql_fetch_array($select_activity_last_24)) {
	
	$time[$x] = $row['time'];
	$now[$x] = $row['on_now'];
	$last_hour[$x] = $row['on_lasthour'];
	$last_day[$x] = $row['on_lastday'];
	$total_accs[$x] = $row['total_accounts'];
	
	$x++;
}
// 0 = Aktuell
// 23 = Vor 24 Stunden
if($now[0] > $now[23]) {
	$sum = $now[0] - $now[23];
	$differenz_now = "<font color='green'>+$sum (</font>";
	$temp = $now[0] / $now[23] * 100;
	$temp = number_format($temp, 2, ',', '.');
	$prozent_now = "<font color='green'>+$temp%)</font>";
}else{
	$sum = $now[0] - $now[23];
	$differenz_now = "<font color='red'>$sum (</font>";
	$temp = $now[0] / $now[23] * 100;
	$temp2 = 100 - $temp;
	$temp2 = number_format($temp2, 2, ',', '.');
	$prozent_now = "<font color='red'>-$temp2%)</font>";
}

if($last_hour[0] > $last_hour[23]) {
	$sum = $last_hour[0] - $last_hour[23];
	$differenz_last_hour = "<font color='green'>+$sum (</font>";
	$temp = $last_hour[0] / $last_hour[23] * 100;
	$temp = number_format($temp, 2, ',', '.');
	$prozent_last_hour = "<font color='green'>+$temp%)</font>";
}else{
	$sum = $last_hour[0] - $last_hour[23];
	$differenz_last_hour = "<font color='red'>$sum (</font>";
	$temp = $last_hour[0] / $last_hour[23] * 100;
	$temp2 = 100 - $temp;
	$temp2 = number_format($temp2, 2, ',', '.');
	$prozent_last_hour = "<font color='red'>-$temp2%)</font>";
}

if($last_day[0] > $last_day[23]) {
	$sum = $last_day[0] - $last_day[23];
	$differenz_last_day = "<font color='green'>+$sum (</font>";
	$temp = $last_day[0] / $last_day[23] * 100;
	$temp = number_format($temp, 2, ',', '.');
	$prozent_last_day = "<font color='green'>+$temp%)</font>";
}else{
	$sum = $last_day[0] - $last_day[23];
	$differenz_last_day = "<font color='red'>$sum (</font>";
	$temp = $last_day[0] / $last_day[23] * 100;
	$temp2 = 100 - $temp;
	$temp2 = number_format($temp2, 2, ',', '.');
	$prozent_last_day = "<font color='red'>-$temp2%)</font>";
}

if($total_accs[0] > $total_accs[23]) {
	$sum = $total_accs[0] - $total_accs[23];
	$differenz_total_accs = "<font color='green'>+$sum (</font>";
	$temp = $total_accs[0] / $total_accs[23] * 100;
	$temp = number_format($temp, 2, ',', '.');
	$prozent_total_accs = "<font color='green'>+$temp%)</font>";
}else{
	$sum = $total_accs[0] - $total_accs[23];
	$differenz_total_accs = "<font color='red'>$sum (</font>";
	$temp = $total_accs[0] / $total_accs[23] * 100;
	$temp2 = 100 - $temp;
	$temp2 = number_format($temp2, 2, ',', '.');
	$prozent_total_accs = "<font color='red'>-$temp2%)</font>";
}

echo "<div align='center'>
		<table border=0>
			<tr>
				<td>
					Jetzt Online
				</td><td>
					Letzte Stunde
				</td><td>
					Letzter Tag
				</td><td>
					Anzahl Accounts
				</td>
			</tr><tr>
				<td>
					$now[0]
				</td><td>
					$last_hour[0]
				</td><td>
					$last_day[0]
				</td><td>
					$total_accs[0]
				</td>
			</tr><tr>
				<td>
					$differenz_now $prozent_now
				</td><td>
					$differenz_last_hour $prozent_last_hour
				</td><td>
					$differenz_last_day $prozent_last_day
				</td><td>
					$differenz_total_accs $prozent_total_accs
				</td>
			</tr>
		</table>
		<br><br><br>";
		// Ausgabe kompletter geladener Informationen
		echo "<table border=1>
				<tr>
					<td colspan=5>
						Tabelle Aktivitätsentwicklung
					</td>
				</tr><tr>
					<td>
						Uhrzeit
					</td><td>
						Aktuell Online
					</td><td>
						Letzte Stunde
					</td><td>
						Letzter Tag
					</td><td>
						Anzahl Accounts
					</td>
				</tr>";

		for($y=0;$y<24;$y++) {
			echo "<tr>
					<td>
						".date("H:i:s d.m.Y",$time[$y])."
					</td><td>
						$now[$y]
					</td><td>
						$last_hour[$y]
					</td><td>
						$last_day[$y]
					</td><td>
						$total_accs[$y]
					</td>
				</tr>";
		}
			// First close inner table, then outer table
		echo "</table> 
		</td>
	</tr>
</table>
</div>";

// Navigation
include("html_end.php");

?>