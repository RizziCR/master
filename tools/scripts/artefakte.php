<?php

include_once("../session.php");
include_once("../htmlheader.php");
require_once("database.php");
require_once("functions.php");

if($_POST['dauer']) {
	
	$duration = $_POST['dauer']*3600;
	$start = mktime($_POST['hour'], "00", "00", $_POST['month'], $_POST['day']);
	if($start < time()) $start=$start+24*3600*366;
	if($_POST[koth] == 1) 
		$koth = "Y";
	else
		$koth = "N";
		
	$insert = "INSERT INTO artefakte (start, duration, last_take, kw1, kw2, hoped_fleets, real_fleets, koords, started, iridium, holzium, wasser, sauerstoff, lager, tank, hangar, flughafen, bz, tz, kz, hz, vz, koth) VALUES 
					('$start', '$duration', '0', ". addslashes(htmlspecialchars($_POST[kw],ENT_QUOTES)) ."', '". addslashes(htmlspecialchars($_POST[kw],ENT_QUOTES)) ."', '" . addslashes(htmlspecialchars($_POST[x],ENT_QUOTES)) . "', '0', '0:0:0', 'not', 
					'" . addslashes(htmlspecialchars($_POST[iridium],ENT_QUOTES)) . "', '" . addslashes(htmlspecialchars($_POST[holzium],ENT_QUOTES)) . "', '" . addslashes(htmlspecialchars($_POST[wasser],ENT_QUOTES)) . "', '" . addslashes(htmlspecialchars($_POST[sauerstoff],ENT_QUOTES)) . "', 
					'" . addslashes(htmlspecialchars($_POST[lager],ENT_QUOTES)) . "', '" . addslashes(htmlspecialchars($_POST[tank],ENT_QUOTES)) . "', '" . addslashes(htmlspecialchars($_POST[hangar],ENT_QUOTES)) . "', '" . addslashes(htmlspecialchars($_POST[flughafen],ENT_QUOTES)) . "', 
					'" . addslashes(htmlspecialchars($_POST[bz],ENT_QUOTES)) . "', '" . addslashes(htmlspecialchars($_POST[tz],ENT_QUOTES)) . "', '" . addslashes(htmlspecialchars($_POST[kz],ENT_QUOTES)) . "', '" . addslashes(htmlspecialchars($_POST[hz],ENT_QUOTES)) . "', '" . addslashes(htmlspecialchars($_POST[vz],ENT_QUOTES)) . "', '$koth')";
	sql_query($insert);
	echo $insert . "<br>";
	
	echo "<b>Event eingefügt.</b>";
	
	
}else{
	$select = sql_query("SELECT 1 FROM usarios");	
	$num_rows = sql_num_rows($select);
	$num_rows = ROUND($num_rows/2);
	$kw = 0;
	$select = "SELECT (( 350 + usarios.t_electronsequenzweapons *15 ) + ( 2000 + usarios.t_protonsequenzweapons *25 ) + ( 4000 + usarios.t_neutronsequenzweapons *40 )) * ( 5 * city.b_airport +3 * usarios.t_computer_management ) AS KW FROM city INNER JOIN usarios ON city.user = usarios.user ORDER BY `KW` DESC LIMIT 0 , $num_rows;";
	$select = sql_query($select);
	while($row = sql_fetch_array($select)) {
		$kw += $row['KW'];
	}	
	
	echo "<table border=1>
			<tr>
				<td>
					Startzeit
				</td><td>
					Dauer
				</td><td>
					Erwartete<br>Flotten
				</td><td>
					Real<br>Fleets
				</td><td>
					Koordinaten
				</td><td>
					Start KW
				</td><td>
					Aktueller KW
				</td></td>
					&nbsp;
				</td><td>
					Punkte
				</td><td>
					Iri
				</td><td>
					Holzi
				</td><td>
					Wasser
				</td><td>
					O2
				</td><td>
					Lager
				</td><td>
					Tank
				</td><td>
					Hangar
				</td><td>
					FH
				</td><td>
					BZ
				</td><td>
					TZ
				</td><td>
					KZ
				</td><td>
					HZ
				</td><td>
					VZ
				</td>
			</tr>";
	$asteroids = sql_query("SELECT * FROM artefakte ORDER BY `start`");
	while($row = sql_fetch_array($asteroids)) {
		
		$points = $row['iridium']+$row['holzium']+$row['wasser']+$row['sauerstoff']+$row['lager']+$row['tank']+$row['hangar']+$row['flughafen']+$row['bz']+$row['tz']+$row['kz']+$row['hz']+$row['vz'];
		
		$end = $row[start] + $row[duration];
		echo $end . " -- " . time();
		if($row['started'] == "ended")
			$win = "<font color='green'>Beendet</font>";	
			
		if($row['started'] == "fail")
			$win = "<font color='red'>Gescheitert</font>";
		
		if($row['started'] == "started") 
			$win = "<font color='orange'>Läuft</font>";
			
		if($row[start] > time() && $row['started'] == "not") 
			$win = "<font color='gray'>Geplant</font>";
			
		if($row[start] < time() && $row['started'] == "not")
			$win = "<font color='gray'>Wird gestartet</font>";
	
		$date = date("H:i d.m.Y",$row[start]); 
		
		$row[duration] = $row[duration]/60;
		$row[duration] = $row[duration]/60;
		$row[duration] = $row[duration] . " Stunden";
		echo "<tr>
					<td>
						$date
					</td><td>
						$row[duration]
					</td><td>
						$row[hoped_fleets]
					</td><td>
						$row[real_fleets]
					</td><td>
						$row[koords]
					</td><td>
						$row[kw1]
					</td><td>
						$row[kw2]
					</td><td>
						$win
					</td><td>
						$points
					</td><td>
						$row[iridium]
					</td><td>
						$row[holzium]
					</td><td>
						$row[wasser]
					</td><td>
						$row[sauerstoff]
					</td><td>
						$row[lager]
					</td><td>
						$row[tank]
					</td><td>
						$row[hangar]
					</td><td>
						$row[flughafen]
					</td><td>
						$row[bz]
					</td><td>
						$row[tz]
					</td><td>
						$row[kz]
					</td><td>
						$row[hz]
					</td><td>
						$row[vz]
					</td>";
	}
	echo "</table>";

}
	
		
echo "V0.02 - 06.01.2013

