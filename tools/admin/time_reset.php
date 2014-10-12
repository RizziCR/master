<?php
include_once("../rootsession.php");
echo 	"<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\">
		<html>
		<head>
		<title>Flotten eines Users</title>
		<link rel=\"stylesheet\" type=\"text/css\" href=\"../css.css\">
		</head>
		<body>
		<h2>Zeit zur&uuml;ckdrehen</h2>";
// Zeit (ganz oder teilweise) streichen, die seit dem Abschalten von ETS vergangen ist
echo "Zeit jetzt zur&uuml;ckdrehen bis (vermutlich zum Tag, an dem ETS vom Netz ging, oder sp&auml;ter):";
  echo "  <form action=\"{$_SERVER[PHP_SELF]}\" method=post>
    <input type=\"text\" class=\"textinput\" size=\"2\" maxlength=\"2\"
    name=\"Tag\" value=\"".($_POST[Tag]?$_POST[Tag]:"21")."\">.
    <input type=\"text\" class=\"textinput\" size=\"2\" maxlength=\"2\"
      name=\"Monat\" value=\"".($_POST[Monat]?$_POST[Monat]:"10")."\">.
    <input type=\"text\" class=\"textinput\" size=\"4\" maxlength=\"4\"
      name=\"Jahr\" value=\"".($_POST[Jahr]?$_POST[Jahr]:"2007")."\"> - 
    <input type=\"text\" class=\"textinput\" size=\"2\" maxlength=\"2\"
    name=\"Stunde\" value=\"".($_POST[Stunde]?$_POST[Stunde]:"16")."\">:
    <input type=\"text\" class=\"textinput\" size=\"2\" maxlength=\"2\"
      name=\"Minute\" value=\"".($_POST[Minute]?$_POST[Minute]:"00")."\">:
    <input type=\"text\" class=\"textinput\" size=\"2\" maxlength=\"2\"
      name=\"Sekunde\" value=\"".($_POST[Sekunde]?$_POST[Sekunde]:"00")."\">
    <br>
    <input type=submit value=Senden name=send>
      </form>";
   
if ($_POST[send])
{
        echo "Gesendet:<br>";
   $time = strtotime($_POST[Tag] . "-" . $_POST[Monat] . "-" . $_POST[Jahr] . " " . $_POST[Stunde] . ":" . $_POST[Minute] . ":" . $_POST[Sekunde]);
        echo "Zeit berechnet: $time.<br>";
  if ($time)
  {
        echo "Zeit gültig.<br>";
    $diff = time() - $time;
    if ($diff <= 0)
    {
      echo "<br>";
      echo "Die Zeit liegt in der Zukunft. Aktion nicht ausgeführt.";
    }
    else
    {
      require_once("database.php");
      $res = sql_query("UPDATE city SET r_time=r_time+$diff, r_time_oxygen=r_time_oxygen+$diff, b_end_time=if(b_end_time<>0,b_end_time+$diff,0), b_end_time_next=if(b_end_time_next,b_end_time_next+$diff,0);") and
      $res = sql_query("UPDATE usarios SET t_end_time=if(t_end_time<>0,t_end_time+$diff,0), login=login+$diff;") and
      sql_query("UPDATE actions SET f_start=f_start+$diff, f_arrival=if(f_arrival<>0,f_arrival+$diff,0);") and
      sql_query("UPDATE attack_denies SET time=time+$diff;") and
      sql_query("UPDATE _attack SET time=time+$diff;") and
      sql_query("UPDATE jobs_build SET end_time=end_time+$diff;") and
      sql_query("UPDATE jobs_tech SET end_time=end_time+$diff;") and
      sql_query("UPDATE jobs_planes SET end_time=end_time+$diff;") and
      sql_query("UPDATE jobs_defense SET end_time=end_time+$diff;") and
      sql_query("UPDATE news_ber SET time=time+$diff;") and
      sql_query("UPDATE news_er SET time=time+$diff;") and
      sql_query("UPDATE news_msg SET time=time+$diff;") and
      sql_query("UPDATE userdata SET del_acc=if(b_del_acc<>0,b_del_acc+$diff,0);");

      if ($res) {
        echo "Aktion erfolgreich ausgeführt.";
        sql_query("INSERT INTO logs_support (supporter, action, action_value, timestamp)
        VALUES ('$_SESSION[supporter]', 'System', '<b>Zeitberechnung aktualisiert </b> Zeit: $time', '".time()."')");
      }else{
        echo "Aktion konnte nicht ausgeführt werden, Fehler bei SQL-Operation.";
      }
    }
  }
}
?>
