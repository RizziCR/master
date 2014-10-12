<?php
include_once("../session.php");
$support_timestamp = time();
$submits = array("Bestrafen", "Abziehen", "Prozent");
echo 	"<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\">
		<html>
		<head>
		<title>Benutzer bestrafen</title>
		<link rel=\"stylesheet\" type=\"text/css\" href=\"../css.css\">
		</head>
		<body>
		<h2>Benutzer bestrafen"; if ($_GET[user]) echo " - Benutzer: ".$_GET[user]; ;echo "</h2>
		<form action=\"strafe.php\" method=\"post\">
		<table>
			<tr>
			<td colspan=\"2\">Ziehe X Stufen der jeweiligen Art beim user ab (f&uuml;r alle seine St&auml;dte):</td>
			</tr>
			<tr>
			<td>User: <input type=\"text\" name=\"benutzer\"><input type=\"submit\" value=\"Benutzer bestrafen\"></td>
			</tr>
			<tr>
			<td><b>ODER</b></td>
			</tr>
			<tr>
			<td>Stadt: <input type=\"text\" name=\"kontinent\" size=\"1\" maxlength=\"1\">:<input type=\"text\" name=\"land\" size=\"3\" maxlength=\"3\">:<input type=\"text\" name=\"stadt\" size=\"2\" maxlength=\"2\"><input type=\"submit\" value=\"Stadtbesitzer bestrafen\"></td>
			</tr>
		</table>
		</form>
		<table>";
		

