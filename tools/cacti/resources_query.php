#! /usr/bin/php
<?php
    require_once("database.php");
    
    $result = sql_query("SELECT * FROM global");
    $res = sql_fetch_assoc($result);

/*
    [iridium] => 2052389716
    [holzium] => 545220513
    [water] => 1966186258
    [oxygen] => 339861478
*/

    echo time().':'.$res[iridium].':'.$res[holzium].':'.$res[water].':'.$res[oxygen];

?>