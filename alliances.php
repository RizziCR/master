<?php
$use_lib = 10; // MSG_ALLIANCES

require_once ("msgs.php");
require_once ("database.php");
require_once ("constants.php");
require_once ("functions.php");
require_once ("do_loop.php");

// define phptal template
require_once ("PHPTAL.php");
require_once ("include/PHPTAL_EtsTranslator.php");
$template = new PHPTAL( 'theme_blue_line.html' );
$template->setTranslator( new PHPTAL_EtsTranslator( ) );
$template->setEncoding( 'ISO-8859-1' );
$template->set('contentMacroName','alliances.html/content');

// set page title
$template->set( 'pageTitle', 'Kommunikation - Allianzen' );

// insert specific page logic here

require_once 'include/class_Krieg.php';

if ($_SESSION['sitt_login'])
    ErrorMessage( MSG_GENERAL, e000 ); // Die Funktion ist für Sitter gesperrt


if (ErrorMessage( 0 )) {
    $errorMessage .= "  <h1>{$MESSAGES[MSG_ALLIANCES][m000]}</h1>";
    $errorMessage .= ErrorMessage();

    // add error output
    $template->set( 'errorMessage', $errorMessage );

    // include common template settings
    require_once ("include/JavaScriptCommon.php");
    require_once ("include/TemplateSettingsCommon.php");

    // save resource changes (ToDo: Is this necessary on every page?)
    $timefixed_depot->save();
    // create html page
    try {
        echo $template->execute();
    } catch( Exception $e ) {
        echo $e->getMessage();
    }

    die();
}

$get_user_infos = sql_query(
        "SELECT alliances.ID,alliances.tag,usarios.alliance_status,usarios.alliance_rank,usarios.voting FROM usarios INNER JOIN alliances ON usarios.alliance = alliances.ID WHERE usarios.ID='$_SESSION[user]'" );
$user_infos = sql_fetch_array( $get_user_infos );
/*echo "<pre>";
print_r($user_infos);
echo "$_SESSION[user]";
echo "</pre>";*/
$user_infos['alliance'] = $user_infos['tag'];
$krieg = new Krieg($user_infos[alliance]);

