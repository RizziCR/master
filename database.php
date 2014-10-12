<?php
  require_once("config.php");

  require_once("include/db_driver_mysql.php");
#  require_once("db_driver_db2.php");

  if (!isset($db))
  {
    $db = sql_connect($dbServer, $dbLogin, $dbPwd);
    if (!$db)
      die ("<<font color=\"#FF0000\">Fehler beim Konnektieren der Datenbank! Bitte versuchen Sie es in ein paar Minuten erneut.</font>><br><br>");
  }

  sql_select_db($dbName,$db);

  require_once("_banlist.php");

  //if (!preg_match("/\/(page)/",$_SERVER['PHP_SELF']) && !preg_match("/\/(tools)/",$_SERVER['PHP_SELF']))
  if (!strstr($_SERVER['PHP_SELF'],"/page/") && !strstr($_SERVER['PHP_SELF'], "/tools/"))
  {
    session_start();
  }
  $add_ir = null;
  $add_hz = null;
  $add_wa = null;
  $add_ox = null;

  $pay_iridium = null;
  $pay_holzium = null;
  $pay_oxygen = null;
  $pay_iridium_next = null;
  $pay_holzium_next = null;

  $duration = null;
  $technologie = null;
  $t_db_name = null;

  $abriss_query = null;
  $p_gesamt = null;
  $d_gesamt = null;

  require_once("functions.php");

   $bad_values = array(
    "t_mining","t_water_compression","t_depot_management","b_end_time",
    "b_end_time_next","b_current_build","b_next_build","b_iridium_mine","b_holzium_plantage","b_water_derrick",
    "b_oxygen_reactor","b_depot","b_oxygen_depot","b_trade_center","b_hangar","b_airport","b_defense_center","b_shield",
    "b_technologie_center","b_communication_center","b_work_board","d_electronwoofer","d_protonwoofer","d_neutronwoofer",
    "d_electronsequenzer","d_protonsequenzer","d_neutronsequenzer","p_sparrow","p_sparrow_gesamt","p_blackbird",
    "p_blackbird_gesamt","p_raven","p_raven_gesamt","p_eagle","p_eagle_gesamt","p_falcon","p_falcon_gesamt",
    "p_nightingale","p_nightingale_gesamt","p_ravager","p_ravager_gesamt","p_destroyer","p_destroyer_gesamt",
    "p_espionage_probe","p_espionage_probe_gesamt","p_settler","p_settler_gesamt","p_scarecrow","p_scarecrow_gesamt",
    "p_bomber","p_bomber_gesamt","p_small_transp_orter","p_small_transp_orter_gesamt","p_medium_transp_orter",
    "p_medium_transporter_gesamt","p_big_transporter","p_big_transporter_gesamt","p_gesamt_flugzeuge",
    "t_oxidationsdrive","t_hoverdrive","t_antigravitydrive","t_electronsequenzweapons","t_protonsequenzweapons",
    "t_neutronsequenzweapons","t_consumption_reduction","t_plane_size","t_computer_management","t_depot_management",
    "t_water_compression","t_mining","t_shield_tech");

  foreach ($_REQUEST as $bad_key => $bad_value) {
    foreach ($bad_values as $bad_word) {
      if (!(strpos(strtolower($bad_value), $bad_word) === false)) {
        smtp_mail($stewardEmail,"word hack", "User: $_SESSION[user] ::: Key: $bad_key ::: Value: $bad_value ::: File:" . $_SERVER['PHP_SELF']);
        die();
      }
    }
  }
?>
