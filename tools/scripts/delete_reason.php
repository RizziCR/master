<?php
include_once("../session.php");
include_once("../htmlheader.php");
require_once("database.php");
require_once("functions.php");


$select = sql_query("SELECT * FROM delete_reason ORDER BY `ID` DESC");

echo "V0.02 05.01.2013<br><br>";
echo "<h1><center>L&ouml;schgrund</center></h1><br><br>";


$output .= "<table border=0>
	<tr>
		<td>
			<b>L&ouml;schgrund</b>
		</td><td>
			<b>Spieldauer</b>
		</td><td>
			<b>Bemerkung</b>
		</td>
	</tr>";
while($row = sql_fetch_array($select)) {
	
	if($row[reason1] == "ETS benötigt zuviel Zeit")
		$x[0]++;
	if($row[reason1] == "ETS ist in letzter Zeit zu langweilig")
		$x[1]++;
	if($row[reason1] == "ETS ist allgemein zu langweilig")
		$x[2]++;
	if($row[reason1] == "Ich fühle mich als Anfänger nicht gut aufgenommen und unwohl")
		$x[3]++;
	if($row[reason1] == "ETS ist nicht das Onlinespiel, das ich suche")
		$x[4]++;
	if($row[reason1] == "Sonstige")
		$x[5]++;
	if($row[reason1] == "Enthaltung")
		$x[6]++;
		
	if($row[reason2] == "Weniger als 4 Wochen")
		$x2[0]++;
	if($row[reason2] == "Dies war meine erste Runde")
		$x2[1]++;
	if($row[reason2] == "Ich habe letzte Runde begonnen")
		$x2[2]++;
	if($row[reason2] == "Seit mehr als 2 Runden")
		$x2[3]++;
	if($row[reason2] == "Seit Ewigkeiten")
		$x2[4]++;
	if($row[reason2] == "Enthaltung")
		$x2[5]++;
	
	$output .= "<tr>
			<td>
				$row[reason1]
			</td><td>
				$row[reason2]
			</td><td>
				$row[reason3]
			</td>
		</tr>";
}

$output .= "</table>";

echo "<table border=0>
			<tr>
				<td>
					<b>Grund:</b>
				</td><td>
					<b>Anzahl:</b>
			</tr><tr>
				<td>
					ETS benötigt zuviel Zeit
				</td><td>
					$x[0]
				</td>
			</tr><tr>
				<td>
					ETS ist in letzter Zeit zu langweilig
				</td><td>
					$x[1]
				</td>
			</tr><tr>
				<td>
					ETS ist allgemein zu langweilig
				</td><td>
					$x[2]
				</td>
			</tr><tr>
				<td>
					Ich fühle mich als Anfänger nicht gut aufgenommen und unwohl
				</td><td>
					$x[3]
				</td>
			</tr><tr>
				<td>
					ETS ist nicht das Onlinespiel, das ich suche
				</td><td>
					$x[4]
				</td>
			</tr><tr>
				<td>
					Sonstige
				</td><td>
					$x[5]
				</td>
			</tr><tr>
				<td>
					Enthaltung
				</td><td>
					$x[6]
				</td>
			</tr><tr>
					<td>
						&nbsp;
					</td>
			</tr><tr>
				<td>
					Weniger als 4 Wochen
				</td><td>
					$x2[0]
				</td>
			</tr><tr>
				<td>
					Dies war meine erste Runde
				</td><td>
					$x2[1]
				</td>
			</tr><tr>
				<td>
					Ich habe letzte Runde begonnen
				</td><td>
					$x2[2]
				</td>
			</tr><tr>
				<td>
					Seit mehr als 2 Runden
				</td><td>
					$x2[3]
				</td>
			</tr><tr>
				<td>
					Seit Ewigkeiten
				</td><td>
					$x2[4]
				</td>
			</tr><tr>
				<td>
					Enthaltung
				</td><td>
					$x2[5]
				</td>
			</tr>
	</table><br><br>
			$output";
			
?>