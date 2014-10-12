<?php
include_once("session.php");
include_once("../database.php");
echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\">
<html>
<head>
<meta http-equiv=\"refresh\" content=\"60\">
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
<link rel=\"stylesheet\" type=\"text/css\" href=\"css.css\">
</head>
<body>";
// Letzte Support-Aktionen:
$last_action = "<td>Letzte Aktion durch Supporter ";
$time_diff = time()-300;
$last_support_action = sql_query("SELECT * FROM logs_support ORDER BY timestamp DESC LIMIT 1");
$actions = sql_fetch_array($last_support_action);
$time_diff_min = floor((time()-$actions[timestamp])/60);
if($actions[action] == "System"){
$supporter_action = explode(" ", $actions[action_value]);
$supporter_action = substr($supporter_action[0], 3);
$last_action .= "<b>".$actions[supporter]."</b>: &Auml;nderung am System (".$supporter_action.")";
} else $last_action .= "<b>".$actions[supporter]."</b>: Benutzer &quot;<b>".$actions[target_user]."</b>&quot; (".$actions[action].")";
if($actions[timestamp] >= $time_diff) $last_action .= " <span style=\"color: red; font-weight: bold;\"> vor ".$time_diff_min." Minuten</span>";
echo $last_action;

// Neue IGM gemeldet ?
$agb_delict = sql_query("SELECT COUNT(*) FROM admin_agb_delict WHERE done = 'N'");
$delicts = sql_fetch_array($agb_delict);
if($delicts[0] == 1) echo "<br>Es wurde <b>1</b> neue IGM als Versto&szlig; gemeldet. Klicke <a href=\"support/admin.php\" target=\"Inhalt\">hier</a> zum Ansehen";
if($delicts[0] > 1) echo "<br>Es wurden <b>".$delicts[0]."</b> neue IGMs als Versto&szlig; gemeldet. Klicke <a href=\"support/admin.php\" target=\"Inhalt\">hier</a> zum Ansehen";
echo "</body>
</html>";
?>