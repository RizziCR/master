<?php

include("../session.php");
include_once("../htmlheader.php");
include("database.php");


// Temporäre Datenbank
$dbName = "ETS12";
sql_select_db($dbName,$db);

//////////// DB wechsel erfolgt - weiter mit Auslesen der Daten:

$name_building = array("Iridium-Mine", "Holzium-Plantage", "Wasser-Bohrturm", "Sauerstoff-Reaktor","Depot","Tank","Hangar","Flughafen","Bauzentrum","Technologiezentrum",
                       "Handelszentrum","Kommunikationszentrum","Verteidigungszentrum");

$name_techs = array("Oxidationsantrieb","Hoverantrieb","Antigravitationsantrieb","Elektronensequenzwaffen","Protonensequenzwaffen","Neutronensequenzwaffen",
				"Treibstoffverbrauch-Reduktion","Flugzeugkapazitätsverwaltung","Computermanagement","Lagerverwaltung","Wasserkompression","Bergbautechnik");

$name_db = array("b_iridium_mine","b_holzium_plantage","b_water_derrick","b_oxygen_reactor","b_depot","b_oxygen_depot","b_hangar","b_airport","b_work_board","b_technologie_center",
                 "b_trade_center","b_communication_center","b_defense_center");

$name_db_tech = array("t_oxidationsdrive","t_hoverdrive","t_antigravitydrive","t_electronsequenzweapons","t_protonsequenzweapons","t_neutronsequenzweapons",
					  "t_consumption_reduction","t_plane_size","t_computer_management","t_depot_management","t_water_compression","t_mining");

/////////////////////////// Output

$pfuschOutput .= "<h1>Revision $revision</h1>

          <table id='rangListe'>
          <tr>
            <td class='titel'>Städte</td>
            <td class='wert'>
             <a title='Top 50' href='./create.php?action=cities'>Grösse</a>
            </td>
          </tr>
          <tr>
            <td class='titel'>Siedler</td>
            <td class='wert'>
            <a title='Top 50' href='./create.php?action=users_score'>Grösse</a> - <a title='Top 50' href='./create.php?action=users_power'>Stärke</a>
            </td>
          </tr>
          <tr>
            <td class='titel'>Allianzen</td>
            <td class='wert'>
            <a title='Top 50' href='./create.php?action=alliances_score'>Grösse</a> - <a title='Top 50' href='./create.php?action=alliances_power'>Stärke</a>
            </td>
          </tr>
          <tr>
            <td class='titel'>Rundenzahlen</td>
            <td class='wert'>
             <a href='./create.php?action=statistics'>Allgemein</a> - <a title='Top 10' href='./create.php?action=upgradings'>Ausbaustufen</a>
            </td>
          </tr>
          <tr>
            <td class='titel'>Andere Runden</td>
            <td class='wert'>
             <a href='stats.php'>Archiv</a>
            </td>
          </tr>
        </table>";

if($_GET['action'] == "cities") {
	$pfuschOutput .= "<div id='blockExpansionStats'>
			<h3>Städte - Größe - Top 50</h3>
			<br/>
						<table id='citiesSize'>
			<tr>
				<th class='rang'>         
				<th class='stadt'>Stadt</th>
          		<th class='name'>Name (Allianz)</th>
          		<th class='groesse'>Grösse</th>
        	</tr>";
	
	
		////// Lade Top 50 Städte
		$x=1;
		$load = sql_query("SELECT city.city_name, usarios.user, city.points, city.alliance FROM city INNER JOIN usarios ON city.user=usarios.ID ORDER BY city.points DESC LIMIT 0,50");
		while($row = sql_fetch_array($load)) {
			$alliance = sql_fetch_array(sql_query("SELECT tag FROM alliances WHERE ID = '$row[alliance]'"));
			$row['alliance'] = $alliance['tag'];
			$pfuschOutput .= "<tr>
						<td class='rang'>$x</td>
						<td class='stadt'>$row[city_name]</td>
						<td class='name'>$row[user] ($row[alliance])</td>
						<td class='groesse'>$row[points]</td>
						</tr>";
			$x++;
		}
		$pfuschOutput .= "</table></div>";
}

