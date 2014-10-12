<?php

require_once 'config.php';

/////////////////////////////////////////////////
/////////////Begin Script below./////////////////
/////////////////////////////////////////////////


// read the post from PayPal system and add 'cmd'
$req = 'cmd=_notify-validate';
foreach ( $_POST as $key => $value ) {
    $value = urlencode ( $value );
    $req .= '&'.$key.'='.$value;
}
// post back to PayPal system to validate
$header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
$header .= "Content-Length: " . strlen ( $req ) . "\r\n\r\n";

// If testing on Sandbox use:
//$fp = fsockopen ('ssl://www.sandbox.paypal.com', 443, $errno, $errstr, 30);


$fp = fsockopen ( 'ssl://www.paypal.com', 443, $errno, $errstr, 30 );

// assign posted variables to local variables
$item_name = $_POST ['item_name'];
$business = $_POST ['business'];
$item_number = $_POST ['item_number'];
$payment_status = $_POST ['payment_status'];
$mc_gross = $_POST ['mc_gross'];
$payment_currency = $_POST ['mc_currency'];
$txn_id = $_POST ['txn_id'];
$receiver_email = $_POST ['receiver_email'];
$receiver_id = $_POST ['receiver_id'];
$quantity = $_POST ['quantity'];
$num_cart_items = $_POST ['num_cart_items'];
$payment_date = $_POST ['payment_date'];
$first_name = $_POST ['first_name'];
$last_name = $_POST ['last_name'];
$payment_type = $_POST ['payment_type'];
$payment_status = $_POST ['payment_status'];
$payment_gross = $_POST ['payment_gross'];
$payment_fee = $_POST ['payment_fee'];
$settle_amount = $_POST ['settle_amount'];
$memo = $_POST ['memo'];
$payer_email = $_POST ['payer_email'];
$txn_type = $_POST ['txn_type'];
$payer_status = $_POST ['payer_status'];
$address_street = $_POST ['address_street'];
$address_city = $_POST ['address_city'];
$address_state = $_POST ['address_state'];
$address_zip = $_POST ['address_zip'];
$address_country = $_POST ['address_country'];
$address_status = $_POST ['address_status'];
$item_number = $_POST ['item_number'];
$tax = $_POST ['tax'];
$option_name1 = $_POST ['option_name1'];
$option_selection1 = $_POST ['option_selection1'];
$option_name2 = $_POST ['option_name2'];
$option_selection2 = $_POST ['option_selection2'];
$for_auction = $_POST ['for_auction'];
$invoice = $_POST ['invoice'];
$custom = $_POST ['custom'];
$notify_version = $_POST ['notify_version'];
$verify_sign = $_POST ['verify_sign'];
$payer_business_name = $_POST ['payer_business_name'];
$payer_id = $_POST ['payer_id'];
$mc_currency = $_POST ['mc_currency'];
$mc_fee = $_POST ['mc_fee'];
$exchange_rate = $_POST ['exchange_rate'];
$settle_currency = $_POST ['settle_currency'];
$parent_txn_id = $_POST ['parent_txn_id'];
$pending_reason = $_POST ['pending_reason'];
$reason_code = $_POST ['reason_code'];

// subscription specific vars


$subscr_id = $_POST ['subscr_id'];
$subscr_date = $_POST ['subscr_date'];
$subscr_effective = $_POST ['subscr_effective'];
$period1 = $_POST ['period1'];
$period2 = $_POST ['period2'];
$period3 = $_POST ['period3'];
$amount1 = $_POST ['amount1'];
$amount2 = $_POST ['amount2'];
$amount3 = $_POST ['amount3'];
$mc_amount1 = $_POST ['mc_amount1'];
$mc_amount2 = $_POST ['mc_amount2'];
$mc_amount3 = $_POST ['mcamount3'];
$recurring = $_POST ['recurring'];
$reattempt = $_POST ['reattempt'];
$retry_at = $_POST ['retry_at'];
$recur_times = $_POST ['recur_times'];
$username = $_POST ['username'];
$password = $_POST ['password'];

//auction specific vars


$for_auction = $_POST ['for_auction'];
$auction_closing_date = $_POST ['auction_closing_date'];
$auction_multi_item = $_POST ['auction_multi_item'];
$auction_buyer_id = $_POST ['auction_buyer_id'];

//DB connect creds and email
$notify_email = "gawain@camlann.de"; //email address to which debug emails are sent to
$DB_Server = $dbServer;
$DB_Username = $dbLogin;
$DB_Password = $dbPwd;
$DB_DBName = $dbName;