switch ( $_POST[action]) {
    case "establishment" : // Allianz gründen
        if ($_POST[send_tag] != rawurlencode( $_POST[send_tag] ))
            ErrorMessage( MSG_ALLIANCES, e000 ); // Sonderzeichen im TAG sind nicht erlaubt


        if (! $_POST[send_tag])
            ErrorMessage( MSG_ALLIANCES, e001 ); // Bitte geben Sie eine TAG an


        if (strlen( $_POST[send_tag] ) > 25)
            ErrorMessage( MSG_ALLIANCES, e010 ); // Der TAG ist zu lang


        $check_alliance = sql_query(
                "SELECT 1 FROM alliances WHERE tag='" . htmlspecialchars( $_POST[send_tag],
                        ENT_QUOTES ) . "'" );
        if (sql_num_rows( $check_alliance ))
            ErrorMessage( MSG_ALLIANCES, e002 ); // Der TAG-Name ist bereits vorhanden


        if (ErrorMessage( 0 )) {
            $errorMessage .= "  <h1>{$MESSAGES[MSG_ALLIANCES][m000]}</h1>";
            $errorMessage .= ErrorMessage();

            // add error output
            $template->set( 'errorMessage', $errorMessage );

            // include common template settings
            require_once ("include/JavaScriptCommon.php");
            require_once ("include/TemplateSettingsCommon.php");

            // save resource changes (ToDo: Is this necessary on every page?)
            $timefixed_depot->save();
            // create html page
            try {
                echo $template->execute();
            } catch( Exception $e ) {
                echo $e->getMessage();
            }

            die();
        }

        sql_query(
                "INSERT INTO alliances (tag,name,members,points) SELECT '" . addslashes(
                        $_POST[send_tag] ) . "','" . addslashes( $_POST[name] ) . "',1,points FROM usarios WHERE ID='$_SESSION[user]'" );
        $select = sql_query("SELECT ID FROM alliances WHERE tag='". addslashes( $_POST[send_tag] ) ."'");
        $select = sql_fetch_array($select);
        sql_query(
                "INSERT INTO ranks (tag,rank) VALUES ('$select[ID]','Mitglied')" );
        sql_query(
                "UPDATE usarios SET alliance='$select[ID]',alliance_status='founder',alliance_rank='Mitglied' ".
                "WHERE ID='$_SESSION[user]'" );
        sql_query(
                "UPDATE city SET alliance='$select[ID]' WHERE user='$_SESSION[user]'" );

        $user_infos[alliance] = $_POST[send_tag];
        $user_infos[alliance_status] = 'founder';
        $user_infos[alliance_rank] = "Mitglied";
    break;

    case "application" : // Bei Allianz bewerben
        $check_alliance = sql_query(
                "SELECT ID FROM alliances WHERE tag='" . htmlspecialchars( $_POST[send_tag],
                        ENT_QUOTES ) . "'" );
        if (! sql_num_rows( $check_alliance ))
            ErrorMessage( MSG_ALLIANCES, e003 ); // Diesen TAG gibt es nicht

        $check_alliance = sql_fetch_array($check_alliance);

        // Changed for Alliancetown ~begin~    
            
        $get_alliance_members = sql_query(
                "SELECT Count(*) AS anzahl FROM usarios WHERE alliance='" . htmlspecialchars(
                        $_POST[send_tag], ENT_QUOTES ) . "'" );
        $alliance_members = sql_fetch_array( $get_alliance_members );

        #$get_alliance_hq = sql_query(
        #		"SELECT alliances_building.stufe FROM alliances_building INNER JOIN alliances ON alliances_building.build_id=alliances.id WHERE alliances.tag = '" . htmlspecialchars( $_POST[send_tag],
        #			ENT_QUOTES) . "'");
        #$alliance_hq = sql_fetch_array($get_alliance_hq);
       
        #if (($alliance_hq['level']*USER_PER_LEVEL) < $alliance_members[anzahl])
        #    ErrorMessage( MSG_ALLIANCES, e004 ); // Die maximale Anzahl der Allianz-Mitglieder wurde erreicht

		// Changed for Alliancetown ~end~


        $is_applicated = sql_fetch_array(sql_query("SELECT user,tag FROM alliance_applications WHERE user LIKE '$_SESSION[user]'"));
        if($is_appicated['user'] == $_SESSION['user']) {
        	ErrorMessage("Sie haben sich schon bei einer Allianz beworben. Sortieren Sie zuvor diese Bewerbung.");
        }
        
            
        if (ErrorMessage( 0 )) {
            $errorMessage .= "  <h1>{$MESSAGES[MSG_ALLIANCES][m000]}</h1>";
            $errorMessage .= ErrorMessage();

            // add error output
            $template->set( 'errorMessage', $errorMessage );

            // include common template settings
            require_once ("include/JavaScriptCommon.php");
            require_once ("include/TemplateSettingsCommon.php");

            // save resource changes (ToDo: Is this necessary on every page?)
            $timefixed_depot->save();
            // create html page
            try {
                echo $template->execute();
            } catch( Exception $e ) {
                echo $e->getMessage();
            }

            die();
        }

        
        sql_query(
                "INSERT INTO alliance_applications (user,tag,text,time) VALUES ('$_SESSION[user]','$check_alliance[ID]',
                		'" . addslashes(BBCode( $_POST[atext] )) . "','" . time() . "')" );
    break;

    case "kill_apply" : // Bewerbung löschen
        sql_query( "DELETE FROM alliance_applications WHERE user='$_SESSION[user]'" );
    break;

    case "send_msg" : // Nachricht im Board posten
        $get_admin_msgs = sql_query(
                "SELECT ID,admin_msgs FROM alliances WHERE tag='$user_infos[alliance]'" );
        $admin_msgs = sql_fetch_array( $get_admin_msgs );

        if ($admin_msgs[admin_msgs] == "Y" && $user_infos[alliance_status] != 'admin' && $user_infos[alliance_status] != 'founder')
            ErrorMessage( MSG_ALLIANCES, e005 ); // Sie haben keine Berechtigung eine Nachricht zu schreiben


        if (empty( $_POST[topic] ) || empty( $_POST[a_msg] ))
            ErrorMessage( MSG_ALLIANCES, e006 ); // Bitte geben Sie eine Nachricht und einen Betreff an


        if (ErrorMessage( 0 )) {
            $errorMessage .= "  <h1>{$MESSAGES[MSG_ALLIANCES][m000]}</h1>";
            $errorMessage .= ErrorMessage();

            // add error output
            $template->set( 'errorMessage', $errorMessage );

            // include common template settings
            require_once ("include/JavaScriptCommon.php");
            require_once ("include/TemplateSettingsCommon.php");

            // save resource changes (ToDo: Is this necessary on every page?)
            $timefixed_depot->save();
            // create html page
            try {
                echo $template->execute();
            } catch( Exception $e ) {
                echo $e->getMessage();
            }

            die();
        }

        sql_query(
                "INSERT INTO news_msg (tag,time,type,topic,msg) VALUES ('$admin_msgs[ID]'," . time() . ",'Nachricht von ".$thisUser->getScreenName().
                "','" . addslashes($_POST[topic] ) . "','" . addslashes( BBCode( $_POST[a_msg] ) ) . "')" );
    break;

    case "exit_alliance" : // Allianz verlassen
        if ($user_infos[alliance_status] != "member" && $user_infos[alliance_status] != 'admin')
            ErrorMessage( MSG_ALLIANCES, e011 ); // Sie k&ouml;nnen Ihre Allianz nicht verlassen (Gr&uuml;nder-Status)


        if (ErrorMessage( 0 )) {
            $errorMessage .= "  <h1>{$MESSAGES[MSG_ALLIANCES][m000]}</h1>";
            $errorMessage .= ErrorMessage();

            // add error output
            $template->set( 'errorMessage', $errorMessage );

            // include common template settings
            require_once ("include/JavaScriptCommon.php");
            require_once ("include/TemplateSettingsCommon.php");

            // save resource changes (ToDo: Is this necessary on every page?)
            $timefixed_depot->save();
            // create html page
            try {
                echo $template->execute();
            } catch( Exception $e ) {
                echo $e->getMessage();
            }

            die();
        }

        $krieg->handleLeaving($_SESSION[user]);

        sql_query(
                "UPDATE alliances RIGHT JOIN usarios ON alliances.ID=usarios.alliance SET alliances.members=alliances.members-1,".
                "alliances.points=alliances.points-usarios.points, alliances.fame=alliances.fame-usarios.fame_own WHERE usarios.ID='$_SESSION[user]'" );
        $select = sql_query("SELECT user FROM userdata WHERE ID ='$_SESSION[user]'");
        $select = sql_fetch_array($select);
        sql_query(
                "INSERT INTO news_er (city,time,topic) SELECT city," . time() . ",'$select[user] hat Ihre Allianz verlassen' ".
                "FROM city RIGHT JOIN usarios ON city.ID=usarios.user WHERE city.home='YES' && usarios.alliance='$user_infos[ID]' ".
                "&& (usarios.alliance_status='admin' || usarios.alliance_status='founder')" );
        sql_query(
                "UPDATE usarios SET alliance='',alliance_status='',alliance_rank='',voting='0', fame=fame_own WHERE ID='$_SESSION[user]'" );
        sql_query( "UPDATE city SET alliance='' WHERE user='$_SESSION[user]'" );
        recompute_user_fame_for_alliance($user_infos[alliance]);

        $user_infos[alliance] = "";
        $user_infos[alliance_status] = "";
        $user_infos[alliance_rank] = "";

    break;

    case "vote" :
        if (! $user_infos[voting]) {
            sql_query(
                    "UPDATE usarios SET voting='" . intval( $_POST[choose] ) . "' WHERE ID='$_SESSION[user]'" );
            sql_query(
                    "UPDATE voting SET answer" . intval( $_POST[choose] ) . "_count=answer" . intval(
                            $_POST[choose] ) . "_count+1 WHERE tag='$user_infos[ID]'" );
            $user_infos[voting] = intval( $_POST[choose] );
        }
    break;
}