if(in_array($_POST[submit], $submits)){
	echo "<tr>
		<td><a href=\"strafe.php?user=".$_POST[user]."\">Weitere Strafe f&uuml;r Benutzer <b>".$_POST[user]."</b></a></td>
		</tr>";
}
if(empty($_POST[benutzer]) && empty($_POST[submit]) && empty($_GET[user]) && empty($_GET[city]) && (empty($_POST[kontinent]) || empty($_POST[land]) || empty($_POST[stadt]))){
echo "<tr><td><b>Bitte einen Benutzernamen oder korrekte Stadtkoordinaten eingeben!</b></td></tr>";

} ELSE {
require_once("database.php");
	
	if(!empty($_POST[benutzer])) {
		$names = sql_query("SELECT * FROM usarios WHERE user LIKE '%". addslashes($_POST[benutzer]) ."%'");
		echo
		"<tr><td><table align=\"left\" cellspacing=\"0\" cellpadding=\"0\">
			<tr>
			<td><b>Benutzer mit ".$_POST[submit]." der Buchstabenfolge \"".$_POST[benutzer]."\"</b>:</td>
			</tr>";
			while($name = sql_fetch_array($names)) {
			echo "<tr>
			<td><a href=\"strafe.php?user=".$name[user]."\">".$name[user]."</a></td>
			</tr>";
			}
		echo "</table></td></tr>";
	}
		
	if(empty($_POST[benutzer]) && isset($_POST[kontinent]) && isset($_POST[land]) && isset($_POST[stadt])){
		$city = $_POST[kontinent].":".$_POST[land].":".$_POST[stadt];
		echo
		"<tr><td><table align=\"left\" cellspacing=\"0\" cellpadding=\"0\">
			<tr>
			<td><b>Stadt mit Koordinaten \"".$city."\"</b>:</td>
			</tr>";
			$staedte = sql_query("SELECT * FROM city WHERE city = '". addslashes($city) ."'");
			while($cities = sql_fetch_array($staedte)) {
			$target_user = $cities[user];
			echo "<tr>
			<td><a href=\"strafe.php?city=".$cities[city]."\">".$cities[city]."</a> - Besitzer: ".$target_user." - ".$cities[points]." Punkt(e)</td>
			</tr>";
			}
		echo "</table></td></tr>";
	}
}
	
	echo "<tr>";
	if($_GET[user]){
	echo "<td><table>
		<tr>
		<td colspan=\"2\"><h1>Strafe f&uuml;r <b>".$_GET[user]. "</b> aussprechen</h1></td>
		</tr>
		<tr>
		<td><form action=\"strafe.php\" method=\"post\">
		<table>";
		$werte = sql_query("SELECT * FROM usarios WHERE user = '". addslashes($_GET[user]) ."'");
		while($act = sql_fetch_array($werte)){
		echo
		"<tr>
		<td colspan=\"3\"><b>F&ouml;rdergeb&auml;ude</b></td>
		</tr>
		<tr>
		<td> Rohstoffgeb&auml;ude:</td>
		<td></td>
		<td> <input type=text name=minen size=\"3\"></td>
		</tr>
		<tr>
		<td> BZ:</td>
		<td></td>
		<td> <input type=text name=bz size=\"3\"></td>
		</tr>
		<tr><td colspan=\"3\"><b>Technologien</b></td>
		</tr>
		<tr>
		<td> WK:</td>
		<td>Aktuell: ".$act[t_water_compression]."</td>
		<td> <input type=\"text\" name=\"wk\" size=\"3\"></td>
		</tr>
		<tr>
		<td> BBT:</td>
		<td>Aktuell: ".$act[t_mining]."</td>
		<td> <input type=\"text\" name=\"bbt\" size=\"3\"></td>
		</tr>
		<tr>
		<td colspan=\"3\"><b>Antriebstechnologien</b></td>
		</tr>
		<tr>
		<td> Oxidationsantrieb:</td>
		<td>Aktuell: ".$act[t_oxidationsdrive]."</td>
		<td> <input type=\"text\" name=\"oxi\" size=\"3\"></td>
		</tr>
		<tr>
		<td> Hoverantrieb:</td>
		<td>Aktuell: ".$act[t_hoverdrive]."</td>
		<td> <input type=\"text\" name=\"hover\" size=\"3\"></td>
		</tr>
		<tr>
		<td> Antigravitationsantrieb:</td>
		<td>Aktuell: ".$act[t_antigravitydrive]." </td>
		<td> <input type=\"text\" name=\"grav\" size=\"3\"></td>
		</tr>
		<tr>
		<td colspan=\"3\"><b>Waffentechnologien</b></td>
		</tr>
		<tr>
		<td> Elektronensequenzwaffen:</td>
		<td>Aktuell: ".$act[t_electronsequenzweapons]."</td>
		<td> <input type=\"text\" name=\"esw\" size=\"3\"></td>
		</tr>
		<tr>
		<td> Protonensequenzwaffen:</td>
		<td>Aktuell: ".$act[t_protonsequenzweapons]."</td>
		<td> <input type=\"text\" name=\"psw\" size=\"3\"></td>
		</tr>
		<tr>
		<td> Neutronensequenzwaffen:</td>
		<td>Aktuell: ".$act[t_neutronsequenzweapons]."</td>
		<td> <input type=\"text\" name=\"nsw\" size=\"3\"></td>
		</tr>
		<tr>
		<td colspan=\"3\" align=\"center\"><input type=\"hidden\" name=\"user\" value=\"".$_GET[user]."\"><input type=\"submit\" name=\"submit\" value=\"Bestrafen\"></td>
		</tr>
	</table>
	</form></td>
	<td valign=\"top\">
	<form action=\"strafe.php\" method=\"post\">
	<table>
		<tr>
		<td colspan=\"2\">%-Abzug <u>aller</u> Geb&auml;ude, Forschungen und Rohstoffe:<br>
		(ausgenommen sind Hangar und Kommunikationszentrum)</td>
		</tr>
		<tr>
		<td align=\"center\">Prozent:</td>
		<td align=\"center\"> <input type=\"text\" name=\"prozent\" size=\"3\">%</td>
		</tr>
		<tr>
		<td colspan=\"2\" align=\"center\"><input type=\"hidden\" name=\"user\" value=\"".$_GET[user]."\"><input type=\"submit\" name=\"submit\" value=\"Prozent\"></td>
		</tr>
	</table>
	</form></td>
	</tr>
	</table>
	</td>";
	}
	}

	if($city){	
	echo "<td><form action=\"strafe.php\" method=\"post\">
	<table>
		<tr>
		<td colspan=\"3\"><b>F&ouml;rdergeb&auml;ude</b></td>
		</tr>
		<tr>
		<td> Rohstoffgeb&auml;ude:</td>
		<td> <input type=text name=minen size=\"3\"></td>
		</tr>
		<tr>
		<td> BZ:</td>
		<td> <input type=text name=bz size=\"3\"></td>
		</tr>
		<tr>
		<td colspan=2>Ziehe X Einheiten Rohstoffe von einer Stadt ab:</td>
		</tr>
		<tr>
		<td>Menge Iridium:</td><td> <input type=text name=r1></td>
		</tr>
		<tr>
		<td>Menge Holzium:</td><td> <input type=text name=r2></td>
		</tr>
		<tr>
		<td>Menge Wasser:</td><td> <input type=text name=r3></td>
		</tr>
		<tr>
		<td>Menge Sauerstoff:</td><td> <input type=text name=r4></td>
		</tr>
		<tr>
		<td colspan=\"2\" align=\"center\"><input type=\"hidden\" name=\"city\" value=\"".$city."\"><input type=\"hidden\" name=\"target_user\" value=\"".$target_user."\"><input type=\"submit\" name=\"submit\" value=\"Abziehen\"></td>
		</tr>
	</table>
	</form></td>";
	}

    $sql = ""; $sql2 = "";
    if ($_POST[submit] == "Bestrafen") {
        require_once("database.php");

        $minus_minen = (int)$_POST[minen];
        $minus_bz = (int)$_POST[bz];
        $minus_wk = (int)$_POST[wk];
        $minus_bbt = (int)$_POST[bbt];
        $minus_oxi = (int)$_POST[oxi];
        $minus_hover = (int)$_POST[hover];
        $minus_grav = (int)$_POST[grav];
        $minus_esw = (int)$_POST[esw];
        $minus_psw = (int)$_POST[psw];
        $minus_nsw = (int)$_POST[nsw];
		$minus_sum = array_sum(array($minus_minen, $minus_bz, $minus_wk, $minus_bbt, $minus_oxi, $minus_hover, $minus_grav, $minus_esw, $minus_psw, $minus_nsw));
		// BLUBB !
		if($minus_minen == 0 && $minus_bz == 0 && $minus_wk == 0 && $minus_bbt == 0 && $minus_oxi == 0 && $minus_hover == 0 && $minus_grav == 0 && $minus_esw == 0 && $minus_psw == 0 && $minus_nsw == 0 && $minus_sum == 0){
			die("Alle Werte gleich Null => keine Strafe verh&auml;ngt");
		} ELSE if($minus_minen >= 0 && $minus_bz >= 0 && $minus_wk >= 0 && $minus_bbt >= 0 && $minus_oxi >= 0 && $minus_hover >= 0 && $minus_grav >= 0 && $minus_esw >= 0 && $minus_psw >= 0 && $minus_nsw >= 0 && $minus_sum > 0){
		$action = "Abzug absolut";
		} ELSE if($minus_minen <= 0 && $minus_bz <= 0 && $minus_wk <= 0 && $minus_bbt <= 0 && $minus_oxi <= 0 && $minus_hover <= 0 && $minus_grav <= 0 && $minus_esw <= 0 && $minus_psw <= 0 && $minus_nsw <= 0 && $minus_sum < 0) {
		$action = "Gutschrift absolut";
		} ELSE {
		$action = "Abzug & Gutschrift";
		}

        if (!empty($_POST[user]) && !empty($_POST[city]))
            die("Bitte ENTWEDER Nutzer ODER Stadt angeben");

        if (!empty($_POST[user])) {
            $get_myuser = sql_query("SELECT user FROM userdata WHERE user='". addslashes($_POST[user]) ."'");
            $myuser = sql_fetch_array($get_myuser);
            if (!$myuser[user])
                die("Nutzer existiert nicht");
            $sql = $sql2 = "user='$myuser[user]'";
            sql_query("UPDATE usarios SET ".
                "t_water_compression=IF(t_water_compression>$minus_wk, t_water_compression-$minus_wk,0),".
                "t_mining=IF(t_mining>$minus_bbt, t_mining-$minus_bbt,0),".
                "t_oxidationsdrive=IF(t_oxidationsdrive>$minus_oxi, t_oxidationsdrive-$minus_oxi,0),".
                "t_hoverdrive=IF(t_hoverdrive>$minus_hover,t_hoverdrive-$minus_hover,0),".
                "t_antigravitydrive=IF(t_antigravitydrive>$minus_grav, t_antigravitydrive-$minus_grav,0),".
                "t_electronsequenzweapons=IF(t_electronsequenzweapons>$minus_esw, t_electronsequenzweapons-$minus_esw,0),".
                "t_protonsequenzweapons=IF(t_protonsequenzweapons>$minus_psw, t_protonsequenzweapons-$minus_psw,0),".
                "t_neutronsequenzweapons=IF(t_neutronsequenzweapons>$minus_nsw, t_neutronsequenzweapons-$minus_nsw,0) ".
                "WHERE $sql");
				
			// Eintrag in Support-Log
			sql_query("INSERT INTO logs_support (supporter, action, target_user, r_res_buildings, r_work_board, r_water_compression, r_mining, r_oxidationsdrive, r_hoverdrive, r_antigravitydrive, r_electronsequenzweapons, r_protonsequenzweapons, r_neutronsequenzweapons, timestamp)
					VALUES ('$_SESSION[supporter]', '$action', '". addslashes($_POST[user]) ."', '$minus_minen', '$minus_bz', '$minus_wk', '$minus_bbt', '$minus_oxi', '$minus_hover', '$minus_grav', '$minus_esw', '$minus_psw', '$minus_nsw', '$support_timestamp')");
        }

        if ($_POST[city]) {
            $get_mycity = sql_query("SELECT user,city FROM city WHERE city='". addslashes($_POST[city]) ."'");
            $mycity = sql_fetch_array($get_mycity);
            if (!$mycity[city])
                die("Koordinaten existieren nicht");
            $sql = "city='$mycity[city]'";
            $sql2 = "user='$mycity[user]'";
        }

        if ($minus_minen < 0 || $minus_wk < 0 || $minus_bbt < 0 || $minus_bz < 0 ||
            $minus_oxi < 0 || $minus_hover < 0 || $minus_grav < 0 ||
            $minus_esw < 0 || $minus_psw < 0 || $minus_nsw < 0
        )
            echo "Achtung, es wurden Geb&auml;ude-Stufen gutgeschrieben<br>";

        sql_query("UPDATE city SET ".
            "b_iridium_mine=IF(b_iridium_mine>$minus_minen, b_iridium_mine-$minus_minen,0),".
            "b_holzium_plantage=IF(b_holzium_plantage>$minus_minen, b_holzium_plantage-$minus_minen,0),".
            "b_water_derrick=IF(b_water_derrick>$minus_minen, b_water_derrick-$minus_minen,0),".
            "b_oxygen_reactor=IF(b_oxygen_reactor>$minus_minen, b_oxygen_reactor-$minus_minen,0),".
            "t_water_compression=IF(t_water_compression>$minus_wk, t_water_compression-$minus_wk,0),".
            "t_mining=IF(t_mining>$minus_bbt, t_mining-$minus_bbt,0),".
            "b_work_board=IF(b_work_board>$minus_bz, b_work_board-$minus_bz, 0) ".
            "WHERE $sql");
        sql_query("UPDATE city SET ".
            "r_iridium_add=((15*POW(b_iridium_mine,1.8)+2000)/3600),".
            "r_holzium_add=((15*POW(b_holzium_plantage,1.7)+2000)/3600),".
            "r_water_add=((10*POW(b_water_derrick,2)+10)/3600),".
            "r_oxygen_add=(((20/7)*POW(b_oxygen_reactor,2))/3600) ".
            "WHERE $sql");

        sql_query("UPDATE city SET points=b_iridium_mine+b_holzium_plantage+b_water_derrick+b_oxygen_reactor+b_depot+b_oxygen_depot+b_trade_center+b_hangar+".
            "b_airport+b_defense_center+b_shield+b_technologie_center+b_communication_center+b_work_board WHERE ".$sql);
        sql_query("UPDATE usarios SET points=0,tech_points=t_oxidationsdrive+t_hoverdrive+t_antigravitydrive+t_electronsequenzweapons+t_protonsequenzweapons+".
            "t_neutronsequenzweapons+t_consumption_reduction+t_computer_management+t_water_compression+t_depot_management+t_mining+t_plane_size+t_shield_tech ".
            "WHERE ".$sql2);
        sql_query("UPDATE usarios SET points=tech_points + (SELECT sum(points) FROM city WHERE $sql2) WHERE $sql2");

        if ($minus_minen > 0 || $minus_wk > 0 || $minus_bbt > 0 || $minus_bz > 0 ||
            $minus_oxi > 0 || $minus_hover > 0 || $minus_grav > 0 ||
            $minus_esw > 0 || $minus_psw > 0 || $minus_nsw > 0
        )
        echo "Strafe verh&auml;ngt ->";
    }

    if ($_POST[submit] == "Abziehen") {
        require_once("database.php");

        $get_mycity = sql_query("SELECT city FROM city WHERE city='". addslashes($_POST[city]) ."'");
        $mycity = sql_fetch_array($get_mycity);

        $minus_r1 = (int)$_POST[r1];
        $minus_r2 = (int)$_POST[r2];
        $minus_r3 = (int)$_POST[r3];
        $minus_r4 = (int)$_POST[r4];
        $minus_minen = (int)$_POST[minen];
        $minus_bz = (int)$_POST[bz];

        //if ($minus_r1 < 0 || $minus_r2 < 0 || $minus_r3 < 0 || $minus_r4 < 0)
        //    die("Fehler");
		if($mycity[r_iridium]-$minus_r1 <= 0) $r_iridium = 0; $r_iridium = $mycity[r_iridium]-$minus_r1;
		if($mycity[r_holzium]-$minus_r2 <= 0) $r_holzium = 0; $r_holzium = $mycity[r_holzium]-$minus_r2;
		if($mycity[r_water]-$minus_r3 <= 0) $r_water = 0; $r_water = $mycity[r_water]-$minus_r3;
		if($mycity[r_oxygen]-$minus_r4 <= 0) $r_oxygen = 0; $r_oxygen = $mycity[r_oxygen]-$minus_r4;
		if($mycity[b_res_buildings]-$minus_minen <= 0) $r_res_buildings = 0; $r_res_buildings = $mycity[b_iridium_mine]-$minus_minen;
		if($mycity[b_work_board]-$minus_bz <= 0) $r_work_board = 0; $r_work_board = $mycity[b_work_board]-$minus_bz;
		
        sql_query("UPDATE city SET r_iridium='" .addslashes($r_iridium). "' WHERE city='$mycity[city]'");
        sql_query("UPDATE city SET r_holzium='" .addslashes($r_holzium). "' WHERE city='$mycity[city]'");
        sql_query("UPDATE city SET r_water='" .addslashes($r_water). "' WHERE city='$mycity[city]'");
        sql_query("UPDATE city SET r_oxygen='" .addslashes($r_oxygen). "' WHERE city='$mycity[city]'");
		sql_query("UPDATE city SET b_res_buildings='" .addslashes($r_res_buildings). "' WHERE city='$mycity[city]'");
		sql_query("UPDATE city SET b_work_board='" .addslashes($r_work_board). "' WHERE city='$mycity[city]'");

        echo "Rohstoffe / Geb&auml;udestufen abgezogen bzw gutgeschrieben !";
		
		if($minus_r1 >= 0 && $minus_r2 >= 0 && $minus_r3 >= 0 && $minus_r4 >= 0 && $minus_minen >= 0 && $minus_bz >= 0){
		$action = "Abzug absolut";
		} ELSE if($minus_r1 <= 0 && $minus_r2 <= 0 && $minus_r3 <= 0 && $minus_r4 <= 0 && $minus_minen <= 0 && $minus_bz <= 0) {
		$action = "Gutschrift absolut";
		} ELSE {
		$action = "Abzug & Gutschrift";
		}
		
		// Eintrag in Support-Log
		sql_query("INSERT INTO logs_support (supporter, action, target_user, target_city, r_iridium, r_holzium, r_water, r_oxygen, r_res_buildings, r_work_board, timestamp)
					VALUES ('$_SESSION[supporter]', '$action', '$_POST[target_user]', '$mycity[city]','$r_iridium', '$r_holzium', '$r_water', '$r_oxygen', '$r_res_buildings', '$r_work_board', '$support_timestamp')");
    }
    
    if ($_POST[submit] == "Prozent") {
        require_once("database.php");
        
        if(empty($_POST[prozent]) || empty($_POST[user])){
        	die("Bitte gib einen Nutzernamen und einen Prozentwert an.");
        }
        $user = addslashes($_POST[user]);
        
        if($_POST[prozent] > 100){
        	die("Bitte gib einen Prozentwert zwischen 1 und 100 an.");        	
        }
        if($_POST[prozent] < 1){
            echo "Achtung, es werden Stufen gutgeschrieben<br>";
        }
        
        $get_myuser = sql_query("SELECT ID,user FROM usarios WHERE user='$user'");
        $myuser = sql_fetch_array($get_myuser);
        
        if (!$myuser[user]){
            die("Nutzer existiert nicht");
    	}
    	
    	$multiplikator = (100 - $_POST[prozent]) / 100;
    	
    	// Technologien des Spielers aktualisieren (Ausgenommen: Lagerverwaltung)
    	sql_query("UPDATE usarios SET 
    		t_oxidationsdrive 			= round(t_oxidationsdrive * $multiplikator),
    		t_hoverdrive 				= round(t_hoverdrive * $multiplikator),
    		t_antigravitydrive 			= round(t_antigravitydrive * $multiplikator),
    		t_electronsequenzweapons	= round(t_electronsequenzweapons  * $multiplikator),
    		t_protonsequenzweapons 		= round(t_protonsequenzweapons  * $multiplikator),
    		t_neutronsequenzweapons 	= round(t_neutronsequenzweapons * $multiplikator),
    		t_consumption_reduction 	= round(t_consumption_reduction * $multiplikator),
    		t_plane_size 				= round(t_plane_size * $multiplikator),
    		t_computer_management 		= round(t_computer_management * $multiplikator),
    		t_water_compression 		= round(t_water_compression * $multiplikator),
    		t_mining 					= round(t_mining * $multiplikator),
    		t_shield_tech 				= round(t_shield_tech * $multiplikator)
    		WHERE user					= '$user'
    	;");
    	
    	// St&auml;dte des Spielers aktualisieren (Ausgenommen: Hangar, Lagerverwaltung, Lager, Treibstofflager, KommZ)	
    	$getUsario = sql_query("SELECT t_mining, t_water_compression FROM usarios WHERE user = '$user';");
    	$myusario = sql_fetch_array($getUsario);
    	$bbt = $myusario[t_mining];
    	$wk = $myusario[t_water_compression];
    	sql_query("UPDATE city SET
			r_iridium_add		 = ((15*POW(round(b_iridium_mine * $multiplikator),1.8)+2000)/3600),   
            r_holzium_add		 = ((15*POW(round(b_holzium_plantage * $multiplikator),1.7)+2000)/3600),
            r_water_add			 = ((10*POW(round(b_water_derrick * $multiplikator),2)+10)/3600),
            r_oxygen_add		 = (((20/7)*POW(round(b_oxygen_reactor * $multiplikator),2))/3600),   	
			r_iridium 			 = round(r_iridium * $multiplikator),
			r_holzium 			 = round(r_holzium * $multiplikator),
			r_water 			 = round(r_water * $multiplikator),
			r_oxygen 			 = round(r_oxygen * $multiplikator),
			t_mining 			 = $bbt,
			t_water_compression  = $wk,
			b_iridium_mine 		 = round(b_iridium_mine * $multiplikator),
			b_holzium_plantage 	 = round(b_holzium_plantage * $multiplikator),
			b_water_derrick 	 = round(b_water_derrick * $multiplikator),
			b_oxygen_reactor 	 = round(b_oxygen_reactor * $multiplikator),
			b_trade_center 		 = round(b_trade_center * $multiplikator),
			b_airport 			 = round(b_airport * $multiplikator),
			b_defense_center 	 = round(b_defense_center * $multiplikator),
			b_shield 			 = round(b_shield * $multiplikator),
			b_technologie_center = round(b_technologie_center * $multiplikator),
			b_work_board 		 = round(b_work_board  * $multiplikator)
			WHERE user			 = '$myuser[ID]'
		;");
    	
    	// Verteidigungsanlagen neu berechnen
    	$getCities = sql_query("SELECT * FROM city WHERE user = '$myuser[ID]';");
    	while($city = sql_fetch_array($getCities)){
    		$max_defense = $city[b_defense_center] * 12;
    		$myAmount = $city[d_electronwoofer] + $city[d_protonwoofer] + $city[d_neutronwoofer] + $city[d_electronsequenzer] + $city[d_protonsequenzer] + $city[d_neutronsequenzer];
    		$diff = $myAmount - $max_defense;    		
    		
    		if($diff > 0){
    			
    			// Elektronenwoofer abziehen
    			if($city[d_electronwoofer] > 0){
    				if($city[d_electronwoofer] <= $diff){
	    				sql_query("UPDATE city SET d_electronwoofer = 0 WHERE city = '$city[city]';");
	    				$diff = $diff - $city[d_electronwoofer];
    				}
    				else{
    					sql_query("UPDATE city SET d_electronwoofer = d_electronwoofer - $diff WHERE city = '$city[city]';");
    					$diff = 0;
    				}
    			}
    			
    			// Protonenwoofer abziehen
    			if($diff > 0){
    				if($city[d_protonwoofer] > 0){
    					if($city[d_protonwoofer] <= $diff){
		    				sql_query("UPDATE city SET d_protonwoofer = 0 WHERE city = '$city[city]';");
		    				$diff = $diff - $city[d_protonwoofer]; 
    					}
    					else{
	    					sql_query("UPDATE city SET d_protonwoofer = d_protonwoofer - $diff WHERE city = '$city[city]';");
	    					$diff = 0;    						
    					}
    				}
    			}
    			
    			// Neutronenwoofer abziehen
    			if($diff > 0){
    				if($city[d_neutronwoofer] > 0){
    					if($city[d_neutronwoofer] <= $diff){
		    				sql_query("UPDATE city SET d_neutronwoofer = 0 WHERE city = '$city[city]';");
		    				$diff = $diff - $city[d_neutronwoofer]; 
    					}
    					else{
	    					sql_query("UPDATE city SET d_neutronwoofer = d_neutronwoofer - $diff WHERE city = '$city[city]';");
	    					$diff = 0;    						
    					}
    				}
    			} 
    			
    			// Elektronensequenzer abziehen
    			if($diff > 0){
    				if($city[d_electronsequenzer] > 0){
    					if($city[d_electronsequenzer] <= $diff){
		    				sql_query("UPDATE city SET d_electronsequenzer = 0 WHERE city = '$city[city]';");
		    				$diff = $diff - $city[d_electronsequenzer]; 
    					}
    					else{
	    					sql_query("UPDATE city SET d_electronsequenzer = d_electronsequenzer - $diff WHERE city = '$city[city]';");
	    					$diff = 0;    						
    					}
    				}
    			}  

    			// Protonensequenzer abziehen
    			if($diff > 0){
    				if($city[d_protonsequenzer] > 0){
    					if($city[d_protonsequenzer] <= $diff){
		    				sql_query("UPDATE city SET d_protonsequenzer = 0 WHERE city = '$city[city]';");
		    				$diff = $diff - $city[d_protonsequenzer]; 
    					}
    					else{
	    					sql_query("UPDATE city SET d_protonsequenzer = d_protonsequenzer - $diff WHERE city = '$city[city]';");
	    					$diff = 0;    						
    					}
    				}
    			} 

    			// Neutronensequenzer abziehen
    			if($diff > 0){
    				if($city[d_neutronsequenzer] > 0){
    					if($city[d_neutronsequenzer] <= $diff){
		    				sql_query("UPDATE city SET d_neutronsequenzer = 0 WHERE city = '$city[city]';");
		    				$diff = $diff - $city[d_neutronsequenzer]; 
    					}
    					else{
	    					sql_query("UPDATE city SET d_neutronsequenzer = d_neutronsequenzer - $diff WHERE city = '$city[city]';");
	    					$diff = 0;    						
    					}
    				}
    			}     			   			
    		}
    	}
    	
    	// Punkte der einzelnen St&auml;dte neu berechnen
    	$getCities = sql_query("SELECT * FROM city WHERE user='$myuser[ID]'");
    	while($city = sql_fetch_array($getCities)){
	        sql_query("UPDATE city SET points = b_iridium_mine + b_holzium_plantage + b_water_derrick + b_oxygen_reactor + b_depot + b_oxygen_depot + b_trade_center + b_hangar + ".
	            "b_airport + b_defense_center + b_shield + b_technologie_center + b_communication_center + b_work_board WHERE city = '$city[city]';");    		
    	}
    	
    	// Technologiepunkte neu berechnen
        sql_query("UPDATE usarios SET
        	points 		= 0,
        	tech_points = t_oxidationsdrive + t_hoverdrive + t_antigravitydrive + t_electronsequenzweapons + t_protonsequenzweapons + ".
            			  "t_neutronsequenzweapons + t_consumption_reduction + t_computer_management + t_water_compression + t_depot_management + t_mining + t_plane_size + t_shield_tech
            WHERE user	= '$user'
        ;");
            
      	// Gesamtpunkte neu berechnen
        sql_query("UPDATE usarios SET points = tech_points + (SELECT sum(points) FROM city WHERE user = '$myuser[ID]') WHERE user = '$user';");  
        
		// Eintrag in Support-Log
		sql_query("INSERT INTO logs_support (supporter, action, target_user,  r_account_prozent, timestamp)
				VALUES ('$_SESSION[supporter]', 'Abzug Account (%)', '$user', '".addslashes($_POST[prozent])."', $support_timestamp)");
		
        echo "Aktion erfolgreich durchgef&uuml;hrt."; 	
    }


//}
echo "</tr></table></body></html>";
?>