<h1><center>Artefakte</center></h1><br><br>
<form action='artefakte.php' method='post'>
<table border=0>
	<tr>
		<td>
			Dauer des Angriffs:
		</td>
		<td>
			<input type='radio' name='dauer' value='24'>24 Stunden<br>
			<input type='radio' name='dauer' value='36'>36 Stunden<br>
			<input type='radio' name='dauer' value='48'>48 Stunden<br>
			<input type='radio' name='dauer' value='60'>60 Stunden<br>
			<input type='radio' name='dauer' value='72'>72 Stunden<br>
		</td>
	</tr>
	<tr>
		<td rowspan='2'>
			Startzeit
		</td>
		<td>
			<select name='day' size='1'>";
				for($x=1;$x<32;$x++) {
					echo "<option>$x</option>";
				}
echo "		</select>
			<select name='month' size='1'>
				<option value='01'>Januar</option>
				<option value='02'>Februar</option>
				<option value='03'>März</option>
				<option value='04'>April</option>
				<option value='05'>Mai</option>
				<option value='06'>Juni</option>
				<option value='07'>Juli</option>
				<option value='08'>August</option>
				<option value='09'>September</option>
				<option value='10'>Oktober</option>
				<option value='11'>November</option>
				<option value='12'>Dezember</option>
			</select>
		</td>
	</tr>
	<tr>
		<td>
			<select name='hour' size='1'>
				<option value='01'>00:00</option>
				<option value='02'>01:00</option>
				<option value='03'>02:00</option>
				<option value='04'>03:00</option>
				<option value='05'>04:00</option>
				<option value='06'>05:00</option>
				<option value='07'>06:00</option>
				<option value='08'>07:00</option>
				<option value='09'>08:00</option>
				<option value='10'>09:00</option>
				<option value='11'>10:00</option>
				<option value='12'>11:00</option>
				<option value='13'>12:00</option>
				<option value='14'>13:00</option>
				<option value='15'>14:00</option>
				<option value='16'>15:00</option>
				<option value='17'>16:00</option>
				<option value='18'>17:00</option>
				<option value='19'>18:00</option>
				<option value='20'>19:00</option>
				<option value='21'>20:00</option>
				<option value='22'>21:00</option>
				<option value='23'>22:00</option>
				<option value='24'>23:00</option>
				</select>
		</td>
	</tr>			
	<tr>
		<td>
			Erwartete Anzahl an Flotten (X)
		</td>
		<td>
			<input type='text' name='X' size='5' maxlength='5'>
		</td>
	</tr>
	<tr>
		<td>
			Erwarteter ArtefaktKW
		</td>
		<td>
			<input type='text' name='kw' size='15' maxlength='15' value='$kw'>
		</td>
	</tr>
	<tr>
		<td>
			Anzahl Crasher (Standdef):
		</td>
		<td>
			<input type='text' name='crasher' size='5' maxlength='5'>
		</td>
	</tr>
	<tr>
		<td>
			King-of-the-Hill
		</td>
		<td>
			<input type='checkbox' name='koth' value='1'>
		</td>
	</tr>
	<tr>
		<td>
			Iridium-Mine
		</td>
		<td>
			<input type='text' name='iridium' size='3' maxlength='4'>
		</td>
	</tr>
	<tr>
		<td>
			Holzium-Plantage
		</td>
		<td>
			<input type='text' name='holzium' size='3' maxlength='4'>
		</td>
	</tr>
	<tr>
		<td>
			Wasser-Bohrturm
		</td>
		<td>
			<input type='text' name='wasser' size='3' maxlength='4'>
		</td>
	</tr>
	<tr>
		<td>
			Sauerstoff-Reaktor
		</td>
		<td>
			<input type='text' name='sauerstoff' size='3' maxlength='4'>
		</td>
	</tr>
	<tr>
		<td>
			Lager
		</td>
		<td>
			<input type='text' name='lager' size='3' maxlength='4'>
		</td>
	</tr>
	<tr>
		<td>
			Tank
		</td>
		<td>
			<input type='text' name='tank' size='3' maxlength='4'>
		</td>
	</tr>
	<tr>
		<td>
			Hangar
		</td>
		<td>
			<input type='text' name='hangar' size='3' maxlength='4'>
		</td>
	</tr>
	<tr>
		<td>
			Flughafen
		</td>
		<td>
			<input type='text' name='flughafen' size='3' maxlength='4'>
		</td>
	</tr>
	<tr>
		<td>
			Bauzentrum
		</td>
		<td>
			<input type='text' name='bz' size='3' maxlength='4'>
		</td>
	</tr>
	<tr>
		<td>
			Technologiezentrum
		</td>
		<td>
			<input type='text' name='tz' size='3' maxlength='4'>
		</td>
	</tr>
	<tr>
		<td>
			Kommunikationszentrum
		</td>
		<td>
			<input type='text' name='kz' size='3' maxlength='4'>
		</td>
	</tr>
	<tr>
		<td>
			Handelszentrum
		</td>
		<td>
			<input type='text' name='hz' size='3' maxlength='4'>
		</td>
	</tr>
	<tr>
		<td>
			Verteidigungszentrum
		</td>
		<td>
			<input type='text' name='vz' size='3' maxlength='4'>
		</td>
	</tr>
	<tr>
		<td>
			&nbsp;
		</td><td>
			<input type='submit' value='Event erstellen'>
		</td>
		</tr>
</table>
</form>";





?>