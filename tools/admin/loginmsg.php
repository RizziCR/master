<?php
include_once("../session.php");
include_once("../htmlheader.php");
require_once("database.php");
if($_POST[submit] == "Absenden"){
if($_POST[msg_delete] == "yes"){
sql_query("DELETE FROM admin_login_msgs WHERE id = '". addslashes($_POST[msg_id])."'");
// Eintrag in Supporter-Log
sql_query("INSERT INTO logs_support (supporter, action, action_value, timestamp)
			VALUES ('$_SESSION[supporter]', 'System', '<b>Login-Message </b>(id = ". addslashes($_POST[msg_id]).") gel&ouml;scht', '".time()."')");
// Ende Eintrag
}
sql_query("UPDATE admin_login_msgs SET toshow = '". addslashes($_POST[toshow]) ."' WHERE id = '". addslashes($_POST[msg_id])."'");
// Eintrag in Supporter-Log
if($_POST[show_old] != $_POST[toshow] && $_POST[msg_delete] != "yes"){
if($_POST[toshow] == "Y") $info = "aktiviert"; else $info = "deaktiviert";
sql_query("INSERT INTO logs_support (supporter, action, action_value, timestamp)
			VALUES ('$_SESSION[supporter]', 'System', '<b>Login-Message </b>(id = ". addslashes($_POST[msg_id]).") ". $info ."</b>', '".time()."')");
// Ende Eintrag
}
}
  /*** Neue Meldung hinzufügen für Login-Seite ***/
echo 
"<h2>Login-Nachrichten verwalten</h2>
<table width=\"80%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
	<tr>
		<td>
		<form action=\"{$_SERVER[PHP_SELF]}\" method=post>
		<textarea name=text cols=50 rows=10></textarea>
		<select name=color>
			<option value=\"#FF0000\">rot</option>
			<option value=\"#00FF00\" selected>gr&uuml;n</option>
			<option value=\"#FFFF00\">gelb</option>
		</select>
		<input type=submit value=Senden>
		</form>
		</td>
	</tr>
	<tr>
		<td>
			<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
				<tr>
					<th width=\"10%\" align=left>Datum</th>
					<th width=\"5%\" align=center>ID</th>
					<th width=\"40%\" align=left>Nachricht</th>
					<th width=\"15%\" align=center>anzeigen ?</th>
					<th width=\"10%\" align=center>l&ouml;schen ?</th>
					<th  width=\"20%\" align=center></th>
				</tr>";
    $get_admin_login_msgs = sql_query("SELECT * FROM admin_login_msgs ORDER BY time DESC");
    while ($admin_login_msgs =sql_fetch_array($get_admin_login_msgs)){
				if($admin_login_msgs[toshow] == "Y") $show = "checked"; else $not_show = "checked";
echo			"<form name=\"edit_login_msg\" action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">
				<input type=\"hidden\" name=\"msg_id\" value=\"".$admin_login_msgs[id]."\">
				<input type=\"hidden\" name=\"show_old\" value=\"".$admin_login_msgs[toshow]."\">
				<tr>
					<td align=left style=\"color:$admin_login_msgs[color]\">". date("d.m.Y",$admin_login_msgs[time]) ."</td>
					<td align=center style=\"color:$admin_login_msgs[color]\">". $admin_login_msgs[id] ."</td>
					<td align=left style=\"color:$admin_login_msgs[color]\">$admin_login_msgs[text]</td>
					<td align=center><input type=\"radio\" name=\"toshow\" value=\"Y\" ".$show."> Ja<input type=\"radio\" name=\"toshow\" value=\"N\" ".$not_show."> Nein</td>
					<td align=center><input type=\"checkbox\" name=\"msg_delete\" value=\"yes\"></td>
					<td align=center><input type=\"submit\" name=\"submit\" value=\"Absenden\"></td>
				</tr>
				</form>";
		unset($show);
		unset($not_show);
		}
echo		"</table>
		</td>
	</tr>
</table>";


  if ($_POST[text]){
    sql_query("INSERT INTO admin_login_msgs (time,color,text) VALUES ('". time() ."','". addslashes($_POST[color]) . "','" . addslashes($_POST[text]) . "')");
	// Eintrag in Support-Log
	switch($_POST[color]){
		case #FF0000:
			$color = "rot";
			break;
		case #00FF00:
			$color = "gr&uuml;n";
			break;
		case #FFFF00:
			$color = "gelb";
			break;
	
	
	}
	// Eintrag in Support-Log
	sql_query("INSERT INTO logs_support (supporter, action, action_value, timestamp)
			VALUES ('$_SESSION[supporter]', 'System', '<b>Login-Nachricht in <font color=\"".addslashes($_POST[color])."\">".$color."</font> erstellt</b>:<br>".addslashes($_POST[text])."', '".time()."')");
	// Ende Eintrag
	}
	echo
"</body>
</html>";
?>
