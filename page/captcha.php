<?php
session_start();

$regenerate = true;
/* check if it is a regen call or a already seen call*/
if(!isset($_SESSION['captchaSeen']) || $_SESSION['captchaSeen'] == false ) {
	$_SESSION['captchaSeen'] = true;
	$regenerate = false;
} 

//ToDo: Aufraeumen: Datei gehoert nicht in dieses Verzeichnis 
if(isset($_REQUEST['handy'])) {
    require_once('include/captcha_text.php');
    if(isset($_SESSION['captchaString']) && !$regenerate) {
    	$captcha = new captcha($_SESSION['captchaString']);
    } else {
    	$captcha = new captcha();
	    $_SESSION['captchaString'] = $captcha->fetchString();
	    $_SESSION['captchaTime'] = time();
    }
    $captcha->show();    
} else {
    require_once('include/captcha.php');
    if(isset($_SESSION['captchaCircle']) && !$regenerate) {
    	$captcha = new captcha($_SESSION['captchaCircle']['x'],$_SESSION['captchaCircle']['y'],$_SESSION['captchaCircle']['radius']);
    } else {
    	$captcha = new captcha();
    	$_SESSION['captchaCircle'] = $captcha->getCircle();
    	$_SESSION['captchaTime'] = time();
    }
    $captcha->draw();
    $captcha->display();    
}
