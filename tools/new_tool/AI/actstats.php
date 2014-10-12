<?php

// Generate activity stats
// Loaded by optimize.php

	$get_users = sql_fetch_array(sql_query("SELECT COUNT(*) AS sum FROM usarios"));

    $login_now = sql_fetch_array(sql_query("SELECT Count(logged_in) AS online FROM usarios WHERE logged_in='YES'"));
    $login_last_std = sql_fetch_array(sql_query("SELECT Count(logged_in) AS online FROM usarios WHERE last_action>UNIX_TIMESTAMP()-3600"));
    $login_last_day = sql_fetch_array(sql_query("SELECT Count(logged_in) AS online FROM usarios WHERE last_action>UNIX_TIMESTAMP()-86400"));
    
	$time = time();
	
	sql_query("INSERT INTO activity_stats (time, on_now, on_lasthour, on_lastday, total_accounts) VALUES ('$time', '$login_now[online]', '$login_last_std[online]', '$login_last_day[online]', '$get_users[sum]');");
	


?>