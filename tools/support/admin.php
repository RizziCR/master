<?php
include_once("../session.php");
$support_timestamp = time();
    require_once("database.php");
    require_once("functions.php");
	require_once("../htmlheader.php");
	echo "<h2>Supportoberfl&auml;che</h2>";
    switch ($_GET[a])
    {
        case "confirm" :
            #$do = sql_query("UPDATE userdata SET confirmation='Y' WHERE user='".addslashes($_GET[user])."'");
			$do = sql_query("UPDATE userdata SET confirmation='Y';");
			// Eintrag in Support-Log
			#sql_query("INSERT INTO logs_support (supporter, action, action_value, target_user, timestamp)
			#		VALUES ('$_SESSION[supporter]', 'Freischaltung', '<b>Login</b> freigeschaltet', '". addslashes($_GET[user]) ."', '$support_timestamp')");
			sql_query("INSERT INTO logs_support (supporter, action, action_value, target_user, timestamp)
			VALUES ('$_SESSION[supporter]', 'Freischaltung', '<b>Login</b> freigeschaltet für ALLE User', 'Alle User', '$support_timestamp')");
				
			// Ende Support-Log

            if ($do)
                echo "<font color=\"#00FF00\">Der Benutzer ".$_GET[user]." wurde freigeschaltet</font>";
            else
                echo "<font color=\"#FF0000\">Fehler beim Benutzer freischalten</font>";

            echo "<br><br><br>";

            break;

        case "nocaptcha" :
            $do = sql_query("UPDATE userdata SET user_captcha_free='yes' WHERE user='".addslashes($_GET[user])."'");
			// Eintrag in Support-Log
			sql_query("INSERT INTO logs_support (supporter, action, action_value, target_user, timestamp)
					VALUES ('$_SESSION[supporter]', 'Freischaltung', '<b>Captcha</b> abgeschaltet', '". addslashes($_GET[user]) ."', '$support_timestamp')");
			// Ende Support-Log
            if ($do)
                echo "<font color=\"#00FF00\">Das Handy-Captcha f&uuml;r ".$_GET[user]." wurde abgeschaltet</font>";
            else
                echo "<font color=\"#FF0000\">Fehler beim Handy-Captcha abschalten</font>";

            echo "<br><br><br>";

            break;

        case "ban" :
			// Variablen vorbereiten
			if(isset($_GET[ban1d])){
				$bantime = $_GET[time_from] + 1*86400;
				$time_block_log = "Sperre -> 24h";
				$action_value_log = "Benutzer gesperrt bis <b>".date("d.m., H:i",$bantime)."</b>";
			}
			if(isset($_GET[ban2d])){
				$bantime = $_GET[time_from] + 2*86400;
				$time_block_log = "Sperre -> 48h";
				$action_value_log = "Benutzer gesperrt bis <b>".date("d.m., H:i",$bantime)."</b>";
			}
			if(isset($_GET[ban7d])){
				$bantime = $_GET[time_from] + 7*86400;
				$time_block_log = "Sperre -> 7d";
				$action_value_log = "Benutzer gesperrt bis <b>".date("d.m., H:i",$bantime)."</b>";
			}
			// Eintrag ins Log
			sql_query("INSERT INTO logs_support (supporter, action, action_value, target_user, timestamp) 
					VALUES ('$_SESSION[supporter]', '".addslashes($time_block_log)."','".addslashes($action_value_log)."', '". addslashes($_GET[user]) ."', $support_timestamp)");
			// Ende Logeintrag			
			
            $do = sql_query("UPDATE userdata SET time_block='$bantime' WHERE ID='".addslashes($_GET[user])."'");
			
            if ($_GET[az])
            {
                $do_mail = sql_query("UPDATE admin_agb_delict SET done='Y' WHERE id='".addslashes($_GET[id])."'");
                $get_email = sql_query("SELECT email FROM userdata WHERE ID='".addslashes($_GET[recipient])."'");
                $email = sql_fetch_array($get_email);

                smtp_mail($email[email],$_GET[betreff],nl2br($_GET[msg]));
            }
	    if ($do) {
		$user = sql_fetch_array(aql_query("SELECT user FROM usarios WHERE ID='".addslashes($_GET[user])."'"));
                echo "<font color=\"#00FF00\">Der Benutzer ".$user[user]." wurde gesperrt</font><br>"; }
            else
                echo "<font color=\"#FF0000\">Fehler beim Benutzer sperren</font><br>";
	    if ($do_mail && $email)
                echo "<font color=\"#00FF00\">E-Mail versendet</font>";
            else
                echo "<font color=\"#FF0000\">Keine E-Mail versendet</font>";

            echo "<br><br><br>";

            break;

        case "agb_offence" :
            $do_mail = sql_query("UPDATE admin_agb_delict SET done='Y' WHERE id='".addslashes($_GET[id])."'");

            $get_email = sql_query("SELECT email FROM userdata WHERE user='".addslashes($_GET[recipient])."'");
            $email = sql_fetch_array($get_email);

	    smtp_mail($email[email],$_GET[betreff],nl2br($_GET[msg]));

	    if ($do_mail && $email)
                echo "<font color=\"#00FF00\">E-Mail versendet</font>";
            else
                echo "<font color=\"#FF0000\">Keine E-Mail versendet</font>";

            break;

        case "drop_complaint" :
            $do = sql_query("UPDATE admin_agb_delict SET done='Y' WHERE id='".addslashes($_GET[id])."'");
	    if ($do)
                echo "<font color=\"#00FF00\">Beschwerde verworfen</font>";
            else
                echo "<font color=\"#FF0000\">Fehler beim Verwerfen der Beschwerde</font>";

            echo "<br><br><br>";

            break;
    }
	$not_activated_result = sql_query("SELECT user FROM userdata WHERE confirmation = 'N' AND delacc = '0' AND delacc2 NOT LIKE 'N' ORDER BY user");
	while($not_activated_array = sql_fetch_array($not_activated_result)){
    $not_activated_options .= "<option value=".$not_activated_array[user]."><font color=000000>".$not_activated_array[user]."</option>";
	}
    $support =	array(
                    array(	"Benutzer nicht freigeschaltet",
                            "E-Mail mit Best&auml;tigung bei der Anmeldung nicht erhalten",
                            "<form action=\"$PHP_SELF?a=confirm\" method=get><input type=hidden name=a value=confirm>Benutzer freischalten:<br>
							<select name=user>
							".$not_activated_options."
							</select>
							<input type=submit value=Freischalten></form>"),
                    array(	"Benutzer hat Probleme, die graphische Anmeldeh&uuml;rde (Captcha) zu meistern",
                            "Sehschw&auml;che, Handy mit schlechter Zeigersteuerung, o.&auml;.",
                            "<form action=\"$PHP_SELF?a=nocaptcha\" method=get><input type=hidden name=a value=nocaptcha>Captcha-Auswertung auf der Handyanmeldungsseite abschalten:<br><input type=text name=user><input type=submit value=Abschalten></form>"),
                    array(	"Benutzer kann sich einloggen, kommt aber nur auf die Startseite und fliegt sonst raus",
                            "verwendet Proxy mit wechselnden IPs oder akzeptiert Cookies nicht",
                            "Alternativlogin freischalten (Login-Fehlerseite) oder Cookies im Browser aktivieren"),
                    array(	"Beleidigung (gr&ouml;&szlig;ere Verst&ouml;&szlig;e bitte Admin melden)",
                            "",
                            "<form action=\"$PHP_SELF\" method=get><input type=hidden name=a value=ban>Benutzer 24 Stunden sperren:<br><input type=text name=user><input type=submit value=Sperren></form>"),
                    array(	"anderes Problem",
                            "nicht bekannt / nicht zu beheben",
                            "Mail an Admin weiterleiten")
                );

?>


<u>Herzlich Willkommen im Support-Men&uuml;, hier kannst du alle h&auml;ufig anfallenden Benutzer-Probleme l&ouml;sen</u><br><br><br>

<table width=100% border=0 cellpadding=3 cellspacing=0>
<tr>
    <td>
        <b>Problem</b>
    </td>
    <td>
        <b>Ursache(n)</b>
    </td>
    <td>
        <b>L&ouml;sung</b>
    </td>
</tr>


<?php
    for ($i=0;$i<count($support);$i++)
        echo "	<tr valign=top align=left bgcolor=". (($i%2) ? "#222222" : "#444444") .">
                    <td>
                        {$support[$i][0]}
                    </td>
                    <td>
                        {$support[$i][1]}
                    </td>
                    <td>
                        {$support[$i][2]}
                    </td>
                </tr>";
?>
</table>



<?php
    $get_admin_agb_delicts = sql_query("SELECT * FROM admin_agb_delict WHERE done='N' LIMIT 1");
    if (sql_num_rows($get_admin_agb_delicts))
    {
        $admin_agb_delict = sql_fetch_array($get_admin_agb_delicts);
		// Daxl Test
		$result = sql_query("SELECT user, time_block FROM userdata WHERE time_block > '". time() ."' AND user = '".$admin_agb_delict[sender]."'");
		$array = sql_fetch_array($result);
		if($array[time_block] > time()){
			$act_ban = "<span style=\"background-color: red; color:white;\">Aktuell gesperrt bis: ".strftime('%A, %d.%m.%Y %R Uhr', $array[time_block])."</span><br>";
			$button1 = "Mail senden &amp; ".$admin_agb_delict[sender]." weitere 24h sperren";
			$button2 = "Weitere 48h sperren";
			$button3 = "Weitere 7 Tage sperren";
			$time_from = $array[time_block];
			} ELSE {
			$button1 = "Mail senden &amp; ".$admin_agb_delict[sender]." 24h sperren";
			$button2 = "48h sperren";
			$button3 = "7 Tage sperren";
			$time_from = time();
		}
		// Ende Daxl Test

        echo "	<br><br><br><br>
                <u>Beleidigungen und anderen AGB-widrige IGMs</u><br><br><br>

                <table border=0 cellpadding=0 cellspacing=0>
                <tr valign=top><td><b>Von:</b> $admin_agb_delict[sender]</td></tr>
                <tr valign=top><td><b>An:</b> $admin_agb_delict[recipient]</td></tr>
                <tr valign=top><td><b>Zeit:</b> ". date("H:i:s d.m.Y",$admin_agb_delict[time]) ."</td></tr>
                <tr valign=top><td><b>Betreff:</b> $admin_agb_delict[topic]</td></tr>
                <tr valign=top><td><b>Nachricht:</b><br><br> $admin_agb_delict[text]</td></tr>
                <tr>
                    <form action=\"$PHP_SELF\" method=get>
                    <td>
                        <br><br><br><b>An den Empf&auml;nger</b><br>
                        <input type=hidden name=a value=ban>
                        <input type=hidden name=az value=agb_offence>
                        <input type=hidden name=id value=\"$admin_agb_delict[id]\">
                        <input type=hidden name=user value=\"$admin_agb_delict[sender]\">
                        <input type=hidden name=recipient value=\"$admin_agb_delict[recipient]\">
						<input type=hidden name=time_from value=\"$time_from\">
                        <input type=text name=betreff size=50 value=\"Regelversto&szlig; bez&uuml;gl. ingame-Nachricht &uuml;berpr&uuml;ft\"><br>
                        <textarea cols=40 rows=8 name=msg>Hallo,\n\nder von dir gemeldete Regelversto&szlig; durch $admin_agb_delict[sender] wurde mit einer Zeitstrafe geahndet.\n\nDie ETS-Spielbetreuung</textarea><br>
                        ".$act_ban."
						<input type=submit name=ban1d value=\"".$button1."\"><br>
						<input type=submit name=ban2d value=\"".$button2."\"><input type=submit name=ban7d value=\"".$button3."\"><br>
						<br><br>
                    </td>
<!--					<td>
                        <b>An den Empf&auml;nger</b><br>
                        <input type=text name=rbetreff size=50>
                        <textarea cols=40 rows=8 name=rmsg></textarea>
                    </td>-->
                    </form>
                </tr>
                <tr>
                    <form action=\"$PHP_SELF\" method=get>
                    <td>
                        <b>An den Empf&auml;nger</b><br>
                        <input type=hidden name=a value=agb_offence>
                        <input type=hidden name=az value=agb_offence>
                        <input type=hidden name=id value=\"$admin_agb_delict[id]\">
                        <input type=hidden name=user value=\"$admin_agb_delict[sender]\">
                        <input type=hidden name=recipient value=\"$admin_agb_delict[recipient]\">
                        <input type=text name=betreff size=50 value=\"Regelversto&szlig; bez&uuml;gl. ingame-Nachricht &uuml;berpr&uuml;ft\"><br>
                        <textarea cols=40 rows=8 name=msg>Hallo,\n\nder von dir gemeldete Regelversto&szlig; durch $admin_agb_delict[sender] wurde aufgrund der zu geringen Schwere nicht geahndet.\n\nDie ETS-Spielbetreuung</textarea><br>
                        <input type=submit value=\"Mail senden & ".$admin_agb_delict[sender]." nicht sperren\"><br><br><br>
                    </td>
<!--					<td>
                        <b>An den Empf&auml;nger</b><br>
                        <input type=text name=rbetreff size=50>
                        <textarea cols=40 rows=8 name=rmsg></textarea>
                    </td>-->
                    </form>
                </tr>
                <tr>
                    <form action=\"$PHP_SELF\" method=get>
                    <td>
                        <b>Beschwerde l&ouml;schen ohne Benachrichtigung</b><br>
                        <input type=hidden name=a value=drop_complaint>
                        <input type=hidden name=id value=\"$admin_agb_delict[id]\">
                        <input type=hidden name=user value=\"$admin_agb_delict[sender]\">
                        <input type=hidden name=recipient value=\"$admin_agb_delict[recipient]\">
                        <input type=submit value=\"Beschwerde verwerfen\">
                    </td>
                    </form>
                </tr>
                </table>";
    }

    echo "<br><br><br><b>Die letzten 10 Beschwerden</b><br><br>";

    $get_agb_delict = sql_query("SELECT sender,recipient,time,topic,text FROM admin_agb_delict ORDER BY time DESC LIMIT 10");
    while ($agb_delict = sql_fetch_array($get_agb_delict)) {
        echo "<b>von $agb_delict[sender] an $agb_delict[recipient] um ". date("H:i:s",$agb_delict[time]) ." am ". date("d.m.Y",$agb_delict[time]) ."</b><br>
        Betreff: $agb_delict[topic]<br>
        Nachricht: $agb_delict[text]<br><br>";
    }

?>

</body>
</HTML>
