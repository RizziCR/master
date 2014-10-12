<?php
  // $use_lib = ?; // MSG_ADMINISTRATION

  require_once("msgs.php");
  require_once("database.php");
  require_once("constants.php");
  require_once("functions.php");
  require_once("do_loop.php");

  // define phptal template
  require_once("PHPTAL.php");
  require_once("include/PHPTAL_EtsTranslator.php");
  $template = new PHPTAL('preferences.html');
  $template->setTranslator(new PHPTAL_EtsTranslator());
  $template->setEncoding('ISO-8859-1');

  // set page title
  $template->set('pageTitle', 'Verwaltung - Account');


  // insert specific page logic here

  require_once 'include/class_Krieg.php';

  $template->suc_msg1 = $template->suc_msg2 = $template->suc_msg3 = $template->suc_msg4 = $template->suc_msg5 = '';
  $template->err_msg1 = $template->err_msg2 = $template->err_msg3 = $template->err_msg4 = $template->err_msg5 = '';

  $query_name_email = null;

  if ($_SESSION[sitt_login])
    ErrorMessage(MSG_GENERAL,e000);  // Die Funktion ist für Sitter gesperrt

  if (ErrorMessage(0))
  {
    $errorMessage .= "  <h1>Einstellungen</h1>";
    $errorMessage .= ErrorMessage();

    // add pfusch output
    $template->set('errorMessage', $errorMessage);

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

  switch ($_POST[action])
  {
      case 'password':
      if (!empty( $_POST[old_pwd] ))
      {
      		$select = sql_query("SELECT email FROM userdata WHERE ID='" . $_SESSION['user'] . "'");
          	$select = sql_fetch_array($select);
          	$md5_password = $_POST['old_pwd'] . $select['email'] . "B3stBr0ws3rg4m33v3r";
	    	$md5_password = md5($md5_password);
	    	$md5_password = md5($md5_password);
	    	$md5_password = md5($md5_password);
            
        if ($md5_password == $_SESSION[pwd])
        {
          if (!empty($_POST[new_pwd]) && !empty($_POST[new_pwd2]) && $_POST[new_pwd] == $_POST[new_pwd2])
          {
          	$md5_password2 = $_POST['new_pwd'] . $select['email'] . "B3stBr0ws3rg4m33v3r";
	    	$md5_password2 = md5($md5_password2);
	    	$md5_password2 = md5($md5_password2);
	    	$md5_password2 = md5($md5_password2);
          	sql_query("UPDATE userdata SET password='". $md5_password2 ."' WHERE ID='$_SESSION[user]'");
            $_SESSION[pwd] = md5($_POST[new_pwd]);
          }
          else
            $ERR_MSG .= "Passw&ouml;rter nicht identisch bzw. nicht gesetzt<br>";
        }
        else
          $ERR_MSG .= "Falsches Kennwort.<br>";
      }
      if (!$ERR_MSG)
        $SUC_MSG = "Die &Auml;nderungen wurden &uuml;bernommen";
      $template->suc_msg2 = $SUC_MSG;
      $template->err_msg2 = $ERR_MSG;
      break;

    case 'design':

        if (strlen($_POST[n_user_path]) == 0)
            $newUserPathIMG = $imgAddress;
        else
            $newUserPathIMG = addslashes($_POST[n_user_path]);

        if (strlen($_POST[n_user_path_css]) == 0)
            $newUserPathCSS = $cssAddress;
        else
            $newUserPathCSS = addslashes($_POST[n_user_path_css]);

        sql_query("UPDATE userdata SET user_path='".$newUserPathIMG."',user_path_css='".$newUserPathCSS."',show_attacks=0".intval($_POST[show_attacks]).",ad_mode='".addslashes($_POST[ad_mode])."' WHERE ID='$_SESSION[user]'");
      $_SESSION[user_path] = htmlspecialchars($newUserPathIMG,ENT_QUOTES);
      $_SESSION[user_path_css] = htmlspecialchars($newUserPathCSS,ENT_QUOTES);
      $_SESSION[show_attacks] = intval($_POST[show_attacks]);
      $_SESSION[ad_mode] = $_POST[ad_mode];
      break;

      case 'misc':
      $_POST[utext] = strip_magic_slashes($_POST[utext]);
      sql_query("UPDATE usarios SET text='". addslashes($_POST[utext]) ."',flightstats=0".intval($_POST[flightstats])." WHERE ID='$_SESSION[user]'");
      sql_query("UPDATE usarios SET text='". addslashes($_POST[utext]) ."',medals=0".intval($_POST[medals])." WHERE ID='$_SESSION[user]'");
      $affix = strip_magic_slashes($_POST[affix]);
      if(!empty($affix)) {
          if(strlen($affix) < 16) {
              if(preg_match('/^[^\'"]+$/', $affix)) {
                  sql_query("UPDATE userdata SET name_affix='". addslashes($affix) ."' WHERE ID='$_SESSION[user]'");
              }
              else $template->err_msg5 = 'Unerlaubte Zeichen im Namenszusatz.';
          }
          else $template->err_msg5 = 'Der Namenszusatz ist zu lang.';
      }
      else
        sql_query("UPDATE userdata SET name_affix=NULL WHERE ID='$_SESSION[user]'");
      break;
    case "save" :
    {
      if ($_POST[new_email])
      {
        $get_user_mail = sql_query("SELECT email FROM userdata WHERE ID='$_SESSION[user]'");
        $user_mail = sql_fetch_array($get_user_mail);

        $code = getConfirmCode();
        sql_query("UPDATE userdata SET confirm_code = '".$code."' WHERE ID = '".$_SESSION['user']."'");


        smtp_mail($user_mail[email],"E-Mail-&Auml;nderungswunsch Best&auml;tigung","Hallo,<br><br>du m&ouml;chtest die E-Mail-Adresse f&uuml;r dein ETS-Benutzerkonto $_SESSION[user] &auml;ndern. Um die &Auml;nderung durchzuf&uuml;hren, klicke bitte auf diesen Link: <a href=\"$etsAddress/page/emailchange.php?u=$_SESSION[user]&e=$user_mail[email]&code=". $code ."\">$etsAddress/page/emailchange.php?u=$_SESSION[user]&e=$user_mail[email]&code=". $code ."</a><br><br>$goodbye<br>$liable<br>[ <a href=\"$etsAddress\">$etsName</a> ]</body></html>","From: Escape To Space <$supportEmail>\r\nReply-To:$supportEmail\r\nContent-type: text/html; charset=iso-8859-1\r\nMIME-Version: 1.0");

        sql_query("UPDATE userdata SET email_new='". addslashes($_POST[new_email]) ."',email_confirm='N' WHERE ID='$_SESSION[user]'");
      }

      if (!$ERR_MSG)
        $SUC_MSG = "Die &Auml;nderungen wurden &uuml;bernommen";

      $show_depots = $nshow_depots;
      
      if ($_POST[nick_change])
      {
	 $get_nick_changed = sql_fetch_array(sql_query("SELECT nick_change FROM userdata WHERE ID='$_SESSION[sitter]'"));
	 if ($get_nick_changed[nick_change] == "N") {
          $get_nick_exists = sql_query("SELECT 1 FROM userdata WHERE user='".addslashes($_POST[nick_change])."'");
          if (!sql_num_rows($get_nick_exists))
          {
            if (strlen($_POST[nick_change]) <= 20)
            {
              if ($_POST[nick_change] == rawurlencode($_POST[nick_change]))
              {
                $new_nick = addslashes($_POST[nick_change]);
                sql_query("UPDATE multi_sessions INNER JOIN userdata ON multi_sessions.user = userdata.user SET multi_sessions.user='$new_nick' WHERE userdata.ID='$_SESSION[user]';");
                sql_query("UPDATE userdata SET user='$new_nick', nick_change='Y' WHERE ID='$_SESSION[sitter]'");
                sql_query("UPDATE usarios SET user='$new_nick' WHERE ID='$_SESSION[sitter]'");

                //session_destroy();
                //die(LoginError("&Auml;nderung erfolgreich! Bitte loggen Sie sich neu ein."));
                $SUC_MSG = "Der Username wurde erfolgreich ge&auml;ndert";
              }
              else
              $ERR_MSG .= "Der Username darf keine Sonderzeichen enthalten<br>";
            }
            else
              $ERR_MSG .= "Der Username ist zu lang<br>";
          }
          else
            $ERR_MSG .= "Der Username ist schon vorhanden<br>";
         } else
		$ERR_MSG .= "Du hast deinen Nickname diese Runde schon ge&auml;ndert!<br>";
      }

      $template->suc_msg1 = $SUC_MSG;
      $template->err_msg1 = $ERR_MSG;
      break;
    }
    case "holiday" :
    {
      sql_query("INSERT INTO holiday (user,time, art) VALUES ('$_SESSION[user]','". (time()+24*3600) ."', '1')");

      $get_user_mail = sql_query("SELECT email FROM userdata WHERE ID='$_SESSION[user]'");
      $user_mail = sql_fetch_array($get_user_mail);

      smtp_mail($user_mail[email],"Aktivierung des Urlaubsmodus!","<html><head></head><body>Hallo,<br><br>Du hast soeben f&uuml;r dein Benutzerkonto den Urlaubsmodus aktiviert. In 24 Stunden wird dieser aktiv.<br><br>$goodbye<br>$liable<br>[ <a href=\"$etsAddress\">$etsName</a> ]</body></html>");

      break;
    }
    case "de_holiday" :
    {
      sql_query("DELETE FROM holiday WHERE user='$_SESSION[user]'");

      $get_user_mail = sql_query("SELECT email FROM userdata WHERE ID='$_SESSION[user]'");
      $user_mail = sql_fetch_array($get_user_mail);

      smtp_mail($user_mail[email],"Deaktivierung des Urlaubsmodus!","<html><head></head><body>Hallo,<br><br>Du hast soeben f&uuml;r dein Benutzerkonto den Urlaubsmodus deaktiviert.<br><br>$goodbye<br>$liable<br>[ <a href=\"$etsAddress\">$etsName</a> ]</body></html>");

      break;
    }
    case "delete" :
    	$select = sql_query("SELECT email FROM userdata WHERE ID='" . $_SESSION['user'] . "'");
    	$select = sql_fetch_array($select);
    	$md5_password = $_POST['pwd_for_delete'] . $select['email'] . "B3stBr0ws3rg4m33v3r";
    	$md5_password = md5($md5_password);
    	$md5_password = md5($md5_password);
    	$md5_password = md5($md5_password);
    	
      if ($md5_password == $_SESSION[pwd])
      {
        switch ($_POST[lfrist])
        {
          case 1 : $ltime = 24; break;
          case 2 : $ltime = 36; break;
          case 3 : $ltime = 48; break;
          default : $ltime = 24; break;
        }
        
        if($_POST["radio"] == "just_now") 
        	$delacc2 = "N"; // Now = Jetzt = Diese Runde - userdata behalten
        else
        	$delacc2 = "K"; // K = Komplett = auch userdata
        	
        $deleteConfirmCode = getConfirmCode();
        sql_query("UPDATE userdata SET confirm_code = '".$deleteConfirmCode."',delacc=". (time()+$ltime*3600) .",delacc2='$delacc2' WHERE ID='$_SESSION[user]'");

        $select = sql_query("SELECT user,alliance FROM usarios WHERE ID='$_SESSION[user]'");
        $select = sql_fetch_array($select);
        sql_query("INSERT INTO delete_reason (user, alliance, reason1, reason2, reason3) VALUES ('$select[user]', '$select[alliance]', '$_POST[reason1]', '$_POST[reason2]', '$_POST[reason3]')");
        
        $get_user_mail = sql_query("SELECT email FROM userdata WHERE user='$ID[user]'");
        $user_mail = sql_fetch_array($get_user_mail);

        if($delacc2 = "N") {
	        smtp_mail($user_mail[email],"ETS Spielkonto-L&ouml;schung f&uuml;r diese Runde!","<html><head></head><body>Hallo,<br><br>du hast soeben die L&ouml;schung deines Spielerkontos f&uuml;r diese Runde bei Escape To Space - Das Online-Strategie-Spiel in Auftrag gegeben. Du hast nun $ltime Stunden Zeit es wieder herzustellen (bitte melde dich dazu ganz normal an, weitere Infos folgen), danach ist dies unter keinen Umst&auml;nden mehr m&ouml;glich und du m&uuml;sstest mit deiner Stadt von vorne beginnen.<br><br>$goodbye<br>$liable<br>[ <a href=\"$etsAddress\">$etsName</a> ]</body></html>");
	
	        die("  <html>
	            <head>
	            <meta http-equiv=refresh content=\"10; URL=$etsAddress/index.php\">
	            </head>
	            <body>
	            Dein Benutzerkonto wird in K&uuml;rze gel&ouml;scht. Vielen Dank f&uuml;r die Teilname an ETS. Wir w&uuml;rden uns freuen, dich vielleicht sp&auml;ter wieder begr&uuml;&szlig;en zu k&ouml;nnen.<br><br>
	            Du wirst nun automatisch zur <a href=$etsAddress/index.php>Startseite</a> weitergeleitet.
	            </body>
	            </html>");
	        break;
        }else{
        	smtp_mail($user_mail[email],"ETS Benutzerkonto-L&ouml;schung!","<html><head></head><body>Hallo,<br><br>du hast soeben die L&ouml;schung deines Benutzerkontos bei Escape To Space - Das Online-Strategie-Spiel in Auftrag gegeben. Du hast nun $ltime Stunden Zeit es wieder herzustellen (bitte melde dich dazu ganz normal an, weitere Infos folgen), danach ist dies unter keinen Umst&auml;nden mehr m&ouml;glich.<br><br>$goodbye<br>$liable<br>[ <a href=\"$etsAddress\">$etsName</a> ]</body></html>");
	
	        die("  <html>
	            <head>
	            <meta http-equiv=refresh content=\"10; URL=$etsAddress/index.php\">
	            </head>
	            <body>
	            Dein Benutzerkonto wird in K&uuml;rze gel&ouml;scht. Vielen Dank f&uuml;r die Teilname an ETS. Wir w&uuml;rden uns freuen, dich vielleicht sp&auml;ter wieder begr&uuml;&szlig;en zu k&ouml;nnen.<br><br>
	            Du wirst nun automatisch zur <a href=$etsAddress/index.php>Startseite</a> weitergeleitet.
	            </body>
	            </html>");
	        break;
        }
      }
      else
        $ERR_MSG = "Falsches Kennwort, um dein Benutzerkonto zu l&ouml;schen";
      $template->suc_msg4 = $SUC_MSG;
      $template->err_msg4 = $ERR_MSG;
      break;

    case "sitter" :
      switch ($_POST[submit_sittaccount])
      {
        case "Beenden" :
          $select = sql_query("SELECT userdata.user FROM usarios INNER JOIN userdata ON usarios.ID = userdata.ID WHERE usarios.sitter = '$_SESSION[user]';");
          $select = sql_fetch_array($select);
          sql_query("INSERT INTO news_er (city,time,topic) SELECT city.ID,'". time() ."','$select[user] m&ouml;chte nicht mehr von dir vertreten werden' FROM city,usarios WHERE usarios.sitter = city.user && city.home='YES' && usarios.user='$_SESSION[user]'");
          sql_query("UPDATE usarios SET sitter='',sitter_confirmation='NO' WHERE ID='$_SESSION[user]'");
          break;
        case "Anfragen" :
          if ($_POST[sitter])
          {
            $check_user = sql_query("SELECT 1 FROM userdata WHERE user='".addslashes($_POST[sitter])."'");
            if (sql_num_rows($check_user))
            {
              $get_sitter_time = sql_query("SELECT 1 FROM usarios WHERE ID='$_SESSION[user]' && sitter_time<=". time());
              if (sql_num_rows($get_sitter_time))
              {
		$get_user = sql_query( "SELECT ID,user FROM usarios WHERE user='" . addslashes($_POST[sitter]) . "'" );
		$sitter = sql_fetch_array( $get_user );
		sql_free_result($get_user);
              	$select = sql_query("SELECT user FROM userdata WHERE ID = '$_SESSION[user]'");
              	$select = sql_fetch_array($select);
                sql_query("UPDATE usarios SET sitter='".addslashes($sitter[ID])."',sitter_confirmation='NO' WHERE ID='$_SESSION[user]'");
                sql_query("INSERT INTO news_er (city,time,topic) SELECT city.ID,'". time() ."','$select[user] möchte von dir vertreten werden' FROM city INNER JOIN userdata ON city.user = userdata.ID WHERE city.home='YES' && userdata.ID='".addslashes($sitter[ID])."'");
              }
              else
                $ERR_MSG .= "Du musst 48 Stunden warten, bis du einen neuen Stellvertreter festlegen darfst.<br>";
            }
            else
              $ERR_MSG .= "Der angegebene Siedler existiert nicht<br>";
          }
          else
            $ERR_MSG .= "Bitte gib einen Siedler als Stellvertreter an<br>";
            break;
        case "Zurueckziehen" :
          $select = sql_query("SELECT user FROM userdata WHERE ID = '$_SESSION[user]'");
          $select = sql_fetch_array($select);
          sql_query("INSERT INTO news_er (city,time,topic) SELECT city.ID,'". time() ."','$select[user] hat seine Stellvertreter-Anfrage zur&ouml;ckgezogen' FROM city INNER JOIN usarios ON city.user = usarios.ID WHERE usarios.sitter = city.user && city.home='YES' && usarios.ID='$_SESSION[user]'");
          sql_query("UPDATE usarios SET sitter='',sitter_confirmation='NO' WHERE ID='$_SESSION[user]'");
          break;
      }


      switch ($_POST[submit])
      {
        case "Beenden" :
          sql_query("INSERT INTO news_er (city,time,topic) SELECT city.ID,'". time() ."','Dein Stellvertreter beendete die Vertretung deiner St&auml;dte' FROM city,usarios WHERE usarios.ID = city.user && city.home='YES' && usarios.sitter='$_SESSION[user]' && usarios.sitter_confirmation='YES'");
          sql_query("UPDATE usarios SET sitter='',sitter_confirmation='NO' WHERE sitter='$_SESSION[user]' && sitter_confirmation='YES'");
          break;

        case "Akzeptieren" :
            $get_sitting_time = sql_query("SELECT 1 FROM usarios WHERE ID='$_SESSION[user]' && sitting_time<=". time());
            if (sql_num_rows($get_sitting_time))
            {
            $select = sql_query("SELECT user FROM userdata WHERE ID = '$_SESSION[user]'");
            $select = sql_fetch_array($select);
            sql_query("UPDATE usarios SET sitter='',sitter_confirmation='NO' WHERE sitter='$_SESSION[user]' && sitter_confirmation='YES'");
            sql_query("UPDATE usarios SET sitter_confirmation='YES',sitter_time='". (time()+48*3600) ."' WHERE user='". addslashes($_POST[sitt_requester]) ."' && sitter='$_SESSION[user]'");
            sql_query("UPDATE usarios SET sitting_time='". (time()+48*3600) ."' WHERE ID='$_SESSION[user]'");
            sql_query("INSERT INTO news_er (city,time,topic) SELECT city.ID,'". time() ."','$select[user] hat deine Stellvertreter-Anfrage akzeptiert' FROM city,usarios WHERE usarios.user = city.user && city.home='YES' && usarios.user='". addslashes($_POST[sitt_requester]) ."'");
          }
          else
            $ERR_MSG .= "Du musst mindestens 48 Stunden warten, bis du wieder eine neue Verwaltung &uuml;bernehmen kannst.<br />";
          break;

        case "Ablehnen" :
          $select = sql_query("SELECT user FROM userdata WHERE ID = '$_SESSION[user]'");
          $select = sql_fetch_array($select);
          sql_query("UPDATE usarios SET sitter='',sitter_confirmation='NO' WHERE user='". addslashes($_POST[sitt_requester]) ."' && sitter='$_SESSION[user]'");
          sql_query("INSERT INTO news_er (city,time,topic) SELECT city.ID,'". time() ."','$select[user] hat deine Stellvertreter-Anfrage abgelehnt' FROM city,usarios WHERE usarios.user = city.user && city.home='YES' && usarios.user='". addslashes($_POST[sitt_requester]) ."'");
          break;
      }
      $template->suc_msg3 = $SUC_MSG;
      $template->err_msg3 = $ERR_MSG;

      break;
  }

  $get_user_prefs = sql_query("SELECT user,email,name,zip,location,birthday,sex,show_attacks,nick_change,name_affix,ad_mode FROM userdata WHERE ID='$_SESSION[user]'");
  $user_prefs1 = sql_fetch_array($get_user_prefs);

  $get_user_prefs = sql_query("SELECT text,sitter,sitter_confirmation,sitter_time,sitting_time,alliance,flightstats,medals FROM usarios WHERE ID='$_SESSION[user]'");
  $user_prefs2 = sql_fetch_array($get_user_prefs);
  
  $select = sql_query("SELECT user FROM userdata WHERE ID = '$user_prefs2[sitter]'");
  $select = sql_fetch_array($select);
  
  $user_prefs2['sitter'] = $select['user'];

  $krieg = new Krieg($user_prefs2[alliance]);

  $template->user_prefs = array_merge((array)$user_prefs1,(array)$user_prefs2);
  $template->user_prefs[text] = stripslashes(str_replace("<br />","\n",$template->user_prefs[text]));

  $acc_to_sit_tmp = array();
  $get_acc_to_sit = sql_query("SELECT user,sitter_confirmation FROM usarios WHERE sitter='$_SESSION[user]'");
  while ($acc_to_sit = sql_fetch_array($get_acc_to_sit)) {
    $acc_to_sit_tmp[] = $acc_to_sit;
  }
  $template->acc_to_sit = $acc_to_sit_tmp;

  $template->sex_chk = "k";
  if(!empty($user_prefs1[sex]))
      $template->sex_chk = $user_prefs1[sex];

  if ($show_depots == YES)
    $lager_chk = "checked";

  $get_holiday = sql_query("SELECT 1 FROM holiday WHERE user='$_SESSION[sitter]'");
  $template->isholiday = sql_num_rows($get_holiday);
  $template->flightstats = 0;
  $template->flightstats_alliance = 0;
  switch (intval($user_prefs2[flightstats])) {
	case 1: $template->flightstats = 1;
		break;
	case 2: $template->flightstats_alliance = 1;
		break;
  }
  $template->medals = 0;
  $template->medals_alliance = 0;
  switch (intval($user_prefs2[medals])) {
	case 1: $template->medals = 1;
		break;
	case 2: $template->medals_alliance = 1;
		break;
  }
  $template->set('ad_mode', $user_prefs1['ad_mode']);
  $template->supportEmail = $supportEmail;
  $template->user_path = stripslashes($_SESSION[user_path]);
  $template->user_path_css = stripslashes($_SESSION[user_path_css]);
  $template->show_attacks = intval($user_prefs1[show_attacks]);
  $template->nick_change = ($user_prefs1[nick_change] == 'Y');

  $template->set( 'war_warning_noleave', $krieg->checkWarOptions(Krieg::NO_LEAVE));
  $template->set( 'war_warning_novacation', $krieg->checkWarOptions(Krieg::NO_VACATION));

  $template->set( 'layouts',
    array(
	array('name'=>'Eigenes Layout',               'value'=>'',
	      'selected' => 0
	),
		array('name'=>'Normales Layout',              'value'=>$etsAddress.'/css/new.css',
	      'selected' => ( $_SESSION[user_path_css] == $etsAddress.'/css/new.css' )
	),
		array('name'=>'Normales Layout für breite Monitore',              'value'=>$etsAddress.'/css/new_breit.css',
	      'selected' => ( $_SESSION[user_path_css] == $etsAddress.'/css/new_breit.css' )
	),
		array('name'=>'Alternatives Layout',              'value'=>$etsAddress.'/css/main.css',
	      'selected' => ( $_SESSION[user_path_css] == $etsAddress.'/css/main.css' )
	),
	array('name'=>'Alternatives für breite Monitore',   'value'=>$etsAddress.'/css/main_wide.css',
	      'selected' => ( $_SESSION[user_path_css] == $etsAddress.'/css/main_wide.css' )
	),
	array('name'=>'Alternatives Layout mit kurzem Bauzentrum', 'value'=>$etsAddress.'/css/main_disabled.css',
	      'selected' => ( $_SESSION[user_path_css] == $etsAddress.'/css/main_disabled.css' )
	),
    )
  );
 // end specific page logic


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
?>