if (! $fp) {
    // HTTP ERROR
} else {
    fputs ( $fp, $header . $req );
    while ( ! feof ( $fp ) ) {
        $res = fgets ( $fp, 1024 );
        if (strcmp ( $res, "VERIFIED" ) == 0) {

            //create MySQL connection
            $Connect = @mysql_connect ( $DB_Server, $DB_Username, $DB_Password ) or die ( "Couldn't connect to MySQL:<br>" . mysql_error () . "<br>" . mysql_errno () );

            //select database
            $Db = @mysql_select_db ( $DB_DBName, $Connect ) or die ( "Couldn't select database:<br>" . mysql_error () . "<br>" . mysql_errno () );

            $fecha = date ( "m" ) . "/" . date ( "d" ) . "/" . date ( "Y" );
            $fecha = date ( "Y" ) . date ( "m" ) . date ( "d" );

            //check if transaction ID has been processed before
            $checkquery = "select txnid from paypal_payment_info where txnid='" . $txn_id . "'";
            $sihay = mysql_query ( $checkquery ) or die ( "Duplicate txn id check query failed:<br>" . mysql_error () . "<br>" . mysql_errno () );
            $nm = mysql_num_rows ( $sihay );
            if ($nm == 0) {

                //execute query


                if ($txn_type == "cart") {
                    $strQuery = "insert into paypal_payment_info(paymentstatus,buyer_email,firstname,lastname,street,city,state,zipcode,country,mc_gross,mc_fee,memo,paymenttype,paymentdate,txnid,pendingreason,reasoncode,tax,datecreation) values ('" . $payment_status . "','" . $payer_email . "','" . $first_name . "','" . $last_name . "','" . $address_street . "','" . $address_city . "','" . $address_state . "','" . $address_zip . "','" . $address_country . "','" . $mc_gross . "','" . $mc_fee . "','" . $memo . "','" . $payment_type . "','" . $payment_date . "','" . $txn_id . "','" . $pending_reason . "','" . $reason_code . "','" . $tax . "','" . $fecha . "')";

                    $result = mysql_query ( $strQuery ) or die ( "Cart - paypal_payment_info, Query failed:<br>" . mysql_error () . "<br>" . mysql_errno () );
                    for($i = 1; $i <= $num_cart_items; $i ++) {
                        $itemname = "item_name" . $i;
                        $itemnumber = "item_number" . $i;
                        $on0 = "option_name1_" . $i;
                        $os0 = "option_selection1_" . $i;
                        $on1 = "option_name2_" . $i;
                        $os1 = "option_selection2_" . $i;
                        $quantity = "quantity" . $i;

                        $struery = "insert into paypal_cart_info(txnid,itemnumber,itemname,os0,on0,os1,on1,quantity,invoice,custom) values ('" . $txn_id . "','" . $_POST [$itemnumber] . "','" . $_POST [$itemname] . "','" . $_POST [$on0] . "','" . $_POST [$os0] . "','" . $_POST [$on1] . "','" . $_POST [$os1] . "','" . $_POST [$quantity] . "','" . $invoice . "','" . $custom . "')";
                        $result = mysql_query ( $struery ) or die ( "Cart - paypal_cart_info, Query failed:<br>" . mysql_error () . "<br>" . mysql_errno () );

                    }
                }

                else {
                    $strQuery = "insert into paypal_payment_info(paymentstatus,buyer_email,firstname,lastname,street,city,state,zipcode,country,mc_gross,mc_fee,itemnumber,".
                    "itemname,os0,on0,os1,on1,quantity,memo,paymenttype,paymentdate,txnid,pendingreason,reasoncode,tax,datecreation,custom) values ".
                    "('" . $payment_status . "','" . $payer_email . "','" . $first_name . "','" . $last_name . "','" . $address_street . "','" . $address_city . "','" . $address_state . "','" . $address_zip . "','" . $address_country . "','" . $mc_gross . "','" . $mc_fee . "','" . $item_number . "','" . $item_name . "','" . $option_name1 . "','" . $option_selection1 . "','" . $option_name2 . "','" . $option_selection2 . "','" . $quantity . "','" . $memo . "','" . $payment_type . "','" . $payment_date . "','" . $txn_id . "','" . $pending_reason . "','" . $reason_code . "','" . $tax . "','" . $fecha . "', '".$custom."')";
                    $result = mysql_query ( "insert into paypal_payment_info(paymentstatus,buyer_email,firstname,lastname,street,city,state,zipcode,country,mc_gross,mc_fee,itemnumber,".
                    "itemname,os0,on0,os1,on1,quantity,memo,paymenttype,paymentdate,txnid,pendingreason,reasoncode,tax,datecreation,custom) values ".
                    "('" . $payment_status . "','" . $payer_email . "','" . $first_name . "','" . $last_name . "','" . $address_street . "','" . $address_city . "','" . $address_state . "','" . $address_zip . "','" . $address_country . "','" . $mc_gross . "','" . $mc_fee . "','" . $item_number . "','" . $item_name . "','" . $option_name1 . "','" . $option_selection1 . "','" . $option_name2 . "','" . $option_selection2 . "','" . $quantity . "','" . $memo . "','" . $payment_type . "','" . $payment_date . "','" . $txn_id . "','" . $pending_reason . "','" . $reason_code . "','" . $tax . "','" . $fecha . "', '".$custom."')" )
                    or die ( "Default - paypal_payment_info, Query failed:<br>" . mysql_error () . "<br>" . mysql_errno () );
                }

                $userName = $custom;
                $trans_id = $txn_id;

                // insert donation record in the donation table
                $toShow = round (floatval($_POST['mc_gross']) / 0.0034, 0);
                if ($toShow <= 1440) {
                    $current = $toShow;
                    $toShow = 0;
                }
                else {
                    $current = 1440;
                    $toShow = $toShow - 1440;
                }

                if (strlen($userName) != 0)
                    mysql_query('INSERT INTO donations (id,user,ident,date,amount,to_show,current,type) VALUES '.
                        '(0,"'.$userName.'","'.$trans_id.'", current_date(), '.floatval($_POST['mc_gross']).','.intval($toShow).','.intval($current).',"u")');

                // add donation to (users) alliance account
                list( $alliance ) = mysql_fetch_row( mysql_query(
                    "SELECT alliance FROM usarios WHERE user='$userName'" )
                );

                $views = floatval($_POST['mc_gross']) * $viewFactor;
                mysql_query('UPDATE alliances SET ads_credit = ads_credit + '.$views.' WHERE tag="'.$alliance.'"');

                // send an email in any case
#                echo "Verified";
#                mail ( $notify_email, "VERIFIED IPN", "$res\n $req\n $strQuery\n $struery\n  $strQuery2" );
            } else {
                // send an email
#                mail ( $notify_email, "VERIFIED DUPLICATED TRANSACTION", "$res\n $req \n $strQuery\n $struery\n  $strQuery2" );
            }

            //subscription handling branch
            if ($txn_type == "subscr_signup" || $txn_type == "subscr_payment") {

                // insert subscriber payment info into paypal_payment_info table
                $strQuery = "insert into paypal_payment_info(paymentstatus,buyer_email,firstname,lastname,street,city,state,zipcode,country,mc_gross,mc_fee,memo,paymenttype,paymentdate,txnid,pendingreason,reasoncode,tax,datecreation) values ('" . $payment_status . "','" . $payer_email . "','" . $first_name . "','" . $last_name . "','" . $address_street . "','" . $address_city . "','" . $address_state . "','" . $address_zip . "','" . $address_country . "','" . $mc_gross . "','" . $mc_fee . "','" . $memo . "','" . $payment_type . "','" . $payment_date . "','" . $txn_id . "','" . $pending_reason . "','" . $reason_code . "','" . $tax . "','" . $fecha . "')";
                $result = mysql_query ( $strQuery ) or die ( "Subscription - paypal_payment_info, Query failed:<br>" . mysql_error () . "<br>" . mysql_errno () );

                // insert subscriber info into paypal_subscription_info table
                $strQuery2 = "insert into paypal_subscription_info(subscr_id , sub_event, subscr_date ,subscr_effective,period1,period2, period3, amount1 ,amount2 ,amount3,  mc_amount1,  mc_amount2,  mc_amount3, recurring, reattempt,retry_at, recur_times, username ,password, payment_txn_id, subscriber_emailaddress, datecreation) values ('" . $subscr_id . "', '" . $txn_type . "','" . $subscr_date . "','" . $subscr_effective . "','" . $period1 . "','" . $period2 . "','" . $period3 . "','" . $amount1 . "','" . $amount2 . "','" . $amount3 . "','" . $mc_amount1 . "','" . $mc_amount2 . "','" . $mc_amount3 . "','" . $recurring . "','" . $reattempt . "','" . $retry_at . "','" . $recur_times . "','" . $username . "','" . $password . "', '" . $txn_id . "','" . $payer_email . "','" . $fecha . "')";
                $result = mysql_query ( $strQuery2 ) or die ( "Subscription - paypal_subscription_info, Query failed:<br>" . mysql_error () . "<br>" . mysql_errno () );

                mail ( $notify_email, "VERIFIED IPN", "$res\n $req\n $strQuery\n $struery\n  $strQuery2" );

            }
        }

        // if the IPN POST was 'INVALID'...do this


        else if (strcmp ( $res, "INVALID" ) == 0) {
            // log for manual investigation


            mail ( $notify_email, "Paypal: Invalid IPN", "$res\n $req" );
        }
    }
    fclose ( $fp );
}

