<?php
  require_once("database.php");
  require_once("constants.php");
  
 // define phptal template
  require_once("PHPTAL.php");
  require_once("include/PHPTAL_EtsTranslator.php");
  //$template = new PHPTAL('guest/alt_login.html');
  $template = new PHPTAL('guest/standardGuest.html');
  $template->setTranslator(new PHPTAL_EtsTranslator());
  $template->setEncoding('ISO-8859-1');
  
require_once("include/TemplateSettingsCommonGuest.php");
  
  // set page title
  $template->set('pageTitle', 'Neue Stadt anlegen');  

  $pfuschOutput = "";  

if($_POST['pwd']) {  
	$pwd = $_POST['pwd'];
	$select = "SELECT ID,user,password,email FROM `userdata` WHERE user='".addslashes($_POST['user'])."';";
	$select = sql_query($select);
	$select = sql_fetch_array($select);
	$md5_password = $pwd . $select['email'] . "B3stBr0ws3rg4m33v3r";
	$md5_password = md5($md5_password);
    $md5_password = md5($md5_password);
    $md5_password = md5($md5_password);
    if($md5_password = $select['password']) {
		// Passwort Korrekt
		
    	if($_POST['newuser'] != "") {
    		// Neuer Username
    		if($_POST['newuser'] != $select['user']) {
    			$username = sql_query("SELECT 1 FROM userdata WHERE user = '".addslashes($_POST['newuser'])."'");
    			if(sql_num_rows($username)) {
    				$x=1;
    				$pfuschOutput = "<li>Der Username ist schon vergeben.</li><br><br>";
    			}else{
					$sperrliste = sql_query("SELECT 1 FROM username_sperrliste WHERE username = '".addslashes($_POST['newuser'])."'");
    				if(sql_num_rows($sperrliste)) {
    					$x=1;
    					$pfuschOutput .= "<li>Der gewählte Username darf nicht verwendet werden. Bitte wähle einen anderen.</li><br><br>";
    				}
    			}
    				if($x==1) {
	    				$user = $select['user'];
	    				$select = sql_query("SELECT city.city FROM city INNER JOIN userdata ON city.user=userdata.ID WHERE userdata.user = '$user';");
	    				$select = sql_fetch_array($select);
	    				if($select['city'] == "") {
	    					$pfuschOutput .= "Du möchtest eine Stadt für den Account '$user' anlegen?<br />Gib hierfür bitte das Passwort erneut ein:
	    					<br /><br /><form action='newtown.php' method='post'>
	    					<input name='pwd' type='password' /><br><br>
	    					Möchtest du den Usernamen von '$user' in einen anderen ändern? Wenn nein, lass das folgende Feld bitte leer:<br>
	    					<input name='newuser' type='text' /><br /><br />
	    					<input name='user' type='hidden' value='$user' />
	    					<input type='submit' name='Bestätigen' />
	    					</form>";
	    				}else{
	    					$pfuschOutput .= "Wie ich sehe besitzt du schon eine Stadt. Möchtest du weitere, gründe bitte welche.";
						}
    				}
					
					// add pfusch output
					$template->set('pfuschOutput', $pfuschOutput);
					
					// create html page
					try {
						echo $template->execute();
					}
					catch (Exception $e) { echo $e->getMessage(); }
					
					
    			}else{
    				sql_query("UPDATE userdata SET user = '".addslashes($_POST['newuser'])."' WHERE user='". addslashes($_POST['user'])."'");
    				$_POST['user'] = $_POST['new_user'];
    			}
    		}    	
    	
		$sel = sql_query("SELECT `city` FROM `city` WHERE `user` = '$select[ID]';");
		$sel = sql_fetch_array($sel);
		if($sel['city'] == "") {
			// Keine Stadt vorhanden
				
			if (time() >= PAUSE_END || time() < PAUSE_BEGIN)
			{
				$reset_time = time();
			}
			else
			{
				$reset_time = PAUSE_END;
			}
			
				//Anzahl der Spieler insgesamt ermitteln und daraus den median		
				$get_count_user = sql_query("SELECT count(user) As user FROM usarios");
				$count_user = sql_fetch_array($get_count_user);
				$count_user = round($count_user[user]/2);
				list($x,$y,$z) = get_new_standard_coordinates();
				$sql_user = "ID='$select[ID]'";
				$sql_city = "city='$x:$y:$z'";
				
				$query1 = "INSERT INTO usarios (ID,user,login) VALUES ('$select[ID]','".addslashes($_POST['user'])."','$reset_time')";
                sql_query($query1);
                
                $query2 = "INSERT INTO city (user,city,home,x_pos,y_pos,z_pos,city_name,text,foundation,r_time,r_time_oxygen,pos) VALUES ('$select[ID]','$x:$y:$z','YES','$x','$y','$z','". addslashes($_POST[city_name]) ."','','$reset_time','$reset_time','$reset_time',0)";
				sql_query($query2);
				
				sql_query("INSERT INTO global_logs (seite, inhalt, datum) VALUES ('newtown.php', '[ACCOUNT ERNEUERT] ::::: Username: ".addslashes($_POST['user'])."', '$reset_time');");
				
				
                if (time() >= BEGIN_USER_BONUS) {
					//Gebäude Bonus für späteinsteiger
					foreach ( $register_bonus as $field ) 
					{	
						$get_median = sql_query("SELECT ID,$field FROM city WHERE home='YES' ORDER BY $field ASC LIMIT $count_user,1");
						$median_temp = sql_fetch_array($get_median);
						// 70% des Medians werden gegeben
						$median_value[$field] = round(0.7*$median_temp[$field]);
						sql_query("UPDATE city SET d_neutronwoofer=b_defense_center*" . TURRETS_PER_LEVEL . " , $field = $median_value[$field] WHERE city='$x:$y:$z'"); 
                    }
					sql_query("UPDATE city SET b_airport = 1 WHERE city='$x:$y:$z'");
					//sql_query("UPDATE city SET b_airport = 1, b_shield = $median_value[b_work_board]/2, c_active_shields = $median_value[b_work_board]/2 WHERE ".$sql_user);
                }
				else { 
			    	sql_query("UPDATE city SET b_work_board = 5 WHERE city='$x:$y:$z'");  
				}					
					sql_query("UPDATE usarios INNER JOIN city ON city.user=usarios.ID SET usarios.t_depot_management=city.t_depot_management,usarios.t_water_compression=city.t_water_compression,usarios.t_mining=city.t_mining WHERE city.city='$x:$y:$z'");				
					
					sql_query("UPDATE city SET points=b_iridium_mine+b_holzium_plantage+b_water_derrick+b_oxygen_reactor+b_depot+b_oxygen_depot+b_trade_center+b_hangar+".
						"b_airport+b_defense_center+b_shield+b_technologie_center+b_communication_center+b_work_board WHERE ".$sql_city);
					sql_query("UPDATE usarios SET points=0,tech_points=t_oxidationsdrive+t_hoverdrive+t_antigravitydrive+t_electronsequenzweapons+t_protonsequenzweapons+".
						"t_neutronsequenzweapons+t_consumption_reduction+t_computer_management+t_water_compression+t_depot_management+t_mining+t_plane_size+t_shield_tech ".
						"WHERE ".$sql_user);
					sql_query("UPDATE usarios SET points=tech_points + (SELECT sum(points) FROM city WHERE $sql_city) WHERE ID='$userID[ID]'");
									
				// Wechsel in Stadthistorie vermerken
                sql_query("INSERT INTO city_history (city, owner, time, user) VALUES ('$median_temp[ID]','".addslashes($_POST[new_user])."',$reset_time,'$select[ID]')");
                
                //Medaillen anlegen
                sql_query("INSERT INTO medals (user) VALUES ('$select[ID]')");

                if(is_file('../templates/welcome_msg.txt')) {
                    $welcome = implode('', file('../templates/welcome_msg.txt'));
                    $welcome = str_replace('%%%NAME%%%', $_POST[new_user], $welcome);
                    $welcome = str_replace('%%%COORDS%%%', "$x:$y:$z", $welcome);
                    sql_query(
                        "INSERT INTO news_igm_umid (sender,recipient,owner,time,topic,text,confirm,dir) VALUES (
                        'Verwaltungsrat','$select[ID]','$select[ID]'," .
                        $reset_time . ",'Willkommen auf Erde II','" . addslashes($welcome) . "','N',0)"
                    );
                }
                $pfuschOutput .= "Dein Spielaccount wurde erfolgreich erstellt. Gehe nun auf 'Erde II betreten' um loslegen zu können!";
			
			
		}else{ // Stadt schon vorhanden
			$pfuschOutput .= "Wie ich sehe besitzt du schon eine Stadt. Möchtest du weitere, gründe bitte welche.";
		}
	}else{
		$pfuschOutput .= "Das Passwort war inkorrekt.";
	}
}else{
	$user = mysql_real_escape_string($_GET['user']);
	$select = sql_query("SELECT city.city FROM city INNER JOIN userdata ON city.user=userdata.ID WHERE userdata.user = '$user';");
	$select = sql_fetch_array($select);
	if($select['city'] == "") {
	 	$pfuschOutput .= "Du möchtest eine Stadt für den Account '$user' anlegen?<br />Gib hierfür bitte das Passwort erneut ein:
	 	<br /><br /><form action='newtown.php' method='post'>
	 	<input name='pwd' type='password' /><br><br>
	 	Möchtest du den Usernamen von '$user' in einen anderen ändern? Wenn nein, lass das folgende Feld bitte leer:<br>
	 	<input name='newuser' type='text' /><br /><br />
	 	<input name='user' type='hidden' value='$user' />
	 	<input type='submit' name='Bestätigen' />
	 	</form>";
	}else{
		$pfuschOutput .= "Wie ich sehe besitzt du schon eine Stadt. Möchtest du weitere, gründe bitte welche.";
	}
	
}



  // add pfusch output
  $template->set('pfuschOutput', $pfuschOutput);  
  
  // create html page
  try {
    echo $template->execute();
  }
  catch (Exception $e) { echo $e->getMessage(); }
?>