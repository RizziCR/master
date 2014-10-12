<?php

// *** local values ******************************************************************************
$dbServer     = "localhost";
$dbLogin      = "ETS";
$dbPwd        = "Liabatyd";
$dbName       = "etsclassic";


if (!isset($db))
{
	$db = mysql_connect($dbServer, $dbLogin, $dbPwd);
	if (!$db)
		die ("<<font color=\"#FF0000\">Fehler beim Konnektieren der Datenbank! Bitte versuchen Sie es in ein paar Minuten erneut.</font>><br><br>");
}

mysql_select_db($dbName,$db);


?>