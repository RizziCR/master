<?php

  require_once("database.php");
  require_once("functions.php");
  require_once("do_loop.php");

  require_once "include/class_Lager.php";

  $q = sql_query("SELECT ID,user FROM city WHERE city='".htmlspecialchars($_GET['city'],ENT_QUOTES)."' AND user='".$_SESSION[user]."'");
  if(! sql_num_rows($q))
    die('Keine Berechtigung!');
?>
<html>
<body>
<?php
  $query = sql_fetch_array($q);
  $citylager = new Lager($query['ID']);
  $get_user_cities = sql_query("SELECT end_time FROM jobs_build WHERE city='$query[ID]' ORDER BY end_time DESC LIMIT 1");
  $h_data = sql_fetch_array($get_user_cities);
  $get_job_planes = sql_query("SELECT end_time FROM jobs_planes WHERE city='$query[ID]' ORDER BY end_time DESC LIMIT 1");
  $p_data = sql_fetch_array($get_job_planes);
  $get_job_defense = sql_query("SELECT end_time FROM jobs_defense WHERE city='$query[ID]' ORDER BY end_time DESC LIMIT 1");
  $d_data = sql_fetch_array($get_job_defense);

  echo "<strong>Stadt: ".htmlspecialchars($_GET['city'],ENT_QUOTES)."</strong><br />";
  echo "<br />";
  echo "<table>";
  echo "<tr><td align='right'>".number_format($citylager->getIridium(), 0, ',', '.')."</td><td> Iridium</td></tr>";
  echo "<tr><td align='right'>".number_format($citylager->getHolzium(), 0, ',', '.')."</td><td> Holzium</td></tr>";
  echo "<tr><td align='right'>".number_format($citylager->getWater(), 0, ',', '.')."</td><td> Wasser</td></tr>";
  echo "<tr><td align='right' style=\"padding-bottom:10px;\">".number_format($citylager->getOxygen(), 0, ',', '.')."</td><td style=\"padding-bottom:10px;\"> Sauerstoff</td></tr>";
  echo "<tr><td align='right'>".round($citylager->fillLevelPercent(),2)." %</td><td> Lager</td></tr>";
  echo "<tr><td align='right' style=\"padding-bottom:10px;\">".round($citylager->fillLevelOxygenPercent(),2)." %</td><td style=\"padding-bottom:10px;\"> Tank</td></tr>";
  echo "<tr><td align='right'>".maketime($h_data[end_time]-time())."</td><td> bis Geb&auml;ude fertig</td></tr>";
  echo "<tr><td align='right'>".maketime($p_data[end_time]-time())."</td><td> bis Flugzeuge fertig</td></tr>";
  echo "<tr><td align='right'>".maketime($d_data[end_time]-time())."</td><td> bis T&uuml;rme fertig</td></tr>";

  echo "</table>"
?>
</body>
</html>