switch ( $_REQUEST[show]) {
    case "status" :
        {
            if ($user_infos[alliance_status] != 'admin' && $user_infos[alliance_status] != 'founder')
                ErrorMessage( MSG_ALLIANCES, e008 ); // Zu dieser Aktion sind Sie nicht berechtigt


            if (ErrorMessage( 0 )) {
                $errorMessage .= "<font class='headline'>{$MESSAGES[MSG_ALLIANCES][m000]}</font><br /><br /><br /><br />";
                $errorMessage .= ErrorMessage();

                // add error output
                $template->set( 'errorMessage', $errorMessage );

                // include common template settings
                require_once ("include/JavaScriptCommon.php");
                require_once ("include/TemplateSettingsCommon.php");

                // save resource changes (ToDo: Is this necessary on every page?)
                $timefixed_depot->save();
                // create html page
                try {
                    echo $template->execute();
                } catch( Exception $e ) {
                    echo $e->getMessage();
                }

                die();
            }
        } // status
}

//echo "Hello World! Here i´m :)<br>";
if ($_REQUEST[show] == "" && count( $_POST[id] ))
    sql_query(
            "DELETE FROM news_msg WHERE tag='$user_infos[ID]' && id IN (" . implode( ',',
                    $_POST[id] ) . ")" );

