<?php
include("../session.php");
include_once("../htmlheader.php");
if($_POST[apply] !== "Ja"){
	echo "Punkte f&uuml;r ETS wirklich komplett neu berechnen (<span class=\"important\">Achtung, ressourcenintensiv !</span>) ?
	<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">
	<input type=\"submit\" name=\"apply\" value=\"Ja\">
	</form>";
} ELSE {
    /* Fuer alle Stadte und Accounts die Punkte neu berechnen */

  @set_time_limit(10000);

  include("database.php");

	sql_query("UPDATE city SET points=b_iridium_mine+b_holzium_plantage+b_water_derrick+b_oxygen_reactor+b_depot+b_oxygen_depot+b_trade_center+b_hangar+b_airport+b_defense_center+b_shield+b_technologie_center+b_communication_center+b_work_board");
	sql_query("UPDATE usarios SET points=0,tech_points=t_oxidationsdrive+t_hoverdrive+t_antigravitydrive+t_electronsequenzweapons+t_protonsequenzweapons+t_neutronsequenzweapons+t_consumption_reduction+t_computer_management+t_water_compression+t_depot_management+t_mining+t_plane_size+t_shield_tech");
	sql_query("UPDATE alliances SET members=0,points=0");
	// Eintrag in Support-Log
	sql_query("INSERT INTO logs_support (supporter, action, action_value, timestamp)
			VALUES ('$_SESSION[supporter]', 'System', '<b>Punkte </b>neu berechnet', '".time()."')");
	// Ende Eintrag

  $res = sql_query("SELECT user,points FROM city");
  while ($lala = sql_fetch_array($res))
    sql_query("UPDATE usarios SET points=points+$lala[points] WHERE ID='$lala[user]'");

  $res = sql_query("SELECT user,tech_points FROM usarios");
  while ($lala = sql_fetch_array($res))
    sql_query("UPDATE usarios SET points=points+$lala[tech_points] WHERE user='$lala[user]'");

  $res = sql_query("SELECT points,alliance FROM usarios");
  while ($lala = sql_fetch_array($res))
    sql_query("UPDATE alliances SET points=points+$lala[points] WHERE ID='$lala[alliance]'");

  $res = sql_query("SELECT alliance,count(*) AS members FROM usarios WHERE alliance!='' GROUP BY alliance");
  while ($lala = sql_fetch_array($res))
    sql_query("UPDATE alliances SET members=$lala[members] WHERE ID='$lala[alliance]'");

  echo "Fertig!";
echo "</body>
</html>";
}
?>
