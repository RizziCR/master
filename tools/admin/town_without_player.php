<?php
    require_once("database.php");
    require_once("constants.php");
    require_once("functions.php");

########## Killing phantom towns
$select = "SELECT city,user FROM city;";
$select = sql_query($select);

while($row = sql_fetch_array($select)) {
	
	$sel = "SELECT user FROM usarios WHERE user='$row[user]' AND user!='Tutorial'";
	$sel = sql_query($sel);
	$sel = sql_fetch_array($sel);
	
	if($sel['user'] != $row['user']) {
		echo "User: $row[user] - Koords: $row[city] - Stadt gel&ouml;scht<br>";
		sql_query("DELETE FROM city WHERE user='$row[user]' AND city='$row[city]'");
	}
}



?>