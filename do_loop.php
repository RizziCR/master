<?php
  /***************************************/
  /*                                     */
  /*  WICHTIG: Bisherige PC ID Cookies:  */
  /*                                     */
  /*  PHPSESSID PHP_SESSID               */
  /*                                     */
  /***************************************/

  require_once("config_general.php");
  require_once("functions.php");

  require_once('include/Logger/LoginForm.php');

  mt_srand((double)microtime()*1000000);

  // Lougout-Funktion
  if ($unset_all_cookies == "YES")
  {
    session_start();
    if (isset($_SESSION['sitter'])) {
        sql_query("UPDATE usarios SET logged_in='NO' WHERE ID='" . $_SESSION['sitter'] . "'");
        sql_query("UPDATE multi_sessions SET logout_time='". time() ."' WHERE id='".$_SESSION['multi_sessid']."'");
    }
    session_destroy();
  }
  else
  {
    if (time() > PAUSE_BEGIN && time() < REG_ALLOWED)
      die(LoginError(PAUSE_TEXT));

    $captchaSuccess = false;
    // special access for picked out users
    $captchaSpecial = false;
    if (isset($_POST['spwd']))
    {
      if (!isset($_COOKIE['PHPSESSID']))
        die(LoginError("Um ETS spielen zu k&ouml;nnen, muss du ALLE Cookies von ETS akzeptieren."));

      session_start();
      /*captcha check */
      if(isset($_POST['handy'])) {
      // ignore captcha if disabled for that email
      $login_exceptions = sql_query("SELECT 1 FROM userdata WHERE email='".
          addslashes(htmlspecialchars($_POST['email'],ENT_QUOTES)) ."' && user_captcha_free='yes'");
      if (sql_num_rows($login_exceptions)) {
          $captchaSuccess = true;
          $captchaSpecial = true;
      } else {
      // otherwise check captcha as usual
        if(!isset($_POST['captcha'])) {
          Logger_LoginForm::log('missing_post_text',false);
          $captchaSuccess = false;
        } else if(!isset($_SESSION['captchaString'])) {
          Logger_LoginForm::log('missing_needed_text',false);
          $captchaSuccess = false;
        } else if(strtoupper($_POST['captcha']) != strtoupper($_SESSION['captchaString'])) {
          markCaptchaWrongForUser($_POST['email'],$_POST['spwd']);
          Logger_LoginForm::log('validation',false);
          die(LoginError("Um dich einzuloggen, mu&szlig;t du den Sicherheitscode korrekt eingeben."));
        } else {
          $captchaSuccess = true;
        }
    }
      } else {
      if(!isset($_POST['captcha_x']) || !isset($_POST['captcha_y']) || !$_POST['captcha_x'] || !$_POST['captcha_y']) {
        Logger_LoginForm::log('missing_circle',false);
        die(LoginError("Um dich einzuloggen, mu&szlig;t du den Sicherheitscode eingeben, d.h. in den Kreis klicken."));
      } else if(!isset($_SESSION['captchaCircle'])) {
        Logger_LoginForm::log('missing_needed_circle',false);
        $captchaSuccess = false;
      } else {
          require_once('include/captcha.php');
          $c = new captcha($_SESSION['captchaCircle']['x'],$_SESSION['captchaCircle']['y'],$_SESSION['captchaCircle']['radius']);
          $captchaSuccess = $c->checkPointInCircle($_POST['captcha_x'],$_POST['captcha_y']);
          if(!$captchaSuccess) {
            markCaptchaWrongForUser($_POST['email'],$_POST['spwd']);
          Logger_LoginForm::log('validation',false);
          die(LoginError("Um dich einzuloggen, mu&szlig;t du genau in den Kreis klicken."));
          }
      }
      }
      /* captcha times out */
      if($captchaSuccess && !$captchaSpecial && (time() - $_SESSION['captchaTime'] > CAPTCHA_TIMEOUT)) {
          markCaptchaWrongForUser($_POST['email'],$_POST['spwd']);
          Logger_LoginForm::log('timeout',false);
          die(LoginError("Dein Sicherheitscode ist abgelaufen. Nach dem Anfordern des Sicherheitscodes hast du ".
            CAPTCHA_TIMEOUT." Sekunden Zeit, ihn zu best&auml;tigen."));
      }

      $text = "Hallo Bürger von Erde II,<br><br>dein Account war nun mehrere Tage unbeobachtet.<br>In dieser Zeit hat es mindestens einen Angriff auf eine deiner Städte gegeben.<br>Da Du Dich so lange nicht eingeloggt hast, haben wir dich aufgrund dessen vorübergehend in den Urlaubs-Modus geschickt, niemand konnte Dich seither angreifen - deine in Auftrag gegebenen Gebäude, Flugzeuge und Türme hingegen haben weiter produziert.<br><br>Viel Spaß beim weiteren Aufbau nach deiner hoffentlich erholenden Pause.<br><br>Mit freundlichen Grüßen,<br>die Erdenregierung";
      
      session_destroy();
      session_start();
      session_regenerate_id();

      $_SESSION["user"] = null;
      $_SESSION["sitter"];
      $_SESSION["sitt_login"] = null;
      $_SESSION["pwd"] = null;
      $_SESSION["city"] = null;

      $_SESSION["noipchk"] = null;
      //$_SESSION["ip"] = $_SERVER['REMOTE_ADDR'];
      $_SESSION["iphash"] = transcodeIp($_SERVER['REMOTE_ADDR']);
      $_SESSION["user_agent"] = $_SERVER['HTTP_USER_AGENT'];

      $_SESSION["user_path"] = null;
      $_SESSION["user_path_css"] = null;
      $_SESSION["next_msg_send"] = null;
      $_SESSION["multi_sessid"] = null;
    }

    /* captcha check */
    if(isset($_POST['spwd']) && !$captchaSuccess) {
        markCaptchaWrongForUser($_POST['email'],$_POST['spwd']);
        Logger_LoginForm::log('general_captcha',false);
        die(LoginError('captcha'));
    }


    if (isset($_POST['spwd']) && !preg_match("/\/(start.php)/",$_SERVER['PHP_SELF'])) {
      Logger_LoginForm::log('wrong_login_script',false);
      die(LoginError());
    }

    if (($_SESSION['noipchk'] != "Y" && $_SESSION['iphash'] != transcodeIp($_SERVER['REMOTE_ADDR'])) || $_SESSION['user_agent'] != $_SERVER['HTTP_USER_AGENT']) {
      Logger_LoginForm::log('session_ip_validation',false);
      die(LoginError());
    }

    if (isset($_POST['spwd']))
    {
    	$get_email = sql_fetch_array ( sql_query ( "SELECT email FROM userdata WHERE email LIKE '". addslashes(htmlspecialchars($_POST['email'],ENT_QUOTES)) . "';"));
    	// Neuer Passworthash - Inhalt: Passwort + E-Mail Adresse + Salz, dazu dreifaches MD5
    	$md5_password = $_POST['spwd'] . $get_email['email'] . "B3stBr0ws3rg4m33v3r";
    	$md5_password = md5($md5_password);
    	$md5_password = md5($md5_password);
    	$md5_password = md5($md5_password);
    			$login_info = sql_query("SELECT ID,user,password,multi,holiday,holiday2,delacc,delacc2,confirmation,time_block,noipchk,user_path,user_path_css,show_attacks,confirm_code,ad_mode,email,user_captcha_blocked,user_captcha_last_try,user_captcha_free FROM userdata ".
          		"WHERE (email='". addslashes(htmlspecialchars($_POST['email'],ENT_QUOTES)) ."' && password='$md5_password') OR (email='". addslashes(htmlspecialchars($_POST['email'],ENT_QUOTES)) ."' && password='reset');");
    
    	
      if (!sql_num_rows($login_info)) {
          Logger_LoginForm::log('credentials',false);
          die(LoginError());
      }

      $login_detail = sql_fetch_array($login_info);

      if($login_detail['password'] == "reset") {
      	Logger_LoginForm::Log('Passwortreset', false);
      	die(LoginError("Um die Sicherheit der Passw&ouml;rter zu erh&ouml;hen wurden s&auml;mtliche Passw&ouml;rter resettet. Klick bitte <a href=\"/page/reset.php\">hier</a>"));
      }
      
      
      if ($login_detail['confirmation'] == "N") {
          Logger_LoginForm::log('registration_confirmation',false);
          die(LoginError("Bitte best&auml;tige deine Registrierung, damit du dich anmelden kannst. Den Freischaltcode hast du per E-Mail erhalten."));
      }

      if ($login_detail['multi'] == "Y") {
          Logger_LoginForm::log('multi_blocked',false);
          die(LoginError("Wegen Verdacht auf Verstoß gegen die AGB ist dein Zugang gesperrt worden. Bitte wende dich unter Angabe deines Nutzernamens an: $supportTextEmail"));
      }

      if ($login_detail['holiday'] > time()) {
          Logger_LoginForm::log('holiday_mode',false);
          die(LoginError("Du hast die Urlaubsfunktion aktiviert - die Anmeldung ist bis ". date("d.m.Y H:i:s",$login_detail['holiday']) ." gesperrt."));
      }

      if ($login_detail['holiday'] <= time() && $login_detail['holiday']!=0) {
        sql_query("UPDATE userdata SET holiday='0', holiday2='0' WHERE ID='$login_detail[ID]'");
      }
      
      if($login_detail['delacc2'] == "N") {
      	$sel = sql_query("SELECT user FROM usarios WHERE ID='$login_detail[ID]'");
      	$sel = mysql_fetch_array($sel);
      	if($sel["user"] != $login_detail["user"])
      		die(LoginError("Du hast keinen Spielaccount für diese Runde. Möchtest du diese Runde mitspielen? Dann klicke bitte <a href=\"$etsAddress/page/newtown.php?user=$login_detail[user]\">hier</a>. Dort musst du nochmal deine Logindaten eingeben"));
      }
      
      if($login_detail['holiday2'] == "2") {
         sql_query("UPDATE userdata SET holiday='0', holiday2='0' WHERE user='$login_detail[user]'");
      	 $test = "INSERT INTO news_igm_umid (sender,recipient,owner,time,topic,text,confirm,dir) VALUES (
      	 	'Reisebüro','$login_detail[user]','$login_detail[user]'," .
                time() . ",'" . addslashes( "Automatischer Urlaubsmodus" ) . "','" . addslashes( $text ) . "','N','0')";
        sql_query($test);
   	 }

      if ($login_detail['time_block'] > time()-86400) {
          Logger_LoginForm::log('block_rules',false);
          die(LoginError("Dein Zugang ist wegen eines Regelverstoßes (z.B. Beleidigungen, Drohungen, etc. gegen andere Spieler) bis ". date("H:i:s d.m.Y",$login_detail['time_block']+86400) ." gesperrt."));
      }

      if ($login_detail['delacc'] > time()) {
          Logger_LoginForm::log('account_delete',false);
          die(LoginError("Dein Zugang wird in Kürze gelöscht. Wenn du ihn wieder herstellen möchtest, klicke bitte <a href=\"$etsAddress/page/recover.php?user=$login_detail[user]&code=". $login_detail['confirm_code'] ."\">hier</a>."));
      }

      if($login_detail['user_captcha_blocked'] == 'yes') {
          if (time () - $login_detail ['user_captcha_last_try'] > 10 * 60) {
            resetCaptchaBlock ( $_POST['email'] );
        } else {
            Logger_LoginForm::log('captcha_block',false);
          die(LoginError("Dein Zugang wurde wegen zuvieler Fehlerversuche beim Login für 10 Minuten gesperrt."));
        }
      }
      
      $select = sql_query("SELECT 1 FROM userdata INNER JOIN usarios ON userdata.user=usarios.user WHERE userdata.holiday2='2' AND usarios.sitter='$login_detail[user]'");
      if(sql_affected_rows($select))
      {
      	sql_query("UPDATE userdata INNER JOIN usarios ON userdata.ID=usarios.ID SET userdata.holiday='0', userdata.holiday2='0' WHERE usarios.sitter='$login_detail[user]'");
      	$select = sql_query("SELECT user FROM usarios WHERE sitter='$login_detail[user]'");
      	$select = sql_fetch_array($select);
      	$test = "INSERT INTO news_igm_umid (sender,recipient,owner,time,topic,text,confirm,dir) VALUES (".
                "'Reisebüro','$select[user]','$select[user]'," .
                time() . ",'" . addslashes( "Automatischer Urlaubsmodus" ) . "','" . addslashes( $text ) . "','N','0')";
        sql_query($test);
      }

      $_SESSION['sitter'] = $login_detail['ID'];

      //$user = $login_detail['user'];
      $_SESSION['user'] = $login_detail['ID'];
      
      $_SESSION['pwd'] = $md5_password;
      
      $_SESSION['noipchk'] = $login_detail['noipchk'];

      $_SESSION['user_path'] = $login_detail['user_path'];
      $_SESSION['user_path_css'] = $login_detail['user_path_css'];

      $_SESSION['show_attacks'] = $login_detail['show_attacks'];
      $_SESSION['ad_mode'] = $login_detail['ad_mode'];

      sql_query("UPDATE usarios SET login=". time() .",logged_in='YES',last_action='". time() ."' WHERE ID='$login_detail[ID]'");
      sql_query("UPDATE userdata SET ip='" . transcodeIp($_SERVER['REMOTE_ADDR']) . "',user_agent='" . $_SERVER['HTTP_USER_AGENT'] . "' WHERE ID='$login_detail[ID]'");

      $trans_ip = transcodeIp($_SERVER['REMOTE_ADDR']);
      $anon_ip = anonIp($_SERVER['REMOTE_ADDR']);

      $get_ip_hash = sql_query("SELECT 1 FROM multi_iphash WHERE iphash='".$trans_ip."'");
      if (!sql_num_rows($get_ip_hash)) {
        include_once('include/phpwhois/whois.main.php');

        $whois = new Whois();
#        $whois->deep_whois = false;
        $whoisresult = $whois->Lookup($_SERVER['REMOTE_ADDR']);

        if(is_array($whoisresult['regrinfo']['owner']['organization']))
          $provider = implode(' ', $whoisresult['regrinfo']['owner']['organization']);
        else
          $provider = $whoisresult['regrinfo']['owner']['organization'];

        sql_query("INSERT INTO multi_iphash VALUES('".$trans_ip."','".$anon_ip."','".addslashes(substr($provider,0,64))."')");
      }

      $get_pc_id = sql_query("SELECT pc_id FROM multi_sessions WHERE id_hash='$_COOKIE[PHPSESSIONID]'");
      if (sql_num_rows($get_pc_id))
      {
        $pc_id = sql_fetch_array($get_pc_id);
        sql_query("INSERT INTO multi_sessions (pc_id,sess_id,user,login_time,ip,client,last_id) VALUES ('$pc_id[pc_id]','". session_id() ."','$login_detail[user]','". time() ."','" . $trans_ip . "','" . $_SERVER['HTTP_USER_AGENT'] . "','$_COOKIE[PHPSESSIONID]')");
        sql_query("UPDATE multi_sessions SET id_hash=MD5(id) WHERE id_hash=''");
        $get_id = sql_query("SELECT Max(id) AS maxid FROM multi_sessions WHERE sess_id='". session_id() ."'");
        $id = sql_fetch_array($get_id);
      }
      else
      {
        sql_query("INSERT INTO multi_sessions (sess_id,user,login_time,ip,client,last_id) VALUES ('". session_id() ."','$login_detail[user]','". time() ."','" . $trans_ip . "','" . $_SERVER['HTTP_USER_AGENT'] . "',md5('0'))");
        sql_query("UPDATE multi_sessions SET id_hash=MD5(id) WHERE id_hash=''");
        $get_id = sql_query("SELECT Max(id) AS maxid FROM multi_sessions WHERE sess_id='". session_id() ."'");
        $id = sql_fetch_array($get_id);
        sql_query("UPDATE multi_sessions SET pc_id='$id[maxid]' WHERE id='$id[maxid]'");
      }

      setcookie("PHPSESSIONID",md5($id['maxid']),time()+365*24*3600);
      $_SESSION['multi_sessid'] = $id['maxid'];

      $get_last_seen = sql_query("SELECT toplist_update FROM usarios WHERE ID='$login_detail[ID]'");
      list( $last_seen ) = sql_fetch_row($get_last_seen);

      // set to true if all power values should be recomputed when you log in
      $update_all_players = false;
      // recompute fame_own of alliances anew from wars
      $recompute_fame = false;
      // recompute generic fame fields of alliances and usarios
      $update_fame = false;
      // set your name - the update initiator
      $update_initiator = "TheKing";
      // this is a special case - only activate when you are sure what you are doing
      if ($update_all_players && "$login_detail[user]" == $update_initiator) {
        $get_names=sql_query("SELECT user from usarios");
        // compute the power for all users
        while (list( $name ) = sql_fetch_row($get_names)) {
          sql_query("UPDATE usarios SET power='". computeUserPower($name) ."', toplist_update='" . time() . "' WHERE user='$name'");
        }
      }
      if ($recompute_fame && "$login_detail[user]" == $update_initiator) {
        $wars_get=sql_query("SELECT count(id) FROM wars");
        list( $wars ) = sql_fetch_row($wars_get);
        for($war_id=1; $war_id < $wars+1; $war_id++)
        {
          sql_query("UPDATE alliances, war_party, wars SET alliances.fame_own=alliances.fame_own+IF(wars.winner='A',wars.fame_A, 0) WHERE alliances.tag=war_party.tag AND war_party.war_id=wars.id AND war_id=$war_id AND side='A'");
          sql_query("UPDATE alliances, war_party, wars SET alliances.fame_own=alliances.fame_own+IF(wars.winner='B',wars.fame_B, 0) WHERE alliances.tag=war_party.tag AND war_party.war_id=wars.id AND war_id=$war_id AND side='B'");
        }
      }
      if ($update_fame && "$login_detail[user]" == $update_initiator)
      {
        $get_alliances=sql_query("select tag from alliances where tag<>''");
        while ($ally = sql_fetch_array($get_alliances))
        {
          recompute_alliance_fame($ally['tag']);
          recompute_user_fame_for_alliance($ally['tag']);
        }
      }
      // usual case - only update power if last session is older than 60 minutes
      else if ($last_seen + 3600 < time())
      {
        sql_query("UPDATE usarios SET power='". computeUserPower($login_detail['user']) ."', toplist_update='" . time() . "' where ID='$login_detail[ID]'");
      }

      //handy special
      if(isset($_POST['handy'])) {
        $_SESSION['isHandy'] = true;
        if(isset($_POST['keeplogindata']) && $_POST['keeplogindata'] == 1) {
            setcookie('keeplogindata',base64_encode($login_detail['email'].'###'.$_POST['spwd']),time()+14*24*3600);
        } else if(isset($_COOKIE['keeplogindata'])){
            //delete old cookie
            setcookie('keeplogindata',null,time()-14*24*3600);
        }
      }
    }
    else
    {
      $login_info = sql_query("SELECT 1 FROM userdata WHERE ID='$_SESSION[sitter]' && password='".$_SESSION['pwd']."' && user_agent='" . $_SERVER['HTTP_USER_AGENT'] . "' && holiday='0' && multi='N' && time_block < ". time() ."-86400 && delacc < ". time());

      if (!sql_num_rows($login_info)) {
        die(LoginError());
      }


      if ($_GET['to_sitter'] && !$_SESSION['sitt_login'])
      {
      	$get_acc_to_sit = sql_query("SELECT ID,logged_in FROM usarios WHERE sitter='$_SESSION[sitter]' && sitter_confirmation='YES'");
        $acc_to_sit = sql_fetch_array($get_acc_to_sit);
        if ($acc_to_sit['logged_in'] == "NO")
        {
          //$user = $acc_to_sit['user'];
          $_SESSION["user"] = $acc_to_sit['ID'];
          $get_acc_denies = "SELECT 1 FROM userdata WHERE ID='$acc_to_sit[ID]' && holiday='0' && multi='N' && time_block < ". time() ."-86400 && delacc < ". time();
          $get_acc_denies = sql_query($get_acc_denies);
          if (!sql_num_rows($get_acc_denies))
          {
          	echo "Warum ende ich hier wenn der SQL-Befehl doch 1 Ausspuckt?????";
            sql_query("UPDATE usarios SET logged_in='NO' WHERE ID='$_SESSION[sitter]'");
            sql_query("UPDATE multi_sessions SET logout_time='". time() ."' WHERE id='".$_SESSION['multi_sessid']."'");

            @session_destroy();

            die(LoginError("Der zu Vertretende befindet sich im Urlaub, ist gesperrt oder wird in Kürze gelöscht. Bitte melde Dich erneut an."));
          }
          else
            $_SESSION['sitt_login'] = true;
        }
      }

      if ($_GET['from_sitter'] && $_SESSION['sitt_login'])
      {
        //$user = $sitter;
        $_SESSION["user"] = $_SESSION['sitter'];
        $_SESSION['sitt_login'] = false;
      }

      if ($_SESSION['sitt_login'])
      {
        $get_acc_denies = sql_query("SELECT 1 FROM userdata WHERE ID='$_SESSION[user]' && holiday='0' && multi='N' && time_block < ". time() ."-86400 && delacc < ". time());
        if (!sql_num_rows($get_acc_denies))
          die(LoginError("Der zu Vertretende befindet sich im Urlaub, ist gesperrt oder wird in Kürze gelöscht. Bitte melde dich erneut an."));

        $get_owner_login = sql_query("SELECT 1 FROM usarios WHERE ID='$_SESSION[user]' && logged_in='YES'");
        if (sql_num_rows($get_owner_login))
          die(LoginError("Du wurdest automatisch abgemeldet, weil der zu Vertretende sich soeben selbst angemeldet hat. Bitte melde dich erneut an."));
      }

      sql_query("UPDATE usarios SET logged_in='YES',last_action='". time() ."' WHERE ID='$_SESSION[sitter]'");
    }

    /* check session_timeout */
    $sql = "SELECT login FROM usarios WHERE ID = '".$_SESSION['sitter']."'";
    $res = sql_query($sql);
    $row = sql_fetch_assoc($res);
    $loginTime = $row['login'];
    if(time() - $loginTime > SESSION_TIMEOUT) {
        //copy logout funktion
        //session_start();

        sql_query("UPDATE usarios SET logged_in='NO' WHERE ID='$_SESSION[sitter]'");
        sql_query("UPDATE multi_sessions SET logout_time='". time() ."' WHERE id='".$_SESSION['multi_sessid']."'");

        // session_destroy() is called by LoginError
        die(LoginError("Deine Besuchszeit war abgelaufen und du hast den Besuch nicht verlängert."));
    } else if(time() - $loginTime > SESSION_TIMEOUT_WARNING) {
        define('DISPLAY_SESSION_TIMEOUT_WARNING',($loginTime + SESSION_TIMEOUT) - time());
    }

    $change_city = $_REQUEST['change_city'];

    if ((!$change_city && !$_SESSION['city']) || $_GET['to_sitter'] || $_GET['from_sitter'] || !$_SESSION['city'])
    {
      $get_cities = sql_query("SELECT ID,city FROM city WHERE user='$_SESSION[user]' && home='YES'");
      $get_cities = sql_fetch_array($get_cities);
      $_SESSION['city'] = $get_cities['ID'];
    }

    if ($change_city)
    {
      $check_city_sql_inc = split(":",$change_city);
      if (count($check_city_sql_inc) != 3 || strlen($check_city_sql_inc[0]) > 2 || strlen($check_city_sql_inc[1]) > 3 || strlen($check_city_sql_inc[2]) > 2)
      {
        mail($debugEmail,"blubb: SQL insertion at change_city","$_SESSION[user] :: $change_city");
        die();
      }

      $check_existing_cities = sql_query("SELECT ID FROM city WHERE user='$_SESSION[user]' && city='". htmlspecialchars($change_city,ENT_QUOTES) ."'");
      if (sql_num_rows($check_existing_cities)) {
      	$change_city = sql_fetch_array($check_existing_cities);
        $_SESSION['city'] = $change_city['ID'];
      }
    }

    $timefixed_depot = new Lager($_SESSION['city']);
    $thisUser = new User($_SESSION["user"]);

    $ressi_query = sql_query("SELECT city.r_time AS r_time,".
        "city.b_iridium_mine AS b_iridium_mine,city.b_holzium_plantage AS b_holzium_plantage,city.b_water_derrick AS b_water_derrick,".
        "city.b_oxygen_reactor AS b_oxygen_reactor,city.b_depot AS b_depot,city.b_oxygen_depot AS b_oxygen_depot,usarios.t_mining AS t_mining,".
        "usarios.t_water_compression AS t_water_compression,usarios.t_depot_management AS t_depot_management ".
        "FROM city LEFT JOIN usarios ON city.user=usarios.user WHERE city.ID='$_SESSION[city]'");
    $rdata = sql_fetch_array($ressi_query);

    if(time() >= PAUSE_END || time() < PAUSE_BEGIN) {
        $timefixed_depot->fixTime(time() - $rdata['r_time'],
            $ir_factor = Foerderung(IRIDIUM,$rdata['b_iridium_mine'],$rdata['t_mining'])/3600,
            $hz_factor = Foerderung(HOLZIUM,$rdata['b_holzium_plantage'],$rdata['t_mining'])/3600,
            $wa_factor = (Foerderung(WATER,$rdata['b_water_derrick'],null) - Verbrauch(WATER,$rdata['b_oxygen_reactor']))/3600,
            $ox_factor = Foerderung(OXYGEN,$rdata['b_oxygen_reactor'],$rdata['t_water_compression'],1,1,$timefixed_depot->getWater(),$rdata['b_water_derrick'])/3600,
            Foerderung(WATER,$rdata['b_water_derrick'],null)*pow($t_increase[COMPRESSION],$rdata['t_water_compression'])/3.5/3600
        );
    }
  }

  $actualPage = $_SERVER['SCRIPT_NAME'];
  if(strpos($actualPage,'refreshsession.php')===false) {
      if(strpos($actualPage,'messages_berichte')===false) {
          if(strpos($actualPage,'city_short.php')===false) {
              $_SESSION['lastPage'] = $actualPage;
          }
      }
  }

?>
