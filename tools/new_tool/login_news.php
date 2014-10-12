<?php

session_start();

// Include des DB Zugriffs (config.php) und des HTML Begins (head, start body -> html_head.php);
include("config.php");
include("html_head.php");

if(!$_SESSION[tool_user]) {
	echo "Bitte zuerst neu einloggen.";
}else{
	if($_POST[submit] == "Absenden"){
		
		
		$what_to_do = explode("-", $_POST[toshow]);
		echo "<pre>";
		print_r($what_to_do);
		echo "</pre>";
		if($_POST[msg_delete] == "yes"){
			mysql_query("DELETE FROM admin_login_msgs WHERE id = '". mysql_real_escape_string($_POST[msg_id])."'");
			// Eintrag in Supporter-Log
			mysql_query("INSERT INTO logs_support (supporter, action, action_value, timestamp)
			VALUES ('$_SESSION[tool_user]', 'System', '<b>Login-Message </b>(id = ". mysql_real_escape_string($_POST[msg_id]).") gel&ouml;scht', '".time()."')");
			// Ende Eintrag
		}
		mysql_query("UPDATE admin_login_msgs SET toshow = '". mysql_real_escape_string($_POST[toshow]) ."' WHERE id = '". mysql_real_escape_string($_POST[msg_id])."'");
		// Eintrag in Supporter-Log
		if($_POST[show_old] != $_POST[toshow] && $_POST[msg_delete] != "yes"){
			if($_POST[toshow] == "Y") 
				$info = "aktiviert"; 
			else 
				$info = "deaktiviert";
			mysql_query("INSERT INTO logs_support (supporter, action, action_value, timestamp)
			VALUES ('$_SESSION[tool_user]', 'System', '<b>Login-Message </b>(id = ". mysql_real_escape_string($_POST[msg_id]).") ". $info ."</b>', '".time()."')");
			// Ende Eintrag
		}
		
		
	}
	
	
	if ($_POST[text]){
		mysql_query("INSERT INTO admin_login_msgs (time,color,text) VALUES ('". time() ."','". mysql_real_escape_string($_POST[color]) . "','" . mysql_real_escape_string($_POST[text]) . "')");
		// Eintrag in Support-Log
		switch($_POST[color]){
	
			case "#FF0000":
				$color = "rot";
				break;
				
			case "#00FF00":
				$color = "grün";
				break;
						
			case "#FFFF00":
				$color = "gelb";
				break;
	
		}
		
		// Eintrag in Support-Log
		mysql_query("INSERT INTO logs_support (supporter, action, action_value, timestamp)
		VALUES ('$_SESSION[tool_user]', 'System', '<b>Login-Nachricht in <font color='".mysql_real_escape_string($_POST[color])."'>".$color."</font> erstellt</b>:<br>".mysql_real_escape_string($_POST[text])."', '".time()."')");
		// Ende Eintrag
		
	}
	
	echo "<h2>Login-Nachrichten verwalten</h2>
	<div align='center'>
		<table border='0' cellspacing='0' cellpadding='0'>
			<tr>
				<td>
					<form action='login_news.php' method='post'>
						<textarea name=text cols=50 rows=10></textarea>
						<select name=color>
							<option value='#00FF00' style='color:green' selected>gr&uuml;n</option>
							<option value='#FFFF00' style='color:yellow'>gelb</option>
							<option value='#FF0000' style='color:red'>rot</option>
						</select>
						<input type='submit' value='Senden'>
					</form>
				</td>
			</tr>
		</table>
	</div>
	<form action='{$_SERVER[PHP_SELF]}' method=post>
		<table border='0'>
			<tr>
				<td>
					<table width='100%' border='0' cellspacing='0' cellpadding='0'>
						<tr>
							<th width='10%' align=left>Datum</th>
							<th width='5%' align=center>ID</th>
							<th width='40%' align=left>Nachricht</th>
							<th width='15%' align=center>anzeigen ?</th>
							<th width='10%' align=center>l&ouml;schen ?</th>
							<th  width='20%' align=center></th>
						</tr>";
	
	$get_admin_login_msgs = mysql_query("SELECT * FROM admin_login_msgs ORDER BY time DESC");
	while ($admin_login_msgs = mysql_fetch_array($get_admin_login_msgs)){
		
			if($admin_login_msgs[toshow] == "Y") 
					$show = "checked='checked'"; 
			else 
					$not_show = "checked='checked'";
			
			echo "<tr>
						<td align=left style='color:$admin_login_msgs[color]'>". date("d.m.Y",$admin_login_msgs[time]) ."</td>
						<td align=center style='color:$admin_login_msgs[color]'>". $admin_login_msgs[id] ."</td>
						<td align=left style='color:$admin_login_msgs[color]'>$admin_login_msgs[text]</td>
						<td align=center>
								<input type='radio' name='toshow-$admin_login_msgs[id]' value='Y-$admin_login_msgs[id]' ".$show."> Ja
								<input type='radio' name='toshow-$admin_login_msgs[id]' value='N-$admin_login_msgs[id]' ".$not_show."> Nein
						</td><td>
								<input type='checkbox' name='msg_delete[]' value='yes'>
						</td>
					</tr>";
	}
	
	echo		"<tr>
					<td colspan='5'>
						<input type='submit' value='Absenden'>
					</td>
				</tr>
			</table>
			</td>
		</tr>
		</table>
	</form>";
	
	include("html_end.php");
}
?>