$get_information = sql_query(
        "SELECT tag,name,points,members,pic,military_alliances,trade_alliances,naps,enemies,text,link,admin_msgs,admin_mails FROM alliances WHERE tag='$user_infos[tag]'" );
$information = sql_fetch_array( $get_information );
$information[bb_military_alliances] = BBCode( $information[military_alliances] );
$information[bb_trade_alliances] = BBCode( $information[trade_alliances] );
$information[bb_naps] = BBCode( $information[naps] );
$information[bb_enemies] = BBCode( $information[enemies] );
$information[bb_text] = BBCode( $information[text] );
$information['link'] = strpos($information['link'],'http://')===0 ? $information['link'] : 'http://'.$information['link'];

/*echo "<pre>";
print_r($information);
echo "</pre>";*/

// TODO HIER !!!!
list ( $applied ) = sql_fetch_row(
        sql_query( "SELECT tag FROM alliance_applications WHERE user='$_SESSION[user]'" ) );
list ( $user_percent ) = sql_fetch_row(
        sql_query( "SELECT Round(100+COUNT(user)*0.01) AS max_members FROM usarios" ) );

if ($_REQUEST[load] == 1) {
    $members = sql_fetch_array(
            sql_query(
                    "SELECT user,alliance_status,alliance_rank FROM usarios WHERE user='$_REQUEST[change_user]' && alliance='$user_infos[ID]'" ) );
    $template->set( 'members', $members );
}

$get_sum_votings = sql_query(
        "SELECT answer1_count+answer2_count+answer3_count+answer4_count+answer5_count+answer6_count+answer7_count+answer8_count+answer9_count+answer10_count AS summe FROM voting WHERE tag='$user_infos[ID]'" );
$sum_votings = sql_fetch_array( $get_sum_votings );
$template->set( 'summe_voting', $sum_votings[summe] );

$get_voting = sql_query( "SELECT * FROM voting WHERE tag='$user_infos[ID]'" );
$whole_voting = sql_fetch_array( $get_voting );
if (sql_num_rows( $get_voting ) > 0)
    $template->set( 'has_voting', 1 );

sql_query("UPDATE usarios SET alliance_seen=".time()." WHERE ID='".$_SESSION['user']."'");

$get_news = sql_query(
        "SELECT id,time,type,topic,msg FROM news_msg WHERE tag='$user_infos[ID]' ORDER BY time DESC" );
