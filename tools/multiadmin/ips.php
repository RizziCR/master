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
			<h2>Diese Spieler haben jeweils den selben IPHash:</h2>
			<table border=1>
			<tr>
			<td>
				<table width='100%'>
				<tr>
					<th align='left' width='300'>IP-Hash</th>
					<th align='right' width='300'>Anzahl</th>
					<th align='right' width='300'></th>
					<th align='center' width='100'></th>
				</tr>";
    $ip_hashes = sql_query("SELECT ipcount(ip+user) AS ips FROM multi_sessions GROUP BY ip, user HAVING ips > 1 ORDER BY ips DESC");
			while($ips = sql_fetch_array($ip_hashes)){
			echo "<tr>
					<td>".$ips[ip]."</td>
					<td>".$ips[ips]."</td>
					<td>".$ips."</td>
					<td>".$ips."</td>
				</tr>";
			}
		echo "</table>
			</td>
			</tr>
			</table>
	</body>
	</html>";
?> 