if($_GET['action'] == "users_score") {
	$pfuschOutput .= "<div id='blockExpansionStats'>
				<h3>Spieler - Grösse - Top 50</h3>
				<br/>
						<table id='blockUserSize'>
				<tr>
						<td class='rang'>Rang</td>
						<td class='name'>Name (Allianz)</td>
						<td class='groesse'>Punkte</td>
				</tr>";
	
	
				////// Lade Top 50 User
				$x = 1;		
				$load = sql_query("SELECT user, points, alliance FROM usarios ORDER BY `points` DESC LIMIT 0,50");
				while($row = sql_fetch_array($load)) {
					$alliance = sql_fetch_array(sql_query("SELECT tag FROM alliances WHERE ID = '$row[alliance]'"));
					$row['alliance'] = $alliance['tag'];
					$pfuschOutput .= "<tr>
							<td class='rang'>$x</td>
							<td class='name'>$row[user] ($row[alliance])</td>
							<td class='groesse'>$row[points]</td>
							</tr>";
					$x++;
				}
		$pfuschOutput .= "</table></div>";
}

if($_GET['action'] == "users_power") {
	$pfuschOutput .= "<div id='blockExpansionStats'>
	<h3>Spieler - Grösse - Top 50</h3>
	<br/>
	<table id='blockUserSize'>
	<tr>
	<td class='rang'>Rang</td>
	<td class='name'>Name (Allianz)</td>
	<td class='groesse'>Stärke</td>
	</tr>";


	////// Lade Top 50 Userstärke
	$x = 1;
	$load = sql_query("SELECT user, power, alliance FROM usarios ORDER BY `power` DESC LIMIT 0,50");
				while($row = sql_fetch_array($load)) {
					$alliance = sql_fetch_array(sql_query("SELECT tag FROM alliances WHERE ID = '$row[alliance]'"));
					$row['alliance'] = $alliance['tag'];
					$pfuschOutput .= "<tr>
					<td class='rang'>$x</td>
					<td class='name'>$row[user] ($row[alliance])</td>
					<td class='groesse'>$row[power]</td>
					</tr>";
					$x++;
	}
	$pfuschOutput .= "</table></div>";
}

if($_GET['action'] == "alliances_score") {
	$pfuschOutput .= "<div id='blockExpansionStats'>
	<h3>Allianzen - Grösse - Top 50</h3>
	<br/>
	<table id='blockUserSize'>
	<tr>
	<td>Rang</td>
	<td>Allianz</td>
	<td>Mitglieder</td>
	<td>Punkte</td>
	</tr>";


	////// Lade Top 50 Allianzpunkte
	$x = 1;
	$load = sql_query("SELECT points, members, tag FROM alliances ORDER BY `points` DESC LIMIT 0,50");
	while($row = sql_fetch_array($load)) {
		$pfuschOutput .= "<tr>
		<td class='rang'>$x</td>
		<td class='name'>$row[tag]</td>
		<td>$row[members]</td>
		<td class='groesse'>$row[points]</td>
		</tr>";
		$x++;
	}
	$pfuschOutput .= "</table></div>";
}

if($_GET['action'] == "alliances_power") {
	$pfuschOutput .= "<div id='blockExpansionStats'>
	<h3>Allianzen - Grösse - Top 50</h3>
	<br/>
	<table id='blockUserSize'>
	<tr>
	<td>Rang</td>
	<td>Allianz</td>
	<td>Mitglieder</td>
	<td>Stärke</td>
	</tr>";


	////// Lade Top 50 Allianzpunkte
	$x = 1;
	$load = sql_query("SELECT power, members, tag FROM alliances ORDER BY `points` DESC LIMIT 0,50");
	while($row = sql_fetch_array($load)) {
		$pfuschOutput .= "<tr>
		<td class='rang'>$x</td>
		<td class='name'>$row[tag]</td>
		<td>$row[members]</td>
		<td class='groesse'>$row[power]</td>
		</tr>";
		$x++;
	}
	$pfuschOutput .= "</table></div>";
}



