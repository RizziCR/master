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

if($_SESION['user'] == "Jens") {
	$template = new PHPTAL( 'alliances_admin2.html' );
}else{
	$template = new PHPTAL( 'alliances_admin.html' );
}
$template->setTranslator( new PHPTAL_EtsTranslator( ) );
$template->setEncoding( 'ISO-8859-1' );

// set page title
$template->set( 'pageTitle', 'Kommunikation - Allianzen' );

// insert specific page logic here

require_once 'include/class_imageop.inc.php';
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

//$get_user_infos = sql_query(
//        "SELECT alliance,alliance_status,alliance_rank,voting FROM usarios WHERE ID='$_SESSION[user]';" );
//$user_infos = sql_fetch_array( $get_user_infos );

$get_user_infos = sql_query(
        "SELECT alliances.ID,alliances.tag,usarios.alliance_status,usarios.alliance_rank,usarios.voting FROM usarios INNER JOIN alliances ON usarios.alliance = alliances.ID WHERE usarios.ID='$_SESSION[user]'" );
$user_infos = sql_fetch_array( $get_user_infos );
$user_infos['alliance'] = $user_infos['tag'];

$krieg = new Krieg($user_infos[alliance]);

switch ( $_POST[action]) {

    case 'banner_add': // Allianz-Banner-Upload
        $get_ads_banner = sql_query("SELECT 1 FROM alliance_ads WHERE tag='$user_infos[alliance]';");
        if(sql_num_rows($get_ads_banner) < 3) {
            $imgop = new ImageOp();
            $file = $imgop->prepareImageForFile(null, './uploads/alliance_banner');
            if(is_array($file)) {
                if( $file[width] <= 140 &&  $file[height] <= 500) {
                    sql_query("INSERT INTO alliance_ads (id, tag, filename, width, height, credit, approved, text, thumb) ".
                        "VALUES (0, '$user_infos[alliance]', '$file[filename]', ".intval($file[width]).", ".intval($file[height]).", 0, 'N', '', '$file[thumb]');");
                }
                else
                    $template->set( 'errorMessage', 'Das neue Banner ist zu gross! Maximal sind 140x500 Pixel erlaubt.' );
            }
            else {
                $template->set( 'errorMessage', $imgop->getError() );
            }
        }
    break;

    case 'banner': // Allianz-Banner lï¿½schen
        switch($_POST[submit]) {
            case "Löschen":
                $get_ads_banner = sql_query("SELECT * FROM alliance_ads WHERE tag='$user_infos[alliance]' AND id=".addslashes(intval($_POST[id])).";");
                if(sql_num_rows($get_ads_banner) == 1) {
                    $ads_banner = sql_fetch_assoc($get_ads_banner);
                    @unlink($ads_banner[filename]);
                    @unlink($ads_banner[thumb]);
                    sql_query("DELETE FROM alliance_ads WHERE id='".addslashes(intval($_POST[id]))."' AND tag='$user_infos[alliance]';");
                }
            break;

            case 'Aufladen': // Allianz-Banner aufladen
                $get_ads_banner = sql_query("SELECT 1 FROM alliance_ads WHERE tag='$user_infos[alliance]' AND id=".addslashes($_POST[id]).";");
                if(sql_num_rows($get_ads_banner) == 1) {
                    $amount = abs(intval($_POST['amount']));
                    $credit = sql_query("SELECT ads_credit FROM alliances WHERE tag='$user_infos[alliance]' AND ads_credit >= ".$amount.";");
                    if(sql_num_rows($credit) == 1) {
                        sql_query("UPDATE alliances SET ads_credit = ads_credit - $amount WHERE tag='$user_infos[alliance]';" );
                        sql_query("UPDATE alliance_ads SET credit = credit + $amount WHERE tag='$user_infos[alliance]' AND id=".addslashes(intval($_POST[id])).";");
                    }
                }
            break;
        }
    break;

    case "assume" : // Bewerbung akzeptieren
    
        if ($user_infos[alliance_status] != 'admin' && $user_infos[alliance_status] != 'founder')
            ErrorMessage( MSG_ALLIANCES, e008 ); // Zu dieser Aktion sind Sie nicht berechtigt


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
        
       	/*if ($user_infos[ID] == 62){
	echo "<pre>";
		echo "<br>Und jetzt bin ich dahinter.<br>";
	echo "</pre>";
	}*/


        $chk_alli_membership = sql_query(
                "SELECT 1 FROM alliance_applications WHERE user='" . addslashes(htmlspecialchars(
                        $_POST[apply_user], ENT_QUOTES )) . "' && tag='$user_infos[ID]';" );
                        //$_POST[apply_user] = UserID
       	/*if ($user_infos[ID] == 62){
	echo "<pre>";
		echo "<br>$_POST[apply_user] und tag= $user_infos[ID] und sql_num_rows ". sql_num_rows( $chk_alli_membership ) ."<br>";
		print_r($chk_alli_membership);
	echo "</pre>";
	}*/

        if (sql_num_rows( $chk_alli_membership )) {
        
            $krieg->handleApplication($_POST[apply_user]);
            sql_query(
                    "INSERT INTO news_er (city,time,topic) SELECT city," . time() . ",'Ihrem Beitritt zur Allianz $user_infos[alliance] wurde zugestimmt' FROM city WHERE user='" . addslashes(
                            $_POST[apply_user] ) . "' && home='YES';" );
            sql_query(
                    "DELETE FROM alliance_applications WHERE user='" . addslashes(htmlspecialchars(
                            $_POST[apply_user], ENT_QUOTES )) . "';" );
            sql_query(
                    "UPDATE usarios SET alliance='$user_infos[ID]',alliance_status='member',alliance_rank='Mitglied' WHERE id='" . addslashes(htmlspecialchars(
                            $_POST[apply_user], ENT_QUOTES )) . "';" );
            sql_query(
                    "UPDATE city SET alliance='$user_infos[ID]' WHERE user='" . addslashes(htmlspecialchars(
                            $_POST[apply_user], ENT_QUOTES )) . "';" );
            sql_query(
                    "UPDATE alliances RIGHT JOIN usarios ON alliances.ID=usarios.alliance SET alliances.members=alliances.members+1,alliances.points=alliances.points+usarios.points,alliances.fame=alliances.fame+usarios.fame_own WHERE usarios.ID='" . addslashes(htmlspecialchars(
                            $_POST[apply_user], ENT_QUOTES )) . "';" );
            // do this only after assigning the new alliance to user and after adding his fame
            recompute_user_fame_for_alliance($user_infos[alliance]);
        }
    break;

    case "reject" : // Bewerbung ablehnen
        if ($user_infos[alliance_status] != 'admin' && $user_infos[alliance_status] != 'founder')
            ErrorMessage( MSG_ALLIANCES, e008 ); // Zu dieser Aktion sind Sie nicht berechtigt


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

        $chk_alli_membership = sql_query(
                "SELECT 1 FROM alliance_applications WHERE user='" . htmlspecialchars(
                        $_POST[apply_user], ENT_QUOTES ) . "' && tag='$user_infos[ID]';" );
        if (sql_num_rows( $chk_alli_membership )) {
            sql_query(
                    "INSERT INTO news_er (city,time,topic) SELECT city," . time() . ",'Ihrem Beitritt zur $user_infos[alliance]-Allianz wurde nicht zugestimmt: " . addslashes(
                            $_POST[reason] ) . "' FROM city WHERE user='" . addslashes(htmlspecialchars(
                            $_POST[apply_user], ENT_QUOTES )) . "' && home='YES';" );
            sql_query(
                    "DELETE FROM alliance_applications WHERE user='" . addslashes(htmlspecialchars(
                            $_POST[apply_user], ENT_QUOTES )) . "';" );
        }
    break;

    case "delete_alliance" : // Allianz löschen
        if ($user_infos[alliance_status] != 'admin' && $user_infos[alliance_status] != 'founder')
            ErrorMessage( MSG_ALLIANCES, e008 ); // Zu dieser Aktion sind Sie nicht berechtigt


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

        $krieg->handleAllianceDeletion();
        sql_query(
                "UPDATE usarios SET alliance='',alliance_status='member',alliance_rank='',voting='0', fame=fame_own WHERE alliance='$user_infos[alliance]';" );
        sql_query( "UPDATE city SET alliance='' WHERE alliance='$user_infos[alliance]';" );

        sql_query( "DELETE FROM alliances WHERE tag='$user_infos[alliance]';" );
        sql_query( "DELETE FROM alliance_applications WHERE tag='$user_infos[alliance]';" );
        sql_query( "DELETE FROM ranks WHERE tag='$user_infos[alliance]';" );
        sql_query( "DELETE FROM news_msg WHERE tag='$user_infos[alliance]';" );
        sql_query( "DELETE FROM voting WHERE tag='$user_infos[alliance]';" );

        $user_infos[alliance] = "";
        $user_infos[alliance_status] = "";
        $user_infos[alliance_rank] = "";
    break;

    case "save" :
        if ($_POST[save_tag] != $user_infos[alliance]) {
            if ($user_infos[alliance_status] != 'admin' && $user_infos[alliance_status] != 'founder')
                ErrorMessage( MSG_ALLIANCES, e008 ); // Zu dieser Aktion sind Sie nicht berechtigt


            if (empty( $_POST[save_tag] ) || $_POST[save_tag] != rawurlencode( $_POST[save_tag] ))
                ErrorMessage( MSG_ALLIANCES, e000 ); // Sonderzeichen im TAG sind nicht erlaubt


            $check_tag = sql_query(
                    "SELECT 1 FROM alliances WHERE tag='" . addslashes( $_POST[save_tag] ) . "';" );
            if (sql_num_rows( $check_tag ))
                ErrorMessage( MSG_ALLIANCES, e002 ); // Der TAG-Name ist bereits vorhanden


            if (strlen( $_POST[save_tag] ) > 25)
                ErrorMessage( MSG_ALLIANCES, e010 ); // Der TAG ist zu lang


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
                    "UPDATE alliances SET tag='" . addslashes(htmlspecialchars( $_POST[save_tag],
                            ENT_QUOTES )) . "' WHERE tag='$user_infos[alliance]';" );
            sql_query(
                    "UPDATE alliance_applications SET tag='" . addslashes(htmlspecialchars(
                            $_POST[save_tag], ENT_QUOTES )) . "' WHERE tag='$user_infos[alliance]';" );
            sql_query(
                    "UPDATE usarios SET alliance='" . addslashes(htmlspecialchars( $_POST[save_tag],
                            ENT_QUOTES )) . "' WHERE alliance='$user_infos[alliance]';" );
            sql_query(
                    "UPDATE city SET alliance='" . addslashes(htmlspecialchars( $_POST[save_tag],
                            ENT_QUOTES )) . "' WHERE alliance='$user_infos[alliance]';" );
            sql_query(
                    "UPDATE news_msg SET tag='" . addslashes(htmlspecialchars( $_POST[save_tag],
                            ENT_QUOTES )) . "' WHERE tag='$user_infos[alliance]';" );
            sql_query(
                    "UPDATE voting SET tag='" . addslashes(htmlspecialchars( $_POST[save_tag], ENT_QUOTES )) . "' WHERE tag='$user_infos[alliance]';" );

            $krieg->handleAllianceRename( $_POST[save_tag] );

            $user_infos[alliance] = $_POST[save_tag];
        }

        sql_query(
                "UPDATE alliances SET name='" . addslashes(htmlspecialchars( $_POST[name], ENT_QUOTES )) . "',pic='" . addslashes(htmlspecialchars(
                        $_POST[apic], ENT_QUOTES )) . "',link='" . addslashes(htmlspecialchars(
                        $_POST[link], ENT_QUOTES )) . "',text='" . addslashes(
                        $_POST[atext]) . "',trade_alliances='" . addslashes(htmlspecialchars(
                        $_POST[trade_alliances], ENT_QUOTES )) . "',naps='" . addslashes(htmlspecialchars(
                        $_POST[naps], ENT_QUOTES )) . "',enemies='" . addslashes(htmlspecialchars(
                        $_POST[enemies], ENT_QUOTES )) . "',admin_msgs='" . (($_POST[admin_msgs]) ? "Y" : "N") . "',admin_mails='" .
                            (($_POST[admin_mails]) ? "Y" : "N") . "' WHERE tag='$user_infos[alliance]';" );
                            // military_alliances='" . addslashes(htmlspecialchars($_POST[military_alliances], ENT_QUOTES )) . "',
    break;

    case "create_voting" :
        if ($user_infos[alliance_status] != 'admin' && $user_infos[alliance_status] != 'founder')
            ErrorMessage( MSG_ALLIANCES, e008 ); // Zu dieser Aktion sind Sie nicht berechtigt


        if (empty( $_POST[question] ) || empty( $_POST[answer1] ) || empty( $_POST[answer2] ))
            ErrorMessage( MSG_ALLIANCES, e007 ); // Bitte geben Sie eine Frage und mindestens zwei Antwortmï¿½glichkeiten an


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

        sql_query( "UPDATE usarios SET voting='0' WHERE alliance='$user_infos[ID]';" );
        sql_query( "DELETE FROM voting WHERE tag='$user_infos[ID]';" );
        sql_query(
                "INSERT INTO voting (tag,question,answer1,answer2,answer3,answer4,answer5,answer6,answer7,answer8,answer9,answer10) " .
                "VALUES ('$user_infos[ID]'," . "'" . addslashes(
                        $_POST[question] ) . "','" . addslashes( $_POST[answer1] ) . "','" . addslashes(
                        $_POST[answer2] ) . "','" . addslashes( $_POST[answer3] ) . "','" . addslashes(
                        $_POST[answer4] ) . "'," . "'" . addslashes( $_POST[answer5] ) . "','" . addslashes(
                        $_POST[answer6] ) . "','" . addslashes( $_POST[answer7] ) . "','" . addslashes(
                        $_POST[answer8] ) . "','" . addslashes( $_POST[answer9] ) . "','" . addslashes(
                        $_POST[answer10] ) . "');" );

        $user_infos[voting] = "";
    break;

    case "kill_vote" :
        if ($user_infos[alliance_status] == 'admin' || $user_infos[alliance_status] == 'founder') {
            sql_query( "DELETE FROM voting WHERE tag='$user_infos[ID]';" );
            sql_query( "UPDATE usarios SET voting='0' WHERE alliance='$user_infos[ID]';" );
            $user_infos[voting] = "";
        }
    break;

    case "Speichern" :
        $chk_alli_membership = sql_query(
                "SELECT 1 FROM usarios WHERE user='" . htmlspecialchars( $_REQUEST[change_user],
                        ENT_QUOTES ) . "' && alliance='$user_infos[ID]';" );
        if (sql_num_rows( $chk_alli_membership )) {
            if ($user_infos[alliance_status] != 'admin' && $user_infos[alliance_status] != 'founder')
                ErrorMessage( MSG_ALLIANCES, e008 ); // Zu dieser Aktion sind Sie nicht berechtigt


            if ($_POST[change_status] == 'founder' && $user_infos[alliance_status] != 'founder')
                ErrorMessage( MSG_ALLIANCES, e008 ); // Zu dieser Aktion sind Sie nicht berechtigt


            if ($_REQUEST[change_status] != 'founder' && $_POST[change_status] != 'admin' && $_POST[change_status] != "member")
                ErrorMessage( MSG_ALLIANCES, e009 ); // Status ungï¿½ltig


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
                    "UPDATE usarios SET alliance_status='" . mysql_real_escape_string(
                            $_POST[change_status]) . "' WHERE user='$_REQUEST[change_user]';" );
            sql_query(
                    "UPDATE usarios RIGHT JOIN ranks ON usarios.alliance=ranks.tag SET usarios.alliance_rank=ranks.rank WHERE usarios.user='" . mysql_real_escape_string(
                            $_REQUEST['change_user']) . "' && ranks.rank='" . mysql_real_escape_string(
                            $_POST['change_rank']) . "';" );

            if ($_POST[give_founder_status] && $user_infos[alliance_status] == 'founder') {
                sql_query(
                        "UPDATE usarios SET alliance_status='admin' WHERE ID='$_SESSION[user]';" );
                sql_query(
                        "UPDATE usarios SET alliance_status='founder' WHERE user='" . mysql_real_escape_string(
                                $_POST[give_founder_status]) . "';" );
            }

            if ($_REQUEST[change_user] == $_SESSION[user]) {
                $user_infos[alliance_status] = $_POST[change_status];
                $user_infos[alliance_rank] = $_POST[change_rank];
            }
        }
    break;

    case "Hinauswerfen" :
        if ($user_infos[alliance_status] != 'admin' && $user_infos[alliance_status] != 'founder')
            ErrorMessage( MSG_ALLIANCES, e008 ); // Zu dieser Aktion sind Sie nicht berechtigt


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

        $chk_alli_membership = sql_query(
                "SELECT 1 FROM usarios WHERE user='" . htmlspecialchars( $_POST[ex_user],
                        ENT_QUOTES ) . "' && alliance='$user_infos[ID]';" );
        if (sql_num_rows( $chk_alli_membership )) {
            sql_query(
                    "UPDATE alliances LEFT JOIN usarios ON alliances.ID=usarios.alliance SET alliances.members=alliances.members-1,".
                    "alliances.points=alliances.points-usarios.points, alliances.fame=alliances.fame-usarios.fame_own WHERE usarios.user='" . addslashes(
                            $_POST[ex_user] ) . "';" );
            sql_query(
                    "UPDATE usarios SET alliance='',alliance_status='',alliance_rank='',voting='0', fame=fame_own WHERE user='" . addslashes(htmlspecialchars(
                            $_POST[ex_user], ENT_QUOTES )) . "';" );
            sql_query(
                    "UPDATE city SET alliance='' WHERE user='" . addslashes(htmlspecialchars(
                            $_POST[ex_user], ENT_QUOTES )) . "';" );
            sql_query(
                    "INSERT INTO news_er (city,time,topic) SELECT city," . time() . ",'Sie sind nicht l&auml;nger Mitglied in der Allianz $user_infos[alliance]: " . addslashes(
                            $_POST[reason] ) . "' FROM city RIGHT JOIN usarios ON city.user=usarios.user WHERE usarios.user='" . addslashes(htmlspecialchars(
                            $_POST[ex_user], ENT_QUOTES )) . "' && city.home='YES';" );

            recompute_user_fame_for_alliance($user_infos[alliance]);
            $krieg->handleLeaving($_POST[ex_user]);

            if ($_REQUEST[change_user] == $_SESSION[user]) {
                $user_infos[alliance] = "";
                $user_infos[alliance_status] = "";
                $user_infos[alliance_rank] = "";
            }
        }
    break;
    
    case "delete_bnd":
    	if ($user_infos[alliance_status] == 'admin' || $user_infos[alliance_status] == 'founder')
    	{
	    	foreach($_POST['Delete'] AS $delete) {
	    		sql_query(
	    			"UPDATE alliances SET military_alliances='' WHERE military_alliances = '" . htmlspecialchars( $delete ) . "' AND tag='$user_infos[alliance]';");
	    		sql_query(
	    			"UPDATE alliances SET military_alliances2='' WHERE military_alliances2 = '" . htmlspecialchars( $delete ) . "' AND tag='$user_infos[alliance]';");
	    		sql_query(
	    			"UPDATE alliances SET military_alliances3='' WHERE military_alliances3 = '" . htmlspecialchars( $delete ) . "' AND tag='$user_infos[alliance]';");
	    	}
    	}
    break;
    
    
    
    case "delete_wing":
    	if ($user_infos[alliance_status] == 'admin' || $user_infos[alliance_status] == 'founder')
    	{
	    	foreach($_POST['Delete'] AS $delete) {
	    		sql_query(
	    			"UPDATE alliances SET wing='' WHERE wing = '" . htmlspecialchars( $delete ) . "' AND tag='$user_infos[alliance]';");
	    	}
    	}    	
    break;
    
    // TODO:
    // SQL -> jobs_build erweitern auf allianzgebäude
    
    case "insert_bnd":
    	if ($user_infos[alliance_status] == 'admin' || $user_infos[alliance_status] == 'founder')
    	{    	
    		$building = sql_query("SELECT stufe FROM alliances_building WHERE stufe = '1' AND tag = '$user_infos[alliance]';");
    		$building = sql_fetch_array($building);
    		
    		$select = sql_query("SELECT military_alliances, military_alliances2, military_alliances3 
    		FROM alliances WHERE tag='$user_infos[alliance]';");
    		$select = sql_fetch_array($select);
    		
    		$sel = sql_query("SELECT tag FROM alliance WHERE tag='" . htmlspecialchars ( $_POST['military_alliances']) . "';");
    		$sel = sql_fetch_array($sel);
    		
    		if($select['military_alliances'] == "" && $building['stufe'] < 3) {
    				if($sel['tag'] == $_POST['military_alliances']) {
	    				sql_query("UPDATE alliances SET military_alliances=
	    					'" . htmlspecialchars( $_POST['military_alliances']) . "' WHERE tag='$user_infos[alliance]';");
	    				break;
	    			}else{
               		 		$errorMessage .= "<font class='headline'>{$MESSAGES[MSG_ALLIANCES][m000]}</font><br /><br /><br /><br />
                			{$MESSAGES[MSG_ALLIANCES][e003]}";
    				}
    		}
    		if($select['military_alliances2'] == "" && $building['stufe'] > 6) {
    				if($sel['tag'] == $_POST['military_alliances']) {
	    				sql_query("UPDATE alliances SET military_alliances2=
	    					'" . htmlspecialchars( $_POST['military_alliances']) . "' WHERE tag='$user_infos[alliance]';");
	    				break;
    				}else{
               		 		$errorMessage .= "<font class='headline'>{$MESSAGES[MSG_ALLIANCES][m000]}</font><br /><br /><br /><br />
                			{$MESSAGES[MSG_ALLIANCES][e003]}";
    				}
    		}
    		if($select['military_alliances3'] == "" && $building['stufe'] > 9) {
	    			if($sel['tag'] == $_POST['military_alliances']) {
	    				sql_query("UPDATE alliances SET military_alliances3=
	    					'" . htmlspecialchars( $_POST['military_alliances']) . "' WHERE tag='$user_infos[alliance]';");
	    				break;
    				}else{
               		 		$errorMessage .= "<font class='headline'>{$MESSAGES[MSG_ALLIANCES][m000]}</font><br /><br /><br /><br />
                			{$MESSAGES[MSG_ALLIANCES][e003]}";
    				}
    		}
    		if($select['military_alliances'] != "" && $select['military_alliances2'] != "" && $select['military_alliances3'] != "") 
                $errorMessage .= "<font class='headline'>{$MESSAGES[MSG_ALLIANCES][m000]}</font><br /><br /><br /><br />
                				{$MESSAGES[MSG_ALLIANCES][e015]}";
    	}
    break;
    
    
    case "insert_wing":
    	if ($user_infos[alliance_status] == 'admin' || $user_infos[alliance_status] == 'founder')
    	{    	
    		$building = sql_query("SELECT stufe FROM alliances_building WHERE stufe = '1' AND tag = '$user_infos[alliance]';");
    		$building = sql_fetch_array($building);
    		
    		$select = sql_query("SELECT wing FROM alliances WHERE tag='$user_infos[alliance]';");
    		$select = sql_fetch_array($select);
    		if($building['stufe'] < 3 || $select['wing'] != "") {
    			$errorMessage .= "<font class='headline'>{$MESSAGES[MSG_ALLIANCES][m000]}</font><br /><br /><br /><br />
                				{$MESSAGES[MSG_ALLIANCES][e015]}";
    		}else{
    			$sel = sql_query("SELECT tag FROM alliance WHERE tag='" . htmlspecialchars ( $_POST['military_alliances']) . "';");
    			$sel = sql_fetch_array($sel);
    		
    			if($sel['tag'] == $_POST['military_alliances']) {
    				$update = sql_query("UPDATE alliances SET wing='" . htmlspecialchars( $_POST['wing']) . "' WHERE tag='$user_infos[alliance]';");
    			}else{
    				$errorMessage .= "<font class='headline'>{$MESSAGES[MSG_ALLIANCES][m000]}</font><br /><br /><br /><br />
             		{$MESSAGES[MSG_ALLIANCES][e003]}";	
    			}
    		}
    			
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

if ($_REQUEST[show] == 'ranks') {
    if ($_POST[new_rank])
        sql_query(
                "INSERT INTO ranks (tag,rank) VALUES ('$user_infos[ID]','" . mysql_real_escape_string($_POST['new_rank']) . "');" );

    if (count( $_POST[del_rank] )) {
        sql_query(
                "UPDATE usarios RIGHT JOIN ranks ON usarios.alliance_rank=ranks.rank && usarios.alliance=ranks.tag SET usarios.alliance_rank='Mitglied' WHERE ranks.id IN (" . implode(
                        ",", $_POST[del_rank] ) . ");" );
        sql_query(
                "DELETE FROM ranks WHERE tag='$user_infos[ID]' && id IN(" . implode( ",",
                        $_POST[del_rank] ) . ");" );
    }
}

//$user_infos[ID] ist oben als alliances.ID ausgelesen worden
$get_information = sql_query(
        "SELECT * FROM alliances WHERE ID='$user_infos[ID]';" );
$information = sql_fetch_array( $get_information );
#$information[bb_military_alliances] = BBCode( $information[military_alliances] );
$information[bb_trade_alliances] = BBCode( $information[trade_alliances] );
$information[bb_naps] = BBCode( $information[naps] );
$information[bb_enemies] = BBCode( $information[enemies] );
$information[bb_text] = BBCode( $information[text] );

//das steht doch schon oben!
//$user_infos['alliance'] = $information['tag'];

$buendnisse = array("name" => array($information[military_alliances], $information[military_alliances2], $information[military_alliances3]));

list ( $applied ) = sql_fetch_row(
        sql_query( "SELECT tag FROM alliance_applications WHERE user='$_SESSION[user]';" ) );
list ( $user_percent ) = sql_fetch_row(
        sql_query( "SELECT Round(100+COUNT(user)*0.01) AS max_members FROM usarios;" ) );

if ($_REQUEST[load] == 1) {
    $members = sql_fetch_array(
            sql_query(
                    "SELECT user,alliance_status,alliance_rank FROM usarios WHERE user='$_REQUEST[change_user]' && alliance='$user_infos[ID]';" ) );
    $template->set( 'members', $members );
}

$get_voting = sql_query( "SELECT * FROM voting WHERE tag='$user_infos[ID]';" );
$whole_voting = sql_fetch_array( $get_voting );
if (sql_num_rows( $get_voting ) > 0)
    $template->set( 'has_voting', 1 );

$applications = array ( );
$get_applications = sql_query(
//        "SELECT user,time,text FROM alliance_applications WHERE tag='$information[tag]';" );
        "SELECT user,time,text FROM alliance_applications WHERE tag='$user_infos[ID]';" );
       //laut alliance.php ist tag = $check_alliance[ID]

//if ($_SESSION[user] == 89){
//	echo "<pre>";
//		echo "Hallo Meph.";
//		echo "<br>Revision 15<br>";
//	echo "</pre>";
//}
        
        
if (sql_num_rows( $get_applications )) {
    $template->set( 'has_applications', 1 );
    while( $row = sql_fetch_array( $get_applications ) ) {
        $sUser = new User($row[user]);
        $row[username] = $sUser->getScreenName();
        $applications[] = $row;
    }
    $template->set( 'applications', $applications );
}

$ranks = array ( );
$get_ranks = sql_query( "SELECT id,rank FROM ranks WHERE tag='$user_infos[ID]' ORDER BY rank;" );
if (sql_num_rows( $get_ranks )) {
    $template->set( 'has_ranks', 1 );
    while( $row = sql_fetch_array( $get_ranks ) )
        $ranks[] = $row;
    $template->set( 'ranks', $ranks );
}

$get_possible_founders = sql_query(
        "SELECT user FROM usarios WHERE alliance='$user_infos[ID]' && user!='$_SESSION[user]';" );
while( $row = sql_fetch_array( $get_possible_founders ) )
    $possible_founders[] = $row;
$template->set( 'possible_founders', $possible_founders );

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
        "SELECT usarios.user,alliance_status,alliance_rank,last_action,points,logged_in,holiday FROM usarios JOIN userdata ON ( usarios.user = userdata.user ) ".
        "WHERE alliance='$user_infos[ID]' ORDER BY $ordertab;" );
        //"WHERE alliance='62' ORDER BY $ordertab;" );
while( $row = sql_fetch_array( $_members ) )
    $get_members[] = $row;
$template->set( 'get_members', $get_members );

$ads_banner = array( );
$get_ads_banner = sql_query("SELECT * FROM alliance_ads WHERE tag='$user_infos[alliance]' ORDER BY id;");
while( $row = sql_fetch_assoc( $get_ads_banner ) ) {
    $ads_banner[] = $row;
}

$template->set( 'ally_credit', number_format($information['ads_credit'],0,'','.') . ' Views');
$template->set( 'ads_banner_admin', $ads_banner );
$template->set( 'ads_enable_create', count($ads_banner) < 3 );
$template->set( 'viewFactor', number_format($viewFactor,0,'','.') );

$template->set( 'has_alliance', $user_infos[alliance] != '' );
$template->set( 'has_applied', $applied );
$template->set( 'user_percent', $user_percent );
$template->set( 'votes', array (1, 2, 3, 4, 5, 6, 7, 8, 9, 10 ) );
$template->set( 'information', $information );
$template->set( 'user_infos', $user_infos );
$template->set( 'whole_voting', $whole_voting );
$template->set( 'war_warning_noappl', $krieg->checkWarOptions(Krieg::NO_APPLICATION));
$template->set( 'war_warning_noleave', $krieg->checkWarOptions(Krieg::NO_LEAVE));
$template->set( 'war_warning', $krieg->inWar());
$template->set( 'user_path', $_SESSION['user_path'] );

$template->set( 'buendnisse', $buendnisse);

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
