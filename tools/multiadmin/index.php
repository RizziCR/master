<?php
include_once("../session.php");
echo"<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\">
	<html>
	<head>
	<title>MultiAdmin</title>
	<link rel=\"stylesheet\" type=\"text/css\" href=\"../css.css\">
	</head>
	<body>
	<h2>Multi-Admin</h2>
	<table align=\"left\" cellspacing=\"0\" cellpadding=\"0\">
		<tr>
			<td>Benutzernamen eingeben:<br>			
			<form action=\"$PHP_SELF\" method=\"post\"><input name=\"suspect_user\" type=\"text\" value=\"".$_POST[suspect_user]."\"><input type=\"submit\" name=\"suche\" value=\"Name\"><input type=\"submit\" name=\"suche\" value=\"Mail\"><input type=\"submit\" name=\"suche\" value=\"Allianz\"></form></td>
		</tr>
		<tr>
			<td>Letzte Suche nach <b>".$_SESSION[suche]."</b> mit Buchstabenfolge <b>".$_SESSION[suspect_user]."</b> <form action=\"$PHP_SELF\" method=\"post\"><input type=\"hidden\" name=\"suspect_user\" value=\"".$_SESSION[suspect_user]."\"><input type=\"hidden\" name=\"suche\" value=\"".$_SESSION[suche]."\"><input type=\"submit\" value=\"Suche wiederholen\"></form></td>
		</tr>
		<tr>
			<td>
			<table border=\"0\" cellpadding=\"5\" cellspacing=\"0\">";
    require_once("database.php");
	
	// Test
	if(empty($_POST[suspect_user]) && empty($_GET[suspect_user])) {
			echo
			"<tr>
				<td colspan=\"2\"><b>Bitte einen Benutzernamen eingeben!</b></td>
			</tr>";
	exit;
    } ELSE {
    if(!empty($_POST[suspect_user])) {
	$_SESSION[suspect_user] = $_POST[suspect_user];
	$_SESSION[suche] = $_POST[suche];
	echo
			"<tr>
			<td colspan=\"2\">
				<table align=\"left\" cellspacing=\"0\" cellpadding=\"0\">
				<tr>
					<td><b>Benutzer mit der Buchstabenfolge \"".$_POST[suspect_user]."\"</b>:</td>
				</tr>";
				switch($_POST[suche]){
				case "Name":
					$names = sql_query("SELECT usarios.user, userdata.email FROM usarios, userdata WHERE usarios.user=userdata.user AND usarios.user LIKE '%". addslashes($_POST[suspect_user]) ."%'");
					break;
				case "Mail":
					$names = sql_query("SELECT usarios.user, userdata.email FROM usarios, userdata WHERE usarios.user=userdata.user AND userdata.email LIKE '%". addslashes($_POST[suspect_user]). "%'");
					break;
				case "Allianz":
					$names = sql_query("SELECT usarios.user, userdata.email FROM usarios, userdata WHERE usarios.user=userdata.user AND usarios.alliance = '". addslashes($_POST[suspect_user]) ."'");
					break;
				}
			while($name = sql_fetch_array($names)) {
			echo
				"<tr>
				<td><a href=\"index.php?suspect_user=".$name[user]."\">".$name[user]." (".$name[email].")</a></td>
				</tr>";
			}
			echo
				"</table>
			</td>
			</tr>";
			}
	// Ende Test

			if ($_GET[sperren]) {
				sql_query("UPDATE userdata SET multi='Y' WHERE user='".addslashes($_GET[sperren])."'");
				sql_query("INSERT INTO logs_support (supporter, action, action_value, timestamp)
				VALUES ('$_SESSION[supporter]', 'System', '<b>Multisperre </b>(User ".addslashes($_GET[sperren]).")', '".time()."')");
			}
			if ($_GET[entsperren]) {
				sql_query("UPDATE userdata SET multi='N' WHERE user='".addslashes($_GET[entsperren])."'");
				sql_query("INSERT INTO logs_support (supporter, action, action_value, timestamp)
				VALUES ('$_SESSION[supporter]', 'System', '<b>Entsperren </b>(User ".addslashes($_GET[entsperren]).")', '".time()."')");
    		}
			if ($_GET[loeschen]) {
				sql_query("UPDATE userdata SET delacc=1 WHERE user='".addslashes($_GET[loeschen])."'");
				sql_query("INSERT INTO logs_support (supporter, action, action_value, timestamp)
				VALUES ('$_SESSION[supporter]', 'System', '<b>Löschen </b>(User ".addslashes($_GET[loeschen]).")', '".time()."')");
			}	
			if ($_GET[entloeschen]) {
				sql_query("UPDATE userdata SET delacc=0 WHERE user='".addslashes($_GET[entloeschen])."'");
				sql_query("INSERT INTO logs_support (supporter, action, action_value, timestamp)
				VALUES ('$_SESSION[supporter]', 'System', '<b>Login-Message </b>(User ".addslashes($_GET[entloeschen]).")', '".time()."')");
			}
	echo 	"<tr>
				<td><b>PC</b></td>
				<td><b>User</b></td>
			</tr>";
			
    $get_pc_ids_of_user = sql_query("SELECT pc_id FROM multi_sessions WHERE user='".addslashes($_GET[suspect_user])."' GROUP BY pc_id");
    while ($pc_ids_of_user = sql_fetch_array($get_pc_ids_of_user))
    {
	echo 	"<tr valign=top>
				<td>
					<a href=\"$PHP_SELF?ip=true&get=pc&pc_id=$pc_ids_of_user[pc_id]&suspect_user=$_GET[suspect_user]\">$pc_ids_of_user[pc_id]</a>
				</td>
				<td>";

        $get_user_of_pc_ids = sql_query("SELECT user,Count(*) AS anzahl FROM multi_sessions WHERE pc_id='$pc_ids_of_user[pc_id]' GROUP BY user");
        while ($user_of_pc_ids = sql_fetch_array($get_user_of_pc_ids))
        {
		echo 	"<table border=0 cellpadding=1 cellspacing=0>
					<tr valign=top>
						<td width=200>
							<a href=\"$PHP_SELF?suspect_user=$user_of_pc_ids[user]\">$user_of_pc_ids[user]</a> (<a href=\"$PHP_SELF?ip=true&get=user&suspect_user=$user_of_pc_ids[user]\">$user_of_pc_ids[anzahl]</a>)
						</td>
						<td width=600>";

            $get_multi_formular = sql_query("SELECT doppel_ip_user,reason FROM multi_angemeldete_doppel_ip WHERE user='$user_of_pc_ids[user]'");
            while ($multi_formular = sql_fetch_array($get_multi_formular))
            {
                echo "			$multi_formular[doppel_ip_user] => $multi_formular[reason]<br>";
            }

            echo 		"</td>
					</tr>
				</table>";
        }

        echo	"</td>
			</tr>";
    }

	// VERSUCH
	/*
	$get_ip_hashes_of_user = sql_query("SELECT ip FROM multi_sessions WHERE user='$_GET[suspect_user]' GROUP BY ip");
    while ($pc_ips_of_user = sql_fetch_array($get_ip_hashes_of_user))
    {
        $get_user_of_ips = sql_query("SELECT user,Count(*) AS anzahl FROM multi_sessions WHERE ip='$pc_ips_of_user[ip]' GROUP BY user");
        while ($user_of_ips = sql_fetch_array($get_user_of_ips))
        {
            echo "		<table border=0 cellpadding=1 cellspacing=0>
                        <tr valign=top>
                            <td width=200>
                                <a href=\"$PHP_SELF?suspect_user=$user_of_ips[user]\">$user_of_ips[user]</a> (<a href=\"$PHP_SELF?ip=true&get=user&suspect_user=$user_of_ips[user]\">$user_of_ips[anzahl]</a>)
                            </td>
                        </tr>
                        </table>";
        }

    }
	*/
	//

    echo "		</table><br><br><br>";


    $get_multidata = sql_query("SELECT * FROM multi_angemeldete WHERE user='".addslashes($_GET[suspect_user])."'");
    $multidata = sql_fetch_array($get_multidata);

    $get_userdata = sql_query("SELECT email, user_agent, holiday, multi, delacc FROM userdata WHERE user='".addslashes($_GET[suspect_user])."'");
    $userdata = sql_fetch_array($get_userdata);
    
    $get_usarios = sql_query("SELECT usarios.ID, usarios.sitter, usarios.points, usarios.tech_points, alliances.tag as alliance, usarios.alliance_status, usarios.sitter_confirmation FROM usarios INNER JOIN alliances ON usarios.alliance=alliances.ID WHERE usarios.user='".addslashes($_GET[suspect_user])."'");
    $usario = sql_fetch_array($get_usarios);
    
    $get_zu_sittender = sql_query("SELECT user,sitter_confirmation FROM usarios WHERE sitter='$usario[ID]'");
    $zu_sittender = sql_fetch_array($get_zu_sittender);

    $get_name_sitter = sql_query("SELECT user FROM userdata WHERE user='$usario[sitter]';");
    $name_sitter = sql_fetch_array($get_name_sitter);
    
    $QS = $_SERVER[QUERY_STRING];

    echo "	<table width=\"700\"border=0 cellpadding=0 cellspacing=0>
            <tr>
                <td width=30%>User:</td>
                <td><strong>$_GET[suspect_user] </strong> (<a href=\"$PHP_SELF?$QS&sperren=$_GET[suspect_user]\">sperren</a> / <a href=\"$PHP_SELF?$QS&entsperren=$_GET[suspect_user]\">entsperren</a> / <a href=\"$PHP_SELF?$QS&loeschen=$_GET[suspect_user]\">l&ouml;schen</a> / <a href=\"$PHP_SELF?$QS&entloeschen=$_GET[suspect_user]\">entl&ouml;schen</a>)</td>
            </tr>
			<tr>
                <td>Punkte</td>
                <td>".$usario[points]."</td>
            </tr>
			<tr>
                <td>Tech-Punkte</td>
                <td>".$usario[tech_points]."</td>
            </tr>
            <tr>
                <td>Allianz</td>
                <td>".$usario[alliance]."</td>
            </tr>
			<tr>
                <td>Alli-Status</td>
                <td>"; if (!empty($usario[alliance])) echo $usario[alliance_status]; echo "</td>
            </tr>
            <tr>
                <td>E-Mail</td>
                <td>".$userdata[email]."</td>
            </tr>
			<tr>
                <td>User-Agent</td>
                <td>".$userdata[user_agent]."</td>
            </tr>
            <tr>
                <td>U-Mode</td>
                <td>".($userdata[holiday]?"Ja":"Nein")."</td>
            </tr>
            <tr>
                <td>Gesperrt</td>
                <td>".($userdata[multi]=="N"?"Nein":"Ja")."</td>
            </tr>
            <tr>
                <td>Acc-L&ouml;schung</td>
                <td>".($userdata[delacc]?"Ja":"Nein")."</td>
            </tr>";
 /* Daten werden nicht mehr erhoben, daher irrelevant
			<tr>
                <td>Vorname, Name</td>
                <td>$multidata[vorname], $multidata[name]</td>
            </tr>
            <tr>
                <td>Stra&szlig;e</td>
                <td>$multidata[strasse]</td>
            </tr>
            <tr>
                <td>PLZ, Ort, Land</td>
                <td>$multidata[plz], $multidata[ort], $multidata[land]</td>
            </tr>
            <tr>
                <td>Telefon</td>
                <td>$multidata[tel]</td>
            </tr>
*/
            echo
			"<tr>
                <td>Kommentar</td>
                <td>$multidata[kommentar]</td>
            </tr>
            <tr>
                <td>no_double_ip</td>
                <td>".$multidata[no_double_ip]."</td>
            </tr>
            <tr>
            	<td>Sitter</td>
            	<td>"; if(!empty($usario[sitter])) { echo "$name_sitter[user] (Von Sitter bestätigt: $usario[sitter_confirmation])"; } echo "</td>
            </tr>
            <tr>
            	<td>Zu Sittender</td>
            	<td>"; if(!empty($zu_sittender)) { echo "$zu_sittender[user] (Von $_GET[suspect_user] bestätigt: $zu_sittender[sitter_confirmation])"; } echo "</td>
            </table><br><br><br>";



    if ($_GET[ip])
    {
        echo "	<table border=0 cellpadding=5 cellspacing=0>
                <tr>
                    <td colspan=2>
                        <table width=950 border=0 cellpadding=2 cellspacing=0>
                        <tr>
                            <td align=\"center\">
                                ". (($_GET[get] == "user") ? "<b><i>User</i></b>" : "<b>User</b>") ."
                            </td>
                            <td align=\"center\">
                                <b>IP</b>
                            </td>
                            <td align=\"center\">
                               ". (($_GET[get] == "pc") ? "<b><i>PC</i></b>" : "<b>PC</b>") ."
                            </td>
                            <td align=\"center\">
                                <b>Login</b>
                            </td>
                            <td align=\"center\">
                                <b>Logout</b>
                            </td>
							<td align=\"center\">
                                <b>Client</b>
                            </td>
                        </tr>";

        switch ($_GET[get])
        {
            case "user" : $query = "user='".addslashes($_GET[suspect_user])."'"; break;
            case "pc"	: $query = "pc_id='".addslashes($_GET[pc_id])."'"; break;
        }

	$ip_translate = array();
	$ip_ident = array('A','A');
        $get_single_ips = sql_query("SELECT DISTINCT multi_sessions.ip,multi_iphash.ip FROM multi_sessions JOIN multi_iphash ON(iphash=multi_sessions.ip) WHERE $query ORDER BY login_time DESC");
	while($row = sql_fetch_row($get_single_ips)) {
		$ip_translate[$row[0]] = str_replace('xxx',implode($ip_ident),$row[1]);
		$ip_ident[1]++;
		if($ip_ident[1]=='Z') { $ip_ident[0]++; $ip_ident[1]='A'; }
	}

	$color_1 = array('#666666', '#888888');
	$color_2 = array('#666666', '#888888');
	//$color_1 = array('#efefef', '#fff');
	//$color_2 = array('#ddd', '#fff');
	$last_ip = '';
	$last_day = '';
	$kk = 0;
	$ii = 0;
        $get_single_ips = sql_query("SELECT user,multi_sessions.ip,pc_id,login_time,logout_time,provider,client FROM multi_sessions JOIN multi_iphash ON(iphash=multi_sessions.ip) WHERE $query ORDER BY login_time DESC");
        while ($single_ips = sql_fetch_array($get_single_ips))
        {
            $i++;

            if ($i%2)
                $col = "#222222";
            else
                $col = "#444444";

	    if($single_ips[ip] != $last_ip) {
		$color = $color_1[$kk % 2];
		$kk++;
		$last_ip = $single_ips[ip];
	    }

	    if(date("d.m.Y",$single_ips[login_time]) != $last_day) {
		$color2 = $color_2[$ii % 2];
		$ii++;
		$last_day = date("d.m.Y",$single_ips[login_time]);
	    }

            echo "		<tr valgin=top bgcolor=$col>
                            <td>
                                ".str_replace(' ', '&nbsp;',str_pad($single_ips[user],17))."
                            </td>
                            <td style='background-color:$color'>
                                <span title='$single_ips[provider]'>".str_replace(' ', '&nbsp;',str_pad($ip_translate[$single_ips[ip]],15))."</span>
                            </td>
                            <td>
                                ".str_replace(' ', '&nbsp;',str_pad($single_ips[pc_id],10))."
                            </td>
                            <td style='background-color:$color2'>
                                &nbsp;&nbsp;". date("d.m. H:i:s",$single_ips[login_time]) ."
                            </td>
                            <td style='background-color:$color2'>
                                &nbsp;&nbsp;". (($single_ips[logout_time]) ? date("d.m. H:i:s",$single_ips[logout_time]) : "") ."
                            </td>
							<td align=right style='background-color:$color2'>
                                &nbsp;&nbsp;". (($single_ips[client]) ."") ."
                            </td>
                        </tr>";
        }
        echo "			</table>
                    </td>
                </tr>";
    }

    echo "		</table>
				</td>
				</tr>
				</table>
                </body>
                </html>";
				}
?>

