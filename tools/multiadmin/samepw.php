<?php
include_once("../session.php");
    require_once("database.php");
    require_once("functions.php");

	echo 	"<html>
			<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\">
			<head>
			<title>Identische Passw&ouml;rter</title>
			<link rel=\"stylesheet\" type=\"text/css\" href=\"../css.css\">
			</head>
			<body>
			<h2>Diese Spieler haben jeweils das selbe Passwort:</h2>
			<table border=1>
			<tr>
			<td>
				<table width='100%'>
				<tr>
					<th align='left' width='300'>Username (Punkte)</th>
					<th align='right' width='300'>Email</th>
					<th align='right' width='300'>letzter IP-Hash</th>
					<th align='center' width='100'>gesperrt?</th>
					<th align='center' width='100'>Multi?</th>
					<th align='center' width='100'>Urlaub?</th>
					<th align='center' width='100'>L&ouml;schung?</th>
				</tr>
				</table>
			</td>
			</tr>";

    $ip_translate = array();
    $ip_ident = array('A','A');
    $hashes = array();
    $pwds = sql_query("SELECT password, count(password) AS cp FROM userdata GROUP BY password HAVING cp > 1 ORDER BY cp DESC");
    while($row = sql_fetch_row($pwds)) {
	echo "<tr><td><table width='100%'>";
	$users = sql_query("SELECT * FROM userdata WHERE password = '".$row[0]."' ORDER BY SOUNDEX(email)");
	while($user = sql_fetch_assoc($users)) {
	    $get_single_ips = sql_query("SELECT DISTINCT multi_sessions.ip,multi_iphash.ip FROM multi_sessions JOIN multi_iphash ON(iphash=multi_sessions.ip) WHERE user='$user[user]' ORDER BY login_time DESC LIMIT 1");
	    while($row = sql_fetch_row($get_single_ips)) {
		if(!isset($ip_translate[$row[0]])) {
		    $ip_translate[$row[0]] = str_replace('xxx',implode($ip_ident),$row[1]);
		    $ip_ident[1]++;
		    if($ip_ident[1]=='Z') { $ip_ident[0]++; $ip_ident[1]='A'; }
		}
	    }
	    list( $ip ) = sql_fetch_row( sql_query("SELECT ip FROM multi_sessions WHERE user='$user[user]' ORDER BY login_time DESC LIMIT 1") );
	    list( $points) = sql_fetch_row( sql_query('SELECT points FROM usarios WHERE user="'.addslashes($user[user]).'"'));
	    echo "<tr>".
		"<td align='left' width='300'><a href='index.php?suspect_user=$user[user]'>".$user[user]."</a> (".$points.") (<a href='fleets.php?nutzer=".$user[user]."'>F</a>)</td>".
		"<td align='right' width='300'>".$user[email]."</td>".
		"<td align='right' width='300' style='".(in_array($ip, $hashes)?'color:red':'')."'>".(!empty($ip_translate[$ip])?$ip_translate[$ip]:$ip)."</td>".
		"<td align='center' width='100'>".($user[block_time]>0?'ja':'nein')."</td>".
		"<td align='center' width='100'>".($user[multi]=='Y'?'ja':'nein')."</td>".
		"<td align='center' width='100'>".($user[holiday]>0?'ja':'nein')."</td>".
		"<td align='center' width='100'>".($user[delacc]>0?'ja':'nein')."</td>".
		"</tr>\n";
	    $hashes[] = $ip;
	}
	echo "</table></td></tr>";
    }
    echo "</table>
	</body>
	</html>";
?>
