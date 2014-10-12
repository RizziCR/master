<?php

    $use_lib = 12; // MSG_REGISTER

  require_once("database.php");
  require_once("functions.php");
  require_once("constants.php");
  require_once("msgs.php");

 // define phptal template
  require_once("PHPTAL.php");
  require_once("include/PHPTAL_EtsTranslator.php");
  $template = new PHPTAL('guest/register.html');
  $template->setTranslator(new PHPTAL_EtsTranslator());
  $template->setEncoding('ISO-8859-1');

    require_once("include/TemplateSettingsCommonGuest.php");

    $new_user = trim($_REQUEST[new_user]);

    // ckeck new data on errors
    $action = $_POST[action];
    if ($action == "register")
    {
        if (!$_POST[new_user] || !$_POST[email1] || !$_POST[email2] || !$_POST[password1] || !$_POST[password2] || !$_POST[city_name])
        {
            // Angaben vervollständigen
            ErrorMessage(MSG_REGISTER,e000);
        }

        if (!$_POST[agb1])
        {
            // AGB akzeptieren
            ErrorMessage(MSG_REGISTER,e001);
        }
        if (!$_POST[privacyCheck])
        {
            // Datenschutzerklaerung akzeptieren
            ErrorMessage(MSG_REGISTER,privacyError);
        }

        if ($_POST[email1] != $_POST[email2])
        {
            // email Wiederholung prüfen
            ErrorMessage(MSG_REGISTER,e002);
        }

        if (!preg_match("/^(\w|-|\.)*?@(\w)(\w|-|\.)*?\.(\w){2,4}$/",$_POST[email1]))
        {
            // email berichtigen
            ErrorMessage(MSG_REGISTER,e003);
        }

        if (strlen($_POST[new_user]) > 15)
        {
            // Benutzernamen kürzen
            ErrorMessage(MSG_REGISTER,e005);
        }

        if(!preg_match('/^([0-9A-Za-z])+$/', $_POST[new_user]))
        {
            // Sonderzeichen entfernen
            ErrorMessage(MSG_REGISTER,e006);
        }

        if ($_POST[password1] != $_POST[password2])
            // Kennwort Wiederholung prüfen
            ErrorMessage(MSG_REGISTER,e007);

        if (strlen($_POST[password1]) < 8)
        {
            // Kennwort verlängern
            ErrorMessage(MSG_REGISTER,e008);
        }

        /*  Voranmeldungsklausel?
        if (time() <= 1167606000)
        {
            $get_pre_reg = sql_query("SELECT user,email FROM ETS6.userdata WHERE user='$_POST[new_user]'");
            $pre_reg = sql_fetch_array($get_pre_reg);
            if (sql_num_rows($get_pre_reg))
            {
                if ($pre_reg[email] != $_POST[email1])
                {
                    // Kennwort verlängern
                    ErrorMessage(MSG_REGISTER,e004);
                }
            }
        }*/
        
        
        $get_sperrliste = sql_query("SELECT 1 FROM sperrliste_username WHERE username='".mysql_real_escape_string($_POST[new_user])."'");
        if(sql_num_rows($get_sperrliste))
        {
        	ErrorMessage(MSG_REGISTER,e012);
        }
        

        $get_user_names = sql_query("SELECT 1 FROM userdata WHERE user='".mysql_real_escape_string($_POST[new_user])."'");
        if (sql_num_rows($get_user_names))
        {
        	$get_usarios = sql_query("SELECT 1 FROM usarios WHERE user='".mysql_real_escape_string($_POST[new_user])."'");
        	if(sql_num_rows($get_usarios))
        	{
            	// Namen ändern
            	ErrorMessage(MSG_REGISTER,e009);
        	}else{
        		// alter Spielaccount schon vorhanden
        	}
        }

        $get_user_names = sql_query("SELECT 1 FROM new_user WHERE user='".mysql_real_escape_string($_POST[new_user])."'");
        if (sql_num_rows($get_user_names))
        {
            $get_code = sql_query("SELECT 1 FROM new_user WHERE user='".mysql_real_escape_string($_POST[new_user])."' AND code='".mysql_real_escape_string($_POST[precode])."'");

            if (sql_num_rows($get_code) == 0)
            {
                // Namen ändern
                ErrorMessage(MSG_REGISTER,e004);
             }
        }

        $get_email_names = sql_query("SELECT 1 FROM userdata WHERE email='".mysql_real_escape_string($_POST[email1])."'");
        if (sql_num_rows($get_email_names))
        {
        	$get_usarios = sql_query("SELECT 1 FROM usarios INNER JOIN userdata ON usarios.ID=userdata.ID WHERE email='".mysql_real_escape_string($_POST[email1])."'");
        	if(sql_num_rows($get_usarios))
        	{
            	// email ändern
            	ErrorMessage(MSG_REGISTER,e010);
        	}else{
        		// alter Spielaccount schon vorhanden
        	}
        	
        }

        
        
////////////////////// ENDE FEHLERMELDUNGEN ///////////////////////////////

        
 		$alt_usarios = sql_query("SELECT ID, user FROM userdata WHERE email='".mysql_real_escape_string($_POST[email1])."'");
 		if(sql_num_rows($alt_usarios)) {
 			// Alter Account schon vorhanden
 			$alt = 1;
 		}      
        
        
        //******************** determine position of new city *************************************
        if (!ErrorMessage(0))
        {
            list($x,$y,$z) = get_new_standard_coordinates();

            if (substr($REMOTE_ADDR,0,7) == "152.163" ||
                substr($REMOTE_ADDR,0,6) == "195.93" ||
                substr($REMOTE_ADDR,0,5) == "64.12" ||
                substr($REMOTE_ADDR,0,6) == "198.81" ||
                substr($REMOTE_ADDR,0,9) == "202.67.64" ||
                substr($REMOTE_ADDR,0,6) == "205.188")
            {
                $ip_chk = "Y";
            }
            else
            {
                $ip_chk = "N";
            }

            $registerTime = time();
            $confirmCode  = getConfirmCode();
            $aboNews = ($_POST[newsLetterCheck]) ? 'Y' : 'N';

            // write data to data base
            $md5_passwort = $_POST[password1] . $_POST[email1] . "B3stBr0ws3rg4m33v3r";
            $md5_passwort = md5($md5_passwort);
            $md5_passwort = md5($md5_passwort);
            $md5_passwort = md5($md5_passwort);
	        
	        if($alt != 1) {
	        	$success = sql_query("INSERT INTO userdata (user,
	                                                        email,
	                                                        password,
	                                                        plunder_iridium,
	                                                        plunder_holzium,
	                                                        plunder_water,
	                                                        plunder_oxygen,
	                                                        register,
	                                                        noipchk,
	                                                        confirm_code,
	                                                        abo_news)
	                                         VALUES ('". mysql_real_escape_string($_POST[new_user]) ."',
	                                                 '". mysql_real_escape_string($_POST[email1]) ."',
	                                                 '$md5_passwort',
	                                                 '1','2','3','4',
	                                                 '". $registerTime ."',
	                                                 '$ip_chk',
	                                                 '".$confirmCode."',
	                                                 '".$aboNews."'
	                                                 )");
	        	
	        	sql_query("INSERT INTO global_logs (seite, inhalt, datum) VALUES ('register.php', '[REGISTRIERUNG] ::::: Ein neuer Spieler! ".mysql_real_escape_string($_POST[new_user]) ."', '$registerTime');");
            }
            if ($success || $alt)
            {
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
			
				$userID = sql_query("SELECT ID,user FROM userdata WHERE user='".mysql_real_escape_string($_POST[new_user])."'");
				$userID = sql_fetch_array($userID);
				
				if($alt == 1) {
					$_POST['new_user'] = $userID['user'];
				}
				
				$sql_user = "user='$userID[ID]'";
				$sql_city = "city='$x:$y:$z'";
				
                sql_query("INSERT INTO usarios (ID,user,login,points) VALUES ('$userID[ID]','".mysql_real_escape_string($_POST[new_user])."','$reset_time','5')");
                sql_query("INSERT INTO city (user,city,home,x_pos,y_pos,z_pos,city_name,text,foundation,r_time,r_time_oxygen,pos) VALUES ('$userID[ID]','$x:$y:$z','YES','$x','$y','$z','". mysql_real_escape_string($_POST[city_name]) ."','','$reset_time','$reset_time','$reset_time',0)");

                //medaillen vorselektieren
                sql_query("INSERT INTO medals (user) VALUES ('$userID[ID]')");
                
                if (time() >= BEGIN_USER_BONUS) {
					//Gebäude Bonus für späteinsteiger
					foreach ( $register_bonus as $field ) 
					{	
						$get_median = sql_query("SELECT $field FROM city WHERE home='YES' ORDER BY $field ASC LIMIT $count_user,1");
						$median_temp = sql_fetch_array($get_median);
						// 70% des Medians werden gegeben
						$median_value[$field] = round(0.7*$median_temp[$field]);
						$update = "UPDATE city SET $field = $median_value[$field], d_neutronwoofer=b_defense_center*" . TURRETS_PER_LEVEL . " WHERE ".$sql_city; 
						
						sql_query($update);
					}
					sql_query("UPDATE city SET b_airport = 1 WHERE ".$sql_user);
					//sql_query("UPDATE city SET b_airport = 1, b_shield = $median_value[b_work_board]/2, c_active_shields = $median_value[b_work_board]/2 WHERE ".$sql_user);
                }
				else { 
			    	sql_query("UPDATE city SET b_work_board = 5 WHERE ".$sql_city);  
				}					
					sql_query("UPDATE usarios INNER JOIN city ON city.user=usarios.ID SET usarios.t_depot_management=city.t_depot_management,usarios.t_water_compression=city.t_water_compression,usarios.t_mining=city.t_mining WHERE city.city='$x:$y:$z'");				
					
					sql_query("UPDATE city SET points=b_iridium_mine+b_holzium_plantage+b_water_derrick+b_oxygen_reactor+b_depot+b_oxygen_depot+b_trade_center+b_hangar+".
						"b_airport+b_defense_center+b_shield+b_technologie_center+b_communication_center+b_work_board WHERE ".$sql_city);
					sql_query("UPDATE usarios SET points=0,tech_points=t_oxidationsdrive+t_hoverdrive+t_antigravitydrive+t_electronsequenzweapons+t_protonsequenzweapons+".
						"t_neutronsequenzweapons+t_consumption_reduction+t_computer_management+t_water_compression+t_depot_management+t_mining+t_plane_size+t_shield_tech ".
						"WHERE ID='$userID[ID]'");
					sql_query("UPDATE usarios SET points=tech_points + (SELECT sum(points) FROM city WHERE $sql_user) WHERE ID='$userID[ID]'");
									
				// Wechsel in Stadthistorie vermerken
                sql_query("INSERT INTO city_history (city, owner, time) VALUES ('$x:$y:$z','".mysql_real_escape_string($_POST[new_user])."',$reset_time)");

                if(is_file('../templates/welcome_msg.txt')) {
                    $welcome = implode('', file('../templates/welcome_msg.txt'));
                    $welcome = str_replace('%%%NAME%%%', $_POST[new_user], $welcome);
                    $welcome = str_replace('%%%COORDS%%%', "$x:$y:$z", $welcome);
                    #### TODO ####
                    sql_query(
                        "INSERT INTO news_igm_umid (sender,recipient,owner,time,topic,text,confirm,dir) VALUES (
                        '". $MESSAGES[MSG_REGISTER]['m027']."','$userID[ID]','$userID[ID]'," .
                        $reset_time . ",'".$MESSAGES[MSG_REGISTER]['m028']."','" . mysql_real_escape_string($welcome) . "','N',0)"
                    );
                }
                
                
                sql_query("INSERT INTO new_tutorial (user, tutorial) VALUES ('$userID[ID]', '999')");

                // send confirmation mail

                if($alt == 1) {
                	$success = "success :)";
                	smtp_mail($_POST[email1],
						$MESSAGES[MSG_REGISTER]['m021'],
						$MESSAGES[MSG_REGISTER]['m022']. ' '.$_POST[new_user].', <br /><br />'.
						$MESSAGES[MSG_REGISTER]['m023']. ' <br />'.
						$MESSAGES[MSG_REGISTER]['m024']. ' <br />'
						.html_entity_decode($goodbye).' <br />'.$liable.' <br />'.$etsName);
                	$SUC_MSG = $MESSAGES[MSG_REGISTER][confirmSuccess];
                }else{
                	$confirm_link = $etsAddress.'/page/confirm.php?u='.$new_user.'&code='.$confirmCode;
	                smtp_mail($_POST[email1],
						$MESSAGES[MSG_REGISTER]['m021'],
	                    $MESSAGES[MSG_REGISTER]['m022']. ' '.$_POST[new_user].', <br /><br />'.
	                    $MESSAGES[MSG_REGISTER]['m025']. ' <br />'.
	                    '<a href="'. $confirm_link .'">'. $confirm_link .'</a> <br /><br />'.
	                    $MESSAGES[MSG_REGISTER]['m026']. ' <br/><br/>'
	                    .html_entity_decode($goodbye).' <br />'.$liable.' <br />'.$etsName
	                );
					$SUC_MSG = $MESSAGES[MSG_REGISTER][registerSuccess];
				}
            }
        }
    }


    // set basis path for links
    $template->set('forumAddress', $forumAddress);
    $template->set('wikiAddress', $wikiAddress);
    $template->set('etsAddress', $etsAddress);

    // set page title
    $template->set('pageTitle', $MESSAGES[MSG_REGISTER][title]);

    // headline
    $template->set('headLine', $MESSAGES[MSG_REGISTER][m000]);

    // set error and message list

    $template->set('errorList', ErrorMessage());
    $template->set('messageList', $SUC_MSG);

    // formular target
    $template->set('formularTarget', $_SERVER['PHP_SELF']);

    // Eintragung von Feldern wenn schon ausgefüllt aber irgendeine Fehlermeldung kam
    if($_POST['privacyCheck'])
    	$template->set('privacyCheck_', 		$_POST['privacyCheck']);
    if($_POST['agb1'])
    	$template->set('agb1_', 					$_POST['agb1']);
    if(!$_POST['newsLetterCheck'])
    	$template->set('newsLetterCheckLabel_', $_POST['newsLetterCheck']);
    
    // nick name
    $template->set('nickLabel',         $MESSAGES[MSG_REGISTER][m002]); // field name
    $template->set('nickValue',         $new_user);                     // field value
    $template->set('nickInfo',          $MESSAGES[MSG_REGISTER][m013]); // info span

    // email
    $template->set('mailLabel',         $MESSAGES[MSG_REGISTER][m003]); // label
    $template->set('mailValue',         $_POST[email1]);                // value
    $template->set('mailInfo',          $MESSAGES[MSG_REGISTER][m014]); // info
    $template->set('mailRepeatLabel',   $MESSAGES[MSG_REGISTER][m004]); // label
    $template->set('mailRepeatValue',   $_POST[email2]);                // value

    // password
    $template->set('passLabel',         $MESSAGES[MSG_REGISTER][m005]); // label
    $template->set('passInfo',          $MESSAGES[MSG_REGISTER][m999]); // info
    $template->set('passInfo2',         $MESSAGES[MSG_REGISTER][m015]); // info
    $template->set('passRepeatLabel',   $MESSAGES[MSG_REGISTER][m004]); // label
    $template->set('passValue', 		$_POST[password1]);
    $template->set('passValue2', 		$_POST[password2]);

    // city
    $template->set('cityNameLabel',     $MESSAGES[MSG_REGISTER][m006]); // label
    $template->set('cityNameValue',     $_POST[city_name]);             // value
    $template->set('cityNameInfo',      $MESSAGES[MSG_REGISTER][m016]); // info

    // agb
    $template->set('agbLabel',          $MESSAGES[MSG_REGISTER][m020]); // info

    // precode
    $template->set('precodeInfoLabel',  $MESSAGES[MSG_REGISTER][precodeInfoLabel]);
    $template->set('precodeLabel',      $MESSAGES[MSG_REGISTER][precodeLabel]);
    $template->set('precode',           $_POST[precode]);             // value

    // privacy
    $template->set('privacyInfoLabel',  $MESSAGES[MSG_REGISTER][privacyInfoLabel]);
    $template->set('privacyCheckLabel', $MESSAGES[MSG_REGISTER][privacyCheckLabel]);

    // newsLetter
    $template->set('newsLetterInfoLabel',  $MESSAGES[MSG_REGISTER][newsLetterInfoLabel]);
    $template->set('newsLetterCheckLabel', $MESSAGES[MSG_REGISTER][newsLetterCheckLabel]);

    // button
    $template->set('submitButton',        $MESSAGES[MSG_REGISTER][submitButton]); // submit button
    $template->set('moreInfoButton',        $MESSAGES[MSG_REGISTER][moreInfoButton]); // more info button
    $template->set('cancelButton',        $MESSAGES[MSG_REGISTER][cancelButton]); // cancel button

    $template->set('max_continents', MAX_CONTINENT);

    // success?
    $template->set('success', $success);

    // create html page
    try {
    echo $template->execute();
    }
    catch (Exception $e) { echo $e->getMessage(); }
?>
