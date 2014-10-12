<?php
include_once("../session.php");
include_once("../htmlheader.php");
require_once("database.php");
require_once("functions.php");
	
    function printFleet($fleet) {
		$zeit = strftime('%d.%m.%y %H:%M', $fleet['time']);
		$a = explode("\n", $fleet['msg']);
		$text = '';
		foreach($a as $b) {
		    list($i, $k, $v) = explode('|', $b);
		    if($i == 'headline') $subject = $k;
		    else $text .= ($k.':'.$v.'<br/>');
		}
		$user = sql_fetch_array(sql_query("SELECT user FROM userdata WHERE ID='$fleet[user]';"));
		$origin = sql_fetch_array(sql_query("SELECT city AS origin FROM city WHERE ID='$fleet[origin]';"));
		$target = sql_fetch_array(sql_query("SELECT city AS target FROM city WHERE ID='$fleet[target]';"));
		echo "<tr><td>$user[user]</td><td>$zeit</td><td>$origin[origin]</td><td>$target[target]</td><td>$subject</td><td>$text</td></tr>";
    }

    function printNewsFleet($fleet) {
		$zeit = strftime('%d.%m.%y %H:%M', $fleet['time']);
		$subject2 = "";
		$user = sql_fetch_array(sql_query("SELECT user FROM userdata WHERE ID='$fleet[user]';"));
		$origin = sql_fetch_array(sql_query("SELECT city AS origin FROM city WHERE ID='$fleet[origin]';"));
		$target = sql_fetch_array(sql_query("SELECT city AS target FROM city WHERE ID='$fleet[target]';"));
		$select = sql_query("SELECT * FROM news_ber_ INNER JOIN type_plane ON type_plane.type = news_ber_.type WHERE news_ber_.ID = '$fleet[id]'");
		while($row = sql_fetch_array($select)) {
			$text2 .= "$row[name]  $row[before]<br>";
		}
		$text = "Iridium: $fleet[iridium]<br>
					Holzium: $fleet[holzium]<br>
					Wasser: $fleet[water]<br>
					Sauerstoff: $fleet[oxygen]<br>
					$text2";
		
		
		echo "<tr><td><a href='http://escape-to-space.de/messages_berichte.php?bid=$fleet[attack_bid]' target=_blank>Bericht anzeigen</a></td><td>$user[user]</td><td>$zeit</td><td>$origin[origin]</td><td>$target[target]</td><td>$text</td></tr>";
    }

    
    
	echo "<h3>Flotten des Spielers ".$_GET[nutzer].":</h3>
	<table align=\"left\" cellspacing=\"0\" cellpadding=\"0\">
	<tr>
	<td>Benutzernamen eingeben:<br>
	<form action=\"fleets.php\" method=\"post\"><input type=\"text\" size=\"15\" name=\"nutzer\" value=".$_POST[nutzer]."><input type=\"submit\" value=\"Suchen\" /></form></td></tr>";

    if(empty($_POST[nutzer]) && empty($_GET[nutzer])) {
	echo "<tr><td><b>Bitte einen Benutzernamen eingeben!</b></td></tr>";
	exit;
    } ELSE {
    if(!empty($_POST[nutzer])) {
	echo
	"<tr><td><table align=\"left\" cellspacing=\"0\" cellpadding=\"0\">
	<tr>
	<td><b>Benutzer mit der Buchstabenfolge \"".$_POST[nutzer]."\"</b>:</td>
	</tr>";
	$names = sql_query("SELECT user FROM usarios WHERE user LIKE '%$_POST[nutzer]%'");
	while($name = sql_fetch_array($names)) {
	echo "<tr>
	<td><a href=\"fleets.php?nutzer=".$name[user]."\">".$name[user]."</a></td>
	</tr>";
	}
	echo "</table></td></tr>";
    }
	$nutzer = $_GET[nutzer];
	$nutz = sql_fetch_array(sql_query("SELECT ID,user FROM userdata WHERE user = '".addslashes($nutzer)."'"));
	$nutzer = $nutz['ID'];
 
	print_r($nutz);

if($nutzer != "") {	
	
echo "<tr>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td>
    <table border='1' cellspacing='0' cellpadding='0'>
	<tr>
	    <th align='left' width='100'>Benutzer</th>
	    <th align='left' width='100'>Ankunft</th>
	    <th align='left' width='100'>Von</th>
	    <th align='left' width='100'>Nach</th>
	    <th align='left'>Inhalt</th>
	</tr>

	<tr>
	    <td colspan='6'><h3>Alle gerade fliegenden Transporte des Benutzers $nutz[user], aber nicht zu eigenen St&auml;dten:</h3></td>
	</tr>";

$fleets = sql_query("SELECT * FROM actions WHERE f_action='transport' AND user='".addslashes($nutzer)."' AND f_target_user<>'".addslashes($nutzer)."'");
while($fleet = sql_fetch_assoc($fleets)) {
	printFleet($fleet);
}
?>

	<tr>
	    <td colspan="6"><h3>Alle gerade fliegenden Transporte zum Benutzer <?php echo $nutz[user]; ?>, aber nicht von eigenen St&auml;dten:</h3></td>
	</tr>

<?php
$fleets = sql_query("SELECT * FROM actions WHERE f_action='transport' AND user<>'".addslashes($nutzer)."' AND f_target_user='".addslashes($nutzer)."'");
while($fleet = sql_fetch_assoc($fleets)) {
	printFleet($fleet);
}

?>

	<tr>
	    <td colspan="6"><h3>Alle gerade fliegenden Transporte von und zum Benutzer <?php echo $nutz[user]; ?>, aber nicht von/zu eigenen St&auml;dten:</h3></td>
	</tr>

<?php
$fleets = sql_query("SELECT * FROM actions WHERE f_action='transport' AND (user='".addslashes($nutzer)."' XOR f_target_user='".addslashes($nutzer)."'");
while($fleet = sql_fetch_assoc($fleets)) {
	printFleet($fleet);
}

?>

	<tr>
	    <td colspan="6"><h3>Alle beendeten Transporte der letzten Tage des Benutzers <?php echo $nutz[user]; ?>, aber nicht zu eigenen St&auml;dten:</h3></td>
	</tr>

<?php
$fleets = sql_query("SELECT id, time, defense_user AS user, attack_city AS origin, defense_city AS target, art, f_name, iridium, holzium, water, oxygen FROM news_ber WHERE attack_user = '$nutzer' AND attack_user <> defense_user AND art = 'transport'");
#$fleets = sql_query(sprintf('SELECT N.* FROM news_ber N JOIN city C1 ON (N.target=C1.city) JOIN city C2 ON (N.origin=C2.city) '.
#	'WHERE C1.user<>C2.user AND C2.user="%1$s" AND N.msg REGEXP "^.*%1$s.*berbrachte.*$"', $nutzer));
while($fleet = sql_fetch_assoc($fleets)) {
	printNewsFleet($fleet);
}

?>

	<tr>
	    <td colspan="6"><h3>Alle beendeten Transporte der letzten Tage zum Benutzer <?php echo $nutz[user]; ?>, aber nicht von eigenen St&auml;dten:</h3></td>
	</tr>

<?php
$fleets = "SELECT id, attack_bid, time, attack_user AS user, attack_city AS origin, defense_city AS target, art, f_name, iridium, holzium, water, oxygen FROM news_ber WHERE defense_user = '$nutzer' AND attack_user <> defense_user AND art = 'transport'";
echo $fleets;
$fleets = sql_query($fleets);
while($fleet = sql_fetch_assoc($fleets)) {
	printNewsFleet($fleet);
}

?>
	<tr>
	    <td colspan="6"><h3>Alle beendeten Angriffe des Benutzers <?php echo $nutz[user]; ?> auf andere Siedler:</h3></td>
	</tr>

<?php
$fleets = sql_query("SELECT id, attack_bid, time, defense_user AS user, attack_city AS origin, defense_city AS target, art, f_name, iridium, holzium, water, oxygen FROM news_ber WHERE attack_user = '$nutzer' AND attack_user <> defense_user AND art = 'attack'");
#$fleets = sql_query(sprintf('SELECT * FROM news_ber WHERE user="%1$s" AND msg REGEXP "^.*Angreifer[|][0-9:]*[|]%1$s.*$" AND NOT (msg REGEXP "^.*Verteidiger[|][0-9:]*[|]%1$s.*$")', $nutzer));
while($fleet = sql_fetch_assoc($fleets)) {
	printNewsFleet($fleet);
}
}
}
?>
</body>
</html>