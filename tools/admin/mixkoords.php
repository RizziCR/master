<?php
  /**
   * Delete all old city coordinates and recolonize the world as if the cities are all just beeing
   * founded.
   * Coordinates in all DB tables are NOT updated.
   * Avoid using it if colonies have already been founded.
   */

  require_once("database.php");
  require_once("functions.php");

// remove all city coordinates
  sql_query("UPDATE city set x_pos=0, y_pos=0, z_pos=0 WHERE user <> 'Tutorial'");
  echo "deleted city coordinates\n";
// compute new coordinates
  $get_cities = sql_query("SELECT * FROM city WHERE user <> 'Tutorial'");
  while ($city = sql_fetch_array($get_cities)) {
    list($x,$y,$z) = get_new_standard_coordinates();
    // try again
    if ($x==0 && $y==0 && $z==0) {
        list($x,$y,$z) = get_new_standard_coordinates();
    }
    //$query = sprintf("UPDATE city SET city='%d:%d:%d',x_pos='%d',y_pos='%d',z_pos='%d' WHERE city='%s'", $x, $y, $z, $x, $y, $z, $city['city']);
    // assign new city coordinates but keep city string
    $query = sprintf("UPDATE city SET x_pos='%d',y_pos='%d',z_pos='%d' WHERE user <> 'Tutorial' AND city='%s'", $x, $y, $z, $city['city']);
    echo $query."\n";
    sql_query($query);
  }
  // rename fleet origin cities
  $query = "UPDATE actions AS a, city as c SET a.city=CONCAT(x_pos, CONCAT(':',CONCAT(y_pos, CONCAT(':',z_pos)))) WHERE a.city=c.city";
  sql_query($query);
  // rename fleet destination cities
  $query = "UPDATE actions AS a, city as c SET a.f_target=CONCAT(x_pos, CONCAT(':',CONCAT(y_pos, CONCAT(':',z_pos)))) WHERE a.f_target=c.city";
  sql_query($query);
  // rename build jobs
  $query = "UPDATE jobs_build AS j, city as c SET j.city=CONCAT(x_pos, CONCAT(':',CONCAT(y_pos, CONCAT(':',z_pos)))), j.msg=replace(j.msg,c.city,CONCAT(x_pos, CONCAT(':',CONCAT(y_pos, CONCAT(':',z_pos))))) WHERE j.city=c.city";
  sql_query($query);
  // rename def jobs
  $query = "UPDATE jobs_defense AS j, city as c SET j.city=CONCAT(x_pos, CONCAT(':',CONCAT(y_pos, CONCAT(':',z_pos)))), j.msg=replace(j.msg,c.city,CONCAT(x_pos, CONCAT(':',CONCAT(y_pos, CONCAT(':',z_pos))))) WHERE j.city=c.city";
  sql_query($query);
  // rename plane jobs
  $query = "UPDATE jobs_planes AS j, city as c SET j.city=CONCAT(x_pos, CONCAT(':',CONCAT(y_pos, CONCAT(':',z_pos)))), j.msg=replace(j.msg,c.city,CONCAT(x_pos, CONCAT(':',CONCAT(y_pos, CONCAT(':',z_pos))))) WHERE j.city=c.city";
  sql_query($query);
  // rename city history
  $query = "TRUNCATE new_city_history";
  sql_query($query);
  $query = "INSERT INTO new_city_history (city,owner,time) SELECT city,owner,time FROM city_history";
  sql_query($query);
  $query = "TRUNCATE city_history";
  sql_query($query);
  $query = "INSERT INTO city_history (city,owner,time) SELECT CONCAT(x_pos, CONCAT(':',CONCAT(y_pos, CONCAT(':',z_pos)))),owner,time FROM new_city_history AS h, city as c WHERE h.city=c.city";
  sql_query($query);
  // current tech origin city
  $query = "UPDATE usarios AS n, city as c SET n.t_start_city=CONCAT(x_pos, CONCAT(':',CONCAT(y_pos, CONCAT(':',z_pos)))) WHERE n.t_start_city=c.city";
  sql_query($query);
  // prebooked tech origin city
  $query = "UPDATE usarios AS n, city as c SET n.t_start_city_next=CONCAT(x_pos, CONCAT(':',CONCAT(y_pos, CONCAT(':',z_pos)))) WHERE n.t_start_city_next=c.city";
  sql_query($query);
  // rename city events
  $query = "UPDATE news_er AS n, city as c SET n.city=CONCAT(x_pos, CONCAT(':',CONCAT(y_pos, CONCAT(':',z_pos)))) WHERE n.city=c.city";
  sql_query($query);
  // rename fleet events?
  # Disabled for the first..... Problem with new report save
  
  #$query = "UPDATE news_ber AS n, city as c SET n.origin=CONCAT(x_pos, CONCAT(':',CONCAT(y_pos,
  #CONCAT(':',z_pos)))) WHERE n.origin=c.city";
  #sql_query($query);
  // rename city toplist
  $query = "UPDATE toplist_city AS t, city as c SET t.city=CONCAT(x_pos, CONCAT(':',CONCAT(y_pos, CONCAT(':',z_pos)))) WHERE t.city=c.city";
  sql_query($query);

  // synchronize city coordinates with city string
  $query = "TRUNCATE city_tmp";
  sql_query($query);
  $query = "INSERT INTO city_tmp (`user`, `city`, `home`, `x_pos`, `y_pos`, `z_pos`, `city_name`, `text`, `pic`, `alliance`, `points`, `foundation`, `r_time`, `r_time_oxygen`, `r_iridium`, `r_iridium_add`, `r_holzium`, `r_holzium_add`, `r_water`, `r_water_add`, `r_oxygen`, `r_oxygen_add`, `t_mining`, `t_water_compression`, `t_depot_management`, `b_end_time`, `b_end_time_next`, `b_current_build`, `b_next_build`, `b_iridium_mine`, `b_holzium_plantage`, `b_water_derrick`, `b_oxygen_reactor`, `b_depot`, `b_oxygen_depot`, `b_trade_center`, `b_hangar`, `b_airport`, `b_defense_center`, `b_shield`, `b_technologie_center`, `b_communication_center`, `b_work_board`, `d_electronwoofer`, `d_protonwoofer`, `d_neutronwoofer`, `d_electronsequenzer`, `d_protonsequenzer`, `d_neutronsequenzer`, `p_sparrow`, `p_sparrow_gesamt`, `p_blackbird`, `p_blackbird_gesamt`, `p_raven`, `p_raven_gesamt`, `p_eagle`, `p_eagle_gesamt`, `p_falcon`, `p_falcon_gesamt`, `p_nightingale`, `p_nightingale_gesamt`, `p_ravager`, `p_ravager_gesamt`, `p_destroyer`, `p_destroyer_gesamt`, `p_espionage_probe`, `p_espionage_probe_gesamt`, `p_settler`, `p_settler_gesamt`, `p_scarecrow`, `p_scarecrow_gesamt`, `p_bomber`, `p_bomber_gesamt`, `p_small_transporter`, `p_small_transporter_gesamt`, `p_medium_transporter`, `p_medium_transporter_gesamt`, `p_big_transporter`, `p_big_transporter_gesamt`, `p_gesamt_flugzeuge`, `c_shield_timer`, `c_active_shields`, `blubb`, `msg`, `msg_next`, `pos`, `special`) SELECT * FROM city";
  sql_query($query);
  $query = "TRUNCATE city";
  sql_query($query);
  $query = "INSERT INTO city (`user`, `city`, `home`, `x_pos`, `y_pos`, `z_pos`, `city_name`, `text`, `pic`, `alliance`, `points`, `foundation`, `r_time`, `r_time_oxygen`, `r_iridium`, `r_iridium_add`, `r_holzium`, `r_holzium_add`, `r_water`, `r_water_add`, `r_oxygen`, `r_oxygen_add`, `t_mining`, `t_water_compression`, `t_depot_management`, `b_end_time`, `b_end_time_next`, `b_current_build`, `b_next_build`, `b_iridium_mine`, `b_holzium_plantage`, `b_water_derrick`, `b_oxygen_reactor`, `b_depot`, `b_oxygen_depot`, `b_trade_center`, `b_hangar`, `b_airport`, `b_defense_center`, `b_shield`, `b_technologie_center`, `b_communication_center`, `b_work_board`, `d_electronwoofer`, `d_protonwoofer`, `d_neutronwoofer`, `d_electronsequenzer`, `d_protonsequenzer`, `d_neutronsequenzer`, `p_sparrow`, `p_sparrow_gesamt`, `p_blackbird`, `p_blackbird_gesamt`, `p_raven`, `p_raven_gesamt`, `p_eagle`, `p_eagle_gesamt`, `p_falcon`, `p_falcon_gesamt`, `p_nightingale`, `p_nightingale_gesamt`, `p_ravager`, `p_ravager_gesamt`, `p_destroyer`, `p_destroyer_gesamt`, `p_espionage_probe`, `p_espionage_probe_gesamt`, `p_settler`, `p_settler_gesamt`, `p_scarecrow`, `p_scarecrow_gesamt`, `p_bomber`, `p_bomber_gesamt`, `p_small_transporter`, `p_small_transporter_gesamt`, `p_medium_transporter`, `p_medium_transporter_gesamt`, `p_big_transporter`, `p_big_transporter_gesamt`, `p_gesamt_flugzeuge`, `c_shield_timer`, `c_active_shields`, `blubb`, `msg`, `msg_next`, `pos`, `special`) SELECT `user`,CONCAT(x_pos, CONCAT(':',CONCAT(y_pos, CONCAT(':',z_pos)))), `home`, `x_pos`, `y_pos`, `z_pos`, `city_name`, `text`, `pic`, `alliance`, `points`, `foundation`, `r_time`, `r_time_oxygen`, `r_iridium`, `r_iridium_add`, `r_holzium`, `r_holzium_add`, `r_water`, `r_water_add`, `r_oxygen`, `r_oxygen_add`, `t_mining`, `t_water_compression`, `t_depot_management`, `b_end_time`, `b_end_time_next`, `b_current_build`, `b_next_build`, `b_iridium_mine`, `b_holzium_plantage`, `b_water_derrick`, `b_oxygen_reactor`, `b_depot`, `b_oxygen_depot`, `b_trade_center`, `b_hangar`, `b_airport`, `b_defense_center`, `b_shield`, `b_technologie_center`, `b_communication_center`, `b_work_board`, `d_electronwoofer`, `d_protonwoofer`, `d_neutronwoofer`, `d_electronsequenzer`, `d_protonsequenzer`, `d_neutronsequenzer`, `p_sparrow`, `p_sparrow_gesamt`, `p_blackbird`, `p_blackbird_gesamt`, `p_raven`, `p_raven_gesamt`, `p_eagle`, `p_eagle_gesamt`, `p_falcon`, `p_falcon_gesamt`, `p_nightingale`, `p_nightingale_gesamt`, `p_ravager`, `p_ravager_gesamt`, `p_destroyer`, `p_destroyer_gesamt`, `p_espionage_probe`, `p_espionage_probe_gesamt`, `p_settler`, `p_settler_gesamt`, `p_scarecrow`, `p_scarecrow_gesamt`, `p_bomber`, `p_bomber_gesamt`, `p_small_transporter`, `p_small_transporter_gesamt`, `p_medium_transporter`, `p_medium_transporter_gesamt`, `p_big_transporter`, `p_big_transporter_gesamt`, `p_gesamt_flugzeuge`, `c_shield_timer`, `c_active_shields`, `blubb`, `msg`, `msg_next`, `pos`, `special` FROM city_tmp";
  sql_query($query);

?>
