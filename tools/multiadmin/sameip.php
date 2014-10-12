<?php
include_once("../session.php");
    require_once("database.php");
    require_once("functions.php");
    echo 	"<html>
			<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\">
			<head>
			<title>Identische IPs</title>
			<link rel=\"stylesheet\" type=\"text/css\" href=\"../css.css\">
			</head>
			<body>
			<h2>Diese Spieler haben jeweils die gleiche IP:</h2>
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
#    sql_query('CREATE TEMPORARY TABLE lastip (user varchar(20),ip varchar(32),logout_time int)');
#    sql_query('INSERT INTO lastip SELECT user, ip, max(logout_time) FROM multi_sessions GROUP BY user,ip ORDER BY max(logout_time) DESC');
#    $pwds = sql_query('SELECT ip, count(distinct(user)) AS ci FROM lastip GROUP BY ip HAVING ci>1 ORDER BY ci DESC');
    $pwds = sql_query("SELECT ip, COUNT(ip) AS ci FROM userdata WHERE ip!='123' AND ip!='' GROUP BY ip HAVING ci > 1 ORDER BY ci DESC");
    while($row = sql_fetch_row($pwds)) {
	echo "<tr><td><table width='100%'>";
	$users = sql_query("SELECT * FROM userdata WHERE ip = '".$row[0]."'");
	while($user = sql_fetch_assoc($users)) {
	    $get_single_ips = sql_query("SELECT iphash,ip FROM multi_iphash WHERE iphash='$user[ip]'");
	    while($row2 = sql_fetch_row($get_single_ips)) {
		if(!isset($ip_translate[$row2[0]])) {
		    $ip_translate[$row2[0]] = str_replace('xxx',implode($ip_ident),$row2[1]);
		    $ip_ident[1]++;
		    if($ip_ident[1]=='Z') { $ip_ident[0]++; $ip_ident[1]='A'; }
		}
	    }
	    $ip = $user[ip];
	    list( $points) = sql_fetch_row( sql_query('SELECT points FROM usarios WHERE user="'.addslashes($user[user]).'"'));
	    echo "<tr>".
		"<td align='left' width='300'><a href='./index.php?suspect_user=$user[user]'>".$user[user]."</a> (".$points.") (<a href='fleets.php?nutzer=".$user[user]."'>F</a>)</td>".
		"<td align='right' width='300'>".$user[email]."</td>".
		"<td align='right' width='300'>".(!empty($ip_translate[$ip])?$ip_translate[$ip]:$ip)."</td>".
		"<td align='center' width='100'>".($user[block_time]>0?'ja':'nein')."</td>".
		"<td align='center' width='100'>".($user[multi]=='Y'?'ja':'nein')."</td>".
		"<td align='center' width='100'>".($user[holiday]>0?'ja':'nein')."</td>".
		"<td align='center' width='100'>".($user[delacc]>0?'ja':'nein')."</td>".
		"</tr>\n";
	}
	echo "</table></td></tr>\n";
    }
    echo "</table>
	</body>
	</html>";

?>
