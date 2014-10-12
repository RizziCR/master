<?php
  require_once("msgs.php");
  require_once("database.php");
  require_once("constants.php");
  require_once("functions.php");
  require_once("do_loop.php");
  require_once('include/captcha.php');
  //count tries...
  $lastPage = './start.php';
  if(isset($_SESSION['lastPage'])) {
  	$lastPage = $_SESSION['lastPage'];
  }
  
  if(!$_SESSION['isHandy']) {
	  if(isset($_POST['circle_x'])) {
	    $c = new captcha($_SESSION['captchaCircle']['x'],$_SESSION['captchaCircle']['y'],$_SESSION['captchaCircle']['radius']);
	    $captchaSuccess = $c->checkPointInCircle($_POST['circle_x'],$_POST['circle_y']);
	    $lag = time() - $_SESSION['captchaTime'];
	    if($_SESSION['captchaWrongCounter'] < 5 && $captchaSuccess && !($lag > CAPTCHA_TIMEOUT)) {
	    	$sql = "UPDATE usarios SET login = '".time()."' WHERE ID = '".$_SESSION['sitter']."'";
	    	$res = sql_query($sql);
	    	
	    	session_regenerate_id(false);
	    	
	    	unset($_SESSION['captchaCircle']);
	    	unset($_SESSION['captchaTime']);
	    	unset($_SESSION['captchaString']);
	    	$_SESSION['captchaWrongCounter'] = 0;
	    	header('Location: '.$lastPage);
	    	exit;
	    } else {
	    	$_SESSION['captchaWrongCounter']++;
	    }
	  }
  } else {
  	$lag = time() - $_SESSION['captchaTime'];
  	if(	
  		$_SESSION['captchaWrongCounter'] < 5 &&
  		isset($_POST['captchaString']) && 
  		isset($_SESSION['captchaString']) && 
  		$_SESSION['captchaString'] == strtoupper($_POST['captchaString'])) {
  		if( $lag < CAPTCHA_TIMEOUT) {
	    	$sql = "UPDATE usarios SET login = '".time()."' WHERE ID = '".$_SESSION['sitter']."'";
	    	$res = sql_query($sql);
	    	
	    	session_regenerate_id(false);
	    	
	    	unset($_SESSION['captchaCircle']);
	    	unset($_SESSION['captchaTime']);
	    	unset($_SESSION['captchaString']);
	    	$_SESSION['captchaWrongCounter'] = 0;
	    	header('Location: '.$lastPage);
	    	exit;
  		} else {
	    	$_SESSION['captchaWrongCounter']++;
	    }
  	}
  }
  
  if(!($_SESSION['captchaWrongCounter'] < 5) ) {
  	die(LoginError('Du hast den Sicherheitscode zu oft falsch eingegeben. Bitte Logge dich neu ein.'));
  }
  
  unset($_SESSION['captchaCircle']);
  unset($_SESSION['captchaTime']);
  unset($_SESSION['captchaString']);
  
  // define phptal template
  require_once("PHPTAL.php");
  require_once("include/PHPTAL_EtsTranslator.php");
  $template = new PHPTAL('refreshsession.html');
  $template->setTranslator(new PHPTAL_EtsTranslator());
  $template->setEncoding('ISO-8859-1');  
  
  // set page title
  $template->set('pageTitle', 'Übersichten - Städte');  

  $pfuschOutput = "";
  
  $template->set('time',time());
  $template->set('path',$etsAddress);
  $template->set('isHandy',$_SESSION['isHandy']);
  
   // set page title
  $template->set('pageTitle', 'Session verlängern');  
  
  // add pfusch output
  $template->set('pfuschOutput', $pfuschOutput);  


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