if (sql_num_rows( $get_news ) >= 1) {
    $template->set( 'has_news', 1 );
    while( $row = sql_fetch_array( $get_news ) )
        $_news[] = $row;
    $template->set( 'get_news', $_news );
}

switch ( $_GET[order]) {
    case "user" :
        $ordertab = "user";
    break;
    case "alliance_status" :
        $ordertab = "alliance_status";
    break;
    case "alliance_rank" :
        $ordertab = "alliance_rank";
    break;
    case "points" :
        $ordertab = "points DESC";
    break;
    default :
        $ordertab = "points DESC";
    break;
}
$get_members = array ( );
$_members = sql_query(
        "SELECT usarios.user,usarios.ID,alliance_status,alliance_rank,last_action,points,logged_in,holiday FROM usarios JOIN userdata ON ( usarios.ID = userdata.ID ) WHERE alliance='$user_infos[ID]' ORDER BY $ordertab" );
while( $row = sql_fetch_array( $_members ) ) {
	//print_r($row);
    $sUser = new User($row[ID]);
    $row[username] = $sUser->getScreenName();
    if (strlen($row[alliance_rank])>30) $row[alliance_rank_short] = substr($row[alliance_rank], 0, 26)." ..."; else $row[alliance_rank_short] ="$row[alliance_rank]";

    // Bestimmen, ob Bewahrer
    $row[isKeeper] = 0;
    $row[isPlayer] = 0;
    if ($_SESSION[user] == $row[ID]) {
	$row[isPlayer] = 1;
    } else {
	$getKeeper = sql_query("SELECT user FROM donations WHERE user='". $row[user] ."'");
	if (sql_num_rows($getKeeper) > 0)
		$row[isKeeper] = 1;
    }

      
    $get_members[] = $row;
}
$template->set( 'get_members', $get_members );

$template->set( 'is_admin',
        $user_infos['alliance_status'] == 'admin' || $user_infos['alliance_status'] == 'founder' );
$template->set( 'has_alliance', $user_infos[alliance] != '' );
$template->set( 'has_applied', $applied );
$template->set( 'user_percent', $user_percent );
$template->set( 'votes', array (1, 2, 3, 4, 5, 6, 7, 8, 9, 10 ) );
$template->set( 'information', $information );
$template->set( 'user_infos', $user_infos );
$template->set( 'whole_voting', $whole_voting );

$template->set( 'user_path', $_SESSION['user_path'] );

// Kriegsanzeige
$_war = $krieg->getWars(Krieg::TYPE_OPEN, true);
foreach($_war as $k => $w)
    $_war[$k]['finishable'] = 0;

$template->set( 'inWar', $krieg->inWar());
$template->set( 'wars', $_war);
$template->set( 'open_wars', $krieg->getWars(Krieg::TYPE_OPEN));
$template->set( 'won_wars', $krieg->getWars(Krieg::TYPE_WON));
$template->set( 'lost_wars', $krieg->getWars(Krieg::TYPE_LOST));

$template->set( 'war_warning_noleave', $krieg->checkWarOptions(Krieg::NO_LEAVE));



// Alliancetown

// Gebäude bauen, abbrechen oder einfach betrachten


// Stadtoptionen Einstellungen
		$alliance_options = "";
		$alliance_options = "<form action='/alliances.php' method='post'>
							<table border='0'>
								<tr>
									<td>
										{$MESSAGES[MSG_ALLIANCES][m001]}
									</td><td>
										<input type='text' name='abgabe' maxlength='3'>
									</td>
								</tr>
							</table>
							<br><br>
							{$MESSAGES[MSG_ALLIANCES][m002]}
							";






#$template->set( 'alliance_options', $alliance_options);
#$template->set( 'alliances_buildings', $alliances_buildings);


// end specific page logic


// include common template settings
require_once ("include/JavaScriptCommon.php");
require_once ("include/TemplateSettingsCommon.php");

// save resource changes (ToDo: Is this necessary on every page?)
$timefixed_depot->save();

// create html page
try {
    echo $template->execute();
} catch( Exception $e ) {
    echo $e->getMessage();
}
?>