/*
# Table structure for table `paypal_cart_info` #
CREATE TABLE `paypal_cart_info` (
    `txnid` varchar(30) NOT NULL default '',
    `itemname` varchar(255) NOT NULL default '',
    `itemnumber` varchar(50) default NULL,
    `os0` varchar(20) default NULL,
    `on0` varchar(50) default NULL,
    `os1` varchar(20) default NULL,
    `on1` varchar(50) default NULL,
    `quantity` char(3) NOT NULL default '',
    `invoice` varchar(255) NOT NULL default '',
    `custom` varchar(255) NOT NULL default ''
) TYPE=MyISAM;
# Table structure for table `paypal_subscription_info` #
CREATE TABLE `paypal_subscription_info` (
    `subscr_id` varchar(255) NOT NULL default '',
    `sub_event` varchar(50) NOT NULL default '',
    `subscr_date` varchar(255) NOT NULL default '',
    `subscr_effective` varchar(255) NOT NULL default '',
    `period1` varchar(255) NOT NULL default '',
    `period2` varchar(255) NOT NULL default '',
    `period3` varchar(255) NOT NULL default '',
    `amount1` varchar(255) NOT NULL default '',
    `amount2` varchar(255) NOT NULL default '',
    `amount3` varchar(255) NOT NULL default '',
    `mc_amount1` varchar(255) NOT NULL default '',
    `mc_amount2` varchar(255) NOT NULL default '',
    `mc_amount3` varchar(255) NOT NULL default '',
    `recurring` varchar(255) NOT NULL default '',
    `reattempt` varchar(255) NOT NULL default '',
    `retry_at` varchar(255) NOT NULL default '',
    `recur_times` varchar(255) NOT NULL default '',
    `username` varchar(255) NOT NULL default '',
    `password` varchar(255) default NULL,
    `payment_txn_id` varchar(50) NOT NULL default '',
    `subscriber_emailaddress` varchar(255) NOT NULL default '',
    `datecreation` date NOT NULL default '0000-00-00'
) TYPE=MyISAM;
# Table structure for table `paypal_payment_info` #
CREATE TABLE `paypal_payment_info` (
    `firstname` varchar(100) NOT NULL default '',
    `lastname` varchar(100) NOT NULL default '',
    `buyer_email` varchar(100) NOT NULL default '',
    `street` varchar(100) NOT NULL default '',
    `city` varchar(50) NOT NULL default '',
    `state` char(3) NOT NULL default '',
    `zipcode` varchar(11) NOT NULL default '',
    `memo` varchar(255) default NULL,
    `itemname` varchar(255) default NULL,
    `itemnumber` varchar(50) default NULL,
    `os0` varchar(20) default NULL,
    `on0` varchar(50) default NULL,
    `os1` varchar(20) default NULL,
    `on1` varchar(50) default NULL,
    `quantity` char(3) default NULL,
    `paymentdate` varchar(50) NOT NULL default '',
    `paymenttype` varchar(10) NOT NULL default '',
    `txnid` varchar(30) NOT NULL default '',
    `mc_gross` varchar(6) NOT NULL default '',
    `mc_fee` varchar(5) NOT NULL default '',
    `paymentstatus` varchar(15) NOT NULL default '',
    `pendingreason` varchar(10) default NULL,
    `txntype` varchar(10) NOT NULL default '',
    `tax` varchar(10) default NULL,
    `mc_currency` varchar(5) NOT NULL default '',
    `reasoncode` varchar(20) NOT NULL default '',
    `custom` varchar(255) NOT NULL default '',
    `country` varchar(20) NOT NULL default '',
    `datecreation` date NOT NULL default '0000-00-00'
) TYPE=MyISAM;
 */
?>
