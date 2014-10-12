<?php

session_start();

// Include des DB Zugriffs (config.php) und des HTML Begins (head, start body -> html_head.php);
include("config.php");
include("html_head.php");

echo "<table border=1>
		<tr>
			<td width=25% valign=top>
				<a href='global_logs.php?what=accdelete'>Accountlöschungen</a><br>
				<a href='global_logs.php?what=towndelete'>Stadtlöschungen</a><br>
				<a href='global_logs.php?what=register'>Registrierung</a><br>
				<a href='global_logs.php?what=accountagain'>Account erneuert</a><br>
				<a href='global_logs.php?what=koords'>Koordinaten ersetzt</a><br>
				<a href='global_logs.php?what=medals'>Medaillen</a>";

if($_GET['what'] == "medals") {
	echo "  <br><br>- Angriff/Flugzeuge:<br>
			<a href='global_logs.php?what=medals&what_medals=settler'>--- Settler</a><br>
			<a href='global_logs.php?what=medals&what_medals=scare'>--- Scare</a><br>
			<a href='global_logs.php?what=medals&what_medals=attack'>--- Attack</a><br>
			<a href='global_logs.php?what=medals&what_medals=verteidiger2'>--- Verteidiger</a><br>
			<a href='global_logs.php?what=medals&what_medals=fleet'>--- Fleet</a><br>
			<a href='global_logs.php?what=medals&what_medals=plunder'>--- Plünderung</a><br>
			<br>- Forschungen:<br>
			<a href='global_logs.php?what=medals&what_medals=bbt'>--- BBT</a><br>
			<a href='global_logs.php?what=medals&what_medals=wk'>--- WK</a><br>
			<a href='global_logs.php?what=medals&what_medals=gear'>--- Antriebe</a><br>
			<a href='global_logs.php?what=medals&what_medals=weapon'>--- Waffen</a><br>
			<br>- Gebäude:<br>
			<a href='global_logs.php?what=medals&what_medals=bz'>--- BZ</a><br>
			<a href='global_logs.php?what=medals&what_medals=vz'>--- VZ</a><br>
			<br>- Allgemein:<br>
			<a href='global_logs.php?what=medals&what_medals=punkte'>--- Punkte</a><br>
			<a href='global_logs.php?what=medals&what_medals=tech'>--- Technologiepunkte</a><br>
			<a href='global_logs.php?what=medals&what_medals=trade'>--- Handel</a><br>";
}

echo "</td><td>";



switch($_GET['what']) {
	
	case "accdelete":
		$search = "Accountlöschungen";
		break;
		
	case "towndelete":
		$search = "Stadtlöschung";
		break;
		
	case "register":
		$search = "REGISTRIERUNG";
		break;
	
	case "accountagain":
		$search = "ACCOUNT ERNEUERT";
		break;
	
	case "koords":
		$search = "[TRANSPORT]";
		break;
	
	case "medals":	
	
		switch ($_GET['what_medals']) {
			
			case "settler":
					$search = "Settler";
					break;
			
			case "scare":
					$search = "Scare";
					break;
				
			case "attack":
					$search = "Attack";
					break;
			
			case "verteidiger2":
					$search = "Verteidiger2";
					break;
			
			case "fleet":
					$search = "Fleet";
					break;
				
			case "plunder":
					$search = "Plunder";
					break;
				
			case "bbt":
					$search = "BBT";
					break;
					
			case "wk":
					$search = "WK";
					break;
					
			case "gear":
					$search = "GEAR";
					break;
					
			case "weapon":
					$search = "WEAPON";
					break;
					
			case "bz":
					$search = "BZ";
					break;
					
			case "vz":
					$search = "VZ";
					break;
					
			case "punkte":
					$search = "Punktem";
					break;
					
			case "tech":
					$search = "Techm";
					break;
					
			case "trade":
					$search = "Trade";
					break;
			
		} // Innerer Switch
		break;
	
} // Outer Switch




$load_global_logs = mysql_query("SELECT * FROM global_logs WHERE inhalt LIKE '%$search%' ORDER BY `datum` DESC");

echo "<table border=1>
					<tr>
						<td>
							Seite
						</td><td>
							Inhalt
						</td><td>
							Datum
						</td>
					</tr>";

while($show_logs = mysql_fetch_array($load_global_logs)) {
		
	echo "<tr>
			<td>
				$show_logs[seite]
			</td><td>
				$show_logs[inhalt]
			</td><td>
				". date("H:i:s d.m.Y",$show_logs['datum']) . "
			</td>
		</tr>";

}





echo "</table>
		</td>
			</tr>
				</table>";









include("html_end.php");

?>