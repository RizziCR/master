<?php

class Logger_LoginForm {
	public static function log($response,$captchaSuccess = false) {
		if($response != 'credentials') return;
		$identity = $_POST['email'];
		if(isset($_REQUEST['handy'])) {
			$captchaNeedValue = $_SESSION['captchaString'];
			$captchaPostValue = $_REQUEST['captcha'];
		} else {
			$captchaNeedValue = $_SESSION['captchaCircle']['x'].':'.$_SESSION['captchaCircle']['y'];
			$captchaPostValue = $_REQUEST['captcha_x'].':'.$_REQUEST['captcha_y'];
		}
		
		$responseDebugData = print_r(
				array(
					'PHP_SESSION'=>$_COOKIE['PHPSESSID'],
					'ETS_MULTI'=>$_COOKIE['PHPSESSIONID'],
					'IPHASH'=>$_SESSION['iphash']
				),true);
		
		
		$sql = "INSERT INTO logs_login SET ";
		$sql.= "identity = '".addslashes($identity)."',";
		$sql.= "response_code = '".addslashes($response)."',";
		$sql.= "response_debug_data = '".addslashes($responseDebugData)."',";
		$sql.= "`time` = '".date('Y-m-d H:i:s')."',";
		$sql.= "user_agent= '".addslashes($_SERVER['HTTP_USER_AGENT'])."',";
		$sql.= "captcha_success= '".($captchaSuccess?'yes':'no')."',";
		$sql.= "captcha_need_value= '".$captchaNeedValue."',";
		$sql.= "captcha_post_value= '".addslashes($captchaPostValue)."'";
		sql_query($sql);
	}
}
