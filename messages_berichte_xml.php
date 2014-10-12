<?php
error_reporting(E_ALL);
  // $use_lib = ?; // MSG_ADMINISTRATION
header('Content-Type: text/xml; charset=utf-8');
require_once("msgs.php");
require_once("database.php");
require_once("constants.php");
require_once("functions.php");

$collect_messages2 = "SELECT
					  			*
					  		FROM
					  			news_ber
					  		WHERE
					  			(attack_bid='". addslashes(htmlspecialchars($_GET[bid],ENT_QUOTES)) ."' AND
					  			attack_xmlid = '".addslashes(htmlspecialchars($_GET[xmlid],ENT_QUOTES))."') OR
					  			(defense_bid='". addslashes(htmlspecialchars($_GET[bid],ENT_QUOTES)) ."' AND
					  			defense_xmlid = '".addslashes(htmlspecialchars($_GET[xmlid],ENT_QUOTES))."')
					  			";

$collect_messages = sql_query($collect_messages2);

$row = sql_fetch_assoc($collect_messages);
if(!$row) {
	die('<error>Diesen Bericht gibt es nicht!</error>');
}

$defense = sql_fetch_array ( sql_query("SELECT city FROM city WHERE ID='$row[defense_city]'") );
$att = sql_fetch_array( sql_query("SELECT city FROM city WHERE ID='$row[attack_city]'") );

$row['defense_city'] = $defense['city'];
$row['attack_city'] = $att['city'];

$aUser = sql_fetch_array ( sql_query("SELECT user FROM userdata WHERE ID='$row[attack_user]'" ) );
$fUser = sql_fetch_array ( sql_query("SELECT user FROM userdata WHERE ID='$row[defense_user]'") );

$row['attack_user'] = $aUser['user'];
$row['defense_user'] = $fUser['user'];

$inhalt = strip_tags ($row['f_name']);
$inhalt = htmlentities ($inhalt);

if($row['art'] == "attack") $type = "Attack";
if($row['art'] == "scan") $type = "Spy";

if($row['attack_bid'] == $_GET['bid']) {
	echo "<report bid='$row[attack_bid]' type='$type'>";
	$title = "Eine Flotte nach $row[defense_city] ($row[defense_user]) erreichte Ihr Ziel";
}else{
	echo "<report bid='$row[defense_bid]' type='$type'>";
	$title = "Eine Flotte von $row[attack_city] ($row[attack_user]) erreichte Ihre Stadt";
}	
	
$capital_attack = sql_query("SELECT home FROM city WHERE city='$row[attack_city]'");
$capital_attack = sql_fetch_array($capital_attack);
if($capital_attack[home] == "YES") $capital_attack = "true";
else $capital_attack = "false";

$capital_defense = sql_query("SELECT home FROM city WHERE city='$row[defense_city]'");
$capital_defense = sql_query($capital_defense);
if($capital_defense[home] == "YES") $capital_defense = "true";
else $capital_defense = "false";

$zeit = date("Y-m-d H:i:s",$row['time']);
$attackers_alliance = sql_fetch_array(sql_query("SELECT tag FROM alliances WHERE ID='$row[attackers_alliance]'"));
$defenders_alliance = sql_fetch_array(sql_query("SELECT tag FROM alliances WHERE ID='$row[defenders_alliance]'"));

echo "<origin name='$row[attack_user]' coordinates='$row[attack_city]' alliance='$attackers_alliance[tag]' capital='$capital_attack' />
	<destination name='$row[defense_user]' coordinates='$row[defense_city]' alliance='$defenders_alliance[tag]' capital='$capital_defense' />
	<subject>$title</subject>";
if($row['f_name_show'] == "Y") {
	echo "<fleetname>$inhalt</fleetname>";
}else{
	echo "<fleetname />";
}
	echo "<time unix='$row[time]'>$zeit</time>
	<attacker>";

$attacker = sql_query("SELECT * FROM news_ber_ INNER JOIN type_plane ON news_ber_.type = type_plane.type WHERE news_ber_.ID = '$row[id]' AND ad='attack'");
while($roww = sql_fetch_array($attacker)) {
	echo "<unit type='$roww[name]' sent='$roww[before]' lost='$roww[after]' />";
}
echo "</attacker>
	<defender points='$row[points]'>";

$defender = sql_query("SELECT * FROM news_ber_ INNER JOIN type_plane ON news_ber_.type= type_plane.type WHERE news_ber_.ID = '$row[id]' AND ad='defense'");
while($roww = sql_fetch_array($defender)) {
	echo "<unit type='$roww[name]' sent='$roww[before]' lost='$roww[after]' />";
}
if($row['iridium'] == "") $row['iridium'] = 0;
if($row['holzium'] == "") $row['holzium'] = 0;
if($row['water'] == "") $row['water'] = 0;
if($row['oxygen'] == "") $row['oxygen'] = 0;
echo "</defender>
	<transport>
		<ressource type='Iridium' amount='$row[iridium]' />
		<ressource type='Holzium' amount='$row[holzium]' />
		<ressource type='Wasser' amount='$row[water]' />
		<ressource type='Sauerstoff' amount='$row[oxygen]' />
	</transport>
</report>";