if($_GET['action'] == "upgradings") {
	$pfuschOutput .= "<div id='blockExpansionStats'>
        <h3>Rundenzahlen - Ausbaustufen - Top 10</h3>
        <br/>
                        <table>";
	
	//////// Lade Top10 Gebäude
	$x=0;
	foreach($name_db as $load) {
	
		$sql_load = sql_query("SELECT usarios.user, city.$load FROM city INNER JOIN usarios ON city.user=usarios.ID ORDER BY `".$load."` DESC LIMIT 0,10");
		$pfuschOutput .= "<tr>
		<th align='right'>$name_building[$x]</th><td>&nbsp;</td><td>";
		$upgrading[$x]['id'] = $name_building[$x];
		$y=0;
		while($row = sql_fetch_array($sql_load)) {
			$pfuschOutput .= "<span title='$row[user]'>$row[$load]</span>";
			if($y<9) $pfuschOutput .= " - ";
			$y++;
		}
		$pfuschOutput .= "</td></tr>";
		$x++;
	}
	
	//////// Lade Top10 Techs
		
		$x=0;
	foreach($name_db_tech as $load2) {
		
		$sql_load2 = sql_query("SELECT user, $load2 FROM usarios ORDER BY `".$load2."` DESC LIMIT 0,10");
			$pfuschOutput .= "<tr>
			<th align='right'>$name_techs[$x]</th><td>&nbsp;</td><td>";
			$y=0;
			while($row = sql_fetch_array($sql_load2)) {
				$pfuschOutput .= "<span title='$row[user]'>$row[$load2]</span>";
				if($y<9) $pfuschOutput .= " - ";
				$y++;
			}
			$pfuschOutput .= "</td></tr>";
			$x++;
	}
	
	///////////// Hangarplätze
	
	$sql_space = sql_query("SELECT usarios.user, b_hangar*10 AS hangar FROM city INNER JOIN usarios ON city.user = usarios.ID ORDER BY `b_hangar` DESC LIMIT 0,10");
	$pfuschOutput .= "<tr><th align='right'>Hangarplätze</th><td>&nbsp;</td><td>";
	$y=0;
	while($row = sql_fetch_array($sql_space)) {
		$pfuschOutput .= "<span title='$row[user]'>$row[hangar]</span>";
		if($y<9) $pfuschOutput .= " - ";
		$y++;
	}
		$pfuschOutput .= "</td></tr>";
	
		
	///////////// Theoretisch größte Flotte
	
	$sql_theo = sql_query("SELECT usarios.user, (city.b_airport *5 + usarios.t_computer_management *3) AS theo FROM city INNER JOIN usarios ON city.user = usarios.ID ORDER BY `theo` DESC LIMIT 0 , 10");
	$pfuschOutput .= "<tr><th align='right'>Theoretisch größte Flotte</th><td>&nbsp;</td><td>";
	$y=0;
	while($row = sql_fetch_array($sql_theo)) {
		$pfuschOutput .= "<span title='$row[user]'>$row[theo]</span>";
		if($y<9) $pfuschOutput .= " - ";
		$y++;
	}
	$pfuschOutput .= "</td></tr>";
	
	////////////// Größte Flotte
	
	$sql_prak = sql_query("SELECT usarios.user, (city.b_airport *5 + usarios.t_computer_management *3) AS theo FROM city INNER JOIN usarios ON city.user = usarios.ID WHERE (city.b_airport *5 + usarios.t_computer_management *3) < city.b_hangar *10 ORDER BY `theo` DESC LIMIT 0 , 10");
	$pfuschOutput .= "<tr><th align='right'>Praktisch größte Flotte</th><td>&nbsp;</td><td>";
	$y=0;
	while($row = sql_fetch_array($sql_prak)) {
		$pfuschOutput .= "<span title='$row[user]'>$row[theo]</span>";
		if($y<9) $pfuschOutput .= " - ";
		$y++;
	}
	$pfuschOutput .= "</td></tr>";
	
	//////////// Following Logins
	$following_logins = sql_query("SELECT user, following_logins FROM usarios ORDER BY following_logins DESC LIMIT 0,10;");
	$pfuschOutput .= "<tr><th align='right'>Aufeinanderfolgende Logins</th><td>&nbsp;</td><td>";
	$y=0;
	while($row = sql_fetch_array($following_logins)) {
		$pfuschOutput .= "<span title='$row[user]'>$row[following_logins]</span>";
		if($y<9) $pfuschOutput .= " - ";
		$y++;
	}
	$pfuschOutput .= "</td></tr>";
	
	
	$pfuschOutput .= "</table></div>";
}

