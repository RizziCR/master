<?php
include_once("../admsession.php");
include_once("../../database.php");

//isset($_POST[supporter]) && isset($_POST[is_active]) && isset($_POST[newaccess]) &&
if($_POST[change] == " Aktualisieren " && $_POST[newaccess] >= $_SESSION[access]){
include_once("../htmlheader.php");
echo
"<table align=\"center\" width=\"50%\" border=\"0\">
<tr>
<td align=\"center\"><span style=\"font-size: 0.9em\";>Keine g&uuml;ltige &Auml;nderung ! (Deine Rechte reichen nicht aus)</span></td>
</tr>
</table>
</body>
</html>";
}


if($_POST[change] == " Aktualisieren " && isset($_POST[supporter]) && isset($_POST[newaccess]) && ($_POST[newaccess] < $_SESSION[access])){
	if($_POST[is_active] == 0) $status_sup = "nicht aktiv";
	if($_POST[is_active] == 1) $status_sup = "aktiv";
	sql_query("UPDATE supporterdata SET access = '". addslashes($_POST[newaccess]) ."', active = '". addslashes($_POST[is_active]) ."'  WHERE supporter = '$_POST[supporter]'");
	// Eintrag in Supporter-Log
	sql_query("INSERT INTO logs_support (supporter, action, action_value, timestamp) VALUES ('$_SESSION[supporter]', 'Supporter', '<b>".$_POST[supporter]." </b>ist nun ".$status_sup."', '".time()."')");
	sql_query("INSERT INTO logs_support (supporter, action, action_value, timestamp) VALUES ('$_SESSION[supporter]', 'Supporter', '<b>".$_POST[supporter]." </b>hat Zugriff ".$_POST[newaccess]."', '".(time()+1)."')");
	// Ende Eintrag
	include_once("../htmlheader.php");
	echo
	"<table align=\"center\" width=\"50%\" border=\"0\">
	<tr>
	<td align=\"center\"><span style=\"font-size: 0.9em\";>Neuer Status von ".$_POST[supporter].":<br><i>Aktiv</i>: <b>".$status_sup."</b><br><i>Zugriff</i>: <b>".$_POST[newaccess]."</b></span></td>
	</tr>
	</table>
	</body>
	</html>";
	}

if(!isset($_POST[change])){
include_once("../htmlheader.php");
echo "<table width=\"80%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" align=\"center\">
	<tr>
	<td colspan=\"6\"><span style=\"font-size: 0.5em\";>&nbsp;</span></td>
	</tr>
	<tr>
	<td colspan=\"6\" align=\"center\"><span style=\"font-weight: bold\">Zugriffsrechte der Supporter</span><br><span style=\"font-size:0.8em\">(Level 90+ = Administrator)</span></td>
	</tr>
	<tr>
	<td colspan=\"6\"><span style=\"font-size: 0.5em\";>&nbsp;</span></td>
	</tr>
	<tr bgcolor=\"444444\">
	<th width=\"20%\" align=\"left\">Member</th>
	<th width=\"10%\" align=\"center\">Zugriff</th>
	<th width=\"10%\" align=\"center\">Aktiv</th>
	<th width=\"20%\" align=\"center\">Letzter Login</th>
	<th width=\"20%\" align=\"center\">Zugriff</th>
	<th width=\"20%\" align=\"center\"></th>
	</tr>";
	$result = sql_query("SELECT * FROM supporterdata ORDER by lastlogin DESC");
	while ($access = sql_fetch_array($result)) {
	if($access[access] >= $_SESSION[access]) $value = "value=\"".$access[access]."\" readonly=\"readonly\""; else $value = "value=\"".$access[access]."\"";
	if($access[active] == 1) $sel_is_active = "selected"; else $sel_not_active = "selected";
	echo
	"<form method=\"post\" action=\"access.php\">
	<tr>
		<td width=\"20%\" align=\"left\" class=\"liste\"><input type=\"hidden\" name=\"supporter\" value=\"".$access[supporter]."\">".$access[supporter]."</td>
		<td width=\"10%\" align=\"center\" class=\"liste\">".$access[access]."</td>
		<td width=\"10%\" align=\"center\">
			<select name=\"is_active\">
				<option value=\"1\" ".$sel_is_active.">Ja</option>
				<option value=\"0\" ".$sel_not_active.">Nein</option>
			</select>
		</td>
		<td width=\"20%\" align=\"center\" class=\"liste\">".date("d.m.Y H:m:s", $access[lastlogin])."</td>
		<td width=\"20%\" align=\"center\"><input type=\"text\" size=\"3\" name=\"newaccess\"  class=\"listeblack\" ".$value."></td>
		<td width=\"20%\"><input type=\"submit\" name=\"change\" class=\"listebutton\" value=\" Aktualisieren \"></td>
	</tr>
	</form>";
	unset($sel_is_active);
	unset($sel_not_active);
	}
echo "<tr>
	<td colspan=\"4\"><span style=\"font-size: 0.5em\";>&nbsp;</span></td>
	</tr>
</table>
</body>
</html>";
}
?>