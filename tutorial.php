<?php

/////////////////////////////////////////////
// Neues Tutorialsystem für ETS 14
// Nicht abbrechbar !!!!!!
// Medaillen fürs Tutorial
// Das Tutorial spielt das Spiel im Schnelldurchlauf durch
// Erster Login, erste Erklärungen, erste Gebäude, Forschungen, Flugzeuge, Verteidigung, Angriffe, Kolonie, Takeabwehr, Takedurchführung 
// Danach alles auf Anfang setzen!
/////////////////////////////////////////////
// Zur neuen Runde immer auf 1 setzen bei allen !!!!!!!
/////////////////////////////////////////////
// Durchnummerierung des Tutorials:
// 1 = Start des Tutorials auf allen Seiten
// 2 = Zweite Seite des Tutorials - Was ist ETS?
// 3 = Dritte Seite - Erklärung Rohstoffe/Gebäude
// 4 = Vierte Seite - Erklärung Gebäude Fortsetzung
// 5 = Fünfte Seite - Erklärung Technologien
// 6 = Sechste Seite - Erklärung Hauptansicht
// 7 = Global - Erklärung Noobschutz / Bauzentrum erste Bauaufgabe
// 8 = Bauzentrum - Technologiezentrum bauen
// 9 = Global / Technologiezentrum - erste Technologien (BBT / WK)
// 10 = Verteidigungsanlagen bauen
// 11 = Settler bauen
// 12 = Kolo gründen
// 13 = Settler gestartet



// 14 = Kolo Verteidigen
// 15 = Flugtimer verwenden
// 16 = Kolo zurückerobern
// 17 = Ende des Tutorials - Verweis auf Allianzwichtigkeit, Kommunikation mit anderen Spielern etc. weiter #998