if($_GET['action'] == "statistics" OR $_GET['action'] == "") {
	// Anzahl Berichte
	$report_load = sql_fetch_array(sql_query("SELECT id FROM news_ber ORDER BY id DESC LIMIT 0,1;"));
	
	// Ereignisse
	$erg_load = sql_fetch_array(sql_query("SELECT id FROM news_er ORDER BY id DESC LIMIT 0,1;"));
	
	// Defensivanlagen gesamt
	$def_total_load = sql_fetch_array(sql_query("SELECT SUM(d_electronwoofer+d_protonwoofer+d_neutronwoofer+d_electronsequenzer+d_protonsequenzer+d_neutronsequenzer) AS def_total FROM city"));
	
	// FLugzeuge gesamt
	$fleet_total_load = sql_fetch_array(sql_query("SELECT SUM(p_blackbird+p_blackbird+p_raven+p_eagle+p_falcon+p_nightingale+p_ravager+p_destroyer+p_settler+p_scarecrow+p_espionage_probe+p_small_transporter+p_medium_transporter+p_big_transporter) AS fleet_total FROM city"));

	// IGMs gesamt
	$igm_load = sql_fetch_array(sql_query("SELECT id FROM news_igm_umid ORDER BY id DESC LIMIT 0,1;"));
	
	// Siedler am Rundenende
	$player_end = sql_fetch_array(sql_query("SELECT COUNT(user) AS user FROM usarios;"));
	
	// Anzahl Städte am Rundenende
	$cities_end = sql_fetch_array(sql_query("SELECT COUNT(city) AS city FROM city;"));

	
	########################
	
	// zerstörte Flugzeuge
	$sparrow = sql_fetch_array(sql_query("SELECT type_plane.name AS name, SUM(`1`) AS `1`,SUM(`2`) AS `2`,SUM(`3`),SUM(`4`),SUM(`5`) AS `5`,SUM(`6`) FROM flightstats INNER JOIN type_plane ON flightstats.type=type_plane.type WHERE flightstats.type LIKE '1';"));
	$blackbird = sql_fetch_array(sql_query("SELECT type_plane.name AS name, SUM(`1`) AS `1`,SUM(`2`) AS `2`,SUM(`3`),SUM(`4`),SUM(`5`) AS `5`,SUM(`6`) FROM flightstats INNER JOIN type_plane ON flightstats.type=type_plane.type WHERE flightstats.type LIKE '2';"));
	$raven = sql_fetch_array(sql_query("SELECT type_plane.name AS name, SUM(`1`) AS `1`,SUM(`2`) AS `2`,SUM(`3`),SUM(`4`),SUM(`5`) AS `5`,SUM(`6`) FROM flightstats INNER JOIN type_plane ON flightstats.type=type_plane.type WHERE flightstats.type LIKE '3';"));
	$eagle = sql_fetch_array(sql_query("SELECT type_plane.name AS name, SUM(`1`) AS `1`,SUM(`2`) AS `2`,SUM(`3`),SUM(`4`),SUM(`5`) AS `5`,SUM(`6`) FROM flightstats INNER JOIN type_plane ON flightstats.type=type_plane.type WHERE flightstats.type LIKE '4';"));
	$falcon = sql_fetch_array(sql_query("SELECT type_plane.name AS name, SUM(`1`) AS `1`,SUM(`2`) AS `2`,SUM(`3`),SUM(`4`),SUM(`5`) AS `5`,SUM(`6`) FROM flightstats INNER JOIN type_plane ON flightstats.type=type_plane.type WHERE flightstats.type LIKE '5';"));
	$nightingale = sql_fetch_array(sql_query("SELECT type_plane.name AS name, SUM(`1`) AS `1`,SUM(`2`) AS `2`,SUM(`3`),SUM(`4`),SUM(`5`) AS `5`,SUM(`6`) FROM flightstats INNER JOIN type_plane ON flightstats.type=type_plane.type WHERE flightstats.type LIKE '6';"));
	$ravager = sql_fetch_array(sql_query("SELECT type_plane.name AS name, SUM(`1`) AS `1`,SUM(`2`) AS `2`,SUM(`3`),SUM(`4`),SUM(`5`) AS `5`,SUM(`6`) FROM flightstats INNER JOIN type_plane ON flightstats.type=type_plane.type WHERE flightstats.type LIKE '7';"));
	$destroyer = sql_fetch_array(sql_query("SELECT type_plane.name AS name, SUM(`1`) AS `1`,SUM(`2`) AS `2`,SUM(`3`),SUM(`4`),SUM(`5`) AS `5`,SUM(`6`) FROM flightstats INNER JOIN type_plane ON flightstats.type=type_plane.type WHERE flightstats.type LIKE '8';"));
	$spy = sql_fetch_array(sql_query("SELECT type_plane.name AS name, SUM(`1`) AS `1`,SUM(`2`) AS `2`,SUM(`3`),SUM(`4`),SUM(`5`) AS `5`,SUM(`6`) FROM flightstats INNER JOIN type_plane ON flightstats.type=type_plane.type WHERE flightstats.type LIKE '9';"));
	$settler = sql_fetch_array(sql_query("SELECT type_plane.name AS name, SUM(`1`) AS `1`,SUM(`2`) AS `2`,SUM(`3`),SUM(`4`),SUM(`5`) AS `5`,SUM(`6`) FROM flightstats INNER JOIN type_plane ON flightstats.type=type_plane.type WHERE flightstats.type LIKE '10';"));
	$scarecrow = sql_fetch_array(sql_query("SELECT type_plane.name AS name, SUM(`1`) AS `1`,SUM(`2`) AS `2`,SUM(`3`),SUM(`4`),SUM(`5`) AS `5`,SUM(`6`) FROM flightstats INNER JOIN type_plane ON flightstats.type=type_plane.type WHERE flightstats.type LIKE '11';"));
	
	$raid_in = sql_fetch_array(sql_query("SELECT SUM(`1`) AS iridium,SUM(`2`) AS holzium,SUM(`3`) AS wasser,SUM(`4`) AS sauerstoff FROM flightstats WHERE ad='raid_in';"));

	$raid_in['iridium'] = number_format($raid_in['iridium'], 0, ',', '.');
	$raid_in['holzium'] = number_format($raid_in['holzium'], 0, ',', '.');
	$raid_in['wasser'] = number_format($raid_in['wasser'], 0, ',', '.');
	$raid_in['sauerstoff'] = number_format($raid_in['sauerstoff'], 0, ',', '.');
	#########################
	
	// Ausgabe
	
	#########################
	
	$pfuschOutput .= "<div id='blockExpansionStats'>
        <h3>Rundenzahlen - Ausbaustufen - Top 10</h3>
        <br/>
              <table border=0>
              		<tr><th align='right'>Berichte gesamt</th><td>$report_load[id]</td></tr>
              		<tr><th align='right'>Ereignisse gesamt</th><td>$erg_load[id]</td></tr>
              		<tr><th align='right'>Ingame Nachrichten gesamt</th><td>$igm_load[id]</td></tr>
              		<tr><th align='right'>Defensivanlagen gesamt</th><td>$def_total_load[def_total]</td></tr>
              		<tr><th align='right'>Flugzeuge gesamt</th><td>$fleet_total_load[fleet_total]</td></tr>
              		<tr><th align='right'>Spieler am Rundenende</th><td>$player_end[user]</td></tr>
              		<tr><th align='right'>Städte am Rundenende</th><td>$cities_end[city]</td></tr>
              	</table>
              	<br/><br/>
          <h3>Zerstörte Flugzeuge</h3>
          <br/>
          		<table border=0>
          			<tr><th>Name</th><th>Angriff verlust</th><th>Verteidigung verlust</th><th>Handel</th></tr>
          			<tr><th align='right'>Sparrow</th><td>$sparrow[1]</td><td>$sparrow[2]</td><td>$sparrow[5]</td></tr>
          			<tr><th align='right'>Blackbird</th><td>$blackbird[1]</td><td>$blackbird[2]</td><td>$blackbird[5]</td></tr>
          			<tr><th align='right'>Raven</th><td>$raven[1]</td><td>$raven[2]</td><td>$raven[5]</td></tr>
          			<tr><th align='right'>Eagle</th><td>$eagle[1]</td><td>$eagle[2]</td><td>$eagle[5]</td></tr>
          			<tr><th align='right'>Falcon</th><td>$falcon[1]</td><td>$falcon[2]</td><td>$falcon[5]</td></tr>
          			<tr><th align='right'>Nightingale</th><td>$nightingale[1]</td><td>$nightingale[2]</td><td>$nightingale[5]</td></tr>
          			<tr><th align='right'>Ravager</th><td>$ravager[1]</td><td>$ravager[2]</td><td>$ravager[5]</td></tr>
          			<tr><th align='right'>Destroyer</th><td>$destroyer[1]</td><td>$destroyer[2]</td><td>$destroyer[5]</td></tr>
          			<tr><th align='right'>Spionagesonde</th><td>$spy[1]</td><td>$spy[2]</td><td>$spy[5]</td></tr>
          			<tr><th align='right'>Settler</th><td>$settler[1]</td><td>$settler[2]</td><td>$settler[5]</td></tr>
          			<tr><th align='right'>Scarecrow</th><td>$scarecrow[1]</td><td>$scarecrow[2]</td><td>$scarecrow[5]</td></tr>
           		</table>
           		<br/><br/>
           <h3>Plünderungen</h3>
           <br/>
           		<table border=0>
           			<tr><th align='right'>Iridium</th><td>$raid_in[iridium]</td></tr>
           			<tr><th align='right'>Holzium</th><td>$raid_in[holzium]</td></tr>
           			<tr><th align='right'>Wasser</th><td>$raid_in[wasser]</td></tr>
           			<tr><th align='right'>Sauerstoff</th><td>$raid_in[sauerstoff]</td></tr>
           		</table></div>";
              		
	
	
	
	
	
}

echo $pfuschOutput;
?>