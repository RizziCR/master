<?php
    require_once("msgs.php");
    require_once("database.php");
    require_once("constants.php");
    require_once("functions.php");
    require_once("do_loop.php");

    // define phptal template
    require_once("PHPTAL.php");
    require_once("include/PHPTAL_EtsTranslator.php");
    $template = new PHPTAL( 'theme_blue_line.html' );
    $template->setTranslator( new PHPTAL_EtsTranslator( ) );
    $template->setEncoding( 'ISO-8859-1' );
    $template->set('contentMacroName','intern.html/content');

    // set page title
    $template->set('pageTitle', 'Werde ein Bewahrer!');

    // TODO: Nötig: Festlegen der Umrechnungsformel.

    // insert specific page logic here

    session_start();

    if ($_SESSION['sitt_login'])
        ErrorMessage(MSG_GENERAL,e000);  // Die Funktion ist für Sitter gesperrt

    if (ErrorMessage(0))
    {
        $errorMessage .= "  <h1>Spenden für ETS</h1>";
        $errorMessage .= ErrorMessage();

        // add error output
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

  // user donation
    $ident_res = sql_query('SELECT COUNT(user)+1 AS cuser FROM donations WHERE user="'.addslashes($_SESSION[user]).'"');
    $row = sql_fetch_assoc($ident_res);
    $trans_id = substr($_SESSION[user],0,4).'-'.$row[cuser].'-'.substr(sha1($row[cuser].$_SESSION[user].'äbqLwj06'),0,8);
    $template->set('ident', $trans_id);

  // paypal
    $template->set('pp_ident', $_SESSION['user']);

  // alliance donation
    list($user_alliance) = sql_fetch_row( sql_query("SELECT alliance FROM usarios WHERE user='$_SESSION[user]'" ));
    $al_ident_res = sql_query('SELECT COUNT(user)+1 AS calliance FROM donations WHERE user="'.$user_alliance.'"');
    $al_row = sql_fetch_assoc($al_ident_res);
    $al_trans_id = substr($user_alliance,0,4).'-'.$al_row[calliance].'-'.substr(sha1($al_row[calliance].$user_alliance.'drtfKü09'),0,8);
    $template->set('al_ident', $al_trans_id);

    $toShow = round ($_POST[amount] / 0.0034, 0);

    // Liste öffentlichen Spender
    $rank = array();
    $j = 1;
    $ranks_res = sql_query("SELECT user, SUM(amount) AS amount, type, date FROM donations WHERE user != 'Ano Nymous' AND rip = 'FALSE' GROUP BY user ORDER BY SUM(amount) DESC");
    while($row = sql_fetch_assoc($ranks_res)) {
        $currentUser = new User($row[user]);
        $rank[] = array_merge($row, array('rank'=>$j++, 'fullName'=>$currentUser->getScreenName()));
        // extract dd.mm.yy to table, to sort for last incoming
        $rank[$j-2]['date_short'] = '2021-' . substr($rank[$j-2]['date'], 5, 2) . '-' . substr($rank[$j-2]['date'], 8, 2);
        // donate ist no older then 24 hours
        if ( $rank[$j-2]['date'] > date("Y-m-d",(time()-1*60*60*24*2)))
        {
            $rank[$j-2]['isnew'] = true;
        } else {
            $rank[$j-2]['isnew'] = false;
        }
    }
    $template->set('spender', $rank);

    // Liste der anonymen Spender
    unset($rank);
    unset($row);
    $j = 1;
    $ranks_res = sql_query("SELECT user, SUM(amount) AS amount, type, date FROM donations WHERE user = 'Ano Nymous' AND rip = 'FALSE' GROUP BY user ORDER BY SUM(amount) DESC");
    while($row = sql_fetch_assoc($ranks_res)) {
        $currentUser = new User($row[user]);
        $rank[] = array_merge($row, array('rank'=>$j++, 'fullName'=>$currentUser->getScreenName()));
        // extract dd.mm.yy to table, to sort for last incoming
        $rank[$j-2]['date_short'] = '2021-' . substr($rank[$j-2]['date'], 5, 2) . '-' . substr($rank[$j-2]['date'], 8, 2);
        // donate ist no older then 24 hours
        if ( $rank[$j-2]['date'] > date("Y-m-d",(time()-1*60*60*24*2)))
        {
            $rank[$j-2]['isnew'] = true;
        } else {
            $rank[$j-2]['isnew'] = false;
        }
    }
    $template->set('spender_anonymous', $rank);

    // include common template settings
    require_once("include/JavaScriptCommon.php");
    require_once("include/TemplateSettingsCommon.php");

    try {
        echo $template->execute();
    }
    catch (Exception $e) { echo $e->getMessage(); }

?>
