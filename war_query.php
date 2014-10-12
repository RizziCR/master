<?php

    require_once("database.php");
    require_once("do_loop.php");
    require_once("class_Krieg.php");
    
    list( $user, $alliance ) = sql_fetch_row(sql_query("SELECT user,alliance FROM city WHERE city='".addslashes($_GET[city])."'"));
    list( $myalliance ) = sql_fetch_row(sql_query("SELECT alliance FROM usarios WHERE user='".$_SESSION[user]."'"));
    $holiday = sql_fetch_array(sql_query("SELECT holiday FROM userdata INNER JOIN city ON userdata.ID=city.user WHERE city='".addslashes($_GET[city])."'"));
    
    $krieg = new Krieg($alliance);
    if( $krieg->inWar() && !$krieg->isAlly($myalliance) && !$krieg->isOpponent($myalliance) ) {
        echo "<span style='color:#f00'>Achtung: Diese Stadt ist Kriegsgebiet!<br></span>";
    }
    if( $holiday['holiday'] > 0 ) {
        echo "<span style='color:#00f'>Achtung: Der Besitzer dieser Stadt ist im Urlaub!<br></span>";
    }
    $origin_user = $_SESSION['user'];
    $target_user = $user;
    if($origin_user != $target_user) {
	    require_once 'include/userprotect.php';
	    if($bed == 1) {
	    	echo "<span style='color:#f00'>Achtung: Spielerschutz greift!<br></span>";
	    }
    }
   
    
?>