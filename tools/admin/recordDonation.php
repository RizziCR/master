<?php
/*
 * Created on 08.03.2008
 *
 * author: Neltakh
 */

    require_once("database.php");

    // define phptal template
    require_once("PHPTAL.php");
    require_once("include/PHPTAL_EtsTranslator.php");
    $template = new PHPTAL('tools/recordDonation.html');
    $template->setTranslator(new PHPTAL_EtsTranslator());
    $template->setEncoding('ISO-8859-1');

    // include common template settings
    require_once("include/TemplateSettingsCommonGuest.php");

    // set page title
    $template->set('pageTitle', 'Administration - Spendeneinnahmen');

    //if($acl == 'ADMIN' || $acl == 'SUPPORT')  {

        if(isset($_POST[ident])) {
            $ident = split('-', $_POST[ident]);

            // get user name
            $posUsers = sql_query('SELECT user FROM userdata WHERE UPPER(user) LIKE "'. $ident[0] .'%"');

            $userName ='';
            $rightIdent = false;
            while( ($posUsersRow = sql_fetch_array( $posUsers )) && !$rightIdent )
            {
                $ident_res = sql_query('SELECT COUNT(user)+1 AS cuser FROM donations WHERE user="'.$posUsersRow[user].'"');
                $row = sql_fetch_assoc($ident_res);
                $trans_id = substr($posUsersRow[user],0,4).'-'.$row[cuser].'-'.substr(sha1($row[cuser].$posUsersRow[user].'äbqLwj06'),0,8);
                if (strtolower($trans_id) == strtolower($_POST[ident]))
                {
                    $rightIdent = true;
                    $userName = $posUsersRow[user];
                }
            }

            // get alliance name
            $allianceName ='';
            if (strlen($userName) == 0 )
            {
                $posAlliance = sql_query('SELECT tag FROM alliances WHERE UPPER(tag) LIKE "'. $ident[0] .'%"');

                $rightIdent = false;
                while( ($posAllianceRow = sql_fetch_array( $posAlliance )) && !$rightIdent )
                {
                    $ident_res = sql_query('SELECT COUNT(user)+1 AS calliance FROM donations WHERE user="'.$posAllianceRow[tag].'"');
                    $row = sql_fetch_assoc($ident_res);
                    $trans_id = substr($posAllianceRow[tag],0,4).'-'.$row[calliance].'-'.substr(sha1($row[calliance].$posAllianceRow[tag].'drtfKü09'),0,8);
                    if (strtolower($trans_id) == strtolower($_POST[ident]))
                    {
                        $rightIdent = true;
                        $allianceName = $posAllianceRow[tag];
                    }
                }
            }

            if ((strlen($userName) == 0) && (strlen($allianceName) == 0))
            {
                // TODO: Anonymen Spender ermoeglichen
                $template->set('errorMessageUser', true);
            }
            else {
                // insert donation record in the donation table
                $toShow = round ($_POST[amount] / 0.0034, 0);
                if ($toShow <= 1440) {
                    $current = $toShow;
                    $toShow = 0;
                }
                else {
                    $current = 1440;
                    $toShow = $toShow - 1440;
                }

                if (strlen($userName) != 0){
                    sql_query('INSERT INTO donations (id,user,ident,date,amount,to_show,current,type) VALUES (0,"'.$userName.'","'.$trans_id.'","'.addslashes($_POST[ddate]).'","'.addslashes($_POST[amount]).'","'.$toShow.'","'.$current.'","u")');
					// Eintrag in Supporter-Log
					sql_query("INSERT INTO logs_support (supporter, action, action_value, timestamp)
					VALUES ('$_SESSION[supporter]', 'System', '<b>Spende	 </b>User: ".$userName.", Spende: ".$_POST[amount]." &euro;', '".time()."')");
					// Ende Eintrag
					}
                else if (strlen($allianceName) != 0){
                    sql_query('INSERT INTO donations (id,user,ident,date,amount,to_show,current,type) VALUES (0,"'.$allianceName.'","'.$trans_id.'","'.addslashes($_POST[ddate]).'","'.addslashes($_POST[amount]).'","'.$toShow.'","'.$current.'","a")');
					// Eintrag in Supporter-Log
					sql_query("INSERT INTO logs_support (supporter, action, action_value, timestamp)
					VALUES ('$_SESSION[supporter]', 'System', '<b>Spende </b>Allianz: ".$userName.", Spende: ".$_POST[amount]." &euro;', '".time()."')");
					// Ende Eintrag
					}
                // add donation to (users) alliance account
                if (strlen($userName) != 0)
                    list( $alliance ) = sql_fetch_row( sql_query(
                        "SELECT alliance FROM usarios WHERE user='$userName'" )
                    );
                else if (strlen($allianceName) != 0)
                    $alliance = $allianceName;

                $views = floatval($_POST[amount]) * $viewFactor;
                sql_query('UPDATE alliances SET ads_credit = ads_credit + '.$views.' WHERE tag="'.$alliance.'"');

                $template->set('donationSuccess', true);
            }
        }

        $getAllDonations = sql_query('SELECT * FROM donations');
        if (sql_num_rows( $getAllDonations ) >= 1 ) {
            while( $row = sql_fetch_array( $getAllDonations ) )
                $allDonations[] = $row;
            $template->set( 'allDonations', $allDonations );
        }
    //}
    //else {
    //    $template->set('errorMessageACL', true);
    //}

    // create html page
    try {
        echo $template->execute();
    }
    catch (Exception $e) { echo $e->getMessage(); }
?>