//
// 998 = Endnummerierung = Tutorial Beendet, Medaille ausgeben
// 999 = Endnummerierung = Tutorial Beendet, Medaille erhalten, alles fertig
/////////////////////////////////////////////
/*
$tut = sql_fetch_array(sql_query("SELECT tutorial FROM new_tutorial WHERE user = '$_SESSION[user]';"));

if($tut['tutorial'] == 1) {
	
	$out = 1;
	  if($_POST['page'] == "to2") {
	  	sql_query("UPDATE new_tutorial SET tutorial = 2 WHERE user = '$_SESSION[user]';");
	  	$tut['tutorial'] = 2;
	  }else{
	
		  $pfuschOutput .= "<br><br><br><br><br><br><br><br>
		  				<table border=1>
								<tr>
									<td>
										<div align='center'>
											<form action='/start.php' method='post'>
												<br><br>Herzlich Willkommen bei Escape to Space. Folge mir und ich erkläre dir die ersten Schritte.<br>
		  										Doch was ist Escape to Space?
												<input type='hidden' name='page' value='to2'>
												<br><br>
												<div align='right'>
													<input type='submit' value='Weiter'>
												</div><br><br>
											</form>
										</div>
									</td>
								</tr>
							</table><br><br><br><br><br><br><br><br>";  
		  
	  }
}
if($tut['tutorial'] == 2) {

	$out = 1;
	if($_POST['page'] == "to3") {
		sql_query("UPDATE new_tutorial SET tutorial = 3 WHERE user = '$_SESSION[user]';");
		$tut['tutorial'] = 3;
	}else{
	
		$pfuschOutput .= "<br><br><br><br><br><br><br><br><table border=1>
								<tr>
									<td>
										<div align='center'>
											<form action='/start.php' method='post'>
												<br><br>Escape to Space ist ein Browsergame ohne viel Grafik. Doch was ist Escape to Space?
												<br><br>
												<b>Bauen, Forschen, Kontakte knüpfen</b><br>
												Die alten Spieler spielen schon seit Jahren Escape to Space - wegen der Freundschaften.<br>
												Lern neue Leute kennen, bilde Feindbilder, schließ Freundschaften.<br><br>
												<b>Krieger, Händler, Wirtschaftler</b><br>
												Du entscheidest selbst wie du Escape to Space spielen möchtest!<br>
												Bist du Krieger? Dann sei Agressiv und zerstöre andere!<br>
												Bist du Händler? Dann bring dich und deinen Account voran durch kluge Flugzeugverkäufe oder Kreditgeschäfte.<br>
												Bist du Wirtschaftler? Dann werde zur größten Wirtschaftsmacht! Besitz eine Produktion wie kein anderer!<br><br>
												<b>Intrigen, Verschwörungen, Kriege</b><br>
												Wer hintergeht wen? Wo habe ich die größten Vorteile? Verteidige dich und deine Allianz! Oder greif alles und jeden an!<br><br>
												<input type='hidden' name='page' value='to3'>
												<br><br>
												<div align='right'>
													<input type='submit' value='Weiter'>
												</div><br><br>
											</form>
										</div>
									</td>
								</tr>
							</table><br><br><br><br><br><br><br><br>";
	
	}
}
if($tut['tutorial'] == 3) {

	$out = 1;
	if($_POST['page'] == "to4") {
		sql_query("UPDATE new_tutorial SET tutorial = 4 WHERE user = '$_SESSION[user]';");
		$tut['tutorial'] = 4;
	}else{

		$pfuschOutput .= "<br><br><br><br><br><br><br><br><table border=1>
								<tr>
									<td>
										<div align='center'>
											<form action='/start.php' method='post'>
												<br><br><b>Rohstoffe, Gebäude, Forschungen</b><br><br>
												In Escape to Space gibt es 4 verschiedene Rohstoffarten:<br>
												- Iridium<br>
												- Holzium<br>
												- Wasser<br>
												- Sauerstoff<br><br>
												Iridium wird für Gebäude, Verteidigungsanlagen und Flugzeuge als Hauptrohstoff benötigt.<br>
												Holzium wird für alles benötigt: Gebäude, Forschungen, Verteidigungsanlagen und Flugzeuge. Es ist der Nebenbestandteil.<br>
												Wasser wird nur zur Produktion von Sauerstoff benötigt und ist ansonsten ein Abfallprodukt.<br>
												Sauerstoff hingegen wird vorrangig für Forschungen aber auch als Treibstoff für Flugzeuge benötigt.<br>
												Ist die produzierte Menge Wasser kleiner als die Verbrauchte und ist kein Wasser im Lager vorhanden, wird die Sauerstoffproduktion gedrosselt.<br><br>
												<b>Gebäudearten</b><br><br>
												Die verschiedenen Gebäude sind eingeteilt in folgende Arten:<br>
												- Rohstoff Gebäude<br>
												- Lager<br>
												- Flugzeug Gebäude<br>
												- Zentren<br><br>
												Erstellte Gebäude wirken jeweils für diese Stadt während Technologien global für einen gesamten Account auf allen Städten wirken.<br>
												Ist die Produktion zu gering, muss zu lange gewartet werden um das nächste Gebäude zu bauen. Eine zu hohe Produktion gibt es nicht.<br>
												Mit jeder neuen Gebäudestufe steigt die Bauzeit. Um diese wieder auf die Grundbauzeit zu senken, sollte genügend <b>Bauzentrum</b> gebaut werden.<br>
												Die Bauzeit des <b>Bauzentrums</b> lässt sich hingegen nicht senken. 
												<input type='hidden' name='page' value='to4'>
												<br><br>
												<div align='right'>
													<input type='submit' value='Weiter'>
												</div><br><br>
											</form>
										</div>
									</td>
								</tr>
							</table><br><br><br><br><br><br><br><br>";

	}
}
if($tut['tutorial'] == 4) {

	$out = 1;
	if($_POST['page'] == "to5") {
		sql_query("UPDATE new_tutorial SET tutorial = 5 WHERE user = '$_SESSION[user]';");
		$tut['tutorial'] = 5;
	}else{
		
		$pfuschOutput .= "<br><br><br><br><br><br><br><br><table border=1>
								<tr>
									<td>
										<div align='center'>
											<form action='/start.php' method='post'>
												<br><br><b>Gebäude</b><br><br>
												Im Gebäude 'Lager' lagern Iridium, Holzium und Wasser; im 'Depot' Sauerstoff.
												Ist eines der Lager voll kann nichts mehr Produziert werden. Das heißt, du verlierst Rohstoffe.<br>
												Sorg dafür, dass du immer genug Platz im Lager und Depot hast. <br><br>
												<b>Zentren</b>
												Es gibt 5 verschiedene Zentren, jeweils mit einer anderen Eigenschaft:<br>
												- Bauzentrum<br>
												- Technologiezentrum<br>
												- Kommunikationszentrum<br>
												- Handelszentrum<br>
												- Verteidigungszentrum<br><br>
												Das Bauzentrum verkürzt, wie schon beschrieben, die Bauzeit der anderen Gebäude.<br>
												Das Technologiezentrum ist für die Verwaltung und Erforschung von Technologien zuständig. 
												Über das Kommunikationszentrum wird die Kommunikation aller Städte verwaltet. Die höhe des Kommunikationszentrums<br>
												bestimmt wieviele Städte du in deinem Account verwalten kannst.<br>
												Das Handelszentrum bietet die Möglichkeit den zentralen Handelsplatz zu betreten. Hier kannst du eine Sorte<br>
												Rohstoffe in eine andere tauschen. Aber auch Flugzeuge lassen sich hier verkaufen oder kaufen.<br>
												Das Verteidigungszentrum ist das Herz des Schutzes deines Stadt. Die Höhe des Verteidigungszentrums bestimmt die Anzahl<br>
												an Verteidigungsanlagen.<br><br>
												<b>Verteidigung</b><br><br>
												Hast du auf deiner Stadt nicht genügend Verteidigung können dir deine Rohstoffe geraubt werden.<br>
												Deine Hauptstadt, mit welcher du startest, kann niemand erobern. Deine 'Kolonien', die du später gründen wirst,<br>
												können dir komplett entwendet werden. Aber auch du kannst dein Imperium vergrößern mit Kolonien anderer Spieler.
												<input type='hidden' name='page' value='to5'>
												<br><br>
												<div align='right'>
													<input type='submit' value='Weiter'>
												</div><br><br>
											</form>
										</div>
									</td>
								</tr>
							</table><br><br><br><br><br><br><br><br>";

	}
}
if($tut['tutorial'] == 5) {

	$out = 1;
	if($_POST['page'] == "to6") {
		sql_query("UPDATE new_tutorial SET tutorial = 6 WHERE user = '$_SESSION[user]';");
		$tut['tutorial'] = 5;
	}else{
		
		$pfuschOutput .= "<br><br><br><br><br><br><br><br><table border=1>
								<tr>
									<td>
										<div align='center'>
											<form action='/start.php' method='post'>
												<br><br><b>Technologien</b><br><br>
												Technologien wirken sich auf den gesamten Account, sprich jede Stadt, aus.<br><br>
												Wie bei Gebäuden gibt es auch verschiedene Technologien:<br>
												- Geschwindigkeitssteigernde<br>
												- Kampfkraftsteigernde<br>
												- Flugzeug<br>
												- Produktionssteigernde<br><br>
												<b>Bergbautechnik</b> erhöht die Produktion von Iridium und Holzium um <b>5%</b> pro erforschte Stufe.
												<b>Wasserkompression</b> ist das Gegenstück zu Bergbautechnik und erhöht die Produktion von Sauerstoff um <b>5%</b> pro erforschter Stufe.<br>
												<b>Lagerverwaltung</b> erhöht die Lagerkapazität um 5% pro erforschter Stufe.<br><br>
												Zu beachten gilt das die zuwächse Exponeniell steigen. Von diesen Forschungen kann niemand genug haben.<br><br>
												<b>Waffentechnologien</b><br><br>
												Elektronen-, Protonen- und Neutronensequenzwaffen heißen die Waffentechnologien. Zur Freischaltung von Flugzeugen und Verteidigungsanlagen<br>
												brauch sie jeder Spieler. Jede Technologie hat andere Kampfwertzuwächse und erhöht für eine kleine Anzahl an Flugzeugen<br>
												den Kampfwert. Welches Flugzeug bei welcher Technologie einen Zuwachs erhält lässt sich in der Beschreibung entnehmen.<br><br>
												<b>Antriebstechnologien</b><br><br>
												Oxidations-, Hover- und Antigravitationsantrieb heißen die Antriebstechnologien. Ebenso wie Waffentechnologien werden diese<br>
												für die Freischaltung von Flugzeugen benötigt und steigern die Geschwindigkeit deiner Flugzeuge.<br><br>
												Du musst dich früh entscheiden was du spielen möchtest: Welche Technologie und somit welche Flugzeuggattung. Aber auch:<br>
												Möchtest du langsam aber stark oder schwächer aber schnell sein? Mehr Waffentechnologien und weniger Antriebe? Oder umgekehrt?<br>
												Die Entscheidung liegt bei dir! Das richtige Balancing ist der Schlüssel zum Erfolg.
												<input type='hidden' name='page' value='to6'>
												<br><br>
												<div align='right'>
													<input type='submit' value='Weiter'>
												</div><br><br>
											</form>
										</div>
									</td>
								</tr>
							</table><br><br><br><br><br><br><br><br>";

	}
}	
if($tut['tutorial'] == 6) {

	if($_POST['page'] == "to7") {
		sql_query("UPDATE new_tutorial SET tutorial = 7 WHERE user = '$_SESSION[user]';");
		$tut['tutorial'] = 7;
	}else{
		
		$out = 1;
		$pfuschOutput .= "<br><br><br><br><br><br><br><br><table border=1>
								<tr>
									<td>
										<div align='center'>
											<form action='/start.php' method='post'>
												<br><br><b>Erste Gebäude</b><br><br>
												Lass uns beginnen!<br><br>
												Nachfolgend landest du auf der Hauptansicht deiner Hauptstadt. Hier landest du nach jedem Login.<br>
												Die Hauptansicht bietet dir einen Überblick über deine Stadt und deinem Account.<br><br>
												Welches Gebäude baut? Wieviele Rohstoffe besitze ich noch? Wie ist die Auslastung des Hangars/Verteidigungszentrums?<br>
												Gibt es Angriffe auf mich/meinen Account?<br>
												<input type='hidden' name='page' value='to7'>
												<br><br>
												<div align='right'>
													<input type='submit' value='Weiter'>
												</div><br><br>
											</form>
										</div>
									</td>
								</tr>
							</table><br><br><br><br><br><br><br><br>";

	}
}
if($tut['tutorial'] == 7) {
	if($tut_build != 1) {
		$points = sql_fetch_array(sql_query("SELECT points FROM city WHERE ID = '$_SESSION[city]'"));
		if($points['points'] < 50) {
			$pfuschOutput .= "<table border=1><tr><td><div align='center'>
								<br>Bis du 50 Stadtpunkte hast gibt es einen Angriffsschutz. Du brauchst noch nicht direkt in Verteidigung investieren.<br> Daher lass uns mit dem Bauen beginnen.<br><br>
								Klick als erstes auf Bauzentrum um dein erstes Gebäude zu bauen und keine Bauzeit zu verlieren - denn das ist das kostbarste gut in diesem Spiel!<br><br></div></td></tr></table><br><br>";
		}else{
			$pfuschOutput .= "<table border=1><tr><td><div align='center'>
								<br>Die aktuelle Runde läuft schon einige Zeit, daher hast du einen Startbonus, den sogenannten Späteinsteigerbonus, bekommen.<br>
								Dadurch bist du schon jetzt stärker wie andere Spieler, aber auch direkt angreifbar!<br>
								Trotzdem entlassen wir dich nicht einfach der bösen Welt dort draußen. Du hast von uns eine Verteidigung bekommen.<br>
								Stimmt jedoch das Verhältnis Rohstoffe/Verteidigung nicht kannst du angegriffen werden. Du musst klug bauen und dich verteidigen.<br>
								Wir zeigen dir jetzt wie!<br><br>
								Das Wichtigste ist keine Bauzeit zu verlieren, drum klicke schnell auf Bauzentrum um dein erstes eigenes Gebäude zu bauen.<br><br></div></td></tr></table><br><br>";
		}
	}else{
		$pfuschOutput .= "<table border=1><tr><td><div align='center'>
								<br>Anfangs ist die Produktion das Wichtigste. Und der wichtigste Rohstoff für Gebäude ist Iridium.<br>
								Trotz einer großen Menge Rohstoffe zum Beginn hast du schnell Rohstoffmangel - vor allem bei Iridium, daher ist es Wichtig keine unnötigen Gebäude zu bauen.<br>
								Hierzu zählt Anfangs auch das Bauzentrum, welches die Bauzeit senkt. Zwar hast du schneller eine höhere Produktion wenn das Gebäude schneller fertig ist, jedoch
								 ist Bauzentrum gerade am Anfang teuer. Als Faustregel gilt: lass die Bauzeit von Produktionsgebäuden ruhig auf 5 Stunden ansteigen.<br>
								Um die Rohstoffe für die nächste Mine zu sammeln brauchst du länger wie die Minen bauen.<br><br>
								Baue als erstes 5 Stufen Iridium-Mine. Wir haben die Bauzeit für dich gesenkt.<br><br></div></td></tr></table>";
	}
}
if($tut['tutorial'] == 8) {
	$pfuschOutput .= "<table border=1><tr><td><div align='center'>
								<br>Lass uns ein Technologiezentrum bauen, denn wir wollen Forschen um schneller voranzukommen.<br><br>
								</div></td></tr></table>";
}
if($tut['tutorial'] == 9) {
	if($tut_build != 2) {
		$pfuschOutput .= "<table border=1><tr><td><div align='center'>
							<br>Die ersten Gebäude sind fertiggestellt. Gratuliere.<br>
							Gebäude wirken sich jedoch nur auf eine einzelne Stadt aus, Technologien auf den gesamten Account. Boni einzelner Stufen summieren sich.
							5 Stufen BBT bringen 5*5% - also nicht 25% sondern 27,6%. Zinseszins. Je höher deine Technologien, desto weniger Rohstoffgebäude brauchst du. Es kann nie zuviel Bergbautechnik sein.
							Daher lass uns beginnen und forsch 2 Stufen Bergbautechnik und 1 Stufe Wasserkompression.<br><br></div></td></tr></table>";
	}else{
		$pfuschOutput .= "<table border=1><tr><td><div align='center'>
							<br>Willkommen im Technologiezentrum.<br><br>
							Hier hast du einen Überblick über alle Forschungen. Für Flugzeuge, für Produktion, für deine Lager. Es funktioniert wie das Bauzentrum, nur das du weniger Technologien vormerken kannst.<br><br>
							Forsch für den Anfang bitte 2 Stufen Bergbautechnik und danach 1 Stufe Wasserkompression. Wir wollen deine Produktion steigern.<br><br></div></td></tr></table>";
	}
}
if($tut['tutorial'] == 10) {
	$select_def = sql_fetch_array(sql_query("SELECT d_electronwoofer FROM city WHERE user='$_SESSION[user]';"));
	if($select_def['d_electronwoofer'] == 30) {
		$tut['tutorial'] = 11;
		$endit = 1;
		sql_query("UPDATE new_tutorial SET tutorial='11' WHERE user='$_SESSION[user]';");
	}
	if($tut_build != 3 && $endit != 1) {
		$pfuschOutput .= "<table border=1><tr><td><div align='center'>
							<br>Je höher die Produktion ist, desto größer sind die Rohstoffbestände und umso attraktiver sind wir für Angriffe durch andere Spieler.<br>
							Daher brauchen wir Verteidigung! Ich habe dir soeben 2 Stufen Verteidigungszentrum gutgeschrieben. <br><br>
							Gehe nun ins Verteidigungszentrum und bau 30 Elektronenwoofer.<br><br></div></td></tr></table>";
	}elseif($endit != 1) {
		$pfuschOutput .= "<table border=1><tr><td><div align='center'>
							<br>Willkommen im Verteidigungszentrum.<br><br>
							Bevor erste Angriffe auf dich stattfinden brauchen wir Verteidigung! Bau 30 Elektronenwoofer um dich zu schützen!<br><br></div></td></tr></table>";
	}
}
if($tut['tutorial'] == 11) {
	if($tut_build != 4) {
		$pfuschOutput .= "<table border=1><tr><td><div align='center'>
							<br>Ein sehr wichtiger Aspekt in Escape to Space ist der Einsatz von Flugzeuge. Ob für Rohstofftransport, zum Handeln oder um andere Spieler anzugreifen.<br><br>
							Oder auch um Kolonien zu gründen. Mit einem 'Settler' lassen sich unbewohnte Flecke unserer Welt besiedeln. Bau bitte einen Settler.<br><br></div></td></tr></table>";
	}else{
		$pfuschOutput .= "<table border=1><tr><td><div align='center'>
							<br>Willkommen im Hangar.<br><br>
							Hier kannst du Flugzeuge verschiedenster Kategorien bauen. Ob für Angriffe, zur Verteidigung, zum Rohstofftransport oder auch um Kolonien gründen (Settler) oder kriegerisch Übernehmen zu können (Scarecrow).<br><br>
							Es wird Zeit dein Imperium zu vergrößern. Je mehr Städte du besitzst, desto größer ist deine Produktion und deine Macht.<br>
							Bau einen Settler um eine Kolonie zu gründen.<br><br></div></td></tr></table>";
	}
}
if($tut['tutorial'] == 12) {
	if($tut_build != 5) {
		$pfuschOutput .= "<table border=1><tr><td><div align='center'>
							<br>Gratuliere, dein Settler ist fertig!<br><br>
							Nun such dir eine passende leere Stelle auf der Weltkarte aus, merke dir die Koordinaten in folgendem Format:<br>
							Kontinent:Land:Stadt<br><br>
							Wenn du passende Koordinaten gefunden hast klicke auf den Flughafen und gib sie dort als Ziel ein. Wähl danach den nun vorhandenen
							Settler aus und klick weiter unten das Feld vor 'Kolonisieren/Erobern' an. Mit einem Klick auf 'Flotte starten' fliegt deine Flotte los - endlich vergrößert sich dein Imperium.<br><br></div></td></tr></table>";
	}else{
		$pfuschOutput .= "<table border=1><tr><td><div align='center'>
							<br>Erweiter dein Imperium!<br><br>
							Wähl als erstes deinen Settler aus, trag danach deine Wunschkoordinaten ein und wähl weiter unten 'Kolonisieren/Erobern' aus.<br><br></div></td></tr></table>";
	}
}
if($tut['tutorial'] == 13) {
	
}





if($out == 1) {
	// define phptal template
	require_once("PHPTAL.php");
	require_once("include/PHPTAL_EtsTranslator.php");
	$template = new PHPTAL('tutorial.html');
	$template->setTranslator(new PHPTAL_EtsTranslator());
	$template->setEncoding('ISO-8859-1');
	
	// set page title
	$template->set('pageTitle', 'Der erste Schritt - Willkommen bei Escape to Space');
	
	$template->set('pfuschOutput',$pfuschOutput);
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

*/

?>