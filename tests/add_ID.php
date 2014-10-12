<?php
require_once("database.php");

echo "Hallo Welt.<br><br>";
// 1. Schritt: Userdata
 $select = sql_query("SELECT user FROM userdata;");
  $ID = 0;
  while($row = sql_fetch_array($select)) {
  		$ID++;
  		echo "Username: $row[user] ---> ID: $ID<br>";
  		sql_query("UPDATE userdata SET ID='$ID' WHERE user='$row[user]';");
 
 echo "$ID<br>";

// 2. Schritt: usarios
  $select = sql_query("SELECT ID,user FROM userdata");
	while($row = sql_fetch_array($select)) {
	echo "Username: $row[user] ---> ID: $row[ID]<br>";
	sql_query("UPDATE usarios SET ID='$row[ID]' WHERE user='$row[user]';");
}


// 3. Schritt: Sittername ersetzen durch SitterID

$select = sql_query("SELECT ID, user FROM userdata");
while($row = sql_fetch_array($select)) {
	sql_query("UPDATE usarios SET sitter = '$row[ID]' WHERE sitter='$row[user]';");
}


// 4. Schritt: Username in "city" ersetzen durch userID
$select = sql_query("SELECT ID, user FROM userdata");
while($row = sql_fetch_array($select)) {
	sql_query("UPDATE city SET user = '$row[ID]' WHERE user = '$row[user]';");
}

// 5. Schritt: "city" bekommt cityIDs
$select = sql_query("SELECT city FROM city");
$ID = 0;
while($row = sql_fetch_array($select)) {
	$ID++;
	echo "City: $row[city] ---> ID: $ID<br>";
	sql_query("UPDATE city SET ID='$ID' WHERE city='$row[city]';");
}

// 6. Schritt: AllianzID
/*$select = sql_query("SELECT tag FROM alliances");
$ID = 0;
while($row = sql_fetch_array($select)) {
	$ID++;
	echo "Allianz: $row[tag] ---> ID: $ID<br>";
	sql_query("UPDATE alliances SET ID='$ID' WHERE tag='$row[tag]'");
}

// 7. Schritt: "city" bekommt AllianceIDs
$select = sql_query("SELECT ID,tag FROM alliances");
while($row = sql_fetch_array($select)) {
	sql_query("UPDATE city SET alliance = '$row[ID]' WHERE alliance='$row[tag]'");
}
*/


?>