<?php
  require_once("database.php");
  
 // define phptal template
  require_once("PHPTAL.php");
  require_once("include/PHPTAL_EtsTranslator.php");
  //$template = new PHPTAL('guest/alt_login.html');
  $template = new PHPTAL('guest/standardGuest.html');
  $template->setTranslator(new PHPTAL_EtsTranslator());
  $template->setEncoding('ISO-8859-1');
  
require_once("include/TemplateSettingsCommonGuest.php");
  
  // set page title
  $template->set('pageTitle', 'Einmaliger Passwortreset');  

  $pfuschOutput = "";  
  
  if($_POST['email']) {
  	$email = mysql_real_escape_string($_POST['email']);
  	$user = mysql_real_escape_string($_POST['user']);
  	$pwd = mysql_real_escape_string($_POST['spwd']);
  	
  	$select = sql_query("SELECT password, user FROM userdata WHERE email='" . addslashes(htmlspecialchars($_POST['email'],ENT_QUOTES)) ."'");
  	$select = sql_fetch_array($select);
  	
  	if($select['user'] == $user) {
  		if($select['password'] == "reset") {
  			if($_POST['spwd'] == "") {
  				$pfuschOutput .= "Gib bitte ein Passwort ein.<br /><br /><br /><br />
  				Deine Sicherheit liegt uns am Herzen, darum speichern wir Passwörter zukünftig mehrfach gehasht mit Zusätzen.<br />
		  		Hierfür mussten wir jedoch alle Passwörter resetten. Gib bitte ein neues (oder auch das alte) Passwort ein um fortzufahren. <br />
  				Es dient deiner Sicherheit.<br /><br />
  				<b>Dies dient lediglich deiner Sicherheit. Einen Diebstahl von Daten gab es nicht.</b><br /><br />
  				<form action='reset.php' method='post'>
  				E-Mail Adresse: <input name='email' type='text' /><br />
  				Username: <input name='user' type='text' /><br />
  				Neues Passwort: <input name='spwd' type='text' />
  				<br /><br />
  				<input type='submit' value='Speichern' /></form>";
  			}else{
  				// Neuer Passworthash - Inhalt: Passwort + E-Mail Adresse + Salz, dazu dreifaches MD5
	    		$md5_password = $_POST['spwd'] . $_POST['email'] . "B3stBr0ws3rg4m33v3r";
	    		$md5_password = md5($md5_password);
	    		$md5_password = md5($md5_password);
	    		$md5_password = md5($md5_password);
	    		sql_query("UPDATE userdata SET `password` = '$md5_password' WHERE `user` = '$user'");
	    		$pfuschOutput .= "Dein Password wurde erfolgreich aktualisiert. Klicke nun auf 'Erde II betreten' um dich einzuloggen.";
  			}
  		}
  	}else{
  		$pfuschOutput .= "<font color=red>Die Kombination aus E-Mail Adresse und Username stimmt nicht. Gib deine Daten bitte nochmals korrekt ein.</font><br /><br /><br /><br />
  				Deine Sicherheit liegt uns am Herzen, darum speichern wir Passwörter zukünftig mehrfach gehasht mit Zusätzen.<br />
  		Hierfür mussten wir jedoch alle Passwörter resetten. Gib bitte ein neues (oder auch das alte) Passwort ein um fortzufahren. <br />
  		Es dient deiner Sicherheit.<br /><br />
  		<b>Dies dient lediglich deiner Sicherheit. Einen Diebstahl von Daten gab es nicht.</b><br /><br />
  		<form action='reset.php' method='post'>
  		E-Mail Adresse: <input name='email' type='text' /><br />
  		Username: <input name='user' type='text' /><br />
  		Neues Passwort: <input name='spwd' type='text' />
  		<br /><br />
  		<input type='submit' value='Speichern' /></form>";
  	}
  }else{
  	$pfuschOutput .= "Deine Sicherheit liegt uns am Herzen, darum speichern wir Passwörter zukünftig mehrfach gehasht mit Zusätzen.<br />
  		Hierfür mussten wir jedoch alle Passwörter resetten. Gib bitte ein neues (oder auch das alte) Passwort ein um fortzufahren. <br />
  		Es dient deiner Sicherheit.<br /><br />
  		<b>Dies dient lediglich deiner Sicherheit. Einen Diebstahl von Daten gab es nicht.</b><br /><br />
  		<form action='reset.php' method='post'>
  		E-Mail Adresse: <input name='email' type='text' /><br />
  		Username: <input name='user' type='text' /><br />
  		Neues Passwort: <input name='spwd' type='text' />
  		<br /><br />
  		<input type='submit' value='Speichern' /></form>";
  }
  
  
  ///////////////////////////////////////////////
  
  /////// do_loop.php so weit fertig für Testbetrieb
  /////// Einfügen von Password in Session fehlt ERLEDIGT
  
  /////// password.php vorbereitet (fertig)
  
  /////// HIER noch Bearbeitung des neuen Passwords einfügen und auf Testbetrieb stellen
  /////// Auch pageTitle etc. anpassen !
  
  /////// Wo kommen noch Passwörter vor?????? ACCOUNTERSTELLUNG !!!!!! (fertig)
  
  ///////////////////////////////////////////////
  
  

  // add pfusch output
  $template->set('pfuschOutput', $pfuschOutput);
  
  // create html page
  try {
  	echo $template->execute();
  }
  catch (Exception $e) { echo $e->getMessage(); }
?>