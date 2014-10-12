<?php

// Userprotect

$select_user = sql_query("SELECT city FROM city WHERE user='$target_user';");
$users = sql_num_rows($select_user);
if($user > 0) {		

		$attack_bigest = sql_query("SELECT city, user, points FROM city WHERE user = '$origin_user' ORDER BY `points` DESC LIMIT 1;");
		$attack_bigest = sql_fetch_array($attack_bigest);
		
		$attack_tech = sql_query("SELECT points, tech_points FROM usarios WHERE ID = '$attack_bigest[user]'");
		$user_techs_origin = sql_fetch_array($attack_tech);
		
		$defense_bigest = sql_query("SELECT city, user, points FROM city WHERE user = '$target_user' ORDER BY `points` DESC LIMIT 1;");
		$defense_bigest = sql_fetch_array($defense_bigest);
		
		$defense_tech = sql_query("SELECT points, tech_points FROM usarios WHERE ID = '$defense_bigest[user]'");
		$user_techs_target = sql_fetch_array($defense_tech);
		
		// 3-Fach-Check Userprotect
		$user_protect = 0;
		if($attack_bigest['points'] > $defense_bigest['points']) $user_protect++;
		if($user_techs_origin['points'] > $user_techs_target['points']) $user_protect++;
		if($user_techs_origin['tech_points'] > $user_techs_target['tech_points']) $user_protect++;
		
		if($user_protect > 1) {
			// KW des Angreifers reduzieren wenn über 1 der Bedingungen zutreffen !!!!!!
			 
			$bedingung_cpoint =  $defense_bigest['points'] / (  $attack_bigest['points'] * 0.8  )  * 100;
			$bedingung_apoint =  $user_techs_target['points'] / (  $user_techs_origin['points'] * 0.8  )  * 100;
			$bedingung_tpoint =  $user_techs_target['tech_points'] / (  $user_techs_origin['tech_points'] * 0.8  )  * 100;
		
			$bedingung = ( $bedingung_cpoint + $bedingung_apoint + $bedingung_tpoint ) / 3;
			if($bedingung < 80) {
				
				if($bedingung < 10) 
						$bedingung = 10;
				//Untergrenze 10%
				
				// Unter 80% -> KW des Angreifers herabsetzen um den Prozentsatz !
				$bed = 1;
			}
		}

}
?>