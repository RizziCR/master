<?php

    require_once 'include/MessageCenterController.php';

    $city = sql_query("SELECT city FROM city WHERE ID='$_SESSION[city]';");
    $city = sql_fetch_array($city);
    
    // set common template parameters
    $template->set('user_path_css', $_SESSION['user_path_css']);
    $template->set('user_path_img', $_SESSION['user_path']);
    $template->set('currentCity', $city['city']);

    $template->set('forumAddress', $forumAddress);
    $template->set('wikiAddress', $wikiAddress);
    $template->set('etsAddress', $etsAddress);
    $template->set('imgAddress', $imgAddress);
    $template->set('blogAddress', $blogAddress);
    $template->set('youtubeAddress', $youtubeAddress);

    $template->set('clockJSCode', $clockJSCode);
    $template->set('serverTime', date("H:i:s"));
    if (isset($js_code))
        $template->set('specificJS', $js_code);


    // Load ACL
    $get_acl = sql_query('SELECT user,acl FROM userdata WHERE ID="'.$_SESSION['user'].'"');
    $acl = sql_fetch_array($get_acl);
    
    switch($acl['acl']) {
        case 'SUPPORT':
            $template->set('acl_support', 1);
            break;
        case 'ADMIN':
            $template->set('acl_support', 1);
            $template->set('acl_admin', 1);
            break;
        default: break;
    }


    // get alliance status
    $get_user_infos = sql_query("SELECT alliance_status,alliance,alliance_seen FROM usarios WHERE ID='$_SESSION[user]'" );
    $user_infos = sql_fetch_array( $get_user_infos );
    $template->set( 'is_admin',
        $user_infos['alliance_status'] == 'admin' || $user_infos['alliance_status'] == 'founder' );


    // city select
    $cityListOption = array();
    $cityListItem   = array();
    $get_user_cities = sql_query("SELECT ID,city FROM city WHERE user='".$_SESSION['user']."' ORDER BY pos ASC");
    while ($h_datas = sql_fetch_array($get_user_cities))
    {
        if ($h_datas['ID'] == $_SESSION['city'])
            $cityListOption[] = "<option value=\"".$h_datas['city']."\" selected=\"selected\">".$h_datas['city']."</option>\n";
        else
            $cityListOption[] = "<option value=\"".$h_datas['city']."\">".$h_datas['city']."</option>\n";
//TODO: remove embeded styles
        $cityListItem[] = '<li><a href="'.$_SERVER['PHP_SELF'].'?change_city='.$h_datas['city'].'"><img src="'.$imgAddress.'/info.gif" class="tooltip" rel="city_short.php?city='.$h_datas['city'].'" style="border:none;margin-left:1px;margin-right:5px;" /> '.$h_datas['city'].'</a></li>'."\n";
    }
    $template->set('cityListOption', $cityListOption);
    $template->set('cityListItem', $cityListItem);
    $template->set('currentPageUrl', $_SERVER['PHP_SELF']);


    // resource and depot values
    $get_city_information = sql_query("SELECT b_hangar,b_defense_center,p_gesamt_flugzeuge FROM city WHERE ID='$_SESSION[city]'");
    $get_city_information = sql_fetch_array($get_city_information);
    $hangarValue = $get_city_information['p_gesamt_flugzeuge'];
    
    $get_build_VZ = sql_query("SELECT count(*) AS turrets FROM jobs_defense WHERE city='$_SESSION[city]'");
    $get_build_VZ = sql_fetch_array($get_build_VZ);
    
    $vorhandene_def = sql_fetch_array ( sql_query ( "SELECT SUM(d_electronwoofer+d_protonwoofer+d_neutronwoofer+d_electronsequenzer+d_protonsequenzer+d_neutronsequenzer) AS def FROM city WHERE ID='$_SESSION[city]'"));
    
    $VZValue = $get_build_VZ['turrets'] + $vorhandene_def['def'];

    $VZSpace = TURRETS_PER_LEVEL*$get_city_information['b_defense_center'];
    $hangarSpace = PLANES_PER_LEVEL*$get_city_information['b_hangar'];
    
    $lager = $timefixed_depot->fillLevelPercent();
    $tank = $timefixed_depot->fillLevelOxygenPercent();
    $lager2 = round($lager,2);
    $tank2 = round($tank,2);
    
    $template->set('resourceCounter', $resourceCounter);
    $template->set('iridiumValue', floor($timefixed_depot->getIridium()));
    $template->set('holziumValue', floor($timefixed_depot->getHolzium()));
    $template->set('waterValue', floor($timefixed_depot->getWater()));
    $template->set('oxygenValue', floor($timefixed_depot->getOxygen()));
    $template->set('depotValue', $lager2);
    $template->set('oxygenDepotValue', $tank2);
    $template->set('hangarValue', $hangarValue);
    $template->set('VZValue', $VZValue);
    $template->set('hangarSpace', $hangarSpace);
    $template->set('VZSpace', $VZSpace);

    // set donator option
    #if (mt_rand(0,10) <= 5)
    #{
        $template->set('enable_voting', 1);
    #}

    // set banner option
    $get_ads_mode = sql_query("SELECT ad_mode FROM userdata WHERE ID='$_SESSION[user]'");
    list( $ads_mode ) = sql_fetch_row($get_ads_mode);
    if($ads_mode == 'A' || $ads_mode == 'I') {
        list($mt, $dummy) = explode(' ',microtime());
        mt_srand($mt*1000000);
        if($ads_mode == 'A' && mt_rand(0,10) <= 6) {
            #if(mt_rand(0,10) <= 7) {
                $template->set('enable_google', 1);
            /*}
            else {
                $get_ads_banner = sql_query("SELECT * FROM alliance_ads WHERE credit > 0 AND approved = 1 AND text = 'thirdParty'");
                $num = sql_num_rows($get_ads_banner);
                if($num > 0) {
                    $i = rand(1, $num);
                    while($i--) {
                        $ads_banner = sql_fetch_assoc($get_ads_banner);
                    }
                    sql_query("UPDATE alliance_ads SET credit = credit - 1 WHERE id=".$ads_banner['id']);
                    $template->set('enable_thirdParty', 1);
                    $template->set('ads_banner', $ads_banner);
                }
            }*/
        }
        else {
            $get_ads_banner = sql_query("SELECT * FROM alliance_ads WHERE credit > 0 AND approved = 1 AND text != 'thirdParty'");
            $num = sql_num_rows($get_ads_banner);
            if($num > 0) {
                $i = rand(1, $num);
                while($i--) {
                    $ads_banner = sql_fetch_assoc($get_ads_banner);
                }
                sql_query("UPDATE alliance_ads SET credit = credit - 1 WHERE id=".$ads_banner['id']);
                $template->set('enable_intern', 1);
                $template->set('ads_banner', $ads_banner);
            }
            else
                $template->set('disable_ads', 1);
        }
    }
    else
        $template->set('disable_ads', 1);


    // session timeout
    if (defined('DISPLAY_SESSION_TIMEOUT_WARNING')) {
        $template->set('sessionTimeout', gmdate('i:s',DISPLAY_SESSION_TIMEOUT_WARNING));
        $template->set('sessionTimeoutCode', $sessionTimeoutCode);
    }


    // sitter account?
    if ($_SESSION['sitt_login'])
    {
        $template->set('sitt_login', true);
    }
    else
    {
        $template->set('sitt_login', false);
        $check_post = sql_query("SELECT Count(*) AS anzahl FROM news_igm_umid WHERE recipient='$_SESSION[sitter]' && seen='N' AND dir=".MessageCenterController::FOLDER_INBOX);
        $get_anzahl_post = sql_fetch_array($check_post);
        $template->set('anzahl_post', $get_anzahl_post['anzahl']);

        $check_allyp = sql_query("SELECT Count(*) AS anzahl FROM news_msg WHERE tag='".$user_infos['alliance']."' AND `time`>".intval($user_infos['alliance_seen']));
        $get_anzahl_allypost = sql_fetch_array($check_allyp);
        $template->set('anzahl_ally', $get_anzahl_allypost['anzahl']);
        $template->set('tmp', "SELECT Count(*) AS anzahl FROM news_msg WHERE tag='".$user_infos['alliance']."' AND `time`>".intval($user_infos['alliance_seen']));
    }


    // sitter selector
    $get_acc_to_sit = sql_query("SELECT user,logged_in FROM usarios WHERE sitter='$_SESSION[user]' && sitter_confirmation='YES'");
    $get_sit_of_acc = sql_query("SELECT sitter FROM usarios WHERE ID = '$_SESSION[user]' && sitter_confirmation = 'YES';");
    
    if (sql_num_rows($get_acc_to_sit) && !$_SESSION['sitt_login'])
    {
        $acc_to_sit = sql_fetch_array($get_acc_to_sit);

        if ($acc_to_sit['logged_in'] == "YES")
            $sitterLoginText = "$acc_to_sit[user] ist da.";
        else
            $sitterLoginText = "<a href=\"" . $_SERVER['PHP_SELF'] . "?to_sitter=true\">$acc_to_sit[user]</a>";

        $template->set('sitterLoginText', $sitterLoginText);
    }
    
    $sit_of_acc = sql_fetch_array($get_sit_of_acc);
    	$sitterName = sql_query("SELECT user FROM userdata WHERE ID = '$sit_of_acc[sitter]'");
    	$sitterName = sql_fetch_array($sitterName);
    if ($_SESSION['sitt_login'])
    {
        $sitterLoginText = "<a href=\"" . $_SERVER['PHP_SELF'] . "?from_sitter=true\">".$sitterName['user']."</a>";
        $template->set('sitterLoginText', $sitterLoginText);
    }
    if (sql_num_rows($get_sit_of_acc))
    {
        $template->set('sitterName', $sitterName['user']);
    }


    // keeper
    $keeper   = sql_query('SELECT user, current, type, rip FROM donations WHERE current>0 ORDER BY date LIMIT 1');
    $sumQuery = sql_query('SELECT SUM(amount) AS sum_amount FROM donations');
    $sum      = sql_fetch_array($sumQuery);
    $sumTime  = round ($sum['sum_amount'] / 0.0034, 0);

    if (sql_num_rows($keeper))
    {
        $keeper = sql_fetch_assoc($keeper);
        $kUser  = new User($keeper['user']);

        $template->set('keeperName', $kUser->getName());
        $template->set('keeperType', $keeper['type']);
        $template->set('keeperScreenName', $kUser->getScreenName());
        $template->set('toShow', substr(maketime($keeper['current'] * 60),0,-3));
        $template->set('endTime', strftime('%d. %B', strtotime('+' . $sumTime . ' minutes', strtotime($donationStart))));
        $template->set('rip', $keeper['rip']);
    }
    
	    // Green Voting
	    $voting = sql_query("SELECT zeit FROM voting_extern WHERE user='$_SESSION[user]'");
	    $voting = sql_fetch_array($voting);
	    $time = time();
	    if($voting['zeit'] < ($time-(24*60*60))) { 
	    	$template->set('voted', true);
	    }else{
	    	$template->set('voted', false);
	    }